{{-- resources/views/settings/add_complaintype.blade.php --}}

<x-header/>
<x-sidebar/>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Settings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                              
                                <li class="breadcrumb-item active">Complain Types</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Add New Complain Type</h4>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="bx bx-check-circle"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="bx bx-error"></i>
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Add/Edit Form -->
                            <form action="{{ route('complain-types.store') }}" method="POST" id="complainTypeForm">
                                @csrf
                                <input type="hidden" name="form_type" id="formType" value="add">
                                <input type="hidden" name="edit_id" id="editId" value="">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               required
                                               placeholder="Enter complain type name">
                                        <div class="invalid-feedback" id="nameError"></div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="1"
                                                  placeholder="Enter description (optional)">{{ old('description') }}</textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary me-2" id="submitBtn">
                                            <i class="bx bx-save"></i> Save
                                        </button>
                                        <button type="reset" class="btn btn-secondary" id="resetBtn">
                                            <i class="bx bx-reset"></i> Reset
                                        </button>
                                        <button type="button" class="btn btn-secondary d-none" id="cancelBtn">
                                            <i class="bx bx-x"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="row mt-4">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Complain Types List</h4>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="complainTypesTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th width="100">Status</th>
                                            <th width="150">Created At</th>
                                            <th width="180" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($complainTypes as $key => $type)
                                            <tr id="row-{{ $type->id }}">
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $type->name }}</td>
                                                <td>{{ $type->description ?: 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $type->status === 'active' ? 'success' : 'danger' }} font-size-12">
                                                        {{ ucfirst($type->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $type->created_at->format('d/m/Y') }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-primary edit-btn"
                                                                data-id="{{ $type->id }}"
                                                                title="Edit">
                                                            <i class="bx bx-edit"></i>
                                                        </button>
                                                        
                                                        <form action="{{ route('complain-types.toggle-status', $type->id) }}" 
                                                              method="POST" 
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-sm btn-outline-{{ $type->status === 'active' ? 'warning' : 'success' }}"
                                                                    title="{{ $type->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                                <i class="bx bx-{{ $type->status === 'active' ? 'power-off' : 'check-circle' }}"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger delete-btn"
                                                                data-id="{{ $type->id }}"
                                                                data-name="{{ $type->name }}"
                                                                title="Delete">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="bx bx-package display-4"></i>
                                                    <p class="mt-2 mb-0">No complain types found</p>
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
</div>

<x-footer/>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteItemName"></strong>?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #complainTypesTable th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }
    
    #complainTypesTable tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .btn-group .btn {
        border-radius: 4px !important;
        margin: 0 2px;
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Form submission
        $('#complainTypeForm').submit(function(e) {
            e.preventDefault();
            
            // Reset errors
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            // Get form data
            var formData = new FormData(this);
            var formType = $('#formType').val();
            var url = formType === 'edit' 
                ? "{{ url('complain-types') }}/" + $('#editId').val() + "/update"
                : "{{ route('complain-types.store') }}";
            
            var method = formType === 'edit' ? 'PUT' : 'POST';
            
            // Show loading
            var submitBtn = $('#submitBtn');
            var originalText = submitBtn.html();
            submitBtn.html('<i class="bx bx-loader bx-spin"></i> Processing...');
            submitBtn.prop('disabled', true);
            
            // AJAX request
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Reload page to show updated data
                    window.location.reload();
                },
                error: function(xhr) {
                    submitBtn.html(originalText);
                    submitBtn.prop('disabled', false);
                    
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid');
                            $('#' + key + 'Error').text(value[0]);
                        });
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                }
            });
        });

        // Edit button click
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            
            $.ajax({
                url: "{{ url('complain-types') }}/" + id + "/edit",
                type: "GET",
                success: function(response) {
                    // Fill form with data
                    $('#name').val(response.name);
                    $('#description').val(response.description);
                    
                    // Set form type to edit
                    $('#formType').val('edit');
                    $('#editId').val(id);
                    
                    // Change button text
                    $('#submitBtn').html('<i class="bx bx-save"></i> Update');
                    
                    // Show cancel button
                    $('#cancelBtn').removeClass('d-none');
                    
                    // Scroll to form
                    $('html, body').animate({
                        scrollTop: $('#complainTypeForm').offset().top - 100
                    }, 500);
                },
                error: function(xhr) {
                    alert('Error loading complain type data');
                }
            });
        });

        // Cancel button click
        $('#cancelBtn').click(function() {
            resetForm();
        });

        // Reset button click
        $('#resetBtn').click(function() {
            resetForm();
        });

        // Reset form function
        function resetForm() {
            $('#complainTypeForm')[0].reset();
            $('#formType').val('add');
            $('#editId').val('');
            $('#submitBtn').html('<i class="bx bx-save"></i> Save');
            $('#cancelBtn').addClass('d-none');
            $('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            // Reset form action
            $('#complainTypeForm').attr('action', "{{ route('complain-types.store') }}");
            
            // Remove any hidden method field
            $('#complainTypeForm').find('input[name="_method"]').remove();
        }

        // Delete button click
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            
            $('#deleteItemName').text(name);
            $('#deleteForm').attr('action', "{{ url('complain-types') }}/" + id);
            $('#deleteModal').modal('show');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>