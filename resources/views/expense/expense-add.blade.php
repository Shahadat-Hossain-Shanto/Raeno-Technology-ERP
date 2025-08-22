@extends('layouts.master')
@section('title', 'Create Expense')

@section('content')
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<!-- Header -->
			</div>
		</div>
	</div>

	<div class="content">
		<div class="container-fluid ">
			<div class="row">
      			<div class="col-lg-6">
          			<div class="card card-primary">
			            <div class="card-header">
			                <h5 class="m-0"><strong><i class="fas fa-hand-holding-usd"></i> Expense</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddExpenseForm" method="POST" enctype="multipart/form-data">
									
								  	<div class="form-group">
									    <label for="expensecategory" class="form-label" style="font-weight: normal;">Expense Type<span class="text-danger"><strong>*</strong></span></label><br>
									    <select class="selectpicker" data-width="379px" data-live-search="true" aria-label="Default select example" name="expensecategory" id="expensecategory" title="Select expense type">
										  	@foreach($expense_types as $expense_type)
							            		<option value="{{ $expense_type->expense_type_name }}">{{ $expense_type->expense_type_name }}</option>
							        		@endforeach
										</select>
									    <h6 class="text-danger pt-1" id="wrongexpensecategory" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="expenseamount" class="form-label" style="font-weight: normal;">Expense Amount<span class="text-danger"><strong>*</strong></span></label>
									    <input type="number" step="any" min="1" class="form-control w-50" name="expenseamount" id="expenseamount" placeholder="e.g. 100">
									    <h6 class="text-danger pt-1" id="wrongexpenseamount" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="store" class="form-label" style="font-weight: normal;">Store<span class="text-danger"><strong>*</strong></span></label><br>
									    <select class="selectpicker" data-width="379px" data-live-search="true" aria-label="Default select example" name="store" id="store" title="Select store">
										  	@foreach($stores as $store)
							            		<option value="{{ $store->id }}">{{ $store->store_name }}</option>
							        		@endforeach
										</select>
									    <h6 class="text-danger pt-1" id="wrongstore" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="expensedate" class="form-label" style="font-weight: normal;">Date<span class="text-danger"><strong>*</strong></span></label>
									    <input type="date" class="form-control w-50" name="expensedate" id="expensedate">
									    <h6 class="text-danger pt-1" id="wrongexpensedate" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="note" class="form-label" style="font-weight: normal;">Note</label>
									    <textarea class="form-control w-50" id="note" name="note" rows="2" placeholder="Any notes for expense"></textarea>
								  	</div>
								  	
								  	<div class="form-group">
									    <label for="expenseimage" class="form-label" style="font-weight: normal;">Expense Image </label>
									    <input type="file" class="form-control w-50" name="expenseimage" id="expenseimage">
									    <div id="" class="form-text"><strong>e.g. </strong>bill, receipt, any other images of expense.</div>
								  	</div>
								  	
								  	<div class="form-group pt-3">
									  	<button type="submit" class="btn btn-primary">Create</button>
										<button type="reset" value="Reset" class="btn btn-outline-danger"><i class="fas fa-eraser"></i> Reset</button>
								  	</div>
	
								</form>

							</div> <!-- container -->
						</div> <!-- card-body -->
		          	</div> <!-- card card-primary card-outline -->
      			</div> <!-- col-lg-5 -->
      		</div> <!-- row -->
		</div> <!-- container-fluid -->
	</div> <!-- content -->
	
</div> <!-- content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="js/expense.js"></script>

@endsection