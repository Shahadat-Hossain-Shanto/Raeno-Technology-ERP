@extends('layouts.master')
@section('title', 'POS')

<style type="text/css">
.wordWrap {
    word-wrap: break-word !important;      /* IE 5.5-7 */
    white-space: -moz-pre-wrap !important; /* Firefox 1.0-2.0 */
    white-space: pre-wrap !important;      /* current browsers */
	padding : 0.25rem !important;
}

</style>

@section('content')
<div class="content-wrapper">
	{{-- <div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-4">
					<h2><span class="badge badge-success">POS: {{ session()->get('posName') }} </span></h2>
				</div>
				<div class="col-8">
					<button class="float-right btn btn-danger" type="button" id="poslogout">POS Logout</button>
					<button id="hold_list" class="notification float-right btn btn btn-primary mr-2" type="button" data-toggle="" data-target="#" aria-expanded="" aria-controls="">
					    <span>Hold List </span><span class="badge badge-light" id="totalHolds">0</span>
					</button>
					<input type="hidden" name="referenceid" id="referenceid" disabled>
				</div>
			</div>
		</div>
	</div> --}}

	<div class="content pt-2 ">
		<div class="container-fluid ">
			<div class="row">
      			<div class="col-lg-5 col-md-6">
          			<div class="card card-primary">
			            <div class="card-header">
                            <div class="row">
				                <div class="col-4">
			                        <h5 class="btn btn-primary m-0"><strong><i class="fas fa-shopping-cart" style="color: ;"></i> CART</strong></h5>
				                </div>
				                <div class="col-8">
					                <button class="float-right btn btn-danger" type="button" id="poslogout">POS Logout</button>
					                <button id="hold_list" class="notification float-right btn btn btn-warning mr-2" type="button" data-toggle="" data-target="#" aria-expanded="" aria-controls="">
					                    <span>Hold List </span><span class="badge badge-warning" id="totalHolds">0</span>
					                </button>
					                <input type="hidden" name="referenceid" id="referenceid" disabled>
				                </div>
			                </div>
			                {{-- <h5 class="m-0"><strong><i class="fas fa-shopping-cart" style="color: ;"></i> CART</strong></h5> --}}
			            </div>

			            <input type="hidden" name="" id="subscriberid" value="{{auth()->user()->subscriber_id}}">
			    		<input type="hidden" name="" id="salesby" value="{{auth()->user()->email}}">
			    		<input type="hidden" name="" id="userid" value="{{auth()->user()->id}}">
						<input type="hidden" name="" id="username" value="{{auth()->user()->name}}">
			    		<input type="hidden" name="" id="storeid" value="{{ session()->get('storeId') }}">
			    		<input type="hidden" name="" id="storename" value="{{ session()->get('storeName') }}">
			    		<input type="hidden" name="" id="posid" value="{{ session()->get('posId') }}">
			    		<input type="hidden" name="" id="orgname" value="{{ session()->get('orgName') }}">
			    		<input type="hidden" name="" id="storeaddress" value="{{ session()->get('storeAddress') }}">
		    			<input type="hidden" name="" id="binnumber" value="{{ session()->get('binNumber') }}">


		              	<div class="card-body" style="height: auto; width: 100%;">
	          				<div class="container">
	          					<div class="row">
	          						<div class="col-4">
	          							<!-- <div class="" role="" aria-label="Basic outlined example"> -->
		          							<button style="font-size: small;padding: unset;margin-bottom: 6;" id="addcustomer" type="button" class="w-100 btn btn-outline-primary"><i class="fas fa-user-plus"></i> Add Customer</button>
		          							<!-- <button id="quicksell" type="button" class=" w-40 btn btn-outline-primary"><i class="fas fa-bolt"></i> Quick Sell</button> -->
		          						<!-- </div> -->
	          						{{-- </div>
	          						<div class="col-3"> --}}
	          							<!-- <div class="" role="" aria-label="Basic outlined example"> -->
		          							<!-- <button id="addcustomer" type="button" class="w-50 btn btn-outline-primary pe-3"><i class="fas fa-user-plus"></i> Add Customer</button> -->
		          							<button style="font-size: small;padding: unset;" id="quicksell" type="button" class="w-100 btn btn-outline-primary"><i class="fas fa-bolt"></i> Quick Sell</button>
		          						<!-- </div> -->
	          						</div>

	          					{{-- </div>

	          					<hr>
	          					<div class="row pt-2"> --}}
	          						<div style="" class="pe-5 col-8">
	          							<div>
	          								<input type="hidden" name="customerid" id="customeridhidden">
								    		<h6> <strong>Customer:</strong> <span id="customername"></span></h6>
								    		<h6> <strong>Contact No:</strong> <span id="customercontact"></span></h6>
								    	</div>
	          						</div>
	          					</div>



	          					<div class="row pt-2">
	          						<div class="table-responsive">
	          							<div class="table-responsive">
		          							<table id="product_table" class="table" width="100%">
											    <thead class="">
											        <tr>
											            <th width="25%">Product</th>
											            <th width="20%">Price</th>
											            <th width="30%">Count</th>
											            <th width="25%">Subtotal</th>
											            <th style="display:none;">Discount</th>
											            <th style="display:none;">Tax</th>
											            <th style="display:none;">FixDiscount</th>
											            <th style="display:none;">FixTax</th>
											            <th style="display:none;">productId</th>
											            <th></th>
											        </tr>
											    </thead>
											    <tbody id="product_table_body">

											    </tbody>
										    </table>
										</div>
	          						</div>

	          						<div class="table-responsive">
	          							<table id="free_product_table" class="table hidden">
										    <thead class="">
										        <tr>
										            <th>Free Item</th>
										            <th>Quantity</th>
										            <th>On Product</th>
										            <th></th>

										        </tr>
										    </thead>
										    <tbody id="free_product_table_body">

										    </tbody>
									    </table>
	          						</div>


								    <div style="position: relative; bottom: 0" class="">

								    	<div class="pt-5">
								    		<h6> <strong>Total:</strong> <span id="total">0</span></h6>
								    		<h6> <strong>Total Discount:</strong> <span id="discount">0</span></h6>
								    		<h6> <strong>Tax/Vat:</strong> <span id="vatS">0</span></h6>
                                            <h6> <strong>Special Discount:</strong> <span id="discountS"><input type="number" style="
                                                height: calc(2rem + 0.7px);
                                                padding: 0.375rem 0.75rem;
                                                font-size: 1rem;
                                                font-weight: 400;
                                                line-height: 1.5;
                                                color: #495057;
                                                background-color: #fff;
                                                background-clip: padding-box;
                                                border: 1px solid #ced4da;
                                                border-radius: 0.25rem;
                                                box-shadow: inset 0 0 0 transparent;
                                                transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;" id="specialdiscount" class="specialdiscount" onchange="payFieldsValidation()" ></span></h6>
								    		<h6> <strong>Grand Total:</strong> <span id="grandTotal">0</span></h6>
								    	</div>

								    	<div  class="btn-group w-100" role="group" aria-label="Basic outlined example">
                                            <button id="cancel_table" type="button" class="btn btn-danger">Cancel</button>
										  <button id="hold_btn" type="button" class="btn btn-secondary"><i class="fas fa-pause-circle"></i> Hold</button>
										  <button id="payprint_btn" type="button" class="btn btn-info"><i class="fas fa-print"></i> Pay & Print</button>
										  <button id="pay_btn" type="button" class="btn btn-primary"><i class="fas fa-cash-register"></i> Pay</button>
										</div>
										{{-- <div class="float-left pt-2" role="group" aria-label="Basic outlined example">

										</div> --}}
								    </div>
	          					</div>




							</div> <!-- container -->
						</div> <!-- card-body -->
		          	</div> <!-- card card-primary card-outline -->
      			</div> <!-- col-lg-4 -->

      			<div class="col-lg-7 col-md-6">
          			<div class="card card-primary">
			            <div class="card-header">
                            <div class="row">
                                <div class="col-3">
                                    <h5 class="btn btn-primary m-0"><strong><i class="fas fa-shopping-bag" style=" color: ;"></i> PRODUCTS</strong></h5>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                      <input type="text" class="form-control" name="search" id="search">
                                      <input type="hidden" name="hiddensearchkeyword" id="hiddensearchkeyword">
                                      <input type="hidden" name="hiddencategorysearchkeyword" id="hiddencategorysearchkeyword">
                                      <div class="input-group-append">
                                          <span class="input-group-text"><i class="fas fa-search"></i></span>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-3">
                                    <button type="button" class="btn btn-primary float-right" value="all_products" onclick="getProductByCategory('all_products');">All Products</button>
                                </div>

                            </div>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

	          					<div class="row ">

	          						<!-- ---------------------------------------------Category slide------------------------------------ -->
	          						  <div class="container my-2">

									    {{-- <hr class="my-2"> --}}

									    <!--Carousel Wrapper-->
									    <div id="multi-item-example" class="carousel slide carousel-multi-item" data-interval="false">

									      <!--Controls-->
									      <div class="controls-top">
                                            <div class="row">
                                                <div class="col-1">
									                <a style="margin-top: 20;" class="btn-floating btn-lg float-left" href="#multi-item-example" data-slide="prev"><i class="fas fa-chevron-circle-left" style="color: gray;" ></i></a>
                                                </div>
                                                <div class="col-10">
                                                    <!--Indicators-->
                                                    <ol class="carousel-indicators">
                                                      <li data-target="#multi-item-example" data-slide-to="0" class="active"></li>
                                                      <li data-target="#multi-item-example" data-slide-to="1"></li>
                                                      <li data-target="#multi-item-example" data-slide-to="2"></li>
                                                    </ol>
                                                    <!--/.Indicators-->

                                                    <!--Slides-->
                                                    <div class="carousel-inner pt-2" role="listbox" id="slides">

                                                      <!--First slide-->
                                                      <div class="carousel-item active" >

                                                        <div class="row" style="text-align:center;" id="categories">


                                                        </div>


                                                      </div>
                                                      <!--/.First slide-->

                                                    </div>
                                                    <!--/.Slides-->
                                                </div>
                                                <div class="col-1">
									                <a style="margin-top: 20;" class="btn-floating btn-lg float-right" href="#multi-item-example" data-slide="next"><i class="fas fa-chevron-circle-right" style="color: gray;"></i></a>
                                                </div>
                                            </div>
									      </div>
									      <!--/.Controls-->



									    </div>
									    <!--/.Carousel Wrapper-->

									    <div class="row pt-5" id="products" style="height: 535px; overflow-x: hidden; overflow-y: auto;">


									    </div>
									    <div class="row pt-3" id="page-link">
									    	<!-- {!! $products->links() !!} -->
									    	<div class="col-6">
									    		<a class='btn btn-primary float-right' href='' id='prev'>Prev</a>
									    	</div>
									    	<div class="col-6">
									    		<a class='btn btn-primary float-left' href='' id='next'>Next</a>
									    	</div>

									    </div>

									  </div>
	          					</div>


							</div> <!-- container -->
						</div> <!-- card-body -->
		          	</div> <!-- card card-primary card-outline -->
      			</div> <!-- col-lg-8 -->
      		</div> <!-- row -->
		</div> <!-- container-fluid -->
	</div> <!-- content -->

