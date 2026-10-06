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
                                <li class="breadcrumb-item active">Site Setting</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Site Setting</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('site.setting.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label>Logo</label>
                                    <input type="file" class="form-control" name="logo">
                                    @if(!empty($setting->logo))
                                        <img src="{{ asset('admin_uploads/'.$setting->logo) }}" width="100" class="mt-2">
                                    @endif
                                </div>


                                <div class="mb-3">
                                    <label>Copyright</label>
                                    <input type="text" class="form-control" name="copyright" value="{{ $setting->copyright ?? '' }}">
                                </div>

                                <button type="submit" class="btn btn-primary">Update Setting</button>

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
