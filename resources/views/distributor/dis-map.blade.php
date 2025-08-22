@extends('layouts.master')
@section('title', 'RAENO :: Distributor Map')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-user"></i> Distributor User List</strong></h5>
                </div>
                {{-- <div class="d-flex justify-content-end" style="padding-top: 10px;padding-right: 30px;">
                    <a href="{{ route('territories.create') }}" class="btn btn-primary">Add New Territory</a>
                </div> --}}

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="distributorTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Distributor Name</th>
                                    <th>Distributor Contact</th>
                                    <th>Distributor Email</th>
                                    <th>District</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Moblile</th>
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

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $('#distributorTable').DataTable({
            ajax: {
                url: '{{ route('dum.data') }}',
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
                { data: 'distributor_relation.distributor_name', name: 'distributor_relation.distributor_name' },
                { data: 'distributor_relation.contact_no', name: 'distributor_relation.contact_no' },
                { data: 'distributor_relation.email', name: 'distributor_relation.email' },
                { data: 'distributor_relation.district.name', name: 'district' },
                { data: 'name' },
                { data: 'username' },
                { data: 'email' },
                { data: 'address' },
                { data: 'contact_number' },
                // { data: 'region' },
                {
                    data: null,
                    render: function(row) {
                        return `
                            <form action="/dum/${row.id}" method="POST" style="display:inline;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove this user from this Distributor?')">
                                    Remove
                                </button>
                            </form>`;
                    },
                    orderable: false,
                    searchable: false
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
    });
</script>

{{-- <style>
    #territoryTable_filter {
        margin-right: 10px;
    }
</style> --}}
@endsection
