@extends('layouts.master')
@section('title', 'Order Details')

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
		            <!-- BAR CHART -->
		            <div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title"><strong>ORDER DETAILS</strong></h3>

		                <div class="card-tools">
		                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
		                    <i class="fas fa-minus"></i>
		                  </button>
		                  <button type="button" class="btn btn-tool" data-card-widget="remove">
		                    <i class="fas fa-times"></i>
		                  </button>
		                </div>
		              </div>
		              <div class="card-body" >
		              	<h6><strong>Name:</strong> {{ $client->name}}</h6>
		              	<h6><strong>Mobile:</strong> {{ $client->mobile}}</h6>
		              	<h6><strong>Address:</strong> {{ $client->address}}</h6>

		                <div class="pt-3">
		                  <table id="due_table_details" class="display">
											  <thead>
											    <tr>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Order ID</th>
                                                    <th scope="col">Cash Payment</th>
                                                    <th scope="col">Card Payment</th>
											        <th scope="col">Online Payment</th>
											        <th scope="col">Total Paid</th>
											        <th scope="col">Due</th>
											        <th scope="col">Total</th>
											    </tr>
											  </thead>
											  <tbody>
											  	<tr>
                                                      <td>{{ $order->orderDate }}</td>
                                                        <td>{{ $order->orderId }}</td>
                                                        <td>{{ number_format($payment->cash, 2, ".", "") }}</td>
                                                        <td>{{ number_format($payment->card, 2, ".", "") }}</td>
                                                        <td>{{ number_format($payment->mobile_bank, 2, ".", "") }}</td>
                                                        <td>{{ number_format(($payment->total-$payment->due), 2, ".", "") }}</td>
                                                        <td>{{ number_format($payment->due, 2, ".", "") }}</td>
                                                        <td>{{ number_format($payment->total, 2, ".", "") }}</td>
                                                    </tr>
											  </tbody>
											</table>


		                </div>
		              </div>
		              <!-- /.card-body -->
		            </div>
		            <!-- /.card -->
		        	</div>   <!-- /.col-lg-6 -->
        		</div><!-- /.row -->

        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection

@section('script')
<script type="text/javascript">
dataTableX()
function dataTableX() {
    $(document).ready(function () {
        $('#due_table_details').DataTable({
            paging: false,
            searching: false,
            info:     false
        })
    });
}

</script>
@endsection
