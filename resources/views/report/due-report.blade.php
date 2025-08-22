@extends('layouts.master')
@section('title', 'Due Reports')

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
        			<div class="col-lg-8">
		            <!-- BAR CHART -->
		            <div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title"><strong>DUE REPORT</strong></h3>

		                <div class="card-tools">
		                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
		                    <i class="fas fa-minus"></i>
		                  </button>
		                  <button type="button" class="btn btn-tool" data-card-widget="remove">
		                    <i class="fas fa-times"></i>
		                  </button>
		                </div>
		              </div>
		              <div class="card-body" >

		                <div class="pt-2">
		                  <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
					  					<table id="due_table" class="table table-bordered">
											  <thead>
											    <tr>
											    	<th class="">#</th>
											      <th scope="col">Client Name</th>
											      <th scope="col">Total Due</th>
											    </tr>
											  </thead>
											  <!-- <tbody>
											    
											  </tbody> -->
											</table>
		                  

		                </div>
		              </div>
		              <!-- /.card-body -->
		            </div>
		            <!-- /.card -->
		        	</div>   <!-- /.col-lg-6 -->
        		</div><!-- /.row -->

        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="js/due-report.js"></script>


@endsection


	
