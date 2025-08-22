@extends('layouts.master')
@section('title', 'Orders')

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
		                	<h5 class="m-0"><strong><i class="fas fa-history"></i> ORDERS</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->

	                	<!-- <a href="/product-create"><button type="button" class="btn btn-primary">Create Product</button></a> -->

<div>
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

								  <div style="padding-top: 32px;" class="form-group col-md-3">
									<button type="submit" class="btn btn-primary">Generate</button>
									<button type="reset" value="Reset" class="btn btn-outline-danger"><i class="fas fa-eraser"></i> Reset</button>
								  </div>

								</div>
							{{-- <small id="" class="form-text text-muted">Click the Generate button to generate overall report.</small>
								 <small id="" class="form-text text-muted">1. Generate Report between dates.</small>
							   <small id="" class="form-text text-muted">2. Generate Report of specific date.</small>
								 <small id="" class="form-text text-muted">3. Generate Report of specific employee.</small>
								 <small id="" class="form-text text-muted">4. Generate Report of specific date and employee.</small>
								 <small id="" class="form-text text-muted">5. Generate Report between dates and specific employee.</small> --}}
			 			    </form>
						</div>

	                    <div class="pt-2">
	                    	<div class="table-responsive">
												<table id="order_table" class="table table-bordered" width="100%">
												    <thead>
												        <tr>
												        		<th width="5%">#</th>
												            <th width="15%">OrderID</th>
												            <th width="15%">Client Name</th>
												            <th width="11%">Client Contact</th>
												            <th width="10%">Order Date</th>
												            <th width="10%">Total</th>
												            <th width="10%">Discount</th>
												            <th width="10%">Special Discount</th>
												            <th width="5%">Total Tax</th>
												            <th width="10%">Grand Total</th>
												            <th width="12%">Return Amount</th>
												            <th width="10%">Action</th>
												        </tr>
												    </thead>
												    <!-- <tbody>

												    </tbody> -->
											    </table>
											  </div>
											</div>

		              </div> <!-- Card-body -->
		            </div>	<!-- Card -->

		        </div>   <!-- /.col-lg-6 -->
        	</div><!-- /.row -->
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="js/order.js"></script>
<script type="text/javascript">
</script>

@endsection



