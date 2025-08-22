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
                                <strong><i class="fas fa-file-contract"></i> Region/Area/Territory Model Based KPI Report</strong>
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
                                    <div class="form-group col-md-2">
                                        <label for="model">Model</label>
                                        <select class="form-control select2 filter-field" id="model" name="model">
                                            <option value="">All</option>
                                            @foreach ($model as $item)
                                                <option value="{{ $item->model_name }}">{{ $item->model_name }} ({{ $item->id }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Month Field -->
                                    <div class="form-group col-md-2" id="monthField">
                                        <label for="month_year">Month</label>
                                        <input type="month" class="form-control" id="month_year" name="month_year" value="{{ now()->format('Y-m') }}">
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
                                                <th>Target Quantity</th>
                                                <th>Sell Quantity</th>
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
                url: '{{ route('model.rsm.data') }}',
                data: function (d) {
                    // Common filters
                    d.region_id = $('#region_id').val();
                    d.area_id = $('#area_id').val();
                    d.territory_id = $('#territory_id').val();
                    d.model = $('#model').val();
                    d.month_year = $('#month_year').val();
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
                { data: 'target_quantity' },
                { data: 'sell_quantity' },
                { data: 'achievement' }
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    title: 'Model Based KPI Report',
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

        $('#searchBtn').click(function () {
            let month = $('#month_year').val();
            let model = $('#model').val();

            if (!month) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Field',
                    text: 'Month is required',
                    confirmButtonColor: '#3085d6',
                });
                return false;
            }

            if (!model) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Field',
                    text: 'Model is required',
                    confirmButtonColor: '#3085d6',
                });
                return false;
            }

            let region = $('#region_id').val();
            let area = $('#area_id').val();
            let territory = $('#territory_id').val();
            let selected = [region, area, territory].filter(val => val !== '');

            if (selected.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Selection',
                    text: 'Please select one option from Region, Area, or Territory.',
                    confirmButtonColor: '#d33',
                });
                return false;
            }

            if (selected.length > 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Selection',
                    text: 'You can only select ONE option from Region, Area, or Territory.',
                    confirmButtonColor: '#d33',
                });
                return false;
            }

            // ✅ Passed validation → reload DataTable
            table.ajax.reload();
        });
    });

    $('#resetBtn').click(function () {
        $('#monthlyReportForm')[0].reset();
        $('.select2').val(null).trigger('change');
        $('#month_year').val('{{ now()->format('Y-m') }}');
        $('#model').val('').trigger('change');
        $('#region_id').val('').trigger('change');
        $('#area_id').val('').trigger('change');
        $('#territory_id').val('').trigger('change');
        $('#tablePart').hide();

        table.clear().draw();
    });

    $(document).ready(function () {
        $('#region_id, #area_id, #territory_id').on('change', function () {
            if ($(this).val() !== '') {
                $('#region_id, #area_id, #territory_id').not(this).val('').trigger('change');
            }
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
