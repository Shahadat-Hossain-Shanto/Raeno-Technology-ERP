@extends('layouts.master')
@section('title', 'RAENO :: Receive Order')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="card card-primary">
            <div class="card-header d-flex justify-content-between" style="height: 49px;">
                <h5><strong><i class="fas fa-list-alt"></i> Receive Order</strong></h5>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" id="order_id" class="form-control" placeholder="Order ID">
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="start_date" class="form-control" placeholder="Start Date">
                    </div>
                    <div class="col-md-3">
                        <input type="date" id="end_date" class="form-control" placeholder="End Date">
                    </div>
                    <div class="col-md-3">
                        <button id="filterBtn" class="btn btn-primary w-100"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="receiveTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Approved Date</th>
                                <th>Delivery ID</th>
                                <th>Ordered Qty</th>
                                <th>Delivery Qty</th>
                                <th>Status</th>
                                <th>Print</th>
                                <th>Distributor ID</th>
                                <th>Distributor Name</th>
                                <th>Mobile</th>
                                <th>Medium</th>
                                <th>Note</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Requisition Details Modal -->
<div class="modal fade" id="delivertyDetailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Details</th>
                            <th>Name</th>
                            <th>Model</th>
                            <th>Variant</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody id="detailTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        let table = $('#receiveTable').DataTable({
            processing: true,
            serverSide: false, // client-side pagination (you're sending all filtered data)
            ajax: {
                url: '{{ route('receive.data') }}',
                data: function (d) {
                    d.order_id = $('#order_id').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                }
            },
            responsive: true,
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'order_id' },
                {
                    data: 'requisition.requisition_date',
                    render: function (data) {
                        if (!data) return '';
                        const date = new Date(data);
                        return `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getFullYear()}`;
                    }
                },
                {
                    data: 'requisition.operations_approved_date',
                    render: function (data) {
                        if (!data) return '';
                        const date = new Date(data);
                        return `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getFullYear()}`;
                    }
                },
                { data: 'id' },
                { data: 'requisition.quantity' },
                { data: 'quantity' },
                {
                    data: 'status',
                    render: function (data) {
                        switch (data) {
                            case 1: return '<span class="badge badge-warning">Partial</span>';
                            case 2: return '<span class="badge badge-success">Received</span>';
                            default: return '';
                        }
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return `
                            <div class="d-flex justify-content-center">
                                <a href="/received-details/${row.id}" class="btn btn-sm btn-info" title="Details" style="min-width: 90px;">
                                    Print
                                </a>
                            </div>
                        `;
                    }
                },
                { data: 'distributor_id' },
                { data: 'distributor_name' },
                { data: 'mobile' },
                { data: 'medium' },
                { data: 'note' }
            ]
        });

        $('#filterBtn').on('click', function () {
            table.ajax.reload();
        });

        const alertBox = document.querySelector('.alert-dismissible');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('fade');
                alertBox.style.display = 'none';
            }, 3000);
        }
    });

    $(document).on('click', '.view-details', function () {
        const requisitionId = $(this).data('id');

        $.ajax({
            url: '/requisition-details/' + requisitionId,
            method: 'GET',
            success: function(response) {
                const tbody = $('#detailTableBody');
                tbody.empty();

                if (response.details.length > 0) {
                    response.details.forEach(function(detail) {
                        tbody.append(`
                            <tr>
                                <td>${detail.product_details}</td>
                                <td>${detail.product_name}</td>
                                <td>${detail.model}</td>
                                <td>${detail.variant}</td>
                                <td>${detail.quantity}</td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append('<tr><td colspan="9" class="text-center">No details found.</td></tr>');
                }

                $('#deliveryDetailModal').modal('show');
            }
        });
    });
</script>
@endsection
