@extends('layouts.master')
@section('title', 'Purchase List')

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
			        	<h5 class="m-0"><strong><i class="fas fa-cart-arrow-down"></i> PURCHASES</strong></h5>
			      </div>
			      <div class="card-body">

			      	<a href="/purchase-create"><button type="button" class="btn btn-outline-info">
			      		<i class="fas fa-plus-circle"></i> Add Purchase</button></a>
                        <div id="form_div">
                                    <form id="purchaseListForm" >
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label for="supplier_id" style="font-weight: normal;">Supplier</label><br>
                                                <select class="selectpicker" name="supplier_id" id="supplier_id"
                                                    data-live-search="true" data-width="100%">
                                                    <option value=""selected disabled>Select Supplier</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="startdate" style="font-weight: normal;">From</label>
                                                <input type="date" class="form-control" id="startdate" name="startdate">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="enddate" style="font-weight: normal;">To</label>
                                                <input type="date" class="form-control" id="enddate" name="enddate">
                                            </div>

                                            <div style="padding-top: 32px;" class="form-group col-md-3">
                                                <button type="submit" class="btn btn-primary"
                                                    id="gen_btn">Generate</button>
                                                <button id="reset" type="reset" class=" w-30 btn btn-outline-danger"><i class="fas fa-eraser"></i> Reset</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

			          <div class="pt-3">
						<table id="purchaseProduct_table" class="display">
						    <thead>
						        <tr>
						        	<th>#</th>
						            <th>Supplier Name</th>
						            <th>Store</th>
						            <th>P.O. Number</th>
						            <th>Purchase Date</th>
						            <th>Total</th>
						            <th>Discount</th>
						            <th>Other Cost</th>
						            <th>Grand Total</th>
						            <th>Purchase Note</th>
						            <th>Status</th>
									<th>Return Amount</th>
						            <th>Action</th>
						        </tr>
						    </thead>
						    <tbody>

						    </tbody>
					    </table>
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
<script type="text/javascript" src="js/purchaselist.js"></script>
<script type="text/javascript">

$('#purchaseListForm').on('submit', function(e) {


            var supplier_id = $('#supplier_id').find(":selected").val();
               var startdate=$('#startdate').val();
               var enddate=$('#enddate').val();

               e.preventDefault();
               if (supplier_id !=''&& startdate.length != 0 && enddate.length != 0) {

                //     $.ajax({
                //     type: "get",
                //     url: "/purchase_list-listData/"+supplier_id+"/"+startdate+"/"+enddate,

                //     success: function(response) {

                //         console.log(response);

                //     }
                // });
                $('#purchaseProduct_table').DataTable().clear().destroy()
                var t = $('#purchaseProduct_table').DataTable({
        ajax: {
            "url": "/purchase_list-listData/"+supplier_id+"/"+startdate+"/"+enddate,
            "dataSrc": "purchase"
        },
        columns: [
            { data: null },
            { data: 'name' },
            { data: 'store' },
            { data: 'poNumber' },
            { data: 'purchaseDate' },
            { "render": function ( data, type, row, meta ){ var totalPrice = parseFloat(row.totalPrice); return totalPrice.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var discount = parseFloat(row.discount); return discount.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var other_cost = parseFloat(row.other_cost); return other_cost.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var grandTotal = parseFloat(row.grandTotal); return grandTotal.toFixed(2);} },
            { "render": function ( data, type, row, meta ){
            		if(row.purchaseNote == null){
						purchaseNote = 'N/A'
					}else{
						purchaseNote = row.purchaseNote
					}
            		return purchaseNote;
            	}
            },
            { "render": function ( data, type, row, meta ){
            		if(row.status == "pending"){
						status = '<span class="badge badge-danger">Pending</span>';
					}else{
						status = '<span class="badge badge-success">Received</span>';
					}
            		return status;
            	}
            },
            {
                "render": function (data, type, row, meta) {
                    var zero = 0;
                    if (row.purchaseReturn == '') {
                        var purchaseReturn = zero.toFixed(2);
                    }
                    else {
                        var purchaseReturn = '<a type="button" class="edit_btn btn btn-info btn-sm" href="/purchase-return-details/' + row.purchaseReturn.return_number + '">' + parseFloat(row.purchaseReturn.net_return).toFixed(2) + '</a>'
                    }
                    return purchaseReturn;
                }
            },
            { render: getBtns },
        ],
        columnDefs: [
	        {
	            searchable: true,
	            orderable: true,
	            targets: 0,
	        },
	    ],
	    // order: [[4, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#purchaseProduct_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
               }

               else {
        $.notify("Please select Supplier Name, From date and To date.", {
            className: 'error',
            position: 'bottom right'
        });

    }

            });
	$(document).on('click', '#reset', function (e) {
		$('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('val', 'Select Supplier');
	});

</script>

@endsection



