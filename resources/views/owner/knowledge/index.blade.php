<x-layouts.app>

    @section('title', 'Knowledge Base')

    @section('content')
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

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">Uploaded Documents</h4>
                                <a href="{{ route('owner.knowledge.create') }}" class="btn btn-primary">
                                    <i class="bx bx-upload me-1"></i> Upload New
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Uploaded</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($documents as $index => $doc)
                                        <tr>
                                            <td>{{ $documents->firstItem() + $index }}</td>
                                            <td><strong>{{ $doc->name }}</strong></td>
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
                                                    <span class="badge bg-secondary">{{ $doc->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $doc->created_at ? $doc->created_at->format('d M Y') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('owner.knowledge.show', $doc->id) }}" class="btn btn-sm btn-info">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                @if($doc->status == 'failed')
                                                    <form action="{{ route('owner.knowledge.reprocess', $doc->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning">
                                                            <i class="bx bx-refresh"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('owner.knowledge.destroy', $doc->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this document?')">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bx bx-book bx-lg d-block mb-2" style="font-size: 48px;"></i>
                                                    <p class="mb-0">No documents uploaded</p>
                                                    <small>Upload PDF, Excel, or text files to train your AI.</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                {{ $documents->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>