@extends('layouts.master')
@section('title', 'Details')

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
		              	<h5 class="m-0"><strong>DETAILS</strong></h5>
		              </div>
		              <div class="card-body">

		              	<div class="row">
		              		<div class="col-3">
			              		@foreach($dues as $due)
			              			<h5><strong>Total Dues:</strong> {{ number_format($due->da, 2, ".", "") }}</h5>
			              		@endforeach
			              	</div>
			              	<div class="col-4">
			              		@foreach($payments as $payment)
			              		<h5><strong>Total Payments:</strong> {{ number_format($payment->a, 2, ".", "") }}</h5>
			              		@endforeach
			              	</div>
			              	<div class="col-3">
			              		<h5><strong>Total Orders:</strong> {{ $orders }}</h5>
			              	</div>
		              	</div>
		              	
			              <div>
			               	<p>   <strong>Name:</strong> {{ $customer->name }}
			               		<br><strong>Mobile:</strong> {{ $customer->mobile }}
			               		<br><strong>Email:</strong> {{ $customer->email }}
			               		<br><strong>Address:</strong> {{ $customer->address }}
			               	</p>
			               	
			              </div>
			              <hr>
			              <input type="hidden" name="customerid" id="customerid" value="{{ $customer->id }}">

			              <div class="pt-2">
			              	<div class="table-responsive">
												<table id="order_table" class="display" width="100%">
											    <thead>
											        <tr>
										            <th>#</th>
										            <th>Order ID</th>
										            <th>Total</th>
										            <th>Discount</th>
										            <th>Tax</th>
										            <th>Grnad Total</th>
										            <th>Date</th>
											        </tr>
											    </thead>
											    <!-- <tbody>

											    </tbody> -->
											  </table>
											</div>
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
<!-- DataTable -->
<script type="text/javascript" src="{{ asset('js/customer-details.js') }}"></script>
@endsection


	
