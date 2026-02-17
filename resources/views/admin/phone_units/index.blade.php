@extends('layouts.vertical', ['subtitle' => 'Phone Stock'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Phone Stock', 'subtitle' => 'View'])

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0">Phone Stock List</h5>
                <p class="card-subtitle mb-0">Manage IMEI-based phone inventory.</p>
            </div>
            <div>
                <a href="{{ route('admin.phone_units.create') }}" class="btn btn-primary">Add Phone</a>
            </div>
        </div>

        <div class="card-body">

            {{-- Filters --}}
            <form method="GET" action="{{ route('admin.phone_units.index') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="bm" value="{{ request('bm') }}" class="form-control"
                        placeholder="Brand / Model search">
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        @foreach(['available' => 'Available','sold'=>'Sold','reserved'=>'Reserved','returned'=>'Returned'] as $k=>$v)
                            <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <input type="text" name="imei" value="{{ request('imei') }}" class="form-control"
                        placeholder="IMEI search">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                    <a href="{{ route('admin.phone_units.index') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Phone</th>
                            <th>IMEI 1</th>
                            <th>Status</th>
                            <th>Cost</th>
                            <th>Expected Sell</th>
                            <th>Updated At</th>
                            <th class="text-center" style="width:110px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($phoneUnits as $u)
                            <tr id="phone-{{ $u->id }}">
                                <td>
                                    <h6 class="mb-0">{{ $u->product?->name ?? '-' }}</h6>
                                    <small class="text-muted">
                                        {{ $u->product?->brand ?? '-' }}{{ $u->product?->model ? ' / '.$u->product->model : '' }}
                                    </small>
                                </td>
                                <td>{{ $u->imei1 }}</td>
                                <td>
                                    @php
                                        $badge = match($u->status) {
                                            'available' => 'bg-success',
                                            'sold' => 'bg-danger',
                                            'reserved' => 'bg-warning',
                                            'returned' => 'bg-secondary',
                                            default => 'bg-light'
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($u->status) }}</span>
                                </td>
                                <td>{{ $u->purchase_cost !== null ? number_format($u->purchase_cost,2) : '-' }}</td>
                                <td>{{ $u->expected_sell_price !== null ? number_format($u->expected_sell_price,2) : '-' }}</td>
                                <td>{{ $u->updated_at->format('d M Y, h:i A') }}</td>

                                {{-- Icon actions (no background) --}}
                                <td class="text-center">
                                    <div style="display:flex; justify-content:center; gap:12px;">
                                        <a href="{{ route('admin.phone_units.show', $u) }}"
                                            style="color:#198754; font-size:18px; display:flex; align-items:center;"
                                            data-bs-toggle="tooltip" title="View">
                                            <iconify-icon icon="solar:eye-outline"></iconify-icon>
                                        </a>

                                        <a href="{{ route('admin.phone_units.edit', $u) }}"
                                            style="color:#0d6efd; font-size:18px; display:flex; align-items:center;"
                                            data-bs-toggle="tooltip" title="Edit">
                                            <iconify-icon icon="solar:pen-outline"></iconify-icon>
                                        </a>

                                        <button type="button"
                                            class="delete-phone"
                                            data-id="{{ $u->id }}"
                                            data-name="{{ $u->product?->name ?? 'Phone' }} ({{ $u->imei1 }})"
                                            style="border:none; background:none; color:#dc3545; font-size:18px; display:flex; align-items:center; padding:0;"
                                            data-bs-toggle="tooltip" title="Delete">
                                            <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No phone stock found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $phoneUnits->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.delete-phone').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.dataset.id;
                let name = this.dataset.name;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Delete: ${name}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    fetch("{{ url('admin/phone-units') }}/" + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('phone-' + id).remove();
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
