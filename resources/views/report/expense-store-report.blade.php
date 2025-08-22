@extends('layouts.master')
@section('title', 'Expense Report(Store Wise)')

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
		                <h3 class="card-title"><strong><i class="fas fa-file-contract"></i> EXPENSE REPORT (STORE WISE)</strong></h3>

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
		              	<form id="ExpenseStoreReport" method="POST" enctype="multipart/form-data">
				            	<!-- <small id="" class="form-text text-muted mb-0 pt-5">Genrate Custom Expense Report</small> -->
				            	<!-- <h5 class="pt-5" style="font"><strong><u>Select store for store wise expense report.</u></strong></h5> -->
				            	<!-- <div class="form-text pt-5">Select store for store wise expense report.</div> -->

	              		{{-- <div class="row">
				              <div class="col-12 col-sm-6 col-md-3">
				                <div class="info-box">
				                  <span class="info-box-icon bg-dark elevation-1"><i style="color: white;" class="fas fa-file-invoice-dollar"></i></span>

				                  <div class="info-box-content">
				                    <h5 class="info-box-text mb-1" style=""><strong>Total Expense</strong></h5>
				                    <h6 class="info-box-text mb-0">Amount: <strong><span id="totalExpense">{{ number_format(0, 2) }}</span></strong> </h6>

				                  </div><!-- /.info-box-content -->
				                </div><!-- /.info-box -->
				              </div><!-- /.col -->
				              <div class="col-12 col-sm-6 col-md-3">
				                <div class="info-box">
				                  <span class="info-box-icon bg-secondary elevation-1"><i style="color: white;" class="fas fa-file-invoice-dollar"></i></span>

				                  <div class="info-box-content">
				                    <h5 class="info-box-text mb-1" style=""><strong>Salary</strong></h5>
				                    <h6 class="info-box-text mb-0">Amount: <strong><span id="totalSalary">{{ number_format(0, 2) }}</span></strong></h6>

				                  </div><!-- /.info-box-content -->
				                </div><!-- /.info-box -->
				              </div><!-- /.col -->
				              <div class="col-12 col-sm-6 col-md-3">
				                <div class="info-box">
				                  <span class="info-box-icon bg-danger elevation-1"><i style="color: white;" class="fas fa-file-invoice-dollar"></i></span>

				                  <div class="info-box-content">
				                    <h5 class="info-box-text mb-1" style=""><strong>Purchase</strong></h5>
				                    <h6 class="info-box-text mb-0">Amount: <strong><span id="totalPurchase">{{ number_format(0, 2) }}</span></strong> </h6>

				                  </div><!-- /.info-box-content -->
				                </div><!-- /.info-box -->
				              </div><!-- /.col -->
				        </div>
				        <div class="row">
				              <div class="col-12 col-sm-6 col-md-3">
				                <div class="info-box">
				                  <span class="info-box-icon bg-warning elevation-1"><i style="color: white;" class="fas fa-file-invoice-dollar"></i></span>

				                  <div class="info-box-content">
				                    <h5 class="info-box-text mb-1" style=""><strong>Rent</strong></h5>
				                    <h6 class="info-box-text mb-0">Amount: <strong><span id="totalRent">{{ number_format(0, 2) }}</span></strong> </h6>
				                  </div><!-- /.info-box-content -->
				                </div><!-- /.info-box -->
				              </div><!-- /.col -->
				              <div class="col-12 col-sm-6 col-md-3">
				                <div class="info-box">
				                  <span class="info-box-icon bg-info elevation-1"><i style="color: white;" class="fas fa-file-invoice-dollar"></i></span>

				                  <div class="info-box-content">
				                    <h5 class="info-box-text mb-1" style=""><strong>Bills</strong></h5>
				                    <h6 class="info-box-text mb-0">Amount: <strong><span id="totalBill">{{ number_format(0, 2) }}</span></strong> </h6>

				                  </div><!-- /.info-box-content -->
				                </div><!-- /.info-box -->
				              </div><!-- /.col -->
				              <div class="col-12 col-sm-6 col-md-3">
				                <div class="info-box">
				                  <span class="info-box-icon bg-primary elevation-1"><i style="color: white;" class="fas fa-file-invoice-dollar"></i></span>

				                  <div class="info-box-content">
				                    <h5 class="info-box-text mb-1" style=""><strong>Others</strong></h5>
				                    <h6 class="info-box-text mb-0">Amount: <strong><span id="totalOther">{{ number_format(0, 2) }}</span></strong> </h6>

				                  </div><!-- /.info-box-content -->
				                </div><!-- /.info-box -->
				              </div><!-- /.col -->
				        </div> --}}

				            <h6 class="pt-3" style="font"><strong><u>Select store for store wise expense report.</u></strong></h6>
				            <div class="form-group row pt-3">
									    <!-- <label for="store" class="col-sm-1 col-form-label" style="font-weight: normal;">Select Store<span class="text-danger"><strong>*</strong></span></label> -->
									    <div class="col-sm-8">
									      <select class="selectpicker" data-live-search="true" aria-label="Default select example" name="store"
									      id="store">
									      	<option value="option_select" selected>Select Store</option>
									      	@foreach($stores as $store)
							            	<option value="{{ $store->id  }}">{{ $store->store_name  }}</option>
							        		@endforeach
									      </select>
									    </div>
							  		</div>


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
										      <label for="employee" style="font-weight:normal;">Expense Type</label><br>
										      <select class="selectpicker" data-live-search="true" data-width="100%" name="expensetype" id="expensetype">
									      		<option value="option_select" selected>	All Expenses</option>
									      		@foreach($expenseTypes as $expenseType)
									      		<option value="{{ $expenseType->expense_type }}">{{ $expenseType->expense_type }}</option>
									      		@endforeach
									      	</select>
										    </div>
										    <div class="form-group col-md-2">
										      <label for="employee" style="font-weight:normal;">By Employee</label><br>
										      <select class="selectpicker" data-live-search="true" data-width="100%" name="byemployee" id="byemployee">
									      		<option value="option_select" selected>	All Employee</option>
									      		@foreach($employees as $employee)
									      		<option value="{{ $employee->name }}">{{ $employee->name }} ({{ $employee->store_name }})</option>
									      		@endforeach
									      	</select>
										    </div>
										    <div style="padding-top: 32px;" class="form-group col-md-3">
										      <button id="gen_btn" type="submit" class="btn btn-primary">Generate</button>
										      <button type="reset" value="Reset" class="btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
										    </div>

										  </div>
										</form>
		                <hr>
		                <div class="row">
		                	<div class="col-12">
		                		<h4 class="pt-2"><strong><u>Expenses</u></strong></h4>
		                		<div class="pt-2">
		                			<table id="expense_table" class="table table-bordered">
													  <thead>
													    <tr>
													      <th width="5%">#</th>
													      <th width="15%">Type</th>
													      <th width="10%">Amount</th>
													      <th width="10%">Store</th>
													      <th width="10%">Date</th>
													      <th width="15%">Note</th>
													      <th width="10%">Submitted By</th>
													      <th width="20%">Image</th>

													    </tr>
													  </thead>
													  <tbody>

													  </tbody>
													</table>
		                		</div>

		                	</div>
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

<!-- Expense Image Modal -->
<div class="modal fade" id="ExpenseImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content"  style="background-color: transparent !important;">



      <div class="modal-body">
      	<img class="mb-2" src="" alt="expense image" width="550px" height="650px"  name="expenseimage" id="expenseimage">

      	<button id="close" type="button" class="btn btn-danger float-right" data-dismiss="modal">Close</button>
      </div>



    </div>
  </div>
</div>

@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/expense-store-report.js') }}"></script>
<script type="text/javascript">
	$(document).on('click', '#close', function (e) {
		$('#ExpenseImageModal').modal('hide');
	});
</script>

@endsection


