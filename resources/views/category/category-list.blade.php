@extends('layouts.master')
@section('title', 'Categories')



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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> CATEGORIES</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->

	                	<a href="/category-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Category</button></a>


	                    <div class="pt-3">
	                    	<div class="table-responsive">
													<table id="category_table" class="display" width="100%">
												    <thead>
												        <tr>
												            <th>#</th>
												            <th>Category Name</th>
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

<!-- Edit Category Modal -->
<div class="modal fade" id="EDITCategoryMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE CATEGORY</strong></h5>
      </div>


      <!-- Update Category Form -->
      <form id="UPDATECategoryFORM">

      <input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

      	<div class="modal-body">

      		<input type="hidden" name="categoryid" id="categoryid">

      		<div class="form-group mb-3">
      			<label for="edit_categoryname" class="form-label">Category Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_categoryname" name="edit_categoryname" class="form-control">
						<div id="" class="form-text"><strong>N.B. </strong>Be sure to make your category name meaningful.</div>
						<h6 class="text-danger pt-1" id="edit_wrongcategoryname" style="font-size: 14px;"></h6>
      		</div>
            {{-- <div class="form-group">
				<label for="edit_categoryimage" class="form-label" style="font-weight: normal;">Category Image<span class="text-danger"><strong></strong></span></label>
				<input type="file" class="form-control w-50" name="edit_categoryimage" id="edit_categoryimage">
			</div> --}}


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

<div class="modal fade" id="DELETECategoryMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETECategoryFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}


			    <div class="modal-body">
			    	<input type="hidden" name="" id="categoryid">
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
<script type="text/javascript" src="js/category.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITCategoryMODAL').modal('hide');
		$('#edit_wrongcategoryname').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETECategoryMODAL').modal('hide');

	});
</script>

@endsection



