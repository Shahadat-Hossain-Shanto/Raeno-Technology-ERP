@extends('layouts.master')
@section('title', 'Approved Requisitions')

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
                                <strong><i class="fas fa-list-alt"></i> APPROVED REQUISITIONS</strong>
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
                               <div class="col-md-3 mb-2">
                                   <label for="filterRequisitionId">Filter by Requisition ID</label>
                                   <select class="selectpicker form-control" id="filterRequisitionId" data-live-search="true">
                                       <option value="">Select Requisition ID</option>
                                       @foreach ($allRequisitions as $allRequisition)
                                           <option value="{{ $allRequisition->requisition_id }}">{{ $allRequisition->requisition_id }}</option>
                                       @endforeach
                                   </select>
                               </div>

                               <!-- Distributor Filter -->
                               @php
                                   $user = auth()->user();
                               @endphp
                               
                               @if(!$user->distributor)
                                   <!-- Distributor Filter -->
                                   <div class="col-md-3 mb-2">
                                       <label for="filterDistributorId">Filter by Distributor</label>
                                       <select class="selectpicker form-control" id="filterDistributorId" data-live-search="true">
                                           <option value="">Select Distributor</option>
                                           @foreach ($distributors as $distributor)
                                               <option value="{{ $distributor->id }}">{{ $distributor->distributor_name }}</option>
                                           @endforeach
                                       </select>
                                   </div>
                               @endif


                            
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
                                                <th>Posting Status</th>
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
            url: '{{ route("approved-requisitions.getData") }}',
            type: 'GET',
            data: function (d) {
                d.requisition_id = $('#filterRequisitionId').val();
                d.distributor_id = $('#filterDistributorId').val();
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
            { data: 'posting' },
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

   $('#filterRequisitionId,#filterDistributorId, #filterStartDate, #filterEndDate').on('change', function () {
    table.ajax.reload();
   });

});
</script>
@endsection

