<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\Supplier;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    private function money($n): string
    {
        return number_format((float)($n ?? 0), 2);
    }

    /**
     * Supplier Ledger
     * Debit  = Purchases (you owe)
     * Credit = Payments made to supplier
     */
    public function supplierIndex(Request $request)
    {
        $suppliers = Supplier::select('id','name')->orderBy('name')->get();

        $supplierId = $request->supplier_id;
        $from = $request->from; // YYYY-MM-DD
        $to = $request->to;

        $rows = [];
        $summary = [
            'total_debit' => 0,  // purchases
            'total_credit' => 0, // payments
            'balance' => 0,
        ];

        $supplier = null;

        if ($supplierId) {
            $supplier = Supplier::select('id','name')->find($supplierId);

            // Purchases (debit)
            $purchasesQ = PurchaseInvoice::query()
                ->select('id','invoice_no','purchase_date','total_amount')
                ->where('supplier_id', $supplierId);

            if ($from) $purchasesQ->whereDate('purchase_date', '>=', $from);
            if ($to) $purchasesQ->whereDate('purchase_date', '<=', $to);

            $purchases = $purchasesQ->get();

            foreach ($purchases as $inv) {
                $rows[] = [
                    'date' => $inv->purchase_date,
                    'ref' => $inv->invoice_no,
                    'type' => 'purchase',
                    'related_id' => $inv->id,
                    'debit' => (float)($inv->total_amount ?? 0),
                    'credit' => 0.0,
                    'note' => 'Purchase Invoice',
                ];
            }

            // Payments made to supplier (credit)
            $paymentsQ = Payment::query()
                ->select('id','related_id','related_type','amount','paid_at','method','reference_no','note')
                ->where('party_type', 'supplier')
                ->where('party_id', $supplierId)
                ->where('related_type', 'purchase_invoice');

            if ($from) $paymentsQ->whereDate('paid_at', '>=', $from);
            if ($to) $paymentsQ->whereDate('paid_at', '<=', $to);

            $payments = $paymentsQ->get();

            foreach ($payments as $pay) {
                $rows[] = [
                    'date' => $pay->paid_at,
                    'ref' => $pay->reference_no ?: ('PAY-' . $pay->id),
                    'type' => 'payment',
                    'related_id' => $pay->related_id, // purchase_invoice id
                    'debit' => 0.0,
                    'credit' => (float)($pay->amount ?? 0),
                    'note' => $pay->note ?: ('Payment (' . $pay->method . ')'),
                ];
            }

            // Sort by date then type (purchase first on same date), then by ref
            usort($rows, function ($a, $b) {
                $da = $a['date'] ? $a['date']->format('Y-m-d') : '0000-00-00';
                $db = $b['date'] ? $b['date']->format('Y-m-d') : '0000-00-00';
                if ($da !== $db) return $da <=> $db;

                $order = ['purchase' => 1, 'payment' => 2];
                if ($order[$a['type']] !== $order[$b['type']]) {
                    return $order[$a['type']] <=> $order[$b['type']];
                }
                return strcmp((string)$a['ref'], (string)$b['ref']);
            });

            // Running balance
            $running = 0.0;
            foreach ($rows as &$r) {
                $running += ((float)$r['debit'] - (float)$r['credit']);
                $r['balance'] = $running;

                $summary['total_debit'] += (float)$r['debit'];
                $summary['total_credit'] += (float)$r['credit'];
            }
            unset($r);

            $summary['balance'] = $running;
        }

        return view('admin.ledgers.suppliers', compact(
            'suppliers','supplier','rows','summary','supplierId','from','to'
        ));
    }

    /**
     * Customer Ledger
     * Debit  = Sales (customer owes you)
     * Credit = Payments received from customer
     */
    public function customerIndex(Request $request)
    {
        $customers = Customer::select('id','name')->orderBy('name')->get();

        $customerId = $request->customer_id;
        $from = $request->from;
        $to = $request->to;

        $rows = [];
        $summary = [
            'total_debit' => 0,  // sales
            'total_credit' => 0, // payments
            'balance' => 0,
        ];

        $customer = null;

        if ($customerId) {
            $customer = Customer::select('id','name')->find($customerId);

            // Sales (debit)
            $salesQ = SalesInvoice::query()
                ->select('id','invoice_no','sale_date','total_amount')
                ->where('customer_id', $customerId);

            if ($from) $salesQ->whereDate('sale_date', '>=', $from);
            if ($to) $salesQ->whereDate('sale_date', '<=', $to);

            $sales = $salesQ->get();

            foreach ($sales as $inv) {
                $rows[] = [
                    'date' => $inv->sale_date,
                    'ref' => $inv->invoice_no,
                    'type' => 'sale',
                    'related_id' => $inv->id,
                    'debit' => (float)($inv->total_amount ?? 0),
                    'credit' => 0.0,
                    'note' => 'Sales Invoice',
                ];
            }

            // Payments received (credit)
            $paymentsQ = Payment::query()
                ->select('id','related_id','related_type','amount','paid_at','method','reference_no','note')
                ->where('party_type', 'customer')
                ->where('party_id', $customerId)
                ->where('related_type', 'sales_invoice');

            if ($from) $paymentsQ->whereDate('paid_at', '>=', $from);
            if ($to) $paymentsQ->whereDate('paid_at', '<=', $to);

            $payments = $paymentsQ->get();

            foreach ($payments as $pay) {
                $rows[] = [
                    'date' => $pay->paid_at,
                    'ref' => $pay->reference_no ?: ('PAY-' . $pay->id),
                    'type' => 'payment',
                    'related_id' => $pay->related_id, // sales_invoice id
                    'debit' => 0.0,
                    'credit' => (float)($pay->amount ?? 0),
                    'note' => $pay->note ?: ('Payment (' . $pay->method . ')'),
                ];
            }

            // Sort by date then type (sale first), then ref
            usort($rows, function ($a, $b) {
                $da = $a['date'] ? $a['date']->format('Y-m-d') : '0000-00-00';
                $db = $b['date'] ? $b['date']->format('Y-m-d') : '0000-00-00';
                if ($da !== $db) return $da <=> $db;

                $order = ['sale' => 1, 'payment' => 2];
                if ($order[$a['type']] !== $order[$b['type']]) {
                    return $order[$a['type']] <=> $order[$b['type']];
                }
                return strcmp((string)$a['ref'], (string)$b['ref']);
            });

            // Running balance
            $running = 0.0;
            foreach ($rows as &$r) {
                $running += ((float)$r['debit'] - (float)$r['credit']);
                $r['balance'] = $running;

                $summary['total_debit'] += (float)$r['debit'];
                $summary['total_credit'] += (float)$r['credit'];
            }
            unset($r);

            $summary['balance'] = $running;
        }

        return view('admin.ledgers.customers', compact(
            'customers','customer','rows','summary','customerId','from','to'
        ));
    }
}
