<?php
// app/Http/Controllers/Owner/ProductController.php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $query = Product::where('tenant_id', $tenantId);
        
        // Search
        if ($request->search) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('sku', 'LIKE', "%{$request->search}%");
        }
        
        // Filter by category
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
        
        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $products = $query->latest()->paginate(50);
        $categories = Category::where('tenant_id', $tenantId)->get();
        
        return view('owner.products.index', compact('products', 'categories'));
    }

    /**
     * Show form to create new product.
     */
    public function create()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $categories = Category::where('tenant_id', $tenantId)->get();
        
        return view('owner.products.create', compact('categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);
        
        // Auto generate SKU if not provided
        $sku = $request->sku;
        if (empty($sku)) {
            $sku = $this->generateSku($request->name, $tenantId);
        }
        
        // Handle images
        $images = [];
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $filename);
                $images[] = 'uploads/products/' . $filename;
            }
        }
        
        $product = Product::create([
            'tenant_id' => $tenantId,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_qty' => $request->stock_qty,
            'sku' => $sku,
            'category_id' => $request->category_id,
            'images' => $images,
            'status' => $request->status,
        ]);
        
        return redirect()->route('owner.products.index')
            ->with('success', 'Product created successfully. SKU: ' . $sku);
    }

    /**
     * Generate SKU.
     */
    private function generateSku($productName, $tenantId)
    {
        // Product name ke first 3 letters uppercase
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $productName), 0, 3));
        
        // Random 6 digit number
        $random = strtoupper(substr(uniqid(), -6));
        
        // Tenant specific code
        $tenantCode = 'T' . substr($tenantId, -4);
        
        $sku = $prefix . '-' . $random . '-' . $tenantCode;
        
        // Check if SKU already exists
        $exists = Product::where('sku', $sku)->exists();
        if ($exists) {
            // If exists, add random suffix
            $sku = $sku . '-' . rand(10, 99);
        }
        
        return $sku;
    }

    /**
     * Show a specific product.
     */
    public function show($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $product = Product::where('tenant_id', $tenantId)->findOrFail($id);
        
        return view('owner.products.show', compact('product'));
    }

    /**
     * Show form to edit product.
     */
    public function edit($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $product = Product::where('tenant_id', $tenantId)->findOrFail($id);
        $categories = Category::where('tenant_id', $tenantId)->get();
        
        return view('owner.products.create', compact('product', 'categories'));
    }

    /**
     * Update a product.
     */
    public function update(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $product = Product::where('tenant_id', $tenantId)->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_qty' => 'required|integer|min:0',
            'sku' => 'nullable|string|unique:products,sku,' . $id,
            'category_id' => 'nullable|exists:categories,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);
        
        // Handle images - Public/uploads/products
        $images = $product->images ?? [];
        if ($request->hasFile('images')) {
            $uploadPath = public_path('uploads/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($uploadPath, $filename);
                $images[] = 'uploads/products/' . $filename;
            }
        }
        
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock_qty' => $request->stock_qty,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'images' => $images,
            'status' => $request->status,
        ]);
        
        return redirect()->route('owner.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete a product.
     */
    public function destroy($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $product = Product::where('tenant_id', $tenantId)->findOrFail($id);
        
        // Delete images from folder
        if ($product->images) {
            foreach ($product->images as $image) {
                $imagePath = public_path($image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        
        $product->delete();
        
        return redirect()->route('owner.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle product status.
     */
    public function toggleStatus($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $product = Product::where('tenant_id', $tenantId)->findOrFail($id);
        $product->status = $product->status == 'active' ? 'inactive' : 'active';
        $product->save();
        
        return response()->json([
            'success' => true,
            'status' => $product->status,
            'message' => 'Status updated successfully!'
        ]);
    }

    /**
     * Show import form.
     */
   
    public function importForm()
    {
        return view('owner.products.import');
    }

    /**
     * Import products from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);
        
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        try {
            Excel::import(new ProductsImport($tenantId), $request->file('file'));
            
            return redirect()->route('owner.products.index')
                ->with('success', 'Products imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Export products to Excel/CSV.
     */
    public function export()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        return Excel::download(new ProductsExport($tenantId), 'products_' . date('Y-m-d') . '.xlsx');
    }
}