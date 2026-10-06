<x-layouts.app>

    @section('title', 'System Health')

    @section('content')
    <style>
        .health-card {
            border: none;
            border-radius: 16px;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }
        .health-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }
        .health-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }
        .health-card.healthy::before { background: linear-gradient(90deg, #10b981, #059669); }
        .health-card.warning::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
        .health-card.unhealthy::before { background: linear-gradient(90deg, #ef4444, #dc2626); }

        .health-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 16px;
        }
        .health-icon.healthy {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #059669;
        }
        .health-icon.warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
        }
        .health-icon.unhealthy {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #dc2626;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            animation: pulse 2s infinite;
        }
        .pulse-dot.healthy { background: #10b981; box-shadow: 0 0 0 0 rgba(16,185,129,0.7); }
        .pulse-dot.warning { background: #f59e0b; box-shadow: 0 0 0 0 rgba(245,158,11,0.7); }
        .pulse-dot.unhealthy { background: #ef4444; box-shadow: 0 0 0 0 rgba(239,68,68,0.7); }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 currentColor; }
            70% { box-shadow: 0 0 0 8px transparent; }
            100% { box-shadow: 0 0 0 0 transparent; }
        }

        .overall-banner {
            border-radius: 16px;
            padding: 24px 32px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .overall-banner.healthy {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .overall-banner.degraded {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .overall-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .metric-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
            border-bottom: 1px dashed #f1f3f7;
        }
        .metric-row:last-child { border-bottom: none; }
        .metric-label { color: #9ca3af; }
        .metric-value { color: #1f2937; font-weight: 600; }
    </style>

    <div class="page-content">
        <div class="container-fluid">

            {{-- Page Title --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">❤️ System Health</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">System Health</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Overall Status Banner --}}
            <div class="row mb-4">
                <div class="col-12">
                    <div class="overall-banner {{ $overallStatus == 'healthy' ? 'healthy' : 'degraded' }}">
                        <div class="d-flex justify-content-between align-items-center flex-wrap position-relative">
                            <div>
                                <h4 class="text-white mb-1">
                                    @if($overallStatus == 'healthy')
                                        ✅ All Systems Operational
                                    @else
                                        ⚠️ Some Systems Need Attention
                                    @endif
                                </h4>
                                <p class="mb-0 opacity-75">
                                    <span class="pulse-dot {{ $overallStatus == 'healthy' ? 'healthy' : 'warning' }}"></span>
                                    Last checked: {{ $lastChecked }}
                                </p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <button class="btn btn-light" onclick="location.reload()">
                                    <i class="bx bx-refresh me-1"></i> Refresh Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Services Grid --}}
            <div class="row">
                @foreach($services as $name => $info)
                    @php
                        $iconMap = [
                            'laravel' => 'bx-server',
                            'database' => 'bx-data',
                            'python_ai' => 'bx-brain',
                            'redis' => 'bx-hdd',
                            'qdrant' => 'bx-purchase-tag',
                            'whatsapp' => 'bxl-whatsapp',
                        ];
                        $icon = $iconMap[$name] ?? 'bx-cog';

                        $displayNames = [
                            'laravel' => 'Laravel App',
                            'database' => 'MySQL Database',
                            'python_ai' => 'Python AI',
                            'redis' => 'Redis Cache',
                            'qdrant' => 'Qdrant DB',
                            'whatsapp' => 'WhatsApp API',
                        ];
                        $displayName = $displayNames[$name] ?? ucfirst(str_replace('_', ' ', $name));

                        $statusText = [
                            'healthy' => 'Operational',
                            'warning' => 'Warning',
                            'unhealthy' => 'Down',
                        ];
                        $statusLabel = $statusText[$info['status']] ?? 'Unknown';
                    @endphp

                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card health-card {{ $info['status'] }} h-100">
                            <div class="card-body p-4">

                                {{-- Icon --}}
                                <div class="health-icon {{ $info['status'] }}">
                                    <i class="{{ $icon }}"></i>
                                </div>

                                {{-- Service Name --}}
                                <h5 class="text-center mb-2 fw-bold">{{ $displayName }}</h5>

                                {{-- Status Badge --}}
                                <div class="text-center mb-3">
                                    <span class="badge bg-{{ $info['status'] == 'healthy' ? 'success' : ($info['status'] == 'warning' ? 'warning' : 'danger') }} px-3 py-2">
                                        <span class="pulse-dot {{ $info['status'] }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                {{-- Metrics --}}
                                <div class="mt-3">
                                    <div class="metric-row">
                                        <span class="metric-label">Response Time</span>
                                        <span class="metric-value">{{ $info['response_time'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="metric-row">
                                        <span class="metric-label">Uptime</span>
                                        <span class="metric-value">{{ $info['uptime'] ?? 'N/A' }}</span>
                                    </div>
                                    @if(!empty($info['message']))
                                    <div class="metric-row">
                                        <span class="metric-label">Status Info</span>
                                        <span class="metric-value text-end" style="font-size:11px; max-width: 60%;">{{ $info['message'] }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary Stats --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex gap-4 flex-wrap">
                                    @php
                                        $healthy = collect($services)->where('status', 'healthy')->count();
                                        $warning = collect($services)->where('status', 'warning')->count();
                                        $unhealthy = collect($services)->where('status', 'unhealthy')->count();
                                        $total = count($services);
                                    @endphp
                                    <div>
                                        <small class="text-muted d-block">Total Services</small>
                                        <h5 class="mb-0">{{ $total }}</h5>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Healthy</small>
                                        <h5 class="mb-0 text-success">{{ $healthy }}</h5>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Warnings</small>
                                        <h5 class="mb-0 text-warning">{{ $warning }}</h5>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Down</small>
                                        <h5 class="mb-0 text-danger">{{ $unhealthy }}</h5>
                                    </div>
                                </div>
                                <div class="text-muted">
                                    <i class="bx bx-time me-1"></i>
                                    <small>Auto-refreshes every 60 seconds</small>
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
        setTimeout(function() {
            location.reload();
        }, 60000);
    </script>
    @endpush

</x-layouts.app>