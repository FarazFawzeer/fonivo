@extends('layouts.vertical', ['subtitle' => 'Category Edit'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Categories', 'subtitle' => 'Edit'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Category</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="updateCategoryForm" action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ $category->name }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select id="is_active" name="is_active" class="form-select" required>
                            <option value="1" {{ $category->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$category->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Back</a>
                    <button type="submit" class="btn btn-primary">Update Category</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('updateCategoryForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);

            fetch(form.action, {
                method: "POST", // using POST with _method=PUT
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
