<table class="table table-hover table-centered">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Type</th>
            <th>Owner</th>
            <th>Purchase Date</th>
            <th>Cost</th>
            <th>Selling</th>
            <th>Profit</th>
            <th>Image</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($products as $product)
            <tr id="product-{{ $product->id }}">
                <td>
                    <div class="d-flex align-items-center gap-2">

                        <span>{{ $product->name }}</span>
                    </div>
                </td>

                <td>
                    <div class="d-flex align-items-center gap-2">

                        <span>{{ $product->product_code }}</span>
                    </div>
                </td>


                <td>{{ ucfirst($product->product_type) }}</td>

                <td>
                    {{ $product->owner_name }} <br>
                    <small class="text-muted">{{ $product->owner_contact }}</small>
                </td>

                <td>{{ \Carbon\Carbon::parse($product->purchase_date)->format('d M Y') }}</td>

                <td>{{ number_format($product->cost_price, 2) }}</td>
                <td>{{ number_format($product->selling_price, 2) }}</td>

                <td class="fw-bold text-success">
                    {{ number_format($product->selling_price - $product->cost_price, 2) }}
                </td>

                <td>
                    <div class="d-flex align-items-center gap-2">
                        @php
                            $images = is_array($product->images) ? $product->images : [];
                            $image = count($images)
                                ? asset('storage/' . $images[0])
                                : asset('images/default-product.png');
                        @endphp

                        <img src="{{ $image }}" class="rounded" style="width:40px;height:40px;object-fit:cover;">


                    </div>
                </td>
                <td>
                    <span
                        class="badge  bg-{{ $product->stock_status == 'available'
                            ? 'success'
                            : ($product->stock_status == 'sold'
                                ? 'danger'
                                : 'secondary') }}" style="width: 80px;">
                        {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                    </span>
                </td>



                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i>
                        </a>

                        <a href="{{ route('admin.products.edit', $product->id) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <button class="btn btn-sm btn-outline-danger delete-product" data-id="{{ $product->id }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center text-muted">No products found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-end mt-3">
    {{ $products->links() }}
</div>
