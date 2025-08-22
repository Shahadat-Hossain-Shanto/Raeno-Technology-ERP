@extends('layouts.master')
@section('title', 'Employee Reports')

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
		            <!-- BAR CHART -->
		            <div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title"><strong><i class="fas fa-file-contract"></i> SALES BY EMPLOYEE</strong></h3>

		                <div class="card-tools">
		                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
		                    <i class="fas fa-minus"></i>
		                  </button>
		                  <button type="button" class="btn btn-tool" data-card-widget="remove">
		                    <i class="fas fa-times"></i>
		                  </button>
		                </div>
		              </div>
		              <div class="card-body" >

		              	<form id="ReportForm" method="POST" enctype="multipart/form-data">
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
										      <label for="employee">Employees</label><br>
										      <select class="selectpicker" name="employee" id="employee" data-live-search="true" data-width="350px">
									      		<option value="option_select"selected>	All Employee</option>
									      		@foreach($salesBy as $emp)
							            	<option value="{{ $emp->id }}">
							            		{{ $emp->name }}
							            	</option>
							        			@endforeach
									      	</select>
										    </div>
										    <div style="padding-top: 32px;" class="form-group col-md-3">
										      <button type="submit" class="btn btn-primary">Generate</button>
										      <button type="reset" value="Reset" class="btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
										    </div>
										    
										  </div>
										   <small id="emailHelp" class="form-text text-muted">Press the Generate button to generate overall report.</small>
										   <small id="emailHelp" class="form-text text-muted">1. Generate Report between dates.</small>
									     <small id="emailHelp" class="form-text text-muted">2. Generate Report of specific date.</small>
										   <small id="emailHelp" class="form-text text-muted">3. Generate Report of specific employee.</small>
										   <small id="emailHelp" class="form-text text-muted">4. Generate Report of specific date and employee.</small>
										   <small id="emailHelp" class="form-text text-muted">5. Generate Report between dates and specific employee.</small>
										</form>

		                <div class="chart"  style="height: ; width: 100%;">
		                  <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
					  					<table id="product_table" class="display">
											  <thead>
											    <tr>
											    	<th scope="col">#</th>
											      <th scope="col">Name</th>
											      <th scope="col">Gross Sales</th>
											      <th scope="col">Discounts</th>
											      <th scope="col">Net Sales</th>
											      <th scope="col">Average Sale</th>
											      <th scope="col">Order Count</th>
											    </tr>
											  </thead>
											  <tbody>
											    
											  </tbody>
											</table>
		                  

		                </div>
		              </div>
		              <!-- /.card-body -->
		            </div>
		            <!-- /.card -->
		        	</div>   <!-- /.col-lg-6 -->
        		</div><!-- /.row -->
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection

@section('script')
<!-- <script src="{{ asset('dist/js/highcharts/highcharts.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/data.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/drilldown.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/exporting.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/export-data.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/accessibility.js')}}"></script> -->

<script type="text/javascript" src="js/employee-report.js"></script>
<script type="text/javascript">

</script>


@endsection


	
