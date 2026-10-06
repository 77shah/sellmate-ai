<x-layouts.app>

    @section('title', 'Subscriptions')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">💳 Subscriptions</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">Subscriptions</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Total Subscriptions</p>
                                    <h4 class="mb-0">{{ $stats['total'] ?? 0 }}</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-primary">
                                        <span class="avatar-title">
                                            <i class="bx bx-credit-card font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Active</p>
                                    <h4 class="mb-0 text-success">{{ $stats['active'] ?? 0 }}</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-success">
                                        <span class="avatar-title">
                                            <i class="bx bx-check-circle font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Expired</p>
                                    <h4 class="mb-0 text-danger">{{ $stats['expired'] ?? 0 }}</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-danger">
                                        <span class="avatar-title">
                                            <i class="bx bx-x-circle font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Trial</p>
                                    <h4 class="mb-0 text-warning">{{ $stats['trial'] ?? 0 }}</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-warning">
                                        <span class="avatar-title">
                                            <i class="bx bx-time font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscriptions Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">All Subscriptions</h4>
                                <span class="badge bg-primary">{{ $stats['total'] ?? 0 }} Total</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Business</th>
                                            <th>Plan</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Auto Renew</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subscriptions ?? [] as $index => $sub)
                                        <tr>
                                            <td>{{ $subscriptions->firstItem() + $index ?? $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $sub->tenant->name ?? 'N/A' }}</strong>
                                                @if($sub->tenant)
                                                    <br><small class="text-muted">{{ $sub->tenant->email ?? '' }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $sub->plan_name }}</span>
                                            </td>
                                            <td>₹{{ number_format($sub->amount ?? 0, 2) }}</td>
                                            <td>
                                                @if($sub->status == 'active')
                                                    <span class="badge bg-success"><i class="bx bx-check-circle me-1"></i> Active</span>
                                                @elseif($sub->status == 'expired')
                                                    <span class="badge bg-danger"><i class="bx bx-x-circle me-1"></i> Expired</span>
                                                @elseif($sub->status == 'cancelled')
                                                    <span class="badge bg-secondary"><i class="bx bx-x me-1"></i> Cancelled</span>
                                                @else
                                                    <span class="badge bg-warning">{{ ucfirst($sub->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $sub->start_date ? $sub->start_date->format('d M Y') : 'N/A' }}</td>
                                            <td>
                                                @if($sub->end_date)
                                                    @if($sub->end_date < now())
                                                        <span class="text-danger">{{ $sub->end_date->format('d M Y') }}</span>
                                                    @else
                                                        {{ $sub->end_date->format('d M Y') }}
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge {{ $sub->auto_renew ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ $sub->auto_renew ? '✅ Yes' : '❌ No' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bx bx-credit-card bx-lg d-block mb-2" style="font-size: 48px;"></i>
                                                    <p class="mb-0">No subscriptions found</p>
                                                    <small>Subscriptions will appear here once users subscribe.</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-3">
                                @if(isset($subscriptions) && method_exists($subscriptions, 'links'))
                                    {{ $subscriptions->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>