@extends('layouts.master')
@section('title', 'RAENO :: ASM List')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-user"></i> ASM User List</strong></h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="asmTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Mobile</th>
                                    <th>Area</th>
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

<!-- Modal to show areas -->
<div class="modal fade" id="areaModal" tabindex="-1" aria-labelledby="areaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content"  style="max-height: 500px;">
      <div class="modal-header">
        <h5 class="modal-title">Areas of <span id="modalUserName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <ul id="areaList" class="list-group">
          {{-- List will be loaded by JS --}}
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
    let shouldReload = false;

    $(document).ready(function () {
        $('#asmTable').DataTable({
            ajax: {
                url: '{{ route('asm.data') }}',
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
                { data: 'areas', name: 'areas' },
                {
                    data: null,
                    render: function(row) {
                        return `
                            <button class="btn btn-info btn-sm" onclick="viewAreas(${row.id}, '${row.name}')">
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

        $('#areaModal').on('hidden.bs.modal', function () {
            if (shouldReload) {
                location.reload();
            }
        });
    });

    function viewAreas(userId, userName) {
        $('#modalUserName').text(userName);
        $('#areaList').empty();

        $.get(`/asm/areas/${userId}`, function(response) {
            if (response.areas.length > 0) {
                response.areas.forEach(function(item) {
                    $('#areaList').append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            ${item.area_name}
                            <button class="btn btn-danger btn-sm" onclick="deleteArea(${item.id}, ${userId})">
                                Delete
                            </button>
                        </li>
                    `);
                });
            } else {
                $('#areaList').append(`<li class="list-group-item">No areas assigned.</li>`);
            }

            $('#areaModal').modal('show');
        });
    }

    function deleteArea(areaUserId, userId) {
        if (confirm("Are you sure to remove this area?")) {
            $.ajax({
                url: `/asm/${areaUserId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    shouldReload = true;
                    viewAreas(userId, $('#modalUserName').text());
                }
            });
        }
    }
</script>

<style>
    #asmTable_filter {
        margin-right: 10px;
    }
</style>
@endsection
