@extends('layouts.master')
@section('title', 'Banks')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> BANKS</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/bank-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Bank</button></a>
	                	
	                	<input type="hidden" name="" id="subscriberid" value="{{auth()->user()->subscriber_id}}">
	                    <div class="pt-3">
	                    	<div class="table-responsive">
													<table id="bank_table" class="display" width="100%">
												    <thead>
												        <tr>
												        		<th>#</th>
												            <th>Bank Name</th>
												            <th>Account Name</th>
												            <th>Account Number</th>
												            <th>Branch</th>
												            <th>Signature/Cheque</th>
												            <th>Balance</th>
												            <th>Action</th>
												        </tr>
												    </thead>
												   <!--  <tbody>

												    </tbody> -->
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

<!-- Edit Bank Modal -->
<div class="modal fade" id="EDITBankMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE BANK</strong></h5>	        
      </div>


      <!-- Update Bank Form -->
      <form id="UPDATEBankFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="bankid" id="bankid">

      		<div class="form-group mb-3">
      			<label>Bank Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_bankname" name="bankname" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongbankname" style="font-size: 14px;"></h6>
      		</div>
      		<div class="form-group mb-3">
      			<label>Account Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_accountname" name="accountname" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongaccountname" style="font-size: 14px;"></h6>
      		</div>
      		<div class="form-group mb-3">
      			<label>Account Number<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_accountnumber" name="accountnumber" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongaccountnumber" style="font-size: 14px;"></h6>
      		</div>
      		<div class="form-group mb-3">
      			<label>Branch<span class="text-danger"><strong></strong></span></label>
      			<input type="text" id="edit_branch" name="branch" class="form-control">
						<!-- <h6 class="text-danger pt-1" id="edit_wrongbranch" style="font-size: 14px;"></h6> -->
      		</div>
      		<div class="form-group mb-3">
      			<label>Balance<span class="text-danger"><strong></strong></span></label>
      			<input type="text" id="edit_balance" name="balance" class="form-control">
						<!-- <h6 class="text-danger pt-1" id="edit_wrongbalance" style="font-size: 14px;"></h6> -->
      		</div>
      		<div class="form-group mb-3">
      			<label>Status<span class="text-danger"><strong>*</strong></span></label>
      			<select class="form-control w-75" name="status" id="edit_status" title="Select Status">
			    		<option selected disabled>Select Status</option>
			    		<option value="active">Active</option>
			    		<option value="inactive">Inactive</option>

			    	</select>
						<h6 class="text-danger pt-1" id="edit_wrongstatus" style="font-size: 14px;"></h6>
      		</div>
      		<div class="form-group mb-3">
      			<img src="" alt="Bank image" width="100px" height="100px" alt="image" class="pb-3" name="bankimage" id="edit_bankimage">
      			<label>Signature/Cheque<span class="text-danger"><strong></strong></span></label>

						<input id="input-b1" name="imagefile" type="file" class="file productimage" data-browse-on-zone-click="true">
      			
						
      		</div>

      		
      			       
	    	</div>
		    <div class="modal-footer">
		        <button id="close" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-primary">Update</button>
		    </div>
      </form>
      <!-- End Update Bank Form -->

    </div>
  </div>
</div>
<!-- End Edit Bank Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEBankMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEBankFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="bankid"> 
			      <h5 class="text-center">Are you sure you want to delete?</h5>
			    </div>

			    <div class="modal-footer justify-content-center">
			        <button type="button" class="cancel_btn btn btn-secondary" data-dismiss="modal">Cancel</button>
			        <button type="submit" class="delete btn btn-outline-danger">Yes</button>
			    </div>

			</form>

		</div>
	</div>
</div>

<!-- END Delete Modal -->

@endsection

@section('script')
<script type="text/javascript" src="js/bank.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITBankMODAL').modal('hide');
		$('#edit_wrongbankname').empty();
		$('#edit_wrongaccountname').empty();
		$('#edit_wrongaccountnumber').empty();
		$('#edit_wrongstatus').empty();
		

	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEBankMODAL').modal('hide');
	});
</script>

@endsection


	
