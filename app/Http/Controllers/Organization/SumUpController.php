<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Organization;
use App\Services\SumUpAccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SumUpController extends Controller
{
    public function __construct(private SumUpAccountService $sumup) {}

    public function show(): View|RedirectResponse
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('info', 'Please complete your organization profile first.');
        }

        return view('organization.sumup.connect', compact('organization'));
    }

    public function store(Request $request): RedirectResponse
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('error', 'Please complete your organization profile first.');
        }

        $validated = $request->validate([
            'sumup_api_key' => ['required', 'string', 'min:20', 'max:255', 'regex:/^sup_sk_[A-Za-z0-9]+$/'],
            'sumup_merchant_code' => ['nullable', 'string', 'max:32', 'regex:/^M[A-Za-z0-9]+$/'],
        ], [
            'sumup_api_key.required' => 'Please paste your SumUp API key.',
            'sumup_api_key.regex' => 'The API key must start with "sup_sk_" followed by the secret value.',
            'sumup_merchant_code.regex' => 'Merchant code must start with "M" followed by letters/digits.',
        ]);

        $result = $this->sumup->verifyApiKey($validated['sumup_api_key']);

        // Happy path: SumUp verified the key online and returned the merchant code.
        if ($result['ok']) {
            $organization->update([
                'sumup_api_key' => $validated['sumup_api_key'],
                'sumup_merchant_code' => $result['merchant_code'],
                'sumup_merchant_name' => $result['merchant_name'] ?? null,
                'sumup_connection_status' => 'connected',
                'sumup_connected_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'organization_id' => $organization->id,
                'action' => 'connected',
                'model_type' => Organization::class,
                'model_id' => $organization->id,
                'description' => 'SumUp account connected (merchant ' . $result['merchant_code'] . ')',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('completed', 'SumUp account connected successfully.');
        }

        // SumUp explicitly rejected the key — never save it.
        if (($result['reason'] ?? null) === 'rejected') {
            return back()->withErrors(['sumup_api_key' => $result['error']])->withInput();
        }

        // SumUp was unreachable (geo/CDN block or network). If the user supplied the merchant
        // code manually, save the credentials with status=unverified so they can be re-tested
        // from a reachable environment (e.g. production server) via the "Test connection" button.
        if (($result['reason'] ?? null) === 'unreachable' && !empty($validated['sumup_merchant_code'])) {
            $organization->update([
                'sumup_api_key' => $validated['sumup_api_key'],
                'sumup_merchant_code' => $validated['sumup_merchant_code'],
                'sumup_merchant_name' => null,
                'sumup_connection_status' => 'unverified',
                'sumup_connected_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'organization_id' => $organization->id,
                'action' => 'connected',
                'model_type' => Organization::class,
                'model_id' => $organization->id,
                'description' => 'SumUp account saved unverified (merchant ' . $validated['sumup_merchant_code'] . ')',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return back()->with('completed', 'SumUp credentials saved. They were not verified with SumUp online (the API was unreachable from this server). Please run "Test connection" once deployed to verify.');
        }

        // Unreachable but no manual merchant code — ask the user to provide one.
        if (($result['reason'] ?? null) === 'unreachable') {
            return back()
                ->with('error', $result['error'] . ' To save the key anyway, please enter your SumUp merchant code (visible top-right at me.sumup.com) in the "Merchant code (manual)" field below.')
                ->withInput();
        }

        return back()->withErrors(['sumup_api_key' => $result['error'] ?? 'Could not verify the API key.'])->withInput();
    }

    public function test(): RedirectResponse
    {
        $organization = auth()->user()->organization;

        if (!$organization || !$organization->sumup_api_key) {
            return back()->with('error', 'No SumUp account connected yet.');
        }

        $result = $this->sumup->verifyApiKey($organization->sumup_api_key);

        if (!$result['ok']) {
            $organization->update(['sumup_connection_status' => 'invalid']);
            return back()->with('error', 'SumUp connection failed: ' . $result['error']);
        }

        $organization->update([
            'sumup_merchant_code' => $result['merchant_code'],
            'sumup_merchant_name' => $result['merchant_name'] ?? $organization->sumup_merchant_name,
            'sumup_connection_status' => 'connected',
            'sumup_connected_at' => now(),
        ]);

        return back()->with('completed', 'SumUp connection is healthy (merchant ' . $result['merchant_code'] . ').');
    }

    public function destroy(): RedirectResponse
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return back()->with('error', 'No organization profile.');
        }

        $previousMerchant = $organization->sumup_merchant_code;

        $organization->update([
            'sumup_api_key' => null,
            'sumup_merchant_code' => null,
            'sumup_merchant_name' => null,
            'sumup_connection_status' => 'disconnected',
            'sumup_connected_at' => null,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'organization_id' => $organization->id,
            'action' => 'disconnected',
            'model_type' => Organization::class,
            'model_id' => $organization->id,
            'description' => 'SumUp account disconnected' . ($previousMerchant ? " (was {$previousMerchant})" : ''),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('completed', 'SumUp account disconnected.');
    }
}
