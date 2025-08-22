@extends('layouts.master')
@section('title', 'Designations')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> Designations</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="{{url('/add-designation')}}"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Designation</button></a>
	                	
	                	
	                    <div class="pt-3">
												<table id="designation_table" class="display" width="100%">
												    <thead>
												        <tr>
												            <th>#</th>
												            <th>Designation Name</th>
												            <th>Designation Desciption</th>
												            <th>Action</th>
												        </tr>
												    </thead>
												    <!-- <tbody>

												    </tbody> -->
											    </table>
											</div>

		              </div> <!-- Card-body -->
		            </div>	<!-- Card -->
	            
		        </div>   <!-- /.col-lg-6 -->
        	</div><!-- /.row -->
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

<!-- Edit Customer Modal -->
<div class="modal fade" id="EDITDesignationMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE DESIGNATIONS</strong></h5>	        
      </div>

      <form id="UPDATEDesignationFORM" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
      <!-- Update Customer Form -->
      {{-- <form id="UPDATEDesignationFORM" enctype="multipart/form-data"> --}}
      	
      	{{-- <input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
      	
      	<div class="modal-body">

      		<input type="hidden" name="designationid" id="designationid">

      		<div class="form-group">
                <label for="designation_name" class="form-label" style="font-weight: normal;">Designation Name<span class="text-danger"><strong>*</strong></span></label>
                <input type="text" class="form-control " name="designation_name" id="edit_designation_name" placeholder="Enter Designation Name">
            
                <h6 class="text-danger pt-1" id="edit_wrong_designation_name" style="font-size: 14px;"></h6>
            
              </div>
              <div class="form-group">
                <label for="designation_description" class="form-label" style="font-weight: normal;">Designation Description<span class="text-danger"><strong></strong></span></label>
                <textarea class="form-control " rows="3" id="edit_designation_description" name="designation_description" placeholder="if any designation"></textarea>
                <h6 class="text-danger pt-1" id="edit_wrong_designation_description" style="font-size: 14px;"></h6>
            
              </div>
      			       
	    </div>
	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Customer Form -->

    </div>
  </div>
</div>
<!-- End Edit Customer Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEDesignationMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEDesignationFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="delete_designationid"> 
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
<script type="text/javascript" src="{{asset('js/designation.js')}}"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITDesignationMODAL').modal('hide');
		$('#edit_wrong_designation_name').empty();
		$('#edit_wrong_designation_description').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEDesignationMODAL').modal('hide');
	});
</script>

@endsection


	
