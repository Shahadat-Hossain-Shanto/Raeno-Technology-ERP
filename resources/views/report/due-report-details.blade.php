@extends('layouts.master')
@section('title', 'Due Reports Details')

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
		                <h3 class="card-title"><strong>DETAILS</strong></h3>

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
											    	<th class="">#</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Order ID</th>
                                                    <th scope="col">Total Purchase</th>
                                                    <th scope="col">Deposit</th>
											        <th scope="col">Due</th>
											        <th scope="col">Balance</th>
											    </tr>
											  </thead>
											  <tbody>
											    	@foreach($datas as $data)
											  	<tr>
											  		<td></td>
                                                      <td>{{ $data->deposit_date }}</td>
                                                        <td>
                                                            @if ( $data->order_id == 0||$data->order_id == '')
                                                            Customer Deposit
                                                            @else
                                                            <a type="button" class="edit_btn btn btn-info btn-sm" href="/customer-order-details/{{ $data->order_id }}">{{ $data->order_id }}</a>
                                                            @endif
                                                        </td>
                                                      <td>{{ number_format($data->totalPurchase, 2, ".", "") }}</td>
                                                      {{-- <td>{{ number_format($data->deposit, 2, ".", "") }}</td> --}}
													  <td>{{ number_format($data->deposit, 2, ".", "") }}</td>
                                                      <td>{{ number_format($data->due, 2, ".", "") }}</td>
													  <td>
                                                        @if ( $data->balance > 0)
                                                        {{ number_format($data->balance, 2, ".", "") }} Dr
                                                        @else
                                                        {{ number_format(-1*$data->balance, 2, ".", "") }} Cr
                                                        @endif
                                                    </td>
											  	</tr>
											  	@endforeach
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
<script type="text/javascript" src="{{ asset('js/due-report-details.js') }}"></script>
<script type="text/javascript">

</script>

@endsection



