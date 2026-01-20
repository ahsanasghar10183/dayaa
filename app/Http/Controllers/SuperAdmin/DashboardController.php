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
    public function index(): View
    {
        // Get key statistics
        $stats = [
            'total_organizations' => Organization::count(),
            'pending_organizations' => Organization::where('status', 'pending')->count(),
            'active_organizations' => Organization::where('status', 'active')->count(),
            'suspended_organizations' => Organization::where('status', 'suspended')->count(),

            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),

            'total_devices' => Device::count(),
            'online_devices' => Device::where('status', 'online')->count(),

            'total_donations' => Donation::where('payment_status', 'success')->count(),
            'total_donations_amount' => Donation::where('payment_status', 'success')->sum('amount'),
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

        // Get monthly donation trends (last 6 months)
        $monthlyDonations = Donation::where('payment_status', 'success')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(amount) as total')
            ->where('created_at', '>=', now()->subMonths(6))
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
}
