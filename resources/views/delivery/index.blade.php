@extends('layouts.master')
@section('title', 'RAENO :: Delivery Request')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="card card-primary">
            <div class="card-header d-flex justify-content-between" style="height: 49px;">
                <h5><strong><i class="fas fa-list-alt"></i> Delivery Request</strong></h5>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="requisitionTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Distributor ID</th>
                                <th>Distributor Name</th>
                                <th>Address</th>
                                <th>Mobile</th>
                                <th>Qty</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Requisition Details Modal -->
<div class="modal fade" id="requisitionDetailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
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
        $('#requisitionTable').DataTable({
            ajax: {
                url: '{{ route('delivery.data') }}',
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
                { data: 'requisition_id' },
                {
                    data: 'requisition_date',
                    render: function(data) {
                        const date = new Date(data);
                        return `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth()+1).toString().padStart(2, '0')}-${date.getFullYear()}`;
                    }
                },
                { data: 'distributor_id' },
                { data: 'name' },
                { data: 'address' },
                { data: 'mobile' },
                { data: 'quantity' },
                {
                    data: 'delivery_status',
                    render: function(data, type, row) {
                        switch (data) {
                            case 0:
                                return '<span class="badge badge-success">Pending</span>';
                            case 1:
                                return '<span class="badge badge-warning">Partial</span>';
                            case 2:
                                return '<span class="badge badge-info">Delivered</span>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let actionHtml = '';

                        // Always show View button
                        actionHtml += `
                            <a href="#" class="btn btn-sm btn-info view-details" data-id="${row.requisition_id}" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        `;

                        // Show "Send" button only if delivery_status is NOT 2
                        if (row.delivery_status !== 2) {
                            actionHtml += `
                                <a href="/delivery-sent/${row.requisition_id}" class="btn btn-sm btn-success" title="Send">
                                    <i class="fas fa-paper-plane"></i>
                                </a>
                            `;
                        }

                        // Show "Details" button only if delivery_status is NOT 0
                        if (row.delivery_status !== 0) {
                            actionHtml += `
                                <a href="/delivery-details/${row.requisition_id}" class="btn btn-sm btn-primary" title="Details">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            `;
                        }

                        return actionHtml;
                    },
                    orderable: false,
                    searchable: false
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

                $('#requisitionDetailModal').modal('show');
            }
        });
    });
</script>
@endsection
