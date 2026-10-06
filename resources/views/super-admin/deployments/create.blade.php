<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">🚀 New Deployment</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.deployments') }}">Deployments</a></li>
                                <li class="breadcrumb-item active">New Deployment</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Deployment Details</h4>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- 🔥 FIXED: Route name -->
                            <form action="{{ route('super-admin.deployments.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Version <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('version') is-invalid @enderror" 
                                                   name="version" value="{{ old('version', '1.0.' . rand(1, 99)) }}" 
                                                   placeholder="e.g., 1.0.0" required>
                                            @error('version')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Branch</label>
                                            <select class="form-select @error('branch') is-invalid @enderror" name="branch">
                                                <option value="main" {{ old('branch') == 'main' ? 'selected' : '' }}>main</option>
                                                <option value="develop" {{ old('branch') == 'develop' ? 'selected' : '' }}>develop</option>
                                                <option value="staging" {{ old('branch') == 'staging' ? 'selected' : '' }}>staging</option>
                                                <option value="feature" {{ old('branch') == 'feature' ? 'selected' : '' }}>feature</option>
                                            </select>
                                            @error('branch')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Commit Hash</label>
                                            <input type="text" class="form-control @error('commit_hash') is-invalid @enderror" 
                                                   name="commit_hash" value="{{ old('commit_hash', substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 7)) }}" 
                                                   placeholder="e.g., a1b2c3d">
                                            @error('commit_hash')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Environment</label>
                                            <select class="form-select @error('environment') is-invalid @enderror" name="environment">
                                                <option value="production" {{ old('environment') == 'production' ? 'selected' : '' }}>Production</option>
                                                <option value="staging" {{ old('environment') == 'staging' ? 'selected' : '' }}>Staging</option>
                                                <option value="development" {{ old('environment') == 'development' ? 'selected' : '' }}>Development</option>
                                            </select>
                                            @error('environment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Deployment Message</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              name="message" rows="3" 
                                              placeholder="What does this deployment include?">{{ old('message', 'Deployment initiated via Super Admin panel') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <strong>Note:</strong> This will trigger a deployment process. It may take a few moments.
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-rocket me-1"></i> Deploy Now
                                    </button>
                                    <a href="{{ route('super-admin.deployments') }}" class="btn btn-secondary">
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