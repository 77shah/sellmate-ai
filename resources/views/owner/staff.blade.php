<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">👥 Staff Management</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Staff</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Stats -->
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Staff</p>
                            <h4 class="mb-0">{{ $stats['total'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">✅ Active</p>
                            <h4 class="mb-0 text-success">{{ $stats['active'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">❌ Inactive</p>
                            <h4 class="mb-0 text-danger">{{ $stats['inactive'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Staff Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">Staff Members</h4>
                                <a href="{{ route('owner.staff.create') }}" class="btn btn-primary">
                                    <i class="bx bx-plus"></i> Add Staff
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($staff ?? [] as $index => $member)
                                        <tr>
                                            <td>{{ ($staff->currentPage() - 1) * $staff->perPage() + $loop->iteration }}</td>
                                            <td><strong>{{ $member->name }}</strong></td>
                                            <td>{{ $member->email }}</td>
                                            <td>{{ $member->phone ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $member->type }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $member->status == 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($member->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('owner.staff.edit', $member->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <form action="{{ route('owner.staff.toggle', $member->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $member->status == 'active' ? 'btn-secondary' : 'btn-success' }}">
                                                            <i class="bx {{ $member->status == 'active' ? 'bx-pause' : 'bx-play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('owner.staff.delete', $member->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff?')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No staff members found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end">
                                {{ $staff->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>