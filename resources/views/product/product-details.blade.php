@extends('layouts.master')
@section('title', 'Product Details')

@section('content')
<div class="content-wrapper">
	<div class="content-header" style="padding-top: 0px;">
		<div class="container-fluid">
			<div class="row">
				<!-- Header -->
			</div>
		</div>
	</div>

	<div class="content">
		<div class="container-fluid ">
            <div class="row">
                <div class="col-12">
                    <div class="card card-info">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="m-0"><strong>PRODUCT DETAILS <i class="fas fa-info-circle"></i></strong></h5>
                        </div>

                        <input type="hidden" id="productid" value="{{ $productId }}">

                        <div class="card-body">
                            <div class="container-fluid">
                                <!-- Row 1 -->
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <h6 class="mb-1"><strong>Product Name:</strong> <span id="productname"></span></h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-1"><strong>SKU:</strong> <span id="sku"></span></h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-1"><strong>Brand Name:</strong> <span id="brandname"></span></h6>
                                    </div>
                                </div>

                                <!-- Row 2 -->
                                <div class="row mb-2">
                                    <div class="col-md-4">
                                        <h6 class="mb-1"><strong>Category:</strong> <span id="category"></span></h6>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-1"><strong>Sub-Category:</strong> <span id="subcategory"></span></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
				<div class="col-lg-12">
          			<div class="card card-info">
			            <div class="card-header">
			                <h5 class="m-0"><strong>VARIANTS</strong></h5>
			            </div>
		              	<div class="card-body">
	          				<div class="container-fluid">
                                <button type="button" id='add_btn'class="btn btn-outline-info" data-toggle="modal" data-target="#addModal"><i class="fas fa-plus"></i> Add Variant</button>
                                <div class="pt-3 table-responsive ">
                                    <table id="variant_table" class="display" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Variant Name</th>
                                                <th>Measurement</th>
                                                <th>Description</th>
                                                {{-- <th>Discount Availability</th>
                                                <th>Discount Type</th>
                                                <th>Discount</th>
                                                <th>Tax Name</th>
                                                <th>Is Tax Excluded</th>
                                                <th>Tax</th> --}}
                                                <th>Variant Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
	          				</div>
	          			</div>
	          		</div>
				</div>
			</div>

            <!-- Add Modal -->

            <div class="modal fade" id="addVariantModal" tabindex="-1" role="dialog" aria-labelledby="addVariantModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content card card-info">
                        <div class="card-header">
			                <h5 class="m-0"><strong>ADD VARIANT</strong></h5>
			            </div>
                        <div class="modal-body container">
                            <div class='row'>
                                <div class='col-4'><label for="name" class="mt-2 float-right" style="font-weight: normal;">Variant Name<span class="text-danger"><strong>*</strong></span></label></div>
                                <div class='col-8'><input style="width:70%" class="form-control " type="text" name="name" id="name"></div>
                            </div>
                            <div class='row'>
                                <div class='col-4'><label for="measurement" class="mt-2 float-right" style="font-weight: normal;">Measurement<span class="text-danger"><strong>*</strong></span></label></div>
                                <div class='col-8'>
                                    <select style="width:70%" class="selectpicker"data-width="70%" data-live-search="true" name="measurement"
                                    id="measurement">
                                        <option value="option_select" disabled selected>Select Unit</option>
                                        @foreach($units as $measurement)
                                        <option value="{{ $measurement->name }}">{{ $measurement->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-4'><label for="description" class="mt-2 float-right" style="font-weight: normal;">Description</label></div>
                                <div class='col-8'><input style="width:70%" class="form-control " type="text" name="description" id="description"></div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-12">
                                    <br>
                                    <div class="card card-secondary  ">
                                        <div class="card-header">
                                            <h5 class="m-0"><strong>OTHERS </strong><span>(optional)</span> <i class="fas fa-percent float-right"></i></h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="container-fluid">
                                                <div class="row pt-3">  <!-- --------------------------row--------------------------- -->
                                                <div class="col-4">
                                                    <div class="col-9">
                                                        <div class="mb-3">
                                                            <label for="availablediscount" class="form-label" style="font-weight: normal;">Discount Availability</label><br>
                                                            <select style="width:70%" class="selectpicker" data-width="100%" name="availablediscount"
                                                                id="availablediscount" title="Discount availability">
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
                                                                <label for="taxname" class="form-label" style="font-weight: normal;">Tax Name</label><br>
                                                                <select style="width:70%" class="selectpicker" data-width="100%" name="taxname"
                                                                    id="taxname" title="Product Tax" data-live-search="true">
                                                                    <option value="" disabled>Select tax</option>
                                                                    <option value="null">N/A</option>
                                                                    @foreach($taxs as $tax)
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
                                            </div> <!-- container -->
                                        </div> <!-- card-body -->
                                    </div> <!-- card card-primary card-outline -->
                                </div><!-- col-6 -->
                            </div> <!-- row --> --}}
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="button" class="cancel_btn btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="add_confirm btn btn-danger" onclick="addVariant()" >Yes</button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- END Add Modal -->

            <!-- Edit Modal -->

            <div class="modal fade" id="editVariantModal" tabindex="-1" role="dialog" aria-labelledby="editVariantModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content card card-info">
                        <div class="card-header">
			                <h5 class="m-0"><strong>EDIT VARIANT</strong></h5>
			            </div>
                        <div class="modal-body container">
                            <input type="hidden" name="" id="variantIdEdit">
                            <div class='row'>
                                <div class='col-4'><label for="edit_name" class="mt-2 float-right" style="font-weight: normal;">Variant Name</label></div>
                                <div class='col-8'><input style="width:70%" class="form-control " type="text" name="edit_name" id="edit_name"></div>
                            </div>
                            <div class='row'>
                                <div class='col-4'><label for="edit_measurement" class="mt-2 float-right" style="font-weight: normal;">Measurement</label></div>
                                <div class='col-8'>
                                    <select style="width:70%" class="selectpicker"data-width="70%" data-live-search="true" name="edit_measurement"
                                    id="edit_measurement">
                                        <option value="option_select" disabled selected>Select Unit</option>
                                        @foreach($units as $measurement)
                                        <option value="{{ $measurement->name }}">{{ $measurement->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-4'><label for="edit_description" class="mt-2 float-right" style="font-weight: normal;">Description</label></div>
                                <div class='col-8'><input style="width:70%" class="form-control " type="text" name="edit_description" id="edit_description"></div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-12">
                                    <br>
                                    <div class="card card-secondary  ">
                                        <div class="card-header">
                                            <h5 class="m-0"><strong>OTHERS </strong><span>(optional)</span> <i class="fas fa-percent float-right"></i></h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="container-fluid">
                                                <div class="row pt-3">  <!-- --------------------------row--------------------------- -->
                                                <div class="col-4">
                                                    <div class="col-9">
                                                        <div class="mb-3">
                                                            <label for="edit_availablediscount" class="form-label" style="font-weight: normal;">Discount Availability</label><br>
                                                            <select style="width:70%" class="selectpicker" data-width="100%" name="edit_availablediscount"
                                                                id="edit_availablediscount" title="Discount availability">
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
                                                            <label for="edit_discounttype" class="form-label" style="font-weight: normal;">Discount Type</label><br>
                                                            <select style="width:70%" class="selectpicker" data-width="100%" name="edit_discounttype"
                                                                id="edit_discounttype" title="Discount type" disabled>
                                                                <option value="" disabled>Select Type</option>
                                                                <option value="BDT">BDT</option>
                                                                <option value="Percentage">Percentage</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="col-4">
                                                    <div class="mb-3">
                                                        <label for="edit_discount" class="form-label" style="font-weight: normal;">Discount</label>
                                                        <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="number" step="any" min="0.1" name="edit_discount" id="edit_discount" placeholder="e.g. 50" disabled>
                                                    </div>
                                                </div>
                                                </div>

                                                    <div class="row pt-3">  <!-- --------------------------row--------------------------- -->
                                                    <div class="col-4">
                                                        <div class="col-9">
                                                            <div class="mb-3">
                                                                <label for="edit_taxname" class="form-label" style="font-weight: normal;">Tax Name</label><br>
                                                                <select style="width:70%" class="selectpicker" data-width="100%" name="edit_taxname"
                                                                    id="edit_taxname" title="Product Tax" data-live-search="true">
                                                                    <option value="" disabled>Select tax</option>
                                                                    <option value="null">N/A</option>
                                                                    @foreach($taxs as $tax)
                                                                    <option value="{{ $tax->taxName }}">{{ $tax->taxName }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="mb-3">
                                                            <label for="edit_tax" class="form-label" style="font-weight: normal;">Tax</label>
                                                            <input style="width:70%" class="form-control border-left-0 border-right-0 border-top-0 rounded-0" type="number" step="any" min="0.1" name="edit_tax" id="edit_tax" placeholder="tax amount e.g. 17.5" disabled>
                                                        </div>
                                                    </div>

                                                    <div class="col-4">
                                                        <div class="col-9">
                                                            <div class="mb-3">
                                                                <label for="edit_taxexcluded" class="form-label" style="font-weight: normal;">Is Tax Excluded</label><br>
                                                                <select style="width:70%" data-width="100%" class="selectpicker"  aria-label="Default select example" name="edit_taxexcluded" id="edit_taxexcluded" title="Is tax excluded" disabled>
                                                                        <option value=""disabled>Select</option>
                                                                    <option value="true">Yes</option>
                                                                    <option value="false">No</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- container -->
                                        </div> <!-- card-body -->
                                    </div> <!-- card card-primary card-outline -->
                                </div><!-- col-6 -->
                            </div> <!-- row --> --}}
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="cancel_btn btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="edit_confirm btn btn-danger" onclick="editVariant()" >Yes</button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- END Edit Modal -->

            <!-- Delete Modal -->

            <div class="modal fade" id="deleteVariantModal" tabindex="-1" role="dialog" aria-labelledby="deleteVariantModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">

                        <div class="modal-body">
                            <input type="hidden" name="" id="variantIdDelete">
                            <h5 class="text-center">Are you sure you want to delete?</h5>
                        </div>

                        <div class="modal-footer justify-content-center">
                            <button type="button" class="cancel_btn btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="delete_confirm btn btn-danger" onclick="deleteVariant()" >Yes</button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- END Delete Modal -->
    	</div>
	</div>
</div>


@endsection

@section('script')

<script type="text/javascript" src="{{ asset('js/product-details.js') }}"></script>

@endsection
