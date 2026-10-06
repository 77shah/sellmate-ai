<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Inner Category Management</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Categories</a></li>
                                <li class="breadcrumb-item active">Inner Category</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Manage Inner Categories</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <!-- Add Inner Category Form -->
                            <div class="row mb-4">
                                <div class="col-md-10">
                                    <form action="{{ route('innercategory.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                                        @csrf
                                        <div class="col-md-3">
                                            <select name="category_id" id="categorySelect" class="form-control" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="sub_category_id" id="subCategorySelect" class="form-control" required disabled>
                                                <option value="">First Select Category</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" name="name" placeholder="Inner Category Name" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="file" class="form-control" name="image" accept="image/*">
                                        </div>
                                        <div class="col-md-12">
                                            <textarea class="form-control" name="description" rows="2" placeholder="Inner Category Description"></textarea>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">Add Inner Category</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Inner Categories List -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Sub Category</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($innerCategories as $innerCategory)
                                        <tr>
                                            <td>{{ $innerCategory->id }}</td>
                                            <td>
                                                @if($innerCategory->image)
                                                    <img src="{{ asset($innerCategory->image) }}" width="50" height="50" style="object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                             </td>
                                            <td>{{ $innerCategory->name }}</td>
                                            <td>{{ $innerCategory->subCategory->name }}</td>
                                            <td>{{ $innerCategory->subCategory->category->name }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input innercategory-toggle" 
                                                           data-id="{{ $innerCategory->id }}"
                                                           {{ $innerCategory->status == 1 ? 'checked' : '' }}>
                                                    <span class="badge {{ $innerCategory->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $innerCategory->status == 1 ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                             </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-innercategory" data-id="{{ $innerCategory->id }}" data-name="{{ $innerCategory->name }}" data-subcategory="{{ $innerCategory->sub_category_id }}" data-desc="{{ $innerCategory->description }}">
                                                    <i class="bx bx-edit"></i> Edit
                                                </button>
                                                <form action="{{ route('innercategory.delete', $innerCategory->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this inner category?')">
                                                        <i class="bx bx-trash"></i> Delete
                                                    </button>
                                                </form>
                                             </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No inner categories found</td>
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
            // Dynamic Sub Category on Category Select
            $('#categorySelect').change(function() {
                var categoryId = $(this).val();
                if(categoryId) {
                    $.ajax({
                        url: '/innercategory/get-subcategories/' + categoryId,
                        type: 'GET',
                        success: function(data) {
                            $('#subCategorySelect').prop('disabled', false);
                            $('#subCategorySelect').empty();
                            $('#subCategorySelect').append('<option value="">Select Sub Category</option>');
                            $.each(data, function(key, value) {
                                $('#subCategorySelect').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#subCategorySelect').prop('disabled', true);
                    $('#subCategorySelect').empty();
                    $('#subCategorySelect').append('<option value="">First Select Category</option>');
                }
            });

            // Toggle Status
            $('.innercategory-toggle').change(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/innercategory/' + id + '/toggle',
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