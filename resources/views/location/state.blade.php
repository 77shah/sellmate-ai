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
                                <li class="breadcrumb-item active">State</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mb-4">Manage States</h4>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <!-- Add State Form -->
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <form action="{{ route('location.state.store') }}" method="POST" class="row g-3">
                                        @csrf
                                        <div class="col-md-5">
                                            <select name="country_id" class="form-control" required>
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="name" placeholder="State Name" required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary w-100">Add State</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- States List -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>State Name</th>
                                            <th>Country</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($states as $state)
                                        <tr id="state-row-{{ $state->id }}">
                                            <td>{{ $state->id }}</td>
                                            <td>
                                                <span class="state-name-{{ $state->id }}">{{ $state->name }}</span>
                                                <input type="text" class="form-control d-none edit-state-{{ $state->id }}" value="{{ $state->name }}" style="width: 150px;">
                                            </td>
                                            <td>
                                                <span class="state-country-{{ $state->id }}">{{ $state->country->name }}</span>
                                                <select class="form-control d-none edit-state-country-{{ $state->id }}" style="width: 150px;">
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" {{ $state->country_id == $country->id ? 'selected' : '' }}>
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input state-toggle" 
                                                           data-id="{{ $state->id }}"
                                                           {{ $state->status == 1 ? 'checked' : '' }}>
                                                    <span class="badge {{ $state->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $state->status == 1 ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-state" data-id="{{ $state->id }}">
                                                    <i class="bx bx-edit"></i> Edit
                                                </button>
                                                <button class="btn btn-sm btn-success save-state d-none" data-id="{{ $state->id }}">
                                                    <i class="bx bx-save"></i> Save
                                                </button>
                                                <button class="btn btn-sm btn-secondary cancel-state d-none" data-id="{{ $state->id }}">
                                                    <i class="bx bx-x"></i> Cancel
                                                </button>
                                                <form action="{{ route('location.state.delete', $state->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this state?')">
                                                        <i class="bx bx-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No states found</td>
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
            // Edit State
            $('.edit-state').click(function() {
                var id = $(this).data('id');
                $('.state-name-' + id).addClass('d-none');
                $('.state-country-' + id).addClass('d-none');
                $('.edit-state-' + id).removeClass('d-none');
                $('.edit-state-country-' + id).removeClass('d-none');
                $(this).addClass('d-none');
                $('.save-state[data-id="' + id + '"]').removeClass('d-none');
                $('.cancel-state[data-id="' + id + '"]').removeClass('d-none');
            });

            // Cancel Edit
            $('.cancel-state').click(function() {
                var id = $(this).data('id');
                $('.state-name-' + id).removeClass('d-none');
                $('.state-country-' + id).removeClass('d-none');
                $('.edit-state-' + id).addClass('d-none');
                $('.edit-state-country-' + id).addClass('d-none');
                $('.edit-state[data-id="' + id + '"]').removeClass('d-none');
                $(this).addClass('d-none');
                $('.save-state[data-id="' + id + '"]').addClass('d-none');
            });

            // Save State
            $('.save-state').click(function() {
                var id = $(this).data('id');
                var name = $('.edit-state-' + id).val();
                var country_id = $('.edit-state-country-' + id).val();
                
                $.ajax({
                    url: '/location/state/' + id,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        country_id: country_id
                    },
                    success: function(response) {
                        if(response.success) {
                            location.reload();
                        }
                    }
                });
            });

            // Toggle Status
            $('.state-toggle').change(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '/location/state/' + id + '/toggle',
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