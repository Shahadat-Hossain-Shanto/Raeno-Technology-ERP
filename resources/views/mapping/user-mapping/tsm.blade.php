@extends('layouts.master')
@section('title', 'RAENO :: TSM List')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-user"></i> TSM User List</strong></h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="tsmTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Moblile</th>
                                    <th>Territory</th>
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

<!-- Modal to show territories -->
<div class="modal fade" id="territoryModal" tabindex="-1" aria-labelledby="territoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content"  style="max-height: 500px;">
      <div class="modal-header">
        <h5 class="modal-title">Territories of <span id="modalUserName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul id="territoryList" class="list-group">
          {{-- List will be loaded by JS --}}
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
    let shouldReload = false;

    $(document).ready(function () {
        $('#tsmTable').DataTable({
            ajax: {
                url: '{{ route('tsm.data') }}',
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
                { data: 'name' },
                { data: 'username' },
                { data: 'email' },
                { data: 'address' },
                { data: 'contact_number' },
                { data: 'territories', name: 'territories' },
                {
                    data: null,
                    render: function(row) {
                        return `
                            <button class="btn btn-info btn-sm" onclick="viewTerritories(${row.id}, '${row.name}')">
                                View / Remove
                            </button>
                        `;
                    }
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

        $('#territoryModal').on('hidden.bs.modal', function () {
            if (shouldReload) {
                location.reload();
            }
        });
    });

    function viewTerritories(userId, userName) {
        $('#modalUserName').text(userName);
        $('#territoryList').empty();

        $.get(`/tsm/territories/${userId}`, function(response) {
            if (response.territories.length > 0) {
                response.territories.forEach(function(item) {
                    $('#territoryList').append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${item.territory_name}
                            <button class="btn btn-danger btn-sm" onclick="deleteTerritory(${item.id}, ${userId})">
                                Delete
                            </button>
                        </li>
                    `);
                });
            } else {
                $('#territoryList').append(`<li class="list-group-item">No territories assigned.</li>`);
            }

            $('#territoryModal').modal('show');
        });
    }

    function deleteTerritory(territoryUserId, userId) {
        if (confirm("Are you sure to remove this territory?")) {
            $.ajax({
                url: `/tsm/territory/${territoryUserId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    shouldReload = true;
                    viewTerritories(userId, $('#modalUserName').text());
                }
            });
        }
    }
</script>

<style>
    #tsmTable_filter {
        margin-right: 10px;
    }
</style>
@endsection