</div> <!-- content-wrapper -->

<!-- Quick Sell Modal -->
<div class="modal fade" id="QuickSellModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="QuickSellForm" enctype="multipart/form-data">

			    <div class="modal-body">

			        <h4 class="text-center pt-3">Please add quick sell product</h4>
			        <div class="form-group mb-3 pt-3">
		      			<label>Name*</label>
		      			<input type="text" id="product" name="product" class="form-control" placeholder="Product name here">
		      	    </div>
		      	    <div class="form-group mb-3">
		      			<label>Price*</label>
		      			<input type="number" id="price" name="price" class="form-control" value="" placeholder="Enter price">
		      	    </div>
		      	    <div class="form-group mb-3">
		      			<label>Quantity*</label>
		      			<input type="number" id="quantity" name="quantity" class="form-control" value="" placeholder="Enter Quantity">
		      	    </div>
		      	    <div class="form-group mb-3">
		      			<label style="display:none;">Discount</label>
		      			<input type="hidden" id="discountP" name="discount" class="form-control" value="0" placeholder="Enter discount">
		      			<div style="display:none;" id="" class="form-text">If any discount applicable.</div>
		      	    </div>
		      	    <div class="form-group mb-3">
		      			<label style="display:none;">Vat/Tax</label>
		      			<input type="hidden" id="vat" name="vat" class="form-control" value="0" placeholder="Enter vat/tax">
		      			<div style="display:none;" id="" class="form-text">If any vat/tax applicable.</div>
		      	    </div>

			    </div>

			    <div class="modal-footer justify-content-center">
			        <button type="button" class="cancel btn btn-secondary cancel_btn" data-dismiss="modal">Cancel</button>
			        <button type="submit" class="delete btn btn-danger">Submit</button>
			    </div>

			</form>

		</div>
	</div>
