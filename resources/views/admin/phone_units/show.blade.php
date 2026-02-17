@extends('layouts.vertical', ['subtitle' => 'Phone Details'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Phone Stock', 'subtitle' => 'Details'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Phone Details</h5>
                <p class="card-subtitle mb-0">IMEI-based phone unit details.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.phone_units.edit', $phoneUnit) }}" class="btn btn-primary">Edit</a>
                <a href="{{ route('admin.phone_units.index') }}" class="btn btn-light">Back</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <h6 class="mb-2">Product</h6>
                    <div class="border rounded p-3">
                        <div><b>Name:</b> {{ $phoneUnit->product?->name ?? '-' }}</div>
                        <div><b>Brand:</b> {{ $phoneUnit->product?->brand ?? '-' }}</div>
                        <div><b>Model:</b> {{ $phoneUnit->product?->model ?? '-' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="mb-2">Stock Status</h6>
                    <div class="border rounded p-3">
                        <div><b>Status:</b> {{ ucfirst($phoneUnit->status) }}</div>
                        <div><b>Warranty Days:</b> {{ $phoneUnit->warranty_days }}</div>
                        <div><b>Updated:</b> {{ $phoneUnit->updated_at->format('d M Y, h:i A') }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="mb-2">IMEI</h6>
                    <div class="border rounded p-3">
                        <div><b>IMEI 1:</b> {{ $phoneUnit->imei1 }}</div>
                        <div><b>IMEI 2:</b> {{ $phoneUnit->imei2 ?? '-' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="mb-2">Condition</h6>
                    <div class="border rounded p-3">
                        <div><b>Condition:</b> {{ $phoneUnit->condition ?? '-' }}</div>
                        <div><b>Battery Health:</b> {{ $phoneUnit->battery_health ?? '-' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="mb-2">Prices</h6>
                    <div class="border rounded p-3">
                        <div><b>Purchase Cost:</b> {{ $phoneUnit->purchase_cost !== null ? number_format($phoneUnit->purchase_cost,2) : '-' }}</div>
                        <div><b>Expected Sell Price:</b> {{ $phoneUnit->expected_sell_price !== null ? number_format($phoneUnit->expected_sell_price,2) : '-' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="mb-2">Included Items</h6>
                    <div class="border rounded p-3">
                        {{ $phoneUnit->included_items ?? '-' }}
                    </div>
                </div>

                <div class="col-12">
                    <h6 class="mb-2">Faults / Notes</h6>
                    <div class="border rounded p-3">
                        {{ $phoneUnit->faults ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
