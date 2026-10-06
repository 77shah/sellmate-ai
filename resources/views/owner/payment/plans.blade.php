<x-layouts.app>

    @section('title', 'Subscription Plans')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">

            {{-- Page Title --}}
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">💳 Subscription Plans</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Plans</li>
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

            {{-- Current Subscription Banner --}}
            @if($subscription && $subscription->status == 'active')
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-center justify-content-between flex-wrap">
                            <div>
                                <i class="bx bx-info-circle me-2"></i>
                                <strong>Current Plan:</strong> {{ $subscription->plan_name }}
                                @if($subscription->end_date)
                                    <span class="ms-3 text-muted">
                                        Expires: {{ $subscription->end_date->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('owner.subscription.status') }}" class="text-decoration-none">
                                View Details <i class="bx bx-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Header --}}
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <span class="badge bg-soft-primary text-primary px-3 py-2 mb-3"
                          style="letter-spacing:2px; font-size:11px; border-radius:20px;">
                        PRICING
                    </span>
                    <h2 class="fw-bold mb-2">Pay for what you need.</h2>
                    <p class="text-muted mb-0">No hidden charges. Choose the plan that fits your business.</p>
                </div>
            </div>

            {{-- Currency Toggle --}}
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <div class="d-inline-flex p-1 rounded-pill"
                         style="background:#f4f6f9; border:1px solid #e6e9ef;">
                        <a href="{{ route('owner.plans', ['currency' => 'INR']) }}"
                           class="btn btn-sm rounded-pill px-4 {{ $currency == 'INR' ? 'btn-primary' : 'btn-link text-muted' }}">
                            🇮🇳 INR ₹
                        </a>
                        <a href="{{ route('owner.plans', ['currency' => 'USD']) }}"
                           class="btn btn-sm rounded-pill px-4 {{ $currency == 'USD' ? 'btn-primary' : 'btn-link text-muted' }}">
                            🌍 USD $
                        </a>
                    </div>
                </div>
            </div>

            {{-- Standard Plans Grid --}}
            <div class="row justify-content-center">
                @foreach($plans as $plan)
                    @php
                        // 🔥 Skip custom/enterprise plans (alag card me dikhayenge)
                        if ($plan->is_custom ?? false) continue;
                        
                        $price = $plan->getPriceForCurrency($currency);
                        $symbol = \App\Models\Plan::getCurrencySymbol($currency);
                        $isCurrent = $currentPlan && $currentPlan->id == $plan->id;
                        $isPopular = $plan->is_popular ?? false;
                    @endphp

                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card h-100 border-0 shadow-sm position-relative plan-card
                                    {{ $isPopular ? 'border border-2 border-primary' : '' }}"
                             style="border-radius:16px; transition:all 0.3s;">

                            {{-- Popular Badge --}}
                            @if($isPopular)
                                <span class="badge bg-primary position-absolute"
                                      style="top:-12px; left:50%; transform:translateX(-50%); padding:6px 16px; border-radius:20px; font-size:10px; letter-spacing:1.5px;">
                                    {{ strtoupper($plan->badge ?? 'MOST POPULAR') }}
                                </span>
                            @endif

                            <div class="card-body text-center p-4 pt-5">

                                {{-- Icon --}}
                                <div class="mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-3"
                                         style="width:64px; height:64px; background:rgba(59,130,246,0.1); color:#3b82f6; font-size:28px;">
                                        <i class="bx bx-bolt-circle"></i>
                                    </div>
                                </div>

                                {{-- Plan Name --}}
                                <h6 class="text-uppercase text-muted fw-bold mb-3"
                                    style="letter-spacing:2px; font-size:11px;">
                                    {{ $plan->name }}
                                </h6>

                                {{-- Price --}}
                                <div class="mb-2">
                                    <span class="fw-bold" style="font-size:44px; line-height:1; color:#1f2937;">
                                        @if($price == 0)
                                            FREE
                                        @else
                                            {{ $symbol }}{{ number_format($price, 0) }}
                                        @endif
                                    </span>
                                    <span class="text-muted fs-14">/{{ $plan->billing_period }}</span>
                                </div>

                                {{-- Description --}}
                                <p class="text-muted mb-4"
                                   style="font-size:11px; letter-spacing:1.5px; text-transform:uppercase;">
                                    {{ $plan->description ?? 'Perfect for your business' }}
                                </p>

                                {{-- Features --}}
                                <ul class="list-unstyled text-start mb-4 pt-4"
                                    style="border-top:1px solid #f1f3f7;">
                                    <li class="py-2 fs-14">
                                        <i class="bx bx-check text-primary me-2"></i>
                                        {{ $plan->ai_messages_limit }} AI Messages
                                    </li>
                                    <li class="py-2 fs-14">
                                        <i class="bx bx-check text-primary me-2"></i>
                                        {{ $plan->channels_limit }} Channels
                                    </li>
                                    <li class="py-2 fs-14">
                                        <i class="bx bx-check text-primary me-2"></i>
                                        {{ $plan->team_seats_limit }} Team Seats
                                    </li>
                                    <li class="py-2 fs-14">
                                        <i class="bx bx-check text-primary me-2"></i>
                                        {{ $plan->storage_limit }} MB Storage
                                    </li>
                                    
                                    {{-- 🔥 Knowledge Base Limits --}}
                                    @if($plan->knowledge_max_chunks)
                                        <li class="py-2 fs-14">
                                            <i class="bx bx-check text-primary me-2"></i>
                                            {{ $plan->knowledge_max_chunks }} Knowledge Chunks
                                        </li>
                                    @endif
                                    @if($plan->max_documents)
                                        <li class="py-2 fs-14">
                                            <i class="bx bx-check text-primary me-2"></i>
                                            {{ $plan->max_documents }} Documents
                                        </li>
                                    @endif
                                    
                                    {{-- Features Column --}}
                                    @if($plan->features && is_array($plan->features))
                                        @foreach($plan->features as $feature)
                                            <li class="py-2 fs-14">
                                                <i class="bx bx-check text-primary me-2"></i>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>

                                {{-- CTA Button --}}
                                @if($isCurrent)
                                    <button class="btn btn-outline-primary w-100 py-2 fw-semibold" disabled
                                            style="border-radius:10px; letter-spacing:1px; font-size:12px;">
                                        <i class="bx bx-check me-1"></i> CURRENT PLAN
                                    </button>
                                @elseif($plan->isFree())
                                    <a href="{{ route('owner.subscribe.free', $plan->id) }}"
                                       class="btn btn-primary w-100 py-2 fw-semibold"
                                       style="border-radius:10px; letter-spacing:1px; font-size:12px;">
                                        GET STARTED
                                    </a>
                                @else
                                    <button class="btn btn-primary w-100 py-2 fw-semibold subscribe-btn"
                                            style="border-radius:10px; letter-spacing:1px; font-size:12px;"
                                            data-plan-id="{{ $plan->id }}"
                                            data-plan-name="{{ $plan->name }}"
                                            data-currency="{{ $currency }}">
                                        SUBSCRIBE NOW
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 🔥 ENTERPRISE CUSTOM PLAN CARD --}}
            <div class="row justify-content-center mb-4">
                <div class="col-xl-8 col-md-10">
                    <div class="card border-0 shadow-sm" 
                         style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%); border-radius:16px;">
                        <div class="card-body p-4 text-white">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle me-3"
                                             style="width:56px; height:56px; background: linear-gradient(135deg, #fbbf24, #f59e0b);">
                                            <i class="bx bx-crown text-white" style="font-size:28px;"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-white mb-0">Enterprise Custom</h3>
                                            <p class="text-white-50 mb-0 small">
                                                For large businesses with unlimited data needs
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-3 mt-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-check-circle text-warning me-2"></i>
                                                <span class="small">Unlimited documents</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-check-circle text-warning me-2"></i>
                                                <span class="small">Unlimited chunks</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-check-circle text-warning me-2"></i>
                                                <span class="small">Priority support 24/7</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-check-circle text-warning me-2"></i>
                                                <span class="small">Custom AI training</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-check-circle text-warning me-2"></i>
                                                <span class="small">Dedicated account manager</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bx bx-check-circle text-warning me-2"></i>
                                                <span class="small">Custom integrations</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
                                    <div class="mb-3">
                                        <h2 class="text-warning mb-0">Contact Us</h2>
                                        <small class="text-white-50">Custom pricing</small>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <a href="mailto:support.sellmateai@gmail.com?subject=Enterprise Plan Inquiry&body=Hi, I want to inquire about Enterprise Plan.%0D%0A%0D%0ABusiness Name:%0D%0ARequired Chunks:%0D%0APhone:%0D%0A" 
                                           class="btn btn-warning fw-bold">
                                            <i class="bx bx-envelope me-1"></i> Request Quote
                                        </a>
                                        <a href="https://wa.me/917355742333?text=Hi, I want to inquire about Enterprise Plan" 
                                           class="btn btn-outline-light btn-sm">
                                            <i class="bx bxl-whatsapp"></i> WhatsApp
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FAQ / Info --}}
            <div class="row justify-content-center mb-4">
                <div class="col-xl-8 col-md-10">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h5 class="mb-3"><i class="bx bx-info-circle text-primary"></i> Need help choosing?</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="bx bx-store text-primary me-2" style="font-size:24px;"></i>
                                        <div>
                                            <h6 class="mb-1">Small Business</h6>
                                            <small class="text-muted">Starter plan — perfect for single outlets</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="bx bx-buildings text-primary me-2" style="font-size:24px;"></i>
                                        <div>
                                            <h6 class="mb-1">Growing Business</h6>
                                            <small class="text-muted">Pro plan — for 2-5 locations</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="bx bx-crown text-warning me-2" style="font-size:24px;"></i>
                                        <div>
                                            <h6 class="mb-1">Large Business</h6>
                                            <small class="text-muted">Business plan — unlimited scale</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex">
                                        <i class="bx bxl-whatsapp text-success me-2" style="font-size:24px;"></i>
                                        <div>
                                            <h6 class="mb-1">Have Questions?</h6>
                                            <small class="text-muted">
                                                <a href="https://wa.me/917355742333" class="text-success">
                                                    Chat with us
                                                </a>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .plan-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08) !important;
        }
        .bg-soft-primary {
            background-color: rgba(59, 130, 246, 0.1) !important;
        }
    </style>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.subscribe-btn').click(function() {
                var planId = $(this).data('plan-id');
                var planName = $(this).data('plan-name');
                var currency = $(this).data('currency');
                var button = $(this);

                button.prop('disabled', true);
                button.html('<span class="spinner-border spinner-border-sm me-1"></span> PROCESSING...');

                $.ajax({
                    url: '{{ route("owner.payment.create.order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        plan_id: planId,
                        currency: currency
                    },
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                            button.prop('disabled', false);
                            button.html('SUBSCRIBE NOW');
                            return;
                        }

                        var options = {
                            key: data.key,
                            amount: data.amount,
                            currency: data.currency,
                            name: 'SellMate AI',
                            description: 'Subscription: ' + planName,
                            order_id: data.order_id,
                            theme: { color: '#3b82f6' },
                            handler: function(response) {
                                $.ajax({
                                    url: '{{ route("owner.payment.verify") }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        razorpay_order_id: response.razorpay_order_id,
                                        razorpay_payment_id: response.razorpay_payment_id,
                                        razorpay_signature: response.razorpay_signature,
                                        payment_method: 'card'
                                    },
                                    success: function(verifyResponse) {
                                        if (verifyResponse.success) {
                                            alert('✅ Payment successful! Your subscription is active.');
                                            window.location.reload();
                                        } else {
                                            alert('❌ ' + (verifyResponse.error || 'Payment verification failed'));
                                            button.prop('disabled', false);
                                            button.html('SUBSCRIBE NOW');
                                        }
                                    }
                                });
                            },
                            modal: {
                                ondismiss: function() {
                                    button.prop('disabled', false);
                                    button.html('SUBSCRIBE NOW');
                                }
                            }
                        };
                        var rzp = new Razorpay(options);
                        rzp.open();
                    },
                    error: function() {
                        alert('❌ Failed to create order. Please try again.');
                        button.prop('disabled', false);
                        button.html('SUBSCRIBE NOW');
                    }
                });
            });
        });
    </script>
    @endpush

</x-layouts.app>