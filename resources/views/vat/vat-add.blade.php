@extends('layouts.master')
@section('title', 'Create Vat')

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
			                <h5 class="m-0"><strong><i class="fas fa-wallet"></i> VAT</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddVatForm" method="POST" enctype="multipart/form-data">
								{{ csrf_field() }}
									<div class="form-group">
									    <label for="vatname" class="form-label" style="font-weight: normal;">Vat Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="vatname" id="vatname" placeholder="e.g. Vat 1%">
									    <h6 class="text-danger pt-1" id="wrongvatname" style="font-size: 14px;"></h6>

								  	</div>

								  	<div class="form-group">
									    <label for="vatrate" class="form-label" style="font-weight: normal;">Vat Amount (%)<span class="text-danger"><strong>*</strong></span></label>
									    <input type="number" step="any" min="0.1" class="form-control w-50" name="vatrate" id="vatrate" placeholder="e.g. 1">
									    <h6 class="text-danger pt-1" id="wrongvatrate" style="font-size: 14px;"></h6>

								  	</div>

								  	<div class="form-group pt-3">
									    <label for="vattype" class="form-label" style="font-weight: normal;">Vat Type<span class="text-danger"><strong>*</strong></span></label>

									    <select class="form-control w-50" data-live-search="true" aria-label="Default select example" name="vattype" id="vattype">
										  	<option value="option_select" disabled selected>Select Vat Type</option>
							            	<option value="included">Included in the Price</option>
							            	<option value="added">Will be added in the Price</option>
										</select>
									    <h6 class="text-danger pt-1" id="wrongvattype" style="font-size: 14px;"></h6>

								  	</div>

								  	<div class="form-group pt-3">
									    <label for="vatoption" class="form-label" style="font-weight: normal;">Vat Option<span class="text-danger"><strong>*</strong></span></label>
									    <select class="form-control w-50" data-live-search="true" aria-label="Default select example" name="vatoption" id="vatoption">
										  	<option value="option_select" disabled selected>Select option</option>
							            	<option value="new items">Apply the tax to the new items</option>
							            	<option value="existing items">Apply the tax to the existing items</option>
							            	<option value="new and existing items">Apply the tax to the all new and existing items</option>
										</select>
									    <h6 class="text-danger pt-1" id="wrongvatoption" style="font-size: 14px;"></h6>

								  	</div>

								  <!-- 	<div class="form-group pt-1">
								  		<label for="store" class="form-label">Store</label><br>
									    <select class="selectpicker form-control" data-width="570px" data-live-search="true" aria-label="Default select example" name="store[]" id="store" multiple>
										  	
										  	@foreach($stores as $store)
							            	<option value="{{ $store->id }}">
							            		{{ $store->store_name }}
							            	</option>
							        		@endforeach
										</select>
								  	</div> -->
								  	
								  	
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
<script type="text/javascript" src="js/vat.js"></script>

@endsection