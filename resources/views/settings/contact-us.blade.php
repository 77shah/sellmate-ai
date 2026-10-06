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
                                <li class="breadcrumb-item active">Contact Us</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Contact Us</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('contact.update') }}" method="POST" class="row g-3">
                                @csrf

                                <div class="col-md-4">
                                    <label>Mobile No</label>
                                    <input type="text" class="form-control" name="mobile_no" value="{{ $user->mobile_no ?? '' }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label>WhatsApp No</label>
                                    <input type="text" class="form-control" name="whatsapp_no" value="{{ $user->whatsapp_no ?? '' }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ $user->email ?? '' }}" required>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Update Contact</button>
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
