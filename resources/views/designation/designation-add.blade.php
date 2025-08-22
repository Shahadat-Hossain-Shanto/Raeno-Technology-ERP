@extends('layouts.master')
@section('title', 'Create Designation')

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
			                <h5 class="m-0"><strong><i class="fas fa-wallet"></i> DESIGNATION </strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddDesignationForm" method="POST" enctype="multipart/form-data">
								{{ csrf_field() }}
									<div class="form-group">
									    <label for="designation_name" class="form-label" style="font-weight: normal;">Designation Name<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-75" name="designation_name" id="designation_name" placeholder="e.g. Store Manager">
									
									    <h6 class="text-danger pt-1" id="wrong_designation_name" style="font-size: 14px;"></h6>
									
								  	</div>

                                      <div class="form-group">
									    <label for="designation_description" class="form-label" style="font-weight: normal;">Designation Description<span class="text-danger"><strong></strong></span></label>
                                        <textarea class="form-control w-75" rows="3" id="designation_description" name="designation_description" placeholder="if any designation"></textarea>
									
									    <h6 class="text-danger pt-1" id="wrong_designation_description" style="font-size: 14px;"></h6>
									
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
<script type="text/javascript" src="{{asset('js/designation.js')}}"></script>

@endsection