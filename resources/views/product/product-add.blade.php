@extends('layouts.master')
@section('title', 'Create Product')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

@section('content')
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<!-- Header -->
			</div>
		</div>
	</div>

	<div class="content pt-4 ">
		<div class="container-fluid ">
		<form id="AddProductForm" method="POST" enctype="multipart/form-data">

			<div class="row">
      			<div class="col-lg-6">
          			
          			<div class="card card-primary card-outline ">
			            <div class="card-header">
			                <h5 class="m-0">Create Product</h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

									<div class="form-group">
										<label for="productname" class="form-label">Product Name</label>
									    <input type="text" class="form-control" name="productname" id="productname" placeholder="Enter product name ">
									</div>
									<div class="form-group">
										<label for="productlabel" class="form-label">Product Label*</label>
									    <input type="text" class="form-control" name="productlabel" id="productlabel" placeholder="Enter product label ">
									</div>
									<div class="form-group pt-1">
									    <label for="brandname" class="form-label">Brand Name*</label>
									    <select class="form-control selectpicker" data-live-search="true" aria-label="Default select example" name="brandname">
										  	<option value="option_select" disabled selected>Select Brand</option>
										  	@foreach($brands as $brand)
							            	<option value="{{ $brand->brand_name }}">
							            		{{ $brand->brand_name }}
							            	</option>
							        		@endforeach
										</select>
								  	</div>
									<div class="form-group pt-1">
									    <label for="categoryid" class="form-label">CategoryID</label>

									    <select class="form-control selectpicker" data-live-search="true" aria-label="Default select example" name="categoryid" id="categoryid">
										  	<option value="option_select" disabled selected>Select Category</option>
										  	@foreach($categories as $category)
							            	<option value="{{ $category->id }}">
							            		{{ $category->category_name }}
							            	</option>
							        		@endforeach
										</select>
								  	</div>

								  	<div class="form-group pt-1">
									    <label for="categoryname" class="form-label">Category Name*</label>

									    <select class="form-control" data-live-search="true" aria-label="Default select example" name="categoryname" id="categoryname">
										  	<option value="option_select" disabled selected>Select Category</option>
										  	@foreach($categories as $category)
							            	<option value="{{ $category->category_name }}">
							            		{{ $category->category_name }}
							            	</option>
							        		@endforeach
										</select>
								  	</div>

								  	<div class="form-group pt-1">
									    <label for="subcategoryname" class="form-label">Sub-category Name</label>

									    <select class="form-control"  name="subcategoryname" id="subcategoryname">
										  	<option value="option_select" disabled selected>Please Select Category First</option>
										</select>
								  	</div>

								  	<div class="form-group pt-1">
									    <label for="productsku" class="form-label">SKU</label>
									    <input type="text" class="form-control" name="productsku" id="productsku" placeholder="Enter SKU">
								  	</div>

								  	<div class="form-group pt-1">
									    <label for="productbarcode" class="form-label">Barcode</label>
									    <input type="text" class="form-control" name="productbarcode" id="productbarcode" placeholder="Enter barcode">
								  	</div>
								  	<div class="form-group pt-1">
									    <label for="suppliername" class="form-label">Supplier</label>
									    <select class="form-control selectpicker" data-live-search="true" aria-label="Default select example" name="suppliername">
										  	<option value="option_select" disabled selected>Select Supplier</option>
										  	@foreach($suppliers as $supplier)
							            	<option value="{{ $supplier->supplier_name }}">
							            		{{ $supplier->supplier_name }}
							            	</option>
							        		@endforeach
										</select>
								  	</div>
								  	<div class="form-group pt-1">
									    <label for="startingstock" class="form-label">Starting Stock*</label>
									    <input type="number" class="form-control" name="startingstock" id="startingstock" placeholder="Enter starting stock">
								  	</div>
								  	<div class="form-group pt-1">
									    <label for="safetystock" class="form-label">Safety Stock*</label>
									    <input type="number" class="form-control" name="safetystock" id="safetystock" placeholder="Enter safety stock">
								  	</div>


								  	<h3 class="">Variants</h3>

								  	<div class="form-group">
								  		<label for="color" class="form-label">Color</label>
									    <input type="text" class="form-control" name="color" id="color" placeholder="Enter color">
								  	</div>
								  	<div class="form-group pt-1">
								  		<label for="size" class="form-label">Size</label>
									    <input type="text" class="form-control" name="size" id="size" placeholder="Enter size">
								  	</div>

								</div> <!-- container -->
						</div> <!-- card-body -->
		          	</div> <!-- card card-primary card-outline -->
		        </div> <!-- col-lg-6 -->
		        <div class="col-lg-6">
		          	<div class="card card-primary card-outline ">
			            <div class="card-header">
			                <h5 class="m-0">Inventory</h5>
			            </div>

		              	<div class="card-body">
	          				<div class="container">

	          					

	          					<div class="form-group">
							  		<label for="productquantity" class="form-label">Quantity</label>
								    <input type="number" class="form-control" name="productquantity" id="productquantity" placeholder="Enter quantity">
							  	</div>
							  	<div class="form-group pt-1">
							  		<label for="sellingprice" class="form-label">Selling Price</label>
								    <input type="double" class="form-control" name="sellingprice" id="sellingprice" placeholder="Enter selling price">
							  	</div>
							  	<div class="form-group pt-1">
							  		<label for="unit" class="form-label">Unit</label>
								    <select class="form-control" data-live-search="true" aria-label="Default select example" name="unit">
									  	<option value="option_select" disabled selected>Select Unit</option>
						            	<option value="ft">ft</option>
						            	<option value="sft">sft</option>
						            	<option value="sq.m">sq.m</option>
						            	<option value="kg">kg</option>
						            	<option value="piece">piece</option>
						            	<option value="km">km</option>
						            	<option value="litre">litre</option>
						            	<option value="meter">meter</option>
						            	<option value="dozon">dozon</option>
						            	<option value="inch">inch</option>
						            	<option value="bosta">bosta</option>
						            	<option value="unit">unit</option>
						            	<option value="set">set</option>
									</select>
							  	</div>
							  	<div class="form-group pt-1">
							  		<label for="purchasecost" class="form-label">Purchase Cost</label>
								    <input type="double" class="form-control" name="purchasecost" id="purchasecost" placeholder="Enter purchase cost">
							  	</div>

							  	<div class="form-group pt-1">
							  		<label for="purchasedate" class="form-label">Purchase Date</label>
								    <input type="date" class="form-control" name="purchasedate" id="purchasedate">
							  	</div>


							  	<h3 class="">Others</h3>


							  	<div class="form-group pt-1">
								    <label for="discounttype" class="form-label">Discount Type*</label>

								    <select class="form-control" data-live-search="true" aria-label="Default select example" name="discounttype" id="discounttype">
									  	<option value="option_select" disabled selected>Select Discount Type</option>
									  	
						            	<option value="BDT">BDT</option>
						            	<option value="Percentage">Percentage</option>

									</select>
							  	</div>

							  	<div class="form-group pt-1">
							  		<label for="discount" class="form-label">Discount</label>
								    <input type="double" class="form-control" name="discount" id="discount" placeholder="Enter discount amount">
							  	</div>



							  	<div class="form-group pt-1">
							  		<label for="offeritemid" class="form-label">Offer Item Id</label>
								     <select class="form-control selectpicker" data-live-search="true" aria-label="Default select example" name="offeritemid">
									  	<option value="option_select" disabled selected>Select Free Item</option>
									  	@foreach($products as $product)
						            	<option value="{{ $product->id }}">
						            		{{ $product->productName }}
						            	</option>
						        		@endforeach
									</select>
							  	</div>

							  	<div class="form-group pt-1">
								    <label for="freeitemname" class="form-label">Free Item Name</label>
								    <select class="form-control selectpicker" data-live-search="true" aria-label="Default select example" name="freeitemname">
									  	<option value="option_select" disabled selected>Select Free Item</option>
									  	@foreach($products as $product)
						            	<option value="{{ $product->productName }}">
						            		{{ $product->productName }}
						            	</option>
						        		@endforeach
									</select>
							  	</div>

							  	<div class="form-group pt-1">
								    <label for="availableoffer" class="form-label">Offer Availability*</label>

								    <select class="form-control" data-live-search="true" aria-label="Default select example" name="availableoffer" id="availableoffer">
									  	<option value="option_select" disabled selected>Select</option>
						            	<option value="true">Yes</option>
						            	<option value="false">No</option>
									</select>
							  	</div>

							  	

							  	<div class="form-group pt-1">
							  		<label for="requiredquantity" class="form-label">Required Quantity</label>
								    <input type="number" class="form-control" name="requiredquantity" id="requiredquantity" placeholder="Enter required quantity">
							  	</div>

							  	<div class="form-group pt-1">
							  		<label for="freequantity" class="form-label">Free Quantity</label>
								    <input type="number" class="form-control" name="freequantity" id="freequantity" placeholder="Enter free quantity">
							  	</div>


							  	<div class="form-group">
							  		<label for="taxname" class="form-label">Tax Name</label>
								    <select class="form-control" data-live-search="true" aria-label="Default select example" name="taxname" id="taxname">
									  	<option value="option_select" disabled selected>Select Tax</option>
									  	@foreach($taxs as $tax)
						            	<option value="{{ $tax->taxName }}">
						            		{{ $tax->taxName }}
						            	</option>
						        		@endforeach
									</select>
							  	</div>


							  	<div class="form-group">
							  		<label for="tax" class="form-label">Tax</label>
								    <input type="double" class="form-control" name="tax" id="tax" placeholder="Tax">
							  	</div>

							  	<div class="form-group pt-1">
							  		<label for="storename" class="form-label">Store</label>
								    <select class="selectpicker form-control" data-live-search="true" aria-label="Default select example" name="storename[]" id="storename" multiple>
									  	
									  	@foreach($stores as $store)
						            	<option value="{{ $store->id }}">
						            		{{ $store->store_name }}
						            	</option>
						        		@endforeach
									</select>
							  	</div>

							  	<div lass="form-group pt-1">
							  		<label for="productdesc" class="form-label">Description</label>
							  		<textarea class="form-control" name="productdesc" id="productdesc"  rows="2" placeholder="Enter produt description"></textarea>
							  	</div>

							  	<div class="form-group pt-1">
							  		<label for="productimage" class="form-label">Image</label>
								    <input type="file" class="form-control" name="productimage" id="productimage">
							  	</div>

							  	<div class="form-group pt-1">
								  	<button type="submit" class="btn btn-outline-primary">Create</button>
									<button type="reset" value="Reset" class="btn btn-primary">Reset</button>
							  	</div>
	          				</div> <!-- container -->
						</div> <!-- card-body -->
		          	</div> <!-- card card-primary card-outline -->
				</div><!-- col-lg-6 -->	
			</div> <!-- row -->

			

		</form>
      				
		</div> <!-- container-fluid -->
	</div> <!-- content -->
	
