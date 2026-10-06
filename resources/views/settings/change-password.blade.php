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
                                <li class="breadcrumb-item active">Change Password</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <div class="card">
                        <div class="card-body p-4">
                            <h4 class="mb-4">Change Password</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
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

                            <form action="{{ route('update.password') }}" method="POST" class="row g-3">
                                @csrf

                                <div class="col-md-12">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="new_password" id="new_password" 
                                               class="form-control" required>
                                        <span class="input-group-text">
                                            <i class="fa fa-eye toggle-password" toggle="#new_password" 
                                               style="cursor: pointer;"></i>
                                        </span>
                                    </div>
                                    @if($errors->has('new_password'))
                                        <small class="text-danger">{{ $errors->first('new_password') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" name="new_password_confirmation" 
                                               id="new_password_confirmation" class="form-control" required>
                                        <span class="input-group-text">
                                            <i class="fa fa-eye toggle-password" 
                                               toggle="#new_password_confirmation" style="cursor: pointer;"></i>
                                        </span>
                                    </div>
                                    @if($errors->has('new_password_confirmation'))
                                        <small class="text-danger">{{ $errors->first('new_password_confirmation') }}</small>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <button type="submit" class="btn btn-primary px-4">Change Password</button>
                                        <a href="{{ route('profile') }}" class="btn btn-secondary">Back to Profile</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<x-footer />

<!-- Password Eye Toggle Script -->
<script>
    document.querySelectorAll('.toggle-password').forEach(function(eyeIcon) {
        eyeIcon.addEventListener('click', function() {
            const target = document.querySelector(this.getAttribute('toggle'));
            const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
            target.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>