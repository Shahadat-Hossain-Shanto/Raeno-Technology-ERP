@extends('layouts.master')
@section('title', 'RAENO :: Retailer Stock Data')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-list-alt"></i> Retailer Stock Data</strong></h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="retailDataTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
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
                                    <th>Receive Date</th>
                                    <th>Sell Date</th>
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

{{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<script>
    $(document).ready(function () {
        $('#retailDataTable').DataTable({
            ajax: {
                url: '{{ route('retailer.stock.data') }}',
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
                    data: 'retail_status',
                    render: function(data) {
                        switch (data) {
                            case 0:
                                return '<span class="badge bg-danger" style="min-width: 75px;">Sold</span>';
                            case 1:
                                return '<span class="badge bg-success" style="min-width: 75px;">In Stock</span>';
                            case 2:
                                return '<span class="badge bg-warning" style="min-width: 75px;">Returned</span>';
                            default:
                                return '<span class="badge bg-secondary">Unknown</span>';
                        }
                    }
                },
                { data: 'distributor_out', },
                { data: 'retail_out', },
                { data: 'retail_name', name: 'retail_name' }
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
