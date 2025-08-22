@extends('layouts.master')
@section('title', 'Product-Transfer')

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
	          	<div class="col-lg-12">

		          	<div class="card card-primary">
		              <div class="card-header">
		                	<h5 class="m-0"><strong><i class="fas fa-exchange-alt"></i>  PRODUCT-TRANSFER</strong></h5>
		              </div>
		              <div class="card-body">

	                	<div id="form_div">
	                		<form id="" method="" enctype="multipart/form-data">
		                		<div id="storediv" style="display: disabled">
			                		<div class="row pt-3">
				                		<div class="form-group col-2">
                                            <label for="fromstore" style="font-weight: normal;">From Store<span class="text-danger"><strong>*</strong></span></label><br>
                                            <select class="selectpicker"  data-live-search="true" aria-label="Default select example" name="fromstore"
                                                id="fromstore" data-width="100%">
                                                <option value="fromdefault" selected disabled>Select Store</option>
                                                <option value="inventory">Warehouse</option>
                                                @foreach($stores as $store)
                                                <option value="{{ $store->id }}">{{ $store->store_name  }}</option>
                                                @endforeach
                                            </select>
                                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                                        </div>
                                        <div class="form-group col-2">
                                            <label for="tostore" style="font-weight: normal;">To Store<span class="text-danger"><strong>*</strong></span></label><br>
                                            <select class="selectpicker"  data-live-search="true" aria-label="Default select example" name="tostore"
                                                id="tostore" data-width="100%">
                                                <option value="todefault" selected disabled>Select Store</option>
                                                <option value="inventory">Warehouse</option>
                                                @foreach($stores as $store)
                                                <option value="{{ $store->id }}">{{ $store->store_name  }}</option>
                                                @endforeach
                                            </select>
                                            <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                                        </div>
                                        <div class="form-group col-2">
                                            <label for="product" style="font-weight: normal;">Product<span class="text-danger"><strong>*</strong></span></label><br>
                                                <select class="selectpicker"  data-live-search="true" aria-label="Default select example" name="product"
                                                id="product" data-width="100%">
                                                {{-- <option value="default" selected disabled>Select Product</option>
                                                @foreach($products as $product)
                                                <option value="{{ $product->productId }}">{{ $product->productName  }}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        <div class="form-group col-2">
                                            <label for="variant" style="font-weight: normal;">Variant<span class="text-danger"><strong>*</strong></span><span style="font-weight: normal;font-size: 14px; color: grey;"></span></label><br>
                                            <select class="selectpicker" data-live-search="true" data-width="100%" name="variant"
                                            id="variant">
                                            {{-- <option value="default" selected disabled>Select Variant</option>
                                            @foreach($variants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->variant_name }}</option>
                                            @endforeach --}}
                                            </select>
                                        <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                                        </div>
                                        <div class="form-group col-2">
                                            <label for="qty" style="font-weight: normal;">Qty<span class="text-danger"><strong>*</strong></span></label>
                                            <input type="text" class="form-control" placeholder="Enter quantity" id="qty" name="qty" >
                                        </div>
                                        <div class="form-group col-2" style="padding-top: 31px">
                                            <button id="add_btn" type="button" class=" w-30 btn btn-primary" onclick="productAddToTable()"><i class="fas fa-plus"></i> Add</button>
                                            <button id="reset_btn" type="reset" value="Reset" class=" w-30 btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
                                        </div>
				                	</div>
				                	<div class="row">
				                		<div class="col-1"></div>
				                		<div class="col-9">
				                			<h6 class="text-danger float-right mr-5 pr-5" ><strong id="errorMsg"></strong></h6>
				                		</div>
				                	</div>
			                	</div>
	                		</form>
	                	</div>

	                	<div class="row">
	                		<div class="col-12">
	                			<small id="" class="form-text text-muted">1. Please check your store or warehouse product Qty before transfering a product.</small>
	                			<small id="" class="form-text text-muted">2. If a product stock Qty is less then transfer Qty then it will not be transfered.</small>
	                			<small id="" class="form-text text-muted">3. If the destination store does not have this product then transfering product will be added to that store.</small>
	                		</div>
	                	</div>

	                	<div class="row pt-3">
	                		<div class="col-10">
	                			<table id="product_transfer_table" class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">From Store</th>
                                        <th scope="col" style="display: none;">FromStoreId</th>
                                        <th scope="col">To Store</th>
                                        <th scope="col" style="display: none;">ToStoreId</th>
                                        <th scope="col">Product</th>
                                        <th scope="col" style="display: none;">ProductId</th>
                                        <th scope="col">Variant</th>
                                        <th scope="col" style="display: none;">VariantId</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col" style="display: none;">Id</th>
                                        <th scope="col" style="display: none;">Type</th>
                                        <th scope="col">Identification Number</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="product_transfer_table_body">
                                    </tbody>
                                </table>
	                		</div>
	                	</div>
	                	<div class="row">
	                		<div class="col-1"></div>
	                		<div class="col-9">
	                			<h6 class="text-danger float-right" ><strong id="errorMsg1"></strong></h6>
	                		</div>
	                	</div>
	                	<div class="row">
	                		<div class="form-group col-10" style="padding-top: 10px">
                                <button id="" type="button" class=" w-30 btn btn-primary float-right" onclick="productTransferToServer()"><i class="fas fa-angle-double-right"></i> Product Transfer</button>
                            </div>
	                	</div>

		              </div> <!-- Card-body -->
		            </div>	<!-- Card -->

		        </div>   <!-- /.col-lg-6 -->
        	</div><!-- /.row -->

            <!-- Modal -->
            <div class="modal fade" id="serialModal" tabindex="-1" role="dialog" aria-labelledby="serialLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="serialLabel">Product Serial</h5>
                        <button type="button" class="close exit" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="container-fluid modal-body">
                            <div class="row">
                                <input type="hidden" class="form-control" name='row' id='row'>
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
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src="js/product-transfer.js"></script>
<script type="text/javascript">

</script>

@endsection



