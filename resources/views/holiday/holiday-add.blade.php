@extends('layouts.master')
@section('title', 'Create Holiday')

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
			                <h5 class="m-0"><strong><i class="fas fa-umbrella-beach"></i> HOLIDAY</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddHolidayForm" method="POST" enctype="multipart/form-data">
									
								  	<div class="form-group">
									    <label for="holidayname" class="form-label" style="font-weight: normal;">Holiday Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="holidayname" id="holidayname" placeholder="e.g. Eid-ul-Adha Vacation">
									    <h6 class="text-danger pt-1" id="wrongholidayname" style="font-size: 14px;"></h6>
								  	</div>

								  	<div class="form-group">
									    <label for="startdate" class="form-label" style="font-weight: normal;">Start Date<span class="text-danger"><strong>*</strong></span></label>
									    <input type="date" class="form-control w-50" name="startdate" id="startdate">
									    <h6 class="text-danger pt-1" id="wrongstartdate" style="font-size: 14px;"></h6>
								  	</div>

								  	<div class="form-group">
									    <label for="enddate" class="form-label" style="font-weight: normal;">End Date<span class="text-danger"><strong>*</strong></span></label>
									    <input type="date" class="form-control w-50" name="enddate" id="enddate">
									    <h6 class="text-danger pt-1" id="wrongenddate" style="font-size: 14px;"></h6>
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
<script type="text/javascript" src="js/holiday.js"></script>

@endsection