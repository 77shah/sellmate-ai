<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">🤖 AI Chat Settings</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">AI Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">AI Chat Configuration</h4>
                            <p class="text-muted">Customize how your AI assistant behaves with customers.</p>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="mdi mdi-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
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

                            <form action="{{ route('owner.ai-settings.update') }}" method="POST">
                                @csrf

                                <!-- Personality -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Bot Personality <span class="text-danger">*</span></label>
                                            <select class="form-select" name="personality">
                                                <option value="formal" {{ ($settings->personality ?? '') == 'formal' ? 'selected' : '' }}>
                                                    🎩 Formal
                                                </option>
                                                <option value="friendly" {{ ($settings->personality ?? '') == 'friendly' ? 'selected' : '' }}>
                                                    😊 Friendly
                                                </option>
                                                <option value="sales" {{ ($settings->personality ?? '') == 'sales' ? 'selected' : '' }}>
                                                    💰 Sales Focused
                                                </option>
                                            </select>
                                            <small class="text-muted">Choose how your AI talks to customers.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Language <span class="text-danger">*</span></label>
                                            <select class="form-select" name="language">
                                                <option value="english" {{ ($settings->language ?? '') == 'english' ? 'selected' : '' }}>
                                                    🇬🇧 English
                                                </option>
                                                <option value="hindi" {{ ($settings->language ?? '') == 'hindi' ? 'selected' : '' }}>
                                                    🇮🇳 Hindi
                                                </option>
                                                <option value="hinglish" {{ ($settings->language ?? '') == 'hinglish' ? 'selected' : '' }}>
                                                    🇮🇳🇬🇧 Hinglish
                                                </option>
                                            </select>
                                            <small class="text-muted">AI will respond in this language.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Business Hours -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Business Hours</label>
                                            <input type="text" class="form-control" name="business_hours" 
                                                   value="{{ $settings->business_hours ?? '9:00 AM - 6:00 PM' }}"
                                                   placeholder="e.g., 9:00 AM - 6:00 PM">
                                            <small class="text-muted">When human support is available.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Auto Reply</label>
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" name="auto_reply_enabled" 
                                                       id="autoReply" {{ ($settings->auto_reply_enabled ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="autoReply">
                                                    <strong>{{ ($settings->auto_reply_enabled ?? true) ? '✅ Enabled' : '❌ Disabled' }}</strong>
                                                </label>
                                            </div>
                                            <small class="text-muted">Enable auto-reply for incoming messages.</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Escalation Rules -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Escalation Rules</label>
                                    <textarea class="form-control" name="escalation_rules" rows="3"
                                              placeholder="When should AI escalate to human?">{{ $settings->escalation_rules ?? 'Escalate to human when customer is frustrated or asks complex questions.' }}</textarea>
                                    <small class="text-muted">Define when AI should hand over to a human agent.</small>
                                </div>

                                <!-- Custom Responses (Optional) -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Custom Responses (JSON)</label>
                                    <textarea class="form-control" name="custom_responses" rows="2"
                                              placeholder='{"greeting": "Hello! How can I help?", "farewell": "Goodbye!"}'
                                              style="font-family: monospace; font-size: 13px;">{{ $settings->custom_responses ?? '' }}</textarea>
                                    <small class="text-muted">Optional: Custom JSON responses for specific scenarios.</small>
                                </div>

                                <!-- Current Status -->
                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle me-2"></i>
                                    <strong>Current AI Status:</strong>
                                    @if($settings->auto_reply_enabled ?? true)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save me-1"></i> Save Settings
                                </button>
                                <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">
                                    <i class="bx bx-x"></i> Cancel
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>