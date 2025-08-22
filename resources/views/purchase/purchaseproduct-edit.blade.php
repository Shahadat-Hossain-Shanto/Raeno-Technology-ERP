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
		<form id="UPDATEProductFORM" enctype="multipart/form-data">
			<div class="row">

				<div class="col-lg-6">

			  		<input type="hidden" name="_method" value="PUT">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

				  	<div class="card card-primary">
				  		<div class="card-header">
			                <h5 class="m-0"><strong><i class="fas fa-pencil-alt"></i> PURCHASE EDIT</strong></h5>
			            </div>

				        <div class="card-body">

				        @foreach($purchaseList as $purchase)
					        {{-- <h1>PO {{ $purchase->poNumber }}</h1> --}}
					      @endforeach
					      <hr>

					    <div class="row pt-2">

							<div class="col-5">
								<div class="mb-3">
							    <label for="supplier" class="form-label">Supplier</label><br>
							    <select class="selectpicker" name="supplier"
							      id="edit_supplier" data-live-search="true">
							      <option value="option_select" disabled selected>Select Supplier</option>
							      @foreach($suppliers as $supplier)
							      <option value="{{ $supplier->id  }}">{{ $supplier->name  }}</option>
					        	@endforeach
							    </select>

							  	</div>
							</div>

							<div class="col-5">
								<div class="mb-3">
							    <label for="store" class="form-label">Store</label><br>
							    <select class="selectpicker" data-live-search="true" aria-label="Default select example" name="store"
							      id="edit_store">
							      <option value="option_select" disabled selected>Select Store</option>
							      <option value="Warehouse">Warehouse</option>
							      	@foreach($stores as $store)
								      <option value="{{ $store->store_name  }}">{{ $store->store_name }}</option>
						        	@endforeach
							    </select>

							  </div>
							</div>

					    </div>

					      <div class="row">

							<div class="col-5">
								<div class="mb-3">
							    <label for="purchasedate" class="form-label">Purchase Date</label>
							    <input type="date" class="form-control" name="purchasedate" id="edit_purchasedate" >
							  </div>
							</div>

					      </div>
                          <div class="row">

							<div class="col-5">
								<div class="mb-3">
							    <label for="edit_discount" class="form-label">Discount</label>
							    <input type="number" class="form-control" name="discount" id="edit_discount" >
							  </div>
							</div>

                            <div class="col-5">
								<div class="mb-3">
							    <label for="edit_othercost" class="form-label">Other Cost</label>
							    <input type="number" class="form-control" name="othercost" id="edit_othercost" >
							  </div>
							</div>

					      </div>

                          <div class="row">

                              <div class="col-5">
                                <div class="mb-3">
                                <label for="edit_subtotalprice" class="form-label">Sub Total</label>
                                <input type="text" class="form-control" name="subtotalprice" id="edit_subtotalprice" disabled>
                              </div>
                            </div>
							<div class="col-5">
								<div class="mb-3">
							    <label for="edit_grandtotalprice" class="form-label">Grand Total</label>
							    <input type="text" class="form-control" name="grandtotalprice" id="edit_grandtotalprice" disabled>
							  </div>
                            </div>

					      </div>


								<div class="row">
									<div class="col-10">
										<div class="mb-3">
								    <label for="note" class="form-label">Note</label>
								    <textarea class="form-control" name="note" id="edit_note"  rows="2" placeholder=""></textarea>
								  </div>
									</div>
								</div>

                                <div class="row">
									<div class="col-10">
										<div class="float-right mb-3">
								        <button class="cancel_btn btn btn-outline-danger btn-lg" type="button" name="">CANCEL</button>
								        <button id='submit' class="btn btn-primary btn-lg" type="button" name="">Confirm</button>
							  	        </div>
									</div>
								</div>

					    </div> <!-- Card-body -->
				    </div>	<!-- Card -->

				</div>   <!-- /.col-lg-6 -->
				<div class="col-lg-6">
					<div class="card card-primary">
				  		<div class="card-header">
			                <h5 class="m-0"><strong>PRODUCTS</strong></h5>
			            </div>
					      <div class="card-body">
					      	<div class="container">

					      		@foreach($productList as $product)
						        	<input type="hidden" name="purchaseid" id="purchaseid" value="{{ $product->purchaseProductId }}">
						        @endforeach

						      	<table id="productTable" class="table">
								  <thead>
								    <tr>
							    		<th scope="col">Product</th>
							    		<th scope="col">Quantity</th>
							    		<th scope="col">Unit Price</th>
							    		<th scope="col">Total Price</th>
							    		<th></th>
                                        <th></th>
								    </tr>
								  </thead>
								  <tbody>

								  </tbody>
								</table>
								{{-- <div class="float-right mb-3">
								  <button class="add_btn btn btn-primary btn-lg" type="button" name="">Done</button>
							  	</div> --}}
					      	</div>
					      </div>
				    </div>
				</div>
				<div class="row">
					<div class="col-12">

					</div>
				</div>

			</div><!-- /.row -->
		</form>
	</div>
</div>
</div>


@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/purchaseproduct-edit.js') }}"></script>
<script type="text/javascript">

	$(document).on('click', '.cancel_btn', function (e) {
		$('#DELETEProductMODAL').modal('hide');
	});

	$(document).ready(function () {

		var purchaseProductId = $('#purchaseid').val();
		fetchProduct(purchaseProductId);

	});

</script>

@endsection


	<!-- {{ asset('js/purchaselist.js') }} -->
