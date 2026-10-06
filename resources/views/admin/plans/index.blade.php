<x-layouts.app>

    @section('title', 'Plans Management')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">

            {{-- Page Title --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📋 Plans Management</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                                <li class="breadcrumb-item active">Plans</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alerts --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="mdi mdi-alert-circle me-2"></i>
                    <strong>Validation Errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="mdi mdi-alert-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Plans Table --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">All Plans</h4>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPlanModal">
                                    <i class="bx bx-plus me-1"></i> Add Plan
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Price (INR)</th>
                                            <th>Price (USD)</th>
                                            <th>Period</th>
                                            <th>AI Messages</th>
                                            <th>Channels</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($plans as $index => $plan)
                                            <tr id="plan-row-{{ $plan->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $plan->name }}</strong></td>
                                                <td>₹{{ number_format($plan->price, 2) }}</td>
                                                <td>${{ number_format($plan->price_usd ?? 0, 2) }}</td>
                                                <td>{{ ucfirst($plan->billing_period) }}</td>
                                                <td>{{ number_format($plan->ai_messages_limit) }}</td>
                                                <td>{{ $plan->channels_limit }}</td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input toggle-status"
                                                               data-id="{{ $plan->id }}"
                                                               data-url="{{ route('admin.plans.toggle', $plan->id) }}"
                                                               {{ $plan->is_active ? 'checked' : '' }}>
                                                        <span class="badge {{ $plan->is_active ? 'bg-success' : 'bg-danger' }} status-badge-{{ $plan->id }}">
                                                            {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning edit-plan"
                                                            data-id="{{ $plan->id }}"
                                                            data-name="{{ $plan->name }}"
                                                            data-price="{{ $plan->price }}"
                                                            data-price-usd="{{ $plan->price_usd ?? 0 }}"
                                                            data-currency="{{ $plan->currency }}"
                                                            data-period="{{ $plan->billing_period }}"
                                                            data-ai="{{ $plan->ai_messages_limit }}"
                                                            data-channels="{{ $plan->channels_limit }}"
                                                            data-seats="{{ $plan->team_seats_limit }}"
                                                            data-storage="{{ $plan->storage_limit }}"
                                                            data-sort="{{ $plan->sort_order }}"
                                                            data-active="{{ $plan->is_active ? '1' : '0' }}">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                    <form action="{{ route('admin.plans.destroy', $plan->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this plan?')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="bx bx-credit-card bx-lg d-block mb-2" style="font-size: 48px;"></i>
                                                        <p class="mb-0">No plans found</p>
                                                        <small>Create your first subscription plan.</small>
                                                    </div>
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

    <!-- ========================================== -->
    <!-- ===== ADD PLAN MODAL ===== -->
    <!-- ========================================== -->
    <div class="modal fade" id="addPlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.plans.store') }}" method="POST" id="addPlanForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Plan Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="add_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Billing Period</label>
                                    <select class="form-select" name="billing_period" id="add_period">
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Price (INR ₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="price" id="add_price" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Price (USD $)</label>
                                    <input type="number" step="0.01" class="form-control" name="price_usd" id="add_price_usd">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Currency</label>
                                    <select class="form-select" name="currency" id="add_currency">
                                        <option value="INR">INR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">AI Messages</label>
                                    <input type="number" class="form-control" name="ai_messages_limit" id="add_ai" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Channels</label>
                                    <input type="number" class="form-control" name="channels_limit" id="add_channels" value="1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Team Seats</label>
                                    <input type="number" class="form-control" name="team_seats_limit" id="add_seats" value="1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Storage (MB)</label>
                                    <input type="number" class="form-control" name="storage_limit" id="add_storage" value="100">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Sort Order</label>
                                    <input type="number" class="form-control" name="sort_order" id="add_sort" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-check" style="margin-top: 30px;">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="form-check-input" name="is_active" id="add_active" value="1" checked>
                                    <label class="form-check-label" for="add_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="addPlanBtn">Save Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- ===== EDIT PLAN MODAL ===== -->
    <!-- ========================================== -->
    <div class="modal fade" id="editPlanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editPlanForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Plan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_plan_id" name="plan_id">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Plan Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Billing Period</label>
                                    <select class="form-select" id="edit_period" name="billing_period">
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Price (INR ₹) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="edit_price" name="price" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Price (USD $)</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_price_usd" name="price_usd">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Currency</label>
                                    <select class="form-select" id="edit_currency" name="currency">
                                        <option value="INR">INR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">AI Messages</label>
                                    <input type="number" class="form-control" id="edit_ai" name="ai_messages_limit">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Channels</label>
                                    <input type="number" class="form-control" id="edit_channels" name="channels_limit">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Team Seats</label>
                                    <input type="number" class="form-control" id="edit_seats" name="team_seats_limit">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Storage (MB)</label>
                                    <input type="number" class="form-control" id="edit_storage" name="storage_limit">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Sort Order</label>
                                    <input type="number" class="form-control" id="edit_sort" name="sort_order">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 form-check" style="margin-top: 30px;">
                                    <input type="hidden" name="is_active" value="0">
                                    <input type="checkbox" class="form-check-input" id="edit_active" name="is_active" value="1">
                                    <label class="form-check-label" for="edit_active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="editPlanBtn">Update Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            // 1. TOGGLE STATUS
            $('.toggle-status').change(function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var url = checkbox.data('url');
                var isChecked = checkbox.is(':checked');
                var badge = checkbox.closest('td').find('.status-badge-' + id);

                $.ajax({
                    url: url,
                    type: 'PATCH',
                    data: { _token: '{{ csrf_token() }}' },
                    beforeSend: function() { checkbox.prop('disabled', true); },
                    success: function(response) {
                        if (response.success) {
                            if (response.is_active) {
                                badge.removeClass('bg-danger').addClass('bg-success').text('Active');
                            } else {
                                badge.removeClass('bg-success').addClass('bg-danger').text('Inactive');
                            }
                        }
                    },
                    error: function() {
                        checkbox.prop('checked', !isChecked);
                        alert('Error updating status.');
                    },
                    complete: function() { checkbox.prop('disabled', false); }
                });
            });

            // 2. EDIT BUTTON
            $('.edit-plan').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var price = $(this).data('price');
                var priceUsd = $(this).data('price-usd');
                var currency = $(this).data('currency') || 'INR';
                var period = $(this).data('period');
                var ai = $(this).data('ai');
                var channels = $(this).data('channels');
                var seats = $(this).data('seats');
                var storage = $(this).data('storage');
                var sort = $(this).data('sort') || 0;
                var active = $(this).data('active') == '1';

                var url = "{{ route('admin.plans.update', ':id') }}";
                url = url.replace(':id', id);
                $('#editPlanForm').attr('action', url);

                $('#edit_plan_id').val(id);
                $('#edit_name').val(name);
                $('#edit_price').val(price);
                $('#edit_price_usd').val(priceUsd);
                $('#edit_currency').val(currency);
                $('#edit_period').val(period);
                $('#edit_ai').val(ai);
                $('#edit_channels').val(channels);
                $('#edit_seats').val(seats);
                $('#edit_storage').val(storage);
                $('#edit_sort').val(sort);
                $('#edit_active').prop('checked', active);

                $('#editPlanModal').modal('show');
            });

            // 3. FORM SUBMIT — Disable double click
            $('#addPlanForm').submit(function() {
                $('#addPlanBtn').prop('disabled', true);
                $('#addPlanBtn').html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
            });

            $('#editPlanForm').submit(function() {
                $('#editPlanBtn').prop('disabled', true);
                $('#editPlanBtn').html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');
            });

        });
    </script>
    @endpush

</x-layouts.app>