@extends('layouts.master')
@section('title', 'Adjustment Voucher')

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
			                <h5 class="m-0"><strong><i class="fas fa-receipt"></i> ADJUSTMENT VOUCHER</strong></h5>
			            </div>

		              	<div class="card-body">
		              	<form id="AddJournalVoucherForm" method="POST" enctype="multipart/form-data">
		              		<div class="">

	          					<div class="form-group row">
								    <label for="transactionid" class="col-sm-2 col-form-label text-right" style="font-weight: normal;">Transaction ID<span class="text-danger"><strong>*</strong></span></label>
							    	<div class="col-sm-4">
								      <input class="form-control w-75" type="text" name="transactionid" id="transactionid" readonly>
								    </div>


							  	</div>

								<div class="form-group row">
								    <label for="transactiondate" class="col-sm-2 col-form-label text-right" style="font-weight: normal;">Date<span class="text-danger"><strong>*</strong></span></label>
							    	<div class="col-sm-4">
								      <input class="form-control w-75" type="date" name="transactiondate" id="transactiondate">
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
							    <div class="col-12" id="div1">
							   		<h5 class="text-left"><b>Adjustment Entry</b></h5>
							   		<div class="pt-2">
										<table id="journal_table" class="table table-bordered">
										    <thead>
										        <tr>
									        		<th width="30%">
									        			<select class="selectpicker" data-width="100%" data-live-search="true" aria-label="Default select example" name="accounthead"
													      id="accounthead">
                                                          <option value="option_select" disabled selected>Select Account</option>
                                                          <!-- <option disabled>--------------------</option> -->
                                                          <option disabled>-- Asset --</option>
                                                          <option disabled>---- Current Asset ----</option>
                                                          <option disabled>------ Account Receivable ------</option>
                                                          <option disabled>-------- Customer Receivable --------</option>
                                                          @foreach($customerReceivables as $customerReceivable)
                                                            <option value="{{ $customerReceivable->head_code  }}">---------- {{ $customerReceivable->head_name  }} ----------</option>
                                                        @endforeach
                                                        @foreach($AccountReceivables as $AccountReceivable)
                                                            <option value="{{ $AccountReceivable->head_code  }}">-------- {{ $AccountReceivable->head_name  }} --------</option>
                                                        @endforeach
														 <option disabled>-------- Distributor Receivable --------</option>
                                                          @foreach($distributorReceivables as $distributorReceivable)
                                                            <option value="{{ $distributorReceivable->head_code  }}">---------- {{ $distributorReceivable->head_name  }} ----------</option>
                                                        @endforeach
                                                          <option disabled>------ Cash & Cash Equivalent ------</option>
                                                          <option disabled>-------- Cash At Bank --------</option>
                                                          @foreach($cashAtBanks as $cashAtBank)
                                                            <option value="{{ $cashAtBank->head_code  }}">---------- {{ $cashAtBank->head_name  }} ----------</option>
                                                        @endforeach
                                                        @foreach($cashEquivalents as $cashEquivalent)
                                                            <option value="{{ $cashEquivalent->head_code  }}">-------- {{ $cashEquivalent->head_name  }} --------</option>
                                                        @endforeach
                                                        <option disabled>---- Non Current Asset ----</option>
                                                        @foreach($inventorys as $inventory)
                                                          <option value="{{ $inventory->head_code  }}">------ {{ $inventory->head_name  }} ------</option>
                                                        @endforeach
                                                        @foreach($tassets as $tasset)
                                                          <option value="{{ $tasset->head_code  }}">---- {{ $tasset->head_name  }} ----</option>
                                                        @endforeach
                                                        <!-- <option disabled>--------------------</option> -->
                                                        <option disabled>-- Equity --</option>
                                                        @foreach($equities as $equity)
                                                            <option value="{{ $equity->head_code  }}">---- {{ $equity->head_name  }} ----</option>
                                                        @endforeach
                                                        <!-- <option disabled>--------------------</option> -->
                                                        <option disabled>-- Expense --</option>
                                                        @foreach($expenseAccounts as $expenseAccount)
                                                            <option value="{{ $expenseAccount->head_code  }}">---- {{ $expenseAccount->head_name  }} ----</option>
                                                        @endforeach

														<option disabled>-- Operating Expense --</option>
														@foreach($operatingExpenses as $operatingExpense)
                                                            <option value="{{ $operatingExpense->head_code  }}">---- {{ $operatingExpense->head_name  }} ----</option>
                                                        @endforeach
                                                        <!-- <option disabled>--------------------</option> -->
                                                        <option disabled>-- Income --</option>
                                                        @foreach($incomes as $income)
                                                            <option value="{{ $income->head_code  }}">---- {{ $income->head_name  }} ----</option>
                                                        @endforeach
                                                        <!-- <option disabled>--------------------</option> -->
                                                        <option disabled>-- Liabilities --</option>
                                                        <option disabled>---- Current Liabilities ----</option>
                                                        <option disabled>------ Account Payable ------</option>
                                                        @foreach($accountPayables as $accountPayable)
                                                            <option value="{{ $accountPayable->head_code  }}">-------- {{ $accountPayable->head_name  }} --------</option>
                                                        @endforeach
                                                        @foreach($currentLiabilities as $currentLiabilitie)
                                                            <option value="{{ $currentLiabilitie->head_code  }}">------ {{ $currentLiabilitie->head_name  }} ------</option>
                                                        @endforeach
                                                        @foreach($nonCurrentLiabilities as $nonCurrentLiabilitie)
                                                            <option value="{{ $nonCurrentLiabilitie->head_code  }}">---- {{ $nonCurrentLiabilitie->head_name  }} ----</option>
                                                        @endforeach

                                                    </select>
													</th>
									        		<th width="20%"><input class="form-control" type="text" name="accountheadcode" id="accountheadcode" readonly></th>
										            <th width="25%"><input class="form-control" type="number" step="any" min="0.1" name="debitamount" id="debitamount" placeholder="Debit amount" value=""></th>
										            <th width="25%"><input class="form-control" type="number" step="any" min="0.1" name="creditamount" id="creditamount" placeholder="Credit amount" value=""></th>
										            <th width="4%">
										            	<button type="button" onclick="addX();" id="addDebit" class="ml-2 btn btn-outline-success float-right">
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
						    		<button type="button" onclick="processData();" id="formsubmit" class="ml-2 btn btn-info float-right">
								  		<i class="fas fa-plus"></i> Add Voucher
								  	</button>
								  	<button type="reset" value="Reset" class="btn btn-outline-danger float-right" onclick="resetButton();"><i class="fas fa-eraser"></i> Reset</button>
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
<script type="text/javascript" src="js/journal-voucher.js"></script>

<script type="text/javascript">




</script>
@endsection
