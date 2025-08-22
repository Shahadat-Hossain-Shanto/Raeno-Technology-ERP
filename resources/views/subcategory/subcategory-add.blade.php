@extends('layouts.master')
@section('title', 'Create Sub-category')

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
			                <h5 class="m-0"><strong><i class="fas fa-plus"></i> SUBCATEGORY</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddSubcategoryForm" method="POST" enctype="multipart/form-data">
									<div class="form-group">
									    <label for="categoryid" class="form-label" style="font-weight: normal;">Category Name<span class="text-danger"><strong>*</strong></span></label><br>

									    <select class="selectpicker" data-live-search="true" aria-label="Default select example" name="categoryid" data-width="380px">
										  	<option value="option_select" disabled selected>Select Category</option>
										  	@foreach($categories as $category)
							            	<option value="{{ $category->id }}">{{ $category->category_name }}</option>
							        		@endforeach
										</select>
										<h6 class="text-danger pt-1" id="wrongcategoryid" style="font-size: 14px;"></h6>
								  	</div>

									<div class="form-group">
									    <label for="subcategoryname" class="form-label" style="font-weight: normal;">Sub-category Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="subcategoryname" id="subcategoryname" placeholder="e.g. Headphone">
									    <div id="" class="form-text"><strong>N.B. </strong>Be sure to make your sub-category name meaningful.</div>

									    <h6 class="text-danger pt-1" id="wrongsubcategoryname" style="font-size: 14px;"></h6>
								  	</div>

								  	<div class="form-group pt-3">
									  	<button type="submit" class="btn btn-primary">Create</button>
										<button type="reset" value="Reset" class="btn btn-outline-danger" onclick="resetButton();"><i class="fas fa-eraser"></i> Reset</button>
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
<script type="text/javascript" src="js/subcategory.js"></script>

@endsection