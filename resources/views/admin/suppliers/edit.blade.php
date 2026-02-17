@extends('layouts.vertical', ['subtitle' => 'Supplier Edit'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Suppliers', 'subtitle' => 'Edit'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Supplier</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="updateSupplierForm" action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone (Optional)</label>
                        <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email (Optional)</label>
                        <input type="email" name="email" class="form-control" value="{{ $supplier->email }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address (Optional)</label>
                        <input type="text" name="address" class="form-control" value="{{ $supplier->address }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea name="notes" class="form-control" rows="3">{{ $supplier->notes }}</textarea>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-light">Back</a>
                    <button type="submit" class="btn btn-primary">Update Supplier</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('updateSupplierForm').addEventListener('submit', function(e) {
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
