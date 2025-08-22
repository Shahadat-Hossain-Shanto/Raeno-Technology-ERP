@extends('layouts.master')
@section('title', 'Summary Reports')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div><!-- /.col -->
            </div><!-- /.row mb-2 -->
        </div><!-- /.container-fluid -->
    </div> <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">


                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong>SUMMARY WISE SALES REPORT</strong></h5>
                        </div>
                        <div class="card-body">
                            <button type="button" id="gt_report" class="gt_report btn btn-primary">Grand Total
                                Report</button>
                            <button type="button" class="qty_report btn btn-secondary">Total Qantity Report </button>
                            <button type="button" class="dscnt_report btn btn-success">Total Discount Report</button>
                            <button type="button" class="tp_report btn btn-info">Total Price Report</button>
                            <button type="button" class="all_report btn btn-dark">All Summary Report</button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Grand total Form --}}
            <div class="row" id="grnd_total">
                <div class="col-lg-12">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Grand Total
                                Report</h5>
                        </div>
                        <div class="card-body">
                            <form id="ReportForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="startdate">From</label>
                                        <input type="date" class="form-control" id="startdate" name="startdate">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="enddate">To</label>
                                        <input type="date" class="form-control" id="enddate" name="enddate">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="employee">Employees</label>
                                        <select class="form-control" name="employee" id="employee">
                                            <option value="option_select" selected> All Employee</option>
                                            @foreach($salesBy as $emp)
                                                <option value="{{ $emp->id }}">
                                                    {{ $emp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="padding-top: 32px;" class="form-group col-md-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>

                                </div>
                                <small id="emailHelp" class="form-text text-muted">Press the submit button to generate
                                    overall report.</small>
                                <small id="emailHelp" class="form-text text-muted">1. Generate Report between
                                    dates.</small>
                                <small id="emailHelp" class="form-text text-muted">2. Generate Report of specific
                                    date.</small>
                                <small id="emailHelp" class="form-text text-muted">3. Generate Report of specific
                                    employee.</small>
                                <small id="emailHelp" class="form-text text-muted">4. Generate Report of specific date
                                    and employee.</small>
                                <small id="emailHelp" class="form-text text-muted">5. Generate Report between dates and
                                    specific employee.</small>
                            </form>

                        </div> <!-- Card-body -->
                    </div> <!-- Card -->
                </div> <!-- /.col-lg-6 -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Grand Total Report</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart" style="height: 400px; width: 100%;">
                                            <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                            <p class="chartp" style="text-align: center;">Submit to generate the report
                                            </p>
                                            <figure class="highcharts-figure">
                                                <div id="container1"></div>
                                                <p class="highcharts-description">

                                                </p>
                                            </figure>

                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- /.End Grand total Form -->

            {{-- total quantity Form --}}
            <div class="row" id="total_qty">
                <div class="col-lg-12">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Total Quantity
                                Report</h5>
                        </div>
                        <div class="card-body">
                            <form id="ReportForm1" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="startdate">From</label>
                                        <input type="date" class="form-control" id="startdate" name="startdate">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="enddate">To</label>
                                        <input type="date" class="form-control" id="enddate" name="enddate">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="employee">Employees</label>
                                        <select class="form-control" name="employee" id="employee">
                                            <option value="option_select" selected> All Employee</option>
                                            @foreach($salesBy as $emp)
                                                <option value="{{ $emp->id }}">
                                                    {{ $emp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="padding-top: 32px;" class="form-group col-md-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>

                                </div>
                                <small id="emailHelp" class="form-text text-muted">Press the submit button to generate
                                    overall report.</small>
                                <small id="emailHelp" class="form-text text-muted">1. Generate Report between
                                    dates.</small>
                                <small id="emailHelp" class="form-text text-muted">2. Generate Report of specific
                                    date.</small>
                                <small id="emailHelp" class="form-text text-muted">3. Generate Report of specific
                                    employee.</small>
                                <small id="emailHelp" class="form-text text-muted">4. Generate Report of specific date
                                    and employee.</small>
                                <small id="emailHelp" class="form-text text-muted">5. Generate Report between dates and
                                    specific employee.</small>
                            </form>

                        </div> <!-- Card-body -->
                    </div> <!-- Card -->
                </div> <!-- /.col-lg-6 -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Total Quantity Report Chart</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart" style="height: 400px; width: 100%;">
                                            <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                            <p class="chartp" style="text-align: center;">Submit to generate the report
                                            </p>
                                            <figure class="highcharts-figure">
                                                <div id="container2"></div>
                                                <p class="highcharts-description">

                                                </p>
                                            </figure>

                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- End  total quantity Form --}}


            {{-- total discount Form --}}
            <div class="row" id="total_dscnt">
                <div class="col-lg-12">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Total Discount
                                Report</h5>
                        </div>
                        <div class="card-body">
                            <form id="ReportForm2" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="startdate">From</label>
                                        <input type="date" class="form-control" id="startdate" name="startdate">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="enddate">To</label>
                                        <input type="date" class="form-control" id="enddate" name="enddate">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="employee">Employees</label>
                                        <select class="form-control" name="employee" id="employee">
                                            <option value="option_select" selected> All Employee</option>
                                            @foreach($salesBy as $emp)
                                                <option value="{{ $emp->id }}">
                                                    {{ $emp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="padding-top: 32px;" class="form-group col-md-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>

                                </div>
                                <small id="emailHelp" class="form-text text-muted">Press the submit button to generate
                                    overall report.</small>
                                <small id="emailHelp" class="form-text text-muted">1. Generate Report between
                                    dates.</small>
                                <small id="emailHelp" class="form-text text-muted">2. Generate Report of specific
                                    date.</small>
                                <small id="emailHelp" class="form-text text-muted">3. Generate Report of specific
                                    employee.</small>
                                <small id="emailHelp" class="form-text text-muted">4. Generate Report of specific date
                                    and employee.</small>
                                <small id="emailHelp" class="form-text text-muted">5. Generate Report between dates and
                                    specific employee.</small>
                            </form>

                        </div> <!-- Card-body -->
                    </div> <!-- Card -->
                </div> <!-- /.col-lg-6 -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Total Discount Report Chart</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart" style="height: 400px; width: 100%;">
                                            <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                            <p class="chartp" style="text-align: center;">Submit to generate the report
                                            </p>
                                            <figure class="highcharts-figure">
                                                <div id="container3"></div>
                                                <p class="highcharts-description">

                                                </p>
                                            </figure>

                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- End  total discount Form --}}

            {{-- total Price Form --}}
            <div class="row" id="total_prc">
                <div class="col-lg-12">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Total Price
                                Report</h5>
                        </div>
                        <div class="card-body">
                            <form id="ReportForm3" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="startdate">From</label>
                                        <input type="date" class="form-control" id="startdate" name="startdate">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="enddate">To</label>
                                        <input type="date" class="form-control" id="enddate" name="enddate">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="employee">Employees</label>
                                        <select class="form-control" name="employee" id="employee">
                                            <option value="option_select" selected> All Employee</option>
                                            @foreach($salesBy as $emp)
                                                <option value="{{ $emp->id }}">
                                                    {{ $emp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="padding-top: 32px;" class="form-group col-md-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>

                                </div>
                                <small id="emailHelp" class="form-text text-muted">Press the submit button to generate
                                    overall report.</small>
                                <small id="emailHelp" class="form-text text-muted">1. Generate Report between
                                    dates.</small>
                                <small id="emailHelp" class="form-text text-muted">2. Generate Report of specific
                                    date.</small>
                                <small id="emailHelp" class="form-text text-muted">3. Generate Report of specific
                                    employee.</small>
                                <small id="emailHelp" class="form-text text-muted">4. Generate Report of specific date
                                    and employee.</small>
                                <small id="emailHelp" class="form-text text-muted">5. Generate Report between dates and
                                    specific employee.</small>
                            </form>

                        </div> <!-- Card-body -->
                    </div> <!-- Card -->
                </div> <!-- /.col-lg-6 -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Total Price Report Chart</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart" style="height: 400px; width: 100%;">
                                            <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                            <p class="chartp" style="text-align: center;">Submit to generate the report
                                            </p>
                                            <figure class="highcharts-figure">
                                                <div id="container4"></div>
                                                <p class="highcharts-description">

                                                </p>
                                            </figure>

                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- End  total Price Form --}}

            {{-- All Summary Report Form --}}
            <div class="row" id="all_report">
                <div class="col-lg-12">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Total Summary
                                Report</h5>
                        </div>
                        <div class="card-body">
                            <form id="ReportForm4" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="startdate">From</label>
                                        <input type="date" class="form-control" id="startdate" name="startdate">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="enddate">To</label>
                                        <input type="date" class="form-control" id="enddate" name="enddate">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="employee">Employees</label>
                                        <select class="form-control" name="employee" id="employee">
                                            <option value="option_select" selected> All Employee</option>
                                            @foreach($salesBy as $emp)
                                                <option value="{{ $emp->id }}">
                                                    {{ $emp->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="padding-top: 32px;" class="form-group col-md-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>

                                </div>
                                <small id="emailHelp" class="form-text text-muted">Press the submit button to generate
                                    overall report.</small>
                                <small id="emailHelp" class="form-text text-muted">1. Generate Report between
                                    dates.</small>
                                <small id="emailHelp" class="form-text text-muted">2. Generate Report of specific
                                    date.</small>
                                <small id="emailHelp" class="form-text text-muted">3. Generate Report of specific
                                    employee.</small>
                                <small id="emailHelp" class="form-text text-muted">4. Generate Report of specific date
                                    and employee.</small>
                                <small id="emailHelp" class="form-text text-muted">5. Generate Report between dates and
                                    specific employee.</small>
                            </form>

                        </div> <!-- Card-body -->
                    </div> <!-- Card -->
                </div> <!-- /.col-lg-6 -->
                <div class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Total Summary Report Chart</h3>

                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart" style="height: 400px; width: 100%;">
                                            <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                            <p class="chartp" style="text-align: center;">Submit to generate the report
                                            </p>
                                            <figure class="highcharts-figure">
                                                <div id="container5"></div>
                                                <p class="highcharts-description">

                                                </p>
                                            </figure>

                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>{{-- End  All Summary Report Form --}}
        </div>
    </div>
</div>









@endsection
@section('script')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script type="text/javascript" src="js/summaryreport.js"></script>
<script type="text/javascript">


</script>

@endsection


{{-- ------------Previous working layout----- --}}
{{-- @extends('layouts.master')
@section('title', 'Summary Reports')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div><!-- /.col -->
            </div><!-- /.row mb-2 -->
        </div><!-- /.container-fluid -->
    </div> <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-lg-12">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="m-0">Product Wise Sales</h5>
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-primary">Primary</button>
                            <button type="button" class="btn btn-secondary">Secondary</button>
                            <button type="button" class="btn btn-success">Success</button>



                            <form id="ReportForm" method="POST" enctype="multipart/form-data">
@csrf
                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="startdate">From</label>
                                        <input type="date" class="form-control" id="startdate" name="startdate">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="enddate">To</label>
                                        <input type="date" class="form-control" id="enddate" name="enddate">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="employee">Employees</label>
                                        <select class="form-control" name="employee" id="employee">
                                            <option value="option_select" selected> All Employee</option>
@foreach($salesBy as $emp)
                                            <option value="{{ $emp->id }}">
{{ $emp->name }}
</option>
@endforeach
</select>
</div>
<div style="padding-top: 32px;" class="form-group col-md-3">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>

</div>
<small id="emailHelp" class="form-text text-muted">Press the submit button to generate
    overall report.</small>
<small id="emailHelp" class="form-text text-muted">1. Generate Report between
    dates.</small>
<small id="emailHelp" class="form-text text-muted">2. Generate Report of specific
    date.</small>
<small id="emailHelp" class="form-text text-muted">3. Generate Report of specific
    employee.</small>
<small id="emailHelp" class="form-text text-muted">4. Generate Report of specific date
    and employee.</small>
<small id="emailHelp" class="form-text text-muted">5. Generate Report between dates and
    specific employee.</small>
</form>

</div> <!-- Card-body -->
</div> <!-- Card -->
</div> <!-- /.col-lg-6 -->
</div><!-- /.row -->

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Summary Report Chart</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart" style="height: 400px; width: 100%;">
                            <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                            <p class="chartp" style="text-align: center;">Submit to generate the report</p>
                            <figure class="highcharts-figure">
                                <div id="container"></div>
                                <p class="highcharts-description">

                                </p>
                            </figure>

                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')
<script src="{{ asset('dist/js/highcharts/highcharts.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/data.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/drilldown.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/exporting.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/export-data.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/accessibility.js')}}"></script>

<script type="text/javascript" src="js/summaryreport.js"></script>
<script type="text/javascript">


</script>


@endsection--}}
