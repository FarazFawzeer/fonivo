@extends('layouts.vertical', ['subtitle' => 'Supplier View'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Suppliers', 'subtitle' => 'View'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Supplier List</h5>
                <p class="card-subtitle mb-0">All suppliers in your system with details.</p>
            </div>
            <div>
                <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">Create Supplier</a>
            </div>
        </div>

        <div class="card-body">

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.suppliers.index') }}" class="row g-2 mb-3">
                <div class="col-md-9">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Search by name / phone / email">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Search</button>
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Updated At</th>
                            <th class="text-center" style="width:110px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $s)
                            <tr id="supplier-{{ $s->id }}">
                                <td><h6 class="mb-0">{{ $s->name }}</h6></td>
                                <td>{{ $s->phone ?? '-' }}</td>
                                <td>{{ $s->email ?? '-' }}</td>
                                <td>{{ $s->updated_at->format('d M Y, h:i A') }}</td>

                                {{-- Icon actions no background --}}
                                <td class="text-center">
                                    <div style="display:flex; justify-content:center; gap:12px;">
                                        <a href="{{ route('admin.suppliers.edit', $s) }}"
                                            style="color:#0d6efd; font-size:18px; display:flex; align-items:center;"
                                            data-bs-toggle="tooltip" title="Edit">
                                            <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                        </a>

                                        <button type="button"
                                            class="delete-supplier"
                                            data-id="{{ $s->id }}"
                                            data-name="{{ $s->name }}"
                                            style="border:none; background:none; color:#dc3545; font-size:18px; display:flex; align-items:center; padding:0;"
                                            data-bs-toggle="tooltip" title="Delete">
                                            <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No suppliers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-supplier').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.dataset.id;
                let name = this.dataset.name;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Delete supplier: ${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    fetch("{{ url('admin/suppliers') }}/" + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('supplier-' + id).remove();
                            Swal.fire('Deleted!', data.message, 'success');
                        } else {
                            Swal.fire('Error!', data.message || 'Something went wrong!', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error!', 'Something went wrong!', 'error'));
                });
            });
        });
    </script>
@endsection
