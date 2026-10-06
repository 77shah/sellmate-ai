<x-header/>
<x-sidebar/>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Settings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">SMS Setting</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">SMS Setting</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('sms.store') }}" method="POST" class="row g-3">
                                @csrf

                                <div class="col-md-12">
                                    <label class="form-label">Enabled / Disabled</label><br>
                                    <input type="checkbox" id="switch1" switch="none" name="enabled" {{ $sms && $sms->enabled ? 'checked' : '' }}>
                                    <label for="switch1" data-on-label="On" data-off-label="Off"></label>
                                </div>

                                <div class="col-md-4">
                                    <label>Country</label>
                                    <input type="text" class="form-control" name="country" value="{{ $sms->country ?? '' }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Customer ID</label>
                                    <input type="text" class="form-control" name="customer_id" value="{{ $sms->customer_id ?? '' }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ $sms->email ?? '' }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="password" value="{{ $sms->password ?? '' }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Key</label>
                                    <input type="text" class="form-control" name="key" value="{{ $sms->key ?? '' }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Country Code</label>
                                    <input type="text" class="form-control" name="country_code" value="{{ $sms->country_code ?? '' }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Flow Type</label>
                                    <input type="text" class="form-control" name="flow_type" value="{{ $sms->flow_type ?? '' }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Length</label>
                                    <input type="number" class="form-control" name="length" value="{{ $sms->length ?? '' }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Auth Token</label>
                                    <input type="text" class="form-control" name="auth_token" value="{{ $sms->auth_token ?? '' }}">
                                </div>

                                <div class="col-md-6">
                                    <label>Sent URL</label>
                                    <input type="text" class="form-control" name="sent_url" value="{{ $sms->sent_url ?? '' }}">
                                </div>

                                <div class="col-md-6">
                                    <label>Verify URL</label>
                                    <input type="text" class="form-control" name="verify_url" value="{{ $sms->verify_url ?? '' }}">
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Save Settings</button>
                                </div>

                            </form>

                            @if ($errors->any())
                                <div class="alert alert-danger mt-3">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<x-footer/>
