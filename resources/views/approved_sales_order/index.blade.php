@extends('layouts.master')
@section('title', 'Approved Sales Order')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
               
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0">
                                <strong><i class="fas fa-list-alt"></i> APPROVED SALES ORDER</strong>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            

                           <div class="row">
                               <!-- Requisition ID Filter -->
                               <div class="col-md-6 mb-2">
                                   <label for="filterRequisitionId">Filter by Requisition ID</label>
                                   <select class="selectpicker form-control" id="filterRequisitionId" data-live-search="true">
                                       <option value="">Select Requisition ID</option>
                                       @foreach ($allRequisitions as $allRequisition)
                                           <option value="{{ $allRequisition->requisition_id }}">{{ $allRequisition->requisition_id }}</option>
                                       @endforeach
                                   </select>
                               </div>
                           
                              <!-- Start Date Filter -->
                               <div class="col-md-3 mb-2">
                                   <label for="filterStartDate">Start Date</label>
                                   <input type="date" class="form-control" id="filterStartDate">
                               </div>
                               
                               <!-- End Date Filter -->
                               <div class="col-md-3 mb-2">
                                   <label for="filterEndDate">End Date</label>
                                   <input type="date" class="form-control" id="filterEndDate">
                               </div>

                           </div>


                            <div class="pt-3">
                                <div class="table-responsive">
                                    <table id="requisition_table" class="display" width="100%">
                                        <thead>
                                            <tr>
                                                <th>SL#</th>
                                                <th>Requisition ID</th>
                                                <th>Date</th>
                                                <th>Distributor Name</th>
                                                <th>Address</th>
                                                <th>Mobile</th>
                                                <th>Quantity</th>
                                                <th>Amount</th>
                                                <th>Rebate</th>
                                                <th>Total Amount</th>                                               
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                        </div> <!-- Card-body -->
                    </div> <!-- Card -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function () {
    $('.selectpicker').selectpicker();

    const table = $('#requisition_table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: '{{ route("approved-sales-order.getData") }}',
            type: 'GET',
            data: function (d) {
                d.requisition_id = $('#filterRequisitionId').val();
                d.start_date = $('#filterStartDate').val();
                d.end_date = $('#filterEndDate').val();

            }
        },
        columns: [
            { data: 'sl' },
            { data: 'requisition_id' },
            { data: 'requisition_date' },
            { data: 'name' },
            { data: 'address' },
            { data: 'mobile' },
            { data: 'quantity' },
            { data: 'amount' },
            { data: 'rebate' },
            { data: 'total_amount' },
            { data: 'status' },
            {
                data: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        responsive: true,
        autoWidth: false,
        ordering: true,
        pageLength: 25,
    });

   $('#filterRequisitionId, #filterStartDate, #filterEndDate').on('change', function () {
    table.ajax.reload();
   });

});
</script>
@endsection