</div>
<!-- End Quick Sell Modal -->

<!-- Add Customer Modal -->
<div class="modal fade" id="AddCustomerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="AddCustomerForm" method="POST" enctype="multipart/form-data">

			    <div class="modal-body">



			        <h4 class="pt-3 text-center">Add Customer</h4>
			        <div class="row pt-2">
						<div class="form-group mb-1">
							<select class="form-control selectpicker" data-live-search="true" aria-label="Default select example" name="customerid" id="customerid">
							  	<option value="option_select" selected>Select Customer</option>
							  	@foreach($customers as $customer)
								<option value="{{ $customer->id }}">
									{{ $customer->name }}({{ $customer->mobile}})
								</option>
								@endforeach
							</select>
						</div>
			        </div>


					<div class="row pt-2">
  						<div class="float-left pt-2" role="group" aria-label="Basic outlined example">
  							<button id="walkin" type="button" class="w-40 btn btn-outline-primary pe-3"><i class="fas fa-walking"></i> Walk-in</button>
  							<button id="newcustomer" type="button" class=" w-40 btn btn-outline-primary"><i class="fas fa-user-plus"></i> New Customer</button>
  						</div>
  					</div>
  					<div class="row pt-3">
  						<div id="newcustomerdiv">

						</div>
					</div>
					<div class="row">
						<div id="newcustomerdiv1">
							<!-- -------------------------------------- details  -------------------------------- -->

						</div>
					</div>

			    </div>

			    <div class="modal-footer justify-content-center">
			        <button type="button" class="cancel_addcustomer btn btn-outline-danger cancel_btn" data-dismiss="modal">Cancel</button>
			        <button type="submit" class="delete btn btn-primary">Save</button>
			    </div>

			</form>

		</div>
	</div>
