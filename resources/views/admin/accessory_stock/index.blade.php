@extends('layouts.vertical', ['subtitle' => 'Accessory Stock'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Accessories Stock', 'subtitle' => 'View'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Accessory Stock List</h5>
            <p class="card-subtitle mb-0">Current stock = Total In - Total Out (ledger-based).</p>
        </div>

        <div class="card-body">

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.accessory_stock.index') }}" class="row g-2 mb-3">
                <div class="col-md-9">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Search by name / sku / brand / model">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Search</button>
                    <a href="{{ route('admin.accessory_stock.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Total In</th>
                            <th>Total Out</th>
                            <th>Current Stock</th>
                            <th class="text-center" style="width:130px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                            <tr>
                                <td>
                                    <h6 class="mb-0">{{ $p->name }}</h6>
                                    <small class="text-muted">
                                        {{ $p->brand ?? '-' }}{{ $p->model ? ' / '.$p->model : '' }}
                                    </small>
                                </td>
                                <td>{{ $p->sku ?? '-' }}</td>
                                <td>{{ (int)$p->total_in }}</td>
                                <td>{{ (int)$p->total_out }}</td>
                                <td>
                                    @if((int)$p->current_stock > 0)
                                        <span class="badge bg-success">{{ (int)$p->current_stock }}</span>
                                    @elseif((int)$p->current_stock == 0)
                                        <span class="badge bg-secondary">0</span>
                                    @else
                                        <span class="badge bg-danger">{{ (int)$p->current_stock }}</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div style="display:flex; justify-content:center; gap:12px;">
                                        {{-- Stock Card --}}
                                        <a href="{{ route('admin.accessory_stock.show', $p->id) }}"
                                            style="color:#198754; font-size:18px; display:flex; align-items:center;"
                                            data-bs-toggle="tooltip" title="Stock Card">
                                            <iconify-icon icon="solar:book-outline"></iconify-icon>
                                        </a>

                                        {{-- Manual Adjust --}}
                                        <a href="{{ route('admin.accessory_stock.adjust.create', $p->id) }}"
                                            style="color:#0d6efd; font-size:18px; display:flex; align-items:center;"
                                            data-bs-toggle="tooltip" title="Adjust Stock">
                                            <iconify-icon icon="solar:plus-minus-outline"></iconify-icon>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No accessory products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $products->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
