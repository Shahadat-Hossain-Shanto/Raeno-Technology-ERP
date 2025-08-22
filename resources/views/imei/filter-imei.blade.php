@extends('layouts.master')
@section('title', 'Filter IMEI Info')

@section('content')
<div class="content-wrapper">
    <div class="content-header py-1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <!-- Optional header -->
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong>Filter IMEI</strong></h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('filter.info.view') }}" method="GET">
                                <div class="row justify-content-start mb-3">
                                    <div class="col-md-4">
                                        <div class="input-group">
                                            <input type="text" name="imei" class="form-control" placeholder="Enter IMEI or Serial Number" required>
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            @php
                                function stateBadge($state) {
                                    if (is_null($state)) {
                                        return '<span class="badge bg-secondary">Unknown</span>';
                                    }
                                    return match((int)$state) {
                                        0 => '<span class="badge bg-success">In Stock</span>',
                                        1 => '<span class="badge bg-warning text-dark">Out for Delivery</span>',
                                        2 => '<span class="badge bg-info text-dark">Transferred</span>',
                                        3 => '<span class="badge bg-warning text-dark">Returned</span>',
                                        default => '<span class="badge bg-secondary">Unknown</span>',
                                    };
                                }
                                function retailState($state) {
                                    if (is_null($state)) {
                                        return '<span class="badge bg-secondary">Unknown</span>';
                                    }
                                    return match((int)$state) {
                                        0 => '<span class="badge bg-success">In Stock</span>',
                                        2 => '<span class="badge bg-info text-dark">Sold</span>',
                                        3 => '<span class="badge bg-warning text-dark">Returned</span>',
                                        default => '<span class="badge bg-secondary">Unknown</span>',
                                    };
                                }
                            @endphp

                            @isset($imeiInfo)
                                @if($imeiInfo)
                                    <div class="card mt-4 shadow-sm">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">IMEI Report</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row gy-4">
                                                <div class="col-md-4">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered mb-0">
                                                            <tbody>
                                                                <tr><th>IMEI 1</th><td>{{ $imeiInfo->imei_1 }}</td></tr>
                                                                <tr><th>IMEI 2</th><td>{{ $imeiInfo->imei_2 }}</td></tr>
                                                                <tr><th>Serial Number</th><td>{{ $imeiInfo->serial_number }}</td></tr>
                                                                <tr><th>Product Name</th><td>{{ $imeiInfo->product_name }}</td></tr>
                                                                <tr><th>Brand</th><td>{{ $imeiInfo->brand }}</td></tr>
                                                                <tr><th>Model</th><td>{{ $imeiInfo->model }}</td></tr>
                                                                <tr><th>Manufacturer</th><td>{{ $imeiInfo->manufacturer }}</td></tr>
                                                                <tr><th>Device Returned On</th><td>{{ $imeiInfo->product_return }}</td></tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered mb-0">
                                                            <tbody>
                                                                <tr><th>Variant</th><td>{{ $imeiInfo->variant }}</td></tr>
                                                                <tr><th>Entry User</th><td>{{ optional($imeiInfo->entryUser)->name ?? 'N/A' }}</td></tr>
                                                                <tr><th>Primary User</th><td>{{ optional($imeiInfo->primaryUser)->name ?? 'N/A' }}</td></tr>
                                                                <tr><th>Primary In</th><td>{{ $imeiInfo->primary_in }}</td></tr>
                                                                <tr><th>Primary Out</th><td>{{ $imeiInfo->primary_out }}</td></tr>
                                                                <tr><th>Primary State</th><td>{!! stateBadge($imeiInfo->primary_state) !!}</td></tr>
                                                                <tr><th>Dealer</th><td>{{ $dealer->distributor_name ?? 'N/A' }}</td></tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered mb-0">
                                                            <tbody>
                                                                <tr><th>Dealer In</th><td>{{ $imeiInfo->dealer_in }}</td></tr>
                                                                <tr><th>Dealer Out</th><td>{{ $imeiInfo->dealer_out }}</td></tr>
                                                                <tr><th>Dealer State</th><td>{!! stateBadge($imeiInfo->dealer_state) !!}</td></tr>
                                                                <tr><th>Retail</th><td>{{ $retail->retail_name ?? 'N/A' }}</td></tr>
                                                                <tr><th>Retail In</th><td>{{ $imeiInfo->retail_in }}</td></tr>
                                                                <tr><th>Retail Out</th><td>{{ $imeiInfo->retail_out }}</td></tr>
                                                                <tr><th>Retail State</th><td>{!! retailState($imeiInfo->retail_state) !!}</td></tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning mt-3">No record found for the given IMEI.</div>
                                @endif
                            @endisset
                        </div>
                    </div> <!-- Card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div>
    </div>
</div>
@endsection
