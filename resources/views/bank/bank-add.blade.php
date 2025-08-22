@extends('layouts.master')
@section('title', 'Create Bank')

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
			                <h5 class="m-0"><strong><i class="fas fa-university"></i> ADD BANK</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddBankForm" method="POST" enctype="multipart/form-data">
								<div class="row">
									<div class="col-6">
										<div class="form-group">
										    <label for="bankname" class="form-label" style="font-weight: normal;">Bank Name<span class="text-danger"><strong>*</strong></span></label>
										    <input type="text" class="form-control w-75" name="bankname" id="bankname" placeholder="e.g. City Bank">
									    	<h6 class="text-danger pt-1" id="wrongbankname" style="font-size: 14px;"></h6>
								  		</div>
									</div>
									<div class="col-6">
										<div class="form-group">
										    <label for="accountname" class="form-label" style="font-weight: normal;">Account Name<span class="text-danger"><strong>*</strong></span></label>
										    <input type="text" class="form-control w-75" name="accountname" id="accountname">
										    <h6 class="text-danger pt-1" id="wrongaccountname" style="font-size: 14px;"></h6>
									  	</div>
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										<div class="form-group">
										    <label for="accountnumber" class="form-label" style="font-weight: normal;">Account Number<span class="text-danger"><strong>*</strong></span></label>
										    <input type="text" class="form-control w-75" name="accountnumber" id="accountnumber">
										    <h6 class="text-danger pt-1" id="wrongaccountnumber" style="font-size: 14px;"></h6>
									  	</div>
									</div>
									<div class="col-6">
										<div class="form-group">
										    <label for="branch" class="form-label" style="font-weight: normal;">Branch<span class="text-danger"><strong></strong></span></label>
										    <input type="text" class="form-control w-75" name="branch" id="branch">
										    <!-- <h6 class="text-danger pt-1" id="wrongbranch" style="font-size: 14px;"></h6> -->
									  	</div>
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										<div class="form-group">
										    <label for="accounthead" class="form-label" style="font-weight: normal;">Account Head<span class="text-danger"><strong>*</strong></span></label><br>

										    <select class="selectpicker" data-width="75%" data-live-search="true" name="accounthead" id="accounthead" title="Select Bank">
										    	<!-- <option value="option_select">Select Bank</option> -->
										    	@foreach($banks as $bank)
										    	<option value="{{$bank->head_code}}">{{$bank->head_name}}</option>
										    	@endforeach
										    </select>

										    <h6 class="text-danger pt-1" id="wrongaccounthead" style="font-size: 14px;"></h6>
									  	</div>
									</div>
									{{-- <div class="col-6">
										<div class="form-group">
										    <label for="openingbalance" class="form-label" style="font-weight: normal;">Opening Balance<span class="text-danger"><strong></strong></span></label>
										    <input type="text" class="form-control w-75" name="openingbalance" id="openingbalance">
										    <!-- <h6 class="text-danger pt-1" id="wrongopeningbalance" style="font-size: 14px;"></h6> -->
									  	</div>
									</div> --}}
								</div>
								<div class="row">
									<div class="col-6">
										<div class="form-group">
										    <label for="signaturepicture" class="form-label" style="font-weight: normal;">Signature or Cheque<span class="text-danger"><strong></strong></span></label>
										    <input id="input-b1" name="imagefile" type="file" class="file productimage" data-browse-on-zone-click="true">
										    <!-- <h6 class="text-danger pt-1" id="wrongsignaturepicture" style="font-size: 14px;"></h6> -->
									  	</div>
									</div>
									<div class="col-6">
										<div class="form-group">
										    <label for="status" class="form-label" style="font-weight: normal;">Status<span class="text-danger"><strong>*</strong></span></label>
									    	<select class="form-control w-75" name="status" id="status" title="Select Status">
									    		<option selected disabled>Select Status</option>
									    		<option value="active">Active</option>
									    		<option value="inactive">Inactive</option>

									    	</select>

										    <h6 class="text-danger pt-1" id="wrongstatus" style="font-size: 14px;"></h6>
									  	</div>
									  	{{-- <small id="" style="font-size: 14px;" class="form-text text-muted w-75"><b>N.B.</b>
									  		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
									  		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
									  		quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
									  		consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
									  		cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
									  		proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
										 </small> --}}
									</div>
								</div>



								  	<div class="form-group pt-3">
									  	<button type="submit" class="btn btn-primary">Create</button>
										<button type="reset" value="Reset" class="btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
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
<script type="text/javascript" src="js/bank.js"></script>

@endsection
