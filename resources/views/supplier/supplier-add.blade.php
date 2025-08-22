@extends('layouts.master')
@section('title', 'Create Supplier')

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
				<!-- <div class="col-lg-2"></div> -->
      			<div class="col-lg-8">
          			<div class="card card-primary">
			            <div class="card-header">
			                <h5 class="m-0"><strong><i class="fas fa-user-plus"></i> SUPPLIER</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddSupplierForm" method="POST" enctype="multipart/form-data">
									<div class="row">
										<div class="col-6">
											<div class="form-group">
											    <label for="suppliername" style="font-weight: normal;" class="form-label">Supplier Name<span class="text-danger"><strong>*</strong></span></label>
											    <input type="text" class="form-control w-75" name="suppliername" id="suppliername" placeholder="e.g. Mike Tyson">
									    		<h6 class="text-danger pt-1" id="wrongsuppliername" style="font-size: 14px;"></h6>

										  	</div>
										</div>
										<div class="col-6">
											<div class="form-group">
											    <label for="note" style="font-weight: normal;" class="form-label">Note <span style="font-size: 14px; color: grey;">(optional)</span></label>
											    <textarea class="form-control w-75" name="note" id="note" rows="1" placeholder="Any notes e.g. Supplier for  Beximco products"></textarea>
										  	</div>
										</div>
									</div>
								  	<div class="row">
								  		<div class="col-6">
								  			<div class="form-group pt-1">
											    <label for="mobile" class="form-label" style="font-weight: normal;">Contact No.<span class="text-danger"><strong>*</strong></span></label>
											    <input type="text" class="form-control w-75" name="mobile" id="mobile" placeholder="e.g. 01XXXXXXXXX">
									    		<h6 class="text-danger pt-1" id="wrongmobile" style="font-size: 14px;"></h6>

										  	</div>
								  		</div>
								  		<!-- <div class="col-6">
								  			<div class="form-group pt-1">
											    <label for="storeid" class="form-label">Store</label>
											    <select class="form-control w-75" data-live-search="true" aria-label="Default select example" name="storeid">
												  	<option value="option_select" disabled selected>Select Store</option>
												  	@foreach($stores as $store)
									            	<option value="{{ $store->id }}">
									            		{{ $store->store_name }}
									            	</option>
									        		@endforeach
												</select>
										  	</div>
								  		</div> -->
								  		<div class="col-6">
								  			<div class="form-group pt-1">
											    <label for="supplierwebsite" class="form-label" style="font-weight: normal;">Website <span style="font-size: 14px; color: grey;">(optional)</span></label>
											    <input type="text" class="form-control w-75" name="supplierwebsite" id="supplierwebsite" placeholder="e.g. www.mike-tyson.com">
										  	</div>
								  		</div>
								  	</div>
								  	
								  	<div class="row">
								  		<div class="col-6">
								  			<div class="form-group pt-1">
											    <label for="supplieremail" class="form-label" style="font-weight: normal;">Email Address <span style="font-size: 14px; color: grey;">(optional)</span></label>
											    <input type="email" class="form-control w-75" name="supplieremail" id="supplieremail" placeholder="e.g. mike_tyson@gmail.com">
										  	</div>
								  		</div>
								  		<div class="col-6">
								  			<div class="form-group pt-1">
											    <label for="supplierimage" class="form-label" style="font-weight: normal;">Image <span style="font-size: 14px; color: grey;">(optional)</span></label>
											    <input type="file" class="form-control w-75" name="supplierimage" id="supplierimage">
										  	</div>
								  		</div>
								  	</div>
								  	
								  	<div class="row">
								  		<div class="col-6">
								  			<div class="form-group pt-1">
											    <label for="supplieraddress" class="form-label" style="font-weight: normal;">Address<span class="text-danger"><strong>*</strong></span></label>
											    <input type="text" class="form-control w-75" name="supplieraddress" id="supplieraddress" placeholder="e.g. Link-Rd, Badda, Dhaka">
									    		<h6 class="text-danger pt-1" id="wrongsupplieraddress" style="font-size: 14px;"></h6>

										  	</div>
								  		</div>
								  		
								  	</div>
								  	
								  	<div class="form-group pt-3">
										<button type="submit" class="btn btn-primary">Create</button>
										<button type="reset" value="Reset" class="btn btn-outline-danger"><i class="fas fa-eraser"></i>  Reset</button>
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
<script type="text/javascript" src="js/supplier.js"></script>

@endsection