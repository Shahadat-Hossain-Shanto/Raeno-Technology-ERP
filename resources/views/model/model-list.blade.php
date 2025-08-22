@extends('layouts.master')
@section('title', 'Models')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clone"></i> Models</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->

	                	<a href="/model-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Model</button></a>

	                	<input type="hidden" name="" id="subscriberid" value="{{auth()->user()->subscriber_id}}">
	                    <div class="pt-3">
	                    	<div class="table-responsive">
													<table id="model_table" class="display" width="100%">
												    <thead>
												        <tr>
											            <th>#</th>
											            <th>ID</th>
											            <th>Name</th>
											            {{-- <th>Logo</th> --}}
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

<!-- Edit model Modal -->
<div class="modal fade" id="EDITModelMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE Model</strong></h5>
      </div>


      <!-- Update model Form -->
      <form id="UPDATEModelFORM">

      	<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

      	<div class="modal-body">

      		<input type="hidden" name="modelid" id="modelid">

      		<div class="form-group mb-3">
      			<label>Models Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_modelname" name="modelname" class="form-control">
      			<h6 class="text-danger pt-1" id="edit_wrongmodelname" style="font-size: 14px;"></h6>
      		</div>
	    </div>
	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Brand Form -->

    </div>
  </div>
</div>
<!-- End Edit Brand Modal -->

<!-- Delete Modal -->

<div class="modal fade" id="DELETEModelMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEModelFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}


			    <div class="modal-body">
			    	<input type="hidden" name="" id="modelid">
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
<script type="text/javascript" src="js/model.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITModelMODAL').modal('hide');
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEModelMODAL').modal('hide');
	});
</script>

@endsection



