<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📦 Orders</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Owner</a></li>
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
                                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}" placeholder="From">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}" placeholder="To">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-filter me-1"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('owner.orders') }}" class="btn btn-secondary w-100">
                                        <i class="bx bx-reset me-1"></i> Reset
                                    </a>
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
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">All Orders</h4>
                                <span class="badge bg-primary">{{ $orders->total() ?? 0 }} Total</span>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="mdi mdi-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="mdi mdi-alert-circle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
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
                                            <td>
                                                <strong class="text-primary">#{{ $order->order_number ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $order->customer->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $order->customer->phone ?? '' }}</small>
                                            </td>
                                            <td>
                                                <strong>₹{{ number_format($order->total_amount ?? 0, 2) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge {{ $order->status == 'paid' ? 'bg-success' : ($order->status == 'pending' ? 'bg-warning' : ($order->status == 'cancelled' ? 'bg-danger' : 'bg-info')) }}">
                                                    {{ ucfirst($order->status ?? 'Pending') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $order->payment_status == 'paid' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($order->payment_status ?? 'Pending') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="bx {{ $order->source == 'whatsapp' ? 'bxl-whatsapp' : ($order->source == 'instagram' ? 'bxl-instagram' : 'bx-globe') }}"></i>
                                                    {{ ucfirst($order->source ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ $order->created_at ? $order->created_at->format('d M Y, h:i A') : 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info" title="View">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bx bx-cart bx-lg d-block mb-2" style="font-size: 48px;"></i>
                                                    <p class="mb-0">No orders found</p>
                                                    <small>Create your first order by adding a customer and placing an order.</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    @if($orders->total() > 0)
                                        <small class="text-muted">
                                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} entries
                                        </small>
                                    @endif
                                </div>
                                <div>
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
    </div>

</x-layouts.app>