@extends('layouts.master')
@section('title', 'Create Customer')

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
      			<div class="col-lg-8">
          			<div class="card card-primary">
			            <div class="card-header">
			                <h5 class="m-0"><strong><i class="fas fa-user-plus"></i> CUSTOMER</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddCustomerForm" method="POST" enctype="multipart/form-data">
									<div class="row">
										<div class="col-6">
											<div class="form-group">
											    <label for="customername" class="form-label" style="font-weight: normal;">Customer Name<span class="text-danger"><strong>*</strong></span></label>
											    <input type="text" class="form-control w-75" name="customername" id="customername" placeholder="e.g. Mike Tyson">
									    		<h6 class="text-danger pt-1" id="wrongcustomername" style="font-size: 14px;"></h6>

										  	</div>
										</div>
										<div class="col-6">
											<div class="form-group">
											    <label for="note" class="form-label" style="font-weight: normal;">Note <span style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
											    <textarea class="form-control w-75" name="note" id="note" rows="1" placeholder="Any notes e.g. customer who wants buy energy pill"></textarea>
										  	</div>
										</div>
									</div>
									<div class="row">
										<div class="col-6">
											<div class="form-group pt-3">
											    <label for="mobile" class="form-label" style="font-weight: normal;">Contact No.<span class="text-danger"><strong>*</strong></span></label>
											    <input type="text" class="form-control w-75" name="mobile" id="mobile" placeholder="e.g. 01XXXXXXXXX">
									    		<h6 class="text-danger pt-1" id="wrongmobile" style="font-size: 14px;"></h6>

										  	</div>
										</div>
										<div class="col-6">
											<div class="form-group pt-3">
											    <label for="customeraddress" class="form-label" style="font-weight: normal;">Address <span style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
											    <input type="text" class="form-control w-75" name="customeraddress" id="customeraddress" placeholder="e.g. Aftabnagar, Rampura, Dhaka">
										  	</div>
										</div>
									</div>
									<div class="row">
										<div class="col-6">
											<div class="form-group pt-3">
											    <label for="customeremail" class="form-label" style="font-weight: normal;">Email Address <span style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
											    <input type="email" class="form-control w-75" name="customeremail" id="customeremail" placeholder="e.g. mike_tyson@xmail.com">
										  	</div>
										</div>
										<div class="col-6">
											<div class="form-group pt-3">
											    <label for="customerimage" class="form-label" style="font-weight: normal;">Image <span style="font-weight: normal;font-size: 14px; color: grey;">(optional)</span></label>
											    <input type="file" class="form-control w-75" name="customerimage" id="customerimage">
										  	</div>
										</div>
									</div>
									
								  	
								  	<div class="form-group pt-3">
									  	<button type="submit" class="btn btn-primary">Create</button>
										<button type="reset" value="Reset" class="btn btn-outline-danger"><i class="fas fa-eraser"></i> Reset</button>
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
<script type="text/javascript" src="js/customer.js"></script>

@endsection