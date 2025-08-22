@extends('layouts.master')
@section('title', 'Create Discount')

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
			                <h5 class="m-0"><strong> <i class="fa fa-percent"></i> DISCOUNT</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddDiscountForm" method="POST" enctype="multipart/form-data">
								{{ csrf_field() }}

									<div class="form-group">
									    <label for="discountname" class="form-label" style="font-weight: normal;">Discount Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-75" name="discountname" id="discountname" placeholder="e.g. Winter discount 20%">
									    <h6 class="text-danger pt-1" id="wrongdiscountname" style="font-size: 14px;"></h6>
								  	</div>

								  	<div class="form-group pt-1">
									    <label for="discounttype" class="form-label" style="font-weight: normal;">Discount Type<span class="text-danger"><strong>*</strong></span></label>

									    <select class="form-control w-75" data-live-search="true" aria-label="Default select example" name="discounttype" id="discounttype">
										  	<option value="option_select" disabled selected>Select Discount Type</option>
							            	<option value="BDT">BDT</option>
							            	<option value="Percentage">Percentage</option>
										</select>
										<h6 class="text-danger pt-1" id="wrongdiscounttype" style="font-size: 14px;"></h6>
								  	</div>

								  	<div class="form-group pt-1">
									    <label for="value" class="form-label" style="font-weight: normal;">Discount Amount<span class="text-danger"><strong>*</strong></span></label>
									    <input type="number" step="any" min="0.1" class="form-control w-75" name="value" id="value" placeholder="e.g. 20">
									    <h6 class="text-danger pt-1" id="wrongvalue" style="font-size: 14px;"></h6>
								  	</div>

								  	<!-- <div class="form-group pt-1">
									    <label for="isrestricted" class="form-label">Restricted</label>
									    <select class="form-control w-75" data-live-search="true" aria-label="Default select example" name="isrestricted" id="isrestricted">
										  	<option value="option_select" disabled selected>Is restricted</option>
							            	<option value="true">Yes</option>
							            	<option value="false">No</option>

										</select>
								  	</div> -->

								  	<div class="row">
								  		<div class="col-4">
								  			<div class="form-group pt-1">
											  	<div class="form-check">
												  <input class="form-check-input" type="checkbox" value="all_store" id="all_store" name="storex">
												  <label class="form-check-label" for="all_store">
												    Discount Available to All Stores
												  </label>

												</div>
												<h6 class="text-danger pt-1" id="wrongstorex" style="font-size: 14px;"></h6>
											</div>
								  		</div>
								  		<div class="col-6">
								  			<div class="form-group pt-1">
											  	<div class="form-check">

												  <input class="form-check-input ml-4" type="checkbox" value="specific_store" id="specific_store" name="storex">
												  <label class="form-check-label ml-5" for="specific_store">
												    Discount Available to Specific Stores
												  </label>
												</div>
												<h6 class="text-danger pt-1" id="wrongstorex" style="font-size: 14px;"></h6>
											</div>
								  		</div>
								  	</div>
								  	<div class="row">
								  		<div class="col-6">
								  			<div class="form-group pt-1" id="specificstores">
										  		<label for="store" class="form-label" style="font-weight: normal;">Store<span class="text-danger"></span></label><br>
											    <select class="selectpicker form-control" data-width="100%" data-live-search="true" aria-label="Default select example" name="store[]" id="store" title="Select stores" multiple disabled>
												  	@foreach($stores as $store)
									            	<option value="{{ $store->id }}">{{ $store->store_name }}</option>
									        		@endforeach
												</select>
												<h6 class="text-danger pt-1 hidden" id="wrongstore" style="font-size: 14px;"></h6>
										  	</div>
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
<script type="text/javascript" src="js/discount.js"></script>

<script type="text/javascript">
	$(document).on('click', 'input[type="checkbox"]', function() {      
	    $('input[type="checkbox"]').not(this).prop('checked', false);      
	});

	// if ($('#specific_store').is('checked')) {
	// 	alert('ok')
	// 	$('#store').prop('disabled', false);
	// }

	$("#specific_store").change(function(){
	  if ($(this).is(':checked')){
	  	// resetButton()
	  	// $('#store').prop('disabled', false);
	  	$("#store").attr('disabled', false);
	  	$('#store').selectpicker('refresh');
	  	$('#wrongstore').show();
	  	
	  }else{
	  	// resetButton()
	  	$("#store").attr('disabled', true);
	  	$('#store').selectpicker('refresh');
	  	// $('#store').appendTo('#store').selectpicker('refresh');
	  	// $('.selectpicker').selectpicker('refresh');
	  	$('#wrongstore').hide();
	  }
	});

	$("#all_store").change(function(){
	  if ($(this).is(':checked')){
	  	// $('#store').prop('disabled', false);
	  	
	  	$("#store").attr('disabled', true);
	  	$('#store').selectpicker('refresh');
	  	$('#wrongstore').hide();
	  }
	});

	function resetButton(){
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}
</script>
@endsection