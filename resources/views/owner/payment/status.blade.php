<x-layouts.app>

    @section('title', 'Subscription Status')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📊 Subscription Status</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Subscription</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($subscription)
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Current Subscription</h5>
                                <hr>
                                <table class="table table-bordered">
                                    <tr><th>Plan</th><td><strong>{{ $subscription->plan_name }}</strong></td></tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge {{ $subscription->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr><th>Start Date</th><td>{{ $subscription->start_date ? $subscription->start_date->format('d M Y') : 'N/A' }}</td></tr>
                                    <tr><th>End Date</th><td>{{ $subscription->end_date ? $subscription->end_date->format('d M Y') : 'N/A' }}</td></tr>
                                    <tr><th>Amount</th><td>₹{{ number_format($subscription->amount, 2) }}</td></tr>
                                    <tr><th>Auto Renew</th><td><span class="badge {{ $subscription->auto_renew ? 'bg-success' : 'bg-secondary' }}">{{ $subscription->auto_renew ? 'Enabled' : 'Disabled' }}</span></td></tr>
                                </table>
                                @if($subscription->status == 'active')
                                    <form action="{{ route('owner.payment.cancel', $subscription->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel your subscription?')">
                                            <i class="bx bx-x me-1"></i> Cancel Subscription
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('owner.plans') }}" class="btn btn-primary"><i class="bx bx-arrow-back me-1"></i> Change Plan</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Features</h5>
                                <hr>
                                <ul class="list-unstyled">
                                    @if($subscription->features)
                                        @foreach($subscription->features as $feature)
                                            <li class="py-1"><i class="bx bx-check-circle text-success me-2"></i> {{ $feature }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Payment History</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead><tr><th>Transaction ID</th><th>Amount</th><th>Gateway</th><th>Status</th><th>Date</th></tr></thead>
                                        <tbody>
                                            @forelse($payments as $payment)
                                                <tr>
                                                    <td>{{ $payment->transaction_ref ?? 'N/A' }}</td>
                                                    <td>₹{{ number_format($payment->amount, 2) }}</td>
                                                    <td>{{ ucfirst($payment->gateway) }}</td>
                                                    <td><span class="badge {{ $payment->status == 'completed' ? 'bg-success' : 'bg-warning' }}">{{ ucfirst($payment->status) }}</span></td>
                                                    <td>{{ $payment->created_at ? $payment->created_at->format('d M Y') : 'N/A' }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-center">No payments found</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{ $payments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="bx bx-info-circle me-2"></i>
                    You don't have an active subscription. <a href="{{ route('owner.plans') }}">Subscribe now</a>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>