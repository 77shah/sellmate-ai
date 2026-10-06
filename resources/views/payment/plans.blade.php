{{-- resources/views/payment/plans.blade.php --}}
<x-layouts.app>

    @section('title', 'Subscription Plans')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
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

            <!-- Current Subscription Info -->
            @if($subscription && $subscription->status == 'active')
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle me-2"></i>
                            <strong>Current Plan:</strong> {{ $subscription->plan_name }}
                            @if($subscription->end_date)
                                <span class="ms-3">Expires: {{ $subscription->end_date->format('d M Y') }}</span>
                            @endif
                            <a href="{{ route('payment.status') }}" class="float-end text-decoration-none">
                                View Details <i class="bx bx-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                @foreach($plans as $plan)
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card h-100 {{ $currentPlan && $currentPlan->id == $plan->id ? 'border-primary shadow-lg' : '' }}">
                            <div class="card-body text-center">
                                @if($plan->price == 0)
                                    <span class="badge bg-primary position-absolute top-0 end-0 m-3">FREE</span>
                                @endif

                                <h5 class="card-title">{{ $plan->name }}</h5>
                                <h2 class="mt-3">
                                    @if($plan->price == 0)
                                        Free
                                    @else
                                        ₹{{ number_format($plan->price, 2) }}
                                    @endif
                                    <small class="text-muted font-size-14">/{{ $plan->billing_period }}</small>
                                </h2>

                                <ul class="list-unstyled text-start mt-4">
                                    <li class="py-1"><i class="bx bx-check-circle text-success me-2"></i> {{ $plan->ai_messages_limit }} AI Messages</li>
                                    <li class="py-1"><i class="bx bx-check-circle text-success me-2"></i> {{ $plan->channels_limit }} Channels</li>
                                    <li class="py-1"><i class="bx bx-check-circle text-success me-2"></i> {{ $plan->team_seats_limit }} Team Seats</li>
                                    <li class="py-1"><i class="bx bx-check-circle text-success me-2"></i> {{ $plan->storage_limit }} MB Storage</li>
                                    @if($plan->features)
                                        @foreach($plan->features as $feature)
                                            <li class="py-1"><i class="bx bx-check-circle text-success me-2"></i> {{ $feature }}</li>
                                        @endforeach
                                    @endif
                                </ul>

                                @if($currentPlan && $currentPlan->id == $plan->id)
                                    <button class="btn btn-success w-100" disabled>
                                        <i class="bx bx-check me-1"></i> Current Plan
                                    </button>
                                @elseif($plan->price == 0)
                                    <a href="{{ route('payment.subscribe.free', $plan->id) }}" class="btn btn-primary w-100">
                                        <i class="bx bx-check me-1"></i> Get Started
                                    </a>
                                @else
                                    <button class="btn btn-primary w-100 subscribe-btn" 
                                            data-plan-id="{{ $plan->id }}"
                                            data-plan-name="{{ $plan->name }}"
                                            data-plan-price="{{ $plan->price }}">
                                        <i class="bx bx-credit-card me-1"></i> Subscribe Now
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Razorpay Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.subscribe-btn').click(function() {
                var button = $(this);
                var planId = button.data('plan-id');
                var planName = button.data('plan-name');
                var planPrice = button.data('plan-price');

                button.prop('disabled', true);
                button.html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');

                $.ajax({
                    url: '{{ route("payment.create.order") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        plan_id: planId
                    },
                    success: function(data) {
                        if (data.error) {
                            alert(data.error);
                            button.prop('disabled', false);
                            button.html('<i class="bx bx-credit-card me-1"></i> Subscribe Now');
                            return;
                        }

                        var options = {
                            key: data.key,
                            amount: data.amount,
                            currency: data.currency,
                            name: 'SellMate AI',
                            description: 'Subscription: ' + planName,
                            order_id: data.order_id,
                            handler: function(response) {
                                // Verify payment
                                $.ajax({
                                    url: '{{ route("payment.verify") }}',
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
                                            alert('❌ Payment verification failed: ' + verifyResponse.error);
                                        }
                                    },
                                    error: function(xhr) {
                                        alert('❌ Payment verification failed. Please contact support.');
                                    }
                                });
                            },
                            modal: {
                                ondismiss: function() {
                                    button.prop('disabled', false);
                                    button.html('<i class="bx bx-credit-card me-1"></i> Subscribe Now');
                                }
                            }
                        };

                        var rzp = new Razorpay(options);
                        rzp.open();
                    },
                    error: function(xhr) {
                        alert('Failed to create order. Please try again.');
                        button.prop('disabled', false);
                        button.html('<i class="bx bx-credit-card me-1"></i> Subscribe Now');
                    }
                });
            });
        });
    </script>
    @endpush

</x-layouts.app>