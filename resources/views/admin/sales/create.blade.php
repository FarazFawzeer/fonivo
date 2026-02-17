@extends('layouts.vertical', ['subtitle' => 'Sales Create'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Sales', 'subtitle' => 'Create'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Sales Invoice</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="salesForm" action="{{ route('admin.sales.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Customer</label>
                        <select name="customer_id" class="form-select" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sale Date</label>
                        <input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                {{-- Phones --}}
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Phones (Pick Available)</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addPhoneRow()">Add Phone</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width:320px;">Available Phone</th>
                                    <th>Sell Price</th>
                                    <th style="width:60px;" class="text-center">#</th>
                                </tr>
                            </thead>
                            <tbody id="phoneRows"></tbody>
                        </table>
                    </div>
                </div>

                {{-- Accessories --}}
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Accessories</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addAccRow()">Add Accessory</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width:260px;">Accessory Product</th>
                                    <th>Qty</th>
                                    <th>Unit Sell Price</th>
                                    <th>Line Total</th>
                                    <th style="width:60px;" class="text-center">#</th>
                                </tr>
                            </thead>
                            <tbody id="accRows"></tbody>
                        </table>
                    </div>
                </div>

                {{-- Total + payment --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total</label>
                        <input type="text" id="total" class="form-control" value="0.00" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pay Now (Optional)</label>
                        <input type="number" step="0.01" min="0" name="paid_now" class="form-control" value="0">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Payment Method</label>
                        <select name="pay_method" class="form-select">
                            <option value="cash" selected>Cash</option>
                            <option value="bank">Bank</option>
                            <option value="card">Card</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Reference No (Optional)</label>
                        <input type="text" name="reference_no" class="form-control" placeholder="Txn / Slip No">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Payment Note (Optional)</label>
                        <input type="text" name="pay_note" class="form-control" placeholder="Advance payment">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Invoice Note (Optional)</label>
                    <textarea name="note" class="form-control" rows="2"></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Save Sale</button>
                </div>

                <input type="hidden" name="phones_json" id="phones_json">
                <input type="hidden" name="accessories_json" id="accessories_json">
            </form>
        </div>
    </div>

    <script>
        const availablePhones = @json($availablePhones);
        const accessoryProducts = @json($accessoryProducts);

        function money(n){ return (Number(n||0)).toFixed(2); }

        function addPhoneRow() {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select class="form-select form-select-sm phone_unit" required>
                        <option value="">Select Available Phone</option>
                        ${availablePhones.map(u => {
                            const p = u.product || {};
                            const label = `${p.name || 'Phone'} | ${p.brand || '-'} ${p.model || ''} | IMEI: ${u.imei1}`;
                            return `<option value="${u.id}">${label}</option>`;
                        }).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" min="0" class="form-control form-control-sm phone_price" value="0"
                        oninput="recalcTotal()">
                </td>
                <td class="text-center">
                    <button type="button" style="border:none;background:none;color:#dc3545;font-size:18px;padding:0;"
                        onclick="this.closest('tr').remove();recalcTotal();">
                        <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                    </button>
                </td>
            `;
            document.getElementById('phoneRows').appendChild(tr);
            recalcTotal();
        }

        function addAccRow() {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select class="form-select form-select-sm acc_product" required>
                        <option value="">Select Accessory</option>
                        ${accessoryProducts.map(p => `<option value="${p.id}">${p.name}${p.sku ? ' - '+p.sku : ''}</option>`).join('')}
                    </select>
                </td>
                <td><input type="number" min="1" class="form-control form-control-sm acc_qty" value="1" oninput="recalcRow(this);recalcTotal();"></td>
                <td><input type="number" step="0.01" min="0" class="form-control form-control-sm acc_price" value="0" oninput="recalcRow(this);recalcTotal();"></td>
                <td class="acc_total text-end">0.00</td>
                <td class="text-center">
                    <button type="button" style="border:none;background:none;color:#dc3545;font-size:18px;padding:0;"
                        onclick="this.closest('tr').remove();recalcTotal();">
                        <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                    </button>
                </td>
            `;
            document.getElementById('accRows').appendChild(tr);
            recalcTotal();
        }

        function recalcRow(el){
            const tr = el.closest('tr');
            const qty = Number(tr.querySelector('.acc_qty').value || 0);
            const price = Number(tr.querySelector('.acc_price').value || 0);
            tr.querySelector('.acc_total').innerText = money(qty * price);
        }

        function recalcTotal(){
            let total = 0;

            document.querySelectorAll('#phoneRows tr').forEach(tr => {
                total += Number(tr.querySelector('.phone_price').value || 0);
            });

            document.querySelectorAll('#accRows tr').forEach(tr => {
                const qty = Number(tr.querySelector('.acc_qty').value || 0);
                const price = Number(tr.querySelector('.acc_price').value || 0);
                total += (qty * price);
            });

            document.getElementById('total').value = money(total);
        }

        function buildJson(){
            const phones = [];
            document.querySelectorAll('#phoneRows tr').forEach(tr => {
                phones.push({
                    phone_unit_id: tr.querySelector('.phone_unit').value,
                    unit_sell_price: tr.querySelector('.phone_price').value
                });
            });

            const accessories = [];
            document.querySelectorAll('#accRows tr').forEach(tr => {
                accessories.push({
                    product_id: tr.querySelector('.acc_product').value,
                    qty: tr.querySelector('.acc_qty').value,
                    unit_sell_price: tr.querySelector('.acc_price').value
                });
            });

            document.getElementById('phones_json').value = JSON.stringify(phones);
            document.getElementById('accessories_json').value = JSON.stringify(accessories);
        }

        document.getElementById('salesForm').addEventListener('submit', function(e){
            e.preventDefault();
            buildJson();

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                }
            })
            .then(r => r.json())
            .then(data => {
                const box = document.getElementById('message');
                if(data.success){
                    box.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    if(data.redirect){ window.location.href = data.redirect; }
                }else{
                    let errors = Object.values(data.errors || {}).flat().join('<br>');
                    box.innerHTML = `<div class="alert alert-danger">${errors || 'Something went wrong!'}</div>`;
                }
            })
            .catch(err => {
                document.getElementById('message').innerHTML = `<div class="alert alert-danger">Error: ${err}</div>`;
            });
        });
    </script>
@endsection
