@extends('layouts.master')
@section('title', 'Create Product Stock')

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

          			<div class="card card-dark">
			            <div class="card-header">
			                <h5 class="m-0"><strong>Store STOCK</strong> <i class="fas fa-store float-right"></i></h5>
			            </div>

		              	<div class="card-body" id="form_div">

	          					<div class="row">
	          						<div class="col-12">
	          							<div class="pt-2">
	          								@isset($productId)
	          								<input type="hidden" name="productid" id="productid" value="{{$productId}}">
	          								<input type="hidden" name="productname" id="productname" value="{{$productName}}">
	          								<h4 class="mb-3">{{$productName}}</h4>
	          								@endisset
											<table id="store_table" class="table table-bordered mb-0">
											    <thead>
											        <tr>
										        		<th width="20%"><label for="store" class="form-label">Store<span class="text-danger"><strong>*</strong></span></label></th>
										        		<th width="20%"><label for="variant" class="form-label">Variant</label></th>
											            <th width="10%"><label for="qty" class="form-label">Qty</label></th>
											            <th width="15%"><label for="purchasecost" class="form-label">Purchase Cost</label></th>
											            <th width="15%"><label for="price" class="form-label">Selling Price</label></th>
											            <th width="10%"><label for="safetystock" class="form-label">Safety Stock</label></th>
											            <th width="5%"></th>
											        </tr>
											    </thead>
											    <tbody>
											    	<tr>
											    		<td>
												    		<select style="width:70%" class="selectpicker" data-width="100%" name="store"
														      id="store" title="Select Store" data-live-search="true">
                                                              <option value="warehouse">Warehouse</option>
														      @foreach($stores as $store)
												            	<option value="{{ $store->id }}">{{ $store->store_name }}</option>
												        		@endforeach
														    </select>
												    	</td>
												    	<td>
												    		<select style="width:70%" class="selectpicker" data-width="100%" name="variant"
														      id="variant" disabled>
														      @foreach($variants as $variant)
												            	<option value="{{ $variant->id }}" selected>{{ $variant->variant_name }}</option>
												        		@endforeach
														    </select>
												    	</td>
												    	<td>
												    		<input style="width:100%" class="form-control" type="number" name="qty" id="qty" placeholder="e.g. 10">
												    	</td>
												    	<td>
												    		<input style="width:100%" class="form-control" type="number" name="purchasecost" id="purchasecost" placeholder="e.g. 100000">
												    	</td>
												    	<td>
												    		<input style="width:100%" class="form-control" type="number" name="price" id="price" placeholder="e.g. 110000">
												    	</td>
												    	<td>
												    		<input style="width:100%" class="form-control" type="number" name="safetystock" id="safetystock" placeholder="e.g. 2">
												    	</td>
												    	<td>
												    		<button type="button" onclick="addStore();" id="addStore" class="ml-2 btn btn-outline-success float-right">
														  		<i class="fas fa-plus"></i>
														  	</button>
												    	</td>
											    	</tr>

											    </tbody>
											    <tfoot>

											  	</tfoot>
										    </table>
										    <table id="store_table_data" class="table table-bordered mt-0">
										    	<thead>

										    	</thead>
										    	<tbody id="store_table_data_body">

										    	</tbody>
										    </table>
										</div>
	          						</div>
	          					</div>
	          				 	<div class="row mb-3">
									<div class="col-12">
										<button class="skip_btn btn btn-info float-left" type="button" name="">SKIP</button>
										<div class="float-right">
										  <button class="btn btn-outline-danger " type="reset" name="" onclick="resetButton();">Reset</button>
										  <button id="save" type="button" onclick="collectData();" class="btn btn-primary"  name="">SAVE</button>
									  	</div>
									</div>
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
<script type="text/javascript" src="{{ asset('js/product-stock.js') }}"></script>


<script type="text/javascript">

$(document).on('click', '.skip_btn', function (e) {
	$(location).attr('href','/product-list');
});

</script>

@endsection
