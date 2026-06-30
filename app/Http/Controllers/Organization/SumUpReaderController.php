<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Organization;
use App\Models\SumUpReader;
use App\Services\SumUpReaderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SumUpReaderController extends Controller
{
    public function __construct(private SumUpReaderService $readers) {}

    public function index(): View|RedirectResponse
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('info', 'Please complete your organization profile first.');
        }

        if (!$organization->isSumUpConnected() && $organization->sumup_connection_status !== 'unverified') {
            return redirect()->route('organization.sumup.show')
                ->with('error', 'Please connect your SumUp account before pairing a card reader.');
        }

        $readers = $organization->sumupReaders()->orderByDesc('id')->get();

        return view('organization.sumup.readers', compact('organization', 'readers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;

        if (!$organization || !$organization->sumup_api_key || !$organization->sumup_merchant_code) {
            return redirect()->route('organization.sumup.show')
                ->with('error', 'Please connect your SumUp account first.');
        }

        $validated = $request->validate([
            'pairing_code' => ['required', 'string', 'min:4', 'max:16', 'regex:/^[A-Za-z0-9-]+$/'],
            'name' => ['required', 'string', 'min:2', 'max:60'],
        ], [
            'pairing_code.required' => 'Enter the pairing code shown on the Solo terminal.',
            'pairing_code.regex' => 'Pairing code may only contain letters, digits and dashes.',
            'name.required' => 'Give this reader a name (e.g. "Front desk").',
        ]);

        $pairingCode = strtoupper(trim($validated['pairing_code']));

        $result = $this->readers->createReader(
            $organization->sumup_api_key,
            $organization->sumup_merchant_code,
            $pairingCode,
            $validated['name'],
        );

        if (!$result['ok']) {
            return back()->withErrors(['pairing_code' => $result['error']])->withInput();
        }

        $data = $result['data'];

        $reader = $organization->sumupReaders()->create([
            'sumup_reader_id' => data_get($data, 'id'),
            'name' => data_get($data, 'name', $validated['name']),
            'pairing_code' => $pairingCode,
            'status' => data_get($data, 'status', 'paired'),
            'device_model' => data_get($data, 'device.model'),
            'device_serial_number' => data_get($data, 'device.serial_number'),
            'last_seen_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'organization_id' => $organization->id,
            'action' => 'created',
            'model_type' => SumUpReader::class,
            'model_id' => $reader->id,
            'description' => "SumUp reader paired: {$reader->name} ({$reader->sumup_reader_id})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('completed', "Reader '{$reader->name}' paired successfully.");
    }

    public function destroy(SumUpReader $reader, Request $request): RedirectResponse
    {
        $organization = Auth::user()->organization;

        if (!$organization || $reader->organization_id !== $organization->id) {
            abort(403);
        }

        if ($organization->sumup_api_key && $organization->sumup_merchant_code) {
            $result = $this->readers->deleteReader(
                $organization->sumup_api_key,
                $organization->sumup_merchant_code,
                $reader->sumup_reader_id,
            );

            // Soft-delete locally even if SumUp was unreachable so the org isn't stuck.
            if (!$result['ok'] && ($result['reason'] ?? null) === 'rejected') {
                return back()->with('error', $result['error']);
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'organization_id' => $organization->id,
            'action' => 'deleted',
            'model_type' => SumUpReader::class,
            'model_id' => $reader->id,
            'description' => "SumUp reader unpaired: {$reader->name} ({$reader->sumup_reader_id})",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $reader->delete();

        return back()->with('completed', 'Reader unpaired.');
    }
}
