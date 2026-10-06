<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">👁️ Business Details</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.businesses') }}">Businesses</a></li>
                                <li class="breadcrumb-item active">View</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">{{ $business->name }}</h4>
                                <div>
                                    @if($business->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Suspended</span>
                                    @endif
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width:220px;">Business Name</th>
                                        <td><strong>{{ $business->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Tenant ID</th>
                                        <td><code>{{ $business->tenant_id }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $business->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mobile No</th>
                                        <td>{{ $business->mobile_no ?? ($businessDetails->phone ?? 'N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>WhatsApp No</th>
                                        <td>{{ $business->whatsapp_no ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Business Type</th>
                                        <td>{{ $businessDetails->business_type ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $businessDetails->address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Website</th>
                                        <td>
                                            @if($businessDetails && $businessDetails->website)
                                                <a href="{{ $businessDetails->website }}" target="_blank">{{ $businessDetails->website }}</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Business Status</th>
                                        <td>
                                            @if(($businessDetails->status ?? $business->status) == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Suspended</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Current Plan</th>
                                        <td>
                                            @if($business->current_plan_id && $business->currentPlan)
                                                <span class="badge bg-primary">{{ $business->currentPlan->name }}</span>
                                            @else
                                                <span class="text-muted">No Plan</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Subscription Expires</th>
                                        <td>{{ $business->subscription_expires_at ? \Carbon\Carbon::parse($business->subscription_expires_at)->format('d M Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Joined</th>
                                        <td>{{ $business->created_at ? $business->created_at->format('d M Y h:i A') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $business->updated_at ? $business->updated_at->format('d M Y h:i A') : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>

                            {{-- Stats Row --}}
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="mb-1">{{ \App\Models\Customer::where('tenant_id', $business->tenant_id)->count() }}</h3>
                                            <p class="text-muted mb-0">Customers</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="mb-1">{{ \App\Models\WhatsAppNumber::where('tenant_id', $business->tenant_id)->count() }}</h3>
                                            <p class="text-muted mb-0">WhatsApp Numbers</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="mb-1">{{ \App\Models\KnowledgeBase::where('tenant_id', $business->tenant_id)->count() }}</h3>
                                            <p class="text-muted mb-0">Knowledge Docs</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h3 class="mb-1">{{ \App\Models\Order::where('tenant_id', $business->tenant_id)->count() }}</h3>
                                            <p class="text-muted mb-0">Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('super-admin.businesses.edit', $business->id) }}" class="btn btn-warning">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <form action="{{ route('super-admin.businesses.impersonate', $business->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" 
                                            onclick="return confirm('Login as this business owner?')">
                                        <i class="bx bx-user-check"></i> Login as Owner
                                    </button>
                                </form>
                                <a href="{{ route('super-admin.businesses') }}" class="btn btn-secondary">
                                    <i class="bx bx-arrow-back"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>