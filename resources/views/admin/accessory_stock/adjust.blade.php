@extends('layouts.vertical', ['subtitle' => 'Adjust Stock'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Accessory Stock', 'subtitle' => 'Manual Adjustment'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Adjust Stock</h5>
            <p class="card-subtitle mb-0">
                Product: <b>{{ $product->name }}</b> |
                Current Stock: <b>{{ $currentStock }}</b>
            </p>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="adjustForm" action="{{ route('admin.accessory_stock.adjust.store', $product->id) }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select" required>
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Qty</label>
                        <input type="number" name="qty" min="1" class="form-control" placeholder="Ex: 10" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ref (Optional)</label>
                        <input type="text" name="ref" class="form-control" placeholder="Ex: ADJ / PUR-12 / SALE-8">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Note (Optional)</label>
                    <textarea name="note" class="form-control" rows="3" placeholder="Reason for adjustment..."></textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.accessory_stock.show', $product->id) }}" class="btn btn-light">Back</a>
                    <button type="submit" class="btn btn-primary">Save Adjustment</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('adjustForm').addEventListener('submit', function(e) {
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
                let box = document.getElementById('message');

                if (data.success) {
                    box.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    form.reset();
                    setTimeout(() => box.innerHTML = "", 2500);
                } else {
                    let errors = Object.values(data.errors || {}).flat().join('<br>');
                    box.innerHTML = `<div class="alert alert-danger">${errors || 'Something went wrong!'}</div>`;
                }
            })
            .catch(err => {
                document.getElementById('message').innerHTML =
                    `<div class="alert alert-danger">Error: ${err}</div>`;
            });
        });
    </script>
@endsection