</div>
<!-- End Add Customer Modal -->

<!-- Payment Modal -->
<div class="modal fade" id="PaymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content" id="form_div">

			<form id="PaymentForm" method="" enctype="multipart/form-data">

			    <div class="modal-body">

			        <h4 class="pt-3 text-center">Payment</h4>

			        <hr>

			        <div class="row g-3 align-items-center pt-3">
  						<div class="col-3">
						    <label for="cashamount" class="col-form-label fw-normal">Cash</label>
						</div>
  						<div class="col-4">
						    <input type="number" name="cashamount" class="form-control" id="cashamount" onchange="payFieldsValidation()" placeholder="Cash amount">
						</div>

  					</div>
  					<div class="row g-3 align-items-center pt-3">
  						<div class="col-3">
						    <label for="mobilebanking" class="col-form-label fw-normal">Mobile Banking</label>
						</div>
  						<div class="col-4">
						    <select class="form-control" name="mobilebanking" id="mobilebanking">
						    	<option value="default" selected>Select</option>
						      	@foreach($methods as $method)
				            	<option value="{{ $method->id }}">{{ $method->paymentType  }}</option>
				        		@endforeach
						    </select>
						</div>
						<div class="col-4">
						    <input type="number" name="mobilebankingamount" class="form-control" id="mobilebankingamount" onchange="payFieldsValidation()" placeholder="Amount" disabled>
						</div>

  					</div>
  					<div class="row g-3 align-items-center pt-3 ">
  						<div class="col-3">
						    <label for="bankamount" class="col-form-label fw-normal">Bank</label>
						</div>
                        <div class="col-4">
						    <select class="form-control" name="bank" id="bank">
						    	<option value="default" selected>Select</option>
						      	@foreach($banks as $bank)
				            	<option value="{{ $bank->id }}">{{ $bank->bank_name  }}</option>
				        		@endforeach
						    </select>
						</div>
  						<div class="col-4">
						    <input type="number" name="bankamount" class="form-control" id="bankamount" onchange="payFieldsValidation()" placeholder="Bank amount" disabled>
						</div>

  					</div>
  					{{-- <div class="row g-3 align-items-center pt-3 ">
  						<div class="col-3">
						    <label for="specialdiscount" class="col-form-label fw-normal">Other Discount</label>
						</div>
  						<div class="col-4">
						    <input type="number" name="specialdiscount" class="form-control specialdiscount" id="specialdiscount" onchange="payFieldsValidation()" placeholder="Bank amount">
						</div>

  					</div> --}}

  					<div class="pt-5">
			    		<h4> <strong>Total:</strong> <span id="totalp">0</span></h4>
			    		<h4> <strong>Paid:</strong> <span id="paidp">0</span></h4>
			    		<h4> <strong>Due:</strong> <span id="duep">0</span></h4>
			    	</div>


			    </div>

			    <div class="modal-footer justify-content-center">
			        <button type="button" class="cancel_payment btn btn-danger cancel_btn" data-dismiss="modal">Cancel</button>
			        <button type="button" class="reset_payment btn btn-info cancel_btn" data-dismiss="modal">Reset</button>
			        <button id="pay_btnP" type="button" class="btn btn-primary">Pay</button>
			    </div>

			</form>

		</div>
	</div>
