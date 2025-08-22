@extends('layouts.master')
@section('title', 'RAENO :: Sent Deliveries')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="card card-primary">
            <div class="card-header d-flex justify-content-between" style="height: 49px;">
                <h5><strong><i class="fas fa-list-alt"></i> Sent Deliveries</strong></h5>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="receiveTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Order Date</th>
                                <th>Approved Date</th>
                                <th>Delivery Date</th>
                                <th>Delivery ID</th>
                                <th>Ordered Qty</th>
                                <th>Delivery Qty</th>
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
        $('#receiveTable').DataTable({
            ajax: {
                url: '{{ route('deliveries.data') }}',
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
                { data: 'order_id' },
                {
                    data: 'requisition.requisition_date',
                    render: function(data) {
                        if (!data) return '';
                        const date = new Date(data);
                        return `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getFullYear()}`;
                    }
                },
                {
                    data: 'requisition.sales_approved_date',
                    render: function(data) {
                        if (!data) return '';
                        const date = new Date(data);
                        return `${date.getDate().toString().padStart(2, '0')}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getFullYear()}`;
                    }
                },
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
                },
                { data: 'id' },
                { data: 'requisition.quantity' },
                { data: 'quantity' },
                { data: 'distributor_id' },
                { data: 'distributor_name' },
                { data: 'mobile' },
                { data: 'medium' },
                { data: 'note' }
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
