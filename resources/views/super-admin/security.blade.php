<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">🛡️ Security Center</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">Security</li>
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
                            <p class="text-muted mb-1">🔴 Failed Logins (Today)</p>
                            <h4 class="mb-0 text-danger">{{ $data['failed_logins'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">⚠️ Suspicious IPs</p>
                            <h4 class="mb-0 text-warning">{{ $data['suspicious_ips']->count() ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">🚫 Blocked IPs</p>
                            <h4 class="mb-0 text-danger">{{ $data['blocked_ips']->count() ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-muted mb-1">📊 Success Rate</p>
                            <h4 class="mb-0 text-success">{{ $data['stats']['success_rate'] ?? 0 }}%</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suspicious IPs -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">⚠️ Suspicious IPs</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>IP Address</th>
                                            <th>Attempts</th>
                                            <th>Last Attempt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data['suspicious_ips'] ?? [] as $ip)
                                        <tr>
                                            <td><code>{{ $ip->ip_address }}</code></td>
                                            <td>{{ $ip->attempts }}</td>
                                            <td>{{ $ip->last_attempt_at ? $ip->last_attempt_at->diffForHumans() : 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No suspicious IPs</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Blocked IPs -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">🚫 Blocked IPs</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>IP Address</th>
                                            <th>Reason</th>
                                            <th>Blocked Until</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data['blocked_ips'] ?? [] as $ip)
                                        <tr>
                                            <td><code>{{ $ip->ip_address }}</code></td>
                                            <td>{{ $ip->reason ?? 'N/A' }}</td>
                                            <td>
                                                @if($ip->permanent)
                                                    <span class="badge bg-danger">Permanent</span>
                                                @else
                                                    {{ $ip->blocked_until ? $ip->blocked_until->diffForHumans() : 'N/A' }}
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No blocked IPs</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Login Attempts -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Recent Login Attempts</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Email</th>
                                            <th>IP</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($data['recent_attempts'] ?? [] as $attempt)
                                        <tr>
                                            <td>{{ $attempt->email }}</td>
                                            <td><code>{{ $attempt->ip_address }}</code></td>
                                            <td>
                                                @if($attempt->success)
                                                    <span class="badge bg-success">✅ Success</span>
                                                @else
                                                    <span class="badge bg-danger">❌ Failed</span>
                                                @endif
                                            </td>
                                            <td>{{ $attempt->created_at ? $attempt->created_at->diffForHumans() : 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No login attempts</td>
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

</x-layouts.app>