@extends('layouts.master')
@section('title', 'Add Account')

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
			                <h5 class="m-0"><strong><i class="fas fa-barcode"></i> ADD ACCOUNT</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddAccountForm" method="POST" enctype="multipart/form-data">
									
								  	<div class="form-group">
									    <label for="roothead" class="form-label" style="font-weight: normal;">Root Head<span class="text-danger"><strong>*</strong></span></label><br>
									   	<select class="selectpicker" data-width="50%" data-live-search="true" aria-label="Default select example" name="roothead" id="roothead">
									      	<option value="" disabled selected>Select Root Head</option>
								      		@foreach($rootHeads as $rootHead)
									            <option value="{{ $rootHead->head_code  }}">{{ $rootHead->head_name  }}</option>
									        @endforeach
									    </select>
									    <h6 class="text-danger pt-1" id="wrongroothead" style="font-size: 14px;"></h6>
								  	</div>
								  	<div class="form-group">
									    <label for="parenthead" class="form-label" style="font-weight: normal;">Parent Head<span class="text-danger"><strong>*</strong></span></label><br>
									    <select class="selectpicker" data-width="50%" data-live-search="true" aria-label="Default select example" name="parenthead" id="parenthead">
									      	<option value="" disabled selected>Select Parent Head</option>
									    </select>
									    <h6 class="text-danger pt-1" id="wrongparenthead" style="font-size: 14px;"></h6>
								  	</div>

								  	<div class="form-group">
								  		<label for="headname" class="form-label" style="font-weight: normal;">Account/Head Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="headname" id="headname" placeholder="e.g. Head Name">
									    <h6 class="text-danger pt-1" id="wrongheadname" style="font-size: 14px;"></h6>
									</div>
								  	
								  	
								  	<div class="form-group pt-3">
									  	<button type="submit" class="btn btn-primary">Create</button>
										<button type="reset" value="Reset"  onclick="resetButton();" class="btn btn-outline-danger"><i class="fas fa-eraser"></i> Reset</button>
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
<script type="text/javascript" src="js/account.js"></script>

@endsection