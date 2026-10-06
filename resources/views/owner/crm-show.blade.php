<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">👤 Customer Details</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('owner.crm') }}">CRM</a></li>
                                <li class="breadcrumb-item active">View</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">{{ $customer->name }}</h4>
                                <div>
                                    <span class="badge bg-primary">{{ ucfirst($customer->stage ?? 'new') }}</span>
                                    <span class="badge {{ $customer->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($customer->status ?? 'active') }}
                                    </span>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width:150px;">Name</th>
                                        <td><strong>{{ $customer->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $customer->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $customer->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Lead Score</th>
                                        <td>
                                            <span class="badge {{ $customer->lead_score >= 70 ? 'bg-success' : ($customer->lead_score >= 40 ? 'bg-warning' : 'bg-secondary') }}">
                                                {{ $customer->lead_score ?? 0 }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Stage</th>
                                        <td>{{ ucfirst($customer->stage ?? 'new') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ ucfirst($customer->status ?? 'active') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tags</th>
                                        <td>{{ $customer->tags ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $customer->address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Notes</th>
                                        <td>{{ $customer->notes ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Joined</th>
                                        <td>{{ $customer->created_at ? $customer->created_at->format('d M Y h:i A') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Updated</th>
                                        <td>{{ $customer->updated_at ? $customer->updated_at->format('d M Y h:i A') : 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('owner.crm.edit', $customer->id) }}" class="btn btn-warning">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <a href="{{ route('owner.crm') }}" class="btn btn-secondary">
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