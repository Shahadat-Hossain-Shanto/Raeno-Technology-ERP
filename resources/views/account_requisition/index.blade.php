@extends('layouts.master')
@section('title', 'Account Requisitions')

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

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong><i class="fas fa-list-alt"></i> ACC REQUISITIONS</strong></h5>
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
                
                            <div class="pt-3">
                               <div class="mb-3 btn-group" role="group">
                                    <button id="showPending" class="btn btn-primary btn-sm filter-btn active">
                                        <i class="fas fa-clock"></i> Pending List
                                    </button>
                                    <button id="showApproved" class="btn btn-outline-success btn-sm filter-btn">
                                        <i class="fas fa-check"></i> Approved List
                                    </button>
                                </div>

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
                                                <th>Distributor Note</th>
                                                <th>Note</th>                                               
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
let filterStatus = '0'; // Default to pending

function loadTable(filter = '0') {
    $('#requisition_table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: '{{ route("account-requisition.getData") }}',
            type: 'GET',
            data: {
                status: filter
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
            { data: 'requisition_note' },
            { data: 'accounts_approved_note' },
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
    loadTable(filterStatus); // Load pending list by default

    $('#showPending').click(function () {
        filterStatus = '0';
        loadTable(filterStatus);
    });

    $('#showApproved').click(function () {
        filterStatus = '1';
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

    // Highlight only the clicked one
    if ($(this).attr('id') === 'showPending') {
        $(this).removeClass('btn-outline-primary').addClass('btn-primary');
    } else if ($(this).attr('id') === 'showApproved') {
        $(this).removeClass('btn-outline-success').addClass('btn-success');
    }

    // Remove .active from all, then set it on this
    $('.filter-btn').removeClass('active');
    $(this).addClass('active');
});

    
});

</script>

@endsection




