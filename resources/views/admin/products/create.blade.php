@extends('layouts.vertical', ['subtitle' => 'Product Create'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Products', 'subtitle' => 'Create'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Product</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="createProductForm" action="{{ route('admin.products.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Ex: iPhone 13 128GB" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Brand (Optional)</label>
                        <input type="text" name="brand" class="form-control" placeholder="Ex: Apple">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Model (Optional)</label>
                        <input type="text" name="model" class="form-control" placeholder="Ex: iPhone 13">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">SKU (Optional)</label>
                        <input type="text" name="sku" class="form-control" placeholder="Ex: USB-C-001">
                        <small class="text-muted">SKU is mostly used for Accessories.</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Default Cost Price (Optional)</label>
                        <input type="number" step="0.01" min="0" name="default_cost_price" class="form-control" placeholder="0.00">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Default Sell Price (Optional)</label>
                        <input type="number" step="0.01" min="0" name="default_sell_price" class="form-control" placeholder="0.00">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('createProductForm').addEventListener('submit', function(e) {
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
