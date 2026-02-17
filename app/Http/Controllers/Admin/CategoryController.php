<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::select('id', 'name', 'is_active', 'updated_at')
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:categories,name',
            'is_active' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $category = Category::create([
            'name' => trim($request->name),
            'is_active' => (bool) $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
            'category' => $category,
        ]);
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'is_active' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $category->update([
            'name' => trim($request->name),
            'is_active' => (bool) $request->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
        ]);
    }

    public function destroy(Category $category)
    {
        // Optional: protect Phone/Accessory from deletion
        if (in_array(strtolower($category->name), ['phone', 'accessory'])) {
            return response()->json([
                'success' => false,
                'message' => 'This category is protected and cannot be deleted.',
            ], 403);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!',
        ]);
    }
}
