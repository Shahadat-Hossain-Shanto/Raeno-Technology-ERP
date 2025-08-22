@extends('layouts.master')
@section('title', 'Categories')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

@section('content')
<div class="content-wrapper" id="container-wrapper">
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
	            
		          	<div class="card card-primary card-outline">
		              <div class="card-header">
		                	<h5 class="m-0">Menu List</h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/menu-create"><button type="button" class="btn btn-primary">Create Menu</button></a>
	                	
	                	
	                    <div class="pt-2">
												<table id="menu_table" class="display">
                                                    <thead>
												        <tr>
												            <th>ID</th>
												            <th>Menu Name</th>
                                                            <th>Menu Link</th>
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

<!-- Edit Category Modal -->
<div class="modal fade" id="EDITMenuMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Menu</h5>	        
      </div>


      <!-- Update Category Form -->
      <form id="UPDATEMenuFORM" enctype="multipart/form-data">
      	
      	<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
      	
      	<div class="modal-body">

      		<input type="hidden" name="id" id="id">

      		<div class="form-group mb-3">
      			<label>Menu Name</label>
      			<input type="text" id="edit_menuname" name="menuname" class="form-control">
      		</div>
      		
      			       
	    </div>
        <div class="modal-body">

          
            <div class="form-group mb-3">
                <label>Menu Link</label>
                <input type="text" id="edit_menulink" name="menulink" class="form-control">
            </div>
            
                       
      </div>
	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Category Form -->

    </div>
  </div>
</div>
<!-- End Edit Category Modal -->

<!-- Delete Modal --> 

<div class="modal fade" id="DELETEMenuMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEMenuFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}
				

			    <div class="modal-body"> 
			    	<input type="hidden" name="" id="id"> 
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


<!-- Data Tablee-->


<!-- END Data Tablee -->

<!-- END Delete Modal -->

@endsection

@section('script')
<script type="text/javascript" src="js/menu.js"></script>
<script type="text/javascript">

$(document).ready( function () {
	$('#menu_table').DataTable();
});

	$(document).on('click', '#close', function (e) {
		$('#EDITMenuMODAL').modal('hide');
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEMenuMODAL').modal('hide');
	});
</script>

@endsection
