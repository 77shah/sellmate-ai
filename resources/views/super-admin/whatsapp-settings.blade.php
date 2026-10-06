<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📱 WhatsApp Settings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('super-admin.dashboard') }}">Super Admin</a></li>
                                <li class="breadcrumb-item active">WhatsApp Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">WhatsApp API Configuration</h4>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width:200px;">Status</th>
                                        <td>
                                            @php
                                                $statusClass = match($settings['status']) {
                                                    '✅ Connected' => 'success',
                                                    '⚠️ Not Connected' => 'warning',
                                                    '❌ Error: 401' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ $settings['status'] ?? 'Unknown' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Phone Number ID</th>
                                        <td><code>{{ $settings['phone_number_id'] ?? 'Not Set' }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Display Phone Number</th>
                                        <td><strong>{{ $settings['display_phone_number'] ?? 'Not Set' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Access Token</th>
                                        <td>
                                            @if($settings['access_token'])
                                                <code>{{ $settings['access_token'] }}</code>
                                            @else
                                                <span class="text-danger">Not Set</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Webhook URL</th>
                                        <td><code>{{ $settings['webhook_url'] }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Verify Token</th>
                                        <td><code>{{ $settings['verify_token'] }}</code></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="alert alert-info mt-3">
                                <i class="bx bx-info-circle me-2"></i>
                                <strong>How to Configure:</strong>
                                <ol class="mt-2 mb-0">
                                    <li>Go to <a href="https://developers.facebook.com/apps/" target="_blank">Facebook Developers</a></li>
                                    <li>Select your app → WhatsApp → API Setup</li>
                                    <li>Copy <code>Phone Number ID</code> and <code>Access Token</code></li>
                                    <li>Update <code>.env</code> file:</li>
                                </ol>
                                <div class="bg-dark text-light p-2 mt-2 rounded">
                                    <code>
                                        WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id<br>
                                        WHATSAPP_ACCESS_TOKEN=your_access_token<br>
                                        WHATSAPP_DISPLAY_NUMBER=your_phone_number
                                    </code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>