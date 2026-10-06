<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Home</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Welcome Card -->
            <div class="row">
                <div class="col-xl-4">
                    <div class="card overflow-hidden">
                        <div class="bg-primary-subtle">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-primary p-3">
                                        <h5 class="text-primary">Welcome Back !</h5>
                                        <p>{{ Auth::user()->name ?? 'Admin' }}</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <img src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="" class="img-thumbnail rounded-circle">
                                    </div>
                                    <h5 class="font-size-15 text-truncate">{{ Auth::user()->name ?? 'Admin' }}</h5>
                                    <p class="text-muted mb-0 text-truncate">{{ Auth::user()->type ?? 'User' }}</p>
                                </div>
                                <div class="col-sm-8">
                                    <div class="pt-4">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5 class="font-size-15">{{ $totalUsers ?? 0 }}</h5>
                                                <p class="text-muted mb-0">Total Users</p>
                                            </div>
                                            <div class="col-6">
                                                <h5 class="font-size-15">{{ $totalCategories ?? 0 }}</h5>
                                                <p class="text-muted mb-0">Categories</p>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('profile') }}" class="btn btn-primary waves-effect waves-light btn-sm">
                                                View Profile <i class="mdi mdi-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Total Users</p>
                                            <h4 class="mb-0">{{ $totalUsers ?? 0 }}</h4>
                                        </div>
                                        <div class="flex-shrink-0 align-self-center">
                                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                <span class="avatar-title">
                                                    <i class="bx bx-user font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Categories</p>
                                            <h4 class="mb-0">{{ $totalCategories ?? 0 }}</h4>
                                        </div>
                                        <div class="flex-shrink-0 align-self-center">
                                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                <span class="avatar-title">
                                                    <i class="bx bx-grid-alt font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mini-stats-wid">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-grow-1">
                                            <p class="text-muted fw-medium">Sub Categories</p>
                                            <h4 class="mb-0">{{ $totalSubCategories ?? 0 }}</h4>
                                        </div>
                                        <div class="flex-shrink-0 align-self-center">
                                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                <span class="avatar-title">
                                                    <i class="bx bx-category font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Recent Activity</h4>
                            <ul class="verti-timeline list-unstyled">
                                <li class="event-list">
                                    <div class="event-timeline-dot">
                                        <i class="bx bx-right-arrow-circle font-size-18"></i>
                                    </div>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <h5 class="font-size-14">Today <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div>Welcome to SellMate AI Dashboard</div>
                                        </div>
                                    </div>
                                </li>
                                <li class="event-list">
                                    <div class="event-timeline-dot">
                                        <i class="bx bx-right-arrow-circle font-size-18"></i>
                                    </div>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <h5 class="font-size-14">Recently <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div>You logged in as {{ Auth::user()->name ?? 'Admin' }}</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Quick Actions</h4>
                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ route('category.index') }}" class="btn btn-primary w-100 mb-2">
                                        <i class="bx bx-grid-alt me-1"></i> Categories
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('profile') }}" class="btn btn-info w-100 mb-2">
                                        <i class="bx bx-user me-1"></i> Profile
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('change.password') }}" class="btn btn-warning w-100 mb-2">
                                        <i class="bx bx-lock me-1"></i> Change Password
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="{{ route('location.country') }}" class="btn btn-success w-100 mb-2">
                                        <i class="bx bx-map me-1"></i> Location
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-layouts.app>