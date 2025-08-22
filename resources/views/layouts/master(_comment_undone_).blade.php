<!-- {{ asset('dist/js/adminlte.js') }} -->
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

<body class="hold-transition sidebar-mini layout-fixed pace-primary">
    <div class="wrapper">

        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}"
                alt="AdminLTELogo" height="60" width="60">
        </div>
 -->
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">

            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{url('home')}}" class="nav-link">Dashboard</a>
                </li>

            </ul>

            

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item" style="margin-left: 100px; margin-bottom: 0px;">
                    <a class="nav-link" data-widget="" href="#" role="button" style="padding-top: 0px; margin-right: 0px; padding-right: 0px;">
                      <i class="fax fa fa-calendar-times"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="" href="#" role="button" style="padding-top: 0px; margin-right: 0px; padding-right: 0px;">
                      <i class="fax fa fa-exclamation-triangle"></i>
                    </a>
                </li>
                <li class="nav-item" style="margin-right: 50px;">
                    @if(session()->has('storeId') && session()->has('posId'))
                    <a class="nav-link" data-widget="" href="{{ url('/pos') }}" role="button" style="padding-top: 0px;">
                      <i class="fax fa fa-cash-register"></i>
                    </a>
                    @else
                    <a class="nav-link" data-widget="" href="{{ url('/pos-login') }}" role="button" style="padding-top: 0px;">
                      <i class="fax fa fa-cash-register"></i>
                    </a>
                    @endif 
                </li>
                  


                <li class="nav-item dropdown">
                    <a class="nav-link notification" data-toggle="dropdown" href="#" style="padding-top: 0px;">
                        <i class="fax fa fa-user-cog"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">Settings</span>
                        <div class="dropdown-divider"></div>
                        <a href="" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i>Profile
                        </a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                            class="d-none">
                            @csrf
                        </form>


                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="#" class="brand-link">
                <img src="{{ asset('uploads/subscriber/logo/').'/'}}{{ session()->get('orgLogo') }}" alt="Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ session()->get('orgNameMaster') }}</span>
            </a>
            <!-- Sidebar -->
            
                <div class="sidebar">

                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-2 pb-2 mb-2">
                        <div class="image">

                            <img src="{{ asset('dist/img/user1.jpg') }}" class="img-circle elevation-2"
                                alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">{{auth()->user()->name}}</a>
                        </div>
                    </div>


                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                            data-accordion="false">
                            <li class="nav-item">
                                <a href="{{ url('/home') }}" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-bag"></i>
                                    <p>
                                        Products
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/product-create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add Product</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/product-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Product List</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/category-create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Add Category
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/category-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Category List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/subcategory-create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Add Sub-Category</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/subcategory-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Sub-Category List</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/brand-create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Add Brand
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/brand-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Brand List
                                            </p>
                                        </a>
                                    </li>
                                </ul>

                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/batch-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Batches</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/leaf-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Leaf Setting</p>
                                        </a>
                                    </li>
                                </ul>

                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Print Labels*</p>
                                        </a>
                                    </li>
                                </ul> -->
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Import Items*</p>
                                        </a>
                                    </li>
                                </ul> -->
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/product-in') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Product In</p>
                                        </a>
                                    </li>
                                </ul>
                                
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/product-transfer') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Product Transfer</p>
                                        </a>
                                    </li>
                                </ul>
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Product Adjustment*</p>
                                        </a>
                                    </li>
                                </ul> -->
                            </li>

                             <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Customer
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/customer-create') }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                New Customer
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/customer-list') }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Customer List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Customer Ledger*
                                            </p>
                                        </a>
                                    </li>
                                </ul> -->
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/customer-credits') }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Customer Credit
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-user-friends"></i>
                                    <p>
                                        Supplier
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/supplier-create') }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                New Supplier
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/supplier-list') }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Supplier List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Supplier Ledger*
                                            </p>
                                        </a>
                                    </li>
                                </ul> -->
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Supplier Credit*
                                            </p>
                                        </a>
                                    </li>
                                </ul> -->
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-cart"></i>
                                    <p>
                                        Sales
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                @if(session()->has('storeId') && session()->has('posId'))
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/pos') }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                POS
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                @else
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/pos-login') }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                POS
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                
                                @endif

                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                New Sales*
                                            </p>
                                        </a>
                                    </li>
                                </ul> -->
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/order-list') }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Sales List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/sales-return-create') }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                New Sales Return
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/sales-return-list') }}" class="nav-link">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Sales Return List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-money-check-alt"></i>
                                    <p>
                                        Purchase
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/purchase-create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>New Purchase</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/purchase-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Purchase List</p>
                                        </a>
                                    </li>
                                </ul>
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Purchase Return*</p>
                                        </a>
                                    </li>
                                </ul> -->
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Purchase Return List*</p>
                                        </a>
                                    </li>
                                </ul> -->
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-warehouse"></i>
                                    <p>
                                        Stock
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/inventory-stock-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Warehouse Stock (Batch)</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/warehouse-stock-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Warehouse Stock</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/store-stock-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Store Stock (Batch)</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/store-stock') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Store Stock</p>
                                        </a>
                                    </li>
                                </ul>
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Available Stock*</p>
                                        </a>
                                    </li>
                                </ul> -->
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/expired-stock') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Store Expired Products</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/inventory-expired-stock') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Warehouse Expired <span class="p-4 ml-2">Products</span></p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/low-stock') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Low Stock (Store)</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/inventory-low-stock') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Low Stock (Warehouse)</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>
                                        Reports
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <!-- <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            
                                            <p class="pl-4">Purchase Report*</p>
                                        </a>
                                    </li>
                                </ul> -->
                                <!-- <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            
                                            <p class="pl-4">Purchase Return Report*</p>
                                        </a>
                                    </li>
                                </ul> -->
                                <!-- <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <p class="pl-4">Supplier Payment Report*</p>
                                        </a>
                                    </li>
                                </ul> -->
                                <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="{{ url('/reports') }}" class="nav-link">
                                            
                                            <p class="pl-4">Sales Report (Product)</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="{{ url('/employee-reports') }}" class="nav-link">
                                            
                                            <p class="pl-4">Sales Report (Employee)</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="{{ url('/sales-return-reports') }}" class="nav-link">
                                            
                                            <p class="pl-4">Sales Return Report</p>
                                        </a>
                                    </li>
                                </ul>
                                <!-- <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            
                                            <p class="pl-4">Sales Payments Report*</p>
                                        </a>
                                    </li>
                                </ul> -->
                                <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="{{ url('/expense-reports') }}" class="nav-link">
                                            
                                            <p class="pl-4">Expense Report</p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="{{ url('/expense-store-reports') }}" class="nav-link">
                                            <!-- <i class="far fa-circle nav-icon"></i> -->
                                            <p class="pl-4">
                                                Expense Report (Store)
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="{{ url('/due-reports') }}" class="nav-link">
                                            <!-- <i class="far fa-circle nav-icon"></i> -->
                                            <p class="pl-4">
                                                Due-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="{{ url('/product-in-reports') }}" class="nav-link">
                                            <!-- <i class="far fa-circle nav-icon"></i> -->
                                            <p class="pl-4">
                                                Product-In-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="{{ url('/deposit-reports') }}" class="nav-link">
                                            <!-- <i class="far fa-circle nav-icon"></i> -->
                                            <p class="pl-4">
                                                Deposit-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="{{ url('/summary-reports') }}" class="nav-link">
                                            <!-- <i class="far fa-circle nav-icon"></i> -->
                                            <p class="pl-4">
                                                Summary-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <!-- <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            
                                            <p class="pl-4">Add Closing (Day/Shift)*</p>
                                        </a>
                                    </li>
                                </ul> -->
                                <!-- <ul class="nav nav-treeview pl-2">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            
                                            <p class="pl-4">Closing List*</p>
                                        </a>
                                    </li>
                                </ul> -->
                            </li>

                            <!-- <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-dollar-sign"></i>
                                    <p>
                                        Account*
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Chart of Accounts*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Add Account*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Account List*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Opening Balance*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Supplier Payment*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Customer Receive*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Cash Adjustment*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Debit Voucher*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Credit Voucher*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Contra Voucher*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Journal Voucher*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Voucher List*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li> -->

                            <!-- <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                    <p>
                                        Accounting Report*
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Cash Book*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Bank Book*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                General Ledger*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Trial Balance*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Profit & Loss Report*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Cash Flow*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                COA Print*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Balance Sheet*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li> -->

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-file-invoice"></i>
                                    <p>
                                        Expense
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/expense-create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Add Expense
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/expense-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Expenses
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/expense-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Expense Report
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/expense-store-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Expense Report (Store)
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/expense-category-create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Add Expense Type
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/expense-category-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Expense Types
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-users-cog"></i>
                                    <p>
                                        Employee Settings*
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Employee*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Attendance*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Payroll*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Leave*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Loan*
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li> -->

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-wrench"></i>
                                    <p>
                                        Application Settings*
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Company Profile*
                                            </p>
                                        </a>
                                    </li>
                                </ul> -->
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Site Settings*
                                            </p>
                                        </a>
                                    </li>
                                </ul> -->
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/store-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Stores Settings
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/pos-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                POS Devices Settings
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/vat-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                VAT/Tax List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/discount-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Discounts List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/product-unit-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Units List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/payment-method-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Payment Types List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <!-- <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Currency List*
                                            </p>
                                        </a>
                                    </li>
                                </ul> -->
                            </li>

                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-user-cog"></i>
                                    <p>
                                        User Management*
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/create-user') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                New User
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/users-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Users List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/role-list') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Roles List
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-hammer"></i>
                                    <p>
                                        Settings
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/store-list') }}" class="nav-link">
                                            <i class="nav-icon fas fa-store"></i>
                                            <p>
                                                Stores
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/pos-list') }}" class="nav-link">
                                            <i class="nav-icon fas fa-store-alt"></i>
                                            <p>
                                                POS Devices
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/discount-list') }}" class="nav-link">
                                            <i class="nav-icon fa fa-percent" aria-hidden="true"></i>
                                            <p>
                                                Discounts
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/vat-list') }}" class="nav-link">
                                            <i class="nav-icon fas fa-wallet"></i>
                                            <p>
                                                Vats
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                
                                
                            </li> -->
                            
                            
                            
                            
                            <!-- <li class="nav-item">
                                <a href="{{ url('/brand-list') }}" class="nav-link">
                                    <i class="nav-icon fas fa-copyright"></i>
                                    <p>
                                        Brands
                                    </p>
                                </a>
                            </li> -->
                           <!--  <li class="nav-item">
                                <a href="product-list" class="nav-link">
                                    <i class="nav-icon fas fa-shopping-basket"></i>
                                    <p>
                                        Product
                                    </p>
                                </a>
                            </li> -->
                            <!-- <li class="nav-item">
                                <a href="{{ url('/order-list') }}" class="nav-link">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>
                                        Orders
                                    </p>
                                </a>
                            </li> -->
                            
                            
                            <!-- <li class="nav-item">
                                <a href="{{ url('/role-list') }}" class="nav-link">
                                    <i class="nav-icon fas fa-user-cog"></i>
                                    <p>
                                        User Management
                                    </p>
                                </a>
                            </li>
 -->
                            <!-- <li class="nav-item">
                                <a href="{{url('/employee-list')}}" class="nav-link">
                                    <i class="nav-icon fas fa-users-cog"></i>
                                    <p>
                                        Employees
                                    </p>
                                </a>
                            </li> -->
                           


                            <!-- <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>
                                        Reports
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                     <li class="nav-item pl-3">
                                        <a href="{{ url('/reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Product-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item pl-3">
                                        <a href="{{ url('/employee-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Employee-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item pl-3">
                                        <a href="{{ url('/due-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Due-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item pl-3">
                                        <a href="{{ url('/product-in-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Product-In-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item pl-3">
                                        <a href="{{ url('/expense-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Expense-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item pl-3">
                                        <a href="{{ url('/deposit-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Deposit-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item pl-3">
                                        <a href="{{ url('/summary-reports') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Summary-Reports
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                                
                            </li> -->

                            <!-- <li class="nav-item">
                                @if(session()->has('storeId') && session()->has('posId'))
                                <a href="{{ url('/pos') }}" class="nav-link">
                                    <i class="nav-icon fas fa-cash-register"></i>
                                    <p>
                                        POS
                                    </p>
                                </a>
                                @else
                                <a href="{{ url('/pos-login') }}" class="nav-link">
                                    <i class="nav-icon fas fa-cash-register"></i>
                                    <p>
                                        POS
                                    </p>
                                </a>
                                @endif
                            </li> -->
                            <!-- <li class="nav-item">
                                <a href="{{ url('/product-unit-list') }}" class="nav-link">
                                    <i class="nav-icon fas fa-balance-scale-right"></i>
                                    <p>
                                        Product Unit
                                    </p>
                                </a>
                            </li> -->
                            
                        </ul>
                    </nav>
                    <!-- /.sidebar-menu -->
                </div>
                <!-- /.sidebar -->
        </aside>

        <!-- Main content -->
        @yield('content')






        <div class="layout-footer-fixed">
            <footer class="main-footer">
                <strong>Copyright &copy; <a href="https://inovexidea.com/">INovex Idea Solution</a>.</strong>
                All rights reserved.
            </footer>
        </div>
        

    </div>
    <!-- ./wrapper -->


    <!-- REQUIRED SCRIPTS -->
    @yield('script')

    <!-- jQuery -->
    @yield('jQuery')

    <!-- jQuery -->

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            var url = window.location;
            // for single sidebar menu
            $('ul.nav-sidebar a').filter(function () {
                return this.href == url;
            }).addClass('active');

            // for sidebar menu and treeview
            $('ul.nav-treeview a').filter(function () {
                return this.href == url;
            }).parentsUntil(".nav-sidebar > .nav-treeview")
                .css({'display': 'block'})
                .addClass('menu-open').prev('a')
                .addClass('active');
        });


    </script>

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

</body>

</html>
