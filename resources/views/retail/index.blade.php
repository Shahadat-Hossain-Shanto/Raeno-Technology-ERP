@extends('layouts.master')
@section('title', 'RAENO :: Retail List')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-list"></i> Retail List</strong></h5>
                </div>
                <div class="d-flex justify-content-end" style="padding-top: 10px;padding-right: 30px;">
                    <a href="{{ route('retails.create') }}" class="btn btn-primary">Add New Retail</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="retailTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Retail Name</th>
                                    <th>Owner Name</th>
                                    <th>NID</th>
                                    <th>Contact No</th>
                                    <th>Email</th>
                                    <th>District</th>
                                    <th>Upazila</th>
                                    <th>Distributor</th>
                                    <th>Retail Address</th>
                                    <th>Type</th>
                                    <th>Bkash No</th>
                                    <th>TIN</th>
                                    <th>Trade License No</th>
                                    <th>License Validity</th>
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

<script>
    $(document).ready(function () {
        $('#retailTable').DataTable({
            ajax: {
                url: '{{ route('retails.data') }}',
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
                { data: 'retail_name' },
                { data: 'owner_name' },
                { data: 'nid' },
                { data: 'contact_no' },
                { data: 'email' },
                { data: 'district.name', name: 'district.name' },
                { data: 'upazila.name', name: 'upazila.name' },
                { data: 'distributor.distributor_name', name: 'distributor.distributor_name' },
                { data: 'retail_address' },
                { data: 'type' },
                { data: 'bkash_no' },
                { data: 'tin' },
                { data: 'trade_license_no' },
                { data: 'trade_license_validity' },
                {
                    data: null,
                    render: function(row) {
                        return `
                            <a href="/retails/${row.id}/edit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="/retails/${row.id}" method="POST" style="display:inline;">
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
</script>
@endsection
