@extends('layouts.vertical', ['subtitle' => 'Purchase Details'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Purchases', 'subtitle' => 'Details'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">{{ $purchase->invoice_no }}</h5>
                <p class="card-subtitle mb-0">
                    Supplier: <b>{{ $purchase->supplier?->name ?? '-' }}</b> |
                    Date: <b>{{ $purchase->purchase_date?->format('d M Y') ?? '-' }}</b>

                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.purchases.index') }}" class="btn btn-light">Back</a>
            </div>
        </div>

        <div class="card-body">
            {{-- Summary --}}
            <div class="row mb-3">
                <div class="col-md-3"><b>Total:</b> {{ number_format($purchase->total, 2) }}</div>
                <div class="col-md-3"> <b>Paid:</b> {{ number_format((float) ($purchase->paid_amount ?? 0), 2) }}</div>
                <div class="col-md-3"><b>Balance:</b> {{ number_format($purchase->balance_amount, 2) }}</div>
                <div class="col-md-3">
                    @php
                        $badge =
                            $purchase->status === 'paid'
                                ? 'bg-success'
                                : ($purchase->status === 'partial'
                                    ? 'bg-warning'
                                    : 'bg-danger');
                    @endphp
                    <span class="badge {{ $badge }}">{{ ucfirst($purchase->status) }}</span>
                </div>
            </div>

            {{-- Items --}}
            <h6 class="mb-2">Items</h6>
            <div class="table-responsive mb-4">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Product</th>
                            <th>IMEI</th>
                            <th>Qty</th>
                            <th>Unit Cost</th>
                            <th>Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase->items as $it)
                            <tr>
                                <td>{{ ucfirst($it->item_type) }}</td>
                                <td>
                                    <b>{{ $it->product?->name ?? '-' }}</b>
                                    <div class="text-muted small">
                                        {{ $it->product?->brand ?? '' }} {{ $it->product?->model ?? '' }}
                                        {{ $it->product?->sku ? ' | ' . $it->product->sku : '' }}
                                    </div>
                                </td>
                                <td>{{ $it->item_type === 'phone' ? $it->imei1 ?? '-' : '-' }}</td>
                                <td>{{ $it->qty }}</td>
                                <td>{{ number_format($it->unit_cost, 2) }}</td>
                                <td>{{ number_format($it->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Payments --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Payments</h6>
                <button class="btn btn-primary btn-sm" type="button" onclick="togglePayForm()">Add Payment</button>
            </div>

            <div id="payBox" style="display:none;" class="border rounded p-3 mb-3">
                <div id="payMessage"></div>

                <form id="payForm" action="{{ route('admin.purchases.payments.store', $purchase->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Paid Date</label>
                            <input type="date" name="paid_at" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Amount</label>
                            <input type="number" step="0.01" min="0.01" name="amount" class="form-control"
                                required>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Method</label>
                            <select name="method" class="form-select" required>
                                <option value="cash" selected>Cash</option>
                                <option value="bank">Bank</option>
                                <option value="card">Card</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label">Reference</label>
                            <input type="text" name="reference_no" class="form-control" placeholder="Txn / Slip No">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Note</label>
                        <input type="text" name="note" class="form-control" placeholder="Optional">
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">Save Payment</button>
                    </div>
                </form>

            </div>

            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Reference</th>
                            <th>Note</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchase->payments as $pay)
                            <tr>
                                <td>{{ $pay->paid_at?->format('d M Y') ?? '-' }}</td>

                                <td>{{ $pay->reference_no ?? '-' }}</td>
                                <td>{{ $pay->note ?? '-' }}</td>
                                <td class="text-end">{{ number_format($pay->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No payments yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        function togglePayForm() {
            const box = document.getElementById('payBox');
            box.style.display = (box.style.display === 'none' || box.style.display === '') ? 'block' : 'none';
        }

        document.getElementById('payForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                        "Accept": "application/json"
                    }
                })
                .then(r => r.json())
                .then(data => {
                    const box = document.getElementById('payMessage');
                    if (data.success) {
                        box.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                        setTimeout(() => window.location.reload(), 700);
                    } else {
                        let errors = Object.values(data.errors || {}).flat().join('<br>');
                        box.innerHTML =
                            `<div class="alert alert-danger">${errors || 'Something went wrong!'}</div>`;
                    }
                })
                .catch(err => {
                    document.getElementById('payMessage').innerHTML =
                        `<div class="alert alert-danger">Error: ${err}</div>`;
                });
        });
    </script>
@endsection
