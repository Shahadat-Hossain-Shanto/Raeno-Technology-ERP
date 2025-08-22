@extends('layouts.master')
@section('title', 'Apply Leave')

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
			                <h5 class="m-0"><strong><i class="fas fa-walking"></i> APPLY LEAVE</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddLeaveApplyForm" method="POST" enctype="multipart/form-data">
									
								  	<div class="form-group">
									    <label for="employee" class="form-label" style="font-weight: normal;">Employee Name<span class="text-danger"><strong>*</strong></span></label><br>
									    <select class="selectpicker" data-width="50%" title="Select Employee" data-live-search="true" aria-label="Default select example" name="employee" id="employee" onchange="">
							                @foreach($employees as $employee)
							                  <option value="{{ $employee->id }}">{{ $employee->employee_name  }}</option>
							                @endforeach
							            </select>
									    <h6 class="text-danger pt-1" id="wrongemployee" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="leavetype" class="form-label" style="font-weight: normal;">Leave Type<span class="text-danger"><strong>*</strong></span></label><br>
									    <select class="selectpicker" data-width="50%" title="Select Type" data-live-search="true" aria-label="Default select example" name="leavetype" id="leavetype" onchange="">
							                @foreach($leaveTypes as $leaveType)
							                  <option value="{{ $leaveType->id }}">{{ $leaveType->leave_type  }}</option>
							                @endforeach
							            </select>
									    <h6 class="text-danger pt-1" id="wrongleavetype" style="font-size: 14px;"></h6>
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

								  	 <div class="form-group">
									    <label for="daycount" class="form-label" style="font-weight: normal;">Total Leave Days<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-25" name="daycount" id="daycount" readonly>
								  	</div> 

								  	<div class="form-group">
									    <label for="note" class="form-label" style="font-weight: normal;">Note</label>
									    <textarea class="form-control w-50" id="note" name="note" rows="2" placeholder="Any notes for leave"></textarea>
								  	</div>
								  	
								  	
								  	<div class="form-group pt-3">
									  	<button type="submit" class="btn btn-primary">Create</button>
										<button type="reset" value="Reset" class="btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
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
<script type="text/javascript" src="js/leave-apply.js"></script>

@endsection