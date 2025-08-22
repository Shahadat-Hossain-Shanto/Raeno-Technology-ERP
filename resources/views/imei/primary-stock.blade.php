@extends('layouts.master')
@section('title', 'RAENO :: Primary Stock')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-list-alt"></i> Primary Stock</strong></h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="imeiTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>IMEI 1</th>
                                    <th>IMEI 2</th>
                                    <th>Serial Number</th>
                                    <th>Product Name</th>
                                    <th>Brand</th>
                                    <th>Manufacturer</th>
                                    <th>Model</th>
                                    <th>Variant</th>
                                    <th>Stock</th>
                                    <th>Created By</th>
                                    <th>Primary In Date</th>
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

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $('#imeiTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route('primary.stock.data') }}',
                type: 'GET',
            },
            columns: [
                {
                    data: null,
                    searchable: false,
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'imei_1', name: 'imei_1' },
                { data: 'imei_2', name: 'imei_2' },
                { data: 'serial_number', name: 'serial_number' },
                { data: 'product_name', name: 'product_name' },
                { data: 'brand', name: 'brand' },
                { data: 'manufacturer', name: 'manufacturer' },
                { data: 'model', name: 'model' },
                { data: 'variant', name: 'variant' },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        return data == 1
                            ? '<span class="badge bg-success">In Stock</span>'
                            : '<span class="badge bg-danger">Stock Out</span>';
                    }
                },
                {
                    data: 'primary_user.name',
                    name: 'primaryUser.name',
                    orderable: false,
                    searchable: false,
                    defaultContent: '',
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function (data) {
                        if (data) {
                            const date = new Date(data);
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${day}-${month}-${year}`;
                        }
                        return '';
                    }
                }
            ]
        });

        // Auto-hide success message
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
