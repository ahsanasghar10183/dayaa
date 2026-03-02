<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Campaign;
use App\Models\Device;
use App\Models\Donation;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the Super Admin dashboard
     */
    public function index(Request $request): View
    {
        // Determine date range based on filter
        $period = $request->get('period', 'last_6_months');
        $dateRange = $this->getDateRange($period, $request->get('start_date'), $request->get('end_date'));

        // Get key statistics (filtered by date range where applicable)
        $stats = [
            'total_organizations' => Organization::count(),
            'pending_organizations' => Organization::where('status', 'pending')->count(),
            'active_organizations' => Organization::where('status', 'active')->count(),
            'suspended_organizations' => Organization::where('status', 'suspended')->count(),

            'total_campaigns' => Campaign::whereBetween('created_at', $dateRange)->count(),
            'active_campaigns' => Campaign::where('status', 'active')
                ->whereBetween('created_at', $dateRange)
                ->count(),

            'total_devices' => Device::whereBetween('created_at', $dateRange)->count(),
            'online_devices' => Device::where('status', 'online')->count(),

            'total_donations' => Donation::where('payment_status', 'success')
                ->whereBetween('created_at', $dateRange)
                ->count(),
            'total_donations_amount' => Donation::where('payment_status', 'success')
                ->whereBetween('created_at', $dateRange)
                ->sum('amount'),
            'donations_today' => Donation::where('payment_status', 'success')
                ->whereDate('created_at', today())
                ->count(),
            'donations_today_amount' => Donation::where('payment_status', 'success')
                ->whereDate('created_at', today())
                ->sum('amount'),

            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'premium_subscriptions' => Subscription::where('status', 'active')
                ->where('plan', 'premium')
                ->count(),
            'basic_subscriptions' => Subscription::where('status', 'active')
                ->where('plan', 'basic')
                ->count(),
        ];

        // Get recent organizations pending approval
        $pendingOrganizations = Organization::where('status', 'pending')
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        // Get recent donations
        $recentDonations = Donation::where('payment_status', 'success')
            ->with(['organization', 'campaign', 'device'])
            ->latest()
            ->limit(10)
            ->get();

        // Get monthly donation trends (based on selected period)
        $monthlyDonations = Donation::where('payment_status', 'success')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total')
            ->whereBetween('created_at', $dateRange)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('super-admin.dashboard', compact(
            'stats',
            'pendingOrganizations',
            'recentDonations',
            'monthlyDonations'
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
                $startDate ? \Carbon\Carbon::parse($startDate)->startOfDay() : now()->subMonths(6)->startOfDay(),
                $endDate ? \Carbon\Carbon::parse($endDate)->endOfDay() : now()->endOfDay()
            ],
            default => [
                now()->subMonths(6)->startOfDay(),
                now()->endOfDay()
            ]
        };
    }
}
