@extends('layouts.master')
@section('title', 'Customers')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> CUSTOMERS</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/customer-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Customer</button></a>
	                	
	                	
	                    <div class="pt-3">
	                    	<div class="table-responsive">
													<table id="customer_table" class="display" width="100%">
													    <thead>
													        <tr>
													            <th>#</th>
													            <th>Name</th>
													            <th>Contact</th>
													            <th>Email</th>
													            <!-- <th>Website</th> -->
													            <th>Address</th>
	<!-- 												            <th>Note</th> -->
													            <!-- <th>Store</th> -->
													            <th>Image</th>
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

<!-- Edit Customer Modal -->
<div class="modal fade" id="EDITCustomerMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE CUSTOMER</strong></h5>	        
      </div>


      <!-- Update Customer Form -->
      <form id="UPDATECustomerFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="customerid" id="customerid">

      		<div class="form-group mb-3">
      			<label class="form-label">Customer Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_customername" name="customername" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongcustomername" style="font-size: 14px;"></h6>

      		</div>
      		<div class="form-group mb-3">
      			<label class="form-label">Contact No.<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_contactnumber" name="mobile" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongcontactnumber" style="font-size: 14px;"></h6>

      		</div>
      		<div class="form-group mb-3">
      			<label class="form-label">Email Address <span style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
      			<input type="email" id="edit_customeremail" name="customeremail" class="form-control" placeholder="update email e.g. mike_tyson@xmail.com">
      		</div>
      		<div class="form-group mb-3">
      			<label class="form-label">Address <span style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
      			<input type="text" id="edit_customeraddress" name="customeraddress" class="form-control" placeholder="update address here e.g. Banasree, Dhaka">
      		</div>
      		<div class="form-group mb-3">
				    <label class="form-label">Note <span style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
				    <textarea class="form-control" name="note" id="edit_note" rows="2" placeholder="add notes for customer"></textarea>
			  	</div>
			  	<!-- <div class="form-group mb-3">
			  		<label class="form-label">Store</label>
			  		<select class="form-control" data-live-search="true" aria-label="Default select example" name="storeid" id="edit_storeid" >
						  	<option value="option_select" disabled selected>Select Store</option>
						  	@foreach($stores as $store)
		            	<option value="{{ $store->id }}">
		            		{{ $store->store_name }}
		            	</option>
	        			@endforeach
						</select>
			  	</div> -->
			  	<div class="form-group pt-3">
				    <img src="" alt="customer image" width="100px" height="100px" alt="image" class="rounded-circle pb-3" name="customerimage" id="edit_image">
			  	</div>
			  	<div class="form-group">
				    <label for="customerimage" class="form-label">Image <span style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
				    <input type="file" class="form-control" name="customerimage" id="customerimage">
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

<div class="modal fade" id="DELETECustomerMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETECustomerFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="customerid"> 
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
<script type="text/javascript" src="js/customer.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITCustomerMODAL').modal('hide');
		$('#edit_wrongcustomername').empty();
		$('#edit_wrongcontactnumber').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETECustomerMODAL').modal('hide');
	});
</script>

@endsection


	
