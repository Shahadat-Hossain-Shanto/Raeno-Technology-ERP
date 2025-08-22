@extends('layouts.master')
@section('title', 'RAENO :: Distributor Stock Data')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-list-alt"></i> Distributor Stock Data</strong></h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="distributorDataTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>IMEI 1</th>
                                    <th>IMEI 2</th>
                                    <th>Serial</th>
                                    <th>Product</th>
                                    <th>Brand</th>
                                    <th>Manufacturer</th>
                                    <th>Model</th>
                                    <th>Variant</th>
                                    {{-- <th>Price</th> --}}
                                    <th>Status</th>
                                    <th>Distributor ID</th>
                                    <th>Distributor Name</th>
                                    <th>Received By</th>
                                    <th>Order ID</th>
                                    <th>Delivery ID</th>
                                    <th>Order Receive Date</th>
                                    <th>Distributor Out</th>
                                    <th>Retailer</th>
                                    {{-- <th>Actions</th> --}}
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#distributorDataTable').DataTable({
            ajax: {
                url: '{{ route('distributorIn.data') }}',
                dataSrc: 'data'
            },
            responsive: true,
            columns: [
                { // Index column
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'imei_1' },
                { data: 'imei_2' },
                { data: 'serial_number' },
                { data: 'product_name' },
                { data: 'brand' },
                { data: 'manufacturer' },
                { data: 'model' },
                { data: 'variant' },
                // { data: 'price' },
                {
                    data: 'status',
                    render: function(data) {
                        switch (data) {
                            case 0:
                                return '<span class="badge bg-danger">Out of stock</span>';
                            case 1:
                                return '<span class="badge bg-success">In Stock</span>';
                            case 2:
                                return '<span class="badge bg-warning">Returned</span>';
                            default:
                                return '<span class="badge bg-secondary">Unknown</span>';
                        }
                    }
                },
                { data: 'distributor_id' },
                { data: 'distributor_name' },
                {
                    data: 'received_by',
                    render: function(data, type, row) {
                        return row.received_by_user?.name ?? data;
                    }
                },
                { data: 'order_id' },
                { data: 'delivery_id' },
                {
                    data: 'created_at',
                    title: 'Order Receive Date',
                    render: function (data) {
                        if (!data) return '';
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: 'distributor_out',
                    render: function(data, type, row) {
                        return data ? data : 'N/A';
                    }
                },
                {
                    data: 'retail_id',
                    render: function(data, type, row) {
                        return row.retail && row.retail.retail_name ? row.retail.retail_name : '<span class="text-muted">N/A</span>';
                    }
                }
            ]
        });

        const alertBox = document.querySelector('.alert-dismissible');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('fade');
                alertBox.style.display = 'none';
            }, 3000);
        }
    });
</script>
@endsection
