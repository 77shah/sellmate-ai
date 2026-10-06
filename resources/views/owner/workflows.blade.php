<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">⚙️ Automation Workflows</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Workflows</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="mdi mdi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">Workflows</h4>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkflowModal">
                                    <i class="bx bx-plus me-1"></i> Add Workflow
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Trigger</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($workflows ?? [] as $index => $workflow)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $workflow->name }}</strong></td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($workflow->trigger) }}</span>
                                            </td>
                                            <td>
                                                @if($workflow->is_active)
                                                    <span class="badge bg-success">✅ Active</span>
                                                @else
                                                    <span class="badge bg-secondary">⏸️ Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $workflow->created_at ? $workflow->created_at->format('d M Y') : 'N/A' }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-info" 
                                                            onclick="editWorkflow('{{ $workflow->id }}')">
                                                        <i class="bx bx-edit"></i>
                                                    </button>
                                                    <form action="{{ route('owner.workflows.toggle', $workflow->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $workflow->is_active ? 'btn-warning' : 'btn-success' }}">
                                                            <i class="bx {{ $workflow->is_active ? 'bx-pause' : 'bx-play' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('owner.workflows.delete', $workflow->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Delete this workflow?')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bx bx-git-branch bx-lg d-block mb-2" style="font-size:48px;"></i>
                                                <h5>No workflows created</h5>
                                                <p class="mb-0">Create your first automation workflow.</p>
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

    <!-- ===== ADD WORKFLOW MODAL ===== -->
    <div class="modal fade" id="addWorkflowModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">➕ Add New Workflow</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('owner.workflows.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Workflow Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="e.g., Follow-up Reminder" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Trigger <span class="text-danger">*</span></label>
                                    <select class="form-select" name="trigger" required>
                                        <option value="">Select Trigger</option>
                                        <option value="new_customer">🆕 New Customer</option>
                                        <option value="order_placed">🛒 Order Placed</option>
                                        <option value="payment_received">💰 Payment Received</option>
                                        <option value="no_response">⏰ No Response (24h)</option>
                                        <option value="high_lead_score">⭐ High Lead Score</option>
                                        <option value="abandoned_cart">🛒 Abandoned Cart</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Conditions (JSON)</label>
                            <textarea class="form-control" name="conditions" rows="3" 
                                      placeholder='{"stage": "new", "lead_score": "> 50"}' 
                                      style="font-family: monospace; font-size: 13px;"></textarea>
                            <small class="text-muted">Optional: Define conditions for this workflow.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Actions (JSON) <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="actions" rows="4" 
                                      placeholder='{"type": "send_message", "template": "followup", "delay": "24h"}'
                                      style="font-family: monospace; font-size: 13px;" required></textarea>
                            <small class="text-muted">
                                Define what actions to perform.<br>
                                Example: {"type": "send_message", "template": "followup", "delay": "24h"}
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Create Workflow
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== EDIT WORKFLOW MODAL ===== -->
    <div class="modal fade" id="editWorkflowModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">✏️ Edit Workflow</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editWorkflowForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Workflow Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="edit_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Trigger <span class="text-danger">*</span></label>
                                    <select class="form-select" name="trigger" id="edit_trigger" required>
                                        <option value="">Select Trigger</option>
                                        <option value="new_customer">🆕 New Customer</option>
                                        <option value="order_placed">🛒 Order Placed</option>
                                        <option value="payment_received">💰 Payment Received</option>
                                        <option value="no_response">⏰ No Response (24h)</option>
                                        <option value="high_lead_score">⭐ High Lead Score</option>
                                        <option value="abandoned_cart">🛒 Abandoned Cart</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Conditions (JSON)</label>
                            <textarea class="form-control" name="conditions" id="edit_conditions" rows="3" 
                                      style="font-family: monospace; font-size: 13px;"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Actions (JSON) <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="actions" id="edit_actions" rows="4" 
                                      style="font-family: monospace; font-size: 13px;" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save"></i> Update Workflow
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function editWorkflow(id) {
            // Fetch workflow details via AJAX
            fetch('/owner/workflows/' + id + '/edit')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_name').value = data.name;
                    document.getElementById('edit_trigger').value = data.trigger;
                    document.getElementById('edit_conditions').value = data.conditions ? JSON.stringify(data.conditions, null, 2) : '';
                    document.getElementById('edit_actions').value = data.actions ? JSON.stringify(data.actions, null, 2) : '';
                    
                    document.getElementById('editWorkflowForm').action = '/owner/workflows/' + id;
                    
                    // Show modal
                    var modal = new bootstrap.Modal(document.getElementById('editWorkflowModal'));
                    modal.show();
                })
                .catch(error => {
                    alert('Error loading workflow details');
                });
        }
    </script>
    @endpush

</x-layouts.app>