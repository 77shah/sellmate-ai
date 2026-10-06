<!-- ===== LEFT SIDEBAR ===== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                
                @php
                    $user = Auth::user();
                    $userType = $user->type ?? 'Guest';
                @endphp

                <!-- ===== SUPER ADMIN MENU ===== -->
                @if($userType == 'SuperAdmin')
                    <li class="menu-title">🚀 Super Admin</li>
                    
                    <li>
                        <a href="{{ route('super-admin.dashboard') }}" class="waves-effect">
                            <i class="bx bx-home-circle"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('super-admin.businesses') }}" class="waves-effect">
                            <i class="bx bx-store"></i>
                            <span>Businesses</span>
                        </a>
                    </li>
                    
                    <!-- 🔥 Plans Management -->
                    <li>
                        <a href="{{ route('admin.plans.index') }}" class="waves-effect">
                            <i class="bx bx-credit-card"></i>
                            <span>Plans Management</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('super-admin.subscriptions') }}" class="waves-effect">
                            <i class="bx bx-credit-card"></i>
                            <span>Subscriptions</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.ai-usage') }}" class="waves-effect">
                            <i class="bx bx-bulb"></i>
                            <span>AI Usage</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.cloud-resources') }}" class="waves-effect">
                            <i class="bx bx-cloud"></i>
                            <span>Cloud Resources</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.billing') }}" class="waves-effect">
                            <i class="bx bx-dollar"></i>
                            <span>Billing Engine</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.logs') }}" class="waves-effect">
                            <i class="bx bx-list-ul"></i>
                            <span>Logs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.api-gateway') }}" class="waves-effect">
                            <i class="bx bx-link"></i>
                            <span>API Gateway</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.system-health') }}" class="waves-effect">
                            <i class="bx bx-heart"></i>
                            <span>System Health</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.deployments') }}" class="waves-effect">
                            <i class="bx bx-code"></i>
                            <span>Deployments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.feature-flags') }}" class="waves-effect">
                            <i class="bx bx-flag"></i>
                            <span>Feature Flags</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('super-admin.security') }}" class="waves-effect">
                            <i class="bx bx-shield"></i>
                            <span>Security Center</span>
                        </a>
                    </li>

                    <!-- ===== USERS MANAGEMENT ===== -->
                    <li>
                        <a href="{{ route('super-admin.users.index') }}" class="waves-effect">
                            <i class="bx bx-user"></i>
                            <span>Users Management</span>
                        </a>
                    </li>

                    <!-- ===== WHATSAPP SETTINGS ===== -->
                    <li>
                        <a href="{{ route('super-admin.whatsapp-settings') }}" class="waves-effect">
                            <i class="bx bxl-whatsapp"></i>
                            <span>WhatsApp Settings</span>
                        </a>
                    </li>

                    <!-- ===== BACKUP/RESTORE ===== 
                    <li>
                        <a href="#" class="waves-effect">
                            <i class="bx bx-cloud-upload"></i>
                            <span>Backup & Restore</span>
                        </a>
                    </li> -->

                    <!-- ===== ADMIN PANEL ===== -->
                    <li class="menu-title mt-4">📋 Admin Panel</li>
                    
                    <li>
                        <a href="{{ route('dashboard') }}" class="waves-effect">
                            <i class="bx bx-home-circle"></i>
                            <span>Admin Dashboard</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-map"></i>
                            <span>Location</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('location.country') }}">Country</a></li>
                            <li><a href="{{ route('location.state') }}">State</a></li>
                            <li><a href="{{ route('location.city') }}">City</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-grid-alt"></i>
                            <span>Categories</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('category.index') }}">Category</a></li>
                            <li><a href="{{ route('subcategory.index') }}">Sub Category</a></li>
                            <li><a href="{{ route('innercategory.index') }}">Inner Category</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-cog"></i>
                            <span>Setting</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('terms.index') }}">Terms and Condition</a></li>
                            <li><a href="{{ route('privacy.index') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('site.setting') }}">Site Setting</a></li>
                            <li><a href="{{ route('about.index') }}">About Us</a></li>
                            <li><a href="{{ route('sms.index') }}">SMS Setting</a></li>
                            <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                            <li><a href="{{ route('refund.index') }}">Refund Policy</a></li>
                            <li><a href="{{ route('refer.index') }}">Refer & Earn</a></li>
                            <li><a href="{{ route('complain-types.index') }}">Complain Types</a></li>
                            <li><a href="{{ route('app.update') }}">App Update</a></li>
                            <li><a href="{{ route('gateway.index') }}">Gateway</a></li>
                            <li><a href="{{ route('ads.index') }}">Ads Setting</a></li>
                            <li><a href="{{ route('social.index') }}">Social Setting</a></li>
                            <li><a href="{{ route('firebase.index') }}">Firebase Setting</a></li>
                            <li><a href="{{ route('gst.index') }}">GST Setting</a></li>
                        </ul>
                    </li>
                @endif

                <!-- ===== ADMIN MENU (Only Admin) ===== -->
                @if($userType == 'Admin')
                    <li class="menu-title">📋 Admin Panel</li>
                    
                    <li>
                        <a href="{{ route('dashboard') }}" class="waves-effect">
                            <i class="bx bx-home-circle"></i>
                            <span>Admin Dashboard</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-map"></i>
                            <span>Location</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('location.country') }}">Country</a></li>
                            <li><a href="{{ route('location.state') }}">State</a></li>
                            <li><a href="{{ route('location.city') }}">City</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-grid-alt"></i>
                            <span>Categories</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('category.index') }}">Category</a></li>
                            <li><a href="{{ route('subcategory.index') }}">Sub Category</a></li>
                            <li><a href="{{ route('innercategory.index') }}">Inner Category</a></li>
                        </ul>
                    </li>

                    <!-- ===== USERS MANAGEMENT ===== -->
                    <!-- <li>
                        <a href="#" class="waves-effect">
                            <i class="bx bx-user"></i>
                            <span>Users Management</span>
                        </a>
                    </li> -->

                    <!-- ===== ACTIVITY LOGS ===== -->
                    <!-- <li>
                        <a href="#" class="waves-effect">
                            <i class="bx bx-time"></i>
                            <span>Activity Logs</span>
                        </a>
                    </li> -->

                    <!-- ===== SYSTEM SETTINGS ===== -->
                    <!-- <li>
                        <a href="#" class="waves-effect">
                            <i class="bx bx-cog"></i>
                            <span>System Settings</span>
                        </a>
                    </li> -->

                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="bx bx-cog"></i>
                            <span>Setting</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('terms.index') }}">Terms and Condition</a></li>
                            <li><a href="{{ route('privacy.index') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('site.setting') }}">Site Setting</a></li>
                            <li><a href="{{ route('about.index') }}">About Us</a></li>
                            <li><a href="{{ route('sms.index') }}">SMS Setting</a></li>
                            <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                            <li><a href="{{ route('refund.index') }}">Refund Policy</a></li>
                            <li><a href="{{ route('refer.index') }}">Refer & Earn</a></li>
                            <li><a href="{{ route('complain-types.index') }}">Complain Types</a></li>
                            <li><a href="{{ route('app.update') }}">App Update</a></li>
                            <li><a href="{{ route('gateway.index') }}">Gateway</a></li>
                            <li><a href="{{ route('ads.index') }}">Ads Setting</a></li>
                            <li><a href="{{ route('social.index') }}">Social Setting</a></li>
                            <li><a href="{{ route('firebase.index') }}">Firebase Setting</a></li>
                            <li><a href="{{ route('gst.index') }}">GST Setting</a></li>
                        </ul>
                    </li>
                @endif

                <!-- ===== BUSINESS OWNER MENU ===== -->
                @if($userType == 'Owner')
                    <li class="menu-title">🏪 Business</li>
                    
                    <li>
                        <a href="{{ route('owner.dashboard') }}" class="waves-effect">
                            <i class="bx bx-home-circle"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- 🔥 Subscription Plans -->
                    <li>
                        <a href="{{ route('owner.plans') }}" class="waves-effect">
                            <i class="bx bx-credit-card"></i>
                            <span>Subscription Plans</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('owner.unanswered-queries') }}" class="waves-effect">
                            <i class="bx bx-question-mark"></i>
                            <span>Unanswered Queries</span>
                            @php
                                $pendingCount = \App\Models\UnansweredQuery::where('tenant_id', Auth::user()->tenant_id ?? Auth::id())->where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="badge bg-danger rounded-pill float-end">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    
                    <!-- 🔥 Subscription Status -->
                    <li>
                        <a href="{{ route('owner.subscription.status') }}" class="waves-effect">
                            <i class="bx bx-stats"></i>
                            <span>Subscription Status</span>
                        </a>
                    </li>
                    
                    <!-- ===== PRODUCTS ===== -->
                    <li>
                        <a href="{{ route('owner.products.index') }}" class="waves-effect">
                            <i class="bx bx-package"></i>
                            <span>Products</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('owner.category.index') }}" class="waves-effect">
                            <i class="bx bx-grid-alt"></i>
                            <span>Categories</span>
                        </a>
                    </li>

                    <!-- ===== INBOX ===== -->
                    <li>
                        <a href="{{ route('owner.inbox') }}" class="waves-effect">
                            <i class="bx bx-message-dots"></i>
                            <span>Inbox</span>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <span class="badge bg-danger rounded-pill float-end">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('owner.crm') }}" class="waves-effect">
                            <i class="bx bx-user"></i>
                            <span>CRM</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('owner.orders') }}" class="waves-effect">
                            <i class="bx bx-cart"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('owner.payments') }}" class="waves-effect">
                            <i class="bx bx-credit-card"></i>
                            <span>Payments</span>
                        </a>
                    </li>
                    
                    <li class="menu-title mt-4">🤖 AI Settings</li>
                    
                    <li>
                        <a href="{{ route('owner.ai-settings') }}" class="waves-effect">
                            <i class="bx bx-bot"></i>
                            <span>AI Chat Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('owner.knowledge') }}" class="waves-effect">
                            <i class="bx bx-book"></i>
                            <span>Knowledge Base</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('owner.workflows') }}" class="waves-effect">
                            <i class="bx bx-git-branch"></i>
                            <span>Automation Workflows</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('owner.analytics') }}" class="waves-effect">
                            <i class="bx bx-bar-chart"></i>
                            <span>Analytics</span>
                        </a>
                    </li>

                    <!-- ===== REPORTS ===== -->
                    <li>
                        <a href="{{ route('owner.reports') }}" class="waves-effect">
                            <i class="bx bx-file"></i>
                            <span>Reports</span>
                        </a>
                    </li>

                    <!-- ===== STAFF MANAGEMENT ===== -->
                    <li>
                        <a href="{{ route('owner.staff') }}" class="waves-effect">
                            <i class="bx bx-group"></i>
                            <span>Staff Management</span>
                        </a>
                    </li>

                    <!-- ===== WHATSAPP NUMBER ===== -->
                    <li>
                        <a href="{{ route('owner.whatsapp-numbers') }}" class="waves-effect">
                            <i class="bx bxl-whatsapp"></i>
                            <span>WhatsApp Number</span>
                        </a>
                    </li>
                @endif

                <!-- ===== STAFF / SALES AGENT MENU ===== -->
                @if(in_array($userType, ['Staff', 'SalesAgent']))
                    <li class="menu-title">👤 Staff Panel</li>
                    
                    <li>
                        <a href="{{ route('staff.dashboard') }}" class="waves-effect">
                            <i class="bx bx-home-circle"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.inbox') }}" class="waves-effect">
                            <i class="bx bx-message-dots"></i>
                            <span>Inbox</span>
                        </a>
                    </li>
                    
                    @if($userType == 'SalesAgent')
                        <li>
                            <a href="{{ route('staff.crm') }}" class="waves-effect">
                                <i class="bx bx-user"></i>
                                <span>CRM</span>
                            </a>
                        </li>
                    @endif
                    
                    <li>
                        <a href="{{ route('staff.orders') }}" class="waves-effect">
                            <i class="bx bx-cart"></i>
                            <span>Orders</span>
                        </a>
                    </li>

                    <!-- ===== MY TASKS ===== -->
                    <li>
                        <a href="#" class="waves-effect">
                            <i class="bx bx-check-square"></i>
                            <span>My Tasks</span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>

        <!-- ===== SIDEBAR FOOTER ===== -->
        <div class="sidebar-footer text-center p-3 border-top">
            @php
                $socialLinks = DB::table('social_links')->where('status', '1')->get();
            @endphp

            @if($socialLinks->count())
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    @foreach($socialLinks as $social)
                        <a href="{{ $social->social_link }}" target="_blank">
                            <img src="{{ asset('admin_uploads/' . $social->social_image) }}" alt="social" width="30" height="30" style="border-radius: 50%;">
                        </a>
                    @endforeach
                </div>
            @else
                <p class="small text-muted mt-2">No Active Social Links</p>
            @endif
        </div>
    </div>
</div>