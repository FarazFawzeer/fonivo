@extends('layouts.vertical', ['subtitle' => 'Customer Ledger'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Ledgers', 'subtitle' => 'Customer'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Customer Ledger</h5>
            <p class="card-subtitle mb-0">Sales (Debit) vs Payments (Credit) with running balance.</p>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('admin.ledgers.customers.index') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="customer_id" class="form-select" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ (string)request('customer_id') === (string)$c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}" placeholder="From">
                </div>

                <div class="col-md-3">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="To">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">View</button>
                    <a href="{{ route('admin.ledgers.customers.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            @if($customer)
                <div class="row mb-3">
                    <div class="col-md-4"><b>Customer:</b> {{ $customer->name }}</div>
                    <div class="col-md-3"><b>Sales (Debit):</b> {{ number_format((float)($summary['total_debit'] ?? 0),2) }}</div>
                    <div class="col-md-3"><b>Payments (Credit):</b> {{ number_format((float)($summary['total_credit'] ?? 0),2) }}</div>
                    <div class="col-md-2">
                        <b>Balance:</b>
                        <span class="{{ ((float)($summary['balance'] ?? 0)) > 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format((float)($summary['balance'] ?? 0),2) }}
                        </span>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Ref</th>
                                <th>Note</th>
                                <th class="text-end">Debit</th>
                                <th class="text-end">Credit</th>
                                <th class="text-end">Balance</th>
                                <th class="text-center" style="width:80px;">Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rows as $r)
                                <tr>
                                    <td>{{ $r['date']?->format('d M Y') ?? '-' }}</td>
                                    <td><b>{{ $r['ref'] }}</b></td>
                                    <td class="text-muted">{{ $r['note'] ?? '-' }}</td>
                                    <td class="text-end">{{ number_format((float)$r['debit'],2) }}</td>
                                    <td class="text-end">{{ number_format((float)$r['credit'],2) }}</td>
                                    <td class="text-end">{{ number_format((float)$r['balance'],2) }}</td>
                                    <td class="text-center">
                                        @if($r['type'] === 'sale')
                                            <a href="{{ route('admin.sales.show', $r['related_id']) }}"
                                                style="color:#198754; font-size:18px; display:inline-flex; align-items:center;"
                                                data-bs-toggle="tooltip" title="View Invoice">
                                                <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">No records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">Select a customer to view ledger.</div>
            @endif
        </div>
    </div>
@endsection
