@extends('layouts.vertical', ['subtitle' => 'Stock Card'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Accessory Stock', 'subtitle' => 'Stock Card'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">{{ $product->name }}</h5>
                <p class="card-subtitle mb-0">
                    SKU: {{ $product->sku ?? '-' }} |
                    Current Stock: <b>{{ $currentStock }}</b>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.accessory_stock.adjust.create', $product->id) }}" class="btn btn-primary">
                    Adjust
                </a>
                <a href="{{ route('admin.accessory_stock.index') }}" class="btn btn-light">Back</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Ref</th>
                            <th>Note</th>
                            <th>In</th>
                            <th>Out</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $balance = 0; @endphp

                        @forelse($ledgers as $l)
                            @php
                                $in = (int)$l->qty_in;
                                $out = (int)$l->qty_out;
                                $balance += ($in - $out);
                            @endphp
                            <tr>
                                <td>{{ $l->created_at->format('d M Y, h:i A') }}</td>
                                <td>{{ $l->ref ?? '-' }}</td>
                                <td>{{ $l->note ?? '-' }}</td>
                                <td>{{ $in ?: '-' }}</td>
                                <td>{{ $out ?: '-' }}</td>
                                <td><b>{{ $balance }}</b></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No stock history found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
