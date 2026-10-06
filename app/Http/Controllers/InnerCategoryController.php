<?php
// app/Http/Controllers/InnerCategoryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\InnerCategory;
use Illuminate\Support\Facades\File;

class InnerCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('name')->get();
        $innerCategories = InnerCategory::with('subCategory.category')->orderBy('id', 'desc')->get();
        return view('category.innercategory', compact('categories', 'innerCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $innerCategory = new InnerCategory();
        $innerCategory->name = $request->name;
        $innerCategory->sub_category_id = $request->sub_category_id;
        $innerCategory->description = $request->description;
        
        // Handle Image Upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_innercategory.' . $image->getClientOriginalExtension();
            $image->move(public_path('innercategory_images'), $imageName);
            $innerCategory->image = 'innercategory_images/' . $imageName;
        }
        
        $innerCategory->save();

        return back()->with('success', 'Inner category added successfully!');
    }

    public function update(Request $request, $id)
    {
        $innerCategory = InnerCategory::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $innerCategory->name = $request->name;
        $innerCategory->sub_category_id = $request->sub_category_id;
        $innerCategory->description = $request->description;
        
        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($innerCategory->image && File::exists(public_path($innerCategory->image))) {
                File::delete(public_path($innerCategory->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_innercategory.' . $image->getClientOriginalExtension();
            $image->move(public_path('innercategory_images'), $imageName);
            $innerCategory->image = 'innercategory_images/' . $imageName;
        }
        
        $innerCategory->save();

        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $innerCategory = InnerCategory::findOrFail($id);
        $innerCategory->status = !$innerCategory->status;
        $innerCategory->save();

        return response()->json(['success' => true, 'status' => $innerCategory->status]);
    }

    public function destroy($id)
    {
        $innerCategory = InnerCategory::findOrFail($id);
        
        // Delete image
        if ($innerCategory->image && File::exists(public_path($innerCategory->image))) {
            File::delete(public_path($innerCategory->image));
        }
        
        $innerCategory->delete();
        return back()->with('success', 'Inner category deleted successfully!');
    }

    // Get Sub Categories by Category (AJAX)
    public function getSubCategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)->where('status', 1)->get();
        return response()->json($subCategories);
    }
}