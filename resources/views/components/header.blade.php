<!doctype html>
<html lang="en">

    
<head>
        
        <meta charset="utf-8" />
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico')}}">

        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        <!-- App js -->
        <script src="{{ asset('assets/js/plugin.js') }}"></script>

    </head>

    <body data-sidebar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
            <!-- ===== HEADER ===== -->
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box">
                            @php
                                $siteSetting = DB::table('site_settings')->first();
                                $logo = $siteSetting && $siteSetting->logo 
                                    ? asset('admin_uploads/' . $siteSetting->logo) 
                                    : asset('assets/images/logo-dark.png');
                            @endphp

                            <a href="{{ url('/dashboard') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ $logo }}" alt="" height="28">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ $logo }}" alt="" height="40">
                                </span>
                            </a>

                            <a href="{{ url('/dashboard') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ $logo }}" alt="" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ $logo }}" alt="" height="40">
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                
                                @php
                                    $user = Auth::user();
                                    $userName = $user->name ?? 'Guest';
                                    $userType = $user->type ?? 'User';
                                    
                                    // Show role badge
                                    $roleBadge = '';
                                    switch($userType) {
                                        case 'SuperAdmin':
                                            $roleBadge = '🔴 Super Admin';
                                            break;
                                        case 'Admin':
                                            $roleBadge = '🟣 Admin';
                                            break;
                                        case 'Owner':
                                            $roleBadge = '🟢 Owner';
                                            break;
                                        case 'Staff':
                                            $roleBadge = '🔵 Staff';
                                            break;
                                        case 'SalesAgent':
                                            $roleBadge = '🟡 Sales Agent';
                                            break;
                                        default:
                                            $roleBadge = '👤 User';
                                    }
                                @endphp

                                <img class="rounded-circle header-profile-user" 
                                    src="{{ $siteSetting && $siteSetting->logo ? asset('admin_uploads/' . $siteSetting->logo) : asset('assets/images/users/avatar-1.jpg') }}" 
                                    alt="Header Avatar">
                                                        
                                <span class="d-none d-xl-inline-block ms-1">
                                    {{ $userName }}
                                    <small class="text-muted d-block" style="font-size: 10px;">{{ $roleBadge }}</small>
                                </span>
                                
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="bx bx-user font-size-16 align-middle me-1"></i> 
                                    <span>Profile</span>
                                </a>

                                @if(Auth::user()->type == 'SuperAdmin')
                                    <a class="dropdown-item" href="{{ route('super-admin.dashboard') }}">
                                        <i class="bx bx-shield font-size-16 align-middle me-1"></i> 
                                        <span>Super Admin Panel</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @endif

                                @if(Auth::user()->type == 'Owner')
                                    <a class="dropdown-item" href="{{ route('owner.dashboard') }}">
                                        <i class="bx bx-store font-size-16 align-middle me-1"></i> 
                                        <span>Business Dashboard</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                @endif

                                <a class="dropdown-item" href="{{ route('change.password') }}">
                                    <i class="bx bx-lock font-size-16 align-middle me-1"></i> 
                                    <span>Change Password</span>
                                </a>

                                <div class="dropdown-divider"></div>

                                @php
                                    // Determine logout route based on user type
                                    $logoutRoute = route('logout');
                                @endphp

                                <a class="dropdown-item text-danger" href="{{ $logoutRoute }}">
                                    <i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> 
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>