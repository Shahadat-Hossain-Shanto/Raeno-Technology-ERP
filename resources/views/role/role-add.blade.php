@extends('layouts.master')
@section('title', 'Create Role')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

@section('content')
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<!-- Header -->
			</div>
		</div>
	</div>

	<div class="content pt-4 ">
		<div class="container-fluid ">
			<div class="row">
      			<div class="col-lg-12">
          			<div class="card card-primary card-outline ">
			            <div class="card-header">
			                <h5 class="m-0">Create Role</h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddRoleForm" method="POST" enctype="multipart/form-data">
									<div class="form-group">
									    <label for="rolename" class="form-label">Role Name</label>
									    <input type="text" class="form-control" name="rolename" id="rolename" placeholder="Enter role name">
								  	</div>

								  	<div class="form-group">
									    <label for="description" class="form-label">Description</label>
									    <textarea class="form-control" name="description" id="description" rows="2" placeholder="Enter role description"></textarea>
								  	</div>

								  	<div class="form-group pt-3">
									  	<button type="submit" class="btn btn-outline-primary">Create</button>
										<button type="reset" value="Reset" class="btn btn-primary">Reset</button>
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
<script type="text/javascript" src="js/role.js"></script>
@endsection