<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">➕ Add Customer</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('owner.crm') }}">CRM</a></li>
                                <li class="breadcrumb-item active">Add</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Customer Details</h4>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('owner.crm.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                                   name="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   name="email" value="{{ old('email') }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Lead Score</label>
                                            <input type="number" class="form-control @error('lead_score') is-invalid @enderror" 
                                                   name="lead_score" value="{{ old('lead_score', 0) }}" min="0" max="100">
                                            @error('lead_score')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Stage</label>
                                            <select class="form-select @error('stage') is-invalid @enderror" name="stage">
                                                <option value="new" {{ old('stage') == 'new' ? 'selected' : '' }}>🆕 New</option>
                                                <option value="contacted" {{ old('stage') == 'contacted' ? 'selected' : '' }}>📞 Contacted</option>
                                                <option value="qualified" {{ old('stage') == 'qualified' ? 'selected' : '' }}>⭐ Qualified</option>
                                                <option value="order_placed" {{ old('stage') == 'order_placed' ? 'selected' : '' }}>🛒 Order Placed</option>
                                                <option value="closed" {{ old('stage') == 'closed' ? 'selected' : '' }}>✅ Closed</option>
                                            </select>
                                            @error('stage')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Tags</label>
                                            <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                                                   name="tags" value="{{ old('tags') }}" placeholder="VIP, New, etc.">
                                            @error('tags')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              name="address" rows="2">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              name="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save"></i> Save Customer
                                    </button>
                                    <a href="{{ route('owner.crm') }}" class="btn btn-secondary">
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