</div>
<!--End Payment Modal -->

<!-- POS receipt -->
<div class="modal fade w-500" id="ReceiptModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content" id="form_div">
		    <div class="modal-body">

		    	<div id="invoice" class="" >
		    		<div  class="container" style="">
		    			<div>
		    				<br>
				        	<h6 class="text-center"><strong><span id="iStoreName"></span></strong></h6>
				        	<h6 class="text-center"><span id="iOrgName"></span></h6>
				        	<h6 class="text-center"><span id="iStoreAddress"></span></h6>
				        	<h6 class="text-center">BIN : <span id="iBin"></span></h6>
				        	<h6 class="text-center">Mushak - <span id="">6.3</span></h6>
				        </div>
				        <div>
				        	<hr style="border: 0 none; border-top: 2px dashed #322f32; background: none; height:0;" class="mb-0">
				        	<h5 class="text-center mb-0">INVOICE</h5>
				        	<hr style="border: 0 none; border-top: 2px dashed #322f32; background: none; height:0;" class="mt-0">
				        </div>

				        <div>
				        	<div class="row">
					        	<div class="col-6"><h6 class="text-left">Cashier : <span id="iCashier"></span></h6></div>
					        	<div class="col-6"><h6 class="text-right">POS Name : {{ session()->get('posName') }}</h6></div>
					        </div>
					        <div class="row">
					        	<div class="col-6"><h6 class="text-left">Invoice : <span id="iInvoice"></span></h6></div>
					        	<div class="col-6"><h6 class="text-right">Date : <span id="iDate"></span></h6></div>
					        </div>
					        <div class="row">
					        	<div class="col-12"><h6 class="text-left">Customer : <span id="iCustomerId"></span></h6></div>
					        </div>
				        </div>

				        <div class="mt-3">
				        	 <table id="invoice_table" class="table table-bordered">
							    <thead class="" style="border: 0 none; border-top: 2px dashed #322f32; background: none; height:0; border-bottom: 2px dashed #322f32; ">
							        <tr>
							        	<th>SL</th>
							            <th>Item Description</th>
							            <th>Unit Price</th>
							            <th>Qty</th>
							            <th>Total</th>

							        </tr>
							    </thead>
							    <tbody id="invoice_table_body">


							    </tbody>
						    </table>
				        </div>
				        <hr style="border: 0 none; border-top: 2px dashed #322f32; background: none; height:0;" class="mt-0">

				        <div class="row">
				        	<div class="col-7"><h6 class="text-right">Sub Total : </h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iSubTotal">0.00</span></h6></div>
				        </div>
				        <div class="row">
				        	<div class="col-7"><h6 class="text-right">(-) Discount : </h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iDiscount">0.00</span></h6></div>
				        </div>
				        <div class="row mt-0">
				        	<div class="col-7"><h6 class="text-right"></h6></div>
					        <div class="col-4"><h6 class="text-right"><hr style="border: 0 none; border-top: 2px dashed #322f32; background: none; height:0;"></h6></div>
				        </div>
				        <div class="row">
				        	<div class="col-7"><h6 class="text-right"></h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iAfterDiscount">0.00</span></h6></div>
				        </div>
				        <div class="row">
				        	<div class="col-7"><h6 class="text-right">(-) Special Discount : </h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iSpecialDiscount">0.00</span></h6></div>
				        </div>
				        <div class="row mt-0">
				        	<div class="col-7"><h6 class="text-right"></h6></div>
					        <div class="col-4"><h6 class="text-right"><hr style="border: 0 none; border-top: 2px dashed #322f32; background: none; height:0;"></h6></div>
				        </div>
				        <div class="row">
				        	<div class="col-7"><h6 class="text-right"></h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iAfterSpecialDiscount">0.00</span></h6></div>
				        </div>
				        <div class="row">
				        	<div class="col-7"><h6 class="text-right"><span id="">(+) Total VAT : </span></h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iVat">0.00</span></h6></div>
				        </div>
				        <div class="row">
				        	<div class="col-7"><h6 class="text-right"></h6></div>
					        <div class="col-4"><h6 class="text-right"><hr style="border: 0 none; border-top: 2px dashed #322f32; background: none; height:0;"></h6></div>
				        </div>
				        <div class="row">
				        	<div class="col-7"><h6 class="text-right"></h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iAfterVat">0.00</span></h6></div>
				        </div>
				        <div class="row">
				        	<div class="col-7"><h6 class="text-right">(+/-) Rounding : </h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iRounding">0.00</span></h6></div>
				        </div>

			         	<div class="row">
				        	<div class="col-7"><h6 class="text-right"></h6></div>
					        <div class="col-4"><h6 class="text-right"><hr style="border: 0 none; border-top: 2px dashed #322f32; background: none; height:0;"></h6></div>
				        </div>
				        <div class="row">
				        	<div class="col-7"><h6 class="text-right">Net Payable : </h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iNetPayable">0.00</span></h6></div>
				        </div>

				        <div class="row">
				        	<div class="col-7"><h6 class="text-right"></h6></div>
					        <div class="col-4"><h6 class="text-right"><hr style="border: 0 none; border-top: 2px dashed #322f32; background: none; height:0;"></h6></div>
				        </div>

				        <div class="row">
				        	<div class="col-7"><h6 class="text-right">Paid : </h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iPaid">0.00</span></h6></div>
				        </div>

				        <div class="row">
				        	<div class="col-7"><h6 class="text-right">Due : </h6></div>
					        <div class="col-4"><h6 class="text-right"><span id="iDue">0.00</span></h6></div>
				        </div>



				        <hr style="border: 0 none; border-top: 2px dashed #322f32; background: none; height:0;" class="mt-2">
				        <div>
				        	<h6 class="text-center">Hotline : <span id=""></span></h6>
				        	<h6 class="text-center"><span id="">*** Thank You For Shopping With Us ***</span></h6>

				        </div>

		    		</div>

		    	</div>


		    </div>
		</div>
	</div>
