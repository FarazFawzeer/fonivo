<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesInvoice;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesInvoicePdfController extends Controller
{
    public function download(SalesInvoice $sale)
    {
        $sale->load([
            'customer:id,name,phone,email,address',
            'items.product:id,name,sku,brand,model',
            'items.phoneUnit:id,imei1,imei2,purchase_cost',
            'payments'
        ]);

        $pdf = Pdf::loadView('admin.sales.pdf', compact('sale'))
            ->setPaper('A4', 'portrait');

        $filename = $sale->invoice_no . '.pdf';

        return $pdf->download($filename);
        // or ->stream($filename) if you want open in browser
    }
}
