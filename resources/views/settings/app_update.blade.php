<x-header />
<x-sidebar />

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Settings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                                <li class="breadcrumb-item active">Application Update</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Application Update</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('app.update.store') }}" method="POST" class="row g-3">
                                @csrf

                                <div class="col-md-6">
                                    <label>Android Version</label>
                                    <input type="text" name="android_version" class="form-control" value="{{ old('android_version', $appUpdate->android_version ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label>Android URL</label>
                                    <input type="url" name="android_url" class="form-control" value="{{ old('android_url', $appUpdate->android_url ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label>iOS Version</label>
                                    <input type="text" name="ios_version" class="form-control" value="{{ old('ios_version', $appUpdate->ios_version ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label>iOS URL</label>
                                    <input type="url" name="ios_url" class="form-control" value="{{ old('ios_url', $appUpdate->ios_url ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label>Drive App Version</label>
                                    <input type="text" name="drive_app_version" class="form-control" value="{{ old('drive_app_version', $appUpdate->drive_app_version ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label>App URL</label>
                                    <input type="url" name="app_url" class="form-control" value="{{ old('app_url', $appUpdate->app_url ?? '') }}">
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
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

<x-footer />
