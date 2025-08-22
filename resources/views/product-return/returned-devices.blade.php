@extends('layouts.master')
@section('title', 'RAENO :: Returned Devices')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-list-alt"></i> Returned Devices</strong></h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="returnedDataTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
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
                                    <th>Order ID</th>
                                    <th>Distributor</th>
                                    <th>Return ID</th>
                                    <th>Return Date</th>
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
        $('#returnedDataTable').DataTable({
            ajax: {
                url: '{{ route('returned.data') }}',
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
                { data: 'order_id' },
                {
                    data: 'distributor.distributor_name',
                    defaultContent: '',
                    render: function (data, type, row) {
                        return data ?? 'N/A';
                    }
                },
                { data: 'return_id' },
                {
                    data: 'created_at',
                    render: function (data) {
                        if (!data) return '';
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
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
