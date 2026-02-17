@extends('layouts.vertical', ['subtitle' => 'Stock Report'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Stock'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Stock Report</h5>
            <p class="card-subtitle mb-0">Phones by status + accessories current stock + low stock.</p>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4"><b>Phones Available:</b> {{ (int) ($phoneCounts->available ?? 0) }}</div>
                <div class="col-md-4"><b>Phones Sold:</b> {{ (int) ($phoneCounts->sold ?? 0) }}</div>
                <div class="col-md-4"><b>Phones Reserved:</b> {{ (int) ($phoneCounts->reserved ?? 0) }}</div>
            </div>

            <form method="GET" action="{{ route('admin.reports.stock') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label">Low stock threshold</label>
                <input type="number" name="low" class="form-control" value="{{ $lowDefault }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button class="btn btn-primary w-100" type="submit">Apply</button>
                    <a href="{{ route('admin.reports.stock') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <h6 class="mb-2">Accessories Stock</h6>
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th class="text-end">In</th>
                            <th class="text-end">Out</th>
                            <th class="text-end">Current</th>
                            <th class="text-end">Reorder</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Low</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accStocks as $p)
                            <tr>
                                <td>
                                    <b>{{ $p->name }}</b>
                                    <div class="text-muted small">{{ $p->brand ?? '' }} {{ $p->model ?? '' }}</div>
                                </td>
                                <td>{{ $p->sku ?? '-' }}</td>
                                <td class="text-end">{{ (int) ($p->total_in ?? 0) }}</td>
                                <td class="text-end">{{ (int) ($p->total_out ?? 0) }}</td>
                                <td class="text-end">{{ (int) ($p->current_stock ?? 0) }}</td>
                                <td class="text-end">{{ (int) ($p->threshold ?? $lowDefault) }}</td>
                                <td class="text-center">
                                    @if ($p->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($p->is_low)
                                        <span class="badge bg-danger">LOW</span>
                                    @else
                                        <span class="badge bg-success">OK</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
