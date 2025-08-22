@extends('layouts.master')
@section('title', 'Product List')

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
				<div class="col-lg-8">
				  	<div class="card card-primary">
				  		<div class="card-header">
			                <h5 class="m-0"><strong>PURCHASE DETAILS</strong></h5>
			            </div>

				      <div class="card-body">
				        <!-- <h6 class="card-title">Special title treatment</h6> -->
				        <!-- Table -->

				        <div class="row justify-content-between">

				        	<div class="col-sm-8">
							      <a href="/purchase-list"><button type="button" class="btn btn-outline-secondary">
				      			<i class="fas fa-chevron-left"></i>  Purchases</button></a>

				      		</div>

				      		<div class="col-sm-4">
				      			@foreach($purchaseList as $purchase)
					      			@if($purchase->status == 'pending')
						      			<a href="#"><button type="button" class="btn btn-danger float-right ml-1" id="pending" value="{{$purchase->id}}">
						      			Receive All</button></a>
						      			<a href="/purchase-product-edit/{{ $purchase->id }}">
						      				<button type="button" class="btn btn-outline-secondary float-right">Edit</button>
				      					</a>
                                    @elseif($purchase->status == 'partial recieved')
                                    <a href="#"><button type="button" class="btn btn-danger float-right ml-1" id="pending" value="{{$purchase->id}}">
                                    Receive All</button></a>
                                    @else
						      			<a href="#"><button type="button" class="btn btn-success float-right ml-1" disabled>
						      			Received</button></a>
						      		@endif


				      			@endforeach
						    </div>
				        </div>

				        <hr>
				        @foreach($purchaseList as $purchase)
					        {{-- <h1>PO {{ $purchase->poNumber }}</h1> --}}
					        @if($purchase->status == 'pending')
				      			<h4 class="text-danger"  id="status"><strong>Pending</strong></h4>
                            @elseif($purchase->status == 'partial recieved')
                                <h4 class="text-danger"  id="status"><strong>Partial Pecieved</strong></h4>
                            @else
				      			<h4 class="text-success" id="status1"><strong>Received</strong></h4>
				      		@endif

					      @endforeach
					        <div class="row">
					        	<div class="col-3">
					        		<p class="mb-0"><strong>Supplier:</strong></p>
							        @foreach($supplier as $s)
								        <p class="mb-0">{{ $s->name }}</p>
								        <p class="mb-0">{{ $s->mobile }}</p>
								        <p class="mb-0">{{ $s->address }}</p>
								        <p class="mb-0">{{ $s->email }}</p>
								        <p class="mb-0">{{ $s->supplier_website }}</p>
							        @endforeach
					        	</div>

					        	@foreach($purchaseList as $purchase)
					        	<div class="col-4">
					        		<p class="mb-0"><strong>Store: </strong><span id="storeSpan">{{ $purchase->store }}</span></p>
							        <p class="mb-0"><strong>Order Date: </strong>{{ $purchase->purchaseDate }}</p>
							        <p class="mb-0"><strong>Order By: </strong>{{ $purchase->created_by }}</p>
							    @endforeach
					        	</div>
					        	@foreach($purchaseList as $purchase)
					        	<div class="col-5">
					        		<div class="">
					        			<div class="row ">
					        				<div class="col-6 ">
							          			<h5><strong>Total: </strong></h5>
					        				</div>
					        				<div class="col-6 ">
					        					<h5>{{ number_format($purchase->totalPrice, 2, ".", "") }}</h5>
					        				</div>
					        			</div>
					        			<div class="row ">
					        				<div class="col-6">
							          			<h5><strong>Discount: </strong></h5>
					        				</div>
					        				<div class="col-6 ">
					        					<h5>{{ number_format($purchase->discount, 2, ".", "") }}</h5>
					        				</div>
					        			</div>
					        			<div class="row ">
					        				<div class="col-6 ">
							          			<h5><strong>Other Cost: </strong></h5>
					        				</div>
					        				<div class="col-6 ">
					        					<h5>{{ number_format($purchase->other_cost, 2, ".", "") }}</h5>
					        				</div>
					        			</div>
					        			<div class="row ">
					        				<div class="col-6 ">
							          			<h5><strong>Grand Total: </strong></h5>
					        				</div>
					        				<div class="col-6">
					        					<h5>{{ number_format($purchase->grandTotal, 2, ".", "") }}</h5>
					        				</div>
					        			</div>

					          		</div>
					        	</div>
					        	@endforeach
					        </div>

				        <hr>

				        <h3>Products</h3>

				        @foreach($productList as $product)
				        <input type="hidden" name="purchaseid" id="purchaseid" value="{{ $product->purchaseProductId }}">
				        @endforeach

				         <div class="pt-2 row">
				          	<div class="col-12">
					          	<table id="product_table" class="display" >
								    <thead>
								        <tr>
								        	<th>#</th>
								        	<th class="hidden">ProductId</th>
								            <th>Product Name</th>
								            <th>Variant</th>
								            <th class="hidden">VariantID</th>
								            <th>Purchase Quantity</th>
								            <th>Unit Price</th>
								            <th>MRP</th>
								            <th>Total Price</th>
								            <th>Recieve Quantity</th>
								        	<th class="hidden">Id</th>
								            <th>Action</th>
								        </tr>
								    </thead>
								    <tbody>

								    </tbody>
							    </table>
				          	</div>
						</div>
						<div class="row">
				          	<div class="col-11">

				          	</div>
						</div>

				      </div> <!-- Card-body -->
				    </div>	<!-- Card -->

			</div>   <!-- /.col-lg-6 -->
		</div><!-- /.row -->

        <div class="row">
            <!-- Modal -->
            <div class="modal fade" id="recieveModal" tabindex="-1" role="dialog" aria-labelledby="recieveModalLabel" aria-hidden="true" >
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="recieveModalLabel">Recieved Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="container-fluid modal-body">
                            <div class="row">
                                <div class="col-5 mt-1">
                                    <label for="recieve_qty" class="form-label float-right">Recieve Quantity:</label>
                                </div>
                                <div class="col-5">
                                    <input type="hidden" class="form-control" name='id' id='id'>
                                    <input type="number" class="form-control" name='recieve_qty' id='recieve_qty'>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="confirm_recieve" class="btn btn-primary">Recieved</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="productSerialModal" tabindex="-1" role="dialog" aria-labelledby="productSerialLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="productSerialLabel">Product Serial</h5>
                        <button type="button" class="close exit" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="container-fluid modal-body">
                            <div class="row">
                                {{-- <input type="hidden" class="form-control" name='id' id='1'> --}}
                                {{-- <div class="col">
                                    <label for="serialNumber" class="form-label float-right">Serial Number:</label>
                                </div> --}}
                                <div class="col-10">
                                    <input type="text" class="form-control" name='serialNumber' id='serialNumber' placeholder="Serial Number">
                                </div>
                                <div class="col-2">
                                    <button type="button" id='addSerial' class="btn btn-secondary">+</button>
                                </div>
                            </div>
                            <div class="table table-responsive" id="serialTable">
                                <table id="serial_table" class="table">
                                    <thead>
                                        <th scope="col">#</th>
                                        <th scope="col">Serial Number</th>
                                        <th scope="col">Action</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="exit btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="confirm_serial" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="allRecieveModal" tabindex="-1" role="dialog" aria-labelledby="allRecieveLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="allRecieveLabel">Recieve</h5>
                        <button type="button" class="close exit" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="container-fluid modal-body">
                            <div class="row">
                                <input type="hidden" id='onlyNonSerilize'>
                                <h5>There are serilize product on purchase list, you need to recieve them with serial number.</h5>
                            </div>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="exit btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="non-serilize_recieve" class="btn btn-primary">Recieve All Non-Serilize Prodect</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


	</div>
</div>
</div>


@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/purchaseproductlist.js') }}"></script>
<script type="text/javascript">
	$(document).on('click', '#close', function (e) {
		$('#EDITDiscountMODAL').modal('hide');
	});
	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEDiscountMODAL').modal('hide');
	});



</script>

@endsection


	<!-- {{ asset('js/purchaselist.js') }} -->
