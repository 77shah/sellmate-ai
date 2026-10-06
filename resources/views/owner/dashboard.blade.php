<x-layouts.app>

    @section('title', 'Owner Dashboard')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">

            {{-- Page Title --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">🏪 Owner Dashboard</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Welcome Banner --}}
            <div class="row">
                <div class="col-12">
                    <div class="card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h4 class="text-white mb-2">👋 Welcome back, {{ Auth::user()->name }}!</h4>
                                    <p class="mb-0 opacity-75">
                                        @if($whatsappCount == 0)
                                            ⚠️ You haven't connected a WhatsApp number yet. <a href="{{ route('owner.whatsapp-numbers') }}" class="text-white fw-bold">Connect now →</a>
                                        @elseif($knowledgeCount == 0)
                                            📄 Upload your menu PDF to train AI. <a href="{{ route('owner.knowledge') }}" class="text-white fw-bold">Upload now →</a>
                                        @else
                                            ✅ Your AI is active and replying to customers!
                                        @endif
                                    </p>
                                </div>
                                <div class="mt-2 mt-md-0">
                                    @if($plan)
                                        <span class="badge bg-white text-success px-3 py-2" style="font-size: 0.9rem;">
                                            <i class="bx bx-crown"></i> {{ $plan->name }} Plan
                                        </span>
                                    @else
                                        <a href="{{ route('owner.plans') }}" class="btn btn-light btn-sm">
                                            <i class="bx bx-crown"></i> Choose Plan
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Warning Alerts --}}
            @if($unansweredCount > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning d-flex align-items-center justify-content-between">
                            <div>
                                <i class="bx bx-info-circle me-2"></i>
                                <strong>{{ $unansweredCount }} unanswered queries</strong> — Aapke customers ke sawaal AI se nahi answer hue. Owner manually reply kariye.
                            </div>
                            <a href="{{ route('owner.unanswered-queries') }}" class="btn btn-warning btn-sm">
                                View Now <i class="bx bx-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Stats Row 1 — Main Stats --}}
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Total Customers</p>
                                    <h4 class="mb-0">{{ number_format($totalCustomers) }}</h4>
                                    <small class="text-success">+{{ $todayOrders }} today</small>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-primary bg-soft">
                                        <span class="avatar-title">
                                            <i class="bx bx-user font-size-24 text-primary"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Total Orders</p>
                                    <h4 class="mb-0">{{ number_format($totalOrders) }}</h4>
                                    <small class="text-info">{{ $monthOrders }} this month</small>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-info bg-soft">
                                        <span class="avatar-title">
                                            <i class="bx bx-cart font-size-24 text-info"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Total Revenue</p>
                                    <h4 class="mb-0">₹{{ number_format($totalRevenue, 0) }}</h4>
                                    <small class="text-success">₹{{ number_format($todayRevenue, 0) }} today</small>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-success bg-soft">
                                        <span class="avatar-title">
                                            <i class="bx bx-rupee font-size-24 text-success"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Pending Orders</p>
                                    <h4 class="mb-0">{{ number_format($pendingOrders) }}</h4>
                                    <small class="text-warning">Need attention</small>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-warning bg-soft">
                                        <span class="avatar-title">
                                            <i class="bx bx-time font-size-24 text-warning"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Row 2 — System Status --}}
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">WhatsApp Numbers</p>
                                    <h4 class="mb-0">{{ $whatsappActive }}/{{ $whatsappCount }}</h4>
                                    <small class="{{ $whatsappCount > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $whatsappCount > 0 ? 'Active' : 'Not connected' }}
                                    </small>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-success bg-soft">
                                        <span class="avatar-title">
                                            <i class="bx bxl-whatsapp font-size-24 text-success"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Knowledge Base</p>
                                    <h4 class="mb-0">{{ $knowledgeProcessed }}/{{ $knowledgeCount }}</h4>
                                    <small class="{{ $knowledgeCount > 0 ? 'text-success' : 'text-warning' }}">
                                        {{ $knowledgeCount > 0 ? 'Docs processed' : 'Upload needed' }}
                                    </small>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-info bg-soft">
                                        <span class="avatar-title">
                                            <i class="bx bx-book font-size-24 text-info"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Active Conversations</p>
                                    <h4 class="mb-0">{{ $activeConversations }}</h4>
                                    <small class="text-info">{{ $todayMessages }} messages today</small>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-danger bg-soft">
                                        <span class="avatar-title">
                                            <i class="bx bx-message-dots font-size-24 text-danger"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Avg Lead Score</p>
                                    <h4 class="mb-0">{{ round($averageLeadScore ?? 0, 1) }}</h4>
                                    <small class="text-muted">out of 100</small>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-purple bg-soft">
                                        <span class="avatar-title">
                                            <i class="bx bx-star font-size-24 text-purple"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts + Subscription --}}
            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">📊 Last 7 Days Performance</h4>
                            <canvas id="revenueChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">💳 Subscription</h4>
                            @if($subscription)
                                <div class="text-center mb-3">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title bg-success bg-soft rounded-circle" style="width:80px;height:80px;margin:0 auto;">
                                            <i class="bx bx-crown font-size-36 text-success"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-1">{{ $subscription->plan_name }}</h5>
                                    <p class="text-muted mb-0">₹{{ number_format($subscription->amount, 0) }}/month</p>
                                </div>
                                <ul class="list-unstyled mb-3">
                                    <li class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted">Status</span>
                                        <span class="badge bg-success">{{ ucfirst($subscription->status) }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between py-2 border-bottom">
                                        <span class="text-muted">Started</span>
                                        <span>{{ $subscription->start_date?->format('d M Y') }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between py-2">
                                        <span class="text-muted">Expires</span>
                                        <span class="fw-bold">{{ $subscription->end_date?->format('d M Y') }}</span>
                                    </li>
                                </ul>
                                <a href="{{ route('owner.subscription.status') }}" class="btn btn-primary w-100">
                                    Manage Subscription
                                </a>
                            @else
                                <div class="text-center py-4">
                                    <i class="bx bx-crown" style="font-size:64px; color:#ddd;"></i>
                                    <h5 class="mt-3">No Active Plan</h5>
                                    <p class="text-muted">Subscribe to a plan to start using SellMate AI.</p>
                                    <a href="{{ route('owner.plans') }}" class="btn btn-primary">
                                        View Plans
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Orders + Recent Conversations --}}
            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">🛒 Recent Orders</h4>
                                <a href="{{ route('owner.orders') }}" class="btn btn-sm btn-primary">
                                    View All <i class="bx bx-arrow-right"></i>
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order</th>
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
                                                    $statusColor = match($order->status ?? 'pending') {
                                                        'paid', 'completed' => 'success',
                                                        'pending' => 'warning',
                                                        'cancelled' => 'danger',
                                                        default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $statusColor }}">
                                                    {{ ucfirst($order->status ?? 'Pending') }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at?->format('d M Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bx bx-cart bx-lg d-block mb-2" style="font-size:48px;"></i>
                                                No orders yet
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
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">💬 Recent Chats</h4>
                                <a href="{{ route('owner.inbox') }}" class="btn btn-sm btn-primary">
                                    View All
                                </a>
                            </div>
                            @forelse($recentConversations as $conv)
                                <div class="d-flex align-items-center py-2 border-bottom">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-title bg-success bg-soft rounded-circle">
                                            {{ strtoupper(substr($conv->customer->name ?? 'C', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $conv->customer->name ?? 'Customer' }}</h6>
                                        <small class="text-muted">
                                            {{ $conv->last_message_at?->diffForHumans() ?? 'Just now' }}
                                        </small>
                                    </div>
                                    @if($conv->unread_count ?? 0 > 0)
                                        <span class="badge bg-danger">{{ $conv->unread_count }}</span>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="bx bx-message-dots bx-lg d-block mb-2" style="font-size:48px;"></i>
                                    No conversations yet
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">⚡ Quick Actions</h4>
                            <div class="row g-3">
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('owner.whatsapp-numbers') }}" class="btn btn-outline-success w-100 py-3">
                                        <i class="bx bxl-whatsapp font-size-24 d-block mb-1"></i>
                                        WhatsApp
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('owner.knowledge') }}" class="btn btn-outline-info w-100 py-3">
                                        <i class="bx bx-book font-size-24 d-block mb-1"></i>
                                        Knowledge
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('owner.inbox') }}" class="btn btn-outline-primary w-100 py-3">
                                        <i class="bx bx-message-dots font-size-24 d-block mb-1"></i>
                                        Inbox
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('owner.orders') }}" class="btn btn-outline-warning w-100 py-3">
                                        <i class="bx bx-cart font-size-24 d-block mb-1"></i>
                                        Orders
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('owner.crm') }}" class="btn btn-outline-secondary w-100 py-3">
                                        <i class="bx bx-user font-size-24 d-block mb-1"></i>
                                        CRM
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('owner.products.index') }}" class="btn btn-outline-dark w-100 py-3">
                                        <i class="bx bx-package font-size-24 d-block mb-1"></i>
                                        Products
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('owner.ai-settings') }}" class="btn btn-outline-info w-100 py-3">
                                        <i class="bx bx-brain font-size-24 d-block mb-1"></i>
                                        AI Settings
                                    </a>
                                </div>
                                <div class="col-md-3 col-6">
                                    <a href="{{ route('owner.analytics') }}" class="btn btn-outline-primary w-100 py-3">
                                        <i class="bx bx-bar-chart font-size-24 d-block mb-1"></i>
                                        Analytics
                                    </a>
                                </div>
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
            const ctx = document.getElementById('revenueChart').getContext('2d');
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
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
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