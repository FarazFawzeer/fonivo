<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PhoneUnit;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhoneUnitController extends Controller
{
    private function phoneCategoryId(): ?int
    {
        // Find Phone category id by name
        $cat = Category::select('id')->whereRaw('LOWER(name) = ?', ['phone'])->first();
        return $cat?->id;
    }

    public function index(Request $request)
    {
        $phoneCategoryId = $this->phoneCategoryId();

        $query = PhoneUnit::query()
            ->with(['product:id,name,brand,model,category_id'])
            ->select(
                'id','product_id','imei1','imei2','condition','battery_health',
                'purchase_cost','expected_sell_price','status','updated_at'
            );

        // Filter: status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter: IMEI search
        if ($request->filled('imei')) {
            $imei = trim($request->imei);
            $query->where(function ($x) use ($imei) {
                $x->where('imei1', 'like', "%{$imei}%")
                  ->orWhere('imei2', 'like', "%{$imei}%");
            });
        }

        // Filter: brand/model (via product)
        if ($request->filled('bm')) {
            $bm = trim($request->bm);
            $query->whereHas('product', function ($p) use ($bm, $phoneCategoryId) {
                if ($phoneCategoryId) {
                    $p->where('category_id', $phoneCategoryId);
                }
                $p->where(function ($x) use ($bm) {
                    $x->where('brand', 'like', "%{$bm}%")
                      ->orWhere('model', 'like', "%{$bm}%")
                      ->orWhere('name', 'like', "%{$bm}%");
                });
            });
        } else {
            // Ensure only phone category units appear (if category exists)
            if ($phoneCategoryId) {
                $query->whereHas('product', fn($p) => $p->where('category_id', $phoneCategoryId));
            }
        }

        $phoneUnits = $query->latest()->paginate(10)->withQueryString();

        return view('admin.phone_units.index', compact('phoneUnits'));
    }

    public function create()
    {
        $phoneCategoryId = $this->phoneCategoryId();

        // show only Phone category products
        $phoneProducts = Product::query()
            ->select('id','name','brand','model','category_id')
            ->when($phoneCategoryId, fn($q) => $q->where('category_id', $phoneCategoryId))
            ->orderBy('name')
            ->get();

        return view('admin.phone_units.create', compact('phoneProducts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'imei1' => 'required|string|max:30|unique:phone_units,imei1',
            'imei2' => 'nullable|string|max:30|unique:phone_units,imei2',
            'condition' => 'nullable|string|max:50',
            'battery_health' => 'nullable|string|max:50',
            'faults' => 'nullable|string|max:2000',
            'included_items' => 'nullable|string|max:2000',
            'warranty_days' => 'nullable|integer|min:0|max:3650',
            'purchase_cost' => 'nullable|numeric|min:0',
            'expected_sell_price' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:available,sold,reserved,returned',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $phoneUnit = PhoneUnit::create([
            'product_id' => $request->product_id,
            'imei1' => trim($request->imei1),
            'imei2' => $request->imei2 ? trim($request->imei2) : null,
            'condition' => $request->condition ? trim($request->condition) : null,
            'battery_health' => $request->battery_health ? trim($request->battery_health) : null,
            'faults' => $request->faults ? trim($request->faults) : null,
            'included_items' => $request->included_items ? trim($request->included_items) : null,
            'warranty_days' => $request->warranty_days ?? 0,
            'purchase_cost' => $request->purchase_cost !== null ? (float)$request->purchase_cost : null,
            'expected_sell_price' => $request->expected_sell_price !== null ? (float)$request->expected_sell_price : null,
            'status' => $request->status ?? 'available', // default
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Phone added to stock successfully!',
            'phone_unit' => $phoneUnit,
        ]);
    }

    public function show(PhoneUnit $phoneUnit)
    {
        $phoneUnit->load(['product:id,name,brand,model']);
        return view('admin.phone_units.show', compact('phoneUnit'));
    }

    // Optional edit/update
    public function edit(PhoneUnit $phoneUnit)
    {
        $phoneUnit->load(['product:id,name,brand,model']);
        $phoneCategoryId = $this->phoneCategoryId();

        $phoneProducts = Product::query()
            ->select('id','name','brand','model','category_id')
            ->when($phoneCategoryId, fn($q) => $q->where('category_id', $phoneCategoryId))
            ->orderBy('name')
            ->get();

        return view('admin.phone_units.edit', compact('phoneUnit', 'phoneProducts'));
    }

    public function update(Request $request, PhoneUnit $phoneUnit)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'imei1' => 'required|string|max:30|unique:phone_units,imei1,' . $phoneUnit->id,
            'imei2' => 'nullable|string|max:30|unique:phone_units,imei2,' . $phoneUnit->id,
            'condition' => 'nullable|string|max:50',
            'battery_health' => 'nullable|string|max:50',
            'faults' => 'nullable|string|max:2000',
            'included_items' => 'nullable|string|max:2000',
            'warranty_days' => 'nullable|integer|min:0|max:3650',
            'purchase_cost' => 'nullable|numeric|min:0',
            'expected_sell_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,sold,reserved,returned',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $phoneUnit->update([
            'product_id' => $request->product_id,
            'imei1' => trim($request->imei1),
            'imei2' => $request->imei2 ? trim($request->imei2) : null,
            'condition' => $request->condition ? trim($request->condition) : null,
            'battery_health' => $request->battery_health ? trim($request->battery_health) : null,
            'faults' => $request->faults ? trim($request->faults) : null,
            'included_items' => $request->included_items ? trim($request->included_items) : null,
            'warranty_days' => $request->warranty_days ?? 0,
            'purchase_cost' => $request->purchase_cost !== null ? (float)$request->purchase_cost : null,
            'expected_sell_price' => $request->expected_sell_price !== null ? (float)$request->expected_sell_price : null,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Phone stock updated successfully!',
        ]);
    }

    // Optional delete
    public function destroy(PhoneUnit $phoneUnit)
    {
        // Optional: prevent delete if already sold
        if ($phoneUnit->status === 'sold') {
            return response()->json([
                'success' => false,
                'message' => 'Sold phones cannot be deleted.',
            ], 403);
        }

        $phoneUnit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Phone removed from stock.',
        ]);
    }
}
