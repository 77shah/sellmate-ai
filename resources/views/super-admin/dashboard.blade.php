<x-layouts.app>

    @section('title', 'Super Admin Dashboard')

    @section('content')
    <style>
        .stat-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }
        .gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .gradient-info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .gradient-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .gradient-purple { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }

        .welcome-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 32px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .health-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 6px;
        }
        .health-dot.healthy { background: #10b981; box-shadow: 0 0 0 4px rgba(16,185,129,0.15); }
        .health-dot.unhealthy { background: #ef4444; box-shadow: 0 0 0 4px rgba(239,68,68,0.15); }

        .quick-action {
            border-radius: 12px;
            padding: 16px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            border: 1px solid #f1f3f7;
            background: white;
            color: #1f2937;
        }
        .quick-action:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(102,126,234,0.1);
        }

        .chart-container {
            position: relative;
            height: 300px;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">

            {{-- Page Title --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">🚀 Super Admin Dashboard</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Super Admin</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Welcome Banner --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="welcome-banner">
                        <div class="d-flex justify-content-between align-items-center flex-wrap position-relative">
                            <div>
                                <h3 class="text-white mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h3>
                                <p class="mb-0 opacity-75">
                                    <i class="bx bx-calendar me-1"></i> {{ now()->format('l, d M Y') }} — 
                                    {{ $totalBusinesses }} businesses, {{ $totalOrders }} orders, ₹{{ number_format($totalRevenue, 0) }} revenue
                                </p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <a href="{{ route('super-admin.businesses.create') }}" class="btn btn-light">
                                    <i class="bx bx-plus me-1"></i> Add Business
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Stats Row --}}
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted fw-medium mb-1">Total Businesses</p>
                                    <h3 class="mb-0 fw-bold">{{ number_format($totalBusinesses) }}</h3>
                                    <small class="text-success">
                                        <i class="bx bx-up-arrow-alt"></i> +{{ $todayBusinesses }} today
                                    </small>
                                </div>
                                <div class="stat-icon gradient-primary">
                                    <i class="bx bx-store text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted fw-medium mb-1">Total Staff</p>
                                    <h3 class="mb-0 fw-bold">{{ number_format($totalStaff) }}</h3>
                                    <small class="text-info">Staff + Sales Agents</small>
                                </div>
                                <div class="stat-icon gradient-info">
                                    <i class="bx bx-user text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted fw-medium mb-1">Total Orders</p>
                                    <h3 class="mb-0 fw-bold">{{ number_format($totalOrders) }}</h3>
                                    <small class="text-warning">
                                        <i class="bx bx-up-arrow-alt"></i> +{{ $todayOrders }} today
                                    </small>
                                </div>
                                <div class="stat-icon gradient-warning">
                                    <i class="bx bx-cart text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted fw-medium mb-1">Total Revenue</p>
                                    <h3 class="mb-0 fw-bold">₹{{ number_format($totalRevenue, 0) }}</h3>
                                    <small class="text-success">
                                        <i class="bx bx-up-arrow-alt"></i> ₹{{ number_format($todayRevenue, 0) }} today
                                    </small>
                                </div>
                                <div class="stat-icon gradient-success">
                                    <i class="bx bx-rupee text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Second Row Stats --}}
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted fw-medium mb-1">Monthly Revenue</p>
                                    <h3 class="mb-0 fw-bold">₹{{ number_format($monthlyRevenue, 0) }}</h3>
                                    <small class="text-muted">{{ now()->format('F Y') }}</small>
                                </div>
                                <div class="stat-icon gradient-success">
                                    <i class="bx bx-line-chart text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted fw-medium mb-1">Active Subscriptions</p>
                                    <h3 class="mb-0 fw-bold">{{ number_format($activeSubscriptions) }}</h3>
                                    <small class="text-danger">{{ $expiredSubscriptions }} expired</small>
                                </div>
                                <div class="stat-icon gradient-primary">
                                    <i class="bx bx-credit-card text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted fw-medium mb-1">AI Tokens (Month)</p>
                                    <h3 class="mb-0 fw-bold">{{ number_format($totalAiUsage) }}</h3>
                                    <small class="text-info">{{ number_format($aiUsageToday) }} today</small>
                                </div>
                                <div class="stat-icon gradient-warning">
                                    <i class="bx bx-brain text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted fw-medium mb-1">System Status</p>
                                    <div class="mt-2">
                                        <span class="health-dot {{ $healthStatus['database'] ? 'healthy' : 'unhealthy' }}"></span>
                                        <small>DB</small>
                                        <span class="health-dot {{ $healthStatus['python'] ? 'healthy' : 'unhealthy' }} ms-2"></span>
                                        <small>AI</small>
                                        <span class="health-dot {{ $healthStatus['qdrant'] ? 'healthy' : 'unhealthy' }} ms-2"></span>
                                        <small>Qdrant</small>
                                    </div>
                                </div>
                                <div class="stat-icon gradient-purple">
                                    <i class="bx bx-heart text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chart + Recent Businesses --}}
            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">📊 Last 7 Days Performance</h4>
                                <span class="badge bg-primary">Live</span>
                            </div>
                            <div class="chart-container">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">🏪 Recent Businesses</h4>
                                <a href="{{ route('super-admin.businesses') }}" class="btn btn-sm btn-primary">View All</a>
                            </div>
                            @forelse($recentBusinesses as $biz)
                                <div class="d-flex align-items-center py-2 border-bottom">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-primary bg-soft rounded-circle">
                                            {{ strtoupper(substr($biz->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $biz->name }}</h6>
                                        <small class="text-muted">{{ $biz->email }}</small>
                                    </div>
                                    <small class="text-muted">{{ $biz->created_at?->diffForHumans() }}</small>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="bx bx-store bx-lg d-block mb-2" style="font-size:48px;"></i>
                                    No businesses yet
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Orders + Quick Actions --}}
            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">🛒 Recent Orders</h4>
                                <a href="#" class="btn btn-sm btn-primary">View All</a>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                        <tr>
                                            <td><strong>#{{ $order->order_number ?? $order->id }}</strong></td>
                                            <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                            <td>₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                                            <td>
                                                @php
                                                    $statusColor = match($order->payment_status ?? 'pending') {
                                                        'paid', 'completed' => 'success',
                                                        'pending' => 'warning',
                                                        'failed' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusColor }}">
                                                    {{ ucfirst($order->payment_status ?? 'Pending') }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at?->format('d M Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bx bx-cart bx-lg d-block mb-2" style="font-size:48px;"></i>
                                                No orders found
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">⚡ Quick Actions</h4>
                            <div class="d-grid gap-2">
                                <a href="{{ route('super-admin.businesses') }}" class="quick-action">
                                    <i class="bx bx-store" style="font-size:24px;"></i>
                                    <span>View All Businesses</span>
                                </a>
                                <a href="{{ route('super-admin.users.index') }}" class="quick-action">
                                    <i class="bx bx-user" style="font-size:24px;"></i>
                                    <span>Manage Users</span>
                                </a>
                                <a href="{{ route('super-admin.subscriptions') }}" class="quick-action">
                                    <i class="bx bx-credit-card" style="font-size:24px;"></i>
                                    <span>Subscriptions</span>
                                </a>
                                <a href="{{ route('super-admin.ai-usage') }}" class="quick-action">
                                    <i class="bx bx-brain" style="font-size:24px;"></i>
                                    <span>AI Usage</span>
                                </a>
                                <a href="{{ route('super-admin.system-health') }}" class="quick-action">
                                    <i class="bx bx-heart" style="font-size:24px;"></i>
                                    <span>System Health</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Businesses --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">🏆 Top Businesses (by Orders)</h4>
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Business</th>
                                            <th>Email</th>
                                            <th>Tenant ID</th>
                                            <th>Total Orders</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topBusinesses as $index => $biz)
                                        <tr>
                                            <td>
                                                @if($index == 0) 🥇
                                                @elseif($index == 1) 🥈
                                                @elseif($index == 2) 🥉
                                                @else {{ $index + 1 }}
                                                @endif
                                            </td>
                                            <td><strong>{{ $biz->name }}</strong></td>
                                            <td>{{ $biz->email }}</td>
                                            <td><code>{{ $biz->tenant_id }}</code></td>
                                            <td><span class="badge bg-primary">{{ $biz->orders_count }}</span></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No data available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Last Updated --}}
            <div class="row">
                <div class="col-12">
                    <div class="text-center text-muted mb-3">
                        <small>Dashboard updated: {{ now()->format('d M Y h:i A') }}</small>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('performanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        {
                            label: 'Revenue (₹)',
                            data: @json($chartRevenue),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y',
                        },
                        {
                            label: 'Orders',
                            data: @json($chartOrders),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y1',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: { display: true, text: 'Revenue (₹)' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            title: { display: true, text: 'Orders' }
                        }
                    }
                }
            });
        });
    </script>

</x-layouts.app>