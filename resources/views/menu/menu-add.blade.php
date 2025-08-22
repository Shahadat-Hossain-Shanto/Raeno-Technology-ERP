@extends('layouts.master')
@section('title', 'Create Store')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

@section('content')
<div class="content-wrapper" id="container-wrapper">
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
			                <h5 class="m-0">Create Menu</h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

								<form id="AddMenuForm" method="POST" >
		
								  	<div class="form-group pt-3">
									    <label for="menuname" class="form-label">Menu Name</label>
									    <input type="text" class="form-control" name="menuname" id="menuname" placeholder="Enter menu Name">
								  	</div>
								  	<div class="form-group pt-3">
									    <label for="menulink" class="form-label">Menu Link</label>
									    <input type="text" class="form-control" name="menulink" id="menulink" placeholder="Enter menu Link">
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
<script type="text/javascript" src="js/menu.js"></script>

@endsection