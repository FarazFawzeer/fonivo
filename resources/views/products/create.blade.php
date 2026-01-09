@extends('layouts.vertical', ['subtitle' => 'Product Create'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Product', 'subtitle' => 'Create'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Product</h5>
        </div>

        <div class="card-body">
            <div id="message"></div> {{-- Success / Error messages --}}

            <form id="createProductForm" action="{{ route('products.store') }}" method="POST">
                @csrf

                {{-- Product Type --}}
                <div class="mb-3">
                    <label for="product_type" class="form-label">Product Type</label>
                    <select name="product_type" id="product_type" class="form-select" required>
                        <option value="">Select Type</option>
                        <option value="phone" {{ old('product_type') == 'phone' ? 'selected' : '' }}>Phone</option>
                        <option value="bike" {{ old('product_type') == 'bike' ? 'selected' : '' }}>Bike</option>
                    </select>
                </div>

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name / Model</label>
                    <input type="text" name="name" id="name" class="form-control" 
                           value="{{ old('name') }}" placeholder="Ex: iPhone 15" required>
                </div>

                {{-- Owner Name + Contact --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="owner_name" class="form-label">Owner Name</label>
                        <input type="text" name="owner_name" id="owner_name" class="form-control" 
                               value="{{ old('owner_name') }}" placeholder="Ex: John Doe" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="owner_contact" class="form-label">Owner Contact</label>
                        <input type="text" name="owner_contact" id="owner_contact" class="form-control" 
                               value="{{ old('owner_contact') }}" placeholder="Ex: +94771234567" required>
                    </div>
                </div>

                {{-- Purchase Date --}}
                <div class="mb-3">
                    <label for="purchase_date" class="form-label">Purchase Date</label>
                    <input type="date" name="purchase_date" id="purchase_date" class="form-control" 
                           value="{{ old('purchase_date') }}" required>
                </div>

                {{-- Cost Price + Selling Price --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cost_price" class="form-label">Cost Price</label>
                        <input type="number" step="0.01" name="cost_price" id="cost_price" class="form-control" 
                               value="{{ old('cost_price') }}" placeholder="Ex: 1500.00" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="selling_price" class="form-label">Selling Price</label>
                        <input type="number" step="0.01" name="selling_price" id="selling_price" class="form-control" 
                               value="{{ old('selling_price') }}" placeholder="Ex: 1800.00" required>
                    </div>
                </div>

                {{-- Stock Status --}}
                <div class="mb-3">
                    <label for="stock_status" class="form-label">Stock Status</label>
                    <select name="stock_status" id="stock_status" class="form-select" required>
                        <option value="available" {{ old('stock_status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="sold" {{ old('stock_status') == 'sold' ? 'selected' : '' }}>Sold</option>
                        <option value="out_of_stock" {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>

                {{-- Submit --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Optional: AJAX submission --}}
    <script>
        document.getElementById('createProductForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                const messageBox = document.getElementById('message');
                if (data.success) {
                    messageBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    form.reset();
                    setTimeout(() => { messageBox.innerHTML = ""; }, 3000);
                } else {
                    let errors = data.errors ? data.errors.join('<br>') : data.message;
                    messageBox.innerHTML = `<div class="alert alert-danger">${errors}</div>`;
                }
            })
            .catch(error => {
                document.getElementById('message').innerHTML =
                    `<div class="alert alert-danger">Something went wrong. Please try again.</div>`;
                console.error(error);
            });
        });
    </script>
@endsection
