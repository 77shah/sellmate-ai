<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">🔗 API Gateway</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">API Gateway</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <select class="form-select" name="filter">
                                        <option value="today" {{ request('filter', 'today') == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>This Week</option>
                                        <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>This Month</option>
                                        <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>This Year</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-filter"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('super-admin.api-gateway') }}" class="btn btn-secondary w-100">
                                        <i class="bx bx-refresh"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total API Calls</p>
                            <h4 class="mb-0">{{ number_format($stats['total_calls'] ?? 0) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">✅ Successful</p>
                            <h4 class="mb-0 text-success">{{ number_format($stats['success_calls'] ?? 0) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">❌ Failed</p>
                            <h4 class="mb-0 text-danger">{{ number_format($stats['failed_calls'] ?? 0) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Error Rate</p>
                            <h4 class="mb-0 text-{{ ($stats['error_rate'] ?? 0) > 5 ? 'danger' : 'success' }}">
                                {{ $stats['error_rate'] ?? 0 }}%
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avg Latency & Status -->
            <div class="row">
                <div class="col-xl-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Avg Latency</p>
                            <h4 class="mb-0">{{ $stats['avg_latency'] ?? 0 }}ms</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">API Status</p>
                            <h4 class="mb-0">
                                @if(isset($stats['api_status']))
                                    @foreach($stats['api_status'] as $service => $status)
                                        <span class="badge {{ $status == 'Operational' ? 'bg-success' : 'bg-danger' }} me-1">
                                            {{ ucfirst($service) }}: {{ $status }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="badge bg-success">Operational</span>
                                @endif
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Endpoints -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Top API Endpoints</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Endpoint</th>
                                            <th>Calls</th>
                                            <th>Avg Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($stats['endpoints'] ?? [] as $index => $endpoint)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><code>{{ $endpoint->endpoint ?? 'N/A' }}</code></td>
                                            <td>{{ number_format($endpoint->calls ?? 0) }}</td>
                                            <td>{{ round($endpoint->avg_time ?? 0, 2) }}ms</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No data found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Code Breakdown -->
            @if(isset($stats['status_codes']) && $stats['status_codes']->count() > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Status Code Breakdown</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Status Code</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['status_codes'] as $code)
                                        <tr>
                                            <td>
                                                @php
                                                    $statusColor = match(true) {
                                                        $code->status_code < 300 => 'success',
                                                        $code->status_code < 400 => 'warning',
                                                        $code->status_code < 500 => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusColor }}">{{ $code->status_code }}</span>
                                            </td>
                                            <td>{{ number_format($code->count) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent API Calls -->
            @if(isset($stats['recent_calls']) && $stats['recent_calls']->count() > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Recent API Calls</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Endpoint</th>
                                            <th>Method</th>
                                            <th>Status</th>
                                            <th>Response Time</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['recent_calls'] as $call)
                                        <tr>
                                            <td><code>{{ $call->endpoint }}</code></td>
                                            <td><span class="badge bg-info">{{ $call->method }}</span></td>
                                            <td>
                                                @php
                                                    $color = $call->status_code < 400 ? 'success' : 'danger';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">{{ $call->status_code }}</span>
                                            </td>
                                            <td>{{ $call->response_time }}ms</td>
                                            <td>
                                                @if($call->created_at)
                                                    {{ \Carbon\Carbon::parse($call->created_at)->format('d M Y H:i:s') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</x-layouts.app>