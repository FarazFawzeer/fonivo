@extends('layouts.vertical', ['subtitle' => 'Daily Sales Summary'])

@section('content')
    @include('layouts.partials.page-title', ['title' => 'Reports', 'subtitle' => 'Daily Sales'])

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Daily Sales Summary</h5>
            <p class="card-subtitle mb-0">Totals by day based on sales invoices.</p>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('admin.reports.dailySales') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="date" name="from" class="form-control" value="{{ $from }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="to" class="form-control" value="{{ $to }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                    <a href="{{ route('admin.reports.dailySales') }}" class="btn btn-light w-100">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-centered">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th class="text-end">Sales</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($days as $d)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($d->day)->format('d M Y') }}</td>
                                <td class="text-end">{{ number_format((float)($d->total_sales ?? 0),2) }}</td>
                                <td class="text-end">{{ number_format((float)($d->total_paid ?? 0),2) }}</td>
                                <td class="text-end">{{ number_format((float)($d->total_balance ?? 0),2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">No records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $days->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
