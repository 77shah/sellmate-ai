<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📋 Deployment Details</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.deployments') }}">Deployments</a></li>
                                <li class="breadcrumb-item active">Details</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Deployment #{{ substr($deployment->id, 0, 8) }}</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Version:</strong> <code>v{{ $deployment->version }}</code></p>
                                    <p><strong>Branch:</strong> {{ $deployment->branch ?? 'N/A' }}</p>
                                    <p><strong>Commit:</strong> <code>{{ $deployment->commit_hash ?? 'N/A' }}</code></p>
                                    <p><strong>Status:</strong> {!! $deployment->status_badge !!}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Duration:</strong> {{ $deployment->duration }}s</p>
                                    <p><strong>Deployed By:</strong> {{ $deployment->deployer->name ?? 'System' }}</p>
                                    <p><strong>Message:</strong> {{ $deployment->message ?? 'N/A' }}</p>
                                    <p><strong>Date:</strong> {{ $deployment->created_at->format('d M Y H:i:s') }}</p>
                                </div>
                            </div>
                            <hr>
                            <a href="{{ route('super-admin.deployments') }}" class="btn btn-secondary">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>