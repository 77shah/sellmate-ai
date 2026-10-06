<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">✏️ Edit Staff Member</h4>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('owner.staff.update', $staff->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ old('name', $staff->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $staff->email) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone</label>
                                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $staff->phone) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Role <span class="text-danger">*</span></label>
                                            <select class="form-select" name="type" required>
                                                <option value="Staff" {{ old('type', $staff->type) == 'Staff' ? 'selected' : '' }}>Staff</option>
                                                <option value="SalesAgent" {{ old('type', $staff->type) == 'SalesAgent' ? 'selected' : '' }}>Sales Agent</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="active" {{ old('status', $staff->status) == 'active' ? 'selected' : '' }}>✅ Active</option>
                                        <option value="inactive" {{ old('status', $staff->status) == 'inactive' ? 'selected' : '' }}>❌ Inactive</option>
                                    </select>
                                </div>

                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <strong>Note:</strong> Password change karne ke liye alag se option hai.
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Update Staff
                                </button>
                                <a href="{{ route('owner.staff') }}" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>