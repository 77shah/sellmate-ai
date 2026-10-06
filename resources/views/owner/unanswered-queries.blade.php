<x-layouts.app>

    @section('title', 'Unanswered Queries')

    @section('content')
    <style>
        .stat-mini {
            border-radius: 12px;
            padding: 20px;
            border: none;
            transition: all 0.3s;
        }
        .stat-mini:hover { transform: translateY(-3px); }
        .stat-mini.pending { background: linear-gradient(135deg, #fef3c7, #fde68a); }
        .stat-mini.resolved { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
        .stat-mini.total { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
        .stat-mini h4 { font-size: 2rem; font-weight: 800; margin: 0; }

        .query-card {
            border: none;
            border-radius: 16px;
            padding: 24px;
            background: white;
            margin-bottom: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            transition: all 0.3s;
            border-left: 4px solid #f59e0b;
        }
        .query-card.resolved { border-left-color: #10b981; }
        .query-card.ignored { border-left-color: #9ca3af; }
        .query-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,0.08); }

        .chat-bubble {
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 10px;
            max-width: 80%;
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
        }
        .chat-bubble.customer {
            background: #f3f4f6;
            color: #1f2937;
            border-bottom-left-radius: 4px;
        }
        .chat-bubble.ai {
            background: #fef3c7;
            color: #92400e;
            border-bottom-left-radius: 4px;
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }
        .chat-bubble.owner {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border-bottom-right-radius: 4px;
            margin-left: auto;
        }

        .chat-label {
            font-size: 11px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .chat-time {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .chat-images {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .chat-images img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.2s;
        }
        .chat-images img:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .chat-url {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: rgba(255,255,255,0.25);
            border-radius: 8px;
            margin-top: 10px;
            font-size: 13px;
            text-decoration: none;
            color: white;
            word-break: break-all;
            max-width: 100%;
            transition: all 0.2s;
        }
        .chat-url:hover {
            background: rgba(255,255,255,0.4);
            color: white;
        }

        .modal-content { border-radius: 16px; border: none; }
        .modal-header { border-bottom: 1px solid #f1f3f7; padding: 20px 24px; }
        .modal-body { padding: 24px; }
        .modal-footer { border-top: 1px solid #f1f3f7; padding: 16px 24px; }

        .image-upload-area {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f9fafb;
        }
        .image-upload-area:hover {
            border-color: #10b981;
            background: #f0fdf4;
        }
        .image-preview {
            position: relative;
            display: inline-block;
            margin: 8px;
            border-radius: 8px;
            overflow: hidden;
        }
        .image-preview img {
            width: 80px; height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .quick-reply-btn {
            padding: 6px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            background: white;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            margin: 4px;
        }
        .quick-reply-btn:hover {
            background: #10b981;
            color: white;
            border-color: #10b981;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">

            {{-- Page Title --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">❓ Unanswered Queries</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Unanswered Queries</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="mdi mdi-alert-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Stats --}}
            <div class="row mb-4">
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="stat-mini pending">
                        <p class="text-muted mb-1 fw-medium">⏳ Pending</p>
                        <h4 class="text-warning">{{ $stats['pending'] ?? 0 }}</h4>
                        <small class="text-muted">Need your reply</small>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="stat-mini resolved">
                        <p class="text-muted mb-1 fw-medium">✅ Resolved</p>
                        <h4 class="text-success">{{ $stats['resolved'] ?? 0 }}</h4>
                        <small class="text-muted">You replied</small>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6 mb-3">
                    <div class="stat-mini total">
                        <p class="text-muted mb-1 fw-medium">📊 Total</p>
                        <h4 class="text-primary">{{ $stats['total'] ?? 0 }}</h4>
                        <small class="text-muted">All queries</small>
                    </div>
                </div>
            </div>

            {{-- Filter Tabs --}}
            <div class="row mb-3">
                <div class="col-12">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link {{ request('status', 'all') == 'all' ? 'active' : '' }}"
                               href="{{ route('owner.unanswered-queries', ['status' => 'all']) }}">
                                All
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}"
                               href="{{ route('owner.unanswered-queries', ['status' => 'pending']) }}">
                                ⏳ Pending
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'resolved' ? 'active' : '' }}"
                               href="{{ route('owner.unanswered-queries', ['status' => 'resolved']) }}">
                                ✅ Resolved
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('status') == 'ignored' ? 'active' : '' }}"
                               href="{{ route('owner.unanswered-queries', ['status' => 'ignored']) }}">
                                ⏸️ Ignored
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Query Cards --}}
            <div class="row">
                <div class="col-12">
                    @forelse($queries ?? [] as $query)
                        <div class="query-card
                            {{ $query->status == 'resolved' ? 'resolved' : '' }}
                            {{ $query->status == 'ignored' ? 'ignored' : '' }}">

                            {{-- Header --}}
                            <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary bg-soft rounded-circle">
                                            {{ strtoupper(substr($query->customer_display_name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">
                                            {{ $query->customer_name ?? 'Customer' }}
                                        </h6>
                                        <small class="text-muted">
                                            <i class="bx bxl-whatsapp text-success"></i>
                                            {{ $query->customer_number }}
                                        </small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    @if($query->hasImages())
                                        <span class="badge bg-info">
                                            <i class="bx bx-image"></i> {{ $query->image_count }}
                                        </span>
                                    @endif
                                    @if($query->hasUrl())
                                        <span class="badge bg-primary">
                                            <i class="bx bx-link"></i> Link
                                        </span>
                                    @endif

                                    <span class="badge bg-{{ $query->status_color }}">
                                        {{ $query->status_label }}
                                    </span>
                                    <small class="text-muted">{{ $query->created_at->diffForHumans() }}</small>
                                </div>
                            </div>

                            {{-- Chat Thread --}}
                            <div class="mb-3">
                                {{-- Customer Message --}}
                                <div class="mb-3">
                                    <div class="chat-label">💬 Customer Message</div>
                                    <div class="chat-bubble customer">
                                        {{ $query->question }}
                                    </div>
                                    <div class="chat-time">{{ $query->created_at->format('d M Y, h:i A') }}</div>
                                </div>

                                {{-- AI Reply --}}
                                @if($query->ai_reply)
                                <div class="mb-3">
                                    <div class="chat-label">🤖 AI Reply (Auto)</div>
                                    <div class="chat-bubble ai">
                                        {{ $query->ai_reply }}
                                    </div>
                                </div>
                                @endif

                                {{-- 🔥 Owner Reply — WITH IMAGES + URL --}}
                                @if($query->owner_reply)
                                <div class="mb-3">
                                    <div class="chat-label">
                                        👤 Your Reply
                                        @if($query->hasImages() || $query->hasUrl())
                                            <span class="text-success">
                                                (with {{ $query->image_count }} image(s)
                                                @if($query->hasUrl()) + link @endif)
                                            </span>
                                        @endif
                                    </div>
                                    <div class="chat-bubble owner">
                                        {{ $query->owner_reply }}

                                        {{-- 🔥 Attached URL --}}
                                        @if($query->hasUrl())
                                            <div>
                                                <a href="{{ $query->owner_reply_url }}"
                                                   target="_blank"
                                                   class="chat-url">
                                                    <i class="bx bx-link-external"></i>
                                                    {{ \Illuminate\Support\Str::limit($query->owner_reply_url, 50) }}
                                                </a>
                                            </div>
                                        @endif

                                        {{-- 🔥 Attached Images — Public folder se --}}
                                        @if($query->hasImages())
                                            <div class="chat-images">
                                                @foreach($query->owner_reply_images as $imagePath)
                                                    <a href="{{ asset($imagePath) }}"
                                                       target="_blank"
                                                       title="Click to view full image">
                                                        <img src="{{ asset($imagePath) }}"
                                                             alt="Reply image"
                                                             onerror="this.style.border='2px solid red'; this.title='Image not found: {{ $imagePath }}'">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    @if($query->reply_sent_at)
                                        <div class="chat-time text-end">
                                            <i class="bx bx-check-double text-success"></i>
                                            Sent: {{ $query->reply_sent_at->format('d M Y, h:i A') }}
                                        </div>
                                    @endif
                                </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            @if($query->status == 'pending')
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#replyModal{{ $query->id }}">
                                    <i class="bx bx-reply me-1"></i> Reply to Customer
                                </button>
                                <form action="{{ route('owner.unanswered-queries.ignore', $query->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-outline-secondary"
                                            onclick="return confirm('Ignore this query?')">
                                        <i class="bx bx-x me-1"></i> Ignore
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>

                        {{-- Reply Modal --}}
                        @if($query->status == 'pending')
                        <div class="modal fade" id="replyModal{{ $query->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('owner.unanswered-queries.reply', $query->id) }}"
                                          method="POST"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bx bx-reply me-2"></i>
                                                Reply to {{ $query->customer_name ?? $query->customer_number }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">

                                            {{-- Customer Question --}}
                                            <div class="mb-4">
                                                <label class="chat-label">💬 Customer Asked:</label>
                                                <div class="alert alert-light border mb-0">
                                                    {{ $query->question }}
                                                </div>
                                            </div>

                                            {{-- Quick Replies --}}
                                            <div class="mb-3">
                                                <label class="chat-label">⚡ Quick Replies:</label>
                                                <div>
                                                    <button type="button" class="quick-reply-btn"
                                                            onclick="setReply('reply{{ $query->id }}', 'Ji haan, bilkul! 😊')">
                                                        Ji haan bilkul
                                                    </button>
                                                    <button type="button" class="quick-reply-btn"
                                                            onclick="setReply('reply{{ $query->id }}', 'Ye service abhi available nahi hai. Aap call kar sakte hain. 😊')">
                                                        Not available
                                                    </button>
                                                    <button type="button" class="quick-reply-btn"
                                                            onclick="setReply('reply{{ $query->id }}', 'Hamari team aapse jaldi contact karegi. 😊')">
                                                        Team will contact
                                                    </button>
                                                    <button type="button" class="quick-reply-btn"
                                                            onclick="setReply('reply{{ $query->id }}', 'Aap hamare number pe call kar sakte hain: 7355742333 📞')">
                                                        Share number
                                                    </button>
                                                    <button type="button" class="quick-reply-btn"
                                                            onclick="setReply('reply{{ $query->id }}', 'Hamara address: Chishti Nagar, Kanpur – 208015 📍')">
                                                        Share address
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Reply Text --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">
                                                    Your Reply <span class="text-danger">*</span>
                                                </label>
                                                <textarea class="form-control"
                                                          id="reply{{ $query->id }}"
                                                          name="reply"
                                                          rows="4"
                                                          required
                                                          placeholder="Type your reply here..."></textarea>
                                            </div>

                                            {{-- Image Upload --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">
                                                    Attach Images (Optional)
                                                </label>
                                                <div class="image-upload-area"
                                                     onclick="document.getElementById('images{{ $query->id }}').click()">
                                                    <i class="bx bx-image-add" style="font-size:32px; color:#10b981;"></i>
                                                    <p class="mb-0 mt-2 text-muted">
                                                        Click to upload images (QR code, menu, etc.)
                                                    </p>
                                                    <small class="text-muted">Max 5 images, 5MB each</small>
                                                </div>
                                                <input type="file"
                                                       id="images{{ $query->id }}"
                                                       name="images[]"
                                                       accept="image/*"
                                                       multiple
                                                       style="display:none;"
                                                       onchange="previewImages(this, {{ $query->id }})">
                                                <div id="preview{{ $query->id }}" class="mt-2"></div>
                                            </div>

                                            {{-- URL Attachment --}}
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">
                                                    Attach URL (Optional)
                                                </label>
                                                <input type="url"
                                                       class="form-control"
                                                       name="attachment_url"
                                                       placeholder="https://maps.google.com/...">
                                                <small class="text-muted">
                                                    Map link, payment link, website, etc.
                                                </small>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bx bx-send me-1"></i> Send Reply
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                    @empty
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="bx bx-check-circle text-success" style="font-size:80px;"></i>
                                <h4 class="mt-3">No Queries Found</h4>
                                <p class="text-muted">
                                    @if(request('status') == 'pending')
                                        Saare queries resolve ho gaye! 🎉
                                    @else
                                        Koi query nahi aayi abhi.
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Pagination --}}
            @if($queries->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $queries->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        function setReply(fieldId, text) {
            document.getElementById(fieldId).value = text;
        }

        function previewImages(input, queryId) {
            var preview = document.getElementById('preview' + queryId);
            preview.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach(function(file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File "' + file.name + '" is too large. Max 5MB.');
                        return;
                    }

                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var div = document.createElement('div');
                        div.className = 'image-preview';
                        div.innerHTML = '<img src="' + e.target.result + '">';
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }
    </script>
    @endpush

</x-layouts.app>