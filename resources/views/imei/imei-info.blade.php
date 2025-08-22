@extends('layouts.master')
@section('title', 'RAENO :: IMEI Info')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-list-alt"></i> IMEI Info</strong></h5>
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
                                    <th>Entry User</th>
                                    <th>Entry Date</th>
                                    <th>Primary User</th>
                                    <th>Primary In</th>
                                    <th>Primary Out</th>
                                    <th>Primary State</th>
                                    <th>Dealer</th>
                                    <th>Dealer In</th>
                                    <th>Dealer Out</th>
                                    <th>Dealer State</th>
                                    <th>Retail</th>
                                    <th>Retail In</th>
                                    <th>Retail Out</th>
                                    <th>Retail State</th>
                                    <th>Device Returned On</th>
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
            ajax: {
                url: '{{ route('info.data') }}',
                dataSrc: 'data'
            },
            responsive: true,
            columns: [
                {
                    data: null,
                    render: function(data, type, row, meta) {
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
                { data: 'entry_user.name', name: 'entry_user.name' },
                {
                    data: 'created_at',
                    render: function(data, type, row) {
                        if (data) {
                            const date = new Date(data);
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${day}-${month}-${year}`;
                        }
                        return '';
                    }
                },
                {
                    data: 'primary_user',
                    render: function(data, type, row) {
                        return data && data.name ? data.name : '';
                    },
                    name: 'primary_user.name'
                },
                {
                    data: 'primary_in',
                    render: function(data, type, row) {
                        if (!data) return '';
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: 'primary_out',
                    render: function(data, type, row) {
                        if (!data) return '';
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: 'primary_state',
                    render: function(data, type, row) {
                        switch (data) {
                            case 0:
                                return '<span class="badge badge-success">In Primary Stock</span>';
                            case 1:
                                return '<span class="badge badge-warning">Out for Delivery</span>';
                            case 2:
                                return '<span class="badge badge-info">Transferred</span>';
                            default:
                                return '<span class="badge badge-secondary">Unknown</span>';
                        }
                    }
                },
                {
                    data: 'dealer',
                    render: function(data, type, row) {
                        return data && data.distributor_name ? data.distributor_name : '';
                    },
                    name: 'dealer.distributor_name'
                },
                { data: 'dealer_in' },
                {
                    data: 'dealer_out',
                    render: function(data, type, row) {
                        if (!data) return '';
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: 'dealer_state',
                    render: function(data, type, row) {
                        switch (data) {
                            case 0:
                                return '<span class="badge badge-success">In Distributor Stock</span>';
                            case 1:
                                return '<span class="badge badge-warning">Out for Delivery</span>';
                            case 2:
                                return '<span class="badge badge-info">Transferred</span>';
                            case 3:
                                return '<span class="badge badge-warning">Returned</span>';
                            default:
                                return '<span class="badge badge-secondary">Unknown</span>';
                        }
                    }
                },
                {
                    data: 'retail',
                    render: function(data, type, row) {
                        return data && data.retail_name ? data.retail_name : '';
                    },
                    name: 'retail.retail_name'
                },
                {
                    data: 'retail_in',
                    render: function(data, type, row) {
                        if (!data) return '';
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: 'retail_out',
                    render: function(data, type, row) {
                        if (!data) return '';
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                {
                    data: 'retail_state',
                    render: function(data, type, row) {
                        switch (data) {
                            case 0:
                                return '<span class="badge badge-success">In Retail Stock</span>';
                            case 1:
                                return '<span class="badge badge-warning">Out for Delivery</span>';
                            case 2:
                                return '<span class="badge badge-info">Sold</span>';
                            case 3:
                                return '<span class="badge badge-warning">Returned</span>';
                            default:
                                return '<span class="badge badge-secondary">Unknown</span>';
                        }
                    }
                },
                {
                    data: 'product_return',
                    render: function(data, type, row) {
                        if (!data) return '';
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                },
                // {
                //     data: null,
                //     render: function(row) {
                //         return `
                //             <a href="/galleries/${row.id}/edit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                //             <form action="/galleries/${row.id}" method="POST" style="display:inline;">
                //                 @csrf
                //                 @method('DELETE')
                //                 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                //                     <i class="fas fa-trash"></i>
                //                 </button>
                //             </form>`;
                //     },
                //     orderable: false,
                //     searchable: false
                // }
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
