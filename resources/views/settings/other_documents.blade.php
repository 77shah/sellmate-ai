<x-layouts.app title="Other Documents">
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">{{ $editMode ? 'Update Document' : 'Other Document Upload' }}</h4>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ $editMode ? route('document.update', $editData->id) : route('document.store') }}" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label>Document Name</label>
                        <input type="text" name="document_name" class="form-control" value="{{ old('document_name', optional($editData)->document_name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label>{{ $editMode ? 'Change Document' : 'Upload Document (Multiple)' }}</label>
                        <input type="file" name="{{ $editMode ? 'file' : 'file[]' }}" class="form-control" {{ $editMode ? '' : 'multiple required' }}>
                        @if($editMode && $editData->file)
                            <p class="mt-1">Current: 
                                @if(in_array($editData->type, ['jpg','jpeg','png']))
                                    <img src="{{ asset('uploads/documents/'.$editData->file) }}" width="40">
                                @else
                                    <a href="{{ asset('uploads/documents/'.$editData->file) }}" target="_blank">View PDF</a>
                                @endif
                            </p>
                        @endif
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">{{ $editMode ? 'Update Document' : 'Upload' }}</button>
                    </div>
                </form>

                @if(!$editMode)
                    <h5 class="mt-5 mb-3">Uploaded Documents</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>File</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $key => $doc)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if(in_array($doc->type, ['jpg','jpeg','png']))
                                        <img src="{{ asset('uploads/documents/'.$doc->file) }}" width="50">
                                    @else
                                        <a href="{{ asset('uploads/documents/'.$doc->file) }}" target="_blank">PDF</a>
                                    @endif
                                </td>
                                <td>{{ $doc->document_name }}</td>
                                <td>{{ strtoupper($doc->type) }}</td>
                                <td>
                                    <span class="badge {{ $doc->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($doc->status) }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('document.toggle', $doc->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $doc->status == 'active' ? 'btn-danger' : 'btn-success' }}">
                                            <i class="bx {{ $doc->status == 'active' ? 'bx-x-circle' : 'bx-check-circle' }}"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('document.edit', $doc->id) }}" class="btn btn-sm btn-warning ms-1">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
</x-layouts.app>