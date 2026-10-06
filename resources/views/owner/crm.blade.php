<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">👥 CRM</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">CRM</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lead Stages -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col">
                                    <div class="p-3 border rounded">
                                        <h5>🆕 New</h5>
                                        <h3 class="text-primary">{{ $stages['new'] ?? 0 }}</h3>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-3 border rounded">
                                        <h5>📞 Contacted</h5>
                                        <h3 class="text-info">{{ $stages['contacted'] ?? 0 }}</h3>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-3 border rounded">
                                        <h5>⭐ Qualified</h5>
                                        <h3 class="text-success">{{ $stages['qualified'] ?? 0 }}</h3>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-3 border rounded">
                                        <h5>🛒 Order Placed</h5>
                                        <h3 class="text-warning">{{ $stages['order_placed'] ?? 0 }}</h3>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-3 border rounded">
                                        <h5>✅ Closed</h5>
                                        <h3 class="text-secondary">{{ $stages['closed'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Add -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Search customers..." value="{{ request('search') }}">
                        <select class="form-select ms-2" name="stage" style="width:150px;">
                            <option value="">All Stages</option>
                            <option value="new" {{ request('stage') == 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ request('stage') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="qualified" {{ request('stage') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                            <option value="order_placed" {{ request('stage') == 'order_placed' ? 'selected' : '' }}>Order Placed</option>
                            <option value="closed" {{ request('stage') == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="bx bx-search"></i> Search
                        </button>
                        <a href="{{ route('owner.crm') }}" class="btn btn-secondary ms-2">
                            <i class="bx bx-refresh"></i> Reset
                        </a>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('owner.crm.create') }}" class="btn btn-success">
                        <i class="bx bx-plus"></i> Add Customer
                    </a>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Customers ({{ $totalCustomers ?? 0 }})</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Lead Score</th>
                                            <th>Stage</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customers ?? [] as $index => $customer)
                                        <tr>
                                            <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $customer->name }}</strong>
                                            </td>
                                            <td>{{ $customer->phone }}</td>
                                            <td>{{ $customer->email ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge {{ $customer->lead_score >= 70 ? 'bg-success' : ($customer->lead_score >= 40 ? 'bg-warning' : 'bg-secondary') }}">
                                                    {{ $customer->lead_score ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ ucfirst($customer->stage ?? 'new') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $customer->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($customer->status ?? 'active') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('owner.crm.show', $customer->id) }}" 
                                                       class="btn btn-sm btn-info" title="View">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="{{ route('owner.crm.edit', $customer->id) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <form action="{{ route('owner.crm.delete', $customer->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                title="Delete"
                                                                onclick="return confirm('Delete this customer?')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                <i class="bx bx-user bx-lg d-block mb-2" style="font-size:48px;"></i>
                                                <h5>No customers found</h5>
                                                <p class="mb-0">Click "Add Customer" to add your first customer.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end">
                                @if(isset($customers) && method_exists($customers, 'links'))
                                    {{ $customers->appends(request()->query())->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>