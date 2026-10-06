<x-layouts.app>

    @section('title', 'Edit User')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">✏️ Edit User - {{ $user->name }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.users.index') }}">Users</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('super-admin.users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">New Password</label>
                                            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Confirm New Password</label>
                                            <input type="password" class="form-control" name="password_confirmation" placeholder="Leave blank to keep current">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Mobile No</label>
                                            <input type="text" class="form-control" name="mobile_no" value="{{ old('mobile_no', $user->mobile_no) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">WhatsApp No</label>
                                            <input type="text" class="form-control" name="whatsapp_no" value="{{ old('whatsapp_no', $user->whatsapp_no) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                                            <select class="form-select" name="type" required>
                                                <option value="SuperAdmin" {{ old('type', $user->type) == 'SuperAdmin' ? 'selected' : '' }}>Super Admin</option>
                                                <option value="Admin" {{ old('type', $user->type) == 'Admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="Owner" {{ old('type', $user->type) == 'Owner' ? 'selected' : '' }}>Business Owner</option>
                                                <option value="Staff" {{ old('type', $user->type) == 'Staff' ? 'selected' : '' }}>Staff</option>
                                                <option value="SalesAgent" {{ old('type', $user->type) == 'SalesAgent' ? 'selected' : '' }}>Sales Agent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Assign Plan</label>
                                            <select class="form-select" name="current_plan_id">
                                                <option value="">No Plan</option>
                                                @foreach($plans as $plan)
                                                    <option value="{{ $plan->id }}" {{ old('current_plan_id', $user->current_plan_id) == $plan->id ? 'selected' : '' }}>
                                                        {{ $plan->name }} - ₹{{ number_format($plan->price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i> Update User
                                    </button>
                                    <a href="{{ route('super-admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            var password = document.querySelector('input[name="password"]').value;
            var confirm = document.querySelector('input[name="password_confirmation"]').value;
            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
    @endpush

</x-layouts.app>