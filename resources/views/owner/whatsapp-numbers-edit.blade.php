<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">✏️ Edit WhatsApp Number</h4>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('owner.whatsapp-numbers.update', $number->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number', $number->phone_number) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone Number ID</label>
                                    <input type="text" class="form-control" name="phone_number_id" value="{{ old('phone_number_id', $number->phone_number_id) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Display Name</label>
                                    <input type="text" class="form-control" name="display_name" value="{{ old('display_name', $number->display_name) }}">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="is_default" id="isDefault" {{ old('is_default', $number->is_default) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="isDefault">Set as Default</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="is_active" id="isActive" {{ old('is_active', $number->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="isActive">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Settings (JSON)</label>
                                    <textarea class="form-control" name="settings" rows="3" style="font-family: monospace;">{{ old('settings', json_encode($number->settings ?? [], JSON_PRETTY_PRINT)) }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i> Update Number
                                </button>
                                <a href="{{ route('owner.whatsapp-numbers') }}" class="btn btn-secondary">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>