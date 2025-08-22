@extends('layouts.master')
@section('title', 'Create Weekend')

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
			                <h5 class="m-0"><strong><i class="fas fa-umbrella-beach"></i> WEEKEND</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddWeekendForm" method="POST" enctype="multipart/form-data">
									
								  	<div class="form-group">
									    <label for="weekendname" class="form-label" style="font-weight: normal;">Weekend Day<span class="text-danger"><strong>*</strong></span></label>
									   	<select class="form-select w-50" aria-label="Default select example" id="weekendname" name="weekendname">
											<option value="option_select" selected disabled>Select Weekend Day</option>
											<option value="Sunday">Sunday</option>
											<option value="Monday">Monday</option>
											<option value="Tuesday">Tuesday</option>
											<option value="Wednesday">Wednesday</option>
											<option value="Thursday">Thursday</option>
											<option value="Friday">Friday</option>
											<option value="Saturday">Saturday</option>
										</select>
									    <h6 class="text-danger pt-1" id="wrongweekendname" style="font-size: 14px;"></h6>
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
<script type="text/javascript" src="js/weekend.js"></script>

@endsection