@extends('layouts.master')
@section('title', 'Distributor')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- Optional header -->
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong><i class="fas fa-users"></i> Distributor</strong></h5>
                        </div>

                        <div class="card-body">

                            @if(session('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <a href="{{ route('distributor.create') }}">
                                <button type="button" class="btn btn-outline-info mb-3">
                                    <i class="fas fa-plus"></i> Add Distributor
                                </button>
                            </a>

                            <div class="table-responsive">
                                <table id="distributor_table" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Distributor Name</th>
                                            <th>Owner Name</th>
                                            <th>NID</th>
                                            <th>Contact No</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Business Type</th>
                                            <th>District</th>
                                            <th>Territory</th>
                                            <th>User Map</th>
                                            <th>Trade License No</th>
                                            <th>Trade License Validity</th>
                                            <th>TIN</th>
                                            <th>Bank Name</th>
                                            <th>Branch</th>
                                            <th>Account Name</th>
                                            <th>Account No</th>
                                            <th>Existing Brands</th>
                                            <th>Actions</th>
                                         </tr>
                                     </thead>

                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->

                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Map Modal -->
<div class="modal fade" id="userMapModal" tabindex="-1" aria-labelledby="userMapModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userMapModalLabel">Distributor Users</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="userMapContent">Loading...</div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
$(document).ready(function() {
    $('#distributor_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("distributor.data") }}',
            type: 'GET'
        },
        columns: [
            { data: 'id' },
            { data: 'distributor_name' },
            { data: 'owner_name' },
            { data: 'nid' },
            { data: 'contact_no' },
            { data: 'email' },
            { data: 'address' },
            { data: 'business_type' },
            { data: 'district_id' },
            { data: 'territory_id'},
            { // New User Map Column
                data: null,
                render: function(row) {
                    return `
                        <button class="btn btn-info btn-sm user-map-btn" data-distributor-id="${row.id}">
                            <i class="fas fa-users"></i> User Map
                        </button>
                    `;
                },
                orderable: false,
                searchable: false
            },
            { data: 'trade_license_no' },
            { data: 'trade_license_validity' },
            { data: 'tin' },
            { data: 'bank_name' },
            { data: 'branch' },
            { data: 'account_name' },
            { data: 'account_no' },
            { data: 'existing_distributor_brands' },
            {
                data: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        responsive: true,
        autoWidth: false,
        ordering: true,
        pageLength: 25,
    });
});

    $(document).on('click', '.user-map-btn', function () {
        const distributorId = $(this).data('distributor-id');

        $('#userMapContent').html('Loading...');
        $('#userMapModal').modal('show');

        $.ajax({
            url: `/distributor/${distributorId}/users`,
            method: 'GET',
            success: function (response) {
                let html = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <input type="text" class="form-control w-50" id="userSearchInput" placeholder="🔍 Search users...">
                        <button class="btn btn-success ms-3" id="bulkAssignBtn" data-distributor-id="${distributorId}">
                            <i class="fas fa-check-circle me-1"></i> Assign Selected Users
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle" id="userTable">
                            <thead class="table-light">
                                <tr>
                                    <th><input type="checkbox" id="selectAllUsers"></th>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Contact Number</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>`;

                if (response.users.length > 0) {
                    response.users.forEach((user, index) => {
                        html += `
                            <tr>
                                <td><input type="checkbox" class="user-checkbox" value="${user.id}"></td>
                                <td>${index + 1}</td>
                                <td>${user.name ?? 'N/A'}</td>
                                <td>${user.username ?? 'N/A'}</td>
                                <td>${user.contact_number ?? 'N/A'}</td>
                                <td>${user.email ?? 'N/A'}</td>
                            </tr>`;
                    });
                } else {
                    html += '<tr><td colspan="6" class="text-center">No distributor user found.</td></tr>';
                }

                html += '</tbody></table>';
                $('#userMapContent').html(html);
            },
            error: function () {
                $('#userMapContent').html('<p class="text-danger">Error loading users.</p>');
            }
        });
    });

    // Select all toggle
    $(document).on('change', '#selectAllUsers', function () {
        $('.user-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Bulk assign
    $(document).on('click', '#bulkAssignBtn', function () {
        const distributorId = $(this).data('distributor-id');
        const userIds = $('.user-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (userIds.length === 0) {
            alert('Please select at least one user.');
            return;
        }

        $.ajax({
            url: '/users/assign-distributor-bulk',
            method: 'POST',
            data: {
                user_ids: userIds,
                distributor_id: distributorId,
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                alert(response.message);
                location.reload();
            },
            error: function () {
                alert('Error assigning users.');
            }
        });
    });

    // Live search filter
    $(document).on('keyup', '#userSearchInput', function () {
        const keyword = $(this).val().toLowerCase();
        $('#userTable tbody tr').each(function () {
            const rowText = $(this).text().toLowerCase();
            $(this).toggle(rowText.includes(keyword));
        });
    });
</script>
@endsection

