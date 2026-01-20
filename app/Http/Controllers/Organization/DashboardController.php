<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the organization dashboard
     */
    public function index()
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('info', 'Please complete your organization profile to get started.');
        }

        // Get key statistics
        $stats = [
            'total_campaigns' => $organization->campaigns()->count(),
            'active_campaigns' => $organization->campaigns()->where('status', 'active')->count(),
            'inactive_campaigns' => $organization->campaigns()->where('status', 'inactive')->count(),

            'total_devices' => $organization->devices()->count(),
            'online_devices' => $organization->devices()->where('status', 'online')->count(),
            'offline_devices' => $organization->devices()->where('status', 'offline')->count(),

            'total_donations' => $organization->donations()->where('payment_status', 'success')->count(),
            'total_amount' => $organization->donations()->where('payment_status', 'success')->sum('amount'),

            'donations_today' => $organization->donations()
                ->where('payment_status', 'success')
                ->whereDate('created_at', today())
                ->count(),
            'donations_today_amount' => $organization->donations()
                ->where('payment_status', 'success')
                ->whereDate('created_at', today())
                ->sum('amount'),

            'this_month_donations' => $organization->donations()
                ->where('payment_status', 'success')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_month_amount' => $organization->donations()
                ->where('payment_status', 'success')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        // Get recent donations
        $recentDonations = $organization->donations()
            ->where('payment_status', 'success')
            ->with(['campaign', 'device'])
            ->latest()
            ->limit(10)
            ->get();

        // Get active campaigns
        $activeCampaigns = $organization->campaigns()
            ->where('status', 'active')
            ->withCount(['donations' => function ($query) {
                $query->where('payment_status', 'success');
            }])
            ->latest()
            ->limit(5)
            ->get();

        // Get subscription info
        $subscription = $organization->subscription;

        // Get daily donation trends (last 7 days)
        $dailyDonations = $organization->donations()
            ->where('payment_status', 'success')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(amount) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('organization.dashboard', compact(
            'organization',
            'stats',
            'recentDonations',
            'activeCampaigns',
            'subscription',
            'dailyDonations'
        ));
    }
}
