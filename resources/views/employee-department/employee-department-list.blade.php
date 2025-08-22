@extends('layouts.master')
@section('title', 'Employee Department')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> EMPLOYEE DEPARTMENTS</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/employee-department-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Department</button></a>
	                	
	                	<input type="hidden" name="" id="subscriberid" value="{{auth()->user()->subscriber_id}}">
	                    <div class="pt-3">
	                    	<div class="table-responsive">
													<table id="employee_department_table" class="display" width="100%">
												    <thead>
												        <tr>
																		<th>#</th>
												            <th>Department</th>
												            <th>Job Description</th>
												            <th>Action</th>
												        </tr>
												    </thead>
												    <!-- <tbody>

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

<!-- Edit EmployeeDepartment Modal -->
<div class="modal fade" id="EDITEmployeeDepartmentMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE EMPLOYEE DEPARTMENT</strong></h5>	        
      </div>


      <!-- Update EmployeeDepartment Form -->
      <form id="UPDATEEmployeeDepartmentFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="employeedepartmentid" id="employeedepartmentid">

      		<div class="form-group mb-3">
      			<label>Department Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_departmentname" name="departmentname" class="form-control w-75">
				<h6 class="text-danger pt-1" id="edit_wrongdepartmentname" style="font-size: 14px;"></h6>
      		</div>

      		<div class="form-group mb-3">
      			<label>Job Description<span class="text-danger"><strong>*</strong></span></label>
				<textarea rows="2" placeholder="e.g. For General purpose" class="form-control w-75" name="jobdescription" id="edit_jobdescription"></textarea>

						
      		</div>
      			       
	    	</div>
		    <div class="modal-footer">
		        <button id="close" type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
		        <button type="submit" class="btn btn-primary">Update</button>
		    </div>
      </form>
      <!-- End Update EmployeeDepartment Form -->

    </div>
  </div>
</div>
<!-- End Edit EmployeeDepartment Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEEmployeeDepartmentMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEEmployeeDepartmentFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="employeedepartmentid"> 
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
<script type="text/javascript" src="js/employee-department.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITEmployeeDepartmentMODAL').modal('hide');
		$('#edit_wrongedepartmentname').empty();
		// $('#edit_wrongexpirydate').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEEmployeeDepartmentMODAL').modal('hide');
	});
</script>

@endsection


	
