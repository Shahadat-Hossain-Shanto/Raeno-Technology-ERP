@extends('layouts.master')
@section('title', 'Create Payment Method')

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
          			<div class="card card-primary ">
			            <div class="card-header">
			                <h5 class="m-0"><strong><i class="fas fa-money-check-alt"></i> PAYMENT METHOD</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddPaymentMethodForm" method="POST" enctype="multipart/form-data">

									<div class="form-group">
									    <label for="paymentmethod" class="form-label" style="font-weight: normal;">Payment Method<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="paymentmethod" id="paymentmethod" placeholder="e.g. Cash">
									    <div id="" class="form-text"><strong>N.B. </strong>Payment methods will show your payment options e.g. Cash, Bank, bKash, Rocket, uPay.</div>
									    <h6 class="text-danger pt-1" id="wrongpaymentmethod" style="font-size: 14px;"></h6>
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
<script type="text/javascript" src="js/payment-methods.js"></script>

@endsection