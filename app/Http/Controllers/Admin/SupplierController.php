<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query()->select('id', 'name', 'phone', 'email', 'updated_at');

        // Search by name/phone/email
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($x) use ($q) {
                $x->where('name', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $suppliers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:30',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'notes'   => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $supplier = Supplier::create([
            'name'    => trim($request->name),
            'phone'   => $request->phone ? trim($request->phone) : null,
            'email'   => $request->email ? trim($request->email) : null,
            'address' => $request->address ? trim($request->address) : null,
            'notes'   => $request->notes ? trim($request->notes) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully!',
            'supplier' => $supplier,
        ]);
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'phone'   => 'nullable|string|max:30',
            'email'   => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'notes'   => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $supplier->update([
            'name'    => trim($request->name),
            'phone'   => $request->phone ? trim($request->phone) : null,
            'email'   => $request->email ? trim($request->email) : null,
            'address' => $request->address ? trim($request->address) : null,
            'notes'   => $request->notes ? trim($request->notes) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully!',
        ]);
    }

    public function destroy(Supplier $supplier)
    {
        // Optional: block delete if supplier has purchase invoices
        // if ($supplier->purchaseInvoices()->exists()) { ... }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully!',
        ]);
    }
}
