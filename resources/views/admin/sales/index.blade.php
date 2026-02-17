@extends('layouts.vertical', ['subtitle' => 'Sales View'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Sales', 'subtitle' => 'View'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Sales List</h5>
                <p class="card-subtitle mb-0">Customer invoices with credit/partial/full payments.</p>
            </div>
            <div>
                <a href="{{ route('admin.sales.create') }}" class="btn btn-primary">Create Sale</a>
            </div>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('admin.sales.index') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="customer_id" class="form-select">
                        <option value="">All Customers</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['unpaid'=>'Unpaid','partial'=>'Partial','paid'=>'Paid'] as $k=>$v)
                            <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search invoice no">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th class="text-center" style="width:90px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $s)
                            <tr>
                                <td><b>{{ $s->invoice_no }}</b></td>
                                <td>{{ $s->customer?->name ?? '-' }}</td>
                                <td>{{ $s->sale_date?->format('d M Y') ?? '-' }}</td>
                                <td>{{ number_format((float)($s->total_amount ?? 0),2) }}</td>
                                <td>{{ number_format((float)($s->paid_amount ?? 0),2) }}</td>
                                <td>{{ number_format((float)($s->balance_amount ?? 0),2) }}</td>
                                <td>
                                    @php
                                        $badge = $s->status === 'paid' ? 'bg-success' : ($s->status === 'partial' ? 'bg-warning' : 'bg-danger');
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($s->status) }}</span>
                                </td>
                                <td class="text-center">
                                    <div style="display:flex; justify-content:center; gap:12px;">
                                        <a href="{{ route('admin.sales.show', $s->id) }}"
                                            style="color:#198754; font-size:18px; display:flex; align-items:center;"
                                            data-bs-toggle="tooltip" title="View">
                                            <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted">No sales found.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $sales->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
