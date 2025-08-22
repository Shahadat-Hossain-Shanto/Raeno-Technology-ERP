@extends('layouts.master')
@section('title', 'Edit Product')

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
				<form id="EditProductForm" method="" enctype="multipart/form-data">
				{{ csrf_field() }}
					<input type="hidden" name="_method" value="PUT">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

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
												    <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="text" name="productname" id="edit_productname">
									    			<h6 class="text-danger pt-1" id="edit_wrongproductname" style="font-size: 14px;"></h6>

											  	</div>
											</div>

											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="categoryid" class="form-label" style="font-weight: normal;">Category<span class="text-danger"><strong>*</strong></span></label><br>
													    <select style="width:70%" class="selectpicker" data-width="100%" data-live-search="true" name="categoryid"
													      id="edit_categoryid">
													      <option value="option_select" disabled selected>Select category</option>
													      	@foreach($categories as $category)
												            	<option value="{{ $category->id }}">{{ $category->category_name }}</option>
											        		@endforeach
													    </select>
													    <h6 class="text-danger pt-1" id="edit_wrongcategoryid" style="font-size: 14px;"></h6>
												  	</div>
												</div>

											</div>

											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="subcategoryname" class="form-label" style="font-weight: normal;">Sub-Category</label><br>
													    <select style="width:70%" class="selectpicker" data-width="100%" name="subcategoryname"
													      id="edit_subcategoryname" data-live-search="true">
													      <option value="" disabled selected>Select subcategory</option>
                                                            @foreach($subcategories as $subcategorie)
                                                                <option value="{{ $subcategorie->id }}">{{ $subcategorie->subcategory_name }}</option>
                                                            @endforeach
													    </select>
													</div>
												</div>

											</div>
							      		</div>

							      		<div class="row pt-3"> <!-- --------------------------row--------------------------- -->

											<div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="productbrand" class="form-label" style="font-weight: normal;">Brand<span class="text-danger"><strong>*</strong></span></label><br>
													    <select style="width:70%" class="selectpicker" data-width="100%" data-width="100%" data-live-search="true" name="productbrand"
													      id="edit_productbrand">
													      <option value="option_select" disabled selected>Select Brand</option>
													      	@foreach($brands as $brand)
											            	<option value="{{ $brand->brand_name }}">{{ $brand->brand_name }}</option>
											        		@endforeach
													    </select>
													    <h6 class="text-danger pt-1" id="edit_wrongbrandname" style="font-size: 14px;"></h6>
													</div>
												</div>

											</div>
                                            <div class="col-4">
                                                <div class="col-9">
                                                    <div class="mb-3">
                                                        <label for="productmodel" class="form-label" style="font-weight: normal;">Model</label><br>
                                                        <select style="width:70%" class="selectpicker" data-width="100%" data-live-search="true" name="productmodel"
                                                            id="edit_productmodel">
                                                            <option value="option_select" disabled selected>Select Model</option>
                                                            @foreach($models as $model)
                                                                <option value="{{ $model->model_name }}">{{ $model->model_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <h6 class="text-danger pt-1" id="edit_wrongmodelname" style="font-size: 14px;"></h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="col-9">
                                                    <div class="mb-3">
                                                        <label for="productmanufacturer" class="form-label" style="font-weight: normal;">Manufacturer</label><br>
                                                        <select style="width:70%" class="selectpicker" data-width="100%" data-live-search="true" name="productmanufacturer"
                                                            id="edit_productmanufacturer">
                                                            <option value="option_select" disabled selected>Select Manufacturer</option>
                                                            @foreach($manufacturers as $manufacturer)
                                                                <option value="{{ $manufacturer->manufacturer_name }}">{{ $manufacturer->manufacturer_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <h6 class="text-danger pt-1" id="edit_wrongmanufacturername" style="font-size: 14px;"></h6>
                                                    </div>
                                                </div>
                                            </div>
							      		</div>

							      		<div class="row pt-3"> <!-- --------------------------row--------------------------- -->

                                            <div class="col-4">
												<div class="col-9">
													<div class="mb-3">
													    <label for="edit_type" class="form-label" style="font-weight: normal;">Type<span class="text-danger"><strong>*</strong></span></label><br>
													    <select style="width:70%" class="selectpicker" name="type" id="edit_type" data-live-search="true" title="Select Type" data-width="100%">
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
												    <label for="sku" class="form-label" style="font-weight: normal;">SKU</label>
												    <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="text" name="sku" id="edit_sku" placeholder="e.g. UGG-BB-PUR-06">
											  	</div>
											</div>

							      		</div>
							      		<div class="row">
							      			<div class="col-4">

							      			</div>

									    	<div class="col-8">
									    		<div class="pt-5 pl-5">
									    			<!-- <img id="edit_defaultimage" src="" width="150px" height="150px" alt="Image" class=""> -->
									    		</div>
									    	</div>

							      		</div>




			          				</div> <!-- container -->
								</div> <!-- card-body -->
				          	</div> <!-- card card-primary card-outline -->
						</div><!-- col-lg-6 -->
					</div> <!-- row -->

					<div class="row pb-3">
						<div class="col-8">

								<button class="cancel_btn btn btn-secondary btn-lg" type="button" name="">CANCEL</button>
						  	<div class="float-right">
				          		<button class="delete_btn btn btn-outline-danger btn-lg" type="button" onclick="deleteProduct();" name="">DELETE</button>
							  	<button class="btn btn-primary btn-lg" type="submit" onclick="" name="">SAVE</button>

						  	</div>
						</div>
					</div>

			        	<input type="hidden" name="productid" id="productid" value="{{ $p }}">
			        	{{-- <input type="hidden" name="variantid" id="variantid" value="{{ $v }}"> --}}

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
      <form id="" enctype="multipart/form-data">

      	<!-- <input type="hidden" name="_method" value="PUT"> -->
		<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->

      	<div class="modal-body">
      		<div class="form-group mb-3">
	   			<input id="input-b1" name="imagefile" type="file" class="file" data-browse-on-zone-click="true">
      		</div>
	    </div>

	    <div class="modal-footer">
	        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <!-- <button type="submit" class="delete btn btn-primary">Submit</button> -->
	    </div>
      </form>
      <!-- End Form -->

    </div>
  </div>
</div>
<!-- End Modal -->

<!-- Delete Modal -->

<div class="modal fade" id="DELETEProductMODAL" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">

			<form id="DELETEProductFORM" method="POST" enctype="multipart/form-data">

					{{ csrf_field() }}
					{{ method_field('DELETE') }}


			    <div class="modal-body">
			    	<input type="hidden" name="" id="productidDelete">
			      <h5 class="text-center">Are you sure you want to delete?</h5>
			    </div>

			    <div class="modal-footer justify-content-center">
			        <button type="button" class="cancel_delete_btn btn btn-secondary" data-dismiss="modal">Cancel</button>
			        <button type="submit" class="delete btn btn-danger">Yes</button>
			    </div>

			</form>

		</div>
	</div>
</div>

<!-- END Delete Modal -->


@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/product-edit.js') }}"></script>


<script type="text/javascript">

$(document).ready(function() {

        var productid = $('#productid').val();
        fetchProduct(productid);
    $('#select-all').click(function() {
        $('input[type="checkbox"]').prop('checked', this.checked);
    })
});

	$(document).on('change', '#edit_categoryid', function (e) {
		e.preventDefault();

		// alert($('#categoryid').val());

		var categoryId = $(this).val();
		// alert(categoryId);

				$.ajax({
					type: "GET",
					url: "/product-edit-subcategory/"+categoryId,
					success: function(response){
						// console.log(response.subcategory);

						$('select[name="subcategoryname"]').empty();
					    $('select[name="subcategoryname"]').append('<option value="">Select sub-category</option>');
					    $.each(response.subcategory, function(key, item){
					         $('select[name="subcategoryname"]')
					         .append('<option value="'+ item.id +'">'+ item.subcategory_name +'</option>');
					    });
					    $('#edit_subcategoryname').appendTo('#edit_subcategoryname').selectpicker('refresh');
					}
				});
		});

$(document).on('change', '#edit_taxname', function (e) {
	e.preventDefault();

	var taxId = $(this).val();
	// alert(taxId);

			$.ajax({
				type: "GET",
				url: "/product-create-tax/"+taxId,
				success: function(response){
				// console.log(response);

				// $('select[name="taxname"]').empty();
			 	//    $('select[name="taxname"]').append('<option value="'+response.tax[0].vatName+'"> '+response.tax[0].vatName+' </option>');

				$('#edit_tax').val(response.tax[0].taxAmount);
                if(response.tax[0].vatType=='included')
                {
                    $('#edit_taxexcluded').val('false').change();
                }
                else{
                    $('#edit_taxexcluded').val('true').change();
                }
				// console.log(response.tax[0].taxAmount);
				}
			});
	});




	$('.cancel_btn').on('click', function(e){
		e.preventDefault();
		$(location).attr('href','/product-list');
	})

	$(document).on('click', '.cancel_delete_btn', function (e) {
		$('#DELETEProductMODAL').modal('hide');
	});

</script>

@endsection
