@extends('layouts.master')
@section('title', 'Stores')



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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> STORE</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	{{-- @can('stores.create.view') --}}
						<a href="/warehouse-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Store</button></a>
						{{-- @endcan --}}



	                    <div class="pt-3">
												<table id="store_table" class="display">
												    <thead>
												        <tr>
												            <th>#</th>
												            <th>Name</th>
												            <th>Address</th>
												            <th>Contact No.</th>
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

{{-- @can('stores.edit.view') --}}
<!-- Edit Store Modal -->
<div class="modal fade" id="EDITStoreMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><strong>UPDATE STORE</strong></h5>
      </div>


      <!-- Update Store Form -->
      <form id="UPDATEStoreFORM" enctype="multipart/form-data">

      	<input type="hidden" name="_method" value="PUT">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

      	<div class="modal-body">

      		<input type="hidden" name="storeid" id="storeid">

      		<div class="form-group mb-3">
      			<label>Store Name<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_storename" name="store_name" class="form-control">
      			<h6 class="text-danger pt-1" id="edit_wrongstorename" style="font-size: 14px;"></h6>
      		</div>
      		<div class="form-group mb-3">
      			<label>Store Address<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_storeaddress" name="storeaddress" class="form-control">
				    <h6 class="text-danger pt-1" id="edit_wrongstoreaddress" style="font-size: 14px;"></h6>

      		</div>
      		<div class="form-group mb-3">
      			<label>Contact No.<span class="text-danger"><strong>*</strong></span></label>
      			<input type="text" id="edit_contactnumber" name="contactnumber" class="form-control">
						<h6 class="text-danger pt-1" id="edit_wrongcontactnumber" style="font-size: 14px;"></h6>

      		</div>

	    </div>
	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Update</button>
	    </div>
      </form>
      <!-- End Update Store Form -->

    </div>
  </div>
</div>
<!-- End Edit Store Modal -->
{{-- @endcan --}}

{{-- @can('stores.destroy') --}}
<!-- Delete Modal -->

<div class="modal fade" id="DELETEStoreMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEStoreFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}


			    <div class="modal-body">
			    	<input type="hidden" name="" id="storeid">
			      <h5 class="text-center">Are you sure you want to delete?</h5>
			    </div>

			    <div class="modal-footer justify-content-center">
			        <button type="button" class="cancel btn btn-outline-secondary cancel_btn" data-dismiss="modal">Cancel</button>
			        <button type="submit" class="delete btn btn-outline-danger">Yes</button>
			    </div>

			</form>

		</div>
	</div>
</div>

<!-- END Delete Modal -->
{{-- @endcan --}}

@endsection

@section('script')
<script type="text/javascript" src="js/store.js"></script>
<script type="text/javascript">

	$(document).on('click', '#close', function (e) {
		$('#EDITStoreMODAL').modal('hide');
		$('#edit_wrongstorename').empty();
		$('#edit_wrongstoreaddress').empty();
		$('#edit_wrongcontactnumber').empty();
	});

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEStoreMODAL').modal('hide');
	});
</script>

@endsection



