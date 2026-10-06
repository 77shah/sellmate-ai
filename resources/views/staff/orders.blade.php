<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📦 Orders</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Staff</a></li>
                                <li class="breadcrumb-item active">Orders</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-2">
                                    <select class="form-select" name="status">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('staff.orders') }}" class="btn btn-secondary w-100">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">All Orders</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Payment</th>
                                            <th>Source</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders ?? [] as $order)
                                        <tr>
                                            <td><strong>#{{ $order->order_number ?? 'N/A' }}</strong></td>
                                            <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                            <td>₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                                            <td>
                                                <span class="badge {{ $order->status == 'paid' ? 'bg-success' : ($order->status == 'pending' ? 'bg-warning' : 'bg-secondary') }}">
                                                    {{ ucfirst($order->status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($order->payment_status ?? 'pending') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($order->source ?? 'N/A') }}</span>
                                            </td>
                                            <td>{{ $order->created_at ? $order->created_at->format('d M Y') : 'N/A' }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info"><i class="bx bx-show"></i></button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bx bx-cart bx-lg d-block mb-2" style="font-size: 48px;"></i>
                                                    <p class="mb-0">No orders found</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                @if(isset($orders) && method_exists($orders, 'links'))
                                    {{ $orders->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>