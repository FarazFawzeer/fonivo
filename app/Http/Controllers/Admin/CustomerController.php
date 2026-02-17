<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query()->select('id', 'name', 'phone', 'email', 'updated_at');

        // Search by name/phone/email
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($x) use ($q) {
                $x->where('name', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
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

        $customer = Customer::create([
            'name'    => trim($request->name),
            'phone'   => $request->phone ? trim($request->phone) : null,
            'email'   => $request->email ? trim($request->email) : null,
            'address' => $request->address ? trim($request->address) : null,
            'notes'   => $request->notes ? trim($request->notes) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully!',
            'customer' => $customer,
        ]);
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
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

        $customer->update([
            'name'    => trim($request->name),
            'phone'   => $request->phone ? trim($request->phone) : null,
            'email'   => $request->email ? trim($request->email) : null,
            'address' => $request->address ? trim($request->address) : null,
            'notes'   => $request->notes ? trim($request->notes) : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully!',
        ]);
    }

    public function destroy(Customer $customer)
    {
        // Optional: block delete if customer has sales invoices
        // if ($customer->salesInvoices()->exists()) { ... }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully!',
        ]);
    }
}
