<x-layouts.app>

    @section('title', 'Upload Knowledge')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📤 Upload Knowledge</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('owner.knowledge.index') }}">Knowledge Base</a></li>
                                <li class="breadcrumb-item active">Upload</li>
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
                            <h5 class="mb-4">Upload Document for AI Training</h5>
                            <p class="text-muted">AI will learn from this document to answer customer questions better.</p>

                            <form action="{{ route('owner.knowledge.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Document Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="e.g., Product Catalog 2024">
                                    <small class="text-muted">Leave empty to use file name</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Document Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="pdf">PDF</option>
                                        <option value="excel">Excel (XLSX/XLS)</option>
                                        <option value="website">Text/CSV</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Upload File <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file" required accept=".pdf,.doc,.docx,.xlsx,.xls,.csv,.txt">
                                    <small class="text-muted">Max size: 10MB. Supported: PDF, Excel, CSV, TXT</small>
                                </div>

                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <strong>How it works:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Document will be processed and added to AI knowledge</li>
                                        <li>AI will use this knowledge to answer customer questions</li>
                                        <li>Processing may take 1-2 minutes</li>
                                    </ul>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-upload me-1"></i> Upload & Process
                                    </button>
                                    <a href="{{ route('owner.knowledge.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>