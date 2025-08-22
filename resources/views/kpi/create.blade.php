@extends('layouts.master')
@section('title', 'KPI Create')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">

            {{-- Alert Messages --}}
            @if(session('success'))
                <div id="kpi-success-msg" class="alert alert-success mt-2">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div id="kpi-error-msg" class="alert alert-danger mt-2">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div id="kpi-validation-msg" class="alert alert-danger mt-2">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <strong><i class="fas fa-plus"></i> KPI Create</strong>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('kpi.store') }}">
                        @csrf

                        <div class="row">
                            {{-- KPI Type Dropdown --}}
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>KPI Type</label>
                                    <select class="form-control" id="kpi_type" name="kpi_type">
                                        <option value="">Select Type</option>
                                        <option value="monthly">Monthly KPI</option>
                                        <option value="quarterly">Quarterly KPI</option>
                                        <option value="model">Model Based KPI</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Main Fields: Displayed to the right --}}
                            <div class="col-md-10">
                                {{-- Monthly KPI Fields --}}
                                <div id="monthly_kpi_fields" style="display:none;">
                                    <input type="hidden" name="monthly_kpi_for" value="Distributor">
                                    <div class="form-group col-md-3 p-0 d-inline-block mr-2">
                                        <label>Month & Year<span class="text-danger">*</span></label>
                                        <input type="month" name="monthly_month_year" class="form-control">
                                    </div>
                                    <div class="form-group col-md-3 p-0 d-inline-block mr-2">
                                        <label>Target Amount<span class="text-danger">*</span></label>
                                        <input type="number" name="monthly_target_amount" class="form-control" placeholder="Enter Target Amount">
                                    </div>
                                </div>
                                {{-- quaterly --}}
                                <div id="quarterly_kpi_fields" style="display:none;">
                                    <div class="form-group col-md-3 p-0 d-inline-block mr-2">
                                        <label>Quarter<span class="text-danger">*</span></label>
                                        <select name="quarterly_quarter" class="form-control">
                                            <option value="">Select Quarter --</option>
                                            <option value="1">Q1 ( Jan, Feb, Mar )</option>
                                            <option value="2">Q2 ( Apr, May, Jun )</option>
                                            <option value="3">Q3 ( Jul, Aug, Sep )</option>
                                            <option value="4">Q4 ( Oct, Nov, Dec )</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3 p-0 d-inline-block mr-2">
                                        <label>Year <span class="text-danger">*</span></label>
                                        <select name="quarterly_year" class="form-control">
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
                                </div>
                                {{-- Model KPI Fields --}}
                                <div id="model_kpi_fields" style="display:none;">
                                    <div class="form-row">
                                    <div class="form-group col-md-2 p-0 d-inline-block mr-2">
                                        <label>KPI For <span class="text-danger">*</span></label>
                                        <select name="model_kpi_for" class="form-control">
                                            <option value="" selected disabled>Select KPI For --</option>
                                            <option value="Distributor">Distributor</option>
                                            <option value="Retailer">Retailer</option>
                                            {{-- <option value="Employee">Employee</option> --}}
                                        </select>
                                    </div>
                                        <div class="form-group col-md-2 p-0 d-inline-block mr-2">
                                            <label>Month & Year<span class="text-danger">*</span></label>
                                            <input type="month" name="model_month_year" class="form-control">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Model<span class="text-danger">*</span></label>
                                            <select class="form-control select2" name="model_id" id="model_id">
                                                <option value="">Select Model</option>
                                                @foreach ($model as $item)
                                                    <option value="{{ $item->id }}" data-name="{{ $item->model_name }}">{{ $item->model_name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="model_name" id="model_name">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Target Quantity<span class="text-danger">*</span></label>
                                            <input type="number" name="model_target_quantity" class="form-control" placeholder="Enter Target Quantity">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Slabs Table Below --}}
                        <div class="row mt-3">
                            <div class="col-12 col-md-9">
                                {{-- Monthly KPI Slabs --}}
                                <div id="kpi_tables">
                                <div id="monthly_kpi_section" style="display:none;">
                                    <h6>Monthly KPI Slabs</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-fixed" id="monthly_slabs_table">
                                            <thead>
                                                <tr>
                                                    <th style="width:40%">Criteria (%)</th>
                                                    <th style="width:40%">Incentive Rate (%)</th>
                                                    <th style="width:20%; text-align:center;">
                                                        <button type="button" class="btn btn-success btn-sm" id="add_monthly_slab"><i class="fas fa-plus"></i></button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                {{-- Quarterly KPI Slabs --}}
                                <div id="quarterly_kpi_section" style="display:none;">
                                    <h6>Quarterly KPI Slabs</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-fixed" id="quarterly_slabs_table">
                                            <thead>
                                                <tr>
                                                    <th style="width:40%">Criteria (%)</th>
                                                    <th style="width:40%">Incentive Rate (%)</th>
                                                    <th style="width:20%; text-align:center;">
                                                        <button type="button" class="btn btn-success btn-sm" id="add_quarterly_slab"><i class="fas fa-plus"></i></button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- Model KPI Slabs --}}
                                <div id="model_kpi_section" style="display:none;">
                                    <h6>Model KPI Slabs</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-fixed" id="model_slabs_table">
                                            <thead>
                                                <tr>
                                                    <th style="width:40%">Criteria (%)</th>
                                                    <th style="width:40%">Incentive Amount (Unit)</th>
                                                    <th style="width:20%; text-align:center;">
                                                        <button type="button" class="btn btn-success btn-sm" id="add_model_slab"><i class="fas fa-plus"></i></button>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Save KPI</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.select2').select2();

    $('#model_id').on('change', function() {
        let name = $(this).find('option:selected').data('name');
        $('#model_name').val(name);
    });

    $('#kpi_type').on('change', function(){
        if($(this).val() === 'monthly'){
            $('#monthly_kpi_fields').show();
            $('#model_kpi_fields').hide();
            $('#quarterly_kpi_fields').hide();
            $('#monthly_kpi_section').show();
            $('#model_kpi_section').hide();
            $('#quarterly_kpi_section').hide();
        } else if($(this).val() === 'quarterly'){
            $('#quarterly_kpi_fields').show();
            $('#monthly_kpi_fields').hide();
            $('#model_kpi_fields').hide();
            $('#quarterly_kpi_section').show();
            $('#monthly_kpi_section').hide();
            $('#model_kpi_section').hide();
        } else if($(this).val() === 'model'){
            $('#model_kpi_fields').show();
            $('#monthly_kpi_fields').hide();
            $('#quarterly_kpi_fields').hide();
            $('#model_kpi_section').show();
            $('#monthly_kpi_section').hide();
            $('#quarterly_kpi_section').hide();
        } else {
            $('#monthly_kpi_fields, #model_kpi_fields').hide();
            $('#monthly_kpi_section, #model_kpi_section').hide();

        }
    });

    $('#add_monthly_slab').click(function(){
        $('#monthly_slabs_table tbody').append(`
            <tr>
                <td><input type="number" name="monthly_criteria_percent[]" class="form-control" required></td>
                <td><input type="number" name="monthly_incentive_rate[]" class="form-control" step="0.01" required></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove_slab"><i class="fas fa-minus"></button></td>
            </tr>
        `);
    });
    $('#add_quarterly_slab').click(function(){
        $('#quarterly_slabs_table tbody').append(`
            <tr>
                <td><input type="number" name="quarterly_criteria_percent[]" class="form-control" required></td>
                <td><input type="number" name="quarterly_incentive_rate[]" class="form-control" step="0.01" required></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove_slab"><i class="fas fa-minus"></button></td>
            </tr>
        `);
    });

    $('#add_model_slab').click(function(){
        $('#model_slabs_table tbody').append(`
            <tr>
                <td><input type="number" name="model_criteria_percent[]" class="form-control" required></td>
                <td><input type="number" name="model_incentive_amount[]" class="form-control" required></td>
                <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove_slab"><i class="fas fa-minus"></button></td>
            </tr>
        `);
    });

    $(document).on('click', '.remove_slab', function(){
        $(this).closest('tr').remove();
    });
});
</script>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Select',
            allowClear: true,
            width: '100%'
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
    .table-fixed {
        table-layout: fixed;
        width: 100%;
    }

    .table-fixed th, .table-fixed td {
        overflow: hidden;
    }
    #kpi_tables table th,
    #kpi_tables table td {
        padding: 0.5rem !important;
    }
</style>
<script>
    setTimeout(() => {
        const successMsg = document.getElementById('kpi-success-msg');
        const errorMsg = document.getElementById('kpi-error-msg');
        const validationMsg = document.getElementById('kpi-validation-msg');

        if(successMsg) successMsg.style.display = 'none';
        if(errorMsg) errorMsg.style.display = 'none';
        if(validationMsg) validationMsg.style.display = 'none';
    }, 3000);
</script>
@endsection
