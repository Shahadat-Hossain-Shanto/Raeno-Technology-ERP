@extends('layouts.master')
@section('title', 'New Sales')

@section('content')
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<!-- Header -->
			</div>
		</div>
	</div>

	<div class="content pt-4 ">
		<div class="container-fluid ">
			<div class="row">
      			<div class="col-lg-8">
          			<div class="card card-primary">
			            <div class="card-header">
			                <h5 class="m-0 fw-bold"><strong><i class="fas fa-hand-point-right"></i> SELECT STORE & POS</strong></h5>
			            </div>
			            <form id="posPinForm" method="POST" enctype="multipart/form-data">
			              	<div class="card-body" style="height: auto; width: auto;">
		          				<div class="container">
		          					<div class="row g-3 align-items-center">
		          						<div class="col-auto">
										    <label for="storeid" class="col-form-label fw-normal">Store</label>
                                            <input type="hidden" id="userStoreId" value="{{$userStoreId}}"/>
										</div>
		          						<div class="col-3">
		          							<select class="selectpicker" data-live-search="true" aria-label="Default select example" name="storeid"
										      id="storeid">
										      	<option value="default" selected disabled>Select Store</option>
										      	@foreach($stores as $store)
								            	<option value="{{ $store->id }}">{{ $store->store_name  }}</option>
								        		@endforeach
										    </select>
		          						</div>

		          					</div>
		          					<div id="posHeading" class="row pt-5 text-dark">

		          					</div>

		          					<div class="row pt-3">
	   									<div id="poses" style="float: none; margin: 0 auto;">

	   									</div>
		          					</div>


	          						<div class="row g-3 align-items-center pt-3" id="posPin">

	          						</div>
	          						<div class="row" id="pinerror">


	          						</div>

								</div> <!-- container -->
							</div> <!-- card-body -->
						</form>
		          	</div> <!-- card card-primary card-outline -->
      			</div> <!-- col-lg-8 -->
      		</div> <!-- row -->
		</div> <!-- container-fluid -->
	</div> <!-- content -->
</div> <!-- content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="js/new-sales-login.js"></script>
<script type="text/javascript">

	$(document).on('click', 'input[type="checkbox"]', function() {
	    $('input[type="checkbox"]').not(this).prop('checked', false);
	});
</script>
@endsection
