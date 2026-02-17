@extends('layouts.vertical', ['subtitle' => 'Category View'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Categories', 'subtitle' => 'View'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Category List</h5>
                <p class="card-subtitle mb-0">Manage your product categories (Phone / Accessory).</p>
            </div>
            <div>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Create Category</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Updated At</th>
                            <th scope="col" style="width:220px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr id="cat-{{ $cat->id }}">
                                <td>
                                    <h6 class="mb-0">{{ $cat->name }}</h6>
                                </td>
                                <td>
                                    @if ($cat->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $cat->updated_at->format('d M Y, h:i A') }}</td>
                            <td class="text-center">
    <div style="display:flex; justify-content:center; gap:12px;">

        {{-- Edit --}}
        <a href="{{ route('admin.categories.edit', $cat) }}"
            style="color:#0d6efd; font-size:18px; display:flex; align-items:center;"
            data-bs-toggle="tooltip"
            title="Edit">
            <iconify-icon icon="solar:pen-outline"></iconify-icon>
        </a>

        {{-- Delete --}}
        <button type="button"
            class="delete-cat"
            data-id="{{ $cat->id }}"
            data-name="{{ $cat->name }}"
            style="border:none; background:none; color:#dc3545; font-size:18px; display:flex; align-items:center; padding:0;"
            data-bs-toggle="tooltip"
            title="Delete">
            <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
        </button>

    </div>
</td>


                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-cat').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.dataset.id;
                let name = this.dataset.name;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Delete category: ${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    fetch("{{ url('admin/categories') }}/" + id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById('cat-' + id).remove();
                                Swal.fire('Deleted!', data.message, 'success');
                            } else {
                                Swal.fire('Error!', data.message || 'Something went wrong!',
                                    'error');
                            }
                        })
                        .catch(() => Swal.fire('Error!', 'Something went wrong!', 'error'));
                });
            });
        });
    </script>
@endsection
