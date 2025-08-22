@extends('layouts.master')
@section('title', 'Create Product')

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
			<div class="row" id="form_div">
				<form id="AddProductForm" method="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="row">
		      			<div class="col-lg-8">

		          			<div class="card card-info">
					            <div class="card-header">
					                <h5 class="m-0"><strong>INFO</strong> <i class="fas fa-info-circle float-right"></i></h5>
					            </div>

				              	<div class="card-body">
			          				<div class="container">

			          					<div class="row">  <!-- --------------------------row--------------------------- -->
											<div class="col-4">
												<div class="mb-3">
												    <label for="productname" class="form-label" style="font-weight: normal;">Product Name<span class="text-danger"><strong>*</strong></span></label>
												    <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="text" name="productname" id="productname" placeholder="Product Name">
									    			<h6 class="text-danger pt-1" id="wrongproductname" style="font-size: 14px;"></h6>

											  	</div>
											</div>

											{{-- <div class="col-4">
												<div class="mb-3">
												    <label for="productlabel" class="form-label" style="font-weight: normal;">Product Label<span class="text-danger"><strong>*</strong></span></label>
												    <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="text" name="productlabel" id="productlabel" placeholder="Product Label">
												    <h6 class="text-danger pt-1" id="wrongproductlabel" style="font-size: 14px;"></h6>
												</div>
											</div> --}}
											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="categoryid" class="form-label" style="font-weight: normal;">Category<span class="text-danger"><strong>*</strong></span></label><br>
													    <select style="width:70%" class="selectpicker" data-width="100%" name="categoryid"
													      id="categoryid" data-live-search="true" title="Select category">
													      <option value="" disabled>Select Category</option>
													      	@foreach($categories as $category)
												            	<option value="{{ $category->id }}">{{ $category->category_name }}</option>
											        		@endforeach
													    </select>
													    <h6 class="text-danger pt-1" id="wrongcategoryid" style="font-size: 14px;"></h6>
												  	</div>
												</div>
											</div>
											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="subcategoryname" class="form-label" style="font-weight: normal;">Sub-Category</label><br>
													    <select style="width:70%" class="selectpicker" name="subcategoryname"
													      id="subcategoryname" data-live-search="true" title="Select subcategory" data-width="100%">
													      	<option value="" disabled selected>Select Subcategory</option>
													    </select>
													</div>
												</div>
											</div>
							      		</div>

							      		<div class="row pt-3"> <!-- --------------------------row--------------------------- -->
											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="brandname" class="form-label" style="font-weight: normal;">Brand<span class="text-danger"><strong>*</strong></span></label><br>
													    <select style="width:70%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="brandname" id="brandname" data-live-search="true" title="Select brand" data-width="100%">
													      <option value="" disabled>Select Brand</option>
                                                          @foreach($brands as $brand)
											            	<option value="{{ $brand->brand_name }}">{{ $brand->brand_name }}</option>
											        		@endforeach
													    </select>
													    <h6 class="text-danger pt-1" id="wrongbrandname" style="font-size: 14px;"></h6>
													</div>
												</div>
											</div>
                                            <div class="col-4">
                                                <div class="col-9">
                                                    <div class="mb-3">
                                                        <label for="modelname" class="form-label" style="font-weight: normal;">
                                                            Model
                                                        </label><br>
                                                        <select style="width:70%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="modelname" id="modelname" data-live-search="true" title="Select model" data-width="100%">
                                                            <option value="" disabled>Select Model</option>
                                                            @foreach($models as $model)
                                                                <option value="{{ $model->model_name }}">{{ $model->model_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <h6 class="text-danger pt-1" id="wrongmodelname" style="font-size: 14px;"></h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="col-9">
                                                    <div class="mb-3">
                                                        <label for="manufacturername" class="form-label" style="font-weight: normal;">
                                                            Manufacturer
                                                        </label><br>
                                                        <select style="width:70%" class="selectpicker border-left-0 border-right-0 border-top-0 rounded-0" name="manufacturername" id="manufacturername" data-live-search="true" title="Select manufacturer" data-width="100%">
                                                            <option value="" disabled>Select Manufacturer</option>
                                                            @foreach($manufacturers as $manufacturer)
                                                                <option value="{{ $manufacturer->manufacturer_name }}">{{ $manufacturer->manufacturer_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <h6 class="text-danger pt-1" id="wrongmanufacturername" style="font-size: 14px;"></h6>
                                                    </div>
                                                </div>
                                            </div>
							      		</div>

							      		<div class="row pt-3"> <!-- --------------------------row--------------------------- -->
                                            <div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="type" class="form-label" style="font-weight: normal;">Type<span class="text-danger"><strong>*</strong></span></label><br>
													    <select style="width:70%" class="selectpicker" name="type" id="type" data-live-search="true" title="Select Type" data-width="100%">
													      	<option value="" disabled selected>Select Type</option>
													      	<option value="Serialize">Serialize</option>
													      	<option value="Non-Serialize">Non-Serialize</option>
													    </select>
													    <h6 class="text-danger pt-1" id="wrongtype" style="font-size: 14px;"></h6>
													</div>
												</div>
											</div>
											<div class="col-4">
												<div class="mb-3">
												    <label for="productsku" class="form-label" style="font-weight: normal;">SKU</label>
												    <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="text" name="productsku" id="productsku" placeholder="e.g. UGG-BB-PUR-06">
											  	</div>
											</div>

											{{-- <div class="col-4">
												<div class="mb-3">
												    <label for="productbarcode" class="form-label" style="font-weight: normal;">Barcode</label>
												    <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="text" name="productbarcode" id="productbarcode" placeholder="e.g. 01234 56789123">
												</div>
											</div>

											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="suppliername" class="form-label" style="font-weight: normal;">Supplier</label><br>
													    <select style="width:70%" class="selectpicker" data-width="100%" name="suppliername" id="suppliername" data-live-search="true" title="Select product supplier">
													    	<option value=""disabled>Select supplier</option>
													      @foreach($suppliers as $supplier)
											            	<option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
											        		@endforeach
													    </select>
													</div>
												</div>

											</div> --}}
							      		</div>
							      		<div class="row">
							      			<div class="col-4">
							      				<!-- <label for="imagefile" class="form-label" style="font-weight: normal;">Image</label>
											    <input id="input-b1" name="imagefile" type="file" class="file productimage" data-browse-on-zone-click="true"> -->
									   			<!-- <div class="input-group mb-3">
									   				<span class="btn btn-primary input-group-text" id="">Browse</span>
										   			<input type="text" class="form-control" placeholder="Select product image" aria-label="Select product image" aria-describedby="basic-addon2" name="productimage" id="productimage">
		  										</div> -->
		  										<!-- <div id="" class="form-text">N.B. Image can be helpful to identify your product easily.</div> -->
							      			</div>
							      		</div>
			          				</div> <!-- container -->
								</div> <!-- card-body -->
				          	</div> <!-- card card-primary card-outline -->
						</div><!-- col-lg-8 -->

					</div> <!-- row -->



					<div class="row">
		      			<div class="col-lg-8">
		          			<br>
		          			<div class="card card-info">
					            <div class="card-header">
					                <h5 class="m-0"><strong>VARIANTS </strong><i class="fas fa-palette float-right"></i></h5>
					            </div>


				              	<div class="card-body">
			          				<div class="container">
			          					<div class="row">
			          						<div class="col-12">
			          							<div class="pt-2">
													<table id="variant_table" class="table table-borderless">
													    <thead>
													        <tr>
												        		<th width="16%"><label for="variantname" class="form-label" style="font-weight: normal;">Variant Name</label></th>
												        		<th width="20%"><label for="measurement" class="form-label" style="font-weight: normal;">Measurement<span class="text-danger"><strong>*</strong></span></label></th>
													            <th width="25%"><label for="variantdescription" class="form-label" style="font-weight: normal;">Variant Description</label></th>
													            {{-- <th width="25%"><label for="variantimage" class="form-label" style="font-weight: normal;">Image</label></th> --}}
													            <th width="4%"></th>
													        </tr>
													    </thead>
													    <tbody>
													    	<tr>
													    		<td>
														    		<input style="width:100%" class="form-control" type="text" name="variantname" id="variantname" placeholder="Variant Name">
														    	</td>
														    	<td>
                                                                    <select style="width:70%" class="selectpicker" data-width="100%" name="measurement" id="measurement" data-live-search="true" title="Select product measurement">
													    	            <option value="" disabled>Select measurement</option>
													                     @foreach($units as $unit)
											            	            <option value="{{ $unit->name }}">{{ $unit->name }}</option>
											        		            @endforeach
													                </select>
                                                                    <h6 class="text-danger pt-1" id="wrongmeasurement" style="font-size: 14px;"></h6>
														    		{{-- <select style="width:70%" class="selectpicker" data-width="100%" data-live-search="true" title="Select product measurement" name="measurement" id="measurement">
														            	<option value="ft">ft</option>
														            	<option value="sft">sft</option>
														            	<option value="sq.m">sq.m</option>
														            	<option value="kg">kg</option>
														            	<option value="piece">pc.</option>
														            	<option value="km">km</option>
														            	<option value="litre">litre</option>
														            	<option value="meter">meter</option>
														            	<option value="dozon">dozon</option>
														            	<option value="inch">inch</option>
														            	<option value="bosta">bosta</option>
														            	<option value="unit">unit</option>
														            	<option value="set">set</option>
														            	<option value="ml">ml</option>
														            	<option value="mg">mg</option>
																	</select> --}}
														    	</td>
														    	<td>
														    		<textarea class="form-control" id="variantdescription" name="variantdescription" rows="1" placeholder="Variant Description"></textarea>
														    	</td>
														    	{{-- <td>
														    		<input id="image" name="variantimage" type="file" class="form-control" data-browse-on-zone-click="true">
														    	</td> --}}
														    	<td>
														    		<button type="button" onclick="addVariant();" id="addCredit" class="ml-2 btn btn-outline-success float-right">
																  		<i class="fas fa-plus"></i>
																  	</button>
														    	</td>
													    	</tr>

													    </tbody>
													    <tfoot>

													  	</tfoot>
												    </table>
												    <table id="variant_table_data" class="table table-bordered">
												    	<thead>

												    	</thead>
												    	<tbody id="variant_table_data_body">

												    	</tbody>
												    </table>
												</div>
			          						</div>
			          					</div>

			          				</div> <!-- container -->
								</div> <!-- card-body -->
				          	</div> <!-- card card-primary card-outline -->
						</div><!-- col-lg-6 -->
					</div> <!-- row -->

					{{-- <div class="row">
		      			<div class="col-lg-8">
		          			<br>
		          			<div class="card card-secondary  ">
					            <div class="card-header">
					                <h5 class="m-0"><strong>OTHERS </strong><span>(optional)</span> <i class="fas fa-percent float-right"></i></h5>
					            </div>

				              	<div class="card-body">
			          				<div class="container">

                                        <div class="row pt-3">  <!-- --------------------------row--------------------------- -->
                                            <div class="col-4">
                                                <div class="col-9">
                                                    <div class="mb-3">
                                                      <label for="select" class="form-label" style="font-weight: normal;">Variant Name</label><br>
                                                      <select style="width:70%" class="selectpicker" data-width="100%" name="selectVariant"
                                                        id="selectVariant" title="Select Variant" disabled>
                                                        <option value="" disabled>Select Variant</option>
                                                        <option value="All Variant">All Variant</option>
                                                      </select>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="col-6"></div>
                                            <div class="col-2 mt-3">
                                                <button type="button" onclick="addDiscountTax()" class="btn mr-3 btn-outline-success float-right">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row pt-3">  <!-- --------------------------row--------------------------- -->

			          						<div class="col-4">
			          							<div class="col-9">
			          								<div class="mb-3">
													    <label for="availablediscount" class="form-label" style="font-weight: normal;">Discount Availability</label><br>
													    <select style="width:70%" class="selectpicker" data-width="100%" name="availablediscount"
													      id="availablediscount" title="Discount availability"disabled>
													      <option value="" disabled>Select Availablility</option>
													      <option value="true">Yes</option>
									            		  <option value="false">No</option>
													    </select>
													</div>
			          							</div>

											</div>

											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="discounttype" class="form-label" style="font-weight: normal;">Discount Type</label><br>
													    <select style="width:70%" class="selectpicker" data-width="100%" name="discounttype"
													      id="discounttype" title="Discount type" disabled>
													      <option value="" disabled>Select Type</option>
													      <option value="BDT">BDT</option>
									            		  <option value="Percentage">Percentage</option>
													    </select>
													</div>
												</div>

											</div>

											<div class="col-4">
												<div class="mb-3">
												    <label for="discount" class="form-label" style="font-weight: normal;">Discount</label>
												    <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="number" step="any" min="0.1" name="discount" id="discount" placeholder="e.g. 50" disabled>
												</div>
											</div>
							      		</div>

							      		<div class="row pt-3">  <!-- --------------------------row--------------------------- -->

											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="availableoffer" class="form-label" style="font-weight: normal;">Offer Availability</label><br>
													    <select style="width:70%" class="selectpicker" data-width="100%" name="availableoffer"
													      id="availableoffer" title="Offer availability">
													      	<option value="">Select availability</option>
													      	<option value="true">Yes</option>
									            			<option value="false">No</option>
													    </select>
													</div>
												</div>

											</div>

											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="offeritemid" class="form-label" style="font-weight: normal;">Offer Item</label><br>
													    <select style="width:70%" class="selectpicker" data-width="100%" name="offeritemid"
													      id="offeritemid" title="Offer product" data-live-search="true">
													      <option value="">Select item</option>
													      	@foreach($products as $product)
											            	<option value="{{ $product->id }}">{{ $product->productName }} ({{$product->variant_name}})</option>
										        			@endforeach
													    </select>
													</div>
												</div>

											</div>
							      		</div>

							      		<div class="row pt-3">  <!-- --------------------------row--------------------------- -->

											<div class="col-4">
												<div class="mb-3">
												    <label for="requiredquantity" class="form-label" style="font-weight: normal;">Required Quantity</label>
												    <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="number" step="any" min="0.1" name="requiredquantity" id="requiredquantity" placeholder="required qunatity e.g. 2">
												</div>
											</div>

											<div class="col-4">
												<div class="mb-3">
												    <label for="freequantity" class="form-label" style="font-weight: normal;">Free Quantity</label>
												    <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="number" step="any" min="0.1" name="freequantity" id="freequantity" placeholder="free offer qunatity e.g. 1">
												</div>
											</div>
							      		</div>

							      		<div class="row pt-3">  <!-- --------------------------row--------------------------- -->
											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="taxname" class="form-label" style="font-weight: normal;">Tax Name</label><br>
													    <select style="width:70%" class="selectpicker" data-width="100%" name="taxname"
													      id="taxname" title="Product Tax" data-live-search="true"disabled>
													      <option value="" disabled>Select tax</option>
													      @foreach($tax as $tax)
											            	<option value="{{ $tax->taxName }}">{{ $tax->taxName }}</option>
											        		@endforeach
													    </select>
													</div>
												</div>
											</div>

											<div class="col-4">
												<div class="mb-3">
												    <label for="tax" class="form-label" style="font-weight: normal;">Tax</label>
												    <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="number" step="any" min="0.1" name="tax" id="tax" placeholder="tax amount e.g. 17.5" disabled>
												</div>
											</div>

											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="taxexcluded" class="form-label" style="font-weight: normal;">Is Tax Excluded</label><br>
													    <select style="width:70%" data-width="100%" class="selectpicker"  aria-label="Default select example" name="taxexcluded" id="taxexcluded" title="Is tax excluded" disabled>
														  	<option value=""disabled>Select</option>
											            	<option value="true">Yes</option>
											            	<option value="false">No</option>
														</select>
													</div>
												</div>

											</div>
							      		</div>

                                        <div>
                                            <table id="variant_taxDiscount_table_data" class="table table-bordered" hidden>
                                                <thead>
                                                    <th>Variant</th>
                                                    <th>Discount Availability</th>
                                                    <th>Discount Type</th>
                                                    <th>Discount Amount</th>
                                                    <th>Tax Name</th>
                                                    <th>Tax Amount</th>
                                                    <th>Is Tax Exclude</th>
                                                    <th>Action</th>
                                                </thead>
                                                <tbody id="variant_taxDiscount_table_data_body">

                                                </tbody>
                                            </table>
                                        </div>

			          				</div> <!-- container -->
								</div> <!-- card-body -->
				          	</div> <!-- card card-primary card-outline -->
						</div><!-- col-lg-6 -->
					</div> --}}

					<div class="row mb-3">
						<div class="col-8">
							<button class="cancel_btn btn btn-secondary btn-lg float-left" type="button" name="">CANCEL</button>
							<div class="float-right">
							  <button class="btn btn-outline-danger btn-lg " type="reset" name="" onclick="resetButton()">Reset</button>
							  <button id="save" type="submit" onclick="productSubmitToServer();" class="btn btn-primary btn-lg"  name="">SAVE</button>
						  	</div>
						</div>
					</div>

				</form>
			</div>
    	</div>
	</div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="ImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
      </div>


      <!-- Image Form -->
      <form id="ImageFORM" enctype="multipart/form-data">

      	<!-- <input type="hidden" name="_method" value="PUT"> -->
			<input type="hidden" name="_token" value="{{ csrf_token() }}">

      	<div class="modal-body">
      		<div class="form-group mb-3">
	   			<input id="input-b1" name="imagefile" type="file" class="file" data-browse-on-zone-click="true">
      		</div>
	    </div>

	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary">Save</button>
	    </div>
      </form>
      <!-- End Form -->

    </div>
  </div>
</div>
<!-- End Modal -->



@endsection

@section('script')
<script type="text/javascript" src="js/product-new.js"></script>


<script type="text/javascript">

$(document).ready(function() {
    $('#select-all').click(function() {
        $('input[type="checkbox"]').prop('checked', this.checked);
    })
});


$(document).on('change', '#categoryid', function (e) {
		e.preventDefault();

	var categoryId = $(this).val();

	$.ajax({
		type: "GET",
		url: "/product-create/"+categoryId,
		success: function(response){
			// console.log(response.subcategory);

			$('select[name="subcategoryname"]').empty();
		    $('select[name="subcategoryname"]').append('<option value="" disabled>Select subcategory</option>');
		    $.each(response.subcategory, function(key, item){
		         $('select[name="subcategoryname"]').append('<option value="'+ item.id +'">'+ item.subcategory_name +'</option>');
		    });

		    $('#subcategoryname').appendTo('#subcategoryname').selectpicker('refresh');
		}
	});
});
$('#selectVariant').change(function (e) {
    e.preventDefault();
    $('#availablediscount').prop('disabled', false);
    $('#availablediscount').selectpicker('refresh');
    $('#taxname').prop('disabled', false);
    $('#taxname').selectpicker('refresh');
});
$(document).on('change', '#availablediscount', function(){
    $('#discounttype').prop('disabled', false);
    $('#discounttype').selectpicker('refresh');
})
$('#discounttype').change(function (e) {
    e.preventDefault();
    $('#discount').prop('disabled', false);
});

$(document).on('change', '#taxname', function (e) {
	e.preventDefault();

	var taxId = $(this).val();

	$.ajax({
		type: "GET",
		url: "/product-create-tax/"+taxId,
		success: function(response){
			$('#tax').val(response.tax[0].taxAmount);

			if(response.tax[0].vatType == "included"){
				// alert('false')
				$("#taxexcluded").val('false').change();;
			}else{
				// alert('true')
				$("#taxexcluded").val('true').change();;
			}
		}
	});
});


$(document).on('click', '.cancel_btn', function (e) {
	$(location).attr('href','/product-list');
});

</script>

@endsection
