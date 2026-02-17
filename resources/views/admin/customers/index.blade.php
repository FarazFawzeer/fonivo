@extends('layouts.vertical', ['subtitle' => 'Customer View'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Customers', 'subtitle' => 'View'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Customer List</h5>
                <p class="card-subtitle mb-0">All customers in your system with details.</p>
            </div>
            <div>
                <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">Create Customer</a>
            </div>
        </div>

        <div class="card-body">

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.customers.index') }}" class="row g-2 mb-3">
                <div class="col-md-9">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Search by name / phone / email">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Search</button>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-light w-100">Reset</a>
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
                        @forelse($customers as $c)
                            <tr id="customer-{{ $c->id }}">
                                <td><h6 class="mb-0">{{ $c->name }}</h6></td>
                                <td>{{ $c->phone ?? '-' }}</td>
                                <td>{{ $c->email ?? '-' }}</td>
                                <td>{{ $c->updated_at->format('d M Y, h:i A') }}</td>

                                {{-- Icon actions no background --}}
                                <td class="text-center">
                                    <div style="display:flex; justify-content:center; gap:12px;">
                                        <a href="{{ route('admin.customers.edit', $c) }}"
                                            style="color:#0d6efd; font-size:18px; display:flex; align-items:center;"
                                            data-bs-toggle="tooltip" title="Edit">
                                            <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                        </a>

                                        <button type="button"
                                            class="delete-customer"
                                            data-id="{{ $c->id }}"
                                            data-name="{{ $c->name }}"
                                            style="border:none; background:none; color:#dc3545; font-size:18px; display:flex; align-items:center; padding:0;"
                                            data-bs-toggle="tooltip" title="Delete">
                                            <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No customers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-customer').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.dataset.id;
                let name = this.dataset.name;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Delete customer: ${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    fetch("{{ url('admin/customers') }}/" + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('customer-' + id).remove();
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
