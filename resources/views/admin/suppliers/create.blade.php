@extends('layouts.vertical', ['subtitle' => 'Supplier Create'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Suppliers', 'subtitle' => 'Create'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">New Supplier</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="createSupplierForm" action="{{ route('admin.suppliers.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Ex: ABC Mobile" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone (Optional)</label>
                        <input type="text" name="phone" class="form-control" placeholder="Ex: 0771234567">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email (Optional)</label>
                        <input type="email" name="email" class="form-control" placeholder="Ex: abc@gmail.com">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address (Optional)</label>
                        <input type="text" name="address" class="form-control" placeholder="Ex: Colombo">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Any notes..."></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Supplier</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('createSupplierForm').addEventListener('submit', function(e) {
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
