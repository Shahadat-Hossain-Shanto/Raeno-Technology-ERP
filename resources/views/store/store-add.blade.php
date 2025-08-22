@extends('layouts.master')
@section('title', 'Create Store')

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
      			<div class="col-lg-6">
          			<div class="card card-primary">
			            <div class="card-header">
			                <h5 class="m-0"><strong><i class="fas fa-store"></i> Store</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

	          					<div class="alert alert-danger print-error-msg" style="display:none">
							        <ul></ul>
							    </div>

								<form id="AddStoreForm" method="POST" enctype="multipart/form-data">
									 {{ csrf_field() }}
								  	<div class="form-group">
									    <label for="store_name" style="font-weight: normal;" class="form-label">Store Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="store_name" id="store_name" placeholder="e.g. Johnson Medical Store">
									    <h6 class="text-danger pt-1" id="wrongstorename" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group pt-1">
									    <label for="storeaddress" class="form-label" style="font-weight: normal;">Store Address<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="storeaddress" id="storeaddress" placeholder="e.g. Shop #3, Rd-2, Block-C, Banasree, Dhaka">
									    <h6 class="text-danger pt-1" id="wrongstoreaddress" style="font-size: 14px;"></h6>

								  	</div>
								  	<div class="form-group pt-1">
									    <label for="contactnumber" class="form-label" style="font-weight: normal;">Contact Number<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="contactnumber" id="contactnumber" placeholder="e.g. 017XXXXXXXX">
									    <h6 class="text-danger pt-1" id="wrongcontactnumber" style="font-size: 14px;"></h6>
								  	</div>

								  	<div class="form-group pt-1">
									  	<button type="submit" class="btn btn-primary"> Create</button>
										<button type="reset" value="Reset" class="btn btn-outline-danger"><i class="fas fa-eraser"></i> Reset</button>
										<div>
											<strong><p class="text-danger pt-2" id="wrongStoreInput"></p></strong>
										</div>
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
<script type="text/javascript" src="js/store.js"></script>

@endsection
