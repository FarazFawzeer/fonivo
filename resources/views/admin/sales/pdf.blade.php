<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $sale->invoice_no }}</title>
    <style>
        @page {
            margin: 40px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        /* Layout Helpers */
        .w-100 {
            width: 100%;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Header & Logo */
   .header-table {
    border-bottom: 2px solid #1e293b;
    padding-bottom: 20px;
    margin-bottom: 25px;
}

        .logo {
            width: 200px;
            height: auto;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #1a56db;
            text-transform: uppercase;
        }

        /* Invoice Info Bar */
        .invoice-details-table {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .invoice-title {
            font-size: 24px;
            color: #1e293b;
            margin: 0;
        }

        /* Sections */
        .section-title {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        /* Tables */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items-table th {
            background: #1e293b;
            color: #ffffff;
            padding: 10px;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Totals Area */
        .totals-container {
            margin-top: 30px;
        }

        .total-row td {
            padding: 5px 0;
        }

        .grand-total {
            background: #f1f5f9;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #1a56db;
        }

        /* Status Badge */
        .badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            background: #e2e8f0;
        }

        .logo {
            width: 140px;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    @php
        $total = (float) ($sale->total_amount ?? 0);
        $paid = (float) ($sale->paid_amount ?? 0);
        $balance = (float) ($sale->balance_amount ?? 0);
    @endphp

<table class="w-100 header-table">

    {{-- LOGO ROW --}}
    <tr>
        <td colspan="2" class="text-center" style="padding-bottom:20px;">
            @php $logoPath = public_path('images/fonivo.png'); @endphp
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" style="width:140px;">
            @endif
        </td>
    </tr>

    {{-- COMPANY LEFT | INVOICE RIGHT --}}
    <tr>
        {{-- Company Details --}}
        <td style="width: 50%; vertical-align: top;">

            <div class="company-name">Fonivo.lk</div>

            <div style="color:#000; font-size:11px; margin-top:8px;">
                123 Business Street<br>
                Colombo, Sri Lanka<br>
                +94 7X XXX XXXX<br>
                support@fonivo.lk
            </div>

        </td>

        {{-- Invoice Details --}}
        <td style="width: 50%; vertical-align: top;" class="text-right">

            <h1 class="invoice-title" style="margin:0;">INVOICE</h1>

            <div style="margin-top: 10px; font-size:12px;">
                <span class="font-bold">Invoice No:</span> {{ $sale->invoice_no }}<br>
                <span class="font-bold">Date:</span> {{ $sale->sale_date?->format('d M Y') ?? '-' }}<br>
                <span class="font-bold">Status:</span>
                <span class="badge">{{ strtoupper($sale->status ?? 'PENDING') }}</span>
            </div>

        </td>
    </tr>

</table>

    <table class="w-100" style="margin-bottom: 30px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="section-title">Bill To:</div>
                <div style="font-size: 14px; font-weight: bold;">{{ $sale->customer?->name ?? 'Walk-in Customer' }}
                </div>
                <div style="color: #475569;">
                    {{ $sale->customer?->phone ?? '' }}<br>
                    {{ $sale->customer?->email ?? '' }}<br>
                    {{ $sale->customer?->address ?? '' }}
                </div>
            </td>
            <td style="width: 50%; vertical-align: top;" class="text-right">
                <div class="section-title">Notes / Remarks:</div>
                <div style="color: #475569; font-style: italic;">
                    {{ $sale->note ?? 'Thank you for your business!' }}
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>IMEI / SKU</th>
                <th class="text-center" style="width: 60px;">Qty</th>
                <th class="text-right" style="width: 100px;">Unit Price</th>
                <th class="text-right" style="width: 100px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $it)
                @php
                    $isPhone = !empty($it->phone_unit_id);
                    $sku = $it->product?->sku;
                    $imei = $it->phoneUnit?->imei1;
                @endphp
                <tr>
                    <td>
                        <span class="font-bold">{{ $it->product?->name ?? '-' }}</span><br>
                        <small style="color: #666;">{{ $it->product?->brand }} {{ $it->product?->model }}
                            ({{ $isPhone ? 'Phone' : 'Accessory' }})
                        </small>
                    </td>
                    <td><small>{{ $isPhone ? $imei ?? '-' : $sku ?? '-' }}</small></td>
                    <td class="text-center">{{ (int) ($it->qty ?? 0) }}</td>
                    <td class="text-right">{{ number_format((float) ($it->unit_sell_price ?? 0), 2) }}</td>
                    <td class="text-right font-bold">{{ number_format((float) ($it->line_total ?? 0), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="w-100 totals-container">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <div class="section-title">Payment History</div>
                <table class="w-100" style="font-size: 11px;">
                    @forelse($sale->payments as $p)
                        <tr>
                            <td style="color: #64748b; padding: 2px 0;">
                                {{ $p->paid_at?->format('d M Y') }} via {{ ucfirst($p->method) }}
                            </td>
                            <td class="text-right" style="padding: 2px 0;">
                                {{ number_format((float) ($p->amount ?? 0), 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="color: #94a3b8;">No payments recorded.</td>
                        </tr>
                    @endforelse
                </table>
            </td>
            <td style="width: 5%;"></td>
            <td style="width: 35%; vertical-align: top;">
                <table class="w-100 total-row">
                    <tr>
                        <td class="text-right">Subtotal:</td>
                        <td class="text-right">{{ number_format($total, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right">Total Paid:</td>
                        <td class="text-right" style="color: #16a34a;">- {{ number_format($paid, 2) }}</td>
                    </tr>
                    <tr class="grand-total">
                        <td class="text-right">Balance Due:</td>
                        <td class="text-right">{{ number_format($balance, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="margin-top: 50px; border-top: 1px solid #e2e8f0; padding-top: 20px;" class="text-center">
        <p style="color: #94a3b8; font-size: 10px;">
            This is a computer-generated document. No signature is required. <br>
            <strong>fonivo.lk</strong>
        </p>
    </div>

</body>

</html>
