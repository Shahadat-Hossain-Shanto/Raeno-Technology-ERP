@extends('layouts.master')
@section('title', 'Create POS')

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
			                <h5 class="m-0"><strong><i class="fas fa-store-alt"></i> POS</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddPosForm" method="POST" enctype="multipart/form-data">
									{{ csrf_field() }}
									<div class="form-group">
								    	<label for="storeid" class="form-label" style="font-weight: normal;">Store<span class="text-danger"><strong>*</strong></span></label><br>
									    <select class="selectpicker" data-live-search="true" name="storeid" title="Select Store" data-width="380px">
										  	@foreach($stores as $store)
							            	<option value="{{ $store->id }}">{{ $store->store_name }}</option>
							        		@endforeach
										</select>
									    <h6 class="text-danger pt-1" id="wrongstoreid" style="font-size: 14px;"></h6>

									</div>

									<div class="form-group pt-1">
									    <label for="posname" class="form-label" style="font-weight: normal;">POS Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="pos_name" id="posname" placeholder="e.g. Counter-1">
									    <h6 class="text-danger pt-1" id="wrongposname" style="font-size: 14px;"></h6>

								  	</div>
								    
								    

									<div class="form-group pt-1">
									    <label for="pospin" class="form-label" style="font-weight: normal;">POS PIN<span class="text-danger"><strong>*</strong></span></label>
									    <input type="password" class="form-control w-50" name="pospin" id="pospin" placeholder="e.g. 1214">
									    <div id="" class="form-text">Your POS PIN must be 4 characters long.</div>
									    <h6 class="text-danger pt-1" id="wrongpospin" style="font-size: 14px;"></h6>

								  	</div>

								  	<div class="form-group pt-1">
									  	<button type="submit" class="btn btn-primary"> Create</button>
										<button type="reset" value="Reset" onclick="resetButton()" class="btn btn-outline-danger"><i class="fas fa-eraser"></i> Reset</button>
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
<script type="text/javascript" src="js/pos-device.js"></script>

@endsection