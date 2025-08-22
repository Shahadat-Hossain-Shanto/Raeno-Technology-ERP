@extends('layouts.master')
@section('title', 'Sub-categories')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> SUB-CATEGORIES</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->

	                	<a href="/subcategory-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Sub-category</button></a>


	                    <div class="pt-3">
												<table id="subcategory_table" class="display">
												    <thead>
												        <tr>
												            <th>#</th>
												            <th>Sub-category Name</th>
												            <th>Categroy</th>
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

<!-- Edit Category Modal -->
<div class="modal fade" id="EDITSubcategoryMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE SUB-CATEGORY</strong></h5>
      </div>


      <!-- Update Sub-category Form -->
      <form id="UPDATESubcategoryFORM" enctype="multipart/form-data">

      	<input type="hidden" name="_method" value="PUT">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

      	<div class="modal-body">

      		<input type="hidden" name="subcategoryid" id="subcategoryid">

            <div class="form-group mb-3">
      			<label>Category Name</label>
      			<input type="text" id="categoryname" name="categoryname" class="form-control" disabled>
      		</div>

      		<div class="form-group mb-3">
      			<label>Sub-category Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_subcategoryname" name="subcategoryname" class="form-control">
				<div id="" class="form-text"><strong>N.B. </strong>Be sure to make your sub-category name meaningful.</div>
				<h6 class="text-danger pt-1" id="edit_wrongsubcategoryname" style="font-size: 14px;"></h6>
      		</div>


	    </div>
	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Sub-category Form -->

    </div>
  </div>
</div>
<!-- End Edit Sub-category Modal -->

<!-- Delete Modal -->

<div class="modal fade" id="DELETESubcategoryMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETESubcategoryFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}


			    <div class="modal-body">
			    	<input type="hidden" name="" id="subcategoryid">
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
<script type="text/javascript" src="js/subcategory.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITSubcategoryMODAL').modal('hide');
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETESubcategoryMODAL').modal('hide');
	});
</script>

@endsection



