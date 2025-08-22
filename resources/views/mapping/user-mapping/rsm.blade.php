@extends('layouts.master')
@section('title', 'RAENO :: RSM List')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-user"></i> RSM User List</strong></h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="rsmTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Mobile</th>
                                    <th>Region</th>
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

<!-- Modal to show regions -->
<div class="modal fade" id="regionModal" tabindex="-1" aria-labelledby="regionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content"  style="max-height: 500px;">
      <div class="modal-header">
        <h5 class="modal-title">Regions of <span id="modalUserName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul id="regionList" class="list-group">
          {{-- List will be loaded by JS --}}
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
    let shouldReload = false;

    $(document).ready(function () {
        $('#rsmTable').DataTable({
            ajax: {
                url: '{{ route('rsm.data') }}',
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
                { data: 'regions', name: 'regions' },
                {
                    data: null,
                    render: function(row) {
                        return `
                            <button class="btn btn-info btn-sm" onclick="viewRegions(${row.id}, '${row.name}')">
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

        $('#regionModal').on('hidden.bs.modal', function () {
            if (shouldReload) {
                location.reload();
            }
        });
    });

    function viewRegions(userId, userName) {
        $('#modalUserName').text(userName);
        $('#regionList').empty();

        $.get(`/rsm/regions/${userId}`, function(response) {
            if (response.regions.length > 0) {
                response.regions.forEach(function(item) {
                    $('#regionList').append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${item.region_name}
                            <button class="btn btn-danger btn-sm" onclick="deleteRegion(${item.id}, ${userId})">
                                Delete
                            </button>
                        </li>
                    `);
                });
            } else {
                $('#regionList').append(`<li class="list-group-item">No regions assigned.</li>`);
            }

            $('#regionModal').modal('show');
        });
    }

    function deleteRegion(regionUserId, userId) {
        if (confirm("Are you sure to remove this region?")) {
            $.ajax({
                url: `/rsm/${regionUserId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    shouldReload = true;
                    viewRegions(userId, $('#modalUserName').text());
                }
            });
        }
    }
</script>

<style>
    #rsmTable_filter {
        margin-right: 10px;
    }
</style>
@endsection
