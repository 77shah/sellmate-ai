<x-layouts.app>

    @section('title', 'Category Management')

    @section('content')
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
                                    <form action="{{ route('owner.category.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
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
                                            <th>#</th>
                                            <th>Category ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Slug</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($categories as $key => $category)
                                            <tr id="category-row-{{ $category->id }}">

                                                {{-- Serial Number --}}
                                                <td>{{ $key + 1 }}</td>

                                                {{-- Category ID --}}
                                                <td>{{ $category->id }}</td>

                                                {{-- Image --}}
                                                <td>
                                                    @if($category->image)
                                                        <img src="{{ asset($category->image) }}"
                                                            width="50"
                                                            height="50"
                                                            style="object-fit: cover;">
                                                    @else
                                                        <span class="text-muted">No Image</span>
                                                    @endif
                                                </td>

                                                {{-- Category Name --}}
                                                <td>
                                                    <span class="category-name-{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </span>

                                                    <input type="text"
                                                        class="form-control d-none edit-category-{{ $category->id }}"
                                                        value="{{ $category->name }}"
                                                        style="width: 150px;">

                                                    <textarea
                                                        class="form-control d-none mt-1 edit-desc-{{ $category->id }}"
                                                        rows="2">{{ $category->description }}</textarea>
                                                </td>

                                                {{-- Slug --}}
                                                <td>{{ $category->slug }}</td>

                                                {{-- Status --}}
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox"
                                                            class="form-check-input category-toggle"
                                                            data-id="{{ $category->id }}"
                                                            {{ $category->status == 'active' ? 'checked' : '' }}>

                                                        <span class="badge {{ $category->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $category->status == 'active' ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </div>
                                                </td>

                                                {{-- Actions --}}
                                                <td>
                                                    <button class="btn btn-sm btn-warning edit-category"
                                                            data-id="{{ $category->id }}">
                                                        <i class="bx bx-edit"></i> Edit
                                                    </button>

                                                    <form action="{{ route('owner.category.destroy', $category->id) }}"
                                                        method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit"
                                                                class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Delete this category?')">
                                                            <i class="bx bx-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>

                                        @empty

                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    No categories found
                                                </td>
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

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // ===== TOGGLE STATUS =====
            $('.category-toggle').change(function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var isChecked = checkbox.is(':checked');
                
                $.ajax({
                    url: '/owner/category/' + id + '/toggle',
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        checkbox.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update badge
                            var badge = checkbox.closest('td').find('.badge');
                            if (response.status == 'active') {
                                badge.removeClass('bg-danger').addClass('bg-success').text('Active');
                            } else {
                                badge.removeClass('bg-success').addClass('bg-danger').text('Inactive');
                            }
                        }
                    },
                    error: function() {
                        checkbox.prop('checked', !isChecked);
                        alert('Something went wrong!');
                    },
                    complete: function() {
                        checkbox.prop('disabled', false);
                    }
                });
            });

            // ===== EDIT BUTTON =====
            $('.edit-category').click(function() {
                var id = $(this).data('id');
                var name = $('.category-name-' + id).text();
                var desc = $('.edit-desc-' + id).val();
                
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_desc').val(desc);
                $('#editForm').attr('action', '/owner/category/' + id);
                
                // Show current image
                var img = $(this).closest('tr').find('img').attr('src');
                if(img) {
                    $('#current_image').html('<img src="' + img + '" width="100" class="mt-2" style="border-radius: 5px;">');
                } else {
                    $('#current_image').html('<p class="text-muted mt-2">No image</p>');
                }
                
                $('#editModal').modal('show');
            });
        });
    </script>
    @endpush

</x-layouts.app>