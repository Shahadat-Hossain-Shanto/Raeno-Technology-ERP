@extends('layouts.master')
@section('title', 'Warehouse Expired Stock')

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
		                	<h5 class="m-0"><strong><i class="fas fa-file-contract"></i> WAREHOUSE EXPIRED PRODUCTS</strong></h5>
		              </div>
		              <div class="card-body">
                        <div class="row">
                            <!-- <div class="col-6">
                                <h4 class="text-right"><u><span  id="storename"></span></u></h4>
                            </div> -->
                            <!-- <div class="col-6">
                                <select class="selectpicker w-50 float-left" title="Select Store" data-live-search="true" aria-label="Default select example" name="store" id="store" onchange="">
                                    <option value="option_select" selected>All store</option>
                                    @foreach($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->store_name  }}</option>
                                    @endforeach
                                </select>
                            </div> -->
                            
                        </div>


		              	<div class="row pt-2">
                            <table id="store_stock_report_table" class="table table-bordered">
                                <thead class="">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">Product Name</th>
                                        <th width="7%">Batch No.</th>
                                        <th width="8%">Expiry Date</th>
                                        <th width="6%">Stock</th>
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
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

@endsection

@section('script')
<script src="{{ asset('dist/js/highcharts/highcharts.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/data.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/drilldown.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/exporting.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/export-data.js')}}"></script>
<script src="{{ asset('dist/js/highcharts/accessibility.js')}}"></script>

<script type="text/javascript" src="js/inventory-expired-stock.js"></script>
@endsection


	
