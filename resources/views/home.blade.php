@extends('layouts.master')

@section('title', 'Home')

@section('content')
<div class="content-wrapper" id="body">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><strong></strong></h1>
                </div><!-- /.col -->
            </div><!-- /.row mb-2 -->
        </div><!-- /.container-fluid -->
    </div> <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            {{-- @if ($first_time_login)
               <!-- <h3>Welcome Popup</h3> -->

               <div class="row">
                <div class="col-lg-6">

                  <div class="card card-warning">
                    <div class="card-header">
                        <h5 class="m-0"><strong>WARNING!</strong></h5>
                    </div>
                    <div class="card-body">
                      <h3>Please note down the below credentials</h3>

                      @foreach($stores as $store)
                        <p class="mb-0">Your Default Store is: <strong>{{ $store->store_name }}</strong></p><br>
                        @foreach($poses as $pos)
                            <p class="mb-0">Your Default POS name is: <strong>{{ $pos->pos_name }}</strong></p>
                            <p class="mb-0">Your Default POS PIN is: <strong>{{ $pos->pos_pin }}</strong></p> <br>
                            <p> ⚠️ Please Note down the credentials for future use. It will not be available after this window is closed.</p>
                        @endforeach
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>


               <div class="modal" id="myModal" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Store and POS Credentials</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Please note down the below credentials.</p>

                      @foreach($stores as $store)
                        Your Default Store is: <strong>{{ $store->store_name }}</strong> <br>
                        @foreach($poses as $pos)
                            Your Default POS name is: <strong>{{ $pos->pos_name }}</strong> <br>
                            Your Default POS PIN is: <strong>{{ $pos->pos_pin }}</strong> <br>
                            <p> ⚠️ Please Note down the credentials for future use. It will not be available after this window is closed.</p>
                        @endforeach
                      @endforeach

                    </div>
                    <div class="modal-footer">
                      <button type="button" id="noted" class="btn btn-primary">Noted</button>
                    </div>
                  </div>
                </div>
              </div>
            @else

            @endif --}}

            <!-- Info boxes -->
            <div class="row">
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-info elevation-1"><i style="color: white;" class="fas fa-shopping-cart"></i></span>

                  <div class="info-box-content">
                    <h6 class="info-box-text mb-0" style=""><strong>Sale</strong></h6>
                    <h6 class="info-box-text mb-0">Today: <strong><span id="todaySale">0</span></strong> </h6>
                    <h6 class="info-box-text mb-0">Current Month: <strong><span id="monthSale">0</span></strong></h6>
                    <h6 class="info-box-text mb-0">Total: <strong><span id="totalSale">0</span></strong></h6>
                  </div>
                  <!-- /.info-box-content -->
                  <!-- <div class="overlay">
                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                  </div> -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-warning elevation-1"><i style="color: white;" class="fas fa-hand-holding-usd"></i></span>

                  <div class="info-box-content">
                    <h6 class="info-box-text mb-0" style=""><strong>Due</strong></h6>
                    <h6 class="info-box-text mb-0">Today: <strong><span id="todayDue">0</span></strong> </h6>
                    <h6 class="info-box-text mb-0">Current Month: <strong><span id="monthDue">0</span></strong> </h6>
                    <h6 class="info-box-text mb-0">Total: <strong><span id="totalDue">0</span></strong> </h6>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->

              <!-- fix for small devices only -->
              <!-- <div class="clearfix hidden-md-up"></div> -->

              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-danger elevation-1"><i style="color: white;" class="fas fa-money-bill-alt"></i></span>

                  <div class="info-box-content">
                    <h6 class="info-box-text mb-0" style=""><strong>Expense</strong></h6>
                    <h6 class="info-box-text mb-0">Today: <strong><span id="todayExpense">0</span></strong> </h6>
                    <h6 class="info-box-text mb-0">Current Month: <strong><span id="monthExpense">0</span></strong> </h6>
                    <h6 class="info-box-text mb-0">Total: <strong><span id="totalExpense">0</span></strong> </h6>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-secondary elevation-1"><i style="color: white;" class="fas fa-shopping-bag"></i></span>

                  <div class="info-box-content">
                    <h6 class="info-box-text mb-0" style=""><strong>Purchase</strong></h6>
                    <h6 class="info-box-text mb-0">Today:  <strong><span id="todayPurchase">0</span></strong> </h6>
                    <h6 class="info-box-text mb-0">Current Month: <strong><span id="monthPurchase">0</span></strong> </h6>
                    <h6 class="info-box-text mb-0">Total: <strong><span id="totalPurchase">0</span></strong> </h6>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"></h3>

                            <div class="card-tools" >
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                        </div>
                        <div class="card-body" >
                            <div class="chart"  style="height: 400px; width: 100%;">
                              <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                <figure class="highcharts-figure">
                                    <div id="container1"></div>
                                    <p class="highcharts-description">

                                    </p>
                                </figure>

                            </div>
                        </div>
                    <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"></h3>

                            <div class="card-tools" >
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                        </div>
                        <div class="card-body" >
                            <div class="chart"  style="height: 400px; width: 100%;">
                              <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                <figure class="highcharts-figure">
                                    <div id="container2"></div>
                                    <p class="highcharts-description">

                                    </p>
                                </figure>

                            </div>
                        </div>
                    <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"></h3>

                            <div class="card-tools" >
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                        </div>
                        <div class="card-body" >
                            <div class="chart"  style="height: 437px; width: 100%;">
                              <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                <figure class="highcharts-figure">
                                    <div id="container3"></div>
                                    <p class="highcharts-description">

                                    </p>
                                </figure>

                            </div>
                        </div>
                    <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"></h3>

                            <div class="card-tools" >
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                        </div>
                        <div class="card-body" >

                            <select class="selectpicker w-25 float-right" title="All Store" data-live-search="true" aria-label="Default select example" name="store" id="store">
                                <option value="all_store">All Store</option>
                                @foreach($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->store_name  }}</option>
                                @endforeach
                            </select>
                            <div class="chart"  style="height: 400px; width: 100%;">
                              <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                <figure class="highcharts-figure">
                                    <div id="container4"></div>
                                    <p class="highcharts-description">

                                    </p>
                                </figure>

                            </div>
                        </div>
                    <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title"></h3>

                            <div class="card-tools" >
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                        </div>
                        <div class="card-body" >
                            <div class="chart"  style="height: 400px; width: 100%;">
                              <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                <figure class="highcharts-figure">
                                    <div id="container5"></div>
                                    <p class="highcharts-description">

                                    </p>
                                </figure>

                            </div>
                        </div>
                    <!-- /.card-body -->
                    </div>

                </div>
                <div class="col-sm-4">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title"></h3>

                            <div class="card-tools" >
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                        </div>
                        <div class="card-body" >
                            <div class="chart"  style="height: 400px; width: 100%;">
                              <!-- <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas> -->
                                <!-- <figure class="highcharts-figure">
                                    <div id="container5"></div>
                                    <p class="highcharts-description">

                                    </p>
                                </figure> -->
                                <h5 class="text-center">{{ date('F-Y') }} Store Sales</h5>
                                <hr>
                                <table id="store_sale_table" class="table table-bordered">
                                    <thead class="">
                                        <tr>
                                            <th>#</th>
                                            <th width="60%">Store</th>
                                            <th width="40%">Sale</th>
                                        </tr>
                                    </thead>
                                    <!-- <tbody>

                                    </tbody> -->
                                </table>

                            </div>
                        </div>
                    <!-- /.card-body -->
                    </div>

                </div>
            </div>

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
<script type="text/javascript" src="js/home.js"></script>

<script>
    $(document).ready(function(){
        $("#myModal").modal('show');
    });

    $(document).on('click', '#noted', function(e){

        e.preventDefault()
        $("#myModal").modal('hide');
    });


</script>
@endsection
