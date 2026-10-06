<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Location</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('location.country') }}">Location</a></li>
                                <li class="breadcrumb-item active">Country</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Manage Countries</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <!-- Add Country Form -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <form action="{{ route('location.country.store') }}" method="POST" class="row g-3">
                                        @csrf
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="name" placeholder="Country Name" required>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="code" placeholder="Country Code (IN, US)">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary w-100">Add Country</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Countries List -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Country Name</th>
                                            <th>Code</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($countries as $country)
                                        <tr id="country-row-{{ $country->id }}">
                                            <td>{{ $country->id }}</td>
                                            <td>
                                                <span class="country-name-{{ $country->id }}">{{ $country->name }}</span>
                                                <input type="text" class="form-control d-none edit-country-{{ $country->id }}" value="{{ $country->name }}" style="width: 150px;">
                                            </td>
                                            <td>
                                                <span class="country-code-{{ $country->id }}">{{ $country->code }}</span>
                                                <input type="text" class="form-control d-none edit-code-{{ $country->id }}" value="{{ $country->code }}" style="width: 100px;">
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input country-toggle" 
                                                           data-id="{{ $country->id }}"
                                                           {{ $country->status == 1 ? 'checked' : '' }}>
                                                    <span class="badge {{ $country->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $country->status == 1 ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-country" data-id="{{ $country->id }}">
                                                    <i class="bx bx-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-success save-country d-none" data-id="{{ $country->id }}">
                                                    <i class="bx bx-save"></i> Save
                                                </button>
                                                <button class="btn btn-sm btn-secondary cancel-country d-none" data-id="{{ $country->id }}">
                                                    <i class="bx bx-x"></i> Cancel
                                                </button>
                                                <form action="{{ route('location.country.delete', $country->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this country?')">
                                                        <i class="bx bx-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No countries found</td>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Edit Country
            $('.edit-country').click(function() {
                var id = $(this).data('id');
                $('.country-name-' + id).addClass('d-none');
                $('.country-code-' + id).addClass('d-none');
                $('.edit-country-' + id).removeClass('d-none');
                $('.edit-code-' + id).removeClass('d-none');
                $(this).addClass('d-none');
                $('.save-country[data-id="' + id + '"]').removeClass('d-none');
                $('.cancel-country[data-id="' + id + '"]').removeClass('d-none');
            });

            // Cancel Edit
            $('.cancel-country').click(function() {
                var id = $(this).data('id');
                $('.country-name-' + id).removeClass('d-none');
                $('.country-code-' + id).removeClass('d-none');
                $('.edit-country-' + id).addClass('d-none');
                $('.edit-code-' + id).addClass('d-none');
                $('.edit-country[data-id="' + id + '"]').removeClass('d-none');
                $(this).addClass('d-none');
                $('.save-country[data-id="' + id + '"]').addClass('d-none');
            });

            // Save Country
            $('.save-country').click(function() {
                var id = $(this).data('id');
                var name = $('.edit-country-' + id).val();
                var code = $('.edit-code-' + id).val();
                
                $.ajax({
                    url: '/location/country/' + id,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        code: code
                    },
                    success: function(response) {
                        if(response.success) {
                            location.reload();
                        }
                    }
                });
            });

            // Toggle Status
            $('.country-toggle').change(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/location/country/' + id + '/toggle',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'PATCH'
                    },
                    success: function(response) {
                        location.reload();
                    }
                });
            });
        });
    </script>

</x-layouts.app>