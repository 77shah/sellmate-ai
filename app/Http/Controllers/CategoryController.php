<?php
// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        return view('category.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->description = $request->description;
        
        // Handle Image Upload to Public Folder
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_category.' . $image->getClientOriginalExtension();
            // Directly move to public/category_images folder
            $image->move(public_path('category_images'), $imageName);
            $category->image = 'category_images/' . $imageName;
        }
        
        $category->save();

        return back()->with('success', 'Category added successfully!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $category->name = $request->name;
        $category->description = $request->description;
        
        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image && File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_category.' . $image->getClientOriginalExtension();
            $image->move(public_path('category_images'), $imageName);
            $category->image = 'category_images/' . $imageName;
        }
        
        $category->save();

        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->status = !$category->status;
        $category->save();

        return response()->json(['success' => true, 'status' => $category->status]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Delete image
        if ($category->image && File::exists(public_path($category->image))) {
            File::delete(public_path($category->image));
        }
        
        // Check if has sub categories
        if($category->subCategories()->count() > 0) {
            return back()->with('error', 'Cannot delete category! First delete its sub categories.');
        }
        
        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }
}