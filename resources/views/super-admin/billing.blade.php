<x-layouts.app>
    
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">💰 Billing Engine</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">Billing</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- ===== Revenue Stats ===== -->
            <div class="row">
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Today</p>
                            <h4 class="mb-0">₹{{ number_format($revenue['today'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">This Week</p>
                            <h4 class="mb-0">₹{{ number_format($revenue['week'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">This Month</p>
                            <h4 class="mb-0">₹{{ number_format($revenue['month'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">This Year</p>
                            <h4 class="mb-0">₹{{ number_format($revenue['year'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Revenue</p>
                            <h4 class="mb-0">₹{{ number_format($revenue['total'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Payments</p>
                            <h4 class="mb-0">{{ number_format($paymentStats['total_payments'] ?? 0) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== Payment Status ===== -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Payment Status Overview</h4>
                            <div class="row">
                                <div class="col-xl-3 col-md-6">
                                    <div class="p-3 border rounded">
                                        <h6 class="text-muted">✅ Successful</h6>
                                        <h4 class="mb-0 text-success">{{ number_format($paymentStats['successful_payments'] ?? 0) }}</h4>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="p-3 border rounded">
                                        <h6 class="text-muted">⏳ Pending</h6>
                                        <h4 class="mb-0 text-warning">{{ number_format($paymentStats['pending_payments'] ?? 0) }}</h4>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="p-3 border rounded">
                                        <h6 class="text-muted">❌ Failed</h6>
                                        <h4 class="mb-0 text-danger">{{ number_format($paymentStats['failed_payments'] ?? 0) }}</h4>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <div class="p-3 border rounded">
                                        <h6 class="text-muted">↩️ Refunded</h6>
                                        <h4 class="mb-0 text-info">{{ number_format($paymentStats['refunded_payments'] ?? 0) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== Recent Payments ===== -->
            @if(isset($recentPayments) && $recentPayments->count() > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Recent Payments</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Business</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentPayments as $payment)
                                        <tr>
                                            <td>#{{ $payment->id }}</td>
                                            <td>{{ $payment->business_name ?? 'N/A' }}</td>
                                            <td><strong>₹{{ number_format($payment->amount ?? 0, 2) }}</strong></td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'success' => 'success',
                                                        'paid' => 'success',
                                                        'pending' => 'warning',
                                                        'failed' => 'danger',
                                                        'refunded' => 'info',
                                                    ];
                                                    $color = $statusColors[$payment->status ?? 'pending'] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ ucfirst($payment->status ?? 'Pending') }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->created_at ? $payment->created_at->format('d M Y H:i') : 'N/A' }}</td>
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

            <!-- ===== Invoices ===== -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">All Invoices / Payments</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Business</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($invoices ?? [] as $invoice)
                                        <tr>
                                            <td>#{{ $invoice->id ?? 'N/A' }}</td>
                                            <td>{{ $invoice->business_name ?? 'N/A' }}</td>
                                            <td><strong>₹{{ number_format($invoice->amount ?? 0, 2) }}</strong></td>
                                            <td>
                                                @php
                                                    $colors = [
                                                        'success' => 'success',
                                                        'paid' => 'success',
                                                        'pending' => 'warning',
                                                        'failed' => 'danger',
                                                        'refunded' => 'info',
                                                    ];
                                                    $color = $colors[$invoice->status ?? 'pending'] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ ucfirst($invoice->status ?? 'Pending') }}
                                                </span>
                                            </td>
                                            <td>{{ $invoice->created_at ? $invoice->created_at->format('d M Y') : 'N/A' }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info" title="View">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                @if(($invoice->status ?? '') == 'pending')
                                                    <button class="btn btn-sm btn-success" title="Mark as Paid">
                                                        <i class="bx bx-check"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bx bx-receipt bx-lg d-block mb-2" style="font-size:48px;"></i>
                                                No invoices found
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                @if(isset($invoices) && method_exists($invoices, 'links'))
                                    {{ $invoices->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>