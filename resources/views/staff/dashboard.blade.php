<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">👤 Staff Dashboard</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Staff</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
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
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Assigned Customers</p>
                                    <h4 class="mb-0">{{ $assignedCustomers ?? 0 }}</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-primary">
                                        <span class="avatar-title">
                                            <i class="bx bx-user font-size-24"></i>
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
                                    <p class="text-muted fw-medium mb-1">Total Orders</p>
                                    <h4 class="mb-0">{{ $totalOrders ?? 0 }}</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-info">
                                        <span class="avatar-title">
                                            <i class="bx bx-cart font-size-24"></i>
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
                                    <p class="text-muted fw-medium mb-1">Pending Orders</p>
                                    <h4 class="mb-0">{{ $pendingOrders ?? 0 }}</h4>
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
                <div class="col-xl-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-1">Active Conversations</p>
                                    <h4 class="mb-0">{{ $activeConversations ?? 0 }}</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-success">
                                        <span class="avatar-title">
                                            <i class="bx bx-chat font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Recent Activity</h4>
                            <div class="table-responsive">
                                <table class="table align-middle table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Activity</th>
                                            <th>Customer</th>
                                            <th>Status</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>New order placed</td>
                                            <td>Rahul Sharma</td>
                                            <td><span class="badge bg-success">Completed</span></td>
                                            <td>2 min ago</td>
                                        </tr>
                                        <tr>
                                            <td>Customer query resolved</td>
                                            <td>Priya Patel</td>
                                            <td><span class="badge bg-info">Resolved</span></td>
                                            <td>15 min ago</td>
                                        </tr>
                                        <tr>
                                            <td>New lead assigned</td>
                                            <td>Amit Kumar</td>
                                            <td><span class="badge bg-warning">Pending</span></td>
                                            <td>1 hour ago</td>
                                        </tr>
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