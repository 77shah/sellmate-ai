<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">🚀 Deployments</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">Deployments</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <!-- Stats -->
            <div class="row">
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">Total</p>
                            <h4 class="mb-0">{{ $stats['total'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">✅ Success</p>
                            <h4 class="mb-0 text-success">{{ $stats['success'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">❌ Failed</p>
                            <h4 class="mb-0 text-danger">{{ $stats['failed'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">⏳ Pending</p>
                            <h4 class="mb-0 text-warning">{{ $stats['pending'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">🔄 Running</p>
                            <h4 class="mb-0 text-info">{{ $stats['running'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">Last Deploy</p>
                            <h4 class="mb-0">
                                @if($lastDeployment)
                                    <small>v{{ $lastDeployment->version }}</small>
                                @else
                                    <small>N/A</small>
                                @endif
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters & Actions -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search by version, commit..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="status">
                                        <option value="">All Status</option>
                                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>✅ Success</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                        <option value="running" {{ request('status') == 'running' ? 'selected' : '' }}>🔄 Running</option>
                                        <option value="rollback" {{ request('status') == 'rollback' ? 'selected' : '' }}>↩️ Rollback</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="branch">
                                        <option value="">All Branches</option>
                                        <option value="main" {{ request('branch') == 'main' ? 'selected' : '' }}>main</option>
                                        <option value="develop" {{ request('branch') == 'develop' ? 'selected' : '' }}>develop</option>
                                        <option value="staging" {{ request('branch') == 'staging' ? 'selected' : '' }}>staging</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-filter"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('super-admin.deployments.create') }}" class="btn btn-primary">
                                        <i class="bx bx-rocket me-1"></i> New Deployment
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deployments Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Deployment History</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Version</th>
                                            <th>Branch</th>
                                            <th>Commit</th>
                                            <th>Status</th>
                                            <th>Duration</th>
                                            <th>Deployed By</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deployments ?? [] as $index => $deployment)
                                        <tr>
                                            <td>{{ ($deployments->currentPage() - 1) * $deployments->perPage() + $loop->iteration }}</td>
                                            <td><code>v{{ $deployment->version }}</code></td>
                                            <td>{{ $deployment->branch ?? 'N/A' }}</td>
                                            <td><code>{{ $deployment->commit_hash ?? 'N/A' }}</code></td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'warning',
                                                        'running' => 'info',
                                                        'success' => 'success',
                                                        'failed' => 'danger',
                                                        'rollback' => 'secondary',
                                                    ];
                                                    $color = $statusColors[$deployment->status] ?? 'secondary';
                                                    $statusIcons = [
                                                        'pending' => '⏳',
                                                        'running' => '🔄',
                                                        'success' => '✅',
                                                        'failed' => '❌',
                                                        'rollback' => '↩️',
                                                    ];
                                                    $icon = $statusIcons[$deployment->status] ?? '';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ $icon }} {{ ucfirst($deployment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $deployment->duration }}s</td>
                                            <td>{{ $deployment->deployer->name ?? 'System' }}</td>
                                            <td>{{ $deployment->created_at ? $deployment->created_at->format('d M Y H:i') : 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('super-admin.deployments.show', $deployment->id) }}" 
                                                   class="btn btn-sm btn-info" title="View Details">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                                @if($deployment->status == 'success')
                                                    <form action="{{ route('super-admin.deployments.rollback', $deployment->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" 
                                                                title="Rollback" 
                                                                onclick="return confirm('Rollback this deployment?')">
                                                            <i class="bx bx-undo"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i class="bx bx-rocket bx-lg d-block mb-2" style="font-size:48px;"></i>
                                                No deployments found
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end">
                                @if(isset($deployments) && method_exists($deployments, 'links'))
                                    {{ $deployments->appends(request()->query())->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>