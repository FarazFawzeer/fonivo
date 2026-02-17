@extends('layouts.vertical', ['subtitle' => 'Purchase Create'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Purchases', 'subtitle' => 'Create'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Create Purchase Invoice</h5>
        </div>

        <div class="card-body">
            <div id="message"></div>

            <form id="purchaseForm" action="{{ route('admin.purchases.store') }}" method="POST">
                @csrf

                {{-- Supplier + date --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                {{-- Items: Phones --}}
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">y\
                        <h6 class="mb-0">Phones (IMEI)</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addPhoneRow()">Add Phone</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width:260px;">Phone Product</th>
                                    <th>IMEI 1</th>
                                    <th>IMEI 2</th>
                                    <th>Cost</th>
                                    <th style="width:60px;" class="text-center">#</th>
                                </tr>
                            </thead>
                            <tbody id="phoneRows"></tbody>
                        </table>
                    </div>
                </div>

                {{-- Items: Accessories --}}
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Accessories (Qty)</h6>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addAccRow()">Add Accessory</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width:260px;">Accessory Product</th>
                                    <th>Qty</th>
                                    <th>Unit Cost</th>
                                    <th>Line Total</th>
                                    <th style="width:60px;" class="text-center">#</th>
                                </tr>
                            </thead>
                            <tbody id="accRows"></tbody>
                        </table>
                    </div>
                </div>

                {{-- Totals + initial payment --}}
             <div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Subtotal</label>
        <input type="text" id="subtotal" class="form-control" value="0.00" readonly>
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
        <input type="text" name="reference_no" class="form-control" placeholder="Bank slip / Txn ID">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Payment Note (Optional)</label>
        <input type="text" name="pay_note" class="form-control" placeholder="Advance payment">
    </div>
</div>

                <div class="mb-3">
                    <label class="form-label">Payment Note (Optional)</label>
                    <input type="text" name="pay_note" class="form-control" placeholder="Ex: Advance payment">
                </div>

                <div class="mb-3">
                    <label class="form-label">Invoice Note (Optional)</label>
                    <textarea name="note" class="form-control" rows="2"></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary" type="submit">Save Purchase</button>
                </div>

                {{-- hidden json --}}
                <input type="hidden" name="phones_json" id="phones_json">
                <input type="hidden" name="accessories_json" id="accessories_json">
            </form>
        </div>
    </div>

    <script>
        const phoneProducts = @json($phoneProducts);
        const accessoryProducts = @json($accessoryProducts);

        function money(n){ return (Number(n||0)).toFixed(2); }

        function addPhoneRow() {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select class="form-select form-select-sm phone_product" required>
                        <option value="">Select Phone</option>
                        ${phoneProducts.map(p => `<option value="${p.id}">${p.name}${p.brand ? ' - '+p.brand : ''}${p.model ? ' / '+p.model : ''}</option>`).join('')}
                    </select>
                </td>
                <td><input type="text" class="form-control form-control-sm imei1" placeholder="IMEI 1" required></td>
                <td><input type="text" class="form-control form-control-sm imei2" placeholder="IMEI 2"></td>
                <td><input type="number" step="0.01" min="0" class="form-control form-control-sm phone_cost" value="0" oninput="recalcSubtotal()"></td>
                <td class="text-center">
                    <button type="button" style="border:none;background:none;color:#dc3545;font-size:18px;padding:0;" onclick="this.closest('tr').remove();recalcSubtotal();">
                        <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                    </button>
                </td>
            `;
            document.getElementById('phoneRows').appendChild(tr);
            recalcSubtotal();
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
                <td><input type="number" min="1" class="form-control form-control-sm acc_qty" value="1" oninput="recalcRow(this);recalcSubtotal();"></td>
                <td><input type="number" step="0.01" min="0" class="form-control form-control-sm acc_cost" value="0" oninput="recalcRow(this);recalcSubtotal();"></td>
                <td class="acc_total text-end">0.00</td>
                <td class="text-center">
                    <button type="button" style="border:none;background:none;color:#dc3545;font-size:18px;padding:0;" onclick="this.closest('tr').remove();recalcSubtotal();">
                        <iconify-icon icon="solar:trash-bin-trash-outline"></iconify-icon>
                    </button>
                </td>
            `;
            document.getElementById('accRows').appendChild(tr);
            recalcSubtotal();
        }

        function recalcRow(el){
            const tr = el.closest('tr');
            const qty = Number(tr.querySelector('.acc_qty').value || 0);
            const cost = Number(tr.querySelector('.acc_cost').value || 0);
            tr.querySelector('.acc_total').innerText = money(qty * cost);
        }

        function recalcSubtotal(){
            let sub = 0;

            // phones
            document.querySelectorAll('#phoneRows tr').forEach(tr => {
                sub += Number(tr.querySelector('.phone_cost').value || 0);
            });

            // accessories
            document.querySelectorAll('#accRows tr').forEach(tr => {
                const qty = Number(tr.querySelector('.acc_qty').value || 0);
                const cost = Number(tr.querySelector('.acc_cost').value || 0);
                sub += (qty * cost);
            });

            document.getElementById('subtotal').value = money(sub);
        }

        function buildJson(){
            const phones = [];
            document.querySelectorAll('#phoneRows tr').forEach(tr => {
                phones.push({
                    product_id: tr.querySelector('.phone_product').value,
                    imei1: tr.querySelector('.imei1').value,
                    imei2: tr.querySelector('.imei2').value,
                    unit_cost: tr.querySelector('.phone_cost').value
                });
            });

            const accessories = [];
            document.querySelectorAll('#accRows tr').forEach(tr => {
                accessories.push({
                    product_id: tr.querySelector('.acc_product').value,
                    qty: tr.querySelector('.acc_qty').value,
                    unit_cost: tr.querySelector('.acc_cost').value
                });
            });

            document.getElementById('phones_json').value = JSON.stringify(phones);
            document.getElementById('accessories_json').value = JSON.stringify(accessories);
        }

        document.getElementById('purchaseForm').addEventListener('submit', function(e){
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
                    if(data.redirect){
                        window.location.href = data.redirect;
                    }
                }else{
                    let errors = Object.values(data.errors || {}).flat().join('<br>');
                    box.innerHTML = `<div class="alert alert-danger">${errors || 'Something went wrong!'}</div>`;
                }
            })
            .catch(err => {
                document.getElementById('message').innerHTML = `<div class="alert alert-danger">Error: ${err}</div>`;
            });
        });

        // start with one row each (optional)
        // addPhoneRow();
        // addAccRow();
    </script>
@endsection
