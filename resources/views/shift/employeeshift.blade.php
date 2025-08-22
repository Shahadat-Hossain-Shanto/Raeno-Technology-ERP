@extends('layouts.master')
@section('title', 'shift')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> SHIFT WISE EMPLOYEE</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/shift-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Shift</button></a>
	                	<a href="/shift-list"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Shift List</button></a>
	                	
	                    <div class="pt-3">
	                    	<div class="table-responsive">
													<table id="employee_shift" class="display" width="100%">
													    <thead>
													        <tr>
													            
													            
													            <th>shift</th>
                                                                <th>name</th>
                                                                <th>department</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
													            <th>Action</th>
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
<div class="modal fade" id="EDITshiftMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE shift</strong></h5>	        
      </div>


      <!-- Update shift Form -->
      <form id="UPDATEshiftFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	<!-- @csrf -->
		  {{ csrf_field() }}
      		<div class="modal-body">

      		<input type="hidden" name="shiftId" id="shiftId">
			<!-- <input type="hidden" name="employee_id" id="employee_id"> -->
			<!-- <input type="hidden" name="shiftwise_id" id="shiftwise_id"> -->


      		<div class="form-group mb-3">
      			<label>Employee<span class="text-danger"><strong>*</strong></span></label><br>
				  <input type="text" id="edit_name" class="form-control" name="edit_name" readonly>
      			 <!-- <select class="selectpicker" data-width="100%" title="Select Employee" data-live-search="true" aria-label="Default select example" name="employee" id="edit_name" onchange=""> -->
               
            <!-- </select>  -->
						<h6 class="text-danger pt-1" id="wrongemployee" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
			  <label>Shift Name<span class="text-danger"><strong>*</strong></span></label>
				<select class="selectpicker" data-width="50%" title="Select shift" data-live-search="true" aria-label="Default select example" 
				name="edit_shift_name" id="edit_shift_name" onchange="">
					@foreach($shifts as $shifts)
						<option value="{{ $shifts->shift_name }}" selected>{{ $shifts->shift_name  }}</option>
					@endforeach
				</select>
				<h6 class="text-danger pt-1" id="wrongemployee" style="font-size: 14px;"></h6>
			<!-- </div> -->
      			
      			<!-- <input type="text" id="" name="" class="form-control" > -->
						<h6 class="text-danger pt-1" id="wrongallocateshiftname" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
      			<label>Start Date<span class="text-danger"><strong>*</strong></span></label>
      			<input type="date" id="edit_startdate" name="edit_startdate" class="form-control">
						<h6 class="text-danger pt-1" id="wrongstartdate" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
      			<label>End Date<span class="text-danger"><strong>*</strong></span></label>
      			<input type="date" id="edit_enddate" name="edit_enddate" class="form-control">
						<h6 class="text-danger pt-1" id="wrongenddate" style="font-size: 14px;"></h6>
      		</div>
      			       
	    	</div>
	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Customer Form -->

    </div>
  </div>
</div>
<!-- End Edit Customer Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEshiftMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEshiftFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="shiftId"> 
			      <h5 class="text-center">Are you sure you want to delete?</h5>
			    </div>

			    <div class="modal-footer justify-content-center">
			        <button type="button" class="cancel btn btn-secondary cancel_btn" data-dismiss="modal">Cancel</button>
			        <button type="submit" class="delete btn btn-danger">Yes</button>
			    </div>

			</form>

		</div>
	</div>
</div>

<!-- END Delete Modal -->

@endsection

@section('script')
<script type="text/javascript" src="js/employeeshift.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITshiftMODAL').modal('hide');
		$('#edit_wrongcustomername').empty();
		$('#edit_wrongcontactnumber').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEshiftMODAL').modal('hide');
	});
</script>

@endsection


	
