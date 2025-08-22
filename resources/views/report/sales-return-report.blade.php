@extends('layouts.master')
@section('title', 'Sales Return Report')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> SALES RETURN REPORT</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->

	                	<input type="hidden" name="" id="subscriberid" value="{{auth()->user()->subscriber_id}}">
	                	<form id="SalesReturnReport" method="POST" enctype="multipart/form-data">
	                		<div class="form-row pt-3">

										    <div class="form-group col-md-2">

										      <label for="startdate" style="font-weight:normal;">From</label>
										      <input type="date" class="form-control" id="startdate" name="startdate">
										    </div>
										    <div class="form-group col-md-2">
										      <label for="enddate" style="font-weight:normal;">To</label>
										      <input type="date" class="form-control" id="enddate" name="enddate">
										    </div>
										    <div class="form-group col-md-2">
										      <label for="store" style="font-weight:normal;">Store</label><br>
										      <select class="selectpicker" data-live-search="true" aria-label="Default select example" name="store" id="store" data-width="250px">
									      	<option value="option_select" selected>All Store</option>
									      	<option value="0">Warehouse</option>
									      	@foreach($stores as $store)
							            	<option value="{{ $store->id  }}">{{ $store->store_name  }}</option>
							        		@endforeach
									      </select>
										    </div>
										    <div style="padding-top: 32px;" class="form-group col-md-2">
										      <button id="gen_btn" type="submit" class="btn btn-primary">Generate</button>
										      <button type="reset" value="Reset" class="btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
										    </div>

										  </div>
	                	</form>

										  <div class="pt-3">
												<table id="return_table" class="table table-bordered">
												    <thead>
												        <tr>
												        		<th width="4%">#</th>
												            <th width="10%">Invoice No.</th>
												            <th width="10%">Return No.</th>
												            <th width="10%">Customer</th>
												            <th width="9%">Mobile</th>
												            <th width="14%">Product</th>
												            <th width="8%">Return Qty</th>
												            <th width="8%">Deduction</th>
												            <th width="6%">Tax</th>
												            <th width="8%">Net Return</th>
												            <th width="12%">Note</th>

												        </tr>
												    </thead>
												    <!-- <tbody>

												    </tbody> -->
											    </table>
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
<script type="text/javascript" src="js/sales-return-report.js"></script>
<script type="text/javascript">

</script>

@endsection



