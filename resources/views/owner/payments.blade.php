<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">💳 Payments</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Owner</a></li>
                                <li class="breadcrumb-item active">Payments</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Revenue</p>
                            <h4 class="mb-0">₹{{ number_format($stats['total'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Pending</p>
                            <h4 class="mb-0 text-warning">₹{{ number_format($stats['pending'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Completed</p>
                            <h4 class="mb-0 text-success">₹{{ number_format($stats['completed'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Transactions</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Order</th>
                                            <th>Gateway</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Method</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments ?? [] as $payment)
                                        <tr>
                                            <td><code>#{{ $payment->transaction_ref ?? 'N/A' }}</code></td>
                                            <td>{{ $payment->order->order_number ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($payment->gateway ?? 'N/A') }}</span>
                                            </td>
                                            <td>₹{{ number_format($payment->amount ?? 0, 2) }}</td>
                                            <td>
                                                <span class="badge {{ $payment->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($payment->status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                            <td>{{ $payment->created_at ? $payment->created_at->format('d M Y') : 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No payments found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                @if(isset($payments) && method_exists($payments, 'links'))
                                    {{ $payments->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>