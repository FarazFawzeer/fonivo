@extends('layouts.vertical', ['subtitle' => 'Profit Report'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Profit'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Profit Report</h5>
            <p class="card-subtitle mb-0">
                Phones: sell - purchase snapshot | Accessories: sell - avg purchase cost (temporary).
            </p>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('admin.reports.profit') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="date" name="from" class="form-control" value="{{ $from }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="to" class="form-control" value="{{ $to }}">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" value="Accessory Avg Cost: {{ number_format((float)$accAvgCost,2) }}" readonly>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                    <a href="{{ route('admin.reports.profit') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <div class="row mb-3">
                <div class="col-md-3"><b>Sales:</b> {{ number_format((float)$grand['sales_total'],2) }}</div>
                <div class="col-md-3"><b>Cost:</b> {{ number_format((float)$grand['cost_total'],2) }}</div>
                <div class="col-md-3"><b>Profit:</b> {{ number_format((float)$grand['profit_total'],2) }}</div>
                <div class="col-md-3">
                    <span class="text-muted">Phones: {{ number_format((float)$grand['phone_profit'],2) }} | Accessories: {{ number_format((float)$grand['accessory_profit'],2) }}</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th class="text-end">Sales</th>
                            <th class="text-end">Cost</th>
                            <th class="text-end">Profit</th>
                            <th class="text-center" style="width:80px;">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $inv)
                            @php
                                $p = $invoiceProfits[$inv->id] ?? ['sales'=>0,'cost'=>0,'profit'=>0];
                            @endphp
                            <tr>
                                <td><b>{{ $inv->invoice_no }}</b></td>
                                <td>{{ $inv->sale_date?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $inv->customer?->name ?? '-' }}</td>
                                <td class="text-end">{{ number_format((float)$p['sales'],2) }}</td>
                                <td class="text-end">{{ number_format((float)$p['cost'],2) }}</td>
                                <td class="text-end">{{ number_format((float)$p['profit'],2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.sales.show', $inv->id) }}"
                                        style="color:#198754; font-size:18px; display:inline-flex; align-items:center;"
                                        data-bs-toggle="tooltip" title="View Invoice">
                                        <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No invoices found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $sales->links() }}
            </div>

        </div>
    </div>
@endsection
