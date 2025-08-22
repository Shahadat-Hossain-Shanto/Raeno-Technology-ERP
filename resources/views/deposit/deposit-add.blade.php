@extends('layouts.master')
@section('title', 'Add Deposit')

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
			                <h5 class="m-0"><strong><i class="fas fa-piggy-bank"></i> Deposit</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddDepositForm" method="POST" enctype="multipart/form-data">

								  	<div class="form-group">
									    <label for="id" class="form-label" style="font-weight: normal;">Customer <span class="text-danger"><strong>*</strong></span></label><br>
									    <select class="selectpicker" data-width="50%" data-live-search="true" aria-label="Default select example" name="id" id="id">
									      	<option value="option_select" disabled selected>Select Customer</option>
								      		@foreach($customers as $customers)
									            <option value="{{ $customers->id }}">{{ $customers->name }}</option>
									        @endforeach
									    </select>
									    <h6 class="text-danger pt-1" id="wrongid" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="storeId" class="form-label" style="font-weight: normal;">Store <span class="text-danger"><strong>*</strong></span></label><br>
									     <select class="selectpicker" data-width="50%" data-live-search="true" aria-label="Default select example" name="storeId"
									      id="storeId">
									      	<option value="option_select" disabled selected>Select Store</option>
									      	@foreach($stores as $store)
							            	<option value="{{ $store->id  }}">{{ $store->store_name  }}</option>
							        		@endforeach
									      </select>
									    <h6 class="text-danger pt-1" id="wrongstoreId" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="deposit_type" class="form-label" style="font-weight: normal;">Deposite Type <span class="text-danger"><strong>*</strong></span></label><br>
									     <select class="selectpicker" data-width="50%" data-live-search="true" aria-label="Default select example" name="deposit_type"
									      id="deposit_type">
									      	<option value="option_select" disabled selected>Select Deposite Type</option>
									      	@foreach($methods as $method)
				            	            <option value="{{ $method->paymentType }}">{{ $method->paymentType  }}</option>
				        		            @endforeach
									      </select>
									    <h6 class="text-danger pt-1" id="wrongdeposit_type" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="deposit" class="form-label" style="font-weight: normal;">Deposite Amount <span class="text-danger"><strong>*</strong></span></label><br>
									    <input class="form-control w-50" type="text" name="deposit" id="deposit" placeholder="e.g. 500">
									    <h6 class="text-danger pt-1" id="wrongdeposit" style="font-size: 14px;"></h6>
								  	</div>

								  	<div class="form-group">
									    <label for="depositDate" class="form-label" style="font-weight: normal;">Date <span class="text-danger"><strong>*</strong></span></label><br>
									    <input class="form-control w-50" type="date" name="depositDate" id="depositDate">
									    <h6 class="text-danger pt-1" id="wrongdepositDate" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="note" class="form-label" style="font-weight: normal;">Note </label><br>
									    <textarea class="form-control w-50" name="note" id="note"  rows="2" placeholder="if any notes"></textarea>


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
<script type="text/javascript" src="js/deposit.js"></script>

@endsection
