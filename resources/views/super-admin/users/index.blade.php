<x-layouts.app>

    @section('title', 'Users Management')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">👥 Users Management</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">Users</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="mdi mdi-alert-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">All Users</h4>
                                <a href="{{ route('super-admin.users.create') }}" class="btn btn-primary">
                                    <i class="bx bx-plus me-1"></i> Add User
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Plan</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $index => $user)
                                        <tr id="user-row-{{ $user->id }}">
                                            <td>{{ $users->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->type == 'SuperAdmin')
                                                    <span class="badge bg-danger ms-1">Admin</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge 
                                                    @if($user->type == 'SuperAdmin') bg-danger
                                                    @elseif($user->type == 'Admin') bg-primary
                                                    @elseif($user->type == 'Owner') bg-success
                                                    @elseif($user->type == 'Staff') bg-info
                                                    @elseif($user->type == 'SalesAgent') bg-warning
                                                    @endif">
                                                    {{ $user->type }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input toggle-status" 
                                                           data-id="{{ $user->id }}"
                                                           data-url="{{ route('super-admin.users.toggle', $user->id) }}"
                                                           {{ $user->status == 'active' ? 'checked' : '' }}
                                                           {{ $user->type == 'SuperAdmin' ? 'disabled' : '' }}>
                                                    <span class="badge {{ $user->status == 'active' ? 'bg-success' : 'bg-danger' }} status-badge-{{ $user->id }}">
                                                        {{ $user->status == 'active' ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($user->currentPlan)
                                                    <span class="badge bg-info">{{ $user->currentPlan->name }}</span>
                                                @else
                                                    <span class="text-muted">No Plan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('super-admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                @if($user->type != 'SuperAdmin')
                                                    <form action="{{ route('super-admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bx bx-user bx-lg d-block mb-2" style="font-size: 48px;"></i>
                                                    <p class="mb-0">No users found</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.toggle-status').change(function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var url = checkbox.data('url');
                var isChecked = checkbox.is(':checked');
                var badge = checkbox.closest('td').find('.status-badge-' + id);

                $.ajax({
                    url: url,
                    type: 'PATCH',
                    data: { _token: '{{ csrf_token() }}' },
                    beforeSend: function() {
                        checkbox.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.status == 'active') {
                                badge.removeClass('bg-danger').addClass('bg-success').text('Active');
                            } else {
                                badge.removeClass('bg-success').addClass('bg-danger').text('Inactive');
                            }
                        }
                    },
                    error: function() {
                        checkbox.prop('checked', !isChecked);
                        alert('Error updating status.');
                    },
                    complete: function() {
                        checkbox.prop('disabled', false);
                    }
                });
            });
        });
    </script>
    @endpush

</x-layouts.app>