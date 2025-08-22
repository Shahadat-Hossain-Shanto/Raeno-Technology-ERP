@extends('layouts.master')
@section('title', 'Income Statement')

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
	            
		          	<div class="card card-primary">
		              <div class="card-header">
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> INCOME STATEMENT</strong></h5>
		              </div>
		              <div class="card-body" id="">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->

		                <button type="button" class="btn btn-light" onclick="print();">Print</button>

	                	<input type="hidden" name="" id="subscriberid" value="{{auth()->user()->subscriber_id}}">
	                    <div class="pt-3" id="income_statement">
							<table id="trial_balance_table_sales" class="table table-bordered table-sm mb-0">
								 <!-- <caption></caption> -->
							    <thead id="sales_thead" class="table-primary">
							       
							    </thead>
							    <tbody id="sales_tbody">
							    	
							    	
						    		
							    </tbody>
							    <tfoot id="sales_tfoot">
							    	
							    </tfoot>
						    </table>
						    <table id="trial_balance_table_income" class="table table-bordered table-sm mt-0 mb-0">
								 <!-- <caption></caption> -->
							    <thead id="expense_thead" class="table-primary">
							       
							    </thead>
							    <tbody id="expense_tbody">
						    		


							    </tbody>
							    <tfoot id="expense_tfoot">
							    	
							    </tfoot>
						    </table>
						    <table id="trial_balance_table_total" class="table table-bordered table-sm mt-0">
								 
							    <tbody id="total_tbody">
							    	
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


@endsection

@section('script')
<script type="text/javascript" src="js/income-statement.js"></script>
<script type="text/javascript">

	
</script>

@endsection


	
