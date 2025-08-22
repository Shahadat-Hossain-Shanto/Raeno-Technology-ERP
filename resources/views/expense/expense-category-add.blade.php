@extends('layouts.master')
@section('title', 'Create Expense Category')

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
			                <h5 class="m-0"><strong><i class="fas fa-coins"></i> Expense Type</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddExpenseCategoryForm" method="POST" enctype="multipart/form-data">

								  	<div class="form-group">
									    <label for="expensecategoryname" class="form-label" style="font-weight: normal;">Expense Type Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="expensecategoryname" id="expensecategoryname" placeholder="e.g. Lunch">
									    <h6 class="text-danger pt-1" id="wrongexpensecategoryname" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="note" class="form-label" style="font-weight: normal;">Description</label>
									    <textarea class="form-control w-50" id="note" name="note" rows="2" placeholder="Any notes"></textarea>

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
<script type="text/javascript" src="js/expense-category.js"></script>

@endsection
