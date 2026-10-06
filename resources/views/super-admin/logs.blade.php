<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📋 System Logs</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">Logs</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search logs..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="severity">
                                        <option value="">All Severity</option>
                                        <option value="ERROR" {{ request('severity') == 'ERROR' ? 'selected' : '' }}>🔴 Error</option>
                                        <option value="WARNING" {{ request('severity') == 'WARNING' ? 'selected' : '' }}>🟡 Warning</option>
                                        <option value="INFO" {{ request('severity') == 'INFO' ? 'selected' : '' }}>🔵 Info</option>
                                        <option value="DEBUG" {{ request('severity') == 'DEBUG' ? 'selected' : '' }}>🟢 Debug</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="file">
                                        <option value="">All Log Files</option>
                                        @foreach($logFilesList ?? [] as $file)
                                            <option value="{{ $file }}" {{ request('file') == $file ? 'selected' : '' }}>
                                                {{ $file }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-filter"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('super-admin.logs') }}" class="btn btn-secondary w-100">
                                        <i class="bx bx-refresh"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Total Logs: </span>
                                    <strong>{{ $logs->total() ?? 0 }}</strong>
                                </div>
                                <div>
                                    <span class="badge bg-danger me-2">🔴 Errors</span>
                                    <span class="badge bg-warning me-2">🟡 Warnings</span>
                                    <span class="badge bg-info me-2">🔵 Info</span>
                                    <span class="badge bg-success">🟢 Debug</span>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-danger" onclick="clearLogs()">
                                        <i class="bx bx-trash"></i> Clear Logs
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logs Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Activity Logs</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:50px;">#</th>
                                            <th style="width:180px;">Timestamp</th>
                                            <th style="width:100px;">Severity</th>
                                            <th>Message</th>
                                            <th style="width:150px;">Tenant ID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs ?? [] as $index => $log)
                                        <tr>
                                            <td>{{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}</td>
                                            <td>
                                                <small>{{ $log['timestamp'] ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                @php
                                                    $severity = $log['severity'] ?? 'INFO';
                                                    $severityClass = match($severity) {
                                                        'ERROR' => 'danger',
                                                        'WARNING' => 'warning',
                                                        'INFO' => 'info',
                                                        'DEBUG' => 'success',
                                                        default => 'secondary'
                                                    };
                                                    $severityIcon = match($severity) {
                                                        'ERROR' => '🔴',
                                                        'WARNING' => '🟡',
                                                        'INFO' => '🔵',
                                                        'DEBUG' => '🟢',
                                                        default => '⚪'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $severityClass }}">
                                                    {{ $severityIcon }} {{ $severity }}
                                                </span>
                                            </td>
                                            <td>
                                                <div style="max-width:500px; word-break:break-word;">
                                                    {{ $log['message'] ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($log['tenant_id'] ?? null)
                                                    <code>{{ $log['tenant_id'] }}</code>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="bx bx-file bx-lg d-block mb-2" style="font-size:48px;"></i>
                                                No logs found
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- 🔥 PAGINATION FIXED -->
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    @if($logs->total() > 0)
                                        Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() ?? 0 }} results
                                    @endif
                                </div>
                                <div>
                                    @if($logs->hasPages())
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-sm mb-0">
                                                {{-- Previous Page Link --}}
                                                @if($logs->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <span class="page-link"><i class="bx bx-chevron-left"></i></span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $logs->previousPageUrl() }}" rel="prev">
                                                            <i class="bx bx-chevron-left"></i>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @php
                                                    $start = max(1, $logs->currentPage() - 2);
                                                    $end = min($logs->lastPage(), $logs->currentPage() + 2);
                                                @endphp

                                                @if($start > 1)
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $logs->url(1) }}">1</a>
                                                    </li>
                                                    @if($start > 2)
                                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                                    @endif
                                                @endif

                                                @for($i = $start; $i <= $end; $i++)
                                                    @if($i == $logs->currentPage())
                                                        <li class="page-item active" aria-current="page">
                                                            <span class="page-link">{{ $i }}</span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link" href="{{ $logs->url($i) }}">{{ $i }}</a>
                                                        </li>
                                                    @endif
                                                @endfor

                                                @if($end < $logs->lastPage())
                                                    @if($end < $logs->lastPage() - 1)
                                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                                    @endif
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $logs->url($logs->lastPage()) }}">{{ $logs->lastPage() }}</a>
                                                    </li>
                                                @endif

                                                {{-- Next Page Link --}}
                                                @if($logs->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $logs->nextPageUrl() }}" rel="next">
                                                            <i class="bx bx-chevron-right"></i>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item disabled">
                                                        <span class="page-link"><i class="bx bx-chevron-right"></i></span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </nav>
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
        function clearLogs() {
            if (confirm('Are you sure you want to clear all logs?')) {
                fetch('{{ route("super-admin.logs.clear") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to clear logs');
                    }
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
            }
        }
    </script>
    @endpush

</x-layouts.app>