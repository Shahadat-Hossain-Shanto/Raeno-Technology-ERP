@extends('layouts.master')
@section('title', 'Supplier Transection')

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
        			<div class="col-lg-10">
		            <!-- BAR CHART -->
		            <div class="card card-primary">
		              <div class="card-header">
		                <h3 class="card-title"><strong>SUPPLIER TRANSECTION DETAILS</strong></h3>

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
                        <h6>Supplier Name: <strong>{{ $supplier_data->name}}</strong></h6>
                        <h6>Mobile: <strong>{{ $supplier_data->mobile}}</strong></h6>
                        <h6>Address: <strong>{{ $supplier_data->address}}</strong></h6>

		                <div class="pt-2">
		                  <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
		                  <div class="table-responsive">
						  					<table id="details_table" class="display" width="100%">
												  <thead>
												    <tr>
												    	<th class="">#</th>
												      <th scope="col">Transaction Date</th>
                                                      <th scope="col">Transaction Id</th>
                                                      <th scope="col">Reference Note</th>
												      <th scope="col">Debit</th>
												      <th scope="col">Credit</th>
                                                      <th scope="col">Balance</th>

												    </tr>
												  </thead>
												  <tbody>
                                                    @php
                                                        $i=1;
                                                    @endphp
                                                    @foreach($data as $item)
                                                    <tr>
                                                    <td>{{$i++}}</td>
                                                    <td>{{ $item->transaction_date }}</td>
                                                    <td>{{ $item->transaction_id }}</td>
                                                    <td>
                                                        @if ($item->reference_note==null)
                                                            N/A
                                                        @else
                                                            {{ $item->reference_note }}
                                                        @endif{{ $item->reference_note }}
                                                    </td>
                                                    <td>{{ number_format($item->debit, 2) }}</td>
                                                    <td>{{ number_format($item->credit, 2) }}</td>
                                                    <td>
                                                        @if ($item->balance>0)
                                                            {{ number_format($item->balance, 2)}} Dr
                                                        @else
                                                            {{ number_format(-1*$item->balance, 2)}} Cr
                                                        @endif
                                                    </td>

                                                </tr>
                                                    @endforeach
												  </tbody>
												</table>
											</div>

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
{{-- <script type="text/javascript" src="js/transection-list.js"></script> --}}
<script>
       $(document).ready(function () {
        $('#details_table').DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
            dom: 'Bfrtip',
	       buttons: [

            {
                extend: 'copy',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                }
            },
        ]

        });
    });
</script>


@endsection



