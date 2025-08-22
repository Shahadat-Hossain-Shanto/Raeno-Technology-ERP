@extends('layouts.master')
@section('title', 'Create Employee Department Create')

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
			                <h5 class="m-0"><strong><i class="fas fa-barcode"></i> EMPLOYEE DEPARTMENT</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddEmployeeDepartmentForm" method="POST" enctype="multipart/form-data">
									
								  	<div class="form-group">
									    <label for="departmentname" class="form-label" style="font-weight: normal;">Department Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="departmentname" id="departmentname" placeholder="e.g. General">
									    <h6 class="text-danger pt-1" id="wrongdepartmentname" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="jobdescription" class="form-label" style="font-weight: normal;">Job Description<span class="text-danger"><strong></strong></span></label>
									    <textarea rows="2" placeholder="e.g. For General purpose" class="form-control w-50" name="jobdescription" id="jobdescription"></textarea>
									    {{-- <h6 class="text-danger pt-1" id="wrongjobdescription" style="font-size: 14px;"></h6> --}}
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
<script type="text/javascript" src="js/employee-department.js"></script>

@endsection