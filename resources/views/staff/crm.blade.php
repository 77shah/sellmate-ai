<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">👥 CRM</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Sales Agent</a></li>
                                <li class="breadcrumb-item active">CRM</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" class="d-flex">
                        <input type="text" class="form-control" name="search" placeholder="Search customers..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary ms-2">Search</button>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Assigned Customers</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Lead Score</th>
                                            <th>Stage</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customers ?? [] as $index => $customer)
                                        <tr>
                                            <td>{{ $customers->firstItem() + $index ?? $index + 1 }}</td>
                                            <td><strong>{{ $customer->name }}</strong></td>
                                            <td>{{ $customer->phone }}</td>
                                            <td>{{ $customer->email }}</td>
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
                                                <button class="btn btn-sm btn-info"><i class="bx bx-show"></i></button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bx bx-user bx-lg d-block mb-2" style="font-size: 48px;"></i>
                                                    <p class="mb-0">No customers assigned</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                @if(isset($customers) && method_exists($customers, 'links'))
                                    {{ $customers->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>