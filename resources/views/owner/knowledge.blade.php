<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📚 Knowledge Base</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Knowledge Base</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 🔥 System Status Check --}}
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>🔍 System Status:</strong>
                                </div>
                                <div>
                                    <span class="me-3">
                                        Python AI: <span id="pythonStatus" class="badge bg-secondary">Checking...</span>
                                    </span>
                                    <span class="me-3">
                                        Qdrant DB: <span id="qdrantStatus" class="badge bg-secondary">Checking...</span>
                                    </span>
                                    <span>
                                        Overall: <span id="overallStatus" class="badge bg-secondary">Checking...</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 🔥 PLAN USAGE BANNER --}}
            @php
                $plan = Auth::user()->currentPlan;
                $maxChunks = $plan->knowledge_max_chunks ?? 15;
                $maxChars = $plan->knowledge_max_chars ?? 15000;
                $maxDocuments = $plan->max_documents ?? 2;

                $tenantId = Auth::user()->tenant_id;
                $usageDocs = \App\Models\KnowledgeBase::where('tenant_id', $tenantId)
                    ->whereIn('status', ['processed', 'processing'])
                    ->get();
                
                $currentChunks = 0;
                $currentChars = 0;
                foreach ($usageDocs as $d) {
                    $currentChunks += $d->metadata['chunks_added'] ?? 0;
                    $currentChars += $d->metadata['text_length'] ?? 0;
                }
                $currentDocs = $usageDocs->count();

                $chunkPercent = $maxChunks > 0 ? min(100, round(($currentChunks / $maxChunks) * 100)) : 0;
                $docPercent = $maxDocuments > 0 ? min(100, round(($currentDocs / $maxDocuments) * 100)) : 0;

                $isAtChunkLimit = $currentChunks >= $maxChunks;
                $isAtDocLimit = $currentDocs >= $maxDocuments;
                $canUpload = !$isAtChunkLimit && !$isAtDocLimit;
            @endphp

            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="flex-grow-1">
                                    <h5 class="text-white mb-3">
                                        <i class="bx bx-crown"></i>
                                        {{ $plan->name ?? 'Free' }} Plan — Knowledge Base Usage
                                    </h5>
                                    
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div style="background: rgba(255,255,255,0.15); padding: 12px; border-radius: 8px;">
                                                <small class="opacity-75 d-block mb-1">Documents</small>
                                                <h4 class="mb-1 text-white">{{ $currentDocs }} / {{ $maxDocuments }}</h4>
                                                <div class="progress" style="height: 4px; background: rgba(255,255,255,0.2);">
                                                    <div class="progress-bar bg-white" style="width: {{ $docPercent }}%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div style="background: rgba(255,255,255,0.15); padding: 12px; border-radius: 8px;">
                                                <small class="opacity-75 d-block mb-1">Chunks Used</small>
                                                <h4 class="mb-1 text-white">{{ $currentChunks }} / {{ $maxChunks }}</h4>
                                                <div class="progress" style="height: 4px; background: rgba(255,255,255,0.2);">
                                                    <div class="progress-bar bg-white" style="width: {{ $chunkPercent }}%;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div style="background: rgba(255,255,255,0.15); padding: 12px; border-radius: 8px;">
                                                <small class="opacity-75 d-block mb-1">Max Per Doc</small>
                                                <h4 class="mb-1 text-white">{{ number_format($maxChars) }}</h4>
                                                <small class="opacity-75">characters</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3 mt-md-0 ms-md-3">
                                    @if(!$canUpload)
                                        <a href="{{ route('owner.plans') }}" class="btn btn-light">
                                            <i class="bx bx-up-arrow-alt"></i> Upgrade Plan
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Warning if limits reached --}}
            @if(!$canUpload)
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="bx bx-error-circle me-2"></i>
                            <strong>
                                @if($isAtDocLimit && $isAtChunkLimit)
                                    Document and Chunk limit reached!
                                @elseif($isAtDocLimit)
                                    Document limit reached!
                                @else
                                    Chunk limit reached!
                                @endif
                            </strong>
                            You cannot upload more documents. 
                            Delete old documents or <a href="{{ route('owner.plans') }}" class="alert-link">upgrade your plan</a>.
                        </div>
                    </div>
                </div>
            @endif

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="white-space: pre-line;">
                    <i class="bx bx-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="white-space: pre-line;">
                    <i class="bx bx-x-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Validation Errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Upload Section --}}
            @if($canUpload)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">📤 Upload Document</h4>

                            <form action="{{ route('owner.knowledge.store') }}" 
                                  method="POST" 
                                  enctype="multipart/form-data" 
                                  id="uploadForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Document Name <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="name"
                                                   value="{{ old('name') }}"
                                                   placeholder="e.g. Menu 2026"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="type" id="typeSelect" required>
                                                <option value="">Select</option>
                                                <option value="pdf">📄 PDF</option>
                                                <option value="excel">📊 Excel</option>
                                                <option value="website">🌐 Text</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">File <span class="text-danger">*</span></label>
                                            <input type="file" 
                                                   class="form-control" 
                                                   name="file" 
                                                   required
                                                   accept=".pdf,.doc,.docx,.xlsx,.xls,.csv,.txt"
                                                   id="fileInput">
                                            <small class="text-muted">
                                                Max: 10 MB | 
                                                Max chars: {{ number_format($maxChars) }} |
                                                Remaining chunks: {{ max(0, $maxChunks - $currentChunks) }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                                <i class="bx bx-upload me-1"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="fileInfo" class="mt-2"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card border-warning">
                        <div class="card-body text-center py-5">
                            <i class="bx bx-lock text-warning" style="font-size: 64px;"></i>
                            <h4 class="mt-3">Upload Disabled</h4>
                            <p class="text-muted">
                                You've reached your plan limits. Delete old documents or upgrade to continue.
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('owner.plans') }}" class="btn btn-primary">
                                    <i class="bx bx-up-arrow-alt"></i> Upgrade Plan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Documents List --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">
                                    📚 Uploaded Documents 
                                    <span class="badge bg-primary">{{ $documents->total() ?? 0 }}</span>
                                </h4>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Chunks</th>
                                            <th>Status</th>
                                            <th>Uploaded</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($documents ?? [] as $index => $doc)
                                        <tr>
                                            <td>{{ $documents->firstItem() + $index }}</td>
                                            <td>
                                                <strong>{{ $doc->name }}</strong>
                                                @if(isset($doc->metadata['text_length']))
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ number_format($doc->metadata['text_length']) }} chars
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @if($doc->type == 'pdf') bg-danger
                                                    @elseif($doc->type == 'excel') bg-success
                                                    @else bg-info
                                                    @endif">
                                                    {{ strtoupper($doc->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if(isset($doc->metadata['chunks_added']))
                                                    <span class="badge bg-primary">
                                                        {{ $doc->metadata['chunks_added'] }} chunks
                                                    </span>
                                                @elseif(isset($doc->metadata['estimated_chunks']))
                                                    <span class="badge bg-secondary">
                                                        ~{{ $doc->metadata['estimated_chunks'] }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($doc->status == 'processed')
                                                    <span class="badge bg-success">
                                                        <i class="bx bx-check-circle me-1"></i> Processed
                                                    </span>
                                                @elseif($doc->status == 'processing')
                                                    <span class="badge bg-warning">
                                                        <i class="bx bx-loader-alt bx-spin me-1"></i> Processing
                                                    </span>
                                                @elseif($doc->status == 'failed')
                                                    <span class="badge bg-danger">
                                                        <i class="bx bx-x-circle me-1"></i> Failed
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($doc->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $doc->created_at ? $doc->created_at->format('d M Y H:i') : 'N/A' }}</td>
                                            <td>
                                                @if(in_array($doc->status, ['failed', 'processing', 'processed']))
                                                    <form action="{{ route('owner.knowledge.reprocess', $doc->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-warning" 
                                                                title="Reprocess"
                                                                onclick="return confirm('Reprocess this document?')">
                                                            <i class="bx bx-refresh"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('owner.knowledge.destroy', $doc->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Delete"
                                                            onclick="return confirm('Delete this document?')">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="bx bx-book bx-lg d-block mb-2 text-muted" style="font-size: 48px;"></i>
                                                <p class="text-muted mb-0">No documents uploaded</p>
                                                <small class="text-muted">Upload PDF, Excel, or text files to train your AI.</small>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if(isset($documents) && method_exists($documents, 'links'))
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $documents->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // 🔥 File Info + Auto Type Select
        document.getElementById('fileInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileInfo = document.getElementById('fileInfo');
            const typeSelect = document.getElementById('typeSelect');
            
            if (file) {
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                fileInfo.innerHTML = `
                    <div class="alert alert-success py-2 mb-0">
                        <i class="bx bx-file me-1"></i>
                        <strong>${file.name}</strong> (${sizeMB} MB)
                    </div>
                `;
                
                const ext = file.name.split('.').pop().toLowerCase();
                if (ext === 'pdf') {
                    typeSelect.value = 'pdf';
                } else if (['xlsx', 'xls', 'csv'].includes(ext)) {
                    typeSelect.value = 'excel';
                } else if (['txt'].includes(ext)) {
                    typeSelect.value = 'website';
                }
            } else {
                fileInfo.innerHTML = '';
            }
        });

        // 🔥 Form Submit - Disable Button
        document.getElementById('uploadForm')?.addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Uploading...';
        });

        // 🔥 System Status Check
        async function checkSystemStatus() {
            try {
                const pythonResponse = await fetch('http://localhost:8001/health');
                const pythonData = await pythonResponse.json();
                if (pythonResponse.ok && pythonData.status === 'healthy') {
                    document.getElementById('pythonStatus').className = 'badge bg-success';
                    document.getElementById('pythonStatus').textContent = '✅ Online';
                } else {
                    throw new Error('Not healthy');
                }
            } catch (e) {
                document.getElementById('pythonStatus').className = 'badge bg-danger';
                document.getElementById('pythonStatus').textContent = '❌ Offline';
            }

            try {
                const qdrantResponse = await fetch('http://localhost:6333/healthz');
                if (qdrantResponse.ok) {
                    document.getElementById('qdrantStatus').className = 'badge bg-success';
                    document.getElementById('qdrantStatus').textContent = '✅ Online';
                } else {
                    throw new Error('Not responding');
                }
            } catch (e) {
                document.getElementById('qdrantStatus').className = 'badge bg-danger';
                document.getElementById('qdrantStatus').textContent = '❌ Offline';
            }

            const pythonOk = document.getElementById('pythonStatus').textContent.includes('✅');
            const qdrantOk = document.getElementById('qdrantStatus').textContent.includes('✅');
            
            if (pythonOk && qdrantOk) {
                document.getElementById('overallStatus').className = 'badge bg-success';
                document.getElementById('overallStatus').textContent = '✅ Ready';
            } else {
                document.getElementById('overallStatus').className = 'badge bg-danger';
                document.getElementById('overallStatus').textContent = '⚠️ Check Services';
            }
        }

        checkSystemStatus();
        setInterval(checkSystemStatus, 10000);

        @if(isset($documents) && method_exists($documents, 'getCollection') && $documents->getCollection()->where('status', 'processing')->count() > 0)
            setTimeout(function() {
                location.reload();
            }, 30000);
        @endif
    </script>
    @endpush

</x-layouts.app>