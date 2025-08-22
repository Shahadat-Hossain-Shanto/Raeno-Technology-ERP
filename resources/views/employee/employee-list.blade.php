@extends('layouts.master')
@section('title', 'Employees')

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
											<h5 class="m-0"><b><i class="fas fa-users"></i>	EMPLOYEES</b></h5>
									</div>
	              	<div class="card-body">
                		<a href="/employee-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Employee</button></a>
										<div class="pt-3">
											<table id="emp_table" class="display" width="100%">
												<thead>
													<tr>
														<th>#</th>
														<th>ID</th>
														<th>Name</th>
														<th>Designation</th>
														<th>Email</th>
														<th>Phone</th>
														<th>Address</th>
														<th>Status</th>
														<th>Salary Grade</th>
														<th>Image</th>
														<th>Action</th>
													</tr>
												</thead>
												<!-- <tbody>

												</tbody> -->
											</table>
										</div>

		              	</div> <!-- Card-body -->
		            </div>	<!-- Card -->
		        </div>   <!-- /.col-lg-6 -->
        	</div><!-- /.row -->
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

<!-- Edit Emp Modal -->
<div class="modal fade" id="EDITEmpMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Employee</h5>	        
      </div>


      <!-- Update Emp Form -->
      <form id="UPDATEEmpFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="empid" id="empid">

      		<div class="form-group mb-3">
      			<label>Name</label>
      			<input type="text" id="edit_empname" name="empname" class="form-control">
      		</div>

      		<div class="form-group mb-3">
      			<label>Email</label>
      			<input type="email" id="edit_empemail" name="empemail" class="form-control">
      		</div>

      		<div class="form-group mb-3">
      			<label>Mobile</label>
      			<input type="text" id="edit_empmobile" name="empmobile" class="form-control">
      		</div>

      		<div class="form-group mb-3">
      			<label>Role</label>
      			<select class="selectpicker form-control" data-live-search="true" aria-label="Default select example" name="role" id="edit_role" multiple>  	
						  	{{-- @foreach($roles as $role)
			            	<option value="{{ $role->roleName }}">
			            		{{ $role->roleName }}
			            	</option>
			        		@endforeach --}}
				</select>
      		</div>
	       
	    </div>

	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Emp Form -->

    </div>
  </div>
</div>
<!-- End Edit Emp Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEEmpMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEEmpFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="empid"> 
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
<script type="text/javascript" src="js/employee.js"></script>
<script type="text/javascript">
	$(document).on('click', '#close', function (e) {
		$('#EDITEmpMODAL').modal('hide');
	});
	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEEmpMODAL').modal('hide');
	});
</script>

@endsection


	