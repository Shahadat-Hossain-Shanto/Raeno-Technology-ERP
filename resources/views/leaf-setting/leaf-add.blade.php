@extends('layouts.master')
@section('title', 'Create Leaf')

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
			                <h5 class="m-0"><strong><i class="fas fa-leaf"></i> LEAF</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddLeafForm" method="POST" enctype="multipart/form-data">
									
								  	<div class="form-group">
									    <label for="leaftype" class="form-label" style="font-weight: normal;">Leaf Type<span class="text-danger"><strong>*</strong></span></label>
									    <input type="text" class="form-control w-50" name="leaftype" id="leaftype" placeholder="1*10 or 3*5">
									    <h6 class="text-danger pt-1" id="wrongleaftype" style="font-size: 14px;"></h6>
									    <div class="form-text">N.B. Here 1 is number of leaves and 10 is for indicating medicines per leaf.</div>
								  	
								  	</div>
								  	<div class="form-group">
									    <label for="totalnumberofperbox" class="form-label" style="font-weight: normal;">Medicine Per Box<span class="text-danger"><strong>*</strong></span></label>
									    <input type="number" class="form-control w-50" name="totalnumberofperbox" id="totalnumberofperbox" placeholder="10 or 15">
									    <h6 class="text-danger pt-1" id="wrongtotalnumberofperbox" style="font-size: 14px;"></h6>
								  		<div class="form-text">N.B. The value shows the total number of medicines per box. 1*10(10) or 3*5(15)</div>
								  	</div>
								  	
								  	
								  	<div class="form-group pt-3">
									  	<button type="submit" class="btn btn-primary">Create</button>
										<button type="reset" value="Reset" class="btn btn-outline-danger">Reset</button>
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
<script type="text/javascript" src="js/leaf.js"></script>

@endsection