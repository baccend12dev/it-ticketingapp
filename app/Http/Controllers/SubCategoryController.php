<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the subcategories.
     */
    public function index()
    {
        $subCategories = SubCategory::with('category')->orderBy('id')->get();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('sub_categories.index', compact('subCategories', 'categories'));
    }

    /**
     * Store a newly created subcategory in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subCategory = SubCategory::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : true,
        ]);

        return redirect()->back()->with('success', "Sub Category '{$subCategory->name}' has been successfully created.");
    }

    /**
     * Update the specified subcategory in storage.
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subCategory->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active') ? (bool)$request->input('is_active') : false,
        ]);

        return redirect()->back()->with('success', "Sub Category '{$subCategory->name}' has been successfully updated.");
    }

    /**
     * Remove the specified subcategory from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        $name = $subCategory->name;
        $subCategory->delete();

        return redirect()->back()->with('success', "Sub Category '{$name}' has been successfully deleted.");
    }
}
