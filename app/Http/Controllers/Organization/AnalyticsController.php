<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard with charts and visualizations
     */
    public function index()
    {
        $organization = auth()->user()->organization;

        // KPI Stats - Today vs Yesterday
        $todayStart = Carbon::today();
        $todayEnd = Carbon::now();
        $yesterdayStart = Carbon::yesterday()->startOfDay();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();

        $todayStats = $this->getPeriodStats($organization->id, $todayStart, $todayEnd);
        $yesterdayStats = $this->getPeriodStats($organization->id, $yesterdayStart, $yesterdayEnd);

        // Period Stats
        $weekStats = $this->getPeriodStats($organization->id, Carbon::now()->startOfWeek(), Carbon::now());
        $monthStats = $this->getPeriodStats($organization->id, Carbon::now()->startOfMonth(), Carbon::now());
        $allTimeStats = $this->getPeriodStats($organization->id, Carbon::createFromDate(2000, 1, 1), Carbon::now());

        // Top Performers
        $topCampaign = Donation::where('donations.organization_id', $organization->id)
            ->where('payment_status', 'completed')
            ->select('campaign_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('campaign_id')
            ->orderByDesc('total')
            ->with('campaign')
            ->first();

        $topDevice = Donation::where('donations.organization_id', $organization->id)
            ->where('payment_status', 'completed')
            ->select('device_id', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('device_id')
            ->orderByDesc('total')
            ->with('device')
            ->first();

        // Active Devices
        $activeDevicesCount = Device::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->count();

        // Chart Data: 30-Day Donation Trend
        $trendData = $this->getTrendData($organization->id);

        // Chart Data: Campaign Performance (Top 8)
        $campaignChartData = $this->getCampaignChartData($organization->id);

        // Chart Data: Device Performance
        $deviceChartData = $this->getDeviceChartData($organization->id);

        // Chart Data: Hourly Activity (Last 30 Days)
        $hourlyData = $this->getHourlyData($organization->id);

        // Chart Data: Day of Week Analysis (Last 90 Days)
        $dayOfWeekData = $this->getDayOfWeekData($organization->id);

        // Chart Data: Monthly Trend (Last 12 Months)
        $monthlyTrendData = $this->getMonthlyTrendData($organization->id);

        // Chart Data: Payment Method Distribution
        $paymentMethodData = $this->getPaymentMethodData($organization->id);

        return view('organization.analytics.index', compact(
            'todayStats',
            'yesterdayStats',
            'weekStats',
            'monthStats',
            'allTimeStats',
            'topCampaign',
            'topDevice',
            'activeDevicesCount',
            'trendData',
            'campaignChartData',
            'deviceChartData',
            'hourlyData',
            'dayOfWeekData',
            'monthlyTrendData',
            'paymentMethodData'
        ));
    }

    // ----- PRIVATE HELPER METHODS -----

    /**
     * Get statistics for a specific period
     */
    private function getPeriodStats(int $orgId, Carbon $start, Carbon $end): array
    {
        $row = Donation::where('organization_id', $orgId)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('
                COUNT(*) as count,
                SUM(CASE WHEN payment_status = "completed" THEN amount ELSE 0 END) as total,
                AVG(CASE WHEN payment_status = "completed" THEN amount ELSE NULL END) as avg
            ')
            ->first();

        return [
            'count' => (int) ($row->count ?? 0),
            'total' => (float) ($row->total ?? 0),
            'avg'   => (float) ($row->avg ?? 0),
        ];
    }

    /**
     * Get 30-day donation trend data
     */
    private function getTrendData(int $orgId): array
    {
        $rows = Donation::where('organization_id', $orgId)
            ->where('payment_status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $totals = [];
        $counts = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('M j');
            $totals[] = isset($rows[$date]) ? (float) $rows[$date]->total : 0;
            $counts[] = isset($rows[$date]) ? (int) $rows[$date]->count : 0;
        }

        return compact('labels', 'totals', 'counts');
    }

    /**
     * Get campaign performance data (top 8 campaigns)
     */
    private function getCampaignChartData(int $orgId): array
    {
        $rows = Donation::where('donations.organization_id', $orgId)
            ->where('payment_status', 'completed')
            ->join('campaigns', 'donations.campaign_id', '=', 'campaigns.id')
            ->selectRaw('campaigns.name as campaign_name, SUM(donations.amount) as total, COUNT(*) as count')
            ->groupBy('campaigns.id', 'campaigns.name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'labels' => $rows->pluck('campaign_name')->toArray(),
            'totals' => $rows->pluck('total')->map(fn($v) => (float) $v)->toArray(),
            'counts' => $rows->pluck('count')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    /**
     * Get device performance data
     */
    private function getDeviceChartData(int $orgId): array
    {
        $rows = Donation::where('donations.organization_id', $orgId)
            ->where('payment_status', 'completed')
            ->join('devices', 'donations.device_id', '=', 'devices.id')
            ->selectRaw('devices.name as device_name, SUM(donations.amount) as total, COUNT(*) as count')
            ->groupBy('devices.id', 'devices.name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return [
            'labels' => $rows->pluck('device_name')->toArray(),
            'totals' => $rows->pluck('total')->map(fn($v) => (float) $v)->toArray(),
            'counts' => $rows->pluck('count')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    /**
     * Get hourly activity data (last 30 days)
     */
    private function getHourlyData(int $orgId): array
    {
        $rows = Donation::where('organization_id', $orgId)
            ->where('payment_status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(29))
            ->selectRaw('HOUR(created_at) as hour, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $labels = [];
        $totals = [];
        $counts = [];

        for ($h = 0; $h <= 23; $h++) {
            $labels[] = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $totals[] = isset($rows[$h]) ? (float) $rows[$h]->total : 0;
            $counts[] = isset($rows[$h]) ? (int) $rows[$h]->count : 0;
        }

        return compact('labels', 'totals', 'counts');
    }

    /**
     * Get day of week analysis data (last 90 days)
     */
    private function getDayOfWeekData(int $orgId): array
    {
        $rows = Donation::where('organization_id', $orgId)
            ->where('payment_status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(89))
            ->selectRaw('DAYOFWEEK(created_at) as dow, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('dow')
            ->orderBy('dow')
            ->get()
            ->keyBy('dow');

        $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $labels = [];
        $totals = [];
        $counts = [];

        // DAYOFWEEK: 1=Sunday, 2=Monday, ..., 7=Saturday
        for ($d = 1; $d <= 7; $d++) {
            $labels[] = $dayNames[$d - 1];
            $totals[] = isset($rows[$d]) ? (float) $rows[$d]->total : 0;
            $counts[] = isset($rows[$d]) ? (int) $rows[$d]->count : 0;
        }

        return compact('labels', 'totals', 'counts');
    }

    /**
     * Get monthly trend data (last 12 months)
     */
    private function getMonthlyTrendData(int $orgId): array
    {
        $rows = Donation::where('organization_id', $orgId)
            ->where('payment_status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(11)->startOfMonth())
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $labels = [];
        $totals = [];
        $counts = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $labels[] = Carbon::now()->subMonths($i)->format('M Y');
            $totals[] = isset($rows[$month]) ? (float) $rows[$month]->total : 0;
            $counts[] = isset($rows[$month]) ? (int) $rows[$month]->count : 0;
        }

        return compact('labels', 'totals', 'counts');
    }

    /**
     * Get payment method distribution
     */
    private function getPaymentMethodData(int $orgId): array
    {
        $rows = Donation::where('organization_id', $orgId)
            ->where('payment_status', 'completed')
            ->selectRaw('COALESCE(payment_method, "Unknown") as method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('method')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $rows->pluck('method')->map(fn($v) => ucfirst($v))->toArray(),
            'totals' => $rows->pluck('total')->map(fn($v) => (float) $v)->toArray(),
            'counts' => $rows->pluck('count')->map(fn($v) => (int) $v)->toArray(),
        ];
    }
}
