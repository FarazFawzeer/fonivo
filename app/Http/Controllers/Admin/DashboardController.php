<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Payment;
use App\Models\PhoneUnit;
use App\Models\Product;
use App\Models\SalesInvoice;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private function accessoryCategoryId(): ?int
    {
        return Category::whereRaw('LOWER(name) = ?', ['accessory'])->value('id');
    }

    public function index()
    {
        $today = now()->toDateString();

        // 1) Today sales total (sales invoices total today)
        $todaySales = (float) SalesInvoice::whereDate('sale_date', $today)->sum('total_amount');

        // 2) Customer due (Sales total - payments received for sales_invoice)
        $totalSales = (float) SalesInvoice::sum('total_amount');

        $paidFromCustomers = (float) Payment::query()
            ->where('party_type', 'customer')
            ->where('related_type', 'sales_invoice')
            ->sum('amount');

        $customerDue = max(0, $totalSales - $paidFromCustomers);

        // 3) Stock snapshot
        $phonesAvailable = (int) PhoneUnit::where('status', 'available')->count();

        // Accessories low stock count (uses reorder_level if exists; else threshold=5)
        $accCat = $this->accessoryCategoryId();
        $defaultLow = 5;

        $accStocks = Product::query()
            ->where('category_id', $accCat)
            ->leftJoin('stock_ledgers', 'stock_ledgers.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', 'products.is_active')
            ->when(\Schema::hasColumn('products', 'reorder_level'), fn($q) => $q->addSelect('products.reorder_level'))
            ->selectRaw('COALESCE(SUM(stock_ledgers.qty_in),0) as total_in')
            ->selectRaw('COALESCE(SUM(stock_ledgers.qty_out),0) as total_out')
            ->groupBy('products.id', 'products.name', 'products.is_active')
            ->when(\Schema::hasColumn('products', 'reorder_level'), fn($q) => $q->groupBy('products.reorder_level'))
            ->get()
            ->map(function ($p) use ($defaultLow) {
                $current = (int)($p->total_in ?? 0) - (int)($p->total_out ?? 0);
                $threshold = property_exists($p, 'reorder_level') ? (int)($p->reorder_level ?? $defaultLow) : $defaultLow;

                $p->current_stock = $current;
                $p->threshold = $threshold;
                $p->is_low = ($p->is_active && $current <= $threshold);
                return $p;
            });

        $lowStockCount = (int) $accStocks->where('is_low', true)->count();

        // Recent Sales (last 8)
        $recentSales = SalesInvoice::query()
            ->with('customer:id,name')
            ->select('id','invoice_no','sale_date','customer_id','total_amount','paid_amount','balance_amount','status')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact(
            'todaySales',
            'customerDue',
            'phonesAvailable',
            'lowStockCount',
            'recentSales'
        ));
    }
}
