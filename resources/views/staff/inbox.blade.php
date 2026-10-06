<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📨 Inbox</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Staff</a></li>
                                <li class="breadcrumb-item active">Inbox</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <select class="form-select" name="status">
                                        <option value="">All Status</option>
                                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                        <option value="escalated" {{ request('status') == 'escalated' ? 'selected' : '' }}>Escalated</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" name="channel">
                                        <option value="">All Channels</option>
                                        <option value="whatsapp" {{ request('channel') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                        <option value="instagram" {{ request('channel') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                        <option value="facebook" {{ request('channel') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                        <option value="web" {{ request('channel') == 'web' ? 'selected' : '' }}>Website</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('staff.inbox') }}" class="btn btn-secondary w-100">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Conversations</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Customer</th>
                                            <th>Channel</th>
                                            <th>Status</th>
                                            <th>Messages</th>
                                            <th>Last Message</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($conversations ?? [] as $index => $conv)
                                        <tr>
                                            <td>{{ $conversations->firstItem() + $index ?? $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $conv->customer->name ?? 'N/A' }}</strong><br>
                                                <small class="text-muted">{{ $conv->customer->phone ?? '' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <i class="bx {{ $conv->channel == 'whatsapp' ? 'bxl-whatsapp' : ($conv->channel == 'instagram' ? 'bxl-instagram' : 'bx-globe') }}"></i>
                                                    {{ ucfirst($conv->channel) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $conv->status == 'open' ? 'bg-success' : ($conv->status == 'escalated' ? 'bg-danger' : 'bg-secondary') }}">
                                                    {{ ucfirst($conv->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $conv->messages_count ?? 0 }}</td>
                                            <td>{{ $conv->last_message_at ? $conv->last_message_at->diffForHumans() : 'N/A' }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="bx bx-message-dots"></i> Reply
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bx bx-message bx-lg d-block mb-2" style="font-size: 48px;"></i>
                                                    <p class="mb-0">No conversations found</p>
                                                    <small>You have no assigned conversations yet.</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end">
                                @if(isset($conversations) && method_exists($conversations, 'links'))
                                    {{ $conversations->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>