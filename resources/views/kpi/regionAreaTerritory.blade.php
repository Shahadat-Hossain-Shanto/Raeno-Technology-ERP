@extends('layouts.master')
@section('title', 'Region/Area/Territory KPI Report')

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
                                <strong><i class="fas fa-file-contract"></i> Region/Area/Territory KPI Report</strong>
                            </h5>
                        </div>

                        <div class="card-body">
                            <!-- Search Form -->
                            <form id="monthlyReportForm" method="GET">
                                <div class="form-row g-2 align-items-end">
                                    <div class="form-group col-md-2">
                                        <label for="region_id">Region</label>
                                        <select class="form-control select2 filter-field" id="region_id" name="region_id">
                                            <option value="">All</option>
                                            @foreach ($region as $item)
                                                <option value="{{ $item->id }}">{{ $item->region_name }} ({{ $item->id }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="area_id">Area</label>
                                        <select class="form-control select2 filter-field" id="area_id" name="area_id">
                                            <option value="">All</option>
                                            @foreach ($area as $item)
                                                <option value="{{ $item->id }}">{{ $item->area_name }} ({{ $item->id }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="territory_id">Territory</label>
                                        <select class="form-control select2 filter-field" id="territory_id" name="territory_id">
                                            <option value="">All</option>
                                            @foreach ($territory as $item)
                                                <option value="{{ $item->id }}">{{ $item->territory_name }} ({{ $item->id }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Report Type -->
                                    <div class="form-group col-md-2">
                                        <label for="report_type">Report Type</label>
                                        <select class="form-control" id="report_type" name="report_type">
                                            <option value="month" selected>Month</option>
                                            <option value="quarter">Quarter</option>
                                        </select>
                                    </div>

                                    <!-- Month Field -->
                                    <div class="form-group col-md-2" id="monthField">
                                        <label for="month_year">Month</label>
                                        <input type="month" class="form-control" id="month_year" name="month_year" value="{{ now()->format('Y-m') }}">
                                    </div>

                                    <!-- Quarter Fields -->
                                    <div class="form-group col-md-2 d-none" id="yearField">
                                        <label for="year">Year</label>
                                        <select class="form-control" id="year" name="year">
                                            @for ($i = now()->year - 10; $i <= now()->year + 10; $i++)
                                                <option value="{{ $i }}" {{ $i == now()->year ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2 d-none" id="quarterField">
                                        <label for="quarter">Quarter</label>
                                        <select class="form-control" id="quarter" name="quarter">
                                            <option value="1">Q1 (Jan - Mar)</option>
                                            <option value="2">Q2 (Apr - Jun)</option>
                                            <option value="3">Q3 (Jul - Sep)</option>
                                            <option value="4">Q4 (Oct - Dec)</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <button type="button" id="searchBtn" class="btn btn-primary btn-block">
                                            Submit
                                        </button>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button type="reset" id="resetBtn" class="btn btn-secondary btn-block">
                                            Reset
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
                                                <th>Month / Year</th>
                                                <th>Quarter</th>
                                                <th>Target Amount</th>
                                                <th>Buy Amount</th>
                                                <th>Achievement</th>
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
                url: '{{ route('region.area.territory.data') }}',
                data: function (d) {
                    // Common filters
                    d.region_id = $('#region_id').val();
                    d.area_id = $('#area_id').val();
                    d.territory_id = $('#territory_id').val();

                    // Report type specific filters
                    let reportType = $('#report_type').val();
                    d.report_type = reportType; // send type too

                    if (reportType === 'month') {
                        d.month_year = $('#month_year').val();
                        d.year = null;
                        d.quarter = null;
                    } else if (reportType === 'quarter') {
                        d.year = $('#year').val();
                        d.quarter = $('#quarter').val();
                        d.month_year = null;
                    }
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
                { data: 'month_year' },
                { data: 'quarter' },
                { data: 'target_amount' },
                { data: 'buy_amount' },
                { data: 'achievement' }
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    title: 'Model Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    title: 'Model Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    title: 'Model Report',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    title: 'Model Report',
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

    $('#resetBtn').click(function () {

        $('#monthlyReportForm')[0].reset();
        $('.select2').val(null).trigger('change');
        $('#report_type').val('month').trigger('change');
        $('#monthField').removeClass('d-none');
        $('#yearField, #quarterField').addClass('d-none');
        $('#month_year').val('{{ now()->format('Y-m') }}');
        $('#year').val('{{ now()->year }}');
        $('#quarter').val('1');
        $('#tablePart').hide();
        table.ajax.reload();
    });

$(document).ready(function () {
    $('.filter-field').on('change', function () {
        if ($(this).val() !== '') {
            // Reset other fields
            $('.filter-field').not(this).val('').trigger('change');
        }
    });
});
$(document).ready(function () {
    $('#report_type').on('change', function () {
        if ($(this).val() === 'month') {
            $('#monthField').removeClass('d-none');
            $('#yearField, #quarterField').addClass('d-none');
        } else {
            $('#monthField').addClass('d-none');
            $('#yearField, #quarterField').removeClass('d-none');
        }
    }).trigger('change');
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
