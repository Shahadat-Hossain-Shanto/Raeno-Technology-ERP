@extends('layouts.master')
@section('title', 'RAENO :: Area List')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-map"></i> Area List</strong></h5>
                </div>
                <div class="d-flex justify-content-end" style="padding-top: 10px;padding-right: 30px;">
                    <a href="{{ route('areas.create') }}" class="btn btn-primary">Add New Area</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="areaTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Area Name</th>
                                    <th>Region Name</th>
                                    <th>Status</th>
                                    <th>User Map</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="userMapModal" tabindex="-1" aria-labelledby="userMapModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userMapModalLabel">ASM Users</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="userMapContent">Loading...</div>
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {

        $('#areaTable').DataTable({
            ajax: {
                url: '{{ route('areas.data') }}',
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
                { data: 'area_name' },
                { data: 'region_name' },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if(data == 1){
                            return '<span class="badge bg-success">Active</span>';
                        } else {
                            return '<span class="badge bg-danger">Inactive</span>';
                        }
                    }
                },
                { // New User Map Column
                    data: null,
                    render: function(row) {
                        return `
                            <button class="btn btn-info btn-sm user-map-btn" data-area-id="${row.id}">
                                <i class="fas fa-users"></i> User Map
                            </button>
                        `;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: null,
                    render: function(row) {
                        return `
                            <a href="/areas/${row.id}/edit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="/areas/${row.id}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>`;
                    },
                    orderable: false,
                    searchable: false
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

    $(document).on('click', '.user-map-btn', function () {
        const areaId = $(this).data('area-id');

        $('#userMapContent').html('Loading...');
        $('#userMapModal').modal('show');

        $.ajax({
            url: `/areas/${areaId}/users`,
            method: 'GET',
            success: function (response) {
                let html = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <input type="text" class="form-control w-50" id="userSearchInput" placeholder="🔍 Search users...">
                        <button class="btn btn-success ms-3" id="bulkAssignBtn" data-area-id="${areaId}">
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
                    html += '<tr><td colspan="6" class="text-center">No RSM users found for this area.</td></tr>';
                }

                html += '</tbody></table>';
                $('#userMapContent').html(html);
            },
            error: function () {
                $('#userMapContent').html('<p class="text-danger">No permission or error loading users.</p>');
            }
        });
    });

    // Select all toggle
    $(document).on('change', '#selectAllUsers', function () {
        $('.user-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Bulk assign
    $(document).on('click', '#bulkAssignBtn', function () {
        const areaId = $(this).data('area-id');
        const userIds = $('.user-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (userIds.length === 0) {
            alert('Please select at least one user.');
            return;
        }

        $.ajax({
            url: '/users/assign-area-bulk',
            method: 'POST',
            data: {
                user_ids: userIds,
                area_id: areaId,
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

<style>
    #areaTable_filter {
        margin-right: 10px;
    }
</style>
@endsection
