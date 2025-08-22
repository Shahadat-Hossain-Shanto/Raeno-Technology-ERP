<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('images/title-image.jpeg') }}" type="image/icon type">

    <style type="text/css">

        .pos-card-text-title{
            font-size:16px;
            font-weight: ;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
        .pos-card-text-body{
            font-size:14px;
            font-weight: normal;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
        .round {
          position: relative;
        }

        .round label {
          background-color: #fff;
          border: 1px solid #ccc;
          border-radius: 50%;
          cursor: pointer;
          height: 28px;
          left: 0;
          position: absolute;
          top: 0;
          width: 28px;
        }

        .round label:after {
          border: 2px solid #fff;
          border-top: none;
          border-right: none;
          content: "";
          height: 6px;
          left: 7px;
          opacity: 0;
          position: absolute;
          top: 8px;
          transform: rotate(-45deg);
          width: 12px;
        }

        .round input[type="checkbox"] {
          visibility: hidden;
        }

        .round input[type="checkbox"]:checked + label {
          background-color: #66bb6a;
          border-color: #66bb6a;
        }

        .round input[type="checkbox"]:checked + label:after {
          opacity: 1;
        }

        i.fax {
          display: inline-block;
          background-color: #ededed;
          border-radius: 60px;
          box-shadow: 0 0 2px #ededed;
          padding: 0.8em 0.9em;


        }

        body
        {
            font-size: 3.2vw;
        }

    </style>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Data table -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"> -->
   <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.11.5/af-2.3.7/b-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.css"/> -->
    <link rel="stylesheet" href="{{ asset('dataTable/datatables.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('dataTable/Buttons-2.2.2/css/buttons.bootstrap5.min.css') }}"> -->



    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">


    <!-- iCheck -->
    <link rel="stylesheet"
        href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <!-- overlayScrollbars -->
    <link rel="stylesheet"
        href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">


    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">

    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">

    <!-- Select2 -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">


    <!-- DataTable -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.css"/>
 -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>

    <!-- PrintJS -->
    <!-- <link rel="stylesheet" type="text/css" href="{{ asset('https://printjs-4de6.kxcdn.com/print.min.css')}}"> -->
    <link rel="stylesheet" href="{{ asset('dist/css/print.min.css') }}">

    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/css/fileinput-rtl.min.css" media="all" rel="stylesheet" type="text/css"/>

    <!-- jquery loader -->
    <link rel="stylesheet" href="{{ asset('loader/css/jquery.loadingModal.min.css') }}">
    <link rel="stylesheet" href="{{ asset('loader/scss/jquery.loadingModal.scss') }}">

    <!-- Pace  -->
    <link rel="stylesheet" href="{{ asset('pace/css/pace-theme-flat-top.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('pace/css/pace-theme-loading-bar.css') }}"> -->

    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- <style type="text/css">
        .main-sidebar { background-color: $your-color !important }
    </style> -->


</head>
<body>
    <div class="wrapper">

            <div class="content">
                <div class="container-fluid ">
                    <div class="row pt-5">

                        <div class="col-sm-12">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h5 class="m-0"><i class="fas fa-shopping-cart" style="color: ;"></i> <strong>ORDER DETAILS</strong></h5>
                                </div>

                              <div class="card-body">
                                <!-- <h6 class="card-title">Special title treatment</h6> -->
                                <!-- Table -->
                                @foreach($orderList as $order)
                                    <h1>ORDER NO. {{ $order->orderId }}</h1>

                                    <div class="row">

                                        <div class="col-12">
                                            <p><strong>Details:</strong></p>
                                            @if($order->clientId != 0)
                                                <p class="mb-0">Customer Name: {{ $order->name }}</p>
                                                <p class="mb-0">Customer Mobile: {{ $order->mobile }}</p>
                                                <p class="mb-0">Total Price: {{number_format( $order->total, 2, '.', '') }}</p>
                                                <p class="mb-0">Total Discount: {{ number_format( $order->totalDiscount, 2, '.', '') }}</p>
                                                <p class="mb-0">Total Tax: {{ number_format( $order->totalTax, 2, '.', '')  }}</p>
                                                <p class="mb-0">Grand Total: {{ number_format( $order->grandTotal, 2, '.', '')  }}</p>
                                                <p class="mb-0">Order Date: {{ $order->orderDate }}</p>
                                            @else
                                                <p class="mb-0">Customer Name: Walking Customer</p>
                                                <p class="mb-0">Customer Mobile: N/A</p>
                                                <p class="mb-0">Total Price: {{ number_format( $order->total, 2, '.', '')  }}</p>
                                                <p class="mb-0">Total Discount: {{ number_format( $order->totalDiscount, 2, '.', '')  }}</p>
                                                <p class="mb-0">Total Tax: {{ number_format( $order->totalTax, 2, '.', '')  }}</p>
                                                <p class="mb-0">Grand Total: {{ number_format( $order->grandTotal, 2, '.', '')  }}</p>
                                                <p class="mb-0">Order Date: {{ $order->orderDate }}</p>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                                <br>
                                <hr>

                                <h3>Orderd Products</h3>

                                @foreach($orderList as $order)
                                <input type="hidden" name="orderid" id="orderid" value="{{ $order->id }}">
                                @endforeach

                                    <div class="pt-2 row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="products_table" class="table col-sm-12 table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Product Name</th>
                                                            <th>Quantity</th>
                                                            <th>Total</th>
                                                            <th>Discount</th>
                                                            <th>Tax</th>
                                                            <th>Grand Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                              </div> <!-- Card-body -->
                            </div>  <!-- Card -->
                        </div>
                    </div>
                </div>
            </div>

    </div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)

    </script>

    <!-- Bootstrap 5 -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>

    <!-- DataTable -->
    <!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js">
    </script> -->
    <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.js"></script> -->

    <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-html5-2.2.2/b-print-2.2.2/sc-2.0.5/datatables.min.js"></script> -->

   <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.11.5/b-2.2.2/b-html5-2.2.2/b-print-2.2.2/r-2.2.9/datatables.min.js"></script> -->
    <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.11.5/af-2.3.7/b-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.js"></script> -->
    <script src="{{ asset('dataTable/datatables.min.js') }}"></script>
    <script src="{{ asset('dataTable/Buttons-2.2.2/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('dataTable/JSZip-2.5.0/jszip.min.js') }}"></script>
    <script src="{{ asset('dataTable/pdfmake-0.1.36/pdfmake.js') }}"></script>
    <script src="{{ asset('dataTable/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dataTable/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dataTable/Buttons-2.2.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dataTable/Buttons-2.2.2/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dataTable/Responsive-2.2.9/js/dataTables.responsive.min.js') }}"></script>





    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

    <!-- Sparkline -->
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>

    <!-- Sparkline -->
    <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>

    <!-- JQVMap -->
    <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>

    <!-- jQuery Knob Chart -->
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>

    <!-- daterangepicker -->
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    <!-- Tempusdominus Bootstrap 4 -->
    <script
        src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}">
    </script>

    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}">
    </script>

    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.js') }}"></script>

    <!-- Notify JS -->
    <script src="{{ asset('dist/js/notify.min.js') }}"></script>

    <!-- Print a div -->
    <script src="{{ asset('dist/js/jQuery.print.min.js') }}"></script>

    <!-- <script type="text/javascript" src="https://printjs-4de6.kxcdn.com/print.min.js"></script> -->
    <script type="text/javascript" src="{{ asset('dist/js/print.min.js') }}"></script>






    <!-- Select2 -->
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
    wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <!-- <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/js/plugins/piexif.min.js" type="text/javascript"></script> -->
    <script type="text/javascript" src="{{ asset('kartik-v-bootstrap-fileinput-ab06a9c/js/plugins/piexif.min.js') }}"></script>

    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
        This must be loaded before fileinput.min.js -->
    <!-- <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/js/plugins/sortable.min.js" type="text/javascript"></script> -->
    <script type="text/javascript" src="{{ asset('kartik-v-bootstrap-fileinput-ab06a9c/js/plugins/sortable.min.js') }}"></script>

    <!-- bootstrap.bundle.min.js below is needed if you wish to zoom and preview file content in a detail modal
        dialog. bootstrap 5.x or 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <!-- the main fileinput plugin script JS file -->
    <!-- <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/js/fileinput.min.js"></script> -->
    <script type="text/javascript" src="{{ asset('kartik-v-bootstrap-fileinput-ab06a9c/js/fileinput.min.js') }}"></script>

    <!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`). Uncomment if needed. -->
    <!-- script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/themes/fas/theme.min.js"></script -->

    <!-- optionally if you need translation for your language then include the locale file as mentioned below (replace LANG.js with your language locale) -->
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/js/locales/LANG.js"></script>

    <!-- jquery loader -->
    <!-- <script src="//code.jquery.com/jquery-3.1.1.slim.min.js"></script> -->
    <script src="{{ asset('loader/js/jquery.loadingModal.min.js') }}"></script>


    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- <script src="dist/js/pages/dashboard.js"></script> -->


    <!-- AdminLTE for demo purposes -->
    <!-- <script src="dist/js/demo.js"></script> -->

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- <script src="dist/js/pages/dashboard.js"></script> -->

    <!-- pace -->
    <script src="{{ asset('pace/js/pace.min.js') }}"></script>
    <script type="text/javascript">

    $(document).ready(function () {
    var orderId = $('#orderid').val();
   // alert(orderId);
    var t = $('#products_table').DataTable({
        ajax: {

            "url": "/api/order-details-data/"+orderId,
            "dataSrc": "productList",
            "dataType": "json",
         	"type": "GET",
        },
        columns: [

            {
            	data: 'productName'
            },

            {
                data: 'quantity'
            },

            {
                data: 'totalPrice'
            },

            {
                data: 'totalDiscount'
            },
            {
                data: 'totalTax'
            },
            {
                data: 'grandTotal'
            }
        ],
        responsive: true,
        pageLength : 10,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });
});
    </script>
</body>
</html>
