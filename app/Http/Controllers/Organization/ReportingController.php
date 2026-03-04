<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportingController extends Controller
{
    /**
     * Main reports index page
     */
    public function index(Request $request)
    {
        $organization = auth()->user()->organization;

        // Build base query
        $query = Donation::where('donations.organization_id', $organization->id)
            ->with(['campaign', 'device']);

        // Apply date range filter
        [$startDate, $endDate, $datePreset] = $this->resolveDateRange($request);
        $query->whereBetween('donations.created_at', [$startDate, $endDate]);

        // Apply campaign filter
        if ($request->filled('campaign_id')) {
            $query->where('donations.campaign_id', $request->campaign_id);
        }

        // Apply device filter
        if ($request->filled('device_id')) {
            $query->where('donations.device_id', $request->device_id);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('donations.payment_status', $request->status);
        }

        // Apply amount range filter
        if ($request->filled('amount_min')) {
            $query->where('donations.amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('donations.amount', '<=', $request->amount_max);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('donations.receipt_number', 'like', "%{$search}%")
                  ->orWhere('donations.transaction_id', 'like', "%{$search}%")
                  ->orWhere('donations.sumup_transaction_id', 'like', "%{$search}%")
                  ->orWhere('donations.amount', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $allowedSorts = ['created_at', 'amount', 'payment_status', 'receipt_number'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        $query->orderBy("donations.{$sortBy}", $sortDir === 'asc' ? 'asc' : 'desc');

        // Pagination
        $perPage = in_array($request->get('per_page'), [20, 50, 100]) ? (int) $request->get('per_page') : 20;
        $donations = $query->paginate($perPage)->appends($request->query());

        // Summary stats for filtered results
        $summaryQuery = Donation::where('donations.organization_id', $organization->id)
            ->whereBetween('donations.created_at', [$startDate, $endDate]);

        if ($request->filled('campaign_id')) {
            $summaryQuery->where('donations.campaign_id', $request->campaign_id);
        }
        if ($request->filled('device_id')) {
            $summaryQuery->where('donations.device_id', $request->device_id);
        }
        if ($request->filled('status')) {
            $summaryQuery->where('donations.payment_status', $request->status);
        }
        if ($request->filled('amount_min')) {
            $summaryQuery->where('donations.amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $summaryQuery->where('donations.amount', '<=', $request->amount_max);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $summaryQuery->where(function ($q) use ($search) {
                $q->where('donations.receipt_number', 'like', "%{$search}%")
                  ->orWhere('donations.transaction_id', 'like', "%{$search}%")
                  ->orWhere('donations.amount', 'like', "%{$search}%");
            });
        }

        $summary = $summaryQuery->selectRaw('
            COUNT(*) as total_count,
            SUM(CASE WHEN payment_status = "completed" THEN amount ELSE 0 END) as total_amount,
            AVG(CASE WHEN payment_status = "completed" THEN amount ELSE NULL END) as avg_amount,
            SUM(CASE WHEN payment_status = "completed" THEN 1 ELSE 0 END) as success_count,
            SUM(CASE WHEN payment_status = "failed" THEN 1 ELSE 0 END) as failed_count,
            SUM(CASE WHEN payment_status = "pending" THEN 1 ELSE 0 END) as pending_count
        ')->first();

        // KPIs (today vs yesterday)
        $todayStart = Carbon::today();
        $todayEnd = Carbon::now();
        $yesterdayStart = Carbon::yesterday();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();

        $todayStats = $this->getPeriodStats($organization->id, $todayStart, $todayEnd);
        $yesterdayStats = $this->getPeriodStats($organization->id, $yesterdayStart, $yesterdayEnd);

        // This week
        $weekStats = $this->getPeriodStats($organization->id, Carbon::now()->startOfWeek(), Carbon::now());
        // This month
        $monthStats = $this->getPeriodStats($organization->id, Carbon::now()->startOfMonth(), Carbon::now());
        // All time
        $allTimeStats = $this->getPeriodStats($organization->id, Carbon::createFromDate(2000, 1, 1), Carbon::now());

        // Top campaign
        $topCampaign = Donation::where('donations.organization_id', $organization->id)
            ->where('payment_status', 'completed')
            ->select('campaign_id', DB::raw('SUM(amount) as total'))
            ->groupBy('campaign_id')
            ->orderByDesc('total')
            ->with('campaign')
            ->first();

        // Top device
        $topDevice = Donation::where('donations.organization_id', $organization->id)
            ->where('payment_status', 'completed')
            ->select('device_id', DB::raw('SUM(amount) as total'))
            ->groupBy('device_id')
            ->orderByDesc('total')
            ->with('device')
            ->first();

        // Active devices count
        $activeDevicesCount = Device::where('organization_id', $organization->id)
            ->where('status', 'active')
            ->count();

        // Recent 10 donations
        $recentDonations = Donation::where('donations.organization_id', $organization->id)
            ->with(['campaign', 'device'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Chart data: 30-day donation trend
        $trendData = $this->getTrendData($organization->id);

        // Chart data: campaign comparison
        $campaignChartData = $this->getCampaignChartData($organization->id);

        // Chart data: device performance
        $deviceChartData = $this->getDeviceChartData($organization->id);

        // Chart data: hourly activity (last 30 days)
        $hourlyData = $this->getHourlyData($organization->id);

        // Chart data: day of week
        $dayOfWeekData = $this->getDayOfWeekData($organization->id);

        // Filter dropdowns
        $campaigns = Campaign::where('organization_id', $organization->id)->orderBy('name')->get();
        $devices = Device::where('organization_id', $organization->id)->orderBy('name')->get();

        return view('organization.reports.index', compact(
            'donations',
            'summary',
            'todayStats',
            'yesterdayStats',
            'weekStats',
            'monthStats',
            'allTimeStats',
            'topCampaign',
            'topDevice',
            'activeDevicesCount',
            'recentDonations',
            'trendData',
            'campaignChartData',
            'deviceChartData',
            'hourlyData',
            'dayOfWeekData',
            'campaigns',
            'devices',
            'startDate',
            'endDate',
            'datePreset',
            'perPage'
        ));
    }

    /**
     * Export donations to CSV
     */
    public function export(Request $request)
    {
        $organization = auth()->user()->organization;

        $query = Donation::where('donations.organization_id', $organization->id)
            ->with(['campaign', 'device']);

        [$startDate, $endDate] = $this->resolveDateRange($request);
        $query->whereBetween('donations.created_at', [$startDate, $endDate]);

        if ($request->filled('campaign_id')) {
            $query->where('donations.campaign_id', $request->campaign_id);
        }
        if ($request->filled('device_id')) {
            $query->where('donations.device_id', $request->device_id);
        }
        if ($request->filled('status')) {
            $query->where('donations.payment_status', $request->status);
        }
        if ($request->filled('amount_min')) {
            $query->where('donations.amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('donations.amount', '<=', $request->amount_max);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('donations.receipt_number', 'like', "%{$search}%")
                  ->orWhere('donations.transaction_id', 'like', "%{$search}%")
                  ->orWhere('donations.amount', 'like', "%{$search}%");
            });
        }

        $query->orderByDesc('donations.created_at');
        $donations = $query->get();

        // Generate filename
        $orgName = preg_replace('/[^A-Za-z0-9_]/', '_', $organization->name);
        $filename = "{$orgName}_Donations_" . now()->format('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($donations) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fputs($file, "\xEF\xBB\xBF");

            // Headers
            fputcsv($file, [
                'Date',
                'Time',
                'Receipt #',
                'Amount (EUR)',
                'Campaign',
                'Device',
                'Status',
                'Transaction ID',
                'SumUp Transaction ID',
                'Payment Method',
                'Donor Name',
                'Donor Email',
            ]);

            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->created_at->format('Y-m-d'),
                    $donation->created_at->format('H:i:s'),
                    $donation->receipt_number ?? '',
                    number_format($donation->amount, 2, '.', ''),
                    $donation->campaign->name ?? 'N/A',
                    $donation->device->name ?? 'N/A',
                    ucfirst($donation->payment_status ?? ''),
                    $donation->transaction_id ?? '',
                    $donation->sumup_transaction_id ?? '',
                    $donation->payment_method ?? '',
                    $donation->donor_name ?? '',
                    $donation->donor_email ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ----- PRIVATE HELPERS -----

    private function resolveDateRange(Request $request): array
    {
        $preset = $request->get('date_preset', 'last_30_days');

        switch ($preset) {
            case 'today':
                $start = Carbon::today();
                $end = Carbon::now();
                break;
            case 'yesterday':
                $start = Carbon::yesterday()->startOfDay();
                $end = Carbon::yesterday()->endOfDay();
                break;
            case 'last_7_days':
                $start = Carbon::now()->subDays(6)->startOfDay();
                $end = Carbon::now();
                break;
            case 'this_month':
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now();
                break;
            case 'last_month':
                $start = Carbon::now()->subMonth()->startOfMonth();
                $end = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'custom':
                $start = $request->filled('start_date')
                    ? Carbon::parse($request->start_date)->startOfDay()
                    : Carbon::now()->subDays(29)->startOfDay();
                $end = $request->filled('end_date')
                    ? Carbon::parse($request->end_date)->endOfDay()
                    : Carbon::now();
                break;
            default: // last_30_days
                $preset = 'last_30_days';
                $start = Carbon::now()->subDays(29)->startOfDay();
                $end = Carbon::now();
                break;
        }

        return [$start, $end, $preset];
    }

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
}
