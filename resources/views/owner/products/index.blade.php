<x-layouts.app>

    @section('title', 'Products')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">📦 Products</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item active">Products</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="mdi mdi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="mdi mdi-alert-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="mdi mdi-information me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="card-title mb-0">All Products</h4>
                                <div>
                                    <a href="{{ route('owner.products.create') }}" class="btn btn-primary">
                                        <i class="bx bx-plus me-1"></i> Add Product
                                    </a>
                                    <a href="{{ route('owner.products.import') }}" class="btn btn-success">
                                        <i class="bx bx-download me-1"></i> Import
                                    </a>
                                    <a href="{{ route('owner.products.export') }}" class="btn btn-info">
                                        <i class="bx bx-upload me-1"></i> Export
                                    </a>
                                </div>
                            </div>

                            <!-- Search & Filter -->
                            <form method="GET" class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" placeholder="Search products..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" name="category">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="status">
                                        <option value="">All Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bx bx-filter-alt me-1"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('owner.products.index') }}" class="btn btn-secondary w-100">
                                        <i class="bx bx-reset me-1"></i> Reset
                                    </a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $index => $product)
                                        <tr>
                                            <td>{{ $products->firstItem() + $index }}</td>
                                            <td>
                                                @if($product->images && count($product->images) > 0)
                                                    <img src="{{ asset($product->images[0]) }}" alt="{{ $product->name }}" 
                                                        width="50" height="50" style="object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                        style="width: 50px; height: 50px; border-radius: 5px; border: 1px solid #ddd;">
                                                        <i class="bx bx-image bx-lg text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                @if($product->description)
                                                    <br><small class="text-muted">{{ Str::limit($product->description, 40) }}</small>
                                                @endif
                                            </td>
                                            <td><code>{{ $product->sku ?? '-' }}</code></td>
                                            <td><strong>₹{{ number_format($product->price, 2) }}</strong></td>
                                            <td>
                                                <span class="badge {{ $product->stock_qty > 10 ? 'bg-success' : ($product->stock_qty > 0 ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $product->stock_qty }}
                                                </span>
                                            </td>
                                            <td>{{ $product->category ? $product->category->name : '-' }}</td>
                                            <td>
                                                <div class="form-check form-switch d-flex align-items-center gap-2">
                                                    <input type="checkbox" class="form-check-input product-toggle" 
                                                           data-id="{{ $product->id }}"
                                                           {{ $product->status == 'active' ? 'checked' : '' }}>
                                                    <span class="badge {{ $product->status == 'active' ? 'bg-success' : 'bg-danger' }} status-badge-{{ $product->id }}">
                                                        {{ $product->status == 'active' ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('owner.products.show', $product->id) }}" class="btn btn-sm btn-info" title="View">
                                                        <i class="bx bx-show"></i>
                                                    </a>
                                                    <a href="{{ route('owner.products.edit', $product->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="bx bx-edit"></i>
                                                    </a>
                                                    <form action="{{ route('owner.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')" title="Delete">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="bx bx-package bx-lg d-block mb-2" style="font-size: 48px;"></i>
                                                    <p class="mb-0">No products found</p>
                                                    <small>Add your first product or import from Excel.</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    @if($products->total() > 0)
                                        <small class="text-muted">
                                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                                        </small>
                                    @endif
                                </div>
                                <div>
                                    {{ $products->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // ===== TOGGLE PRODUCT STATUS =====
            $('.product-toggle').change(function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var isChecked = checkbox.is(':checked');
                var badge = checkbox.closest('td').find('.status-badge-' + id);
                
                $.ajax({
                    url: '/owner/products/' + id + '/toggle',
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        checkbox.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.status == 'active') {
                                badge.removeClass('bg-danger').addClass('bg-success').text('Active');
                            } else {
                                badge.removeClass('bg-success').addClass('bg-danger').text('Inactive');
                            }
                            
                            // Show toast message
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        checkbox.prop('checked', !isChecked);
                        alert('Something went wrong! Please try again.');
                    },
                    complete: function() {
                        checkbox.prop('disabled', false);
                    }
                });
            });
        });
    </script>
    @endpush

</x-layouts.app>