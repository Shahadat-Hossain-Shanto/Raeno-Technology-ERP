@extends('layouts.master')
@section('title', 'Discounts')

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
		                	<h5 class="m-0"><i class="fas fa-clipboard-list"></i> <strong>DISCOUNTS</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/discount-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Discount</button></a>
	                	
	                	
	                    <div class="pt-3">
	                    	<div class="table-responsive">
													<table id="discount_table" class="display" width="100%">
													    <thead>
													        <tr>
													            <th>#</th>
													            <th>Discount Name</th>
													            <th>Discount Type</th>
													            <th>Value</th>
													            <th>Store</th>
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

<!-- Edit Discount Modal -->
<div class="modal fade" id="EDITDiscountMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE DISCOUNT</strong></h5>	        
      </div>


      <!-- Update Discount Form -->
      <form id="UPDATEDiscountFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="discountid" id="discountid">

      		<div class="form-group mb-3">
      			<label>Discount Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_discountname" name="discountname" class="form-control">
      			<h6 class="text-danger pt-1" id="edit_wrongdiscountname" style="font-size: 14px;"></h6>

      		</div>

      		<div class="form-group mb-3">
      			<label>Discount Type<span class="text-danger"><strong>*</strong></span></label>
      			<select class="form-control" data-live-search="true" aria-label="Default select example" name="discounttype" id="edit_discounttype">
						  	<option value="option_select" disabled selected>Select Discount Type</option>
	            	<option value="BDT">BDT</option>
	            	<option value="Percentage">Percentage</option>
						</select>
      			<h6 class="text-danger pt-1" id="edit_wrongdiscounttype" style="font-size: 14px;"></h6>

      		</div>

      		<div class="form-group mb-3">
      			<label>Value<span class="text-danger"><strong>*</strong></span></label>
      			<input type="number" class="form-control" name="value" id="edit_value" placeholder="Enter value">
      			<h6 class="text-danger pt-1" id="edit_wrongvalue" style="font-size: 14px;"></h6>

      		</div>

      		<div class="form-group mb-3">
      			<label>Store<span class="text-danger"><strong>*</strong></span></label><br>
      			<select class="selectpicker" data-live-search="true" name="store[]"  id="edit_store" data-width="355px" title="Select store" multiple>
						  	@foreach($stores as $store)
			            	<option value="{{ $store->id }}">{{ $store->store_name }}</option>
			        		@endforeach
						</select>
      			<h6 class="text-danger pt-1" id="edit_wrongstore" style="font-size: 14px;"></h6>

      		</div>

      		<!-- <div class="form-group mb-3">
      			<label>Restricted</label>
      			<select class="form-control" data-live-search="true" aria-label="Default select example" name="isrestricted" id="edit_isrestricted">
						  	<option value="option_select" disabled selected>Select</option>
	            	<option value="true">Yes</option>
	            	<option value="false">No</option>
						</select>
      			<h6 class="text-danger pt-1" id="edit_wrongposname" style="font-size: 14px;"></h6>
      		</div> -->

      		
      			       
	    </div>
	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Discount Form -->

    </div>
  </div>
</div>
<!-- End Edit Discount Modal -->


<!-- Delete Modal --> 

<div class="modal fade" id="DELETEDiscountMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEDiscountFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="discountid"> 
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
<script type="text/javascript" src="js/discount.js"></script>
<script type="text/javascript">
	$(document).on('click', '#close', function (e) {
		$('#EDITDiscountMODAL').modal('hide');
				$('#edit_wrongdiscountname').empty();
        $('#edit_wrongdiscounttype').empty();
				$('#edit_wrongvalue').empty();
				$('#edit_wrongstore').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEDiscountMODAL').modal('hide');
	});
</script>

@endsection


	