</div>

<!-- Hold Modal -->
<div class="modal fade" id="HoldModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content" id="form_div_hold">

			<form id="HoldForm" method="" enctype="multipart/form-data">

			    <div class="modal-body">

			        <h4 class="pt-3 text-center">Hold Invoice? Same Reference will replace the old list if exist!!!</h4>

			        <hr>

			        <div class="row g-3 align-items-center pt-3">
  						<!-- <div class="col-3">
						    <label for="referencenumber" class="col-form-label fw-normal">Cash</label>
						</div> -->
  						<div class="col-12">
						    <input type="number" name="referencenumber" class="form-control" id="referencenumber" placeholder="Reference number e.g. 10237">
						    <h6 class="text-danger pt-1" id="wrongreferencenumber" style="font-size: 14px;"></h6>
						</div>

  					</div>
			    </div>

			    <div class="modal-footer justify-content-center">
			        <button type="button" class="cancel_hold btn btn-danger" data-dismiss="modal">Cancel</button>
			        <button id="save_hold" type="submit" class="btn btn-info" onclick="dataCollectionForHold()">Save</button>
			    </div>

			</form>

		</div>
	</div>
</div>
<!--End Hold Modal -->

<!-- Hold List Modal -->
<div class="modal fade" id="HoldListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content" id="">



			    <div class="modal-body">
			        <h4 class="pt-3 text-center"><strong>HOLDS</strong></h4>
			        <hr>
			        <div class="table-responsive">
                        <table id="hold_table" class="table table-bordered" >
                            <thead>
                                <tr>
                                	<!-- <th>#</th> -->
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Ref ID</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="hold_table_body">

                            </tbody>
                        </table>
                    </div>
                    <div class="pt-3">
	                    <button type="button" class=" float-right cancel_hold_list btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>
			    </div>

		</div>
	</div>
