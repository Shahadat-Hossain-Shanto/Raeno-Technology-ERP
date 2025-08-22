@extends('layouts.master')
@section('title', 'Deposit Reports')

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
        			<div class="col-md-6 col-lg-6 ">
		            
		            <div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title"><strong><i class="fas fa-file-contract"></i> DEPOSIT REPORT</strong></h3>

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

		                <div class="row">
				              <div class="col-12 col-sm-6 col-md-4">
				                <div class="info-box">
				                  <span class="info-box-icon bg-info elevation-1"><i style="color: white;" class="fas fa-file-invoice-dollar"></i></span>

				                  <div class="info-box-content">
				                    <h6 class="info-box-text mb-1" style="">Total Sale</h6>
				                    <h6 class="info-box-text mb-0">Amount: <strong><span id="totalSale">{{ number_format(0, 2) }}</span></strong> </h6>
				                   
				                  </div><!-- /.info-box-content -->
				                </div><!-- /.info-box -->
				              </div><!-- /.col -->
				              <div class="col-12 col-sm-6 col-md-4">
				                <div class="info-box">
				                  <span class="info-box-icon bg-warning elevation-1"><i style="color: white;" class="fas fa-file-invoice-dollar"></i></span>

				                  <div class="info-box-content">
				                    <h6 class="info-box-text mb-1" style="">Total Deposit</h6>
				                    <h6 class="info-box-text mb-0">Amount: <strong><span id="totalDeposit">{{ number_format(0, 2) }}</span></strong></h6>
				                    
				                  </div><!-- /.info-box-content -->
				                </div><!-- /.info-box -->
				              </div><!-- /.col -->
				              <div class="col-12 col-sm-6 col-md-4">
				                <div class="info-box">
				                  <span class="info-box-icon bg-danger elevation-1"><i style="color: white;" class="fas fa-file-invoice-dollar"></i></span>

				                  <div class="info-box-content">
				                    <h6 class="info-box-text mb-1" style="">Total Due</h6>
				                    <h6 class="info-box-text mb-0">Amount: <strong><span id="totalDue">{{ number_format(0, 2) }}</span></strong> </h6>
				                    
				                  </div><!-- /.info-box-content -->
				                </div><!-- /.info-box -->
				              </div><!-- /.col -->
				            </div>

				            <form id="DepositReport" method="POST" enctype="multipart/form-data">
				            	<!-- <small id="" class="form-text text-muted mb-0 pt-5">Genrate Custom Expense Report</small> -->
				            	<h4 class="mb-0 pt-5" style="font"><strong><u>Generate Deposit Report</u></strong></h4>
										  <div class="form-row pt-3">

										    <div class="form-group col-md-3">

										      <label for="startdate">From</label>
										      <input type="date" class="form-control" id="startdate" name="startdate">
										    </div>
										    <div class="form-group col-md-3">
										      <label for="enddate">To</label>
										      <input type="date" class="form-control" id="enddate" name="enddate">
										    </div>
										    
										    <div style="padding-top: 32px;" class="form-group col-md-6">
										      <button type="submit" class="btn btn-primary">Generate</button>
										      <button type="reset" value="Reset" class="btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
										    </div>
										    
										  </div>
										</form>
										<hr>
										<div class="row">
		                	<div class="col-12">
		                		<h4 class="pt-2"><strong><u>Deposits & Dues</u></strong></h4>
		                		<div class="pt-2">
		                			<table id="deposit_table" class="display">
													  <thead>
													    <tr>
													    	<th>#</th>
													      <th>Deposit Date</th>
													      <th>Deposit</th>
													      <th>Due</th>
													    </tr>
													  </thead>
													  <tbody class="deposit_table">
													    
													  </tbody>
													</table>
		                		</div>
							  					
		                	</div>
		                </div>
		              </div>
		              <!-- /.card-body -->
		            </div>
		            <!-- /.card -->
		        	</div>   <!-- /.col-lg-6 -->
		        	<div class="col-md-6 col-lg-6">
		        		<div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title"><strong>DETAILS</strong></h3>

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

		                <div class="row">
		                	<div class="col-12">
		                		<h4 class="pt-2"><strong><u>Details</u></strong></h4>
		                		<h5><span id="detailDate">Click date to see details.</span></h5>
		                		<div class="pt-2">
		                			<table id="deposit_table_details" class="display hidden">
													  <thead>
													    <tr>
													    	<th>#</th>
													      <th>Client</th>
													      <th>Deposit</th>
													      <th>Due</th>
													    </tr>
													  </thead>
													  <tbody class="deposit_details">
													    
													  </tbody>
													</table>
		                		</div>
							  					
		                	</div>
		                </div>
				             
		              </div>
		              <!-- /.card-body -->
		            </div>
		        	</div>
        		</div><!-- /.row -->

        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="js/deposit-report.js"></script>


@endsection


	
