@extends('layouts.vertical', ['subtitle' => 'Edit Phone'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Phone Stock', 'subtitle' => 'Edit'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Phone Stock</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="updatePhoneForm" action="{{ route('admin.phone_units.update', $phoneUnit) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Product</label>
                        <select name="product_id" class="form-select" required>
                            @foreach($phoneProducts as $p)
                                <option value="{{ $p->id }}" {{ $phoneUnit->product_id == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                    {{ $p->brand ? " - {$p->brand}" : '' }}
                                    {{ $p->model ? " / {$p->model}" : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach(['available','reserved','returned','sold'] as $s)
                                <option value="{{ $s }}" {{ $phoneUnit->status == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">IMEI 1</label>
                        <input type="text" name="imei1" class="form-control" value="{{ $phoneUnit->imei1 }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">IMEI 2 (Optional)</label>
                        <input type="text" name="imei2" class="form-control" value="{{ $phoneUnit->imei2 }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Condition</label>
                        <input type="text" name="condition" class="form-control" value="{{ $phoneUnit->condition }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Battery Health</label>
                        <input type="text" name="battery_health" class="form-control" value="{{ $phoneUnit->battery_health }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Warranty Days</label>
                        <input type="number" min="0" name="warranty_days" class="form-control" value="{{ $phoneUnit->warranty_days }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purchase Cost</label>
                        <input type="number" step="0.01" min="0" name="purchase_cost" class="form-control" value="{{ $phoneUnit->purchase_cost }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expected Sell Price</label>
                        <input type="number" step="0.01" min="0" name="expected_sell_price" class="form-control" value="{{ $phoneUnit->expected_sell_price }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Included Items</label>
                    <textarea name="included_items" class="form-control" rows="2">{{ $phoneUnit->included_items }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Faults / Notes</label>
                    <textarea name="faults" class="form-control" rows="3">{{ $phoneUnit->faults }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.phone_units.index') }}" class="btn btn-light">Back</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('updatePhoneForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            fetch(form.action, {
                method: "POST", // POST + _method=PUT
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                }
            })
            .then(res => res.json())
            .then(data => {
                let messageBox = document.getElementById('message');

                if (data.success) {
                    messageBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    setTimeout(() => messageBox.innerHTML = "", 2500);
                } else {
                    let errors = Object.values(data.errors || {}).flat().join('<br>');
                    messageBox.innerHTML = `<div class="alert alert-danger">${errors || 'Something went wrong!'}</div>`;
                }
            })
            .catch(err => {
                document.getElementById('message').innerHTML =
                    `<div class="alert alert-danger">Error: ${err}</div>`;
            });
        });
    </script>
@endsection
