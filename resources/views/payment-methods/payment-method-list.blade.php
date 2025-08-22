@extends('layouts.master')
@section('title', 'Payment Methods')



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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> PAYMENT METHODS</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/payment-method-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Payment Method</button></a>
	                	
	                	
	                    <div class="pt-3">
	                    	<div class="table-responsive">
												<table id="paymentmethod_table" class="display" width="100%">
												    <thead>
												        <tr>
												            <th>#</th>
												            <th>Payment Method</th>
												            <th>Action</th>
												        </tr>
												    </thead>
												    <tbody>

												    </tbody>
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

<!-- Edit Payment Method Modal -->
<div class="modal fade" id="EDITPaymentMethodMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE PAYMENT METHOD</strong></h5>	        
      </div>


      <!-- Update Payment Method Form -->
      <form id="UPDATEPaymentMethodFORM" enctype="multipart/form-data">
      	
      <input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="paymentmethodid" id="paymentmethodid">

      		<div class="form-group mb-3">
      			<label class="form-label">Payment Method<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_paymentmethod" name="paymentmethod" class="form-control">
						<div id="" class="form-text"><strong>N.B. </strong>Payment methods will show your payment options e.g. Cash, Bank, bKash, Rocket, uPay.</div>
						<h6 class="text-danger pt-1" id="edit_wrongpaymentmethod" style="font-size: 14px;"></h6>
      		</div>
      		
      			       
	    </div>
	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Payment Method Form -->

    </div>
  </div>
</div>
<!-- End Edit Payment Method Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEPaymentMethodMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEPaymentMethodFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="categoryid"> 
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
<script type="text/javascript" src="js/payment-methods.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITPaymentMethodMODAL').modal('hide');
		$('#edit_wrongpaymentmethod').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEPaymentMethodMODAL').modal('hide');

	});
</script>

@endsection


	
