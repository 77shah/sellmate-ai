<?php
// app/Http/Controllers/SubCategoryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\File;

class SubCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $subCategories = SubCategory::with('category')->orderBy('id', 'desc')->get();
        return view('category.subcategory', compact('categories', 'subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->category_id = $request->category_id;
        $subCategory->description = $request->description;
        
        // Handle Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_subcategory.' . $image->getClientOriginalExtension();
            $image->move(public_path('subcategory_images'), $imageName);
            $subCategory->image = 'subcategory_images/' . $imageName;
        }
        
        $subCategory->save();

        return back()->with('success', 'Sub category added successfully!');
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $subCategory->name = $request->name;
        $subCategory->category_id = $request->category_id;
        $subCategory->description = $request->description;
        
        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($subCategory->image && File::exists(public_path($subCategory->image))) {
                File::delete(public_path($subCategory->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_subcategory.' . $image->getClientOriginalExtension();
            $image->move(public_path('subcategory_images'), $imageName);
            $subCategory->image = 'subcategory_images/' . $imageName;
        }
        
        $subCategory->save();

        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->status = !$subCategory->status;
        $subCategory->save();

        return response()->json(['success' => true, 'status' => $subCategory->status]);
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        
        // Delete image
        if ($subCategory->image && File::exists(public_path($subCategory->image))) {
            File::delete(public_path($subCategory->image));
        }
        
        // Check if has inner categories
        if($subCategory->innerCategories()->count() > 0) {
            return back()->with('error', 'Cannot delete sub category! First delete its inner categories.');
        }
        
        $subCategory->delete();
        return back()->with('success', 'Sub category deleted successfully!');
    }
}