<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Settings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">GST Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">GST Rate Management</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <!-- Add GST Form -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <form action="{{ route('gst.store') }}" method="POST" class="row g-3">
                                        @csrf
                                        <div class="col-md-8">
                                            <label class="form-label">Add New GST Rate</label>
                                            <input type="text" class="form-control" name="rate" 
                                                   placeholder="Enter GST Rate (e.g., 18%, 5%, 12%)" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary form-control">Add GST</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- GST Rates List -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>GST Rate</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($gstRates as $gst)
                                                <tr>
                                                    <td>{{ $gst->id }}</td>
                                                    <td>
                                                        <span class="gst-rate-text-{{ $gst->id }}">{{ $gst->rate }}</span>
                                                        <input type="text" class="form-control d-none edit-input-{{ $gst->id }}" 
                                                               value="{{ $gst->rate }}" style="width: 100px;">
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-switch">
                                                            <input type="checkbox" class="form-check-input status-toggle" 
                                                                   data-id="{{ $gst->id }}"
                                                                   {{ $gst->status == 1 ? 'checked' : '' }}>
                                                            <label class="form-check-label">
                                                                <span class="badge {{ $gst->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                                    {{ $gst->status == 1 ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $gst->id }}">
                                                            <i class="bx bx-edit"></i> 
                                                        </button>
                                                        <button class="btn btn-sm btn-success save-btn d-none" data-id="{{ $gst->id }}">
                                                            <i class="bx bx-save"></i> Save
                                                        </button>
                                                        <button class="btn btn-sm btn-secondary cancel-btn d-none" data-id="{{ $gst->id }}">
                                                            <i class="bx bx-x"></i> Cancel
                                                        </button>
                                                        <form action="{{ route('gst.destroy', $gst->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                                <i class="bx bx-trash"></i> 
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No GST rates found. Add one!</td>
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
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Edit button click
            $('.edit-btn').click(function() {
                var id = $(this).data('id');
                $('.gst-rate-text-' + id).addClass('d-none');
                $('.edit-input-' + id).removeClass('d-none');
                $(this).addClass('d-none');
                $('.save-btn[data-id="' + id + '"]').removeClass('d-none');
                $('.cancel-btn[data-id="' + id + '"]').removeClass('d-none');
            });

            // Cancel button click
            $('.cancel-btn').click(function() {
                var id = $(this).data('id');
                $('.gst-rate-text-' + id).removeClass('d-none');
                $('.edit-input-' + id).addClass('d-none');
                $('.edit-btn[data-id="' + id + '"]').removeClass('d-none');
                $(this).addClass('d-none');
                $('.save-btn[data-id="' + id + '"]').addClass('d-none');
            });

            // Save button click
            $('.save-btn').click(function() {
                var id = $(this).data('id');
                var rate = $('.edit-input-' + id).val();
                
                $.ajax({
                    url: '/gst/' + id,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        rate: rate
                    },
                    success: function(response) {
                        if(response.success) {
                            $('.gst-rate-text-' + id).text(rate);
                            $('.gst-rate-text-' + id).removeClass('d-none');
                            $('.edit-input-' + id).addClass('d-none');
                            $('.edit-btn[data-id="' + id + '"]').removeClass('d-none');
                            $('.save-btn[data-id="' + id + '"]').addClass('d-none');
                            $('.cancel-btn[data-id="' + id + '"]').addClass('d-none');
                            
                            // Show success message
                            $('.alert').remove();
                            $('.card-body').prepend('<div class="alert alert-success">GST rate updated successfully!</div>');
                            setTimeout(function() {
                                $('.alert').fadeOut();
                            }, 3000);
                        }
                    }
                });
            });

            // Status toggle
            $('.status-toggle').change(function() {
                var id = $(this).data('id');
                var isChecked = $(this).is(':checked');
                var statusLabel = $(this).closest('td').find('.badge');
                
                $.ajax({
                    url: '/gst/' + id + '/toggle-status',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PATCH'
                    },
                    success: function(response) {
                        if(response.success) {
                            if(response.status == 1) {
                                statusLabel.removeClass('bg-danger').addClass('bg-success');
                                statusLabel.text('Active');
                            } else {
                                statusLabel.removeClass('bg-success').addClass('bg-danger');
                                statusLabel.text('Inactive');
                            }
                        }
                    }
                });
            });
        });
    </script>

    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }
        .btn-sm {
            margin: 0 2px;
        }
        .badge {
            font-size: 12px;
            padding: 5px 10px;
        }
    </style>

</x-layouts.app>