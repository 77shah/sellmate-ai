<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">🤖 AI Usage Dashboard</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">AI Usage</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-primary bg-soft rounded p-2">
                                        <i class="bx bx-message-dots font-size-24 text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16 mb-1">Total Messages</h5>
                                    <h3 class="mb-0">{{ number_format($totalMessages) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-success bg-soft rounded p-2">
                                        <i class="bx bx-data font-size-24 text-success"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16 mb-1">Total Tokens</h5>
                                    <h3 class="mb-0">{{ number_format($totalTokens) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-warning bg-soft rounded p-2">
                                        <i class="bx bx-dollar font-size-24 text-warning"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16 mb-1">Total Cost</h5>
                                    <h3 class="mb-0">₹{{ number_format($totalCost, 4) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-sm bg-info bg-soft rounded p-2">
                                        <i class="bx bx-time font-size-24 text-info"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16 mb-1">Avg Response</h5>
                                    <h3 class="mb-0">{{ round($avgResponse, 2) }}ms</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Intent Breakdown -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Intent Breakdown</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Intent</th>
                                            <th>Count</th>
                                            <th>Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($intents as $intent)
                                        <tr>
                                            <td>{{ ucfirst($intent->intent) }}</td>
                                            <td>{{ $intent->count }}</td>
                                            <td>
                                                @php
                                                    $percent = $totalMessages > 0 ? round(($intent->count / $totalMessages) * 100, 1) : 0;
                                                @endphp
                                                {{ $percent }}%
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Top Tenants</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tenant</th>
                                            <th>Messages</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tenantUsage as $tenant)
                                        <tr>
                                            <td><code>{{ $tenant->tenant_id }}</code></td>
                                            <td>{{ $tenant->total }}</td>
                                        </tr>
                                        @endforeach 
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