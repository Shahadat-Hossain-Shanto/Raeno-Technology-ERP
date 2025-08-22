@extends('layouts.master')
@section('title', 'Product-Adjustment')

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
                                <h5 class="m-0"><strong><i class="fas fa-chevron-circle-down"></i>PRODUCT ADJUSTMENT</strong>
                                </h5>
                            </div>
                            <div class="card-body">

                                <div id="form_div">
                                    <form id="" method="" enctype="multipart/form-data">
                                        <div id="storediv" style="display: disabled">
                                            <div class="row pt-3">
                                                <div class="form-group col-2">
                                                    <label for="store" style="font-weight: normal;">Store<span
                                                            class="text-danger"><strong>*</strong></span></label><br>
                                                    <select class="form-control" data-live-search="true"
                                                        aria-label="Default select example" name="store" id="store">
                                                        <option value="default" selected disabled>Select Store</option>
                                                        <option value="0">Warehouse</option>
                                                        @foreach ($stores as $store)
                                                            <option value="{{ $store->id }}">{{ $store->store_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-2">
                                                    <label for="number" style="font-weight: normal;">Barcode/Serial Number</label>
                                                    <input type="text" class="form-control" placeholder="e.g. 12345"id="number" name="number">
                                                    <input type="hidden" class="form-control" placeholder="Serialize/Non-Serialize"id="type" name="type"disabled>
                                                </div>
                                                <div class="form-group col-2">
                                                    <label for="product" style="font-weight: normal;">Product<span
                                                            class="text-danger"><strong>*</strong></span></label><br>
                                                    <select class="selectpicker" data-live-search="true"
                                                        aria-label="Default select example" name="product" id="product"
                                                        data-width="100%"disabled>
                                                        <option value="default" selected disabled>Select Product</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-2">
                                                    <label for="variant" style="font-weight: normal;">Variant<span
                                                            class="text-danger"><strong>*</strong></span></span></label><br>
                                                    <select class="selectpicker" data-live-search="true" data-width="100%"
                                                        name="variant" id="variant" disabled>
                                                        <option value="default" selected disabled>Select Variant</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-2">
                                                    <label for="qty" style="font-weight: normal;">Qty<span
                                                            class="text-danger"><strong>*</strong></span></label>
                                                    <input type="number" class="form-control" placeholder="e.g. 1000"
                                                        id="qty" name="qty"disabled>
                                                    <input type="hidden" class="form-control" placeholder="e.g. 1000"
                                                        id="onHand" name="onHand">
                                                </div>
                                                <div class="col-2 mt-4">

                                                    <button id="add_btn" type="button"
                                                        class=" w-30 btn btn-info float-right ml-1"
                                                        onclick="productAddToTable()"><i class="fas fa-plus"></i>
                                                        Add</button>
                                                    <button class="btn btn-outline-danger float-right" type="reset"
                                                        name="" onclick="resetButton()"><i class="fas fa-eraser"></i>
                                                        Reset</button>
                                                    <h5 class="text-danger float-right mr-5"><strong
                                                            id="errorMsg"></strong></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>


                                <div id='adjustment' hidden>
                                    <div class="row pt-4">
                                        <div class="col-10">
                                            <table id="product_table" class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" style="display: none;">StoreId</th>
                                                        <th scope="col">Store</th>
                                                        <th scope="col" style="display: none;">ProductId</th>
                                                        <th scope="col">Product</th>
                                                        <th scope="col" style="display: none;">VariantId</th>
                                                        <th scope="col">Variant</th>
                                                        <th scope="col">Quantity</th>
                                                        <th scope="col">Product Type</th>
                                                        <th scope="col">Identification Number</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product_table_body">


                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="form-group col-10" style="padding-top: 31px">

                                            <button id="submit" type="button" class=" w-30 btn btn-primary float-right"
                                                onclick="productAdjustInToServer()"><i class="fas fa-arrow-alt-circle-down"></i>
                                                Adjust Product</button>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- Card-body -->
                        </div> <!-- Card -->

                    </div> <!-- /.col-lg-6 -->
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
                                    <input type="hidden" class="form-control" name='qty' id='qty'>
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
    <script type="text/javascript" src="js/product-adjustment.js"></script>
    <script type="text/javascript"></script>

@endsection
