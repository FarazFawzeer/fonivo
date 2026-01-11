<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Show the create product form
    public function create()
    {
        return view('products.create');
    }

    // Store product details
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'product_code'   => 'required|string|max:50|unique:products,product_code',
            'product_type'   => 'required|in:phone,bike',
            'name'           => 'required|string|max:255|unique:products,name',
            'owner_name'     => 'required|string|max:255',
            'owner_contact'  => 'required|string|max:255',
            'purchase_date'  => 'required|date',
            'cost_price'     => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_status'   => 'required|in:available,sold,out_of_stock',
            'images.*'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors()->all()
                ]);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Collect validated data
        $data = $request->only([
            'product_code',
            'product_type',
            'name',
            'owner_name',
            'owner_contact',
            'purchase_date',
            'cost_price',
            'selling_price',
            'stock_status',
        ]);

        // Handle images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = json_encode($imagePaths);
        }

        // Save product
        $product = Product::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => $product
            ]);
        }

        return redirect()->route('admin.products.create')
            ->with('success', 'Product created successfully!');
    }



    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

 public function update(Request $request, Product $product)
{
    // Validation
    $validator = Validator::make($request->all(), [
        'product_code'   => 'required|string|max:50|unique:products,product_code,' . $product->id,
        'product_type'   => 'required|in:phone,bike',
        'name'           => 'required|string|max:255|unique:products,name,' . $product->id,
        'owner_name'     => 'required|string|max:255',
        'owner_contact'  => 'required|string|max:255',
        'purchase_date'  => 'required|date',
        'cost_price'     => 'required|numeric|min:0',
        'selling_price'  => 'required|numeric|min:0',
        'stock_status'   => 'required|in:available,sold,out_of_stock',
        'images.*'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => implode('<br>', $validator->errors()->all())
        ]);
    }

    // Collect data except images
    $data = $request->only([
        'product_code',
        'product_type',
        'name',
        'owner_name',
        'owner_contact',
        'purchase_date',
        'cost_price',
        'selling_price',
        'stock_status',
    ]);

    // Handle new images
  // Remove selected images
if ($request->has('remove_images') && is_array($request->remove_images)) {
    $images = $product->images ?? [];
    foreach ($request->remove_images as $index) {
        if (isset($images[$index])) {
            // Delete file from storage
            if (Storage::disk('public')->exists($images[$index])) {
                Storage::disk('public')->delete($images[$index]);
            }
            // Remove from array
            unset($images[$index]);
        }
    }
    // Reindex array and save
    $product->images = array_values($images);
}

if ($request->hasFile('images')) {
    $images = $product->images ?? [];
    foreach ($request->file('images') as $image) {
        $images[] = $image->store('products', 'public');
    }
    $product->images = $images;
}


    // Update product
    $product->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Product updated successfully!'
    ]);
}



    // Show product details
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

   public function index(Request $request)
{
    $query = Product::query();

    // Filters
    if ($request->filled('product_type')) {
        $query->where('product_type', $request->product_type);
    }

    if ($request->filled('stock_status')) {
        $query->where('stock_status', $request->stock_status);
    }

    // Search by name, product_code, or owner_name
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('product_code', 'like', "%{$search}%")
              ->orWhere('owner_name', 'like', "%{$search}%");
        });
    }

    $products = $query->latest()->paginate(10);

    // AJAX request → return table only
    if ($request->ajax()) {
        return view('products.index-table', compact('products'))->render();
    }

    return view('products.index', compact('products'));
}

}
