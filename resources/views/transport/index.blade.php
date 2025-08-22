@extends('layouts.master')
@section('title', 'RAENO :: Transport List')

@section('content')
<div class="content-wrapper">
    <div class="content-header"></div>

    <div class="content">
        <div class="">
            <div class="card card-primary">
                <div class="card-header d-flex justify-content-between" style="height: 49px;">
                    <h5><strong><i class="fas fa-list"></i> Transport List</strong></h5>
                </div>
                <div class="d-flex justify-content-end" style="padding-top: 10px;padding-right: 30px;">
                    <a href="{{ route('transports.create') }}" class="btn btn-primary">Add New Transport</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table id="transportTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transport Name</th>
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
        $('#transportTable').DataTable({
            ajax: {
                url: '{{ route('transports.data') }}',
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
                {
                    data: null,
                    render: function(row) {
                        return `
                            <a href="/transports/${row.id}/edit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <form action="/transports/${row.id}" method="POST" style="display:inline;">
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
