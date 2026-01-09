<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

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
            'product_type'  => 'required|in:phone,bike',
            'name'          => 'required|string|max:255',
            'owner_name'    => 'required|string|max:255',
            'owner_contact' => 'required|string|max:255',
            'purchase_date' => 'required|date',
            'cost_price'    => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_status'  => 'required|in:available,sold,out_of_stock',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // max 5MB each
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()->all()
                ]);
            }
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();

        // Handle images
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public'); // stored in storage/app/public/products
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths; // store as JSON in DB
        }

        // Create product
        $product = Product::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => $product
            ]);
        }

        return redirect()->route('admin.products.create')->with('success', 'Product created successfully!');
    }


    // Optional: product listing
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }
}
