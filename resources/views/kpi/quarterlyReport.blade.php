@extends('layouts.master')
@section('title', 'Quarterly KPI Report')

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
                                <strong><i class="fas fa-file-contract"></i> Quarterly KPI Report</strong>
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

                                    <div class="form-group col-md-2 p-0 d-inline-block mr-2">
                                        <label>Year <span class="text-danger">*</span></label>
                                        <select name="month_year" class="form-control" id="month_year">
                                            <option value="">Select Year --</option>
                                            @php
                                                $currentYear = date('Y');
                                                $startYear = $currentYear - 10;
                                                $endYear = $currentYear + 10;
                                            @endphp
                                            @for ($y = $startYear; $y <= $endYear; $y++)
                                                <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2 p-0 d-inline-block mr-2">
                                        <label>Quarter<span class="text-danger">*</span></label>
                                        <select name="quarter" class="form-control" id="quarter">
                                            <option value="">Select Quarter --</option>
                                            <option value="1">Q1 ( Jan, Feb, Mar )</option>
                                            <option value="2">Q2 ( Apr, May, Jun )</option>
                                            <option value="3">Q3 ( Jul, Aug, Sep )</option>
                                            <option value="4">Q4 ( Oct, Nov, Dec )</option>
                                        </select>
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
                url: '{{ route('quarterly.report.data') }}',
                data: function (d) {
                    d.distributor_id = $('#distributor_id').val();
                    d.month_year = $('#month_year').val();
                    d.quarter = $('#quarter').val();
                },
                dataSrc: function (json) {
                    if (json.data.length > 0) {
                        $('#tablePart').show();
                    } else {
                        $('#tablePart').show();
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
            dom: 'Bfrtip', // Enables buttons
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    title: 'Quarterly Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    title: 'Quarterly Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    title: 'Quarterly Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    title: 'Quarterly Report',
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
<script>
$('#searchBtn').on('click', function (e) {
    e.preventDefault(); // prevent form submission (page reload)

    let year = $('#month_year').val();
    let quarter = $('#quarter').val();

    if (!year) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Year',
            text: 'Please select a Year',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (!quarter) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Quarter',
            text: 'Please select a Quarter',
            confirmButtonText: 'OK'
        });
        return;
    }

    // ✅ Reload DataTable instead of submitting the form
    $('#monthlyTable').DataTable().ajax.reload();
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
