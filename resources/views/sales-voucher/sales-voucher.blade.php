@extends('layouts.master')
@section('title', 'Sales Voucher')

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
          			<div class="card shadow rounded-xl">
			            <div class="card-header bg-primary text-white">
			                <h5 class="m-0 font-weight-bold"><i class="fas fa-receipt"></i> SALES VOUCHER</h5>
			            </div>

		              	<div class="card-body">
		              	<form id="AddPurchaseVoucherForm" method="POST" enctype="multipart/form-data">
	          				<div class="row">
	          					<div class="col-md-6">
	        						<div class="form-group">
										<label for="transactionid">Transaction ID <span class="text-danger">*</span></label>
										<input type="text" name="transactionid" id="transactionid" class="form-control" readonly>
									</div>
	        					</div>
	        					<div class="col-md-6">
	        						<div class="form-group">
										<label for="transactiondate">Date <span class="text-danger">*</span></label>
										<input type="date" name="transactiondate" id="transactiondate" class="form-control">
									</div>
	        					</div>
	          				</div>

							<div class="form-group row">
                            	<label for="requisitionId" class="col-sm-2 col-form-label">Order ID <span class="text-danger">*</span></label>
                            	<div class="col-sm-6">
                            		<select name="requisitionId" id="requisitionId" class="form-control selectpicker" data-live-search="true">
                            			<option selected disabled>Select Order ID</option>
                            			@foreach ($requisitions as $requisition)
                            				<option value="{{ $requisition->requisition_id }}">{{ $requisition->requisition_id }}</option>
                            			@endforeach
                            		</select>
                            	</div>
                            	<div class="col-sm-2">
                            		<input type="text" class="form-control" name="head_name" id="head_name" readonly placeholder="">
                            	</div>
                            	<div class="col-sm-2">
                            		<input type="text" class="form-control" name="head_code" id="head_code" readonly placeholder="">
                            	</div>
                            </div>


{{-- 
							<div class="col-6">
                            	<h5><b>Credit Head</b></h5>
                            	<table id="credit_table" class="table table-bordered mt-2">
                            		<thead>
                            			<tr>
                            				<th width="36%">
                            					<select class="form-control selectpicker" data-live-search="true" name="creditheadname" id="creditheadname">
                            						<option disabled selected>Select Credit Head</option>
                            						@foreach($datas as $data)
                            							<option value="{{ $data->head_code }}">{{ $data->head_name }}</option>
                            						@endforeach
                            					</select>
                            				</th>
                            				<th width="30%"><input class="form-control" type="text" name="creditheadcode" id="creditheadcode" readonly></th>
                            				<th width="30%"><input class="form-control" type="number" name="creditamount" id="creditamount" placeholder="Credit amount"></th>
                            				<th width="4%">
                            					<button type="button" onclick="addCreditX();" class="btn btn-outline-success">
                            						<i class="fas fa-plus"></i>
                            					</button>
                            				</th>
                            			</tr>
                            		</thead>
                            		<tbody></tbody>
                            	</table>
                            </div> --}}

							<div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="creditamount">Sales Amount <span class="text-danger">*</span></label>
                                        <input type="number" name="creditamount" id="creditamount" class="form-control" placeholder="Enter amount" readonly>
                                    </div>
                                </div>
                            
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="creditheadname">Sales Type <span class="text-danger">*</span></label>
                                        <select name="creditheadname" id="creditheadname" class="form-control selectpicker" data-live-search="true">
                                            <option value="" selected disabled>Select Sales Head</option>
                                            @foreach($datas as $data)
                                                <option value="{{ $data->head_code }}">{{ $data->head_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="invisible">Head Code</label>
                                        <input type="text" name="creditheadcode" id="creditheadcode" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            

							<div class="form-group ">
								<label for="referencenote" class="col-sm-2 col-form-label">Reference Note</label>
								<div class="col-sm-10">
									<textarea class="form-control" name="referencenote" id="referencenote" rows="2" placeholder="If any notes..."></textarea>
								</div>
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
						</div> <!-- card-body -->
					</div> <!-- card -->
      			</div> <!-- col-lg-12 -->
      		</div> <!-- row -->
		</div> <!-- container-fluid -->
	</div> <!-- content -->
</div> <!-- content-wrapper -->
@endsection

@section('script')
<script type="text/javascript" src="js/sales-voucher.js"></script>
@endsection
