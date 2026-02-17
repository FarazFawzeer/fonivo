@extends('layouts.vertical', ['subtitle' => 'Dashboard'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Fonivo.lk', 'subtitle' => 'Dashboard'])

    <div class="row">
        {{-- Card 1: Today Sales --}}
        <div class="col-md-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-md bg-primary bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:money-bag-outline"
                                    class="fs-32 text-primary avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Today Sales</p>
                            <h3 class="text-dark mt-2 mb-0">{{ number_format((float)$todaySales, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                    <span class="text-muted fs-12">Total sales for today</span>
                </div>
            </div>
        </div>

        {{-- Card 2: Customer Due --}}
        <div class="col-md-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-md bg-danger bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:hand-money-outline"
                                    class="fs-32 text-danger avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Customer Due</p>
                            <h3 class="text-dark mt-2 mb-0">{{ number_format((float)$customerDue, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                    <span class="text-muted fs-12">Total outstanding from customers</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Stock Alerts --}}
        <div class="col-md-6 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="avatar-md bg-warning bg-opacity-10 rounded-circle">
                                <iconify-icon icon="solar:box-outline"
                                    class="fs-32 text-warning avatar-title"></iconify-icon>
                            </div>
                        </div>
                        <div class="col-6 text-end">
                            <p class="text-muted mb-0 text-truncate">Stock Snapshot</p>
                            <h5 class="text-dark mt-2 mb-0">
                                Phones: <b>{{ (int)$phonesAvailable }}</b>
                            </h5>
                            <div class="text-muted small">
                                Low Accessories: <b>{{ (int)$lowStockCount }}</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 py-2 bg-light bg-opacity-50 mx-2 mb-2">
                    <span class="text-muted fs-12">Available phones + low stock accessories</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Sales --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="card-title mb-0">Recent Sales Invoices</h4>
                        <p class="card-subtitle mb-0">Latest invoices from your system.</p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.sales.index') }}" class="btn btn-light btn-sm">
                            View All
                        </a>
                        <a href="{{ route('admin.sales.create') }}" class="btn btn-primary btn-sm">
                            Create Sale
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-centered">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Balance</th>
                                    <th>Status</th>
                                    <th class="text-center" style="width:70px;">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSales as $s)
                                    @php
                                        $badge = $s->status === 'paid' ? 'bg-success' : ($s->status === 'partial' ? 'bg-warning' : 'bg-danger');
                                    @endphp
                                    <tr>
                                        <td><b>{{ $s->invoice_no }}</b></td>
                                        <td>{{ $s->sale_date?->format('d M Y') ?? '-' }}</td>
                                        <td>{{ $s->customer?->name ?? '-' }}</td>
                                        <td class="text-end">{{ number_format((float)($s->total_amount ?? 0),2) }}</td>
                                        <td class="text-end">{{ number_format((float)($s->paid_amount ?? 0),2) }}</td>
                                        <td class="text-end">{{ number_format((float)($s->balance_amount ?? 0),2) }}</td>
                                        <td><span class="badge {{ $badge }}">{{ ucfirst($s->status) }}</span></td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.sales.show', $s->id) }}"
                                               style="color:#198754; font-size:18px; display:inline-flex; align-items:center;"
                                               data-bs-toggle="tooltip" title="View">
                                                <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No sales found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
