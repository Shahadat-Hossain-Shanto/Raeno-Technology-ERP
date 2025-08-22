@extends('layouts.master')
@section('title', 'RAENO :: Produst Return List')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="card card-primary">
            <div class="card-header d-flex justify-content-between" style="height: 49px;">
                <h5><strong><i class="fas fa-list-alt"></i> Produst Return List</strong></h5>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="returnTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Return ID</th>
                                <th>Return Date</th>
                                <th>Distributor ID</th>
                                <th>Distributor Name</th>
                                <th>Mobile</th>
                                <th>Qty</th>
                                {{-- <th>Status</th> --}}
                                <th>Posting Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#returnTable').DataTable({
            ajax: {
                url: '{{ route('return.data') }}',
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
                { data: 'id' },
                {
                    data: 'created_at',
                    render: function(data) {
                        if (!data) return '';

                        const date = new Date(data);
                        if (isNaN(date)) return '';

                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();

                        return `${day}-${month}-${year}`;
                    }
                },
                { data: 'distributor_id' },
                { data: 'distributor_name' },
                { data: 'mobile' },
                { data: 'quantity' },
                // {
                //     data: 'status',
                //     render: function(data, type, row) {
                //         switch (data) {
                //             case 0:
                //                 return '<span class="badge badge-danger">Pending</span>';
                //             case 1:
                //                 return '<span class="badge badge-success">Received</span>';
                //         }
                //     }
                // },
                {
                    data: 'posting_status',
                    render: function(data, type, row) {
                        switch (data) {
                            case 0:
                                return '<span class="badge badge-danger">No</span>';
                            case 1:
                                return '<span class="badge badge-success">Yes</span>';
                        }
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `
                            <a href="/return-details/${row.id}" class="btn btn-sm btn-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                        `;
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
@endsection
