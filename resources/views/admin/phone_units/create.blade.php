@extends('layouts.vertical', ['subtitle' => 'Add Phone'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Phone Stock', 'subtitle' => 'Add Phone'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Add Phone (Manual)</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="createPhoneForm" action="{{ route('admin.phone_units.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Product</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">Select Phone</option>
                            @foreach($phoneProducts as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->name }}
                                    {{ $p->brand ? " - {$p->brand}" : '' }}
                                    {{ $p->model ? " / {$p->model}" : '' }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Create phone products in Products section first.</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="available" selected>Available</option>
                            <option value="reserved">Reserved</option>
                            <option value="returned">Returned</option>
                            <option value="sold">Sold</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">IMEI 1</label>
                        <input type="text" name="imei1" class="form-control" placeholder="IMEI 1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">IMEI 2 (Optional)</label>
                        <input type="text" name="imei2" class="form-control" placeholder="IMEI 2">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Condition (Optional)</label>
                        <input type="text" name="condition" class="form-control" placeholder="Ex: A / Good">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Battery Health (Optional)</label>
                        <input type="text" name="battery_health" class="form-control" placeholder="Ex: 85%">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Warranty Days</label>
                        <input type="number" min="0" name="warranty_days" class="form-control" value="0">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purchase Cost (Optional)</label>
                        <input type="number" step="0.01" min="0" name="purchase_cost" class="form-control" placeholder="0.00">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expected Sell Price (Optional)</label>
                        <input type="number" step="0.01" min="0" name="expected_sell_price" class="form-control" placeholder="0.00">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Included Items (Optional)</label>
                    <textarea name="included_items" class="form-control" rows="2" placeholder="Box, charger, cable..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Faults / Notes (Optional)</label>
                    <textarea name="faults" class="form-control" rows="3" placeholder="Any issues..."></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Add to Stock</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('createPhoneForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
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
                    form.reset();
                    setTimeout(() => messageBox.innerHTML = "", 3000);
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
