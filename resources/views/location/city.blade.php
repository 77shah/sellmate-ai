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
                                <li class="breadcrumb-item active">City</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Manage Cities</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <!-- Add City Form -->
                            <div class="row mb-4">
                                <div class="col-md-10">
                                    <form action="{{ route('location.city.store') }}" method="POST" class="row g-3">
                                        @csrf
                                        <div class="col-md-4">
                                            <select name="country_id" id="countrySelect" class="form-control" required>
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select name="state_id" id="stateSelect" class="form-control" required>
                                                <option value="">First Select Country</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="name" placeholder="City Name" required>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">Add City</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Cities List -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>City Name</th>
                                            <th>State</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($cities as $city)
                                        <tr id="city-row-{{ $city->id }}">
                                            <td>{{ $city->id }}</td>
                                            <td>
                                                <span class="city-name-{{ $city->id }}">{{ $city->name }}</span>
                                                <input type="text" class="form-control d-none edit-city-{{ $city->id }}" value="{{ $city->name }}" style="width: 150px;">
                                            </td>
                                            <td>
                                                <span class="city-state-{{ $city->id }}">{{ $city->state->name }}</span>
                                                <select class="form-control d-none edit-city-state-{{ $city->id }}" style="width: 150px;">
                                                    @foreach($city->state->country->states as $state)
                                                        <option value="{{ $state->id }}" {{ $city->state_id == $state->id ? 'selected' : '' }}>
                                                            {{ $state->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>{{ $city->state->country->name }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input city-toggle" 
                                                           data-id="{{ $city->id }}"
                                                           {{ $city->status == 1 ? 'checked' : '' }}>
                                                    <span class="badge {{ $city->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $city->status == 1 ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-city" data-id="{{ $city->id }}">
                                                    <i class="bx bx-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-success save-city d-none" data-id="{{ $city->id }}">
                                                    <i class="bx bx-save"></i> Save
                                                </button>
                                                <button class="btn btn-sm btn-secondary cancel-city d-none" data-id="{{ $city->id }}">
                                                    <i class="bx bx-x"></i> Cancel
                                                </button>
                                                <form action="{{ route('location.city.delete', $city->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this city?')">
                                                        <i class="bx bx-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No cities found</td>
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
            // Dynamic State on Country Select
            $('#countrySelect').change(function() {
                var countryId = $(this).val();
                if(countryId) {
                    $.ajax({
                        url: '/location/get-states/' + countryId,
                        type: 'GET',
                        success: function(data) {
                            $('#stateSelect').empty();
                            $('#stateSelect').append('<option value="">Select State</option>');
                            $.each(data, function(key, value) {
                                $('#stateSelect').append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#stateSelect').empty();
                    $('#stateSelect').append('<option value="">First Select Country</option>');
                }
            });

            // Edit City
            $('.edit-city').click(function() {
                var id = $(this).data('id');
                $('.city-name-' + id).addClass('d-none');
                $('.city-state-' + id).addClass('d-none');
                $('.edit-city-' + id).removeClass('d-none');
                $('.edit-city-state-' + id).removeClass('d-none');
                $(this).addClass('d-none');
                $('.save-city[data-id="' + id + '"]').removeClass('d-none');
                $('.cancel-city[data-id="' + id + '"]').removeClass('d-none');
            });

            // Cancel Edit
            $('.cancel-city').click(function() {
                var id = $(this).data('id');
                $('.city-name-' + id).removeClass('d-none');
                $('.city-state-' + id).removeClass('d-none');
                $('.edit-city-' + id).addClass('d-none');
                $('.edit-city-state-' + id).addClass('d-none');
                $('.edit-city[data-id="' + id + '"]').removeClass('d-none');
                $(this).addClass('d-none');
                $('.save-city[data-id="' + id + '"]').addClass('d-none');
            });

            // Save City
            $('.save-city').click(function() {
                var id = $(this).data('id');
                var name = $('.edit-city-' + id).val();
                var state_id = $('.edit-city-state-' + id).val();
                
                $.ajax({
                    url: '/location/city/' + id,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        state_id: state_id
                    },
                    success: function(response) {
                        if(response.success) {
                            location.reload();
                        }
                    }
                });
            });

            // Toggle Status
            $('.city-toggle').change(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/location/city/' + id + '/toggle',
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