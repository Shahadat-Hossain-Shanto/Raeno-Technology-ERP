@extends('layouts.master')
@section('title', 'Attendance')

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
      			<div class="col-lg-12">
          			<div class="card card-primary">
			            <div class="card-header">
			                <h5 class="m-0"><strong><i class="far fa-calendar-alt"></i> ATTENDANCE</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<!-- <div class="container"> -->
	          					<div class="row">
	          						<div class="col-2">
	          							<input type="date" id="date" name="date" max="<?= date('Y-m-d'); ?>" class="form-control">
	          						</div>
	          						<div class="col-8">
	          							<h4 class=""></h4>
	          						</div>
	          						<div class="col-2">
	          							<!-- <input type="file" id="file" name="file" class="form-control float-right"> -->
	          						</div>
	          					</div>
									
	          					<div class="row">
	          						<div class="col-12">
				                    	<div class="table-responsive pt-3">
											<table id="attendance_table" class="table table-bordered" width="100%">
											    <thead>
											        <tr>
										        		<th width="5%">#</th>
											            <th width="15">Employee</th>
											            <th width="12%">Designation</th>
											            <th width="10%">Department</th>
											            <th width="10%">Date</th>
											            <th width="12%">SignIn</th>
											            <th width="12%">SignOut</th>
											            <th width="10%">Staytime</th>
											            <th width="10%">Status</th>
											            <th class="hidden"></th>
											            <th width="4%"></th>
											        </tr>
											    </thead>
											    <tbody>

											    </tbody>
									    	</table>
									  	</div>
									</div>
	          					</div>
	          					<div class="row pt-4">
	          						<div class="col-12">
	          							<button type="button" id="save" class="btn btn-primary float-right" onclick="processData();">Save</button>
	          						</div>
	          					</div>

								

							<!-- </div> container -->
						</div> <!-- card-body -->
		          	</div> <!-- card card-primary card-outline -->
      			</div> <!-- col-lg-5 -->
      		</div> <!-- row -->
		</div> <!-- container-fluid -->
	</div> <!-- content -->
	
</div> <!-- content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="js/attendance.js"></script>

@endsection