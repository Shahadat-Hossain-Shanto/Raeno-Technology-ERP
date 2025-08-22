@extends('layouts.master')
@section('title', 'Bank Reports')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

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
		            <!-- BAR CHART -->
		            <div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title"><strong><i class="fas fa-file-contract"></i> Bank Transaction</strong></h3>

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

		              	<form id="ReportForm" method="POST" enctype="multipart/form-data">
										  <div class="form-row">
										    <div class="form-group col-md-3"> 
											  <label for="productname" class="form-label" style="font-weight: normal;">From<span class="text-danger"><strong>*</strong></span></label>
										    
										      <input type="date" class="form-control" id="startdate" name="startdate">
										    </div>
										    <div class="form-group col-md-3">
											  <label for="productname" class="form-label" style="font-weight: normal;">To<span class="text-danger"><strong>*</strong></span></label>
										    
										      <input type="date" class="form-control" id="enddate" name="enddate">
										    </div> 
										    <div class="form-group col-md-3">
												<label for="productname" class="form-label" style="font-weight: normal;">Account name<span class="text-danger"><strong>*</strong></span></label>
												<select class="selectpicker" name="employee" id="employee" data-live-search="true">
									      		<option value="" selected> - - Please Select - - </option>
												@foreach($banks as $bank)
												<option value="{{ $bank->account_head }}">
													{{ $bank->account_name }} - {{ $bank->bank_name }}
							            	    </option>
							        			@endforeach
									      	    </select>
										    </div>
										    <div style="padding-top: 32px;" class="form-group col-md-3">
										      <button type="submit" class="btn btn-primary">Generate</button>
										      <button type="reset" value="Reset" class="btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
										    </div>
										    
										  </div>
										  <small id="emailHelp" class="form-text text-muted">Account name : <strong><span id="account_name"></span></strong></small>
										  <small id="emailHelp" class="form-text text-muted">Bank Name : <strong><span id="bank_name"></span></strong></small>
										   <small id="emailHelp" class="form-text text-muted">Opening Balance : <strong><span id="opening_balance"></span></strong></small>
										   <small id="emailHelp" class="form-text text-muted">Closing Balance : <strong><span id="closing_balance"></span></strong></small>
										  
										  
										</form>

		                <div class="chart"  style="height: ; width: 100%;">
		                  <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
					  					<table id="product_table" class="display">
											  <thead>
											    <tr>
											    	<th scope="col">#</th>
													<th scope="col">Transaction Id</th>
												  <th scope="col">Transaction Ref</th>
												  <th scope="col">Transaction date</th>
											      <th scope="col">Debit</th>
											      <th scope="col">Credit</th>
											      <th scope="col">Balance</th>
											     
											    </tr>
											  </thead>
											  <tbody>
											    
											  </tbody>
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


<script type="text/javascript" src="js/bank-report.js"></script>
<script type="text/javascript">

</script>


@endsection


	
