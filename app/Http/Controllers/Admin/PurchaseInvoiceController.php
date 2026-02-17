<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Payment;
use App\Models\PhoneUnit;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseItem;
use App\Models\StockLedger;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseInvoiceController extends Controller
{
    private function phoneCategoryId(): ?int
    {
        return Category::whereRaw('LOWER(name) = ?', ['phone'])->value('id');
    }

    private function accessoryCategoryId(): ?int
    {
        return Category::whereRaw('LOWER(name) = ?', ['accessory'])->value('id');
    }

    private function makeInvoiceNo(): string
    {
        $lastId = (int) (PurchaseInvoice::max('id') ?? 0);
        return 'PUR-' . str_pad((string)($lastId + 1), 6, '0', STR_PAD_LEFT);
    }

    private function recalcInvoice(PurchaseInvoice $invoice): void
    {
        $total = (float) $invoice->items()->sum('line_total');

        $paid = (float) Payment::query()
            ->where('related_type', 'purchase_invoice')
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

    public function index(Request $request)
    {
        $query = PurchaseInvoice::query()
            ->with(['supplier:id,name'])
            ->select('id','supplier_id','invoice_no','purchase_date','total_amount','paid_amount','balance_amount','status','updated_at');

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where('invoice_no', 'like', "%{$q}%");
        }

        $purchases = $query->latest()->paginate(10)->withQueryString();
        $suppliers = Supplier::select('id','name')->orderBy('name')->get();

        return view('admin.purchases.index', compact('purchases','suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::select('id','name')->orderBy('name')->get();

        $phoneCat = $this->phoneCategoryId();
        $accCat   = $this->accessoryCategoryId();

        $phoneProducts = Product::select('id','name','brand','model','category_id')
            ->when($phoneCat, fn($q)=>$q->where('category_id',$phoneCat))
            ->orderBy('name')
            ->get();

        $accessoryProducts = Product::select('id','name','sku','brand','model','category_id')
            ->when($accCat, fn($q)=>$q->where('category_id',$accCat))
            ->orderBy('name')
            ->get();

        return view('admin.purchases.create', compact('suppliers','phoneProducts','accessoryProducts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
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

        $invoiceNo = $this->makeInvoiceNo();

        return DB::transaction(function () use ($request, $phones, $accessories, $invoiceNo) {

            $invoice = PurchaseInvoice::create([
                'supplier_id' => $request->supplier_id,
                'invoice_no' => $invoiceNo,
                'purchase_date' => $request->purchase_date,

                'total_amount' => 0,
                'paid_amount' => 0,
                'balance_amount' => 0,
                'status' => 'unpaid',

                'note' => $request->note ? trim($request->note) : null,
                'created_by' => Auth::id(),
            ]);

            // 1) Phones → create phone_units + purchase_items
            foreach ($phones as $i => $p) {
                if (empty($p['product_id']) || empty($p['imei1']) || !isset($p['unit_cost'])) {
                    return response()->json([
                        'success'=>false,
                        'errors'=>['phones'=>["Phone row ".($i+1)." is incomplete."]]
                    ], 422);
                }

                $phoneUnit = PhoneUnit::create([
                    'product_id' => (int)$p['product_id'],
                    'imei1' => trim($p['imei1']),
                    'imei2' => !empty($p['imei2']) ? trim($p['imei2']) : null,
                    'condition' => !empty($p['condition']) ? trim($p['condition']) : null,
                    'battery_health' => !empty($p['battery_health']) ? trim($p['battery_health']) : null,
                    'warranty_days' => isset($p['warranty_days']) ? (int)$p['warranty_days'] : 0,
                    'included_items' => !empty($p['included_items']) ? trim($p['included_items']) : null,
                    'faults' => !empty($p['faults']) ? trim($p['faults']) : null,
                    'purchase_cost' => (float)$p['unit_cost'],
                    'expected_sell_price' => null,
                    'status' => 'available',
                ]);

                PurchaseItem::create([
                    'purchase_invoice_id' => $invoice->id,
                    'phone_unit_id' => $phoneUnit->id,
                    'product_id' => (int)$p['product_id'],
                    'qty' => 1,
                    'unit_cost_price' => (float)$p['unit_cost'],
                    'line_total' => (float)$p['unit_cost'],
                ]);
            }

            // 2) Accessories → purchase_items + stock_ledgers qty_in
            foreach ($accessories as $i => $a) {
                if (empty($a['product_id']) || empty($a['qty']) || !isset($a['unit_cost'])) {
                    return response()->json([
                        'success'=>false,
                        'errors'=>['accessories'=>["Accessory row ".($i+1)." is incomplete."]]
                    ], 422);
                }

                $qty = (int)$a['qty'];
                $unitCost = (float)$a['unit_cost'];
                $lineTotal = $qty * $unitCost;

                PurchaseItem::create([
                    'purchase_invoice_id' => $invoice->id,
                    'phone_unit_id' => null,
                    'product_id' => (int)$a['product_id'],
                    'qty' => $qty,
                    'unit_cost_price' => $unitCost,
                    'line_total' => $lineTotal,
                ]);

                StockLedger::create([
                    'product_id' => (int)$a['product_id'],
                    'qty_in' => $qty,
                    'qty_out' => 0,
                    'ref' => $invoiceNo,
                    'note' => 'Purchase IN',
                    'created_by' => Auth::id(),
                ]);
            }

            // 3) Initial payment using YOUR payments table (optional)
            $paidNow = (float)($request->paid_now ?? 0);

            if ($paidNow > 0) {
                Payment::create([
                    'party_type' => 'supplier',
                    'party_id' => $invoice->supplier_id,

                    'related_type' => 'purchase_invoice',
                    'related_id' => $invoice->id,

                    'amount' => $paidNow,
                    'paid_at' => $invoice->purchase_date,
                    'method' => $request->pay_method ? trim($request->pay_method) : 'cash',
                    'reference_no' => $request->reference_no ? trim($request->reference_no) : $invoiceNo,
                    'note' => $request->pay_note ? trim($request->pay_note) : 'Initial payment',
                    'created_by' => Auth::id(),
                ]);
            }

            // 4) Recalc totals/paid/balance/status
            $this->recalcInvoice($invoice->fresh());

            return response()->json([
                'success' => true,
                'message' => 'Purchase invoice created successfully!',
                'redirect' => route('admin.purchases.show', $invoice->id),
            ]);
        });
    }

    public function show(PurchaseInvoice $purchase)
    {
        $purchase->load([
            'supplier:id,name,phone,email',
            'items.product:id,name,sku,brand,model',
            'items.phoneUnit:id,imei1,imei2,status',
            'payments'
        ]);

        return view('admin.purchases.show', compact('purchase'));
    }

    public function storePayment(Request $request, PurchaseInvoice $purchase)
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
            'party_type' => 'supplier',
            'party_id' => $purchase->supplier_id,

            'related_type' => 'purchase_invoice',
            'related_id' => $purchase->id,

            'amount' => (float)$request->amount,
            'paid_at' => $request->paid_at,
            'method' => trim($request->method),
            'reference_no' => $request->reference_no ? trim($request->reference_no) : $purchase->invoice_no,
            'note' => $request->note ? trim($request->note) : null,
            'created_by' => Auth::id(),
        ]);

        $this->recalcInvoice($purchase->fresh());

        return response()->json([
            'success' => true,
            'message' => 'Payment added successfully!',
        ]);
    }
}
