<x-layouts.app>

    @section('title', 'Add User')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">➕ Add New User</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.users.index') }}">Users</a></li>
                                <li class="breadcrumb-item active">Add</li>
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
                            <form action="{{ route('super-admin.users.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Mobile No</label>
                                            <input type="text" class="form-control" name="mobile_no" value="{{ old('mobile_no') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">WhatsApp No</label>
                                            <input type="text" class="form-control" name="whatsapp_no" value="{{ old('whatsapp_no') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Role <span class="text-danger">*</span></label>
                                            <select class="form-select" name="type" required>
                                                <option value="">Select Role</option>
                                                <option value="SuperAdmin" {{ old('type') == 'SuperAdmin' ? 'selected' : '' }}>Super Admin</option>
                                                <option value="Admin" {{ old('type') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="Owner" {{ old('type') == 'Owner' ? 'selected' : '' }}>Business Owner</option>
                                                <option value="Staff" {{ old('type') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                                <option value="SalesAgent" {{ old('type') == 'SalesAgent' ? 'selected' : '' }}>Sales Agent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Assign Plan (Optional)</label>
                                            <select class="form-select" name="current_plan_id">
                                                <option value="">No Plan</option>
                                                @foreach($plans as $plan)
                                                    <option value="{{ $plan->id }}" {{ old('current_plan_id') == $plan->id ? 'selected' : '' }}>
                                                        {{ $plan->name }} - ₹{{ number_format($plan->price, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i> Create User
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
        // Password confirmation validation
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