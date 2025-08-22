@extends('layouts.master')
@section('title', 'Product Reports')

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
		                	<h5 class="m-0"><strong><i class="fas fa-file-contract"></i> SALES BY PRODUCT</strong></h5>
		              </div>
		              <div class="card-body">
	                	
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
									      		<option value="option_select" selected>	All Employee</option>
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
										   <small id="" class="form-text text-muted">Click the Generate button to generate overall report.</small>
										   <small id="" class="form-text text-muted">1. Generate Report between dates.</small>
									     <small id="" class="form-text text-muted">2. Generate Report of specific date.</small>
										   <small id="" class="form-text text-muted">3. Generate Report of specific employee.</small>
										   <small id="" class="form-text text-muted">4. Generate Report of specific date and employee.</small>
										   <small id="" class="form-text text-muted">5. Generate Report between dates and specific employee.</small>
										</form>

		              </div> <!-- Card-body -->
		            </div>	<!-- Card -->
		          </div>   <!-- /.col-lg-6 -->
        		</div><!-- /.row -->

        		<div class="row">
	          	<div class="col-lg-6">
		            <!-- BAR CHART -->
		            <div class="card card-info">
		              <div class="card-header">
		                <h3 class="card-title">Top Products Chart</h3>

		                <div class="card-tools" >
		                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
		                    <i class="fas fa-minus"></i>
		                  </button>
		                  <button type="button" class="btn btn-tool" data-card-widget="remove">
		                    <i class="fas fa-times"></i>
		                  </button>
		                </div>
		              </div>
		              <div class="card-body" >
		                <div class="chart"  style="height: 400px; width: 100%;">
		                  <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
		                  <!-- <p class="chartp" style="text-align: center;">Submit to generate the report</p> -->
		                  <figure class="highcharts-figure">
											    <div id="container1"></div>
											    <p class="highcharts-description">
											        
											    </p>
											</figure>

		                </div>
		              </div>
		              <!-- /.card-body -->
		            </div>
		            <!-- /.card -->
		        	</div>   <!-- /.col-lg-6 -->

			        <div class="col-lg-6">
			            <!-- BAR CHART -->
			            <div class="card card-info">
			              <div class="card-header">
			                <h3 class="card-title">Sales-by Items Chart</h3>

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
			                <div class="chart"  style="height: 400px; width: 100%;">
			                  <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
			                  <figure class="highcharts-figure">
											    <div id="container2"></div>
											    <p class="highcharts-description">
											        
											    </p>
												</figure>
			                </div>
			              </div>
			              <!-- /.card-body -->
			            </div>
			            <!-- /.card -->
		            
			        </div>   <!-- /.col-lg-6 -->
        		</div><!-- /.row -->


        		<div class="row pt-2">
        			<div class="col-lg-12">
		            <!-- BAR CHART -->
		            <div class="card card-secondary">
		              <div class="card-header">
		                <h3 class="card-title">Products</h3>

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
		                <div class="chart"  style="height: ; width: 100%;">
		                  <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
		                  <h4> <strong><u>List of Products</u></strong></h4>
					  					<table id="product_table" class="display">
											  <thead>
											    <tr>
											    	<th scope="col">#</th>
											      <th scope="col">Product Name</th>
											      <th scope="col">Quantity</th>
											      <th scope="col">Price</th>
											      <th scope="col">Discount</th>
											      <th scope="col">Tax</th>
											      <th scope="col">Total</th>
											    </tr>
											  </thead>
											  <!-- <tbody>
											    
											  </tbody> -->
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
<script src="{{ asset('dist/js/highcharts/highcharts.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/data.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/drilldown.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/exporting.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/export-data.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/accessibility.js')}}"></script>

<script type="text/javascript" src="js/report1.js"></script>
@endsection


	
