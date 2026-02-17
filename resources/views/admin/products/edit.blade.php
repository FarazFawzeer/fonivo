@extends('layouts.vertical', ['subtitle' => 'Product Edit'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Products', 'subtitle' => 'Edit'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Product</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="updateProductForm" action="{{ route('admin.products.update', $product) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Brand (Optional)</label>
                        <input type="text" name="brand" class="form-control" value="{{ $product->brand }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Model (Optional)</label>
                        <input type="text" name="model" class="form-control" value="{{ $product->model }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">SKU (Optional)</label>
                        <input type="text" name="sku" class="form-control" value="{{ $product->sku }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Default Cost Price</label>
                        <input type="number" step="0.01" min="0" name="default_cost_price" class="form-control"
                               value="{{ $product->default_cost_price }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Default Sell Price</label>
                        <input type="number" step="0.01" min="0" name="default_sell_price" class="form-control"
                               value="{{ $product->default_sell_price }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light">Back</a>
                    <button type="submit" class="btn btn-primary">Update Product</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('updateProductForm').addEventListener('submit', function(e) {
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
