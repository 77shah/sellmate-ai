<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📄 Reports</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Reports</li>
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
                                <div class="col-md-3">
                                    <label class="form-label">Report Type</label>
                                    <select class="form-select" name="report_type">
                                        <option value="orders" {{ request('report_type', 'orders') == 'orders' ? 'selected' : '' }}>Orders</option>
                                        <option value="customers" {{ request('report_type') == 'customers' ? 'selected' : '' }}>Customers</option>
                                        <option value="payments" {{ request('report_type') == 'payments' ? 'selected' : '' }}>Payments</option>
                                        <option value="ai_usage" {{ request('report_type') == 'ai_usage' ? 'selected' : '' }}>AI Usage</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date From</label>
                                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from', $date_from ?? now()->startOfMonth()->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Date To</label>
                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to', $date_to ?? now()->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-search"></i> Generate Report
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Button -->
            <div class="row mb-3">
                <div class="col-md-12 text-end">
                    <a href="{{ route('owner.reports.export', request('report_type', 'orders')) }}?date_from={{ request('date_from', $date_from ?? '') }}&date_to={{ request('date_to', $date_to ?? '') }}" 
                       class="btn btn-success">
                        <i class="bx bx-download"></i> Export CSV
                    </a>
                </div>
            </div>

            <!-- Report Data -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">
                                {{ ucfirst(request('report_type', 'orders')) }} Report
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            @if(request('report_type', 'orders') == 'orders')
                                                <th>#</th>
                                                <th>Order ID</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            @elseif(request('report_type') == 'customers')
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Stage</th>
                                                <th>Date</th>
                                            @elseif(request('report_type') == 'payments')
                                                <th>#</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Gateway</th>
                                                <th>Date</th>
                                            @else
                                                <th>#</th>
                                                <th>Intent</th>
                                                <th>Tokens Used</th>
                                                <th>Cost</th>
                                                <th>Date</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(${$report_type ?? 'orders'} ?? [] as $index => $item)
                                        <tr>
                                            @if(request('report_type', 'orders') == 'orders')
                                                <td>{{ $loop->iteration }}</td>
                                                <td>#{{ $item->order_number ?? $item->id }}</td>
                                                <td>{{ $item->customer->name ?? 'N/A' }}</td>
                                                <td>₹{{ number_format($item->total_amount ?? 0, 2) }}</td>
                                                <td><span class="badge bg-{{ ($item->payment_status ?? 'pending') == 'paid' ? 'success' : 'warning' }}">{{ ucfirst($item->payment_status ?? 'Pending') }}</span></td>
                                                <td>{{ $item->created_at ? $item->created_at->format('d M Y') : 'N/A' }}</td>
                                            @elseif(request('report_type') == 'customers')
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->phone }}</td>
                                                <td>{{ $item->email ?? 'N/A' }}</td>
                                                <td><span class="badge bg-primary">{{ ucfirst($item->stage ?? 'new') }}</span></td>
                                                <td>{{ $item->created_at ? $item->created_at->format('d M Y') : 'N/A' }}</td>
                                            @elseif(request('report_type') == 'payments')
                                                <td>{{ $loop->iteration }}</td>
                                                <td>₹{{ number_format($item->amount ?? 0, 2) }}</td>
                                                <td><span class="badge bg-{{ ($item->status ?? 'pending') == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($item->status ?? 'Pending') }}</span></td>
                                                <td>{{ ucfirst($item->gateway ?? 'N/A') }}</td>
                                                <td>{{ $item->created_at ? $item->created_at->format('d M Y') : 'N/A' }}</td>
                                            @else
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->intent ?? 'N/A' }}</td>
                                                <td>{{ $item->tokens_used ?? 0 }}</td>
                                                <td>₹{{ number_format($item->cost ?? 0, 4) }}</td>
                                                <td>{{ $item->created_at ? $item->created_at->format('d M Y') : 'N/A' }}</td>
                                            @endif
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No data found for the selected period</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>