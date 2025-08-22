@extends('layouts.master')
@section('title', 'Product-In Reports Details')

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
	          	<div class="col-lg-6">

		          	<div class="card card-primary">
		              <div class="card-header">
		                	<h5 class="m-0"><strong>Details</strong></h5>
		              </div>
		              <div class="card-body">
	                	<h5> <strong class="text-secondary">Store: </strong>{{ $store_name}}</h5>
	                	<h5> <strong class="text-secondary">Product: </strong>{{ $product_name}}</h5>
	                	<h5> <strong class="text-secondary">Variant: </strong>{{ $variant_name}}</h5>
	                	<hr>
										<div id="tablePart" class="col-12  pt-3">

											<table id="product_table" class="table table-bordered">
											  <thead>
											    <tr>
											    	<th>#</th>
											      <th scope="col">Qty</th>
											      <th scope="col">Date</th>
											    </tr>
											  </thead>
											  <tbody id="product_table_body">
											  	@foreach($details as $data)
											  	<tr>
											  		<td></td>
														<td>{{ $data-> quantity}}</td>
														<td>{{ $data-> created_at}}</td>
											  	</tr>
											  	@endforeach
											  </tbody>
											</table>
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
<script type="text/javascript">
$(document).ready(function () {
	$('#product_table').DataTable({
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    dom: 'Bfrtip',
	    buttons: [
	            'copy', 'csv', 'excel', 'pdf', 'print'
	        ],
	    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
		    //debugger;
		    var index = iDisplayIndexFull + 1;
		    $("td:first", nRow).html(index);
		    return nRow;
	  	},
	});
})
</script>
@endsection



