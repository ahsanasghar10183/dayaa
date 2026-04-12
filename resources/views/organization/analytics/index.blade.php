<x-organization-sidebar-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Analytics Dashboard</h2>
                <p class="text-sm text-gray-500 mt-1">Comprehensive donation insights and performance metrics</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            {{-- Today --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Today</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">€{{ number_format($todayStats['total'], 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $todayStats['count'] }} donations</p>
                @php
                    $diff = $todayStats['total'] - $yesterdayStats['total'];
                    $pct = $yesterdayStats['total'] > 0 ? round(($diff / $yesterdayStats['total']) * 100, 1) : 0;
                @endphp
                <p class="text-xs mt-2 {{ $diff >= 0 ? 'text-green-600' : 'text-red-500' }}">
                    {{ $diff >= 0 ? '+' : '' }}€{{ number_format(abs($diff), 2) }} vs yesterday
                    @if($pct != 0)({{ $diff >= 0 ? '+' : '' }}{{ $pct }}%)@endif
                </p>
            </div>

            {{-- This Week --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">This Week</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">€{{ number_format($weekStats['total'], 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $weekStats['count'] }} donations</p>
                <p class="text-xs mt-2 text-gray-400">Avg €{{ number_format($weekStats['avg'], 2) }}</p>
            </div>

            {{-- This Month --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">This Month</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">€{{ number_format($monthStats['total'], 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $monthStats['count'] }} donations</p>
                <p class="text-xs mt-2 text-gray-400">Avg €{{ number_format($monthStats['avg'], 2) }}</p>
            </div>

            {{-- All Time --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">All Time</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">€{{ number_format($allTimeStats['total'], 2) }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $allTimeStats['count'] }} donations</p>
                <p class="text-xs mt-2 text-gray-400">Avg €{{ number_format($allTimeStats['avg'], 2) }}</p>
            </div>
        </div>

        {{-- Top Performers Row --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            {{-- Top Campaign --}}
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-blue-700">Top Campaign</p>
                </div>
                @if($topCampaign && $topCampaign->campaign)
                    <p class="font-bold text-gray-900 truncate">{{ $topCampaign->campaign->name }}</p>
                    <p class="text-2xl font-bold text-blue-700 mt-1">€{{ number_format($topCampaign->total, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $topCampaign->count }} donations</p>
                @else
                    <p class="text-gray-400 text-sm">No data yet</p>
                @endif
            </div>

            {{-- Top Device --}}
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-purple-700">Top Device</p>
                </div>
                @if($topDevice && $topDevice->device)
                    <p class="font-bold text-gray-900 truncate">{{ $topDevice->device->name }}</p>
                    <p class="text-2xl font-bold text-purple-700 mt-1">€{{ number_format($topDevice->total, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $topDevice->count }} donations</p>
                @else
                    <p class="text-gray-400 text-sm">No data yet</p>
                @endif
            </div>

            {{-- Active Devices --}}
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100 p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-green-700">Active Devices</p>
                </div>
                <p class="text-3xl font-bold text-green-700">{{ $activeDevicesCount }}</p>
                <p class="text-sm text-gray-500 mt-1">Currently online</p>
            </div>
        </div>

        {{-- Trend Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- 30-Day Trend --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Donation Trend (Last 30 Days)</h3>
                <div class="relative h-64">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            {{-- 12-Month Trend --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Monthly Trend (Last 12 Months)</h3>
                <div class="relative h-64">
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Performance Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Campaign Performance --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Campaign Performance (Top 8)</h3>
                <div class="relative h-64">
                    @if(count($campaignChartData['labels']) > 0)
                        <canvas id="campaignChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400">No campaign data yet</div>
                    @endif
                </div>
            </div>

            {{-- Device Performance --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Device Performance</h3>
                <div class="relative h-64">
                    @if(count($deviceChartData['labels']) > 0)
                        <canvas id="deviceChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400">No device data yet</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Activity Pattern Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            {{-- Hourly Activity --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Hourly Activity (Last 30 Days)</h3>
                <div class="relative h-64">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </div>

            {{-- Day of Week --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Day of Week Analysis (Last 90 Days)</h3>
                <div class="relative h-64">
                    <canvas id="dowChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Payment Method Distribution --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Payment Method Distribution</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="relative h-64">
                    @if(count($paymentMethodData['labels']) > 0)
                        <canvas id="paymentMethodChart"></canvas>
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400">No payment data yet</div>
                    @endif
                </div>
                <div>
                    @if(count($paymentMethodData['labels']) > 0)
                        <div class="space-y-3">
                            @foreach($paymentMethodData['labels'] as $index => $label)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full" style="background-color: {{ ['#1163F0','#10B981','#F59E0B','#EF4444','#8B5CF6'][$index % 5] }}"></div>
                                        <span class="font-medium text-gray-700">{{ $label }}</span>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">€{{ number_format($paymentMethodData['totals'][$index], 2) }}</p>
                                        <p class="text-xs text-gray-500">{{ $paymentMethodData['counts'][$index] }} donations</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Chart is defined
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                return;
            }

            const PRIMARY = '#1163F0';
            const PRIMARY_LIGHT = 'rgba(17,99,240,0.15)';
            const COLORS = ['#1163F0','#10B981','#F59E0B','#EF4444','#8B5CF6','#06B6D4','#F97316','#EC4899'];

            // ---- 30-Day Trend Chart ----
            const trendData = @json($trendData);
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trendData.labels,
                datasets: [{
                    label: 'Total (€)',
                    data: trendData.totals,
                    borderColor: PRIMARY,
                    backgroundColor: PRIMARY_LIGHT,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => '€' + ctx.parsed.y.toFixed(2) + ' (' + trendData.counts[ctx.dataIndex] + ' donations)'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '€' + v.toLocaleString() },
                        grid: { color: '#f3f4f6' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { maxRotation: 45, maxTicksLimit: 10 }
                    }
                }
            }
        });

        // ---- Monthly Trend Chart ----
        const monthlyTrendData = @json($monthlyTrendData);
        new Chart(document.getElementById('monthlyTrendChart'), {
            type: 'bar',
            data: {
                labels: monthlyTrendData.labels,
                datasets: [{
                    label: 'Total (€)',
                    data: monthlyTrendData.totals,
                    backgroundColor: 'rgba(17,99,240,0.7)',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => '€' + ctx.parsed.y.toFixed(2) + ' (' + monthlyTrendData.counts[ctx.dataIndex] + ' donations)'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '€' + v.toLocaleString() },
                        grid: { color: '#f3f4f6' }
                    },
                    x: { grid: { display: false }, ticks: { maxRotation: 45 } }
                }
            }
        });

        // ---- Campaign Chart ----
        @if(count($campaignChartData['labels']) > 0)
        const campaignData = @json($campaignChartData);
        new Chart(document.getElementById('campaignChart'), {
            type: 'bar',
            data: {
                labels: campaignData.labels,
                datasets: [{
                    label: 'Total (€)',
                    data: campaignData.totals,
                    backgroundColor: COLORS,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => '€' + ctx.parsed.y.toFixed(2) + ' (' + campaignData.counts[ctx.dataIndex] + ' donations)'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '€' + v.toLocaleString() },
                        grid: { color: '#f3f4f6' }
                    },
                    x: { grid: { display: false }, ticks: { maxRotation: 45 } }
                }
            }
        });
        @endif

        // ---- Device Chart ----
        @if(count($deviceChartData['labels']) > 0)
        const deviceData = @json($deviceChartData);
        new Chart(document.getElementById('deviceChart'), {
            type: 'bar',
            data: {
                labels: deviceData.labels,
                datasets: [{
                    label: 'Total (€)',
                    data: deviceData.totals,
                    backgroundColor: COLORS,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => '€' + ctx.parsed.y.toFixed(2) + ' (' + deviceData.counts[ctx.dataIndex] + ' donations)'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => '€' + v.toLocaleString() },
                        grid: { color: '#f3f4f6' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
        @endif

        // ---- Hourly Chart ----
        const hourlyData = @json($hourlyData);
        new Chart(document.getElementById('hourlyChart'), {
            type: 'bar',
            data: {
                labels: hourlyData.labels,
                datasets: [{
                    label: 'Donations',
                    data: hourlyData.counts,
                    backgroundColor: 'rgba(16,185,129,0.7)',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.parsed.y + ' donations (€' + hourlyData.totals[ctx.dataIndex].toFixed(2) + ')'
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false }, ticks: { maxRotation: 45 } }
                }
            }
        });

        // ---- Day of Week Chart ----
        const dowData = @json($dayOfWeekData);
        new Chart(document.getElementById('dowChart'), {
            type: 'bar',
            data: {
                labels: dowData.labels,
                datasets: [{
                    label: 'Donations',
                    data: dowData.counts,
                    backgroundColor: COLORS.slice(0, 7),
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.parsed.y + ' donations (€' + dowData.totals[ctx.dataIndex].toFixed(2) + ')'
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });

        // ---- Payment Method Chart ----
        @if(count($paymentMethodData['labels']) > 0)
        const paymentMethodData = @json($paymentMethodData);
        new Chart(document.getElementById('paymentMethodChart'), {
            type: 'doughnut',
            data: {
                labels: paymentMethodData.labels,
                datasets: [{
                    data: paymentMethodData.totals,
                    backgroundColor: COLORS.slice(0, paymentMethodData.labels.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.label + ': €' + ctx.parsed.toFixed(2) + ' (' + paymentMethodData.counts[ctx.dataIndex] + ' donations)'
                        }
                    }
                }
            }
        });
        @endif

        }); // End DOMContentLoaded
    </script>
    @endpush
</x-organization-sidebar-layout>
