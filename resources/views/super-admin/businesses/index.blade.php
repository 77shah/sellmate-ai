<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">🏢 Businesses</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">Businesses</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">All Businesses</h4>
                                <a href="{{ route('super-admin.businesses.create') }}" class="btn btn-primary">
                                    <i class="bx bx-plus"></i> Add Business
                                </a>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-alert-circle me-2"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Search & Filter -->
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <form method="GET" class="d-flex">
                                        <input type="text" class="form-control" name="search" 
                                               placeholder="Search by name, email or tenant ID..." 
                                               value="{{ request('search') }}">
                                        <select class="form-control ms-2" name="status" style="width:150px;">
                                            <option value="">All Status</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary ms-2">
                                            <i class="bx bx-search"></i> Search
                                        </button>
                                        <a href="{{ route('super-admin.businesses') }}" class="btn btn-secondary ms-2">
                                            <i class="bx bx-refresh"></i> Reset
                                        </a>
                                    </form>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="text-muted">Total: {{ $businesses->total() }} businesses</span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Business Name</th>
                                            <th>Tenant ID</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Joined</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($businesses as $index => $business)
                                        <tr>
                                            <td>{{ $businesses->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $business->name }}</strong>
                                                @if(isset($business->biz_name) && $business->biz_name && $business->biz_name != $business->name)
                                                    <br><small class="text-muted">{{ $business->biz_name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <code>{{ $business->tenant_id }}</code>
                                            </td>
                                            <td>{{ $business->email }}</td>
                                            <td>
                                                {{ $business->biz_phone ?? $business->mobile_no ?? '—' }}
                                            </td>
                                            <td>
                                                @if(isset($business->biz_type) && $business->biz_type)
                                                    <span class="badge bg-info">{{ ucfirst($business->biz_type) }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($business->status == 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($business->status == 'suspended')
                                                    <span class="badge bg-danger">Suspended</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $business->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $business->created_at ? $business->created_at->format('d M Y') : 'N/A' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('super-admin.businesses.show', $business->id) }}" 
                                                       class="btn btn-sm btn-info" title="View">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="{{ route('super-admin.businesses.edit', $business->id) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    
                                                    @if($business->status == 'active')
                                                        <form action="{{ route('super-admin.businesses.suspend', $business->id) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    title="Suspend" 
                                                                    onclick="return confirm('Are you sure you want to suspend this business?')">
                                                                <i class="bx bx-block"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('super-admin.businesses.activate', $business->id) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" 
                                                                    title="Activate"
                                                                    onclick="return confirm('Are you sure you want to activate this business?')">
                                                                <i class="bx bx-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <form action="{{ route('super-admin.businesses.impersonate', $business->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" 
                                                                title="Impersonate"
                                                                onclick="return confirm('Login as this business owner?')">
                                                            <i class="bx bx-user-check"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="{{ route('super-admin.businesses.delete', $business->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                title="Delete"
                                                                onclick="return confirm('Delete this business permanently? This action cannot be undone.')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i class="bx bx-store bx-lg d-block mb-2" style="font-size:48px;"></i>
                                                <h5>No businesses found</h5>
                                                <p class="mb-0">Click "Add Business" to create your first business.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end">
                                {{ $businesses->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>