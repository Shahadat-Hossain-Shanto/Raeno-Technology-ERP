@extends('layouts.master')
@section('title', 'Roles')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

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
			@hasanyrole('admin')
			<h1>Hello admin {{auth()->user()->name }}</h1> 
		    @endhasanyrole

			@hasanyrole('superadmin')
			<h1>Hello superadmin {{auth()->user()->name }}</h1> 
		    
            @endhasanyrole

			@hasanyrole('editor')
			<h1>Hello editor {{auth()->user()->name }}</h1> 
		    @endhasanyrole

			@hasanyrole('user')
			<h1>Hello user {{auth()->user()->name }}</h1> 

		    
            @endhasanyrole
		 
			
            <div class="row">
	          	<div class="col-lg-12">
	            
		          	<div class="card card-primary">
		              <div class="card-header">
		                	<h5 class="m-0"><strong>ROLES</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/role-create"><button type="button" class="btn btn-outline-info">Create Role</button></a>
					
	                	
	                    <div class="pt-2">
												<table id="role_table" class="display">
												    <thead>
												        <tr>
												            <th>ID</th>
												            <th>Role Name</th>
												            <th>Description</th>
												            <th>Action</th>
												        </tr>
												    </thead>
												    <tbody>

												    </tbody>
											    </table>
											</div>

		              </div> <!-- Card-body -->
		            </div>	<!-- Card -->
	            
		        </div>   <!-- /.col-lg-6 -->
        	</div><!-- /.row -->
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

<!-- Edit Role Modal -->
<div class="modal fade" id="EDITRoleMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Role</h5>	        
      </div>


      <!-- Update Role Form -->
      <form id="UPDATERoleFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="roleid" id="roleid">

      		<div class="form-group mb-3">
      			<label>Role Name</label>
      			<input type="text" id="edit_rolename" name="rolename" class="form-control">
      		</div>

      		<div class="form-group mb-3">
      			<label>Description</label>
      			<textarea class="form-control" name="description" id="edit_description" rows="2"></textarea>
      		</div>

	    </div>

	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Role Form -->

    </div>
  </div>
</div>
<!-- End Edit Role Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETERoleMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETERoleFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="roleid"> 
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

<script type="text/javascript">
	$(document).on('click', '#close', function (e) {
		$('#EDITRoleMODAL').modal('hide');
	});
	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETERoleMODAL').modal('hide');
	});
</script>

@endsection