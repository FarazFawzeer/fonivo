@extends('layouts.vertical', ['subtitle' => 'Product Edit'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Product', 'subtitle' => 'Edit'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Product</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="editProductForm" action="{{ route('admin.products.update', $product->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Product Type --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Type</label>
                        <select name="product_type" class="form-select" required>
                            <option value="phone" {{ $product->product_type == 'phone' ? 'selected' : '' }}>Phone</option>
                            <option value="bike" {{ $product->product_type == 'bike' ? 'selected' : '' }}>Bike</option>
                        </select>
                    </div>


                    {{-- Product Code --}}
<div class="col-md-6 mb-3">
    <label class="form-label">Product Code</label>
    <input type="text" name="product_code" class="form-control"
           value="{{ old('product_code', $product->product_code) }}" required>
</div>


                    {{-- Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Product Name / Model</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}"
                            required>
                    </div>

                    {{-- Owner --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Owner Name</label>
                        <input type="text" name="owner_name" class="form-control"
                            value="{{ old('owner_name', $product->owner_name) }}" required>
                    </div>

                    {{-- Contact --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Owner Contact</label>
                        <input type="text" name="owner_contact" class="form-control"
                            value="{{ old('owner_contact', $product->owner_contact) }}" required>
                    </div>

                    {{-- Purchase Date --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control"
                            value="{{ old('purchase_date', $product->purchase_date->format('Y-m-d')) }}" required>
                    </div>

                    {{-- Cost --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" class="form-control"
                            value="{{ old('cost_price', $product->cost_price) }}" required>
                    </div>

                    {{-- Selling --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" class="form-control"
                            value="{{ old('selling_price', $product->selling_price) }}" required>
                    </div>

                    {{-- Stock --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock Status</label>
                        <select name="stock_status" class="form-select" required>
                            <option value="available" {{ $product->stock_status == 'available' ? 'selected' : '' }}>
                                Available</option>
                            <option value="sold" {{ $product->stock_status == 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="out_of_stock" {{ $product->stock_status == 'out_of_stock' ? 'selected' : '' }}>
                                Out of Stock</option>
                        </select>
                    </div>

                    {{-- Images --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Add More Images</label>
                        <input type="file" name="images[]" class="form-control" multiple>
                    </div>
                </div>

                {{-- Existing Images --}}
{{-- Existing Product Images --}}
@if (!empty($product->images) && is_array($product->images))
    <div class="row mb-3">
        @foreach ($product->images as $key => $img)
            <div class="col-md-2 mb-2">
                <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded" style="width:100px;height:100px;object-fit:cover;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remove_images[]" value="{{ $key }}" id="removeImage{{ $key }}">
                    <label class="form-check-label" for="removeImage{{ $key }}">
                        Remove
                    </label>
                </div>
            </div>
        @endforeach
    </div>
@endif


                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="width: 120px;">
                        Back
                    </a>

                    <button type="submit" class="btn btn-primary" style="width: 120px;">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- AJAX --}}
    <script>
        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('message').innerHTML =
                        `<div class="alert alert-${data.success ? 'success' : 'danger'}">${data.message}</div>`;
                });
        });
    </script>
@endsection
