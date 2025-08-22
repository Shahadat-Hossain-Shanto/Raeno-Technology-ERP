@extends('layouts.master')
@section('title', 'Profit Calculation Reports')

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
	          	<div class="col-lg-8">

		          	<div class="card card-primary">
		              <div class="card-header">
		                	<h5 class="m-0"><strong><i class="fas fa-file-contract"></i> PROFIT CALCULATION REPORT</strong></h5>
		              </div>
		              <div class="card-body">
	                	<div id="form_div">
		                	<form id="ProfitCalculationReportForm" method="POST" enctype="multipart/form-data">
                                <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label for="store" style="font-weight: normal;">Select Store</label>
                                    <select class="selectpicker w-100" title="Select Store" data-live-search="true" aria-label="Default select example" name="store" id="store">
                                        <option value="0" selected>All Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->store_name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="startdate" style="font-weight: normal;">From<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="startdate" name="startdate">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="enddate" style="font-weight: normal;">To<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="enddate" name="enddate">
                                </div>
                                <div style="padding-top: 32px;" class="form-group col-md-4">
                                    <button type="submit" class="btn btn-primary" id="gen_btn">Generate</button>
                                    <button id="reset_btn" type="button" class=" w-30 btn btn-outline-danger" onclick="resetButton()"><i class="fas fa-eraser"></i> Reset</button>
                                    <button id="print" type="button" class=" w-30 btn btn-outline-primary" onclick="printButton()"><i class="fas fa-print"></i> Print</button>
                                </div>
                                </div>
                            </form>
	                	</div>


                        <div id="tablePart" class="col-12">
                            <hr>
                            <div class="row">
                                <h2 class="pb-3 text-center"><strong><u>Profit Calculation Report</u></strong></h2>
                                <h3 class="pb-3 text-center"><strong><u><span id='currentStore'>All Store</span></u></strong></h3>
                            </div>
                            <hr>
                            <div class="row">
                                <h4 class="pb-3 text-center"><strong><u>Stock Transaction</u></strong></h4>
                                    <table id="total_stock" class="table table-bordered border-dark display">
                                        <tbody id="total_stock_body">
                                            <tr>
                                                <th scope="col-6">Purchase Stock Amount</th>
                                                <td scope="col-6"><spam id='purchaseAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">ProductIn Stock Amount</th>
                                                <td scope="col-6"><spam id='productInStockAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">Transfered Product Into Stock Amount</th>
                                                <td scope="col-6"><spam id='productTransferIntoStockAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr style="border:2px solid rgb(6, 97, 233);background-color: rgb(7, 141, 230);">
                                                <th scope="col-6">Total Stock Amount</th>
                                                <td scope="col-6"><spam id='totalStockAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table id="closing_stock" class="table table-bordered border-dark  display">
                                        <tbody id="closing_stock_body">
                                            <tr>
                                                <th scope="col-6">Stock Amount</th>
                                                <td scope="col-6"><spam id='ttotalStockAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">Transaction Amount</th>
                                                <td scope="col-6"><spam id='transactionAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">Transfered Product From Amount</th>
                                                <td scope="col-6"><spam id='productTransferFromAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">Return Amount</th>
                                                <td scope="col-6"><spam id='returnAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr style="border:2px solid rgb(6, 97, 233);background-color: rgb(7, 141, 230);">
                                                <th scope="col-6">Closing Stock Amount</th>
                                                <td scope="col-6"><spam id='closingStockAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                        </tbody>
                                    </table>

                            </div>
                            <hr>
                            <div class='row'>
                                <div class='col-12' id="period" hidden>
                                    <h3 class="pb-3 text-center"><strong><u>Period : </u></strong><span id='fromDate'></span> - <span id='toDate'></span></h3>
                                </div>

                                <div class='col-6'>
                                    <h4 class="pb-3"><strong><u>Selling Cost Value</u></strong></h4>
                                    <table id="total_selling_cost" class="table table-bordered border-dark  display">
                                        <tbody id="total_selling_cost_body">
                                            <tr>
                                                <th scope="col-6">Selling Cost Amount</th>
                                                <td scope="col-6"><spam id='sellingCostAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">Return Cost Amount</th>
                                                <td scope="col-6"><spam id='returnCostAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr style="border:2px solid rgb(6, 97, 233);background-color: rgb(7, 141, 230);">
                                                <th scope="col-6">Total Selling Cost Amount</th>
                                                <td scope="col-6"><spam id='totalSellingCostAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class='col-6'>
                                    <h4 class="pb-3"><strong><u>Net Sell Value</u></strong></h4>
                                    <table id="net_sell" class="table table-bordered border-dark display">
                                        <tbody id="net_sell_body">
                                            <tr>
                                                <th scope="col-6">Sell Amount</th>
                                                <td scope="col-6"><spam id='totalSellAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">Vat/Tax Amount</th>
                                                <td scope="col-6"><spam id='totalVatAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">Discount Amount</th>
                                                <td scope="col-6"><spam id='totalDiscountAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">SpecialDiscount Amount</th>
                                                <td scope="col-6"><spam id='totalSpecialDiscountAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">Return Amount</th>
                                                <td scope="col-6"><spam id='totalReturnAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr style="border:2px solid rgb(6, 97, 233);background-color: rgb(7, 141, 230);">
                                                <th scope="col-6">Net Sell Amount</th>
                                                <td scope="col-6"><spam id='netSellAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <h4 class="pb-3 text-center"><strong><u>Profit:</u></strong></h4>
                                    <table id="net_Profit" class="table table-bordered border-dark  display">
                                        <tbody id="net_Profit_body">
                                            <tr>
                                                <th scope="col-6">Net Sell Amount</th>
                                                <td scope="col-6"><spam id='nnetSellAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr>
                                                <th scope="col-6">Total Selling Cost Amount</th>
                                                <td scope="col-6"><spam id='totalSellCostAmount' class="float-right">0.00</spam></td>
                                            </tr>
                                            <tr style="border:2px solid rgb(6, 97, 233);background-color: rgb(7, 141, 230);">
                                                <th scope="col-6">Net Profit</th>
                                                <td scope="col-6"><spam id='netProfit' class="float-right">0.00</spam></td>
                                            </tr>
                                        </tbody>
                                    </table>
                            </div>
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
<script type="text/javascript" src="js/profit-calculation-report.js"></script>
@endsection
