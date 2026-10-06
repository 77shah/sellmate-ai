<x-layouts.app>

    @section('title', isset($product) ? 'Edit Product' : 'Add Product')

    @section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ isset($product) ? '✏️ Edit Product' : '➕ Add Product' }}</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{ route('owner.dashboard') }}">Owner</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('owner.products.index') }}">Products</a></li>
                                <li class="breadcrumb-item active">{{ isset($product) ? 'Edit' : 'Add' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ isset($product) ? route('owner.products.update', $product->id) : route('owner.products.store') }}" 
                                  method="POST" enctype="multipart/form-data" id="productForm">
                                @csrf
                                @if(isset($product))
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Product Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   name="name" id="product_name" value="{{ old('name', $product->name ?? '') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">SKU</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                                       name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}" placeholder="Auto-generated">
                                                <button type="button" class="btn btn-secondary" id="generateSkuBtn">
                                                    <i class="bx bx-refresh"></i> Generate
                                                </button>
                                            </div>
                                            <small class="text-muted">Leave empty to auto-generate from product name</small>
                                            @error('sku')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      name="description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Price (₹) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                                   name="price" value="{{ old('price', $product->price ?? '') }}" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Stock Quantity <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('stock_qty') is-invalid @enderror" 
                                                   name="stock_qty" value="{{ old('stock_qty', $product->stock_qty ?? '') }}" required>
                                            @error('stock_qty')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Category</label>
                                            <select class="form-select @error('category_id') is-invalid @enderror" name="category_id">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Product Images</label>
                                            <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                                   name="images[]" multiple accept="image/*">
                                            <small class="text-muted">You can upload multiple images. Max 5.</small>
                                            @error('images.*')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        @if(isset($product) && $product->images && count($product->images) > 0)
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Current Images</label>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($product->images as $image)
                                                        <img src="{{ asset($image) }}" alt="{{ $product->name }}" 
                                                             width="80" height="80" style="object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Status</label>
                                            <select class="form-select @error('status') is-invalid @enderror" name="status">
                                                <option value="active" {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-save me-1"></i> {{ isset($product) ? 'Update Product' : 'Save Product' }}
                                    </button>
                                    <a href="{{ route('owner.products.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
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
            // Auto generate SKU on product name change
            $('#product_name').on('input', function() {
                var name = $(this).val();
                var skuInput = $('#sku');
                if (skuInput.val() === '') {
                    var sku = generateSKU(name);
                    skuInput.val(sku);
                }
            });

            // Generate SKU button
            $('#generateSkuBtn').click(function() {
                var name = $('#product_name').val();
                if (name === '') {
                    alert('Please enter product name first!');
                    return;
                }
                var sku = generateSKU(name);
                $('#sku').val(sku);
            });

            function generateSKU(name) {
                var prefix = name.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();
                var random = Math.random().toString(36).substring(2, 8).toUpperCase();
                var tenantCode = 'T001';
                return prefix + '-' + random + '-' + tenantCode;
            }
        });
    </script>
    @endpush

</x-layouts.app>