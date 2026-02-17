<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();

        $query = Product::query()
            ->with(['category:id,name'])
            ->select('id', 'category_id', 'name', 'brand', 'model', 'sku', 'default_cost_price', 'default_sell_price', 'updated_at');

        // Filter: category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter: search (name/brand/model/sku)
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($x) use ($q) {
                $x->where('name', 'like', "%{$q}%")
                  ->orWhere('brand', 'like', "%{$q}%")
                  ->orWhere('model', 'like', "%{$q}%")
                  ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'brand'       => 'nullable|string|max:100',
            'model'       => 'nullable|string|max:100',
            'sku'         => 'nullable|string|max:100|unique:products,sku',
            'default_cost_price' => 'nullable|numeric|min:0',
            'default_sell_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'name'        => trim($request->name),
            'brand'       => $request->brand ? trim($request->brand) : null,
            'model'       => $request->model ? trim($request->model) : null,
            'sku'         => $request->sku ? trim($request->sku) : null,
            'default_cost_price' => $request->default_cost_price !== null ? (float)$request->default_cost_price : null,
            'default_sell_price' => $request->default_sell_price !== null ? (float)$request->default_sell_price : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'product' => $product,
        ]);
    }

    public function edit(Product $product)
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'brand'       => 'nullable|string|max:100',
            'model'       => 'nullable|string|max:100',
            'sku'         => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'default_cost_price' => 'nullable|numeric|min:0',
            'default_sell_price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $product->update([
            'category_id' => $request->category_id,
            'name'        => trim($request->name),
            'brand'       => $request->brand ? trim($request->brand) : null,
            'model'       => $request->model ? trim($request->model) : null,
            'sku'         => $request->sku ? trim($request->sku) : null,
            'default_cost_price' => $request->default_cost_price !== null ? (float)$request->default_cost_price : null,
            'default_sell_price' => $request->default_sell_price !== null ? (float)$request->default_sell_price : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!',
        ]);
    }
}
