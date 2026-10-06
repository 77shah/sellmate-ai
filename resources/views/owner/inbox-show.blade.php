<x-layouts.app>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">💬 Conversation</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('owner.inbox') }}">Inbox</a></li>
                                <li class="breadcrumb-item active">Conversation</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="mb-0">{{ $conversation->customer->name ?? 'Unknown' }}</h5>
                                    <small class="text-muted">{{ $conversation->customer->phone ?? 'N/A' }}</small>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $conversation->status == 'open' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($conversation->status) }}
                                    </span>
                                    <span class="badge bg-info">{{ ucfirst($conversation->channel) }}</span>
                                </div>
                            </div>

                            <div class="chat-container" style="max-height:400px; overflow-y:auto;">
                                @foreach($conversation->messages as $message)
                                <div class="d-flex {{ $message->sender_type == 'customer' ? 'justify-content-start' : 'justify-content-end' }} mb-3">
                                    <div class="p-3 rounded {{ $message->sender_type == 'customer' ? 'bg-light' : 'bg-primary text-white' }}" 
                                         style="max-width:70%;">
                                        <p class="mb-0">{{ $message->content }}</p>
                                        <small class="{{ $message->sender_type == 'customer' ? 'text-muted' : 'text-white-50' }}">
                                            {{ $message->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <hr>
                            <form action="#" method="POST" class="mt-3">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Type your reply...">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bx bx-send"></i> Send
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>