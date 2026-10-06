<x-layouts.app>
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Settings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Ads Setting</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">AdMob Ads Setting</h4>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="mdi mdi-alert-circle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif                           

                            <form action="{{ route('ads.store') }}" method="POST" class="row g-3">
                                @csrf

                                <!-- Basic Information Card -->
                                <div class="col-12">
                                    <div class="card border shadow-none bg-light">
                                        <div class="card-body">
                                            <h5 class="mb-3"><i class="bx bx-info-circle me-2"></i>Basic Information</h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Package ID</label>
                                                    <input type="text" class="form-control" name="package_id" 
                                                           value="{{ old('package_id', $ads->package_id ?? '') }}" 
                                                           placeholder="com.example.app">
                                                    <small class="text-muted">Application package name</small>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">Email</label>
                                                    <input type="email" class="form-control" name="email" 
                                                           value="{{ old('email', $ads->email ?? '') }}" 
                                                           placeholder="admin@example.com">
                                                    <small class="text-muted">Admin email</small>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-bold">AdMob App ID</label>
                                                    <input type="text" class="form-control" name="admob_app_id" 
                                                           value="{{ old('admob_app_id', $ads->admob_app_id ?? '') }}" 
                                                           placeholder="ca-app-pub-xxxxxxxxxxxxxxxx~yyyyyyyyyy">
                                                    <small class="text-muted">AdMob Application ID</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ========== BANNER AD SECTION ========== -->
                                <div class="col-12 mt-3">
                                    <div class="card border shadow-none">
                                        <div class="card-header bg-transparent">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0 text-success">
                                                    <i class="bx bx-image me-1"></i>Banner Ad
                                                </h5>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" 
                                                           class="form-check-input banner-status-toggle" 
                                                           id="bannerAdStatus"
                                                           name="banner_ad_status_toggle"
                                                           {{ old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') == 'ON' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="bannerAdStatus">
                                                        <span class="badge bg-{{ (old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') == 'ON') ? 'success' : 'secondary' }} banner-status-badge px-3 py-2">
                                                            {{ old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="banner_ad_status" id="banner_ad_status" value="{{ old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') }}">
                                            
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Banner Ad Type</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input banner-type-radio" 
                                                               type="radio" 
                                                               name="banner_ad_type" 
                                                               id="banner_demo" 
                                                               value="Demo" 
                                                               {{ old('banner_ad_type', $ads->banner_ad_type ?? 'Demo') == 'Demo' ? 'checked' : '' }}
                                                               {{ old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="banner_demo">Demo</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input banner-type-radio" 
                                                               type="radio" 
                                                               name="banner_ad_type" 
                                                               id="banner_live" 
                                                               value="Live" 
                                                               {{ old('banner_ad_type', $ads->banner_ad_type ?? 'Demo') == 'Live' ? 'checked' : '' }}
                                                               {{ old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="banner_live">Live</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Banner Demo ID</label>
                                                    <input type="text" class="form-control banner-demo-id" name="banner_demo_id" 
                                                           value="{{ old('banner_demo_id', $ads->banner_demo_id ?? '') }}" 
                                                           placeholder="ca-app-pub-3940256099942544/6300978111"
                                                           {{ old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Demo Ad Unit ID</small>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Banner Live ID</label>
                                                    <input type="text" class="form-control banner-live-id" name="banner_live_id" 
                                                           value="{{ old('banner_live_id', $ads->banner_live_id ?? '') }}" 
                                                           placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy"
                                                           {{ old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Live Ad Unit ID</small>
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <div id="bannerDemoAlert" class="alert alert-info py-1 px-3 mb-0" style="display: {{ (old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') == 'ON' && old('banner_ad_type', $ads->banner_ad_type ?? 'Demo') == 'Demo') ? 'block' : 'none' }};">
                                                        <i class="bx bx-info-circle me-1"></i> Using <strong>Demo</strong> Banner Ad
                                                    </div>
                                                    <div id="bannerLiveAlert" class="alert alert-success py-1 px-3 mb-0" style="display: {{ (old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') == 'ON' && old('banner_ad_type', $ads->banner_ad_type ?? 'Demo') == 'Live') ? 'block' : 'none' }};">
                                                        <i class="bx bx-check-circle me-1"></i> Using <strong>Live</strong> Banner Ad
                                                    </div>
                                                    <div id="bannerOffAlert" class="alert alert-secondary py-1 px-3 mb-0" style="display: {{ old('banner_ad_status', $ads->banner_ad_status ?? 'OFF') == 'OFF' ? 'block' : 'none' }};">
                                                        <i class="bx bx-power-off me-1"></i> Banner Ad is <strong>OFF</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ========== INTERSTITIAL AD SECTION ========== -->
                                <div class="col-12 mt-3">
                                    <div class="card border shadow-none">
                                        <div class="card-header bg-transparent">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0 text-warning">
                                                    <i class="bx bx-window me-1"></i>Interstitial Ad
                                                </h5>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" 
                                                           class="form-check-input interstitial-status-toggle" 
                                                           id="interstitialAdStatus"
                                                           name="interstitial_ad_status_toggle"
                                                           {{ old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') == 'ON' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="interstitialAdStatus">
                                                        <span class="badge bg-{{ (old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') == 'ON') ? 'success' : 'secondary' }} interstitial-status-badge px-3 py-2">
                                                            {{ old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="interstitial_ad_status" id="interstitial_ad_status" value="{{ old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') }}">
                                            
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Interstitial Ad Type</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input interstitial-type-radio" 
                                                               type="radio" 
                                                               name="interstitial_ad_type" 
                                                               id="interstitial_demo" 
                                                               value="Demo" 
                                                               {{ old('interstitial_ad_type', $ads->interstitial_ad_type ?? 'Demo') == 'Demo' ? 'checked' : '' }}
                                                               {{ old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="interstitial_demo">Demo</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input interstitial-type-radio" 
                                                               type="radio" 
                                                               name="interstitial_ad_type" 
                                                               id="interstitial_live" 
                                                               value="Live" 
                                                               {{ old('interstitial_ad_type', $ads->interstitial_ad_type ?? 'Demo') == 'Live' ? 'checked' : '' }}
                                                               {{ old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="interstitial_live">Live</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Interstitial Demo ID</label>
                                                    <input type="text" class="form-control interstitial-demo-id" name="interstitial_demo_id" 
                                                           value="{{ old('interstitial_demo_id', $ads->interstitial_demo_id ?? '') }}" 
                                                           placeholder="ca-app-pub-3940256099942544/1033173712"
                                                           {{ old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Demo Ad Unit ID</small>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Interstitial Live ID</label>
                                                    <input type="text" class="form-control interstitial-live-id" name="interstitial_live_id" 
                                                           value="{{ old('interstitial_live_id', $ads->interstitial_live_id ?? '') }}" 
                                                           placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/zzzzzzzzzz"
                                                           {{ old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Live Ad Unit ID</small>
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <div id="interstitialDemoAlert" class="alert alert-info py-1 px-3 mb-0" style="display: {{ (old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') == 'ON' && old('interstitial_ad_type', $ads->interstitial_ad_type ?? 'Demo') == 'Demo') ? 'block' : 'none' }};">
                                                        <i class="bx bx-info-circle me-1"></i> Using <strong>Demo</strong> Interstitial Ad
                                                    </div>
                                                    <div id="interstitialLiveAlert" class="alert alert-success py-1 px-3 mb-0" style="display: {{ (old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') == 'ON' && old('interstitial_ad_type', $ads->interstitial_ad_type ?? 'Demo') == 'Live') ? 'block' : 'none' }};">
                                                        <i class="bx bx-check-circle me-1"></i> Using <strong>Live</strong> Interstitial Ad
                                                    </div>
                                                    <div id="interstitialOffAlert" class="alert alert-secondary py-1 px-3 mb-0" style="display: {{ old('interstitial_ad_status', $ads->interstitial_ad_status ?? 'OFF') == 'OFF' ? 'block' : 'none' }};">
                                                        <i class="bx bx-power-off me-1"></i> Interstitial Ad is <strong>OFF</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ========== REWARDED AD SECTION ========== -->
                                <div class="col-12 mt-3">
                                    <div class="card border shadow-none">
                                        <div class="card-header bg-transparent">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0 text-danger">
                                                    <i class="bx bx-gift me-1"></i>Rewarded Ad
                                                </h5>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" 
                                                           class="form-check-input rewarded-status-toggle" 
                                                           id="rewardedAdStatus"
                                                           name="rewarded_ad_status_toggle"
                                                           {{ old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') == 'ON' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="rewardedAdStatus">
                                                        <span class="badge bg-{{ (old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') == 'ON') ? 'success' : 'secondary' }} rewarded-status-badge px-3 py-2">
                                                            {{ old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="rewarded_ad_status" id="rewarded_ad_status" value="{{ old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') }}">
                                            
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Rewarded Ad Type</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input rewarded-type-radio" 
                                                               type="radio" 
                                                               name="rewarded_ad_type" 
                                                               id="rewarded_demo" 
                                                               value="Demo" 
                                                               {{ old('rewarded_ad_type', $ads->rewarded_ad_type ?? 'Demo') == 'Demo' ? 'checked' : '' }}
                                                               {{ old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="rewarded_demo">Demo</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input rewarded-type-radio" 
                                                               type="radio" 
                                                               name="rewarded_ad_type" 
                                                               id="rewarded_live" 
                                                               value="Live" 
                                                               {{ old('rewarded_ad_type', $ads->rewarded_ad_type ?? 'Demo') == 'Live' ? 'checked' : '' }}
                                                               {{ old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="rewarded_live">Live</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Rewarded Demo ID</label>
                                                    <input type="text" class="form-control rewarded-demo-id" name="rewarded_demo_id" 
                                                           value="{{ old('rewarded_demo_id', $ads->rewarded_demo_id ?? '') }}" 
                                                           placeholder="ca-app-pub-3940256099942544/5224354917"
                                                           {{ old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Demo Ad Unit ID</small>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Rewarded Live ID</label>
                                                    <input type="text" class="form-control rewarded-live-id" name="rewarded_live_id" 
                                                           value="{{ old('rewarded_live_id', $ads->rewarded_live_id ?? '') }}" 
                                                           placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/wwwwwwwwww"
                                                           {{ old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Live Ad Unit ID</small>
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <div id="rewardedDemoAlert" class="alert alert-info py-1 px-3 mb-0" style="display: {{ (old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') == 'ON' && old('rewarded_ad_type', $ads->rewarded_ad_type ?? 'Demo') == 'Demo') ? 'block' : 'none' }};">
                                                        <i class="bx bx-info-circle me-1"></i> Using <strong>Demo</strong> Rewarded Ad
                                                    </div>
                                                    <div id="rewardedLiveAlert" class="alert alert-success py-1 px-3 mb-0" style="display: {{ (old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') == 'ON' && old('rewarded_ad_type', $ads->rewarded_ad_type ?? 'Demo') == 'Live') ? 'block' : 'none' }};">
                                                        <i class="bx bx-check-circle me-1"></i> Using <strong>Live</strong> Rewarded Ad
                                                    </div>
                                                    <div id="rewardedOffAlert" class="alert alert-secondary py-1 px-3 mb-0" style="display: {{ old('rewarded_ad_status', $ads->rewarded_ad_status ?? 'OFF') == 'OFF' ? 'block' : 'none' }};">
                                                        <i class="bx bx-power-off me-1"></i> Rewarded Ad is <strong>OFF</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ========== REWARDED INTERSTITIAL AD SECTION ========== -->
                                <div class="col-12 mt-3">
                                    <div class="card border shadow-none">
                                        <div class="card-header bg-transparent">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0 text-purple">
                                                    <i class="bx bxs-gift me-1"></i>Rewarded Interstitial Ad
                                                </h5>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" 
                                                           class="form-check-input rewarded-interstitial-status-toggle" 
                                                           id="rewardedInterstitialAdStatus"
                                                           name="rewarded_interstitial_ad_status_toggle"
                                                           {{ old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') == 'ON' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="rewardedInterstitialAdStatus">
                                                        <span class="badge bg-{{ (old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') == 'ON') ? 'success' : 'secondary' }} rewarded-interstitial-status-badge px-3 py-2">
                                                            {{ old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="rewarded_interstitial_ad_status" id="rewarded_interstitial_ad_status" value="{{ old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') }}">
                                            
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Rewarded Interstitial Ad Type</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input rewarded-interstitial-type-radio" 
                                                               type="radio" 
                                                               name="rewarded_interstitial_ad_type" 
                                                               id="rewarded_interstitial_demo" 
                                                               value="Demo" 
                                                               {{ old('rewarded_interstitial_ad_type', $ads->rewarded_interstitial_ad_type ?? 'Demo') == 'Demo' ? 'checked' : '' }}
                                                               {{ old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="rewarded_interstitial_demo">Demo</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input rewarded-interstitial-type-radio" 
                                                               type="radio" 
                                                               name="rewarded_interstitial_ad_type" 
                                                               id="rewarded_interstitial_live" 
                                                               value="Live" 
                                                               {{ old('rewarded_interstitial_ad_type', $ads->rewarded_interstitial_ad_type ?? 'Demo') == 'Live' ? 'checked' : '' }}
                                                               {{ old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="rewarded_interstitial_live">Live</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Rewarded Interstitial Demo ID</label>
                                                    <input type="text" class="form-control rewarded-interstitial-demo-id" name="rewarded_interstitial_demo_id" 
                                                           value="{{ old('rewarded_interstitial_demo_id', $ads->rewarded_interstitial_demo_id ?? '') }}" 
                                                           placeholder="ca-app-pub-3940256099942544/6978759866"
                                                           {{ old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Demo Ad Unit ID</small>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Rewarded Interstitial Live ID</label>
                                                    <input type="text" class="form-control rewarded-interstitial-live-id" name="rewarded_interstitial_live_id" 
                                                           value="{{ old('rewarded_interstitial_live_id', $ads->rewarded_interstitial_live_id ?? '') }}" 
                                                           placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy"
                                                           {{ old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Live Ad Unit ID</small>
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <div id="rewardedInterstitialDemoAlert" class="alert alert-info py-1 px-3 mb-0" style="display: {{ (old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') == 'ON' && old('rewarded_interstitial_ad_type', $ads->rewarded_interstitial_ad_type ?? 'Demo') == 'Demo') ? 'block' : 'none' }};">
                                                        <i class="bx bx-info-circle me-1"></i> Using <strong>Demo</strong> Rewarded Interstitial Ad
                                                    </div>
                                                    <div id="rewardedInterstitialLiveAlert" class="alert alert-success py-1 px-3 mb-0" style="display: {{ (old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') == 'ON' && old('rewarded_interstitial_ad_type', $ads->rewarded_interstitial_ad_type ?? 'Demo') == 'Live') ? 'block' : 'none' }};">
                                                        <i class="bx bx-check-circle me-1"></i> Using <strong>Live</strong> Rewarded Interstitial Ad
                                                    </div>
                                                    <div id="rewardedInterstitialOffAlert" class="alert alert-secondary py-1 px-3 mb-0" style="display: {{ old('rewarded_interstitial_ad_status', $ads->rewarded_interstitial_ad_status ?? 'OFF') == 'OFF' ? 'block' : 'none' }};">
                                                        <i class="bx bx-power-off me-1"></i> Rewarded Interstitial Ad is <strong>OFF</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ========== NATIVE ADVANCED AD SECTION ========== -->
                                <div class="col-12 mt-3">
                                    <div class="card border shadow-none">
                                        <div class="card-header bg-transparent">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0 text-primary">
                                                    <i class="bx bx-layout me-1"></i>Native Advanced Ad
                                                </h5>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" 
                                                           class="form-check-input native-status-toggle" 
                                                           id="nativeAdStatus"
                                                           name="native_ad_status_toggle"
                                                           {{ old('native_ad_status', $ads->native_ad_status ?? 'OFF') == 'ON' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="nativeAdStatus">
                                                        <span class="badge bg-{{ (old('native_ad_status', $ads->native_ad_status ?? 'OFF') == 'ON') ? 'success' : 'secondary' }} native-status-badge px-3 py-2">
                                                            {{ old('native_ad_status', $ads->native_ad_status ?? 'OFF') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="native_ad_status" id="native_ad_status" value="{{ old('native_ad_status', $ads->native_ad_status ?? 'OFF') }}">
                                            
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Native Advanced Ad Type</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input native-type-radio" 
                                                               type="radio" 
                                                               name="native_ad_type" 
                                                               id="native_demo" 
                                                               value="Demo" 
                                                               {{ old('native_ad_type', $ads->native_ad_type ?? 'Demo') == 'Demo' ? 'checked' : '' }}
                                                               {{ old('native_ad_status', $ads->native_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="native_demo">Demo</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input native-type-radio" 
                                                               type="radio" 
                                                               name="native_ad_type" 
                                                               id="native_live" 
                                                               value="Live" 
                                                               {{ old('native_ad_type', $ads->native_ad_type ?? 'Demo') == 'Live' ? 'checked' : '' }}
                                                               {{ old('native_ad_status', $ads->native_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="native_live">Live</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Native Advanced Demo ID</label>
                                                    <input type="text" class="form-control native-demo-id" name="native_demo_id" 
                                                           value="{{ old('native_demo_id', $ads->native_demo_id ?? '') }}" 
                                                           placeholder="ca-app-pub-3940256099942544/2247696110"
                                                           {{ old('native_ad_status', $ads->native_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Demo Ad Unit ID</small>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">Native Advanced Live ID</label>
                                                    <input type="text" class="form-control native-live-id" name="native_live_id" 
                                                           value="{{ old('native_live_id', $ads->native_live_id ?? '') }}" 
                                                           placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy"
                                                           {{ old('native_ad_status', $ads->native_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Live Ad Unit ID</small>
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <div id="nativeDemoAlert" class="alert alert-info py-1 px-3 mb-0" style="display: {{ (old('native_ad_status', $ads->native_ad_status ?? 'OFF') == 'ON' && old('native_ad_type', $ads->native_ad_type ?? 'Demo') == 'Demo') ? 'block' : 'none' }};">
                                                        <i class="bx bx-info-circle me-1"></i> Using <strong>Demo</strong> Native Advanced Ad
                                                    </div>
                                                    <div id="nativeLiveAlert" class="alert alert-success py-1 px-3 mb-0" style="display: {{ (old('native_ad_status', $ads->native_ad_status ?? 'OFF') == 'ON' && old('native_ad_type', $ads->native_ad_type ?? 'Demo') == 'Live') ? 'block' : 'none' }};">
                                                        <i class="bx bx-check-circle me-1"></i> Using <strong>Live</strong> Native Advanced Ad
                                                    </div>
                                                    <div id="nativeOffAlert" class="alert alert-secondary py-1 px-3 mb-0" style="display: {{ old('native_ad_status', $ads->native_ad_status ?? 'OFF') == 'OFF' ? 'block' : 'none' }};">
                                                        <i class="bx bx-power-off me-1"></i> Native Advanced Ad is <strong>OFF</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ========== APP OPEN AD SECTION ========== -->
                                <div class="col-12 mt-3">
                                    <div class="card border shadow-none">
                                        <div class="card-header bg-transparent">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0 text-info">
                                                    <i class="bx bx-mobile-alt me-1"></i>App Open Ad
                                                </h5>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" 
                                                           class="form-check-input app-open-status-toggle" 
                                                           id="appOpenAdStatus"
                                                           name="app_open_ad_status_toggle"
                                                           {{ old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') == 'ON' ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="appOpenAdStatus">
                                                        <span class="badge bg-{{ (old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') == 'ON') ? 'success' : 'secondary' }} app-open-status-badge px-3 py-2">
                                                            {{ old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="app_open_ad_status" id="app_open_ad_status" value="{{ old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') }}">
                                            
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">App Open Ad Type</label><br>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input app-open-type-radio" 
                                                               type="radio" 
                                                               name="app_open_ad_type" 
                                                               id="app_open_demo" 
                                                               value="Demo" 
                                                               {{ old('app_open_ad_type', $ads->app_open_ad_type ?? 'Demo') == 'Demo' ? 'checked' : '' }}
                                                               {{ old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="app_open_demo">Demo</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input app-open-type-radio" 
                                                               type="radio" 
                                                               name="app_open_ad_type" 
                                                               id="app_open_live" 
                                                               value="Live" 
                                                               {{ old('app_open_ad_type', $ads->app_open_ad_type ?? 'Demo') == 'Live' ? 'checked' : '' }}
                                                               {{ old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                        <label class="form-check-label" for="app_open_live">Live</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">App Open Demo ID</label>
                                                    <input type="text" class="form-control app-open-demo-id" name="app_open_demo_id" 
                                                           value="{{ old('app_open_demo_id', $ads->app_open_demo_id ?? '') }}" 
                                                           placeholder="ca-app-pub-3940256099942544/3419835294"
                                                           {{ old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Demo Ad Unit ID</small>
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold">App Open Live ID</label>
                                                    <input type="text" class="form-control app-open-live-id" name="app_open_live_id" 
                                                           value="{{ old('app_open_live_id', $ads->app_open_live_id ?? '') }}" 
                                                           placeholder="ca-app-pub-xxxxxxxxxxxxxxxx/yyyyyyyyyy"
                                                           {{ old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') == 'OFF' ? 'disabled' : '' }}>
                                                    <small class="text-muted">Live Ad Unit ID</small>
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <div id="appOpenDemoAlert" class="alert alert-info py-1 px-3 mb-0" style="display: {{ (old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') == 'ON' && old('app_open_ad_type', $ads->app_open_ad_type ?? 'Demo') == 'Demo') ? 'block' : 'none' }};">
                                                        <i class="bx bx-info-circle me-1"></i> Using <strong>Demo</strong> App Open Ad
                                                    </div>
                                                    <div id="appOpenLiveAlert" class="alert alert-success py-1 px-3 mb-0" style="display: {{ (old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') == 'ON' && old('app_open_ad_type', $ads->app_open_ad_type ?? 'Demo') == 'Live') ? 'block' : 'none' }};">
                                                        <i class="bx bx-check-circle me-1"></i> Using <strong>Live</strong> App Open Ad
                                                    </div>
                                                    <div id="appOpenOffAlert" class="alert alert-secondary py-1 px-3 mb-0" style="display: {{ old('app_open_ad_status', $ads->app_open_ad_status ?? 'OFF') == 'OFF' ? 'block' : 'none' }};">
                                                        <i class="bx bx-power-off me-1"></i> App Open Ad is <strong>OFF</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-12 mt-4">
                                    <hr>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-secondary me-2 d-none" onclick="resetForm()">
                                            <i class="bx bx-reset me-1"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-save me-1"></i> Save All Settings
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // ===== BANNER AD TOGGLE =====
        $('.banner-status-toggle').change(function() {
            var isChecked = $(this).is(':checked');
            var status = isChecked ? 'ON' : 'OFF';
            
            $('#banner_ad_status').val(status);
            
            // Update badge
            var badge = $(this).closest('.d-flex').find('.banner-status-badge');
            if (status == 'ON') {
                badge.removeClass('bg-secondary').addClass('bg-success').text('ON');
            } else {
                badge.removeClass('bg-success').addClass('bg-secondary').text('OFF');
            }
            
            // Enable/disable fields
            $('.banner-type-radio, .banner-demo-id, .banner-live-id').prop('disabled', status == 'OFF');
            
            // Show/hide alerts
            var adType = $('input[name="banner_ad_type"]:checked').val();
            
            if (status == 'OFF') {
                $('#bannerDemoAlert, #bannerLiveAlert').hide();
                $('#bannerOffAlert').show();
            } else {
                $('#bannerOffAlert').hide();
                if (adType == 'Demo') {
                    $('#bannerDemoAlert').show();
                    $('#bannerLiveAlert').hide();
                } else {
                    $('#bannerDemoAlert').hide();
                    $('#bannerLiveAlert').show();
                }
            }
        });
        
        // Banner Ad Type Change
        $('input[name="banner_ad_type"]').change(function() {
            var adType = $(this).val();
            var status = $('#banner_ad_status').val();
            
            if (status == 'ON') {
                if (adType == 'Demo') {
                    $('#bannerDemoAlert').show();
                    $('#bannerLiveAlert').hide();
                } else {
                    $('#bannerDemoAlert').hide();
                    $('#bannerLiveAlert').show();
                }
            }
        });

        // ===== INTERSTITIAL AD TOGGLE =====
        $('.interstitial-status-toggle').change(function() {
            var isChecked = $(this).is(':checked');
            var status = isChecked ? 'ON' : 'OFF';
            
            $('#interstitial_ad_status').val(status);
            
            // Update badge
            var badge = $(this).closest('.d-flex').find('.interstitial-status-badge');
            if (status == 'ON') {
                badge.removeClass('bg-secondary').addClass('bg-success').text('ON');
            } else {
                badge.removeClass('bg-success').addClass('bg-secondary').text('OFF');
            }
            
            // Enable/disable fields
            $('.interstitial-type-radio, .interstitial-demo-id, .interstitial-live-id').prop('disabled', status == 'OFF');
            
            // Show/hide alerts
            var adType = $('input[name="interstitial_ad_type"]:checked').val();
            
            if (status == 'OFF') {
                $('#interstitialDemoAlert, #interstitialLiveAlert').hide();
                $('#interstitialOffAlert').show();
            } else {
                $('#interstitialOffAlert').hide();
                if (adType == 'Demo') {
                    $('#interstitialDemoAlert').show();
                    $('#interstitialLiveAlert').hide();
                } else {
                    $('#interstitialDemoAlert').hide();
                    $('#interstitialLiveAlert').show();
                }
            }
        });
        
        // Interstitial Ad Type Change
        $('input[name="interstitial_ad_type"]').change(function() {
            var adType = $(this).val();
            var status = $('#interstitial_ad_status').val();
            
            if (status == 'ON') {
                if (adType == 'Demo') {
                    $('#interstitialDemoAlert').show();
                    $('#interstitialLiveAlert').hide();
                } else {
                    $('#interstitialDemoAlert').hide();
                    $('#interstitialLiveAlert').show();
                }
            }
        });

        // ===== REWARDED AD TOGGLE =====
        $('.rewarded-status-toggle').change(function() {
            var isChecked = $(this).is(':checked');
            var status = isChecked ? 'ON' : 'OFF';
            
            $('#rewarded_ad_status').val(status);
            
            // Update badge
            var badge = $(this).closest('.d-flex').find('.rewarded-status-badge');
            if (status == 'ON') {
                badge.removeClass('bg-secondary').addClass('bg-success').text('ON');
            } else {
                badge.removeClass('bg-success').addClass('bg-secondary').text('OFF');
            }
            
            // Enable/disable fields
            $('.rewarded-type-radio, .rewarded-demo-id, .rewarded-live-id').prop('disabled', status == 'OFF');
            
            // Show/hide alerts
            var adType = $('input[name="rewarded_ad_type"]:checked').val();
            
            if (status == 'OFF') {
                $('#rewardedDemoAlert, #rewardedLiveAlert').hide();
                $('#rewardedOffAlert').show();
            } else {
                $('#rewardedOffAlert').hide();
                if (adType == 'Demo') {
                    $('#rewardedDemoAlert').show();
                    $('#rewardedLiveAlert').hide();
                } else {
                    $('#rewardedDemoAlert').hide();
                    $('#rewardedLiveAlert').show();
                }
            }
        });
        
        // Rewarded Ad Type Change
        $('input[name="rewarded_ad_type"]').change(function() {
            var adType = $(this).val();
            var status = $('#rewarded_ad_status').val();
            
            if (status == 'ON') {
                if (adType == 'Demo') {
                    $('#rewardedDemoAlert').show();
                    $('#rewardedLiveAlert').hide();
                } else {
                    $('#rewardedDemoAlert').hide();
                    $('#rewardedLiveAlert').show();
                }
            }
        });

        // ===== REWARDED INTERSTITIAL AD TOGGLE =====
        $('.rewarded-interstitial-status-toggle').change(function() {
            var isChecked = $(this).is(':checked');
            var status = isChecked ? 'ON' : 'OFF';
            
            $('#rewarded_interstitial_ad_status').val(status);
            
            // Update badge
            var badge = $(this).closest('.d-flex').find('.rewarded-interstitial-status-badge');
            if (status == 'ON') {
                badge.removeClass('bg-secondary').addClass('bg-success').text('ON');
            } else {
                badge.removeClass('bg-success').addClass('bg-secondary').text('OFF');
            }
            
            // Enable/disable fields
            $('.rewarded-interstitial-type-radio, .rewarded-interstitial-demo-id, .rewarded-interstitial-live-id').prop('disabled', status == 'OFF');
            
            // Show/hide alerts
            var adType = $('input[name="rewarded_interstitial_ad_type"]:checked').val();
            
            if (status == 'OFF') {
                $('#rewardedInterstitialDemoAlert, #rewardedInterstitialLiveAlert').hide();
                $('#rewardedInterstitialOffAlert').show();
            } else {
                $('#rewardedInterstitialOffAlert').hide();
                if (adType == 'Demo') {
                    $('#rewardedInterstitialDemoAlert').show();
                    $('#rewardedInterstitialLiveAlert').hide();
                } else {
                    $('#rewardedInterstitialDemoAlert').hide();
                    $('#rewardedInterstitialLiveAlert').show();
                }
            }
        });
        
        // Rewarded Interstitial Ad Type Change
        $('input[name="rewarded_interstitial_ad_type"]').change(function() {
            var adType = $(this).val();
            var status = $('#rewarded_interstitial_ad_status').val();
            
            if (status == 'ON') {
                if (adType == 'Demo') {
                    $('#rewardedInterstitialDemoAlert').show();
                    $('#rewardedInterstitialLiveAlert').hide();
                } else {
                    $('#rewardedInterstitialDemoAlert').hide();
                    $('#rewardedInterstitialLiveAlert').show();
                }
            }
        });

        // ===== NATIVE ADVANCED AD TOGGLE =====
        $('.native-status-toggle').change(function() {
            var isChecked = $(this).is(':checked');
            var status = isChecked ? 'ON' : 'OFF';
            
            $('#native_ad_status').val(status);
            
            // Update badge
            var badge = $(this).closest('.d-flex').find('.native-status-badge');
            if (status == 'ON') {
                badge.removeClass('bg-secondary').addClass('bg-success').text('ON');
            } else {
                badge.removeClass('bg-success').addClass('bg-secondary').text('OFF');
            }
            
            // Enable/disable fields
            $('.native-type-radio, .native-demo-id, .native-live-id').prop('disabled', status == 'OFF');
            
            // Show/hide alerts
            var adType = $('input[name="native_ad_type"]:checked').val();
            
            if (status == 'OFF') {
                $('#nativeDemoAlert, #nativeLiveAlert').hide();
                $('#nativeOffAlert').show();
            } else {
                $('#nativeOffAlert').hide();
                if (adType == 'Demo') {
                    $('#nativeDemoAlert').show();
                    $('#nativeLiveAlert').hide();
                } else {
                    $('#nativeDemoAlert').hide();
                    $('#nativeLiveAlert').show();
                }
            }
        });
        
        // Native Ad Type Change
        $('input[name="native_ad_type"]').change(function() {
            var adType = $(this).val();
            var status = $('#native_ad_status').val();
            
            if (status == 'ON') {
                if (adType == 'Demo') {
                    $('#nativeDemoAlert').show();
                    $('#nativeLiveAlert').hide();
                } else {
                    $('#nativeDemoAlert').hide();
                    $('#nativeLiveAlert').show();
                }
            }
        });

        // ===== APP OPEN AD TOGGLE =====
        $('.app-open-status-toggle').change(function() {
            var isChecked = $(this).is(':checked');
            var status = isChecked ? 'ON' : 'OFF';
            
            $('#app_open_ad_status').val(status);
            
            // Update badge
            var badge = $(this).closest('.d-flex').find('.app-open-status-badge');
            if (status == 'ON') {
                badge.removeClass('bg-secondary').addClass('bg-success').text('ON');
            } else {
                badge.removeClass('bg-success').addClass('bg-secondary').text('OFF');
            }
            
            // Enable/disable fields
            $('.app-open-type-radio, .app-open-demo-id, .app-open-live-id').prop('disabled', status == 'OFF');
            
            // Show/hide alerts
            var adType = $('input[name="app_open_ad_type"]:checked').val();
            
            if (status == 'OFF') {
                $('#appOpenDemoAlert, #appOpenLiveAlert').hide();
                $('#appOpenOffAlert').show();
            } else {
                $('#appOpenOffAlert').hide();
                if (adType == 'Demo') {
                    $('#appOpenDemoAlert').show();
                    $('#appOpenLiveAlert').hide();
                } else {
                    $('#appOpenDemoAlert').hide();
                    $('#appOpenLiveAlert').show();
                }
            }
        });
        
        // App Open Ad Type Change
        $('input[name="app_open_ad_type"]').change(function() {
            var adType = $(this).val();
            var status = $('#app_open_ad_status').val();
            
            if (status == 'ON') {
                if (adType == 'Demo') {
                    $('#appOpenDemoAlert').show();
                    $('#appOpenLiveAlert').hide();
                } else {
                    $('#appOpenDemoAlert').hide();
                    $('#appOpenLiveAlert').show();
                }
            }
        });

        // Initialize all toggles on page load
        $('.banner-status-toggle').trigger('change');
        $('.interstitial-status-toggle').trigger('change');
        $('.rewarded-status-toggle').trigger('change');
        $('.rewarded-interstitial-status-toggle').trigger('change');
        $('.native-status-toggle').trigger('change');
        $('.app-open-status-toggle').trigger('change');

        // Form validation before submit
        $('form').submit(function(e) {
            var errorMessage = '';
            
            // Validate all ad types
            var adTypes = [
                'banner', 'interstitial', 'rewarded', 
                'rewarded_interstitial', 'native', 'app_open'
            ];
            
            adTypes.forEach(function(type) {
                var status = $('#' + type + '_ad_status').val();
                var adType = $('input[name="' + type + '_ad_type"]:checked').val();
                var demoId = $('input[name="' + type + '_demo_id"]').val().trim();
                var liveId = $('input[name="' + type + '_live_id"]').val().trim();
                
                if (status == 'ON') {
                    if (adType == 'Demo' && !demoId) {
                        errorMessage += '• ' + formatAdType(type) + ' Demo ID is required when status is ON and type is Demo\n';
                    }
                    if (adType == 'Live' && !liveId) {
                        errorMessage += '• ' + formatAdType(type) + ' Live ID is required when status is ON and type is Live\n';
                    }
                }
            });
            
            if (errorMessage) {
                alert('Please fix the following errors:\n\n' + errorMessage);
                e.preventDefault();
                return false;
            }
        });

        // Helper function to format ad type for error messages
        function formatAdType(type) {
            switch(type) {
                case 'rewarded_interstitial': return 'Rewarded Interstitial';
                case 'app_open': return 'App Open';
                default: return type.charAt(0).toUpperCase() + type.slice(1);
            }
        }
    });

    // Reset form function
    function resetForm() {
        if(confirm('Are you sure you want to reset all fields?')) {
            $('form')[0].reset();
            $('.banner-status-toggle').trigger('change');
            $('.interstitial-status-toggle').trigger('change');
            $('.rewarded-status-toggle').trigger('change');
            $('.rewarded-interstitial-status-toggle').trigger('change');
            $('.native-status-toggle').trigger('change');
            $('.app-open-status-toggle').trigger('change');
        }
    }
    </script>

    <!-- Custom CSS -->
    <style>
        /* Form Switch Styles */
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            cursor: pointer;
            margin-right: 0.5rem;
        }
        
        .form-switch .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .form-switch .form-check-input:focus {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%2328a745'/%3e%3c/svg%3e");
        }
        
        .form-switch .form-check-input:checked:focus {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }
        
        /* Badge Styles */
        .badge {
            font-size: 0.75em;
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 0.375rem;
        }
        
        /* Alert Styles */
        .alert {
            border-radius: 0.375rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }
        
        /* Form Label */
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.25rem;
        }
        
        /* Color Classes */
        .bg-success {
            background-color: #28a745 !important;
        }
        
        .bg-warning {
            background-color: #ffc107 !important;
        }
        
        .bg-danger {
            background-color: #dc3545 !important;
        }
        
        .bg-info {
            background-color: #17a2b8 !important;
        }
        
        .bg-secondary {
            background-color: #6c757d !important;
        }
        
        .text-purple {
            color: #6f42c1 !important;
        }
        
        /* Disabled Input */
        input:disabled, .form-check-input:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Card Hover Effect */
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        /* Section Headers */
        .card-header {
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        /* Demo IDs placeholder style */
        input[placeholder*="ca-app-pub"] {
            font-family: monospace;
            font-size: 0.9rem;
        }
    </style>
</x-layouts.app>