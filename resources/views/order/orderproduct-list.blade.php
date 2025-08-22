@extends('layouts.master')
@section('title', 'Ordered Product List')

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
		                <h5 class="m-0"><i class="fas fa-shopping-cart" style="color: ;"></i> <strong>ORDER DETAILS</strong></h5>
		            </div>

			      <div class="card-body">
			        <!-- <h6 class="card-title">Special title treatment</h6> -->
			        <!-- Table -->

			        <div class="row justify-content-between">

			        	<div class="col-sm-8">
						      <a href="/order-list"><button type="button" class="btn btn-outline-secondary">
			      			<i class="fas fa-arrow-alt-circle-left"></i> Orders</button></a>

			      		</div>

			        </div>

			        <hr>
			        @foreach($orderList as $order)
				        <h1>ORDER NO. {{ $order->orderId }}</h1>

				        <div class="row">

				        	<div class="col-6">
				        		<p><strong>Details:</strong></p>
				        		@if($order->clientId != 0)
							        <p class="mb-0">Customer Name: {{ $order->name }}</p>
							        <p class="mb-0">Customer Mobile: {{ $order->mobile }}</p>
							        <p class="mb-0">Order Date: {{ $order->orderDate }}</p>
							        <p class="mb-0">Total Price: {{number_format( $order->total, 2, '.', '') }}</p>
							        <p class="mb-0">Discount: {{ number_format( $order->totalDiscount, 2, '.', '') }}</p>
							        <p class="mb-0">Total Tax: {{ number_format( $order->totalTax, 2, '.', '')  }}</p>
							        <p class="mb-0">Special Discount: {{ number_format( $order->specialDiscount, 2, '.', '') }}</p>
							        <p class="mb-0">Grand Total: {{ number_format( $order->grandTotal, 2, '.', '')  }}</p>
							    @else
							    	<p class="mb-0">Customer Name: Walking Customer</p>
							        <p class="mb-0">Customer Mobile: N/A</p>
							        <p class="mb-0">Order Date: {{ $order->orderDate }}</p>
							        <p class="mb-0">Total Price: {{ number_format( $order->total, 2, '.', '')  }}</p>
							        <p class="mb-0">Discount: {{ number_format( $order->totalDiscount, 2, '.', '')  }}</p>
							        <p class="mb-0">Total Tax: {{ number_format( $order->totalTax, 2, '.', '')  }}</p>
							        <p class="mb-0">Special Discount: {{ number_format( $order->specialDiscount, 2, '.', '')  }}</p>
							        <p class="mb-0">Grand Total: {{ number_format( $order->grandTotal, 2, '.', '')  }}</p>
							    @endif
				        	</div>

				        </div>
				    @endforeach
			        <br>
			        <hr>

			        <h3>Orderd Products</h3>

			        @foreach($orderList as $order)
			        <input type="hidden" name="orderid" id="orderid" value="{{ $order->id }}">
			        @endforeach

			            <div class="pt-2 row">
				          	<div class="col-12">
				          		<div class="table-responsive">
				          		<table id="products_table" class="display" width="100%">
								    <thead>
								        <tr>
								        	<th>#</th>
								            <th>Product Name</th>
								            <th>Quantity</th>
								            <th>Total</th>
								            <th>Discount</th>
								            {{-- <th>Special Discount</th> --}}
								            <th>Tax</th>
								            <th>Sub Total</th>
								        </tr>
								    </thead>
								    <tbody>

								    </tbody>
							    </table>
							</div>
				          	</div>
					    </div>

			      </div> <!-- Card-body -->
			    </div>	<!-- Card -->

			</div>   <!-- /.col-lg-6 -->
		</div><!-- /.row -->
	</div>
</div>
</div>


@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/orderproductlist.js') }}"></script>
<script type="text/javascript">


</script>

@endsection


	<!-- {{ asset('js/purchaselist.js') }} -->
