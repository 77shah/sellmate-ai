<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Settings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item active">Gateway Setting</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="mb-0">Gateway Setting</h4>
                                <!-- ON/OFF Toggle Switch -->
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" 
                                           id="statusToggle" name="status" 
                                           value="1" 
                                           style="width: 50px; height: 25px; cursor: pointer;"
                                           {{ ($gateway->status ?? 0) == 1 ? 'checked' : '' }}
                                           onchange="toggleFields()">
                                    <label class="form-check-label" for="statusToggle">
                                        <span id="statusLabel" class="badge {{ ($gateway->status ?? 0) == 1 ? 'bg-success' : 'bg-danger' }}">
                                            {{ ($gateway->status ?? 0) == 1 ? 'ON' : 'OFF' }}
                                        </span>
                                    </label>
                                </div>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <form action="{{ route('gateway.store') }}" method="POST" class="row g-3">
                                @csrf
                                <!-- Hidden field for status when checkbox is unchecked -->
                                <input type="hidden" name="status" value="0" id="statusHidden">

                                <div class="col-md-6">
                                    <label class="form-label">Payment Company</label>
                                    <input type="text" class="form-control" name="payment_company" 
                                           value="{{ $gateway->payment_company ?? '' }}"
                                           id="payment_company"
                                           {{ ($gateway->status ?? 0) == 0 ? 'disabled' : '' }}>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="{{ $gateway->email ?? '' }}"
                                           id="email"
                                           {{ ($gateway->status ?? 0) == 0 ? 'disabled' : '' }}>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" class="form-control" name="mobile" 
                                           value="{{ $gateway->mobile ?? '' }}"
                                           id="mobile"
                                           {{ ($gateway->status ?? 0) == 0 ? 'disabled' : '' }}>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Merchant ID</label>
                                    <input type="text" class="form-control" name="merchant_id" 
                                           value="{{ $gateway->merchant_id ?? '' }}"
                                           id="merchant_id"
                                           {{ ($gateway->status ?? 0) == 0 ? 'disabled' : '' }}>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Merchant Key</label>
                                    <textarea class="form-control" name="merchant_key" rows="3"
                                              id="merchant_key"
                                              {{ ($gateway->status ?? 0) == 0 ? 'disabled' : '' }}>{{ $gateway->merchant_key ?? '' }}</textarea>
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

    <script>
        function toggleFields() {
            const toggle = document.getElementById('statusToggle');
            const hiddenStatus = document.getElementById('statusHidden');
            const statusLabel = document.getElementById('statusLabel');
            
            // Fields to enable/disable
            const fields = [
                'payment_company',
                'email', 
                'mobile',
                'merchant_id',
                'merchant_key'
            ];
            
            if (toggle.checked) {
                // Enable all fields
                fields.forEach(field => {
                    const element = document.getElementById(field);
                    if (element) element.disabled = false;
                });
                statusLabel.textContent = 'ON';
                statusLabel.className = 'badge bg-success';
                hiddenStatus.value = '1';
            } else {
                // Disable all fields
                fields.forEach(field => {
                    const element = document.getElementById(field);
                    if (element) element.disabled = true;
                });
                statusLabel.textContent = 'OFF';
                statusLabel.className = 'badge bg-danger';
                hiddenStatus.value = '0';
            }
        }
    </script>

    <style>
        .form-switch .form-check-input {
            cursor: pointer;
        }
        
        input:disabled, textarea:disabled {
            background-color: #e9ecef;
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .bg-success {
            background-color: #198754 !important;
        }
        
        .bg-danger {
            background-color: #dc3545 !important;
        }
        
        .badge {
            font-size: 12px;
            padding: 4px 8px;
        }
    </style>

</x-layouts.app>