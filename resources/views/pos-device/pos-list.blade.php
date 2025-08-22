@extends('layouts.master')
@section('title', 'POS')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> POS</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/pos-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus-circle"></i> Create POS</button></a>
	                	
	                	
	                    <div class="pt-3">
												<table id="pos_table" class="display">
												    <thead>
												        <tr>
												            <th>#</th>
												            <th>POS Name</th>
												            <th>Status</th>
												            <th>Store</th>
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

<!-- Edit pos Modal -->
<div class="modal fade" id="EDITPosMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update pos</h5>	        
      </div>


      <!-- Update pos Form -->
      <form id="UPDATEPosFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="posid" id="posid">

      		<div class="form-group mb-3">
      			<label>Store<span class="text-danger"><strong>*</strong></span></label><br>
      			<select class="selectpicker" data-live-search="true" name="storeid"  id="edit_storeid" data-width="355px">
						  	@foreach($stores as $store)
			            	<option value="{{ $store->id }}">{{ $store->store_name }}</option>
			        		@endforeach
						</select>
      			<h6 class="text-danger pt-1" id="edit_wrongstoreid" style="font-size: 14px;"></h6>

      		</div>
      		<div class="form-group mb-3">
      			<label>POS Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_posname" name="pos_name" class="form-control w-75">
      			<h6 class="text-danger pt-1" id="edit_wrongposname" style="font-size: 14px;"></h6>
      		</div>
      		<div class="form-group mb-3">
      			<label>Status<span class="text-danger"><strong>*</strong></span></label>
      			<!-- <input type="text" id="edit_posstatus" name="posstatus" class="form-control w-75"> -->
      			<select class="form-control w-75" title="Select status" name="posstatus"  id="edit_posstatus">
	            	<option value="Inactive">Inactive</option>
	            	<option value="Active">Active</option>
      			</select>
      			<h6 class="text-danger pt-1" id="edit_wrongposstatus" style="font-size: 14px;"></h6>

      		</div>
      		<div class="form-group mb-3">
      			<label>POS PIN<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_pospin" name="pospin" class="form-control w-75">
      			<div id="pospinHelp" class="form-text">Your POS PIN must be 4 characters long.</div>
      			<h6 class="text-danger pt-1" id="edit_wrongpospin" style="font-size: 14px;"></h6>

      		</div>
      			       
	    </div>
	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update pos Form -->

    </div>
  </div>
</div>
<!-- End Edit pos Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEPosMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEPosFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="posid"> 
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
<script type="text/javascript" src="js/pos-device.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITPosMODAL').modal('hide');
		$('#edit_wrongstoreid').empty();
		$('#edit_wrongposname').empty();
		$('#edit_wrongposstatus').empty();
		$('#edit_wrongpospin').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEPosMODAL').modal('hide');
	});
</script>

@endsection


	
