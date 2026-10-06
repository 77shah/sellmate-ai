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
                                <li class="breadcrumb-item active">My Profile</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">My Profile</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="POST" class="row g-3">
                                @csrf

                                <!-- Common Fields -->
                                <div class="col-md-6">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" 
                                           value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="{{ old('email', $user->email) }}" required>
                                </div>

                                <!-- Only Admin Specific Fields -->
                                <div class="col-md-6">
                                    <label>Mobile No</label>
                                    <input type="text" class="form-control" name="mobile_no" 
                                           value="{{ old('mobile_no', $user->mobile_no ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label>WhatsApp No</label>
                                    <input type="text" class="form-control" name="whatsapp_no" 
                                           value="{{ old('whatsapp_no', $user->whatsapp_no ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label>Type</label>
                                    <input type="text" class="form-control" 
                                           value="{{ $user->type ?? 'Admin' }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label>Status</label>
                                    <input type="text" class="form-control" 
                                           value="{{ isset($user->status) ? ucfirst($user->status) : 'Active' }}" readonly>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                    <a href="{{ route('change.password') }}" class="btn btn-warning ms-2">
                                        Change Password
                                    </a>
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