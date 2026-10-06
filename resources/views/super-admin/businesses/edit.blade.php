<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">✏️ Edit Business</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.businesses') }}">Businesses</a></li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Edit Business: {{ $business->name }}</h4>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('super-admin.businesses.update', $business->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Business Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   name="name" value="{{ old('name', $business->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   name="email" value="{{ old('email', $business->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mobile Number</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                   name="phone" 
                                                   value="{{ old('phone', $businessDetails->phone ?? $business->mobile_no ?? '') }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">WhatsApp Number</label>
                                            <input type="text" class="form-control @error('whatsapp_no') is-invalid @enderror" 
                                                   name="whatsapp_no" 
                                                   value="{{ old('whatsapp_no', $business->whatsapp_no ?? '') }}">
                                            @error('whatsapp_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Business Type</label>
                                            <input type="text" class="form-control @error('business_type') is-invalid @enderror" 
                                                   name="business_type" 
                                                   value="{{ old('business_type', $businessDetails->business_type ?? '') }}" 
                                                   placeholder="e.g., Clinic, Retail, Restaurant">
                                            @error('business_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Website</label>
                                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                                   name="website" 
                                                   value="{{ old('website', $businessDetails->website ?? '') }}" 
                                                   placeholder="https://example.com">
                                            @error('website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              name="address" rows="3">{{ old('address', $businessDetails->address ?? '') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                        <option value="active" {{ old('status', $business->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="suspended" {{ old('status', $business->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <strong>Tenant ID:</strong> <code>{{ $business->tenant_id }}</code>
                                    <br>
                                    <small>Ye ID change nahi hogi. Har tenant ka data is ID se link hai.</small>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save"></i> Update Business
                                    </button>
                                    <a href="{{ route('super-admin.businesses') }}" class="btn btn-secondary">
                                        <i class="bx bx-x"></i> Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>