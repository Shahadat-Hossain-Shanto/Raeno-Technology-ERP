@extends('layouts.master')
@section('title', 'Balance Sheet')

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
		                	<h5 class="m-0"><strong><i class="fas fa-clipboard-list"></i> BALANCE SHEET</strong></h5>
		              </div>
		              <div class="card-body" id="">
		                <!-- <h6 class="card-title">Special title treatment</h6> -->
		                <!-- Table -->

		                <!-- <button type="button" class="btn btn-light" onclick="print();">Print</button> -->

	                	<input type="hidden" name="" id="subscriberid" value="{{auth()->user()->subscriber_id}}">
	                    <div class="pt-3" id="balance_sheet">
												<table id="balance_sheet_table_asset" class="table table-bordered table-sm mb-0">
												 <!-- <caption></caption> -->
											    <thead id="asset_thead" class="table-primary">
											       
											    </thead>
											    <tbody id="asset_tbody">
											    	
											    	
										    		
											    </tbody>
											    <tfoot id="asset_tfoot">
											    	
											    </tfoot>
										    </table>
										    <table id="balance_sheet_table_liabilities" class="table table-bordered table-sm mt-0 mb-0">
												 <!-- <caption></caption> -->
											    <thead id="liabilities_thead" class="table-primary">
											       
											    </thead>
											    <tbody id="liabilities_tbody">
										    		


											    </tbody>
											    <tfoot id="liabilities_tfoot">
											    	
											    </tfoot>
										    </table>
										    <table id="balance_sheet_table_equity" class="table table-bordered table-sm mb-0 mt-0">
												 
											    <thead id="equity_thead" class="table-primary">
											       
											    </thead>
											    <tbody id="equity_tbody">
										    		


											    </tbody>
											    <tfoot id="equity_tfoot">
											    	
											    </tfoot>
										    </table>
										    <table id="balance_sheet_table_income" class="table table-bordered table-sm mb-0 mt-0">
												 
											    <thead id="income_thead" class="table-primary">
											       
											    </thead>
											    <tbody id="income_tbody">
										    		


											    </tbody>
											    <tfoot id="income_tfoot">
											    	
											    </tfoot>
										    </table>
										    <table id="balance_sheet_table_total" class="table table-bordered table-sm mb-0 mt-0">
								 
											    <thead id="total_owners_liabilities_tbody">
											    	
											    </thead>
										    </table>
										    <table id="balance_sheet_table_total_final" class="table table-bordered table-sm mt-0">
								 
											    <thead id="total_final_tbody">
											    	
											    </thead>
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
<script type="text/javascript" src="js/balance-sheet.js"></script>
<script type="text/javascript">

	
</script>

@endsection


	
