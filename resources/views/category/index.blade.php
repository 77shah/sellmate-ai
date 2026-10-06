<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Category Management</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Categories</a></li>
                                <li class="breadcrumb-item active">Category</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Manage Categories</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <!-- Add Category Form -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="name" placeholder="Category Name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="file" class="form-control" name="image" accept="image/*">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary w-100">Add Category</button>
                                        </div>
                                        <div class="col-md-12">
                                            <textarea class="form-control" name="description" rows="2" placeholder="Category Description"></textarea>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Categories List -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categories as $category)
                                        <tr id="category-row-{{ $category->id }}">
                                            <td>{{ $category->id }}</td>
                                            <td>
                                                @if($category->image)
                                                    <img src="{{ asset($category->image) }}" width="50" height="50" style="object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="category-name-{{ $category->id }}">{{ $category->name }}</span>
                                                <input type="text" class="form-control d-none edit-category-{{ $category->id }}" value="{{ $category->name }}" style="width: 150px;">
                                                <textarea class="form-control d-none mt-1 edit-desc-{{ $category->id }}" rows="2">{{ $category->description }}</textarea>
                                            </td>
                                            <td>{{ $category->slug }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input category-toggle" 
                                                           data-id="{{ $category->id }}"
                                                           {{ $category->status == 1 ? 'checked' : '' }}>
                                                    <span class="badge {{ $category->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $category->status == 1 ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-category" data-id="{{ $category->id }}">
                                                    <i class="bx bx-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-success save-category d-none" data-id="{{ $category->id }}">
                                                    <i class="bx bx-save"></i> Save
                                                </button>
                                                <button class="btn btn-sm btn-secondary cancel-category d-none" data-id="{{ $category->id }}">
                                                    <i class="bx bx-x"></i> Cancel
                                                </button>
                                                <form action="{{ route('category.delete', $category->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">
                                                        <i class="bx bx-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No categories found</td>
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

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label>Category Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea class="form-control" id="edit_desc" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Current Image</label>
                            <div id="current_image"></div>
                        </div>
                        <div class="mb-3">
                            <label>Change Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle Status
            $('.category-toggle').change(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/category/' + id + '/toggle',
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

            // Edit Button Click
            $('.edit-category').click(function() {
                var id = $(this).data('id');
                var name = $('.category-name-' + id).text();
                var desc = $('.category-name-' + id).closest('td').find('textarea').val();
                
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_desc').val(desc);
                
                // Show current image
                var img = $('.category-name-' + id).closest('tr').find('img').attr('src');
                if(img) {
                    $('#current_image').html('<img src="' + img + '" width="100" class="mt-2">');
                } else {
                    $('#current_image').html('<p class="text-muted mt-2">No image</p>');
                }
                
                $('#editForm').attr('action', '/category/' + id);
                $('#editModal').modal('show');
            });
        });
    </script>

</x-layouts.app>