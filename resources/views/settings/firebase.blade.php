<x-layouts.app>
    <div class="page-content">
        <div class="container-fluid">

            <!-- Start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Firebase Settings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                               
                                <li class="breadcrumb-item active">Firebase Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End page title -->

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-alert-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('firebase.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf                                

                                <!-- Form Fields -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Project Name</label>
                                            <input type="text" class="form-control @error('project_name') is-invalid @enderror" 
                                                   name="project_name" value="{{ old('project_name', $firebase->project_name ?? '') }}" 
                                                   placeholder="Enter project name">
                                            @error('project_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Project ID</label>
                                            <input type="text" class="form-control @error('project_id') is-invalid @enderror" 
                                                   name="project_id" value="{{ old('project_id', $firebase->project_id ?? '') }}" 
                                                   placeholder="Enter project ID">
                                            @error('project_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Project Number</label>
                                            <input type="text" class="form-control @error('project_number') is-invalid @enderror" 
                                                   name="project_number" value="{{ old('project_number', $firebase->project_number ?? '') }}" 
                                                   placeholder="Enter project number">
                                            @error('project_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">App ID</label>
                                            <input type="text" class="form-control @error('app_id') is-invalid @enderror" 
                                                   name="app_id" value="{{ old('app_id', $firebase->app_id ?? '') }}" 
                                                   placeholder="Enter app ID">
                                            @error('app_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Package Name</label>
                                            <input type="text" class="form-control @error('package_name') is-invalid @enderror" 
                                                   name="package_name" value="{{ old('package_name', $firebase->package_name ?? '') }}" 
                                                   placeholder="Enter package name">
                                            @error('package_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sender ID</label>
                                            <input type="text" class="form-control @error('sender_id') is-invalid @enderror" 
                                                   name="sender_id" value="{{ old('sender_id', $firebase->sender_id ?? '') }}" 
                                                   placeholder="Enter sender ID">
                                            @error('sender_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Server Key</label>
                                            <textarea class="form-control @error('server_key') is-invalid @enderror" 
                                                      name="server_key" rows="3" 
                                                      placeholder="Enter server key">{{ old('server_key', $firebase->server_key ?? '') }}</textarea>
                                            @error('server_key')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Key Pair</label>
                                            <textarea class="form-control @error('key_pair') is-invalid @enderror" 
                                                      name="key_pair" rows="2" 
                                                      placeholder="Enter key pair">{{ old('key_pair', $firebase->key_pair ?? '') }}</textarea>
                                            @error('key_pair')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- JSON File Upload Field -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">JSON Configuration File</label>
                                            <div class="input-group">
                                                <input type="file" class="form-control @error('json_file') is-invalid @enderror" 
                                                       name="json_file" id="jsonFile" accept=".json,.txt">
                                                @if(isset($firebase) && $firebase->json_file)
                                                    <a href="{{ route('firebase.download', $firebase->id) }}" 
                                                       class="btn btn-primary" target="_blank">
                                                        <i class="bx bx-download"></i> Download Current File
                                                    </a>
                                                @endif
                                            </div>
                                            <small class="text-muted">Upload google-services.json or GoogleService-Info.plist file (Max: 2MB)</small>
                                            @error('json_file')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                            
                                            @if(isset($firebase) && $firebase->json_file)
                                                <div class="mt-2">
                                                    <span class="badge bg-success">
                                                        <i class="bx bx-check"></i> JSON file uploaded
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bx bx-save me-2"></i>Save Settings
                                        </button>
                                        <button type="reset" class="btn btn-secondary px-4 ms-2 d-none">
                                            <i class="bx bx-reset me-2"></i>Reset
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Error Display -->
                            @if ($errors->any())
                                <div class="alert alert-danger mt-4">
                                    <h5 class="alert-heading"><i class="bx bx-error-circle me-2"></i>Validation Errors:</h5>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .form-switch.form-switch-lg .form-check-input {
            height: 2rem;
            width: calc(3rem + 0.75rem);
            border-radius: 4rem;
            margin-right: 0.5rem;
            cursor: pointer;
        }
        
        .form-switch.form-switch-lg .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .form-switch.form-switch-lg .form-check-label {
            font-size: 1.1rem;
            padding-top: 0.3rem;
            cursor: pointer;
        }
        
        .card {
            border-radius: 10px;
        }
        
        .form-control:focus {
            border-color: #556ee6;
            box-shadow: 0 0 0 0.15rem rgba(85, 110, 230, 0.25);
        }
        
        textarea {
            resize: vertical;
        }
        
        .input-group .btn {
            border-radius: 0 5px 5px 0;
        }
    </style>

    <!-- JavaScript -->
    <script>
        // Confirmation before reset
        document.querySelector('button[type="reset"]')?.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to reset all fields?')) {
                e.preventDefault();
            }
        });

        // File input validation
        document.getElementById('jsonFile')?.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const fileType = file.name.split('.').pop().toLowerCase();
                if (fileType !== 'json' && fileType !== 'txt') {
                    alert('Please upload only JSON or TXT files');
                    this.value = '';
                }
                
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size should not exceed 2MB');
                    this.value = '';
                }
            }
        });
    </script>

</x-layouts.app>