</div> <!-- content-wrapper -->

@endsection

@section('script')
<script type="text/javascript" src=""></script>

<script type="text/javascript">
	$(document).on('change', '#categoryid', function (e) {
		e.preventDefault();

		// alert($('#categoryid').val());

		var categoryId = $(this).val();
		// alert(categoryId);
				
				$.ajax({
					type: "GET",
					url: "/product-create/"+categoryId,
					success: function(response){
						// console.log(response.subcategory);

						$('select[name="subcategoryname"]').empty();
					    $('select[name="subcategoryname"]').append('<option value=""> -- Please Select -- </option>');
					    $.each(response.subcategory, function(key, item){ 
					         $('select[name="subcategoryname"]')
					         .append('<option value="'+ item.subcategory_name +'">'+ item.subcategory_name +'</option>');
					    });
					}
				});
		});

	$(document).on('change', '#categoryid', function (e) {
		e.preventDefault();

		// alert($('#categoryid').val());

		var categoryId = $(this).val();
		// alert(categoryId);
				
				$.ajax({
					type: "GET",
					url: "/product-create/"+categoryId,
					success: function(response){
						// console.log(response.subcategory);

						$('select[name="categoryname"]').empty();
					    $('select[name="categoryname"]').append('<option value="'+response.category+'"> '+response.category+' </option>');
					    // $.each(response.category, function(key, item){ 
					    //      $('select[name="categoryname"]')
					    //      .append('<option value="'+ item.category_name +'">'+ item.category_name +'</option>');
					    // });

						
					}
				});
		});

	$(document).on('change', '#taxname', function (e) {
		e.preventDefault();

		var taxId = $(this).val();
		// alert(taxId);
				
				$.ajax({
					type: "GET",
					url: "/product-create-tax/"+taxId,
					success: function(response){
					// console.log(response.tax[0].vatType);
					    
					// $('select[name="taxname"]').empty();
				 	//    $('select[name="taxname"]').append('<option value="'+response.tax[0].vatName+'"> '+response.tax[0].vatName+' </option>');

					$('#tax').val(response.tax[0].taxAmount);
					// console.log(response.tax[0].taxAmount);
					}
				});
		});

	// $(document).on('change', '#discountid', function (e) {
	// 	e.preventDefault();

	// 	var discountId = $(this).val();
	// 	alert(discountId);
				
	// 			$.ajax({
	// 				type: "GET",
	// 				url: "/product-discount/"+discountId,
	// 				success: function(response){
						

	// 					$('select[name="discount"]').empty();
	// 				    $('select[name="discount"]').append('<input value="'+response.discount.discount+'" placeholder="'+response.discount.discount+'"/> ');
						
	// 				}
	// 			});
	// 	});

	$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE PRODUCT
	$(document).on('submit', '#AddProductForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddProductForm')[0]);

		$.ajax({
			type: "POST",
			url: "/product-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				console.log(response.message);	
				// $(location).attr('href','/product-list');
			}
		});

	});

});

	
</script>

@endsection