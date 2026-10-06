<?php
// app/Http/Controllers/Owner/CategoryController.php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // ===== INDEX =====
    public function categoryIndex()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $categories = Category::where('tenant_id', $tenantId)->orderBy('id', 'desc')->get();
        return view('owner.category.index', compact('categories'));
    }

    // ===== STORE =====
    public function categoryStore(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,NULL,id,tenant_id,'.$tenantId,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $category = new Category();
        $category->tenant_id = $tenantId;
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_category.' . $image->getClientOriginalExtension();
            $image->move(public_path('category_images'), $imageName);
            $category->image = 'category_images/' . $imageName;
        }
        
        $category->save();

        return back()->with('success', 'Category added successfully!');
    }

    // ===== UPDATE =====
    public function categoryUpdate(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $category = Category::where('tenant_id', $tenantId)->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$id.',id,tenant_id,'.$tenantId,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        
        if ($request->hasFile('image')) {
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_category.' . $image->getClientOriginalExtension();
            $image->move(public_path('category_images'), $imageName);
            $category->image = 'category_images/' . $imageName;
        }
        
        $category->save();

        return redirect()->back()->with('success', 'Category updated successfully!');
    }

    // ===== TOGGLE STATUS =====
    // categoryToggle method me ye change karein

    public function categoryToggle($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $category = Category::where('tenant_id', $tenantId)->findOrFail($id);
        
        // Toggle string values
        if ($category->status === 'active') {
            $category->status = 'inactive';
        } else {
            $category->status = 'active';
        }
        $category->save();
        
        return response()->json([
            'success' => true, 
            'status' => $category->status,
            'message' => 'Status updated successfully!'
        ]);
    }

    // ===== DESTROY =====
    public function categoryDestroy($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $category = Category::where('tenant_id', $tenantId)->findOrFail($id);
        
        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }
        
        if($category->subCategories()->count() > 0) {
            return back()->with('error', 'Cannot delete category! First delete its sub categories.');
        }
        
        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }
}