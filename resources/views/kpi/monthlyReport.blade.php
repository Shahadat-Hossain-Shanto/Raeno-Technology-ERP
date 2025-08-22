@extends('layouts.master')
@section('title', 'Monthly KPI Report')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- Optional: Page title here --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card card-primary shadow-sm">
                        <div class="card-header">
                            <h5 class="m-0">
                                <strong><i class="fas fa-file-contract"></i> Monthly KPI Report</strong>
                            </h5>
                        </div>

                        <div class="card-body">
                            <!-- Search Form -->
                            <form id="monthlyReportForm" method="GET">
                                <div class="form-row g-2 align-items-end">
                                    <div class="form-group col-md-3">
                                        <label for="distributor_id">Distributor</label>
                                        <select class="form-control select2" id="distributor_id" name="distributor_id">
                                            <option value="">All</option>
                                            @foreach ($distributor as $item)
                                                <option value="{{ $item->id }}">{{ $item->distributor_name }} ({{ $item->id }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="month_year">Month</label>
                                        <input type="month" class="form-control" id="month_year" name="month_year" value="{{ now()->format('Y-m') }}">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <button type="button" id="searchBtn" class="btn btn-primary btn-block">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <hr>

                            <!-- Data Table -->
                            <div id="tablePart" class="pt-3" style="display: none;">
                                <div class="table-responsive w-100">
                                    <table id="monthlyTable" class="table table-bordered table-striped w-100">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Distributor ID</th>
                                                <th>Distributor Name</th>
                                                <th>Target</th>
                                                <th>Buy Amount</th>
                                                <th>Achievement</th>
                                                <th>Incentive Rate</th>
                                                <th>Incentive Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->

                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Select',
            allowClear: true,
            width: '100%'
        });

        // Initialize DataTable
        const table = $('#monthlyTable').DataTable({
            processing: true,
            serverSide: true,
            deferLoading: 0,
            ajax: {
                url: '{{ route('monthly.report.data') }}',
                data: function (d) {
                    d.distributor_id = $('#distributor_id').val();
                    d.month_year = $('#month_year').val();
                },
                dataSrc: function (json) {
                    if (json.data.length > 0) {
                        $('#tablePart').show();
                    } else {
                        $('#tablePart').hide();
                    }
                    return json.data;
                }
            },
            columns: [
                { data: 'distributor_id' },
                { data: 'distributor_name' },
                { data: 'target' },
                { data: 'buy_amount' },
                { data: 'achievement' },
                { data: 'incentive_rate' },
                { data: 'incentive_amount' }
            ],
            dom: 'Bfrtip', // Enable buttons
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    title: 'Monthly Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    title: 'Monthly Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    title: 'Monthly Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    title: 'Monthly Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });

        // Search button click
        $('#searchBtn').click(function () {
            table.ajax.reload();
        });
    });
</script>

<style>
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .select2-selection__rendered {
        line-height: 24px !important;
    }

    .select2-selection__arrow {
        height: 36px !important;
        top: 1px !important;
    }
</style>
@endsection
