<x-layouts.app>

    @section('title', $product->name)

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📦 {{ $product->name }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('owner.products.index') }}">Products</a></li>
                                <li class="breadcrumb-item active">{{ $product->name }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- Images -->
                                <div class="col-md-6">
                                    @if($product->images && count($product->images) > 0)
                                        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-inner">
                                                @foreach($product->images as $index => $image)
                                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                        <img src="{{ asset($image) }}" alt="{{ $product->name }}" 
                                                             class="d-block w-100" style="height: 350px; object-fit: cover; border-radius: 8px;">
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if(count($product->images) > 1)
                                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon"></span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon"></span>
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 350px; border-radius: 8px;">
                                            <i class="bx bx-image bx-lg text-muted" style="font-size: 80px;"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Details -->
                                <div class="col-md-6">
                                    <h2 class="mb-2">{{ $product->name }}</h2>
                                    <p class="text-muted">
                                        <small>SKU: <code>{{ $product->sku ?? 'N/A' }}</code></small>
                                    </p>

                                    <div class="mb-3">
                                        <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }}" style="font-size: 14px;">
                                            {{ $product->status == 'active' ? 'Active' : 'Inactive' }}
                                        </span>
                                        <span class="badge bg-info ms-2" style="font-size: 14px;">
                                            {{ $product->category ? $product->category->name : 'Uncategorized' }}
                                        </span>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-6">
                                            <h3 class="text-primary">₹{{ number_format($product->price, 2) }}</h3>
                                            <small class="text-muted">Price</small>
                                        </div>
                                        <div class="col-6">
                                            <h3>
                                                <span class="badge {{ $product->stock_qty > 10 ? 'bg-success' : ($product->stock_qty > 0 ? 'bg-warning' : 'bg-danger') }}" style="font-size: 24px;">
                                                    {{ $product->stock_qty }}
                                                </span>
                                            </h3>
                                            <small class="text-muted">Stock Quantity</small>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <h6 class="fw-bold">Description</h6>
                                        <p class="text-muted">{{ $product->description ?? 'No description available.' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h6 class="fw-bold">Created At</h6>
                                        <p class="text-muted">{{ $product->created_at ? $product->created_at->format('d M Y, h:i A') : 'N/A' }}</p>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('owner.products.edit', $product->id) }}" class="btn btn-warning">
                                            <i class="bx bx-edit me-1"></i> Edit Product
                                        </a>
                                        <a href="{{ route('owner.products.index') }}" class="btn btn-secondary">
                                            <i class="bx bx-arrow-back me-1"></i> Back to List
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>