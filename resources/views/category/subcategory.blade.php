<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Sub Category Management</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Categories</a></li>
                                <li class="breadcrumb-item active">Sub Category</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Manage Sub Categories</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <!-- Add Sub Category Form -->
                            <div class="row mb-4">
                                <div class="col-md-10">
                                    <form action="{{ route('subcategory.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        <div class="col-md-3">
                                            <select name="category_id" class="form-control" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="name" placeholder="Sub Category Name" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="file" class="form-control" name="image" accept="image/*">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary w-100">Add Sub Category</button>
                                        </div>
                                        <div class="col-md-12">
                                            <textarea class="form-control" name="description" rows="2" placeholder="Sub Category Description"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Sub Categories List -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subCategories as $subCategory)
                                        <tr>
                                            <td>{{ $subCategory->id }}</td>
                                            <td>
                                                @if($subCategory->image)
                                                    <img src="{{ asset($subCategory->image) }}" width="50" height="50" style="object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $subCategory->name }}</td>
                                            <td>{{ $subCategory->category->name }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input subcategory-toggle" 
                                                           data-id="{{ $subCategory->id }}"
                                                           {{ $subCategory->status == 1 ? 'checked' : '' }}>
                                                    <span class="badge {{ $subCategory->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $subCategory->status == 1 ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                             </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-subcategory" data-id="{{ $subCategory->id }}" data-name="{{ $subCategory->name }}" data-category="{{ $subCategory->category_id }}" data-desc="{{ $subCategory->description }}">
                                                    <i class="bx bx-edit"></i> Edit
                                                </button>
                                                <form action="{{ route('subcategory.delete', $subCategory->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this sub category?')">
                                                        <i class="bx bx-trash"></i> Delete
                                                    </button>
                                                </form>
                                             </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No sub categories found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.subcategory-toggle').change(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/subcategory/' + id + '/toggle',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PATCH'
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            });
        });
    </script>

</x-layouts.app>