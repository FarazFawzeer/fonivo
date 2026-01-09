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

        // Create product
        $product = Product::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product created successfully!',
                'product' => $product
            ]);
        }

        return redirect()->route('products.create')->with('success', 'Product created successfully!');
    }

    // Optional: product listing
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }
}
