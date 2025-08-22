@extends('layouts.master')
@section('title', 'Opening Balance')

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
			                <h5 class="m-0"><strong><i class="fas fa-receipt"></i> OPENING BALANCE</strong></h5>
			            </div>

		              	<div class="card-body">
		              	<form id="AddOpeningBalanceForm" method="POST" enctype="multipart/form-data">
		              		<div class="">

	          					<div class="form-group row">
									<div class="col-6">
										<label for="transactionid" class="col-form-label text-right" style="font-weight: normal;">Transaction ID<span class="text-danger"><strong>*</strong></span></label>
							            <input class="form-control" type="text" name="transactionid" id="transactionid" readonly>
									</div>
								  
			
                                    <div class="col-6">
										<label for="transactiondate" class="col-form-label text-right" style="font-weight: normal;">Date<span class="text-danger"><strong>*</strong></span></label>
										<input class="form-control" type="date" name="transactiondate" id="transactiondate">
									</div>
							    	

							  	</div>

								<div class="row">
									<div class="col-6">
										<div class="form-group row">
											<label for="accountX" class="col-4 col-form-label" style="font-weight: normal;">Select Account<span class="text-danger"><strong>*</strong></span></label>								  
											<select class="selectpicker col-8" data-width="70%" data-live-search="true" aria-label="Default select example" name="accountX" id="accountX">
												<option value="option_select" disabled selected>Select Account</option>
									      	    <!-- <option disabled>--------------------</option> -->
									      	    <option disabled>-- Asset --</option>
									      	    <option disabled>---- Current Asset ----</option>
									      	    <option disabled>------ Account Receivable ------</option>
											    
                                                @foreach($AccountReceivables as $AccountReceivable)
									                <option value="{{ $AccountReceivable->head_code  }}">-------- {{ $AccountReceivable->head_name  }} --------</option>
									            @endforeach
                                                @if ($distributorReceivables->isNotEmpty())
											    <option disabled>---- {{ $distributorReceivables->first()->parent_head }} ----</option>												
											    @endif
											    @foreach ($distributorReceivables as $distributorReceivable)
											    <option value="{{ $distributorReceivable->head_code }}">{{ $distributorReceivable->head_name }}</option>	
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
    
											    @if ($operatingExpenses->isNotEmpty())
											    <option disabled>--- {{ $operatingExpenses->first()->parent_head  }} ---</option>										
											    @endif
											    @foreach ($operatingExpenses as $operatingExpense )
											    <option value="{{ $operatingExpense->head_code }}">{{ $operatingExpense->head_name }}</option>
											    	
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
									    </div>
									</div>
                                   
								    
                                    <div class="col-6">
										<div class="form-group row">
											<label for="amountX" class="col-4 col-form-label" style="font-weight: normal;">Amount<span class="text-danger"><strong>*</strong></span></label>
								            <input class="form-control col-8 " type="number" step="any" min="0.1" name="amountX" id="amountX" placeholder="Opening amount" value="">
										</div>

									</div>
									

							  	</div>

							  	<div class="form-group row">
							  		<label for="transactiontypeX" class="col-sm-2 col-form-label" style="font-weight: normal;">Transaction Type<span class="text-danger"><strong>*</strong></span></label>
							  		<div class="col-sm-4">
						  			 	<select class="selectpicker" aria-label="Default select example" name="transactiontypeX" id="transactiontypeX">
						  			 		<option value="option_select" selected disabled>Select Transaction Type</option>
						  			 		<option value="debit">Debit</option>
						  			 		<option value="credit">Credit</option>
						  			 	</select>
							  		</div>

								    <label for="referencenote" class="col-sm-2 col-form-label" style="font-weight: normal;">Reference Note<span class="text-danger"><strong></strong></span></label>
							    	<div class="col-sm-4">
								      <textarea class="form-control" name="referencenote" id="referencenote"  rows="2" placeholder="if any notes"></textarea>
								    </div>
							  	</div>

							</div>


							<div class="row pt-3">
							    <div class="col-12" id="div1">
							   		<h5 class="text-left"><b id="tableHeader"></b></h5>
							   		<div class="pt-2">
										<table id="opening_table" class="table table-bordered">
										    <thead>
										        <tr>
									        		<th width="30%">
									        			<select class="selectpicker" data-width="100%" data-live-search="true" aria-label="Default select example" name="accountY"
													      id="accountY">
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
									        		<th width="20%"><input class="form-control" type="text" name="transactiontypeY" id="transactiontypeY" readonly></th>
										            <th width="20%"><input class="form-control" type="number" step="any" min="0.1" name="amountY" id="amountY" placeholder="Debit amount" value=""></th>

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
<script type="text/javascript" src="js/opening-balance.js"></script>

<script type="text/javascript">




</script>
@endsection
