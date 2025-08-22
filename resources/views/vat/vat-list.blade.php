@extends('layouts.master')
@section('title', 'Vats')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> VATS</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/vat-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Vat</button></a>
	                	
	                	
	                    <div class="pt-3">
												<table id="vat_table" class="display">
												    <thead>
												        <tr>
												            <th>#</th>
												            <th>Vat Name</th>
												            <th>Vat Amount (%)</th>
												            <th>Vat Type</th>
												            <th>Vat Option</th>
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

<!-- Edit Vat Modal -->
<div class="modal fade" id="EDITVatMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE VAT</strong></h5>	        
      </div>


      <!-- Update Vat Form -->
      <form id="UPDATEVatFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="vatid" id="vatid">

      		<div class="form-group mb-3">
      			<label>Vat Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_vatname" name="vatname" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongvatname" style="font-size: 14px;"></h6>

      		</div>

      		<div class="form-group mb-3">
      			<label>Vat Rate (%)<span class="text-danger"><strong>*</strong></span></label>
      			<input type="number" id="edit_vatrate" name="vatrate" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongvatrate" style="font-size: 14px;"></h6>

      		</div>

      		<div class="form-group mb-3">
      			<label>Vat Type<span class="text-danger"><strong>*</strong></span></label>
      			<select class="form-control" data-live-search="true" aria-label="Default select example" name="vattype" id="edit_vattype">
						  	<option value="option_select" disabled selected>Select Vat Type</option>
	            	<option value="included">Included in the Price</option>
	            	<option value="added">Added in the Price</option>
						</select>
						<h6 class="text-danger pt-1" id="edit_wrongvattype" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
      			<label>Vat Option<span class="text-danger"><strong>*</strong></span></label>
      			<select class="form-control" data-live-search="true" aria-label="Default select example" name="vatoption" id="edit_vatoption">
						  	<option value="option_select" disabled selected>Select option</option>
	            	<option value="new items">Apply the tax to the new items</option>
	            	<option value="existing items">Apply the tax to the existing items</option>
	            	<option value="new and existing items">Apply the tax to the all new and existing items</option>
						</select>
						<h6 class="text-danger pt-1" id="edit_wrongvatoption" style="font-size: 14px;"></h6>
      		</div>

      			       
	    </div>

	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Vat Form -->

    </div>
  </div>
</div>
<!-- End Edit Vat Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEVatMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEVatFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="vatid"> 
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
<script type="text/javascript" src="js/vat.js"></script>
<script type="text/javascript">
	$(document).on('click', '#close', function (e) {
		$('#EDITVatMODAL').modal('hide');
		$('#edit_wrongvatname').empty();
		$('#edit_wrongvatrate').empty();
		$('#edit_wrongvattype').empty();
		$('#edit_wrongvatoption').empty();
	});
	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEVatMODAL').modal('hide');
	});
</script>

@endsection


	
