@extends('layouts.master')
@section('title', 'Sales Return List')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> SALES RETURNS</strong></h5>
		              </div>
		              <div class="card-body">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->
	                	
	                	<a href="/sales-return-create"><button type="button" class="btn btn-outline-info"><i class="fas fa-plus"></i> Create Sales Return</button></a>
	                	
	                	<input type="hidden" name="" id="subscriberid" value="{{auth()->user()->subscriber_id}}">
	                    <div class="pt-3">
												<table id="return_table" class="display" width="100%">
												    <thead>
												        <tr>
												        		<th>#</th>
												            <th>Invoice No.</th>
												            <th>Return No.</th>
												            <th>Deduction</th>
												            <th>Tax</th>
												            <th>Net Return</th>
												            <th>Note</th>
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


@endsection

@section('script')
<script type="text/javascript" src="js/sales-return-list.js"></script>
<script type="text/javascript">

</script>

@endsection


	
