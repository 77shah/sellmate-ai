<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📊 Analytics</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Analytics</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== MAIN STATS ===== -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Leads</p>
                            <h4 class="mb-0">{{ $totalLeads ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Active Customers</p>
                            <h4 class="mb-0">{{ $activeCustomers ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Orders</p>
                            <h4 class="mb-0">{{ $totalOrders ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Revenue</p>
                            <h4 class="mb-0">₹{{ number_format($totalRevenue ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== RATE STATS ===== -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Conversion Rate</p>
                            <h4 class="mb-0 {{ ($conversionRate ?? 0) >= 50 ? 'text-success' : 'text-warning' }}">
                                {{ $conversionRate ?? 0 }}%
                            </h4>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-{{ ($conversionRate ?? 0) >= 50 ? 'success' : 'warning' }}" 
                                     style="width: {{ min($conversionRate ?? 0, 100) }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">AI Resolution Rate</p>
                            <h4 class="mb-0 {{ ($aiResolutionRate ?? 0) >= 70 ? 'text-success' : 'text-warning' }}">
                                {{ $aiResolutionRate ?? 0 }}%
                            </h4>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-{{ ($aiResolutionRate ?? 0) >= 70 ? 'success' : 'warning' }}" 
                                     style="width: {{ min($aiResolutionRate ?? 0, 100) }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Avg Response Time</p>
                            <h4 class="mb-0 {{ ($avgResponseTime ?? 0) <= 5 ? 'text-success' : 'text-warning' }}">
                                {{ $avgResponseTime ?? 0 }} min
                            </h4>
                            <div class="progress mt-2" style="height: 6px;">
                                @php
                                    $responsePercent = $avgResponseTime > 0 ? min((10 - $avgResponseTime) * 10, 100) : 100;
                                @endphp
                                <div class="progress-bar bg-{{ ($avgResponseTime ?? 0) <= 5 ? 'success' : 'warning' }}" 
                                     style="width: {{ max($responsePercent, 0) }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Conversations</p>
                            <h4 class="mb-0">{{ $totalConversations ?? 0 }}</h4>
                            <small class="text-muted">AI Resolved: {{ $resolvedByAI ?? 0 }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== CUSTOMER STAGES ===== -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Customer Stages</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Stage</th>
                                            <th>Count</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($customerStages ?? [] as $stage => $count)
                                        <tr>
                                            <td>
                                                @php
                                                    $stageIcons = [
                                                        'new' => '🆕',
                                                        'contacted' => '📞',
                                                        'qualified' => '⭐',
                                                        'order_placed' => '🛒',
                                                        'closed' => '✅',
                                                    ];
                                                @endphp
                                                {{ $stageIcons[$stage] ?? '' }} {{ ucfirst($stage) }}
                                            </td>
                                            <td>{{ $count }}</td>
                                            <td>
                                                @php
                                                    $total = array_sum($customerStages);
                                                    $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                                                @endphp
                                                {{ $percent }}%
                                                <div class="progress mt-1" style="height: 4px;">
                                                    <div class="progress-bar" style="width: {{ $percent }}%;"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== CHANNEL DISTRIBUTION ===== -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Channel Distribution</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Channel</th>
                                            <th>Conversations</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalChannels = $channelDistribution->sum('total');
                                        @endphp
                                        @forelse($channelDistribution ?? [] as $channel)
                                        <tr>
                                            <td>
                                                @php
                                                    $channelIcons = [
                                                        'whatsapp' => '💬',
                                                        'instagram' => '📷',
                                                        'facebook' => '📘',
                                                        'web' => '🌐',
                                                    ];
                                                @endphp
                                                {{ $channelIcons[$channel->channel] ?? '' }} {{ ucfirst($channel->channel ?? 'Unknown') }}
                                            </td>
                                            <td>{{ $channel->total ?? 0 }}</td>
                                            <td>
                                                @php
                                                    $percent = $totalChannels > 0 ? round(($channel->total / $totalChannels) * 100, 1) : 0;
                                                @endphp
                                                {{ $percent }}%
                                                <div class="progress mt-1" style="height: 4px;">
                                                    <div class="progress-bar" style="width: {{ $percent }}%;"></div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No data available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== MONTHLY TRENDS ===== -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Monthly Trends (Last 12 Months)</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Month</th>
                                            <th>Orders</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($monthlyTrends ?? [] as $trend)
                                        <tr>
                                            <td><strong>{{ $trend->month_name ?? 'N/A' }}</strong></td>
                                            <td>{{ $trend->total_orders ?? 0 }}</td>
                                            <td>₹{{ number_format($trend->total_revenue ?? 0, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No data available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== LAST UPDATED ===== -->
            <div class="row">
                <div class="col-12">
                    <div class="text-center text-muted">
                        <small>Data updated: {{ now()->format('d M Y h:i A') }}</small>
                        <br>
                        <small>Auto-refreshes every 60 seconds</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto refresh every 60 seconds
        setTimeout(function() {
            location.reload();
        }, 60000);
    </script>
    @endpush

</x-layouts.app>