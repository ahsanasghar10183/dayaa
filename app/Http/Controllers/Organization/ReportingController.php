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
     * Main reports index page - Tabular data and exports
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
            SUM(CASE WHEN payment_status = "pending" THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN payment_status = "processing" THEN 1 ELSE 0 END) as processing_count
        ')->first();

        // Filter dropdowns
        $campaigns = Campaign::where('organization_id', $organization->id)->orderBy('name')->get();
        $devices = Device::where('organization_id', $organization->id)->orderBy('name')->get();

        return view('organization.reports.index', compact(
            'donations',
            'summary',
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
}
