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
    public function index(Request $request)
    {
        $organization = auth()->user()->organization;

        if (!$organization) {
            return redirect()->route('organization.profile.create')
                ->with('info', 'Please complete your organization profile to get started.');
        }

        // Determine date range based on filter
        $period = $request->get('period', 'this_month');
        $dateRange = $this->getDateRange($period, $request->get('start_date'), $request->get('end_date'));

        // Get key statistics (filtered by date range where applicable)
        $stats = [
            'total_campaigns' => $organization->campaigns()->count(),
            'active_campaigns' => $organization->campaigns()->where('status', 'active')->count(),
            'inactive_campaigns' => $organization->campaigns()->where('status', 'inactive')->count(),

            'total_devices' => $organization->devices()->count(),
            'online_devices' => $organization->devices()->where('status', 'online')->count(),
            'offline_devices' => $organization->devices()->where('status', 'offline')->count(),

            'total_donations' => $organization->donations()
                ->where('payment_status', 'success')
                ->whereBetween('created_at', $dateRange)
                ->count(),
            'total_amount' => $organization->donations()
                ->where('payment_status', 'success')
                ->whereBetween('created_at', $dateRange)
                ->sum('amount'),

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

        // Get recent donations (filtered)
        $recentDonations = $organization->donations()
            ->where('payment_status', 'success')
            ->whereBetween('created_at', $dateRange)
            ->with(['campaign', 'device'])
            ->latest()
            ->limit(10)
            ->get();

        // Get active campaigns
        $activeCampaigns = $organization->campaigns()
            ->where('status', 'active')
            ->withCount(['donations' => function ($query) use ($dateRange) {
                $query->where('payment_status', 'success')
                    ->whereBetween('created_at', $dateRange);
            }])
            ->latest()
            ->limit(5)
            ->get();

        // Get subscription info
        $subscription = $organization->subscription;

        // Get donation trends based on selected period
        $dailyDonations = $organization->donations()
            ->where('payment_status', 'success')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(amount) as total')
            ->whereBetween('created_at', $dateRange)
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

    /**
     * Get date range based on period filter
     */
    private function getDateRange(string $period, $startDate = null, $endDate = null): array
    {
        return match ($period) {
            'today' => [
                now()->startOfDay(),
                now()->endOfDay()
            ],
            'this_month' => [
                now()->startOfMonth(),
                now()->endOfMonth()
            ],
            'last_month' => [
                now()->subMonth()->startOfMonth(),
                now()->subMonth()->endOfMonth()
            ],
            'last_6_months' => [
                now()->subMonths(6)->startOfDay(),
                now()->endOfDay()
            ],
            'last_year' => [
                now()->subYear()->startOfDay(),
                now()->endOfDay()
            ],
            'custom' => [
                $startDate ? \Carbon\Carbon::parse($startDate)->startOfDay() : now()->startOfMonth()->startOfDay(),
                $endDate ? \Carbon\Carbon::parse($endDate)->endOfDay() : now()->endOfDay()
            ],
            default => [
                now()->startOfMonth(),
                now()->endOfDay()
            ]
        };
    }
}
