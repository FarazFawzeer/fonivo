@extends('layouts.vertical', ['subtitle' => 'Due Report'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Due'])

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Due Report</h5>
            <p class="card-subtitle mb-0">Customers & suppliers who still have balance.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Customers Due (balance &gt; 0)</h6>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Customer</th>
                                <th class="text-end">Sales</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customerDue as $c)
                                <tr>
                                    <td><b>{{ $c->name }}</b></td>
                                    <td class="text-end">{{ number_format((float)($c->sales_total ?? 0),2) }}</td>
                                    <td class="text-end">{{ number_format((float)($c->paid_total ?? 0),2) }}</td>
                                    <td class="text-end text-danger"><b>{{ number_format((float)($c->balance ?? 0),2) }}</b></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No due customers.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Suppliers Due (balance &gt; 0)</h6>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-centered">
                        <thead class="table-light">
                            <tr>
                                <th>Supplier</th>
                                <th class="text-end">Purchases</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplierDue as $s)
                                <tr>
                                    <td><b>{{ $s->name }}</b></td>
                                    <td class="text-end">{{ number_format((float)($s->purchase_total ?? 0),2) }}</td>
                                    <td class="text-end">{{ number_format((float)($s->paid_total ?? 0),2) }}</td>
                                    <td class="text-end text-danger"><b>{{ number_format((float)($s->balance ?? 0),2) }}</b></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted">No due suppliers.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
