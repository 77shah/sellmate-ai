<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📱 WhatsApp Numbers</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">WhatsApp Numbers</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="mdi mdi-alert-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- 🔥 Plan Banner --}}
            @php
                $plan = Auth::user()->currentPlan;
                $limit = $plan->channels_limit ?? 1;
                $used = $numbers->count();
                $canAddMore = $used < $limit;
            @endphp

            <div class="row mb-3">
                <div class="col-12">
                    <div class="card border-0" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <div class="card-body text-white">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div>
                                    <h5 class="text-white mb-1">
                                        <i class="bx bxl-whatsapp"></i>
                                        {{ $plan->name ?? 'Free' }} Plan — {{ $used }}/{{ $limit }} Numbers Used
                                    </h5>
                                    <p class="mb-0 opacity-75">
                                        @if(!$canAddMore)
                                            ⚠️ Plan limit reached. Upgrade to add more numbers.
                                        @else
                                            ✅ You can add {{ $limit - $used }} more WhatsApp number(s).
                                        @endif
                                    </p>
                                </div>
                                @if(!$canAddMore)
                                    <a href="{{ route('owner.plans') }}" class="btn btn-light btn-sm">
                                        <i class="bx bx-crown"></i> Upgrade
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                <h4 class="card-title mb-0">WhatsApp Numbers</h4>

                                {{-- 🔥 Buttons — Sirf tab dikhao jab limit available ho --}}
                                @if($canAddMore)
                                    <div class="d-flex gap-2">
                                        {{-- 1-Click Connect --}}
                                        <button type="button" class="btn btn-success" 
                                                id="connectWhatsAppBtn"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#connectModal">
                                            <i class="bx bxl-whatsapp me-1"></i> Connect WhatsApp
                                        </button>

                                        {{-- Manual Add --}}
                                        <a href="{{ route('owner.whatsapp-numbers.create') }}" class="btn btn-outline-primary">
                                            <i class="bx bx-plus me-1"></i> Add Manually
                                        </a>
                                    </div>
                                @else
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('owner.plans') }}" class="btn btn-warning">
                                            <i class="bx bx-crown me-1"></i> Upgrade Plan
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Phone Number</th>
                                            <th>Display Name</th>
                                            <th>Default</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($numbers ?? [] as $index => $number)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <i class="bx bxl-whatsapp text-success me-1"></i>
                                                <code>{{ $number->phone_number }}</code>
                                            </td>
                                            <td>{{ $number->display_name ?? 'N/A' }}</td>
                                            <td>
                                                @if($number->is_default)
                                                    <span class="badge bg-success">⭐ Default</span>
                                                @else
                                                    <span class="badge bg-secondary">Not Default</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $number->is_active ? 'success' : 'danger' }}">
                                                    {{ $number->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('owner.whatsapp-numbers.edit', $number->id) }}" class="btn btn-sm btn-warning">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    @if(!$number->is_default)
                                                        <form action="{{ route('owner.whatsapp-numbers.set-default', $number->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="bx bx-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('owner.whatsapp-numbers.delete', $number->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this number?')">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="bx bxl-whatsapp" style="font-size:64px; color:#ddd;"></i>
                                                <h5 class="mt-3">No WhatsApp number connected</h5>
                                                <p class="text-muted mb-3">Connect your WhatsApp Business number to start receiving customer messages.</p>
                                                
                                                @if($canAddMore)
                                                    <button type="button" class="btn btn-success" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#connectModal">
                                                        <i class="bx bxl-whatsapp me-1"></i> Connect Now
                                                    </button>
                                                @else
                                                    <a href="{{ route('owner.plans') }}" class="btn btn-warning">
                                                        <i class="bx bx-crown me-1"></i> Upgrade Plan
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- 🔥 CONNECT WHATSAPP MODAL (Sirf tab jab limit available) -->
    <!-- ========================================== -->
    @if($canAddMore)
    <div class="modal fade" id="connectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📱 Connect WhatsApp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                             style="width:100px; height:100px; background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="bx bxl-whatsapp text-white" style="font-size:56px;"></i>
                        </div>
                    </div>
                    <h4 class="mb-3">Connect Your WhatsApp</h4>
                    <p class="text-muted mb-4">
                        Click the button below to connect your WhatsApp Business number.
                        Takes less than 2 minutes.
                    </p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg" id="embeddedSignupBtn">
                            <i class="bx bxl-whatsapp me-2"></i> Connect Now
                        </button>
                        <small class="text-muted">
                            You'll be redirected to Facebook to authorize.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId: '{{ env("META_APP_ID") }}',
                autoLogAppEvents: true,
                xfbml: true,
                version: 'v20.0'
            });
        };
    </script>
    <script async defer crossorigin="anonymous" 
            src="https://connect.facebook.net/en_US/sdk.js"></script>

    <script>
        document.getElementById('embeddedSignupBtn')?.addEventListener('click', function() {
            if (typeof FB === 'undefined') {
                alert('⚠️ Facebook SDK not loaded. Please refresh.');
                return;
            }

            FB.login(function(response) {
                if (response.authResponse && response.authResponse.code) {
                    var code = response.authResponse.code;
                    
                    fetch('{{ route("owner.whatsapp-numbers.embedded-signup") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ code: code })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('✅ WhatsApp connected successfully!');
                            window.location.reload();
                        } else {
                            alert('❌ ' + (data.error || 'Failed to connect'));
                        }
                    })
                    .catch(err => {
                        alert('❌ Server error. Please try again.');
                    });
                }
            }, {
                config_id: '{{ env("META_CONFIG_ID") }}',
                response_type: 'code',
                override_default_response_type: true,
                extras: {
                    setup: {},
                    featureType: 'whatsapp_business_messaging',
                }
            });
        });
    </script>
    @endpush
    @endif

</x-layouts.app>