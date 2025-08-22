@extends('layouts.master')
@section('title', 'Warehouse Stock Report (Variant Wise)')

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
		                	<h5 class="m-0"><strong><i class="fas fa-file-contract"></i> WAREHOUSE STOCK (VARIANT WISE)</strong></h5>
                        </div>
                        <div class="card-body">
                            <!-- <select class="selectpicker w-25 float-right" title="Warehouse" data-live-search="true" aria-label="Default select example" name="store" id="store">
                            <option value="inventory">Warehouse</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->store_name  }}</option>
                            @endforeach
                            </select> -->
                            <div class="table-responsive">
                                <table id="stock_report_table" class="table table-bordered" width="100%">
                                    <thead class="">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="10%">Product Name</th>
                                            <th width="7%">Variant</th>
                                            <th width="8%">Expiry Date</th>
                                            <th width="10%">Brand Name</th>
                                            <th width="5%">MRP</th>
                                            <th width="10%">Purchase Cost</th>
                                            <th width="6%">In Qty</th>
                                            <th width="6%">Out Qty</th>
                                            <th width="6%">Stock</th>
                                            <th width="10%">Stock Sale Price</th>
                                            <th width="11%">Stock Purchase Cost</th>
                                        </tr>
                                    </thead>
                                    <!-- <tbody>
                                    </tbody> -->
                                </table>
                            </div>
                        </div> <!-- Card-body -->
		            </div>	<!-- Card -->
		        </div>   <!-- /.col-lg-6 -->
        	</div><!-- /.row -->
            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Product Identification Number</h5>
                            <button type="button" class="close exit" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table id="info_table" class="display table" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Product Name</th>
                                            <th>Type</th>
                                            <th>Variant Name</th>
                                            <th>BarCode / Serial Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="exit btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection

@section('script')
<!-- <script src="{{ asset('dist/js/highcharts/highcharts.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/data.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/drilldown.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/exporting.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/export-data.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/accessibility.js')}}"></script> -->

<script type="text/javascript" src="js/stock-report.js"></script>
@endsection



