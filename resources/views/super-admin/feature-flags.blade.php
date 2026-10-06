<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">🚩 Feature Flags</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">Feature Flags</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="row">
                <div class="col-xl-4 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">Total Features</p>
                            <h4 class="mb-0">{{ $stats['total'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">✅ Active</p>
                            <h4 class="mb-0 text-success">{{ $stats['active'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-muted mb-1">❌ Inactive</p>
                            <h4 class="mb-0 text-danger">{{ $stats['inactive'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features by Category -->
            <div class="row">
                @foreach($groupedFeatures as $category => $items)
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                {{ ucfirst(str_replace('_', ' ', $category)) }}
                            </h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Feature</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $key => $feature)
                                        <tr>
                                            <td>
                                                <i class="{{ $feature['icon'] ?? 'bx-cog' }} me-2"></i>
                                                <strong>{{ $feature['name'] }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $feature['description'] ?? '' }}</small>
                                            </td>
                                            <td>
                                                @if($feature['enabled'])
                                                    <span class="badge bg-success">✅ Active</span>
                                                @else
                                                    <span class="badge bg-danger">❌ Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($feature['enabled'])
                                                    <span class="text-muted">Enabled via .env</span>
                                                @else
                                                    <span class="text-muted">Disabled via .env</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- How to Change -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">ℹ️ How to Enable/Disable Features</h5>
                            <hr>
                            <p>Features are controlled via <code>.env</code> file:</p>
                            <div class="bg-light p-3 rounded">
                                <code>
                                    FEATURE_AI_CHAT=true<br>
                                    FEATURE_WHATSAPP=true<br>
                                    FEATURE_INSTAGRAM=false<br>
                                    FEATURE_KNOWLEDGE_BASE=true<br>
                                    FEATURE_VOICE_AI=false<br>
                                    FEATURE_MULTI_TENANT=true<br>
                                    FEATURE_ANALYTICS=true<br>
                                    FEATURE_WORKFLOWS=false<br>
                                    FEATURE_PAYMENT=true<br>
                                    FEATURE_BULK_IMPORT=true
                                </code>
                            </div>
                            <p class="mt-2">
                                <small class="text-muted">
                                    After changing .env, run: <code>php artisan config:cache</code>
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>