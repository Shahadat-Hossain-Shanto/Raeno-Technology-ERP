@extends('layouts.master')
@section('title', 'Purchase Voucher')

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
		<div class="container-fluid ">
			<div class="row">
      			<div class="col-lg-12">
          			<div class="card card-primary">
			            <div class="card-header">
			                <h5 class="m-0"><strong><i class="fas fa-receipt"></i> PURCHASE VOUCHER</strong></h5>
			            </div>

		              	<div class="card-body">
		              	<form id="AddPurchaseVoucherForm" method="POST" enctype="multipart/form-data">

	          				<div class="">

	          					<div class="form-group row">
								    <label for="transactionid" class="col-sm-2 col-form-label text-right" style="font-weight: normal;">Transaction ID<span class="text-danger"><strong>*</strong></span></label>
							    	<div class="col-sm-4">
								      <input class="form-control w-75" type="text" name="transactionid" id="transactionid" readonly>
								    </div>

							     	<label for="ponumber" class="col-sm-1 col-form-label text-right" style="font-weight: normal;">P. O. Number<span class="text-danger"><strong></strong></span></label>
							    	<div class="col-sm-4">
								      <input class="form-control w-75" type="text" name="ponumber" id="ponumber">
								    </div>
							  	</div>

								<div class="form-group row">
								    <label for="transactiondate" class="col-sm-2 col-form-label text-right" style="font-weight: normal;">Date<span class="text-danger"><strong>*</strong></span></label>
							    	<div class="col-sm-4">
								      <input class="form-control w-75" type="date" name="transactiondate" id="transactiondate">
								    </div>

								    <label for="supplier" class="col-sm-1 col-form-label text-right" style="font-weight: normal;">Supplier<span class="text-danger"><strong></strong></span></label>
							    	<div class="col-sm-4">
								      <select class="selectpicker" data-width="75%" data-live-search="true" aria-label="Default select example" name="supplier"
								      id="supplier">
								      	<option value="option_select" disabled selected>Select Supplier</option>
								      	@foreach($suppliers as $supplier)
						            	<option value="{{ $supplier->id  }}">{{ $supplier->name }}</option>
						        		@endforeach
								      </select>
								    </div>
							  	</div>

							  	<div class="form-group row">
								    <label for="referencenote" class="col-sm-2 col-form-label text-right" style="font-weight: normal;">Reference Note<span class="text-danger"><strong></strong></span></label>
							    	<div class="col-sm-4">
								      <textarea class="form-control w-75" name="referencenote" id="referencenote"  rows="2" placeholder="if any notes"></textarea>
								    </div>
							  	</div>

							</div>
									
								  	<div class="row pt-3">
									   <div class="col-6" id="div1">
									   		<h5 class="text-left"><b>Debit Head</b></h5>
									   		<div class="pt-2">
												<table id="debit_table" class="table table-bordered">
												    <thead>
												        <tr>
											        		<th width="36%">
											        			<select class="selectpicker" data-width="100%" data-live-search="true" aria-label="Default select example" name="debitheadname"
															      id="debitheadname">
															      	<option value="option_select" disabled selected>Select Debit Head</option>
															      	@foreach($expenseAccounts as $expenseAccount)
													            	<option value="{{ $expenseAccount->head_code  }}">{{ $expenseAccount->head_name  }}</option>
													        		@endforeach
															    </select>
															</th>
											        		<th width="30%"><input class="form-control" type="text" name="debitheadcode" id="debitheadcode" readonly></th>
												            <th width="30%"><input class="form-control" type="number" name="debitamount" id="debitamount" placeholder="Debit amount"></th>
												            <th width="4%">
												            	<button type="button" onclick="addDebitX();" id="addDebit" class="ml-2 btn btn-outline-success float-right">
															  		<i class="fas fa-plus"></i>
															  	</button>
															</th>
												        </tr>
												    </thead>
												    <tbody>

												    </tbody>
												    <tfoot>
													    
												  	</tfoot>
											    </table>
											</div>
									   </div>

									   <div class="col-6" id="div2">
									   		<h5 class="text-left"><b>Credit Head</b></h5>
									   		<div class="pt-2">
												<table id="credit_table" class="table table-bordered">
												    <thead>
												        <tr>
											        		<th width="36%">
											        			<select class="selectpicker" data-width="100%" data-live-search="true" aria-label="Default select example" name="creditheadname"
															      id="creditheadname">
															      	<option value="option_select" disabled selected>Select Credit Head</option>
															      	@foreach($datas as $data)
													            	<option value="{{ $data->head_code  }}">{{ $data->head_name  }}</option>
													        		@endforeach
															    </select>
															</th>
											        		<th width="30%"><input class="form-control" type="text" name="creditheadcode" id="creditheadcode" readonly></th>
												            <th width="30%"><input class="form-control" type="number" name="creditamount" id="creditamount" placeholder="Credit amount"></th>
												            <th width="4%">
												            	<button type="button" onclick="addCreditX();" id="addCredit" class="ml-2 btn btn-outline-success float-right">
															  		<i class="fas fa-plus"></i>
															  	</button>
												            </th>
												        </tr>
												    </thead>
												    <tbody>

												    </tbody>
												    <tfoot>
													    
												  	</tfoot>
											    </table>
											</div>
									   </div>
									</div>
									<div class="row pt-4">
								    	<div class="col-12">
								    		<button type="button" onclick="processData()" id="formsubmit" class="ml-2 btn btn-info float-right">
										  		<i class="fas fa-plus"></i> Add Voucher  
										  	</button>
										  	<button type="reset" value="Reset" class="btn btn-outline-danger float-right" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
										  	<h6 class="text-danger float-right mr-5" ><strong id="errorMsgCredit"></strong></h6>
									    </div>
								      
					    			</div>
									
								  	
						</form>

							<!-- </div> container -->
						</div> <!-- card-body -->
		          	</div> <!-- card card-primary card-outline -->
      			</div> <!-- col-lg-5 -->
      		</div> <!-- row -->
		</div> <!-- container-fluid -->
	</div> <!-- content -->
	
</div> <!-- content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="js/purchase-voucher.js"></script>

<script type="text/javascript">

	

	
</script>
@endsection