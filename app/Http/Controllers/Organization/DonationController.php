<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationController extends Controller
{
    /**
     * Display a listing of donations for the organization
     */
    public function index(Request $request): View
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('info', 'Please complete your organization profile first.');
        }

        // Get donations with filters
        $query = $organization->donations()
            ->with(['campaign', 'device'])
            ->where('payment_status', 'completed');

        // Filter by campaign if requested
        if ($request->has('campaign') && $request->campaign) {
            $query->where('campaign_id', $request->campaign);
        }

        // Filter by device if requested
        if ($request->has('device') && $request->device) {
            $query->where('device_id', $request->device);
        }

        // Filter by date range if requested
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->latest()->paginate(20);

        // Get statistics
        $stats = [
            'total_donations' => $organization->donations()->where('payment_status', 'completed')->count(),
            'total_amount' => $organization->donations()->where('payment_status', 'completed')->sum('amount'),
            'today_donations' => $organization->donations()
                ->where('payment_status', 'completed')
                ->whereDate('created_at', today())
                ->count(),
            'today_amount' => $organization->donations()
                ->where('payment_status', 'completed')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'this_month_donations' => $organization->donations()
                ->where('payment_status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_month_amount' => $organization->donations()
                ->where('payment_status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        // Get campaigns and devices for filters
        $campaigns = $organization->campaigns()->get();
        $devices = $organization->devices()->get();

        return view('organization.donations.index', compact(
            'donations',
            'stats',
            'campaigns',
            'devices'
        ));
    }
}
