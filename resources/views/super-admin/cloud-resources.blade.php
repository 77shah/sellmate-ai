<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">☁️ Cloud Resources</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">Cloud Resources</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== CPU, Memory, Disk, Uptime ===== -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">CPU Usage</p>
                                    <h4 class="mb-0">{{ $resources['cpu_usage'] ?? 0 }}%</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-primary">
                                        <span class="avatar-title">
                                            <i class="bx bx-chip font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                @php
                                    $cpu = min($resources['cpu_usage'] ?? 0, 100);
                                @endphp
                                <div class="progress-bar bg-primary" style="width: {{ $cpu }}%;"></div>
                            </div>
                            <small class="text-muted">Updated: {{ $resources['timestamp'] ?? now()->format('H:i:s') }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Memory Usage</p>
                                    <h4 class="mb-0">{{ $resources['memory_usage'] ?? 0 }}%</h4>
                                    <small class="text-muted">{{ $resources['memory_used'] ?? 0 }} GB / {{ $resources['memory_total'] ?? 0 }} GB</small>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-info">
                                        <span class="avatar-title">
                                            <i class="bx bx-data font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                @php
                                    $memory = min($resources['memory_usage'] ?? 0, 100);
                                @endphp
                                <div class="progress-bar bg-info" style="width: {{ $memory }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Disk Usage</p>
                                    <h4 class="mb-0">{{ $resources['disk_usage'] ?? 0 }}%</h4>
                                    <small class="text-muted">{{ $resources['disk_used'] ?? 0 }} GB / {{ $resources['disk_total'] ?? 0 }} GB</small>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-warning">
                                        <span class="avatar-title">
                                            <i class="bx bx-hdd font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                @php
                                    $disk = min($resources['disk_usage'] ?? 0, 100);
                                @endphp
                                <div class="progress-bar bg-warning" style="width: {{ $disk }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1">Uptime</p>
                                    <h4 class="mb-0">{{ $resources['uptime'] ?? 'N/A' }}</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-success">
                                        <span class="avatar-title">
                                            <i class="bx bx-check-circle font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== Connections, Queue, Status ===== -->
            <div class="row">
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Active Connections (MySQL)</p>
                            <h4 class="mb-0">{{ $resources['active_connections'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">Queue Length (Redis)</p>
                            <h4 class="mb-0">{{ $resources['queue_length'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">System Status</p>
                            <h4 class="mb-0">
                                @if(isset($resources['system_status']['status']))
                                    <span class="badge {{ strpos($resources['system_status']['status'], '✅') !== false ? 'bg-success' : 'bg-warning' }}">
                                        {{ $resources['system_status']['status'] }}
                                    </span>
                                @else
                                    <span class="badge bg-success">Operational</span>
                                @endif
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== Service Status ===== -->
            @if(isset($resources['system_status']['services']))
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Service Status</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Service</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($resources['system_status']['services'] as $service => $status)
                                        <tr>
                                            <td><strong>{{ $service }}</strong></td>
                                            <td>
                                                @if($status == 'Operational')
                                                    <span class="badge bg-success">✅ Operational</span>
                                                @elseif($status == 'Down')
                                                    <span class="badge bg-danger">❌ Down</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- ===== Auto Refresh ===== -->
            <div class="row">
                <div class="col-12">
                    <div class="text-muted text-center">
                        <small>Auto-refreshes every 30 seconds</small>
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