@extends('layouts.master')
@section('title', 'Pricing List')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">                 
                            <h5 class="m-0">
                                <strong><i class="fas fa-clipboard-list"></i> Pricing List</strong>
                            </h5>
                        </div>

                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <a href="{{ route('pricing-list.create') }}">
                                <button type="button" class="btn btn-outline-info mb-3">
                                    <i class="fas fa-plus"></i> Add New Pricing
                                </button>
                            </a>

                            <div class="table-responsive">
                                <table id="pricing_table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Product</th>
                                            <th>Variant</th>
                                            <th>Landed Cost</th>
                                            <th>Dealer Cost</th>
                                            <th>VAT Tax</th>
                                            <th>Model</th>
                                            <th>Manufacturer</th>
                                            <th>Brand</th>
                                            <th>Created By</th>
                                            <th>Updated By</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- DataTables will populate rows here --}}
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div> <!-- col-lg-12 -->
            </div> <!-- row -->
        </div> <!-- container-fluid -->
    </div> <!-- content -->
</div> <!-- content-wrapper -->
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function() {
    $('#pricing_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("pricing-list.data") }}',
            type: 'GET'
        },
        columns: [
            { data: 'sl' },
            { data: 'product_name' },
            { data: 'variant_name' },
            { data: 'landed_cost' },
            { data: 'dealer_cost' },
            {
                data: 'vat_tax',
                render: function(data) {
                    return data ? 'Include' : 'Exclude';
                }
            },
            { data: 'model' },
            { data: 'manufacturer' },
            { data: 'brand' },
            { data: 'creator_name' },
            { data: 'updater_name' },
            {
                data: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
        ],
        responsive: true,
        autoWidth: false,
        ordering: true,
        pageLength: 25,
    });
});
</script>
@endsection
