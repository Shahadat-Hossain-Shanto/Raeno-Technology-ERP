@extends('layouts.master')
@section('title', 'Shifts')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> SHIFTS</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/shift-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Shift</button></a>
	                	
	                	<input type="hidden" name="" id="subscriberid" value="{{auth()->user()->subscriber_id}}">
	                    <div class="pt-3">
	                    	<div class="table-responsive">
												<table id="shift_table" class="display" width="100%">
												    <thead>
												        <tr>
												        		<th width="5%">#</th>
												            <th width="20%">Shift Name</th>
												            <th width="15%">In-Time</th>
												            <th width="15%">Out-Time</th>
												            <th width="15%">Allocation</th>
												            <th width="15%">Action</th>
												            
												        </tr>
												    </thead>
												    <!-- <tbody>

												    </tbody> -->
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

<!-- Edit Shift Modal -->
<div class="modal fade" id="EDITShiftMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE SHIFT</strong></h5>	        
      </div>


      <!-- Update Shift Form -->
      <form id="UPDATEShiftFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="shiftid" id="shiftid">

      		<div class="form-group mb-3">
      			<label>Shift Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_shiftname" name="shiftname" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongshiftname" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
      			<label>In-Time<span class="text-danger"><strong>*</strong></span></label>
      			<input type="time" id="edit_intime" name="intime" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongintime" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
      			<label>Out-Time<span class="text-danger"><strong>*</strong></span></label>
      			<input type="time" id="edit_outtime" name="outtime" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongouttime" style="font-size: 14px;"></h6>
      		</div>
      			       
	    	</div>
		    <div class="modal-footer">
		        <button id="close" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-primary">Update</button>
		    </div>
      </form>
      <!-- End Update Shift Form -->

    </div>
  </div>
</div>
<!-- End Edit Shift Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEShiftMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEShiftFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="shiftid"> 
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


<!-- Allocate Shift Modal -->
<div class="modal fade" id="ALLOCATEShiftMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>ALLOCATE EMPLOYEE TO SHIFT</strong></h5>	        
      </div>


      <!-- Allocate Shift Form -->
      <form id="ALLOCATEShiftFORM" enctype="multipart/form-data">
      	
      	<!-- <input type="hidden" name="_method" value="PUT"> -->
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="shiftid" id="shiftid">

      		<div class="form-group mb-3">
      			<label>Employee<span class="text-danger"><strong>*</strong></span></label><br>
      			<select class="selectpicker" data-width="100%" title="Select Employee" data-live-search="true" aria-label="Default select example" name="employee" id="employee" onchange="">
                @foreach($employees as $employee)
                  <option value="{{ $employee->id }}">{{ $employee->employee_name  }}</option>
                @endforeach
            </select>
						<h6 class="text-danger pt-1" id="wrongemployee" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
      			<label>Shift Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="allocateshiftname" name="allocateshiftname" class="form-control" readonly>
						<h6 class="text-danger pt-1" id="wrongallocateshiftname" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
      			<label>Start Date<span class="text-danger"><strong>*</strong></span></label>
      			<input type="date" id="startdate" name="startdate" class="form-control">
						<h6 class="text-danger pt-1" id="wrongstartdate" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
      			<label>End Date<span class="text-danger"><strong>*</strong></span></label>
      			<input type="date" id="enddate" name="enddate" class="form-control">
						<h6 class="text-danger pt-1" id="wrongenddate" style="font-size: 14px;"></h6>
      		</div>
      			       
	    	</div>
		    <div class="modal-footer">
		        <button id="allocateclose" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-primary">Save</button>
		    </div>
      </form>
      <!-- End Update Shift Form -->

    </div>
  </div>
</div>
<!-- End Allocate Shift Modal -->

@endsection

@section('script')
<script type="text/javascript" src="js/shift.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITShiftMODAL').modal('hide');
		$('#edit_wrongshiftname').empty();
		$('#edit_wrongintime').empty();
		$('#edit_wrongouttime').empty();
	});

	$(document).on('click', '#allocateclose', function (e) {
		$('#ALLOCATEShiftMODAL').modal('hide');

		$('#wrongemployee').empty();
		$('#wrongallocateshiftname').empty();
		$('#wrongstartdate').empty();
		$('#wrongenddate').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEShiftMODAL').modal('hide');
	});
</script>

@endsection


	
