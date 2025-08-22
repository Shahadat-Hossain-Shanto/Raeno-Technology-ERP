@extends('layouts.master')
@section('title', 'Distributor Requisitions')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="m-0"><strong><i class="fas fa-list-alt"></i> DISTRIBUTOR REQUISITIONS</strong></h5>
                            
                        </div>

                      

                        <div class="card-body">
                                  @if(session('success'))
                          <div class="alert alert-success">
                              {{ session('success') }}
                          </div>
                          @endif
                          
                          @if(session('error'))
                              <div class="alert alert-danger">
                                  {{ session('error') }}
                              </div>
                          @endif

                              {{-- <a href="{{ url('/distributor-requisition/create') }}">
                                <button type="button" class="btn btn-outline-info mb-3">
                                    <i class="fas fa-plus"></i> Create Requisition
                                </button>
                              </a> --}}

                               <a href="{{ route('distributor_requisition.create') }}">
                                <button type="button" class="btn btn-outline-info mb-3">
                                    <i class="fas fa-plus"></i> Create Requisition
                                </button>
                               </a>
                            <!-- Filter Buttons -->
                            <div class="mb-3">
                                <div class="btn-group" role="group">
                                    <button id="showPending" class="btn btn-sm filter-btn btn-primary active">
                                        <i class="fas fa-clock"></i> Pending List
                                    </button>
                                    <button id="showApproved" class="btn btn-sm filter-btn btn-outline-success">
                                        <i class="fas fa-check"></i> Approved List
                                    </button>
                                    <button id="showCanceled" class="btn btn-sm filter-btn btn-outline-warning">
                                        <i class="fas fa-times-circle"></i> Canceled List
                                    </button>
                                </div>
                            </div>

                            <!-- Requisition Table -->
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
                                            <th>Note</th>
                                            <th>Status</th>
                                            <th>Posting</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script>
let filterStatus = '0';

function loadTable(filter = '0') {
    $('#requisition_table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: '{{ route("distributor_requisition.getData") }}',
            type: 'GET',
            data: { status: filter }
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
            { data: 'requisition_note' },
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
}

$(document).ready(function () {
    loadTable(filterStatus);

    $('#showPending').click(function () {
        filterStatus = '0';
        loadTable(filterStatus);
    });

    $('#showApproved').click(function () {
        filterStatus = '1';
        loadTable(filterStatus);
    });

    $('#showCanceled').click(function () {
        filterStatus = '2';
        loadTable(filterStatus);
    });

   $('.filter-btn').click(function () {
    // Reset all buttons
    $('#showPending')
        .removeClass('btn-primary')
        .addClass('btn-outline-primary');

    $('#showApproved')
        .removeClass('btn-success')
        .addClass('btn-outline-success');

    $('#showCanceled')
        .removeClass('btn-warning')
        .addClass('btn-outline-warning');

    // Activate the clicked one
    if ($(this).attr('id') === 'showPending') {
        $(this).removeClass('btn-outline-primary').addClass('btn-primary');
    } else if ($(this).attr('id') === 'showApproved') {
        $(this).removeClass('btn-outline-success').addClass('btn-success');
    } else if ($(this).attr('id') === 'showCanceled') {
        $(this).removeClass('btn-outline-warning').addClass('btn-warning');
    }

    $('.filter-btn').removeClass('active');
    $(this).addClass('active');
    });


    // Cancel handler
    // $('#requisition_table').on('click', '.cancel-btn', function () {
    //     const id = $(this).data('id');
    //     if (confirm('Are you sure you want to cancel this requisition?')) {
    //         $.ajax({
    //             url: `/distributor-requisition/${id}/cancel`,
    //             type: 'POST',
    //             data: {
    //                 _token: '{{ csrf_token() }}'
    //             },
    //             success: function (response) {
    //                 alert(response.message);
    //                 loadTable(filterStatus); // Reload with current filter
    //             },
    //             error: function () {
    //                 alert('Failed to cancel requisition.');
    //             }
    //         });
    //     }
    // });
});
</script>
@endsection





