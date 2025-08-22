@extends('layouts.master')
@section('title', 'Chart of Accounts')

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
			                <h5 class="m-0"><strong><i class="fas fa-code-branch"></i> Chart Of Accounts</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">
								<div id="jstree">

								</div>
								

							</div> <!-- container -->
						</div> <!-- card-body -->
		          	</div> <!-- card card-primary card-outline -->
      			</div> <!-- col-lg-5 -->
      		</div> <!-- row -->
		</div> <!-- container-fluid -->
	</div> <!-- content -->
	
</div> <!-- content-wrapper -->

<!-- View Account Modal -->
<div class="modal fade" id="ViewAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>CHART OF ACCOUNTS</strong></h5>	        
      </div>


      <!-- View Account Form -->
      	<form id="ViewAccountFORM" enctype="multipart/form-data">
      	
      		<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      		<div class="modal-body">

	      		<input type="hidden" name="coaid" id="coaid">

	      		<div class="form-group mb-3">
	      			<label>Head Code<span class="text-danger"><strong>*</strong></span></label>
	      			<input type="text" id="edit_headcode" name="headcode" class="form-control" readonly>
	      		</div>

	      		<div class="form-group mb-3">
	      			<label>Head Name<span class="text-danger"><strong>*</strong></span></label>
	      			<input type="text" id="edit_headname" name="headname" class="form-control">
	      			<h6 class="text-danger pt-1" id="wrongheadname" style="font-size: 14px;"></h6>
	      		</div>
	      		<div class="form-group mb-3">
	      			<label>Parent Head<span class="text-danger"><strong>*</strong></span></label>
	      			<input type="text" id="edit_parenthead" name="parenthead" class="form-control" readonly>
	      		</div>
	      		<div class="form-group mb-3">
	      			<label>Parent Head Level<span class="text-danger"><strong>*</strong></span></label>
	      			<input type="text" id="edit_parentheadlevel" name="parentheadlevel" class="form-control" readonly>
	      		</div>

	      		<div class="form-group mb-3">
	      			<label>Head Type<span class="text-danger"><strong>*</strong></span></label>
	      			<input type="text" id="edit_headtype" name="headtype" class="form-control" readonly>
	      		</div>
	      		<div class="form-group mb-1 ml-3">
	      			 <input class="form-check-input" type="checkbox" value="" name="istransaction" id="edit_istransaction">
	      			<label>IsTransaction<span class="text-danger"><strong>*</strong></span></label>
	      		</div>
	      		<div class="form-group mb-1 ml-3">
	      			 <input class="form-check-input" type="checkbox" value="" name="isactive" id="edit_isactive">
	      			<label>IsActive<span class="text-danger"><strong>*</strong></span></label>
	      		</div>
	      		<div class="form-group mb-1 ml-3">
	      			 <input class="form-check-input" type="checkbox" value="" name="isgl" id="edit_isgl">
	      			<label>IsGL<span class="text-danger"><strong>*</strong></span></label>
	      		</div>
      			       
	    	</div>
		    <div class="modal-footer">
		        <button id="close" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-success">Update</button>
		        <button id="newBtn" type="button" class="btn btn-primary">New</button>
		    </div>
      	</form>
      <!-- End View Account Form -->

    </div>
  </div>
</div>
<!-- End View Account Modal -->


<!-- New Account Modal -->
<div class="modal fade" id="NewAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>CHART OF ACCOUNTS</strong></h5>	        
      </div>


      <!-- New Account Form -->
      	<form id="NewAccountFORM" method="POST" enctype="multipart/form-data">
      	
      		<!-- <input type="hidden" name="_method" value="PUT"> -->
			<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
      	
      		<div class="modal-body">

	      		<!-- <input type="hidden" name="coaid" id="coaid"> -->

	      		<div class="form-group mb-3">
	      			<label>Head Code<span class="text-danger"><strong>*</strong></span></label>
	      			<input type="text" id="headcode" name="headcode" class="form-control" readonly>
	      		</div>

	      		<div class="form-group mb-3">
	      			<label>Head Name<span class="text-danger"><strong>*</strong></span></label>
	      			<input type="text" id="headname" name="headname" class="form-control">
	      			<h6 class="text-danger pt-1" id="wrongheadnameX" style="font-size: 14px;"></h6>
	      		</div>
	      		<div class="form-group mb-3">
	      			<label>Parent Head<span class="text-danger"><strong>*</strong></span></label>
	      			<input type="text" id="parenthead" name="parenthead" class="form-control" readonly>
	      		</div>
	      		<div class="form-group mb-3">
	      			<label>Parent Head Level<span class="text-danger"><strong>*</strong></span></label>
	      			<input type="text" id="parentheadlevel" name="parentheadlevel" class="form-control" readonly>
	      		</div>

	      		<div class="form-group mb-3">
	      			<label>Head Type<span class="text-danger"><strong>*</strong></span></label>
	      			<input type="text" id="headtype" name="headtype" class="form-control" readonly>
	      		</div>
	      		<div class="form-group mb-1 ml-3">
	      			 <input class="form-check-input" type="checkbox" value="" name="istransaction" id="istransaction">
	      			<label>IsTransaction<span class="text-danger"><strong>*</strong></span></label>
	      		</div>
	      		<div class="form-group mb-1 ml-3">
	      			 <input class="form-check-input" type="checkbox" value="" name="isactive" id="isactive">
	      			<label>IsActive<span class="text-danger"><strong>*</strong></span></label>
	      		</div>
	      		<div class="form-group mb-1 ml-3">
	      			 <input class="form-check-input" type="checkbox" value="" name="isgl" id="isgl">
	      			<label>IsGL<span class="text-danger"><strong>*</strong></span></label>
	      		</div>
      			       
	    	</div>
		    <div class="modal-footer">
		        <button id="closeX" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-success">Save</button>
		        
		    </div>
      	</form>
      <!-- End New Account Form -->

    </div>
  </div>
</div>
<!-- End New Account Modal -->


@endsection

@section('script')
<!-- jQuery treeView -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{ asset('dist/jstree.min.js') }}"></script>
<script type="text/javascript" src="js/chart-of-accounts.js"></script>
<script type="text/javascript">
	$(document).on('click', '#close', function (e) {
		$('#ViewAccountModal').modal('hide');
	});

	$(document).on('click', '#closeX', function (e) {
		$('#NewAccountModal').modal('hide');
	});
</script>

@endsection