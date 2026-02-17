<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PhoneUnit;
use App\Models\Product;
use App\Models\SalesInvoice;
use App\Models\SalesItem;
use App\Models\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SalesInvoiceController extends Controller
{
    private function accessoryCategoryId(): ?int
    {
        return Category::whereRaw('LOWER(name) = ?', ['accessory'])->value('id');
    }

    private function makeInvoiceNo(): string
    {
        $lastId = (int) (SalesInvoice::max('id') ?? 0);
        return 'SAL-' . str_pad((string)($lastId + 1), 6, '0', STR_PAD_LEFT);
    }

    private function recalcInvoice(SalesInvoice $invoice): void
    {
        $total = (float) $invoice->items()->sum('line_total');

        $paid = (float) Payment::query()
            ->where('related_type', 'sales_invoice')
            ->where('related_id', $invoice->id)
            ->sum('amount');

        $balance = max(0, $total - $paid);

        $status = 'unpaid';
        if ($paid > 0 && $balance > 0) $status = 'partial';
        if ($total > 0 && $balance <= 0) $status = 'paid';

        $invoice->update([
            'total_amount' => $total,
            'paid_amount' => $paid,
            'balance_amount' => $balance,
            'status' => $status,
        ]);
    }

    private function getAccessoryStock(int $productId): int
    {
        $totals = StockLedger::query()
            ->where('product_id', $productId)
            ->selectRaw('COALESCE(SUM(qty_in),0) as total_in, COALESCE(SUM(qty_out),0) as total_out')
            ->first();

        return ((int)$totals->total_in) - ((int)$totals->total_out);
    }

    public function index(Request $request)
    {
        $query = SalesInvoice::query()
            ->with(['customer:id,name'])
            ->select('id','customer_id','invoice_no','sale_date','total_amount','paid_amount','balance_amount','status','updated_at');

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where('invoice_no', 'like', "%{$q}%");
        }

        $sales = $query->latest()->paginate(10)->withQueryString();
        $customers = Customer::select('id','name')->orderBy('name')->get();

        return view('admin.sales.index', compact('sales','customers'));
    }

    public function create()
    {
        $customers = Customer::select('id','name')->orderBy('name')->get();

        // Available phones (units)
        $availablePhones = PhoneUnit::query()
            ->with(['product:id,name,brand,model'])
            ->where('status', 'available')
            ->select('id','product_id','imei1','imei2','purchase_cost','status','updated_at')
            ->latest()
            ->get();

        // Accessories products
        $accCat = $this->accessoryCategoryId();
        $accessoryProducts = Product::select('id','name','sku','category_id')
            ->when($accCat, fn($q)=>$q->where('category_id',$accCat))
            ->orderBy('name')
            ->get();

        return view('admin.sales.create', compact('customers','availablePhones','accessoryProducts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'sale_date' => 'required|date',
            'note' => 'nullable|string|max:2000',

            'phones_json' => 'nullable|string',
            'accessories_json' => 'nullable|string',

            'paid_now' => 'nullable|numeric|min:0',
            'pay_method' => 'nullable|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'pay_note' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false,'errors'=>$validator->errors()], 422);
        }

        $phones = $request->phones_json ? json_decode($request->phones_json, true) : [];
        $accessories = $request->accessories_json ? json_decode($request->accessories_json, true) : [];

        if (!is_array($phones)) $phones = [];
        if (!is_array($accessories)) $accessories = [];

        if (count($phones) === 0 && count($accessories) === 0) {
            return response()->json([
                'success' => false,
                'errors' => ['items' => ['Please add at least one phone or accessory item.']]
            ], 422);
        }

        // Validate phone rows
        $phoneIds = [];
        foreach ($phones as $i => $p) {
            if (empty($p['phone_unit_id']) || !isset($p['unit_sell_price'])) {
                return response()->json(['success'=>false,'errors'=>['phones'=>["Phone row ".($i+1)." is incomplete."]]], 422);
            }
            $phoneIds[] = (int)$p['phone_unit_id'];
        }

        // Validate accessory rows
        foreach ($accessories as $i => $a) {
            if (empty($a['product_id']) || empty($a['qty']) || !isset($a['unit_sell_price'])) {
                return response()->json(['success'=>false,'errors'=>['accessories'=>["Accessory row ".($i+1)." is incomplete."]]], 422);
            }
        }

        $invoiceNo = $this->makeInvoiceNo();

        return DB::transaction(function () use ($request, $phones, $accessories, $phoneIds, $invoiceNo) {

            // Lock phones for safe selling
            $phoneUnits = PhoneUnit::query()
                ->whereIn('id', $phoneIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($phoneIds as $id) {
                $unit = $phoneUnits->get($id);
                if (!$unit) {
                    return response()->json(['success'=>false,'errors'=>['phones'=>["Phone unit #{$id} not found."]]], 422);
                }
                if ($unit->status !== 'available') {
                    return response()->json(['success'=>false,'errors'=>['phones'=>["Phone IMEI {$unit->imei1} is not available."]]], 422);
                }
            }

            // Validate accessory stock enough
            foreach ($accessories as $a) {
                $productId = (int)$a['product_id'];
                $qty = (int)$a['qty'];

                $available = $this->getAccessoryStock($productId);
                if ($qty > $available) {
                    return response()->json([
                        'success'=>false,
                        'errors'=>['accessories'=>["Accessory stock not enough (Product #{$productId}). Available: {$available}"]]
                    ], 422);
                }
            }

            $sale = SalesInvoice::create([
                'customer_id' => $request->customer_id,
                'invoice_no' => $invoiceNo,
                'sale_date' => $request->sale_date,

                'total_amount' => 0,
                'paid_amount' => 0,
                'balance_amount' => 0,
                'status' => 'unpaid',

                'note' => $request->note ? trim($request->note) : null,
                'created_by' => Auth::id(),
            ]);

            // 1) Phones: set sold + create items with cost snapshot
            foreach ($phones as $p) {
                $unitId = (int)$p['phone_unit_id'];
                $sellPrice = (float)$p['unit_sell_price'];

                $unit = $phoneUnits->get($unitId);

                $unit->update(['status' => 'sold']);

                SalesItem::create([
                    'sales_invoice_id' => $sale->id,
                    'phone_unit_id' => $unit->id,
                    'product_id' => $unit->product_id,
                    'qty' => 1,
                    'unit_sell_price' => $sellPrice,
                    'unit_cost_price_snapshot' => (float)($unit->purchase_cost ?? 0),
                    'line_total' => $sellPrice,
                ]);
            }

            // 2) Accessories: ledger out + create items
            foreach ($accessories as $a) {
                $productId = (int)$a['product_id'];
                $qty = (int)$a['qty'];
                $sellPrice = (float)$a['unit_sell_price'];
                $lineTotal = $qty * $sellPrice;

                SalesItem::create([
                    'sales_invoice_id' => $sale->id,
                    'phone_unit_id' => null,
                    'product_id' => $productId,
                    'qty' => $qty,
                    'unit_sell_price' => $sellPrice,
                    'unit_cost_price_snapshot' => null,
                    'line_total' => $lineTotal,
                ]);

                StockLedger::create([
                    'product_id' => $productId,
                    'qty_in' => 0,
                    'qty_out' => $qty,
                    'ref' => $invoiceNo,
                    'note' => 'Sale OUT',
                    'created_by' => Auth::id(),
                ]);
            }

            // Initial payment
            $paidNow = (float)($request->paid_now ?? 0);
            if ($paidNow > 0) {
                Payment::create([
                    'party_type' => 'customer',
                    'party_id' => $sale->customer_id,

                    'related_type' => 'sales_invoice',
                    'related_id' => $sale->id,

                    'amount' => $paidNow,
                    'paid_at' => $sale->sale_date,
                    'method' => $request->pay_method ? trim($request->pay_method) : 'cash',
                    'reference_no' => $request->reference_no ? trim($request->reference_no) : $invoiceNo,
                    'note' => $request->pay_note ? trim($request->pay_note) : 'Initial payment',
                    'created_by' => Auth::id(),
                ]);
            }

            $this->recalcInvoice($sale->fresh());

            return response()->json([
                'success' => true,
                'message' => 'Sales invoice created successfully!',
                'redirect' => route('admin.sales.show', $sale->id),
            ]);
        });
    }

    public function show(SalesInvoice $sale)
    {
        $sale->load([
            'customer:id,name,phone,email',
            'items.product:id,name,sku,brand,model',
            'items.phoneUnit:id,imei1,imei2,status,purchase_cost',
            'payments'
        ]);

        return view('admin.sales.show', compact('sale'));
    }

    public function storePayment(Request $request, SalesInvoice $sale)
    {
        $validator = Validator::make($request->all(), [
            'paid_at' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|max:50',
            'reference_no' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>false,'errors'=>$validator->errors()], 422);
        }

        Payment::create([
            'party_type' => 'customer',
            'party_id' => $sale->customer_id,

            'related_type' => 'sales_invoice',
            'related_id' => $sale->id,

            'amount' => (float)$request->amount,
            'paid_at' => $request->paid_at,
            'method' => trim($request->method),
            'reference_no' => $request->reference_no ? trim($request->reference_no) : $sale->invoice_no,
            'note' => $request->note ? trim($request->note) : null,
            'created_by' => Auth::id(),
        ]);

        $this->recalcInvoice($sale->fresh());

        return response()->json([
            'success' => true,
            'message' => 'Payment added successfully!',
        ]);
    }
}
