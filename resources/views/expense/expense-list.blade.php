@extends('layouts.master')
@section('title', 'Expenses')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> EXPENSES</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/expense-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Expense</button></a>
	                	
	                	<input type="hidden" name="" id="subscriberid" value="{{auth()->user()->subscriber_id}}">
	                    <div class="pt-3">
	                    	<div class="table-responsive">
													<table id="expense_table" class="display" width="100%">
												    <thead>
												        <tr>
												        		<th>#</th>
												            <th class="">Expense Type</th>
												            <th class="">Amount</th>
												            <th class="">Store</th>
												            <th class="">Date</th>
												            <th>Note</th>
												            <th>Image</th>
												            <th>Action</th>
												        </tr>
												    </thead>
												    <tbody>

												    </tbody>
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

<!-- Edit Expense Modal -->
<div class="modal fade" id="EDITExpenseMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE EXPENSE</strong></h5>	        
      </div>

      <!-- Update Expense Form -->
      <form id="UPDATEExpenseFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="expenseid" id="expenseid">

      		<div class="form-group mb-3">
      			<label>Expense Type<span class="text-danger"><strong>*</strong></span></label>
      			<select class="selectpicker" data-width="379px" data-live-search="true" aria-label="Default select example" name="expensecategory" id="edit_expensecategory" title="Select expense type">
				  	@foreach($expense_types as $expense_type)
	            		<option value="{{ $expense_type->expense_type_name }}">{{ $expense_type->expense_type_name }}</option>
	        	@endforeach
						</select>
						<h6 class="text-danger pt-1" id="edit_wrongexpensecategory" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
      			<label for="expenseamount" class="form-label" style="font-weight: normal;">Expense Amount<span class="text-danger"><strong>*</strong></span></label>
				    <input type="number" class="form-control w-50" name="expenseamount" id="edit_expenseamount" placeholder="">
				    <h6 class="text-danger pt-1" id="edit_wrongexpenseamount" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
				    <label for="store" class="form-label" style="font-weight: normal;">Store<span class="text-danger"><strong>*</strong></span></label><br>
				    <select class="selectpicker" data-width="379px" data-live-search="true" aria-label="Default select example" name="store" id="edit_store" title="Select store">
					  	@foreach($stores as $store)
		            <option value="{{ $store->id }}">{{ $store->store_name }}</option>
		        	@endforeach
						</select>
				    <h6 class="text-danger pt-1" id="edit_wrongstore" style="font-size: 14px;"></h6>
			  	</div>

			  	<div class="form-group mb-3">
				    <label for="expensedate" class="form-label" style="font-weight: normal;">Date<span class="text-danger"><strong>*</strong></span></label>
				    <input type="date" class="form-control w-50" name="expensedate" id="edit_expensedate">
				    <h6 class="text-danger pt-1" id="edit_wrongexpensedate" style="font-size: 14px;"></h6>
			  	</div>

      		<div class="form-group mb-3">
      			<label>Notes</label>
      			<textarea class="form-control" id="edit_note" name="note" rows="2" placeholder="Any notes"></textarea>
      		</div>

      		<div class="form-group pt-3">
				    <img src="" alt="expense image" width="100px" height="100px" class="rounded-circle pb-3" name="image" id="edit_image">
			  	</div>
			  	<div class="form-group">
				    <label for="expenseimage" class="form-label">Image </label>
				    <input type="file" class="form-control" name="expenseimage" id="edit_expenseimage">
			  	</div>
      			       
	    	</div>
		    <div class="modal-footer">
		        <button id="close" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-primary">Update</button>
		    </div>
      </form>
      <!-- End Update Expense Form -->

    </div>
  </div>
</div>
<!-- End Edit Expense Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEExpenseMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEExpenseFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="expenseid"> 
			      <h5 class="text-center">Are you sure you want to delete?</h5>
			    </div>

			    <div class="modal-footer justify-content-center">
			        <button type="button" class="cancel_btn btn btn-secondary" data-dismiss="modal">Cancel</button>
			        <button type="submit" class="delete btn btn-outline-danger">Yes</button>
			    </div>

			</form>

		</div>
	</div>
</div>

<!-- END Delete Modal -->

@endsection

@section('script')
<script type="text/javascript" src="js/expense.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITExpenseMODAL').modal('hide');
		$('#edit_wrongexpensecategory').empty();
		$('#edit_wrongexpenseamount').empty();
		$('#edit_wrongstore').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEExpenseMODAL').modal('hide');
	});
</script>

@endsection


	
