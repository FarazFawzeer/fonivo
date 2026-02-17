<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AccessoryStockController extends Controller
{
    private function accessoryCategoryId(): ?int
    {
        $cat = Category::select('id')->whereRaw('LOWER(name) = ?', ['accessory'])->first();
        return $cat?->id;
    }

    public function index(Request $request)
    {
        $accessoryCategoryId = $this->accessoryCategoryId();

        // Base products query (only accessories)
        $productsQuery = Product::query()
            ->select('id', 'name', 'sku', 'brand', 'model', 'category_id')
            ->when($accessoryCategoryId, fn($q) => $q->where('category_id', $accessoryCategoryId));

        // Search filter (name/sku/brand/model)
        if ($request->filled('q')) {
            $q = trim($request->q);
            $productsQuery->where(function ($x) use ($q) {
                $x->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%")
                    ->orWhere('model', 'like', "%{$q}%");
            });
        }

        // We need stock sums from stock_ledgers grouped by product_id
        // We'll do a left join subquery for performance and clean output.
        $stockSub = StockLedger::query()
            ->select(
                'product_id',
                DB::raw('COALESCE(SUM(qty_in),0) as total_in'),
                DB::raw('COALESCE(SUM(qty_out),0) as total_out')
            )
            ->groupBy('product_id');

        $products = $productsQuery
            ->leftJoinSub($stockSub, 'stk', function ($join) {
                $join->on('products.id', '=', 'stk.product_id');
            })
            ->addSelect(
                DB::raw('COALESCE(stk.total_in,0) as total_in'),
                DB::raw('COALESCE(stk.total_out,0) as total_out'),
                DB::raw('(COALESCE(stk.total_in,0) - COALESCE(stk.total_out,0)) as current_stock')
            )
            ->orderByDesc('products.updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.accessory_stock.index', compact('products'));
    }

    public function show(Product $product)
    {
        // Ensure this is an accessory (optional strict check)
        $accessoryCategoryId = $this->accessoryCategoryId();
        if ($accessoryCategoryId && $product->category_id != $accessoryCategoryId) {
            abort(404);
        }

        $product->loadMissing([]);

        // Get all ledger rows for this product
        $ledgers = StockLedger::query()
            ->where('product_id', $product->id)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Current stock
        $totals = StockLedger::query()
            ->where('product_id', $product->id)
            ->selectRaw('COALESCE(SUM(qty_in),0) as total_in, COALESCE(SUM(qty_out),0) as total_out')
            ->first();

        $currentStock = ((int)$totals->total_in) - ((int)$totals->total_out);

        return view('admin.accessory_stock.show', compact('product', 'ledgers', 'currentStock'));
    }

    public function createAdjustment(Product $product)
    {
        $accessoryCategoryId = $this->accessoryCategoryId();
        if ($accessoryCategoryId && $product->category_id != $accessoryCategoryId) {
            abort(404);
        }

        // Current stock
        $totals = StockLedger::query()
            ->where('product_id', $product->id)
            ->selectRaw('COALESCE(SUM(qty_in),0) as total_in, COALESCE(SUM(qty_out),0) as total_out')
            ->first();

        $currentStock = ((int)$totals->total_in) - ((int)$totals->total_out);

        return view('admin.accessory_stock.adjust', compact('product', 'currentStock'));
    }

    public function storeAdjustment(Request $request, Product $product)
    {
        $accessoryCategoryId = $this->accessoryCategoryId();
        if ($accessoryCategoryId && $product->category_id != $accessoryCategoryId) {
            return response()->json([
                'success' => false,
                'errors' => ['product' => ['Invalid product category.']]
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:in,out',
            'qty'  => 'required|integer|min:1|max:1000000',
            'ref'  => 'nullable|string|max:50',
            'note' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $type = $request->type;
        $qty  = (int)$request->qty;

        // Prevent negative stock when stock-out
        if ($type === 'out') {
            $totals = StockLedger::query()
                ->where('product_id', $product->id)
                ->selectRaw('COALESCE(SUM(qty_in),0) as total_in, COALESCE(SUM(qty_out),0) as total_out')
                ->first();

            $currentStock = ((int)$totals->total_in) - ((int)$totals->total_out);

            if ($qty > $currentStock) {
                return response()->json([
                    'success' => false,
                    'errors' => ['qty' => ['Stock out qty cannot exceed current stock (' . $currentStock . ').']]
                ], 422);
            }
        }

        StockLedger::create([
            'product_id' => $product->id,
            'qty_in' => $type === 'in' ? $qty : 0,
            'qty_out' => $type === 'out' ? $qty : 0,
            'ref' => $request->ref ? trim($request->ref) : 'ADJ',
            'note' => $request->note ? trim($request->note) : null,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock adjustment saved successfully!',
        ]);
    }
}
