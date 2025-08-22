@extends('layouts.master')
@section('title', 'Expense Voucher')

@section('content')
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<!-- Header -->
			</div>
		</div>
	</div>

	<div class="content">
		<div class="container-fluid">
			<div class="row">
      			<div class="col-lg-12">
          			<div class="card card-primary">
			            <div class="card-header">
			                <h5 class="m-0"><strong><i class="fas fa-receipt"></i> EXPENSE VOUCHER</strong></h5>
			            </div>

		              	<div class="card-body">
		              	<form id="AddPurchaseVoucherForm" method="POST" enctype="multipart/form-data">

	          				<div class="row">
	          					<div class="col-md-6">
	          						<div class="form-group">
									    <label for="transactionid">Transaction ID <span class="text-danger">*</span></label>
									    <input class="form-control" type="text" name="transactionid" id="transactionid" readonly>
								  	</div>
								</div>

								<div class="col-md-6">
	          						<div class="form-group">
									    <label for="transactiondate">Date <span class="text-danger">*</span></label>
									    <input class="form-control" type="date" name="transactiondate" id="transactiondate">
								  	</div>
								</div>
							</div>

                            <div class="row">
                        		<div class="col-md-4">
                        			<div class="form-group">
                        				<label for="debitheadname">Debit Head <span class="text-danger">*</span></label>
                        				<select class="form-control selectpicker" data-live-search="true" name="debitheadname" id="debitheadname">
                        					<option disabled selected>Select Debit Head</option>
                        					@foreach($expenseAccounts as $expenseAccount)
                        						<option value="{{ $expenseAccount->head_code }}">{{ $expenseAccount->head_name }}</option>
                        					@endforeach
                        				</select>
                        			</div>
                        		</div>
                        
                        		<div class="col-md-4">
                        			<div class="form-group">
                        				<label for="debitheadcode">Head Code</label>
                        				<input type="text" class="form-control" name="debitheadcode" id="debitheadcode" readonly>
                        			</div>
                        		</div>
                        
                        		<div class="col-md-4">
                        			<div class="form-group">
                        				<label for="debitamount">Amount <span class="text-danger">*</span></label>
                        				<input type="number" name="debitamount" id="debitamount" class="form-control" placeholder="Enter amount">
                        			</div>
                        		</div>
                        	</div>
                        
                        	<div class="row mt-3">
                        		<div class="col-md-6">
                        			<div class="form-group">
                        				<label for="creditheadname">Credit Head <span class="text-danger">*</span></label>
                        				<select class="form-control selectpicker" data-live-search="true" name="creditheadname" id="creditheadname">
                        					<option disabled selected>Select Credit Head</option>
                        					@foreach($assetAccounts as $assetAccount)
                        						<option value="{{ $assetAccount->head_code }}">{{ $assetAccount->head_name }}</option>
                        					@endforeach
                        				</select>
                        			</div>
                        		</div>
                        
                        		<div class="col-md-6">
                        			<div class="form-group">
                        				<label for="creditheadcode">Head Code</label>
                        				<input type="text" class="form-control" name="creditheadcode" id="creditheadcode" readonly>
                        			</div>
                        		</div>
                        	</div>
                        
                        	<div class="form-group">
                        		<label for="referencenote">Reference Note</label>
                        		<textarea class="form-control" name="referencenote" id="referencenote" rows="2" placeholder="If any notes..."></textarea>
                        	</div>
							

							<div class="pt-4 text-right">
					    		<button type="button" onclick="processData()" class="btn btn-info">
							  		<i class="fas fa-plus"></i> Add Voucher  
							  	</button>
							  	<button type="reset" class="btn btn-outline-danger ml-2" onclick="resetButton()">
							  		<i class="fas fa-eraser"></i> Reset
							  	</button>
							  	<h6 class="text-danger mt-2" id="errorMsgCredit"></h6>
						    </div>

						</form>
						</div>
		          	</div>
      			</div>
      		</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script src="js/expense-voucher.js"></script>
@endsection
