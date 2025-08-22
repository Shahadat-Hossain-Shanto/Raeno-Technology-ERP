@extends('layouts.master')
@section('title', 'Create Category')

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
          			<div class="card card-primary ">
			            <div class="card-header">
			                <h5 class="m-0"><strong><i class="fas fa-plus"></i> CATEGORY</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddCategoryForm" method="POST">

									<div class="form-group">
									    <label for="categoryname" class="form-label" style="font-weight: normal;">Category Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="categoryname" id="categoryname" placeholder="e.g. Electronics">
									    <div id="" class="form-text"><strong>N.B. </strong>Be sure to make your category name meaningful.</div>
									    <h6 class="text-danger pt-1" id="wrongcategoryname" style="font-size: 14px;"></h6>
								  	</div>

								  	{{-- <div class="form-group">
									    <label for="categoryimage" class="form-label" style="font-weight: normal;">Category Image<span class="text-danger"><strong></strong></span></label>
									    <input type="file" class="form-control w-50" name="categoryimage" id="categoryimage">
								  	</div> --}}

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
<script type="text/javascript" src="js/category.js"></script>

@endsection
