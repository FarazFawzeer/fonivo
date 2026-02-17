<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PhoneUnit;
use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\SalesInvoice;
use App\Models\SalesItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    private function f($n): float
    {
        return (float) ($n ?? 0);
    }

    private function accessoryCategoryId(): ?int
    {
        return Category::whereRaw('LOWER(name) = ?', ['accessory'])->value('id');
    }

    /**
     * Profit report
     */
    public function profit(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $accCostRow = PurchaseItem::query()
            ->whereNull('phone_unit_id')
            ->selectRaw('COALESCE(SUM(qty * unit_cost_price),0) as total_cost, COALESCE(SUM(qty),0) as total_qty')
            ->first();

        $accAvgCost = 0.0;
        if ((int)($accCostRow->total_qty ?? 0) > 0) {
            $accAvgCost = ((float)$accCostRow->total_cost) / ((int)$accCostRow->total_qty);
        }

        $salesQ = SalesInvoice::query()
            ->with(['customer:id,name'])
            ->select('id','customer_id','invoice_no','sale_date','total_amount','paid_amount','balance_amount','status')
            ->latest();

        if ($from) $salesQ->whereDate('sale_date', '>=', $from);
        if ($to) $salesQ->whereDate('sale_date', '<=', $to);

        $sales = $salesQ->paginate(10)->withQueryString();

        $invoiceProfits = [];
        $grand = [
            'sales_total' => 0,
            'cost_total' => 0,
            'profit_total' => 0,
            'phone_profit' => 0,
            'accessory_profit' => 0,
        ];

        $invoiceIds = $sales->pluck('id')->toArray();

        $items = SalesItem::query()
            ->with(['product:id,name,sku,brand,model,default_cost_price','phoneUnit:id,imei1,purchase_cost'])
            ->whereIn('sales_invoice_id', $invoiceIds)
            ->get()
            ->groupBy('sales_invoice_id');

        foreach ($sales as $inv) {
            $invItems = $items->get($inv->id, collect());

            $invSales = 0.0;
            $invCost = 0.0;
            $invPhoneProfit = 0.0;
            $invAccProfit = 0.0;

            foreach ($invItems as $it) {
                $lineSales = $this->f($it->line_total);
                $invSales += $lineSales;

                if ($it->phone_unit_id) {
                    $cost = $this->f($it->unit_cost_price_snapshot);
                    $invCost += $cost;
                    $invPhoneProfit += ($lineSales - $cost);
                } else {
                    $qty = (int)($it->qty ?? 0);
                    $productCost = (float)($it->product?->default_cost_price ?? 0);

                    $unitCost = $productCost > 0 ? $productCost : $accAvgCost;
                    $cost = $unitCost * $qty;

                    $invCost += $cost;
                    $invAccProfit += ($lineSales - $cost);
                }
            }

            $invProfit = $invSales - $invCost;

            $invoiceProfits[$inv->id] = [
                'sales' => $invSales,
                'cost' => $invCost,
                'profit' => $invProfit,
                'phone_profit' => $invPhoneProfit,
                'accessory_profit' => $invAccProfit,
                'items' => $invItems,
            ];

            $grand['sales_total'] += $invSales;
            $grand['cost_total'] += $invCost;
            $grand['profit_total'] += $invProfit;
            $grand['phone_profit'] += $invPhoneProfit;
            $grand['accessory_profit'] += $invAccProfit;
        }

        return view('admin.reports.profit', compact('sales','invoiceProfits','accAvgCost','grand','from','to'));
    }

    /**
     * Stock report
     */
    public function stock(Request $request)
    {
        $phoneCounts = PhoneUnit::query()
            ->selectRaw("
                SUM(CASE WHEN status='available' THEN 1 ELSE 0 END) as available,
                SUM(CASE WHEN status='sold' THEN 1 ELSE 0 END) as sold,
                SUM(CASE WHEN status='reserved' THEN 1 ELSE 0 END) as reserved
            ")
            ->first();

        $accCat = $this->accessoryCategoryId();
        $lowDefault = (int)($request->low ?? 5);

        $accStocks = Product::query()
            ->where('category_id', $accCat)
            ->select('products.id','products.name','products.sku','products.brand','products.model','products.is_active','products.default_cost_price','products.default_sell_price')
            ->when(\Schema::hasColumn('products','reorder_level'), function($q){
                $q->addSelect('products.reorder_level');
            })
            ->leftJoin('stock_ledgers', 'stock_ledgers.product_id', '=', 'products.id')
            ->selectRaw('COALESCE(SUM(stock_ledgers.qty_in),0) as total_in')
            ->selectRaw('COALESCE(SUM(stock_ledgers.qty_out),0) as total_out')
            ->groupBy(
                'products.id','products.name','products.sku','products.brand','products.model','products.is_active',
                'products.default_cost_price','products.default_sell_price'
            )
            ->when(\Schema::hasColumn('products','reorder_level'), function($q){
                $q->groupBy('products.reorder_level');
            })
            ->orderBy('products.name')
            ->get()
            ->map(function ($p) use ($lowDefault) {
                $in = (int)($p->total_in ?? 0);
                $out = (int)($p->total_out ?? 0);
                $p->current_stock = $in - $out;

                $threshold = property_exists($p, 'reorder_level')
                    ? (int)($p->reorder_level ?? $lowDefault)
                    : $lowDefault;

                $p->threshold = $threshold;
                $p->is_low = ($p->current_stock <= $threshold);
                return $p;
            });

        return view('admin.reports.stock', compact('phoneCounts','accStocks','lowDefault'));
    }

    /**
     * Due report
     */
    public function due()
    {
        $customerDue = Customer::query()
            ->select('customers.id','customers.name')
            ->leftJoin('sales_invoices', 'sales_invoices.customer_id', '=', 'customers.id')
            ->selectRaw('COALESCE(SUM(sales_invoices.total_amount),0) as sales_total')
            ->groupBy('customers.id','customers.name')
            ->get()
            ->map(function ($c) {
                $paid = Payment::query()
                    ->where('party_type', 'customer')
                    ->where('party_id', $c->id)
                    ->where('related_type', 'sales_invoice')
                    ->sum('amount');

                $c->paid_total = (float)$paid;
                $c->balance = ((float)$c->sales_total) - (float)$paid;
                return $c;
            })
            ->filter(fn($c) => $c->balance > 0.00001)
            ->sortByDesc('balance')
            ->values();

        $supplierDue = Supplier::query()
            ->select('suppliers.id','suppliers.name')
            ->leftJoin('purchase_invoices', 'purchase_invoices.supplier_id', '=', 'suppliers.id')
            ->selectRaw('COALESCE(SUM(purchase_invoices.total_amount),0) as purchase_total')
            ->groupBy('suppliers.id','suppliers.name')
            ->get()
            ->map(function ($s) {
                $paid = Payment::query()
                    ->where('party_type', 'supplier')
                    ->where('party_id', $s->id)
                    ->where('related_type', 'purchase_invoice')
                    ->sum('amount');

                $s->paid_total = (float)$paid;
                $s->balance = ((float)$s->purchase_total) - (float)$paid;
                return $s;
            })
            ->filter(fn($s) => $s->balance > 0.00001)
            ->sortByDesc('balance')
            ->values();

        return view('admin.reports.due', compact('customerDue','supplierDue'));
    }

    /**
     * Daily sales summary
     */
    public function dailySales(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        $q = SalesInvoice::query()
            ->selectRaw('sale_date as day')
            ->selectRaw('COALESCE(SUM(total_amount),0) as total_sales')
            ->selectRaw('COALESCE(SUM(paid_amount),0) as total_paid')
            ->selectRaw('COALESCE(SUM(balance_amount),0) as total_balance')
            ->groupBy('sale_date')
            ->orderByDesc('sale_date');

        if ($from) $q->whereDate('sale_date', '>=', $from);
        if ($to) $q->whereDate('sale_date', '<=', $to);

        $days = $q->paginate(15)->withQueryString();

        return view('admin.reports.daily_sales', compact('days','from','to'));
    }
}