</div>
<!--End Hold List Modal -->

<!-- Modal -->
<div class="modal fade" id="serialModal" tabindex="-1" aria-labelledby="serialModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="serialModalLabel">Add Serial Number</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="text" id='serial' class="form-control" placeholder="Serial Number">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="serial_submit btn btn-primary">Confirm Serial</button>
        </div>
      </div>
    </div>
  </div>


@endsection

@section('script')
<script type="text/javascript" src="js/pos.js"></script>
<script type="text/javascript" src="js/hold-pos.js"></script>
<script type="text/javascript" src="js/pos-pagination.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
    var pagebutton= document.getElementById("selfclick");
    pagebutton.click();
    });

	$(document).on('click', '.cancel', function (e) {
		$('#QuickSellModal').modal('hide');
	})

	$(document).on('click', '.cancel_addcustomer', function (e) {
		$("#newcustomerdiv").empty();
		$("#newcustomerdiv1").empty();
		$('#AddCustomerModal').modal('hide');
	})

	$(document).on('click', '.cancel_payment', function (e) {
        $('#form_div').find('form')[0].reset();
		// $('#cashamount').reset();

		// var total = parseFloat($('#total').text())

		var grandTotal = parseFloat($('#grandTotal').text())
		var zero = 0

		$('#totalp').text(grandTotal.toFixed(2))
		$('#paidp').text(zero.toFixed(2))
		$('#duep').text(grandTotal.toFixed(2))
		$('#grandTotal').text(grandTotal.toFixed(2))

		$('#cashamount').val('')
		$('#mobilebankingamount').val('')
		$('#bankamount').val('')
		$('#PaymentModal').modal('hide');
	})

    $('#mobilebanking').on('change', function() {
    $('#mobilebankingamount').prop("disabled", false);
    });

    $('#bank').on('change', function() {
    $('#bankamount').prop("disabled", false);
    });

	$(document).on('click', '.reset_payment', function (e) {

		$('#form_div').find('form')[0].reset();
		// $('#cashamount').reset();

		// var total = parseFloat($('#total').text())

		var grandTotal = parseFloat($('#grandTotal').text())
		var zero = 0

		$('#totalp').text(grandTotal.toFixed(2))
		$('#paidp').text(zero.toFixed(2))
		$('#duep').text(grandTotal.toFixed(2))
		$('#grandTotal').text(grandTotal.toFixed(2))

		$('#cashamount').val('')
		$('#mobilebankingamount').val('')
		$('#bankamount').val('')
	})



	$(document).on('click', '#quicksell', function (e) {
		e.preventDefault();
		$('#product').val('')
		$('#price').val('')
		$('#quantity').val('')
		$('#QuickSellModal').modal('show');
	});

	$(document).on('click', '#addcustomer', function (e) {
		e.preventDefault();

		$("#newcustomerdiv").empty();
		$("#newcustomerdiv1").empty();

		$("#newcustomer").show();

		$('#AddCustomerModal').modal('show');
	});

	$(document).on('click', '#hold_btn', function (e) {
		e.preventDefault();
		// alert('Hold')
		$('#HoldModal').modal('show');
		$('#form_div_hold').find('form')[0].reset();
	})

	$(document).on('click', '.cancel_hold', function (e) {
		e.preventDefault();
		// alert('Hold')
		$('#HoldModal').modal('hide');
		$('#wrongpayreferencenumber').empty();
		$('#form_div_hold').find('form')[0].reset();
	})

	$(document).on('click', '#hold_list', function (e) {
		e.preventDefault();
		// alert('Hold')
		$('#HoldListModal').modal('show');
	})

	$(document).on('click', '.cancel_hold_list', function (e) {
		e.preventDefault();
		// alert('Hold')
		$('#HoldListModal').modal('hide');
	})



</script>

@endsection
