@extends('layouts.master')
@section('title', 'Create Sales Return')

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
      			<div class="col-lg-4">
          			<div class="card card-primary">
			            <div class="card-header">
			                <h5 class="m-0"><strong><i class="fas fa-sync-alt"></i> SALES RETURN</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">
	          					<form id="InvoiceNoForm" method="GET">
	          						<div class="row g-3 align-items-center">
		          						<div class="col-auto">
	          								<label for="search" class="col-form-label fw-normal">Invoice No.<span class="text-danger"><strong>*</strong></span> </label>
										</div>
	          							<div class="input-group col-8">
                                            {{-- <input type="text" class="form-control" name="search" id="search"> --}}
                                            <select class="selectpicker" data-width="75%" data-live-search="true" aria-label="Default select example" name="search" id="search">
										      	<option value="option_select" disabled selected>Select Invoice Number</option>
									      		@foreach($orders as $order)
										            <option value="{{ $order->orderId }}">{{ $order->orderId }}</option>
										        @endforeach
										    </select>
											<div class="input-group-append">
												<!-- <span class="input-group-text" id="search_btn" style=""><i class="fas fa-search"></i></span> -->
												<button class="input-group-text btn btn-light" id="search_btn" type="submit"><i class="fas fa-search"></i></button>
											</div>
										</div>
		          					</div>
		          					<h6 class="text-danger" id="wrongsearch" style="font-size: 15px; margin-left: 98px; margin-top: 5px;"></h6>

	          					</form>
	          					<div class="row">
									<div class="col-12">

										<div id="freeItemTableDiv" class="pt-5 hidden">
											<h6>Free item list:</h6>
											<table id="free_return_table" class="table table-bordered">
											    <thead>
											        <tr>
											        	<th width="38%">Free Product Name</th>
											            <th width="15%">Required </th>
											            <th width="12%">Free</th>
											            <th width="35%">On Product</th>
											            <th class="hidden">Offer Item Id</th>

											        </tr>
											    </thead>
											    <tbody id="free_return_table_body">

											    </tbody>
										    </table>
										</div>
									</div>
								</div>

							</div> <!-- container -->
						</div> <!-- card-body -->
		          	</div> <!-- card card-primary card-outline -->
      			</div> <!-- col-lg-5 -->
      		</div>
      		<div class="row">
      			<div class="col-lg-12">
          			<div class="card card-info">
			            <div class="card-header">
			                <h5 class="m-0"><strong> RETURN DETAILS</strong></h5>
			            </div>

		              	<div class="card-body">
	          				<!-- <div class="container"> -->
	          					<div class="row ">
	          						<div class="col-3">
		          						<div class="form-group">
		      								<p class="mb-0 hidden">Customer Id: <span id='customerId'></span></p>
		      								<p class="mb-0 hidden">Store Id: <span id='storeId'></span></p>
		      								<p class="mb-0">Customer Name: <span id='customerName'></span></p>
                                            <p class="mb-0">Customer Mobile: <span id='customerMobile'></span></p>
                                            <p class="mb-0">Order Date: <span id='orderDate'></span></p>
                                            <p class="mb-0">Total Price: <span id='totalPrice'></span></p>
                                            <p class="mb-0">Discount: <span id='totalDiscount'></span></p>
                                            <p class="mb-0">Special Discount: <span id='specialDiscount'></span></p>
                                            <p class="mb-0">Total Tax: <span id='totalTax'></span></p>
                                            <p class="mb-0">Grand Total: <span id='grandTotal'></span></p>
			          					</div>
		          					</div>
		          					{{-- <div class="col-3">
		          						<div class="form-group">
		      								<label for="date" class="col-form-label fw-normal">Date</label>
											<input type="text" class="form-control w-75" name="date" id="date" disabled>
			          					</div>
		          					</div> --}}
	          					</div>
	          					<div class="pt-3">
									<table id="return_table" class="table table-bordered">
									    <thead>
									        <tr>
									        	<th width="25%">Product Name</th>
									            <th width="10%">Sold Qty</th>
									            <th width="11%">Return Qty</th>
									            <th width="10%">Price(Unit)</th>
									            <th width="11%">Deduction %</th>
									            <th width="10%">Total</th>
									            <th class="hidden">Action</th>
									            <th class="hidden">Deduction Amount</th>
									            <th width="10%">Discount(Unit)</th>
									            <th width="10%">Tax(Unit)</th>
									            <th class="hidden">Total Tax Return</th>
									            <th class="hidden">ProductId</th>
									            <th class="hidden">variantId</th>
									        </tr>
									    </thead>
									    <tbody id="return_table_body">

									    </tbody>
								    </table>
								</div>

								<div class="row">
									<div class="col-4">
										<div class="form-group">
										    <label for="note">Note</label>
										    <textarea class="form-control" id="note" name="note" rows="1" placeholder="Any notes"></textarea>
									    </div>
									</div>
									<div class="col-2">
										<div class="form-group">
										    <label for="totaldeduction">Total Deduction</label>
										    <input type="number" class="form-control" name="totaldeduction" id="totaldeduction" disabled>
									    </div>
									</div>
									<div class="col-2">
										<div class="form-group">
										    <label for="totaltax">Total Tax</label>
										    <input type="number" class="form-control" name="totaltax" id="totaltax" disabled>
									    </div>
									</div>
									<div class="col-2">
										<div class="form-group">
										    <label for="discountdeduction">Discount Deduction</label>
										    <input type="number" class="form-control" name="discountdeduction" id="discountdeduction" disabled>
									    </div>
									</div>
									<div class="col-2">
										<div class="form-group">
										    <label for="netreturn">Net Return</label>
										    <input type="number" class="form-control" name="netreturn" id="netreturn" disabled>
									    </div>
									</div>
								</div>
								<div class="row">
									<div class="col-12">
										<h6 class="text-danger float-right"><span id="errMsg"></span></h6>
									</div>
								</div>
								<div class="row pt-3">
									<div class="col-12">
										<button class="btn btn-info float-right" type="button" id="return_btn" onclick="collectingData()"><i class="fas fa-reply-all"></i> Return</button>
									</div>
								</div>


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
<script type="text/javascript" src="js/sales-return.js"></script>
<script type="text/javascript">

</script>

@endsection
