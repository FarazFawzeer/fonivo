@extends('layouts.vertical', ['subtitle' => 'Product View'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Products', 'subtitle' => 'View'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Product List</h5>
                <p class="card-subtitle mb-0">All products (Phones + Accessories).</p>
            </div>
            <div>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Create Product</a>
            </div>
        </div>

        <div class="card-body">

            {{-- Filters --}}
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Search by name / brand / model / sku">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand / Model</th>
                            <th>SKU</th>
                            <th>Default Cost</th>
                            <th>Default Sell</th>
                            <th class="text-center" style="width:110px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                            <tr id="product-{{ $p->id }}">
                                <td>
                                    <h6 class="mb-0">{{ $p->name }}</h6>
                                </td>
                                <td>{{ $p->category?->name ?? '-' }}</td>
                                <td>
                                    {{ $p->brand ?? '-' }}
                                    @if($p->model) / {{ $p->model }} @endif
                                </td>
                                <td>{{ $p->sku ?? '-' }}</td>
                                <td>{{ $p->default_cost_price !== null ? number_format($p->default_cost_price, 2) : '-' }}</td>
                                <td>{{ $p->default_sell_price !== null ? number_format($p->default_sell_price, 2) : '-' }}</td>

                                {{-- Icon actions no background --}}
                                <td class="text-center">
                                    <div style="display:flex; justify-content:center; gap:12px;">
                                        <a href="{{ route('admin.products.edit', $p) }}"
                                            style="color:#0d6efd; font-size:18px; display:flex; align-items:center;"
                                            data-bs-toggle="tooltip" title="Edit">
                                            <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                        </a>

                                        <button type="button"
                                            class="delete-product"
                                            data-id="{{ $p->id }}"
                                            data-name="{{ $p->name }}"
                                            style="border:none; background:none; color:#dc3545; font-size:18px; display:flex; align-items:center; padding:0;"
                                            data-bs-toggle="tooltip" title="Delete">
                                            <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-product').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.dataset.id;
                let name = this.dataset.name;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Delete product: ${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    fetch("{{ url('admin/products') }}/" + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('product-' + id).remove();
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
