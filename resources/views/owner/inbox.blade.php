<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📨 Inbox</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Inbox</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Conversations</p>
                            <h4 class="mb-0">{{ $conversations->total() ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">🟢 Open</p>
                            <h4 class="mb-0 text-success">
                                {{ $conversations->where('status', 'open')->count() ?? 0 }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">🔴 Escalated</p>
                            <h4 class="mb-0 text-danger">
                                {{ $conversations->where('status', 'escalated')->count() ?? 0 }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">🔵 Closed</p>
                            <h4 class="mb-0 text-secondary">
                                {{ $conversations->where('status', 'closed')->count() ?? 0 }}
                            </h4>
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
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search by name or phone..." 
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="status">
                                        <option value="">All Status</option>
                                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>🟢 Open</option>
                                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>🔵 Closed</option>
                                        <option value="escalated" {{ request('status') == 'escalated' ? 'selected' : '' }}>🔴 Escalated</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="channel">
                                        <option value="">All Channels</option>
                                        <option value="whatsapp" {{ request('channel') == 'whatsapp' ? 'selected' : '' }}>💬 WhatsApp</option>
                                        <option value="instagram" {{ request('channel') == 'instagram' ? 'selected' : '' }}>📷 Instagram</option>
                                        <option value="facebook" {{ request('channel') == 'facebook' ? 'selected' : '' }}>📘 Facebook</option>
                                        <option value="web" {{ request('channel') == 'web' ? 'selected' : '' }}>🌐 Website</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-filter"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('owner.inbox') }}" class="btn btn-secondary w-100">
                                        <i class="bx bx-refresh"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conversations Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Conversations</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
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
                                            <td>{{ ($conversations->currentPage() - 1) * $conversations->perPage() + $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <strong>{{ $conv->customer->name ?? 'Unknown' }}</strong><br>
                                                        <small class="text-muted">{{ $conv->customer->phone ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $channelIcons = [
                                                        'whatsapp' => 'bxl-whatsapp text-success',
                                                        'instagram' => 'bxl-instagram text-danger',
                                                        'facebook' => 'bxl-facebook text-primary',
                                                        'web' => 'bx-globe text-info',
                                                    ];
                                                    $icon = $channelIcons[$conv->channel] ?? 'bx-message';
                                                @endphp
                                                <i class="bx {{ $icon }}"></i>
                                                {{ ucfirst($conv->channel ?? 'Unknown') }}
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'open' => 'success',
                                                        'closed' => 'secondary',
                                                        'escalated' => 'danger',
                                                    ];
                                                    $color = $statusColors[$conv->status] ?? 'secondary';
                                                    $statusIcons = [
                                                        'open' => '🟢',
                                                        'closed' => '🔵',
                                                        'escalated' => '🔴',
                                                    ];
                                                    $icon = $statusIcons[$conv->status] ?? '';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ $icon }} {{ ucfirst($conv->status ?? 'Unknown') }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary rounded-pill">
                                                    {{ $conv->messages_count ?? 0 }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($conv->last_message_at)
                                                    <small>{{ $conv->last_message_at->diffForHumans() }}</small>
                                                @else
                                                    <small class="text-muted">N/A</small>
                                                @endif
                                            </td>
                                            <td>
                                                <!-- 🔥 FIXED: Direct link with id -->
                                                <a href="{{ route('owner.inbox.show', $conv->id) }}" 
                                                   class="btn btn-sm btn-primary" title="View Conversation">
                                                    <i class="bx bx-message-dots"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="bx bx-inbox bx-lg d-block mb-2" style="font-size:48px;"></i>
                                                <h5>No conversations found</h5>
                                                <p class="mb-0">When customers message you, they'll appear here.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    @if($conversations->total() > 0)
                                        Showing {{ $conversations->firstItem() ?? 0 }} to {{ $conversations->lastItem() ?? 0 }} 
                                        of {{ $conversations->total() ?? 0 }} conversations
                                    @endif
                                </div>
                                <div>
                                    @if(isset($conversations) && method_exists($conversations, 'links'))
                                        {{ $conversations->appends(request()->query())->links() }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto refresh every 30 seconds
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
    @endpush

</x-layouts.app>