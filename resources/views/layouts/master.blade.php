<!-- {{ asset('dist/js/adminlte.js') }} -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('images/title-image.jpeg') }}" type="image/icon type">
{{-- @stack('styles') --}}

    <style type="text/css">
    .pos-card-text-title {
        font-size: 16px;
        font-weight: ;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }

    .pos-card-text-body {
        font-size: 14px;
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

    .round input[type="checkbox"]:checked+label {
        background-color: #66bb6a;
        border-color: #66bb6a;
    }

    .round input[type="checkbox"]:checked+label:after {
        opacity: 1;
    }

    i.fax {
        display: inline-block;
        background-color: #ededed;
        border-radius: 60px;
        box-shadow: 0 0 2px #ededed;
        padding: 0.8em 0.9em;


    }

    body {
        font-size: 3.2vw;
    }
    </style>


    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Bootstrap 5 -->
    <link href="{{ asset('css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap/bootstrap.min.css.map') }}">


    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css"> -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <!-- Data table -->

    <link rel="stylesheet" href="{{ asset('dataTable/datatables.min.css') }}">

    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
        CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION
                                            IMPORTANT FOR DATATABLE SOLUTION
    -->



    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">


    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">


    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">

    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">

    <!-- Select2 -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap-select.min.css') }}">


    <!-- DataTable -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"> -->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.11.5/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.2/sp-2.0.0/sl-1.3.4/sr-1.1.0/datatables.min.css"/>-->
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css"/>  -->

    <!-- PrintJS -->
    <link rel="stylesheet" href="{{ asset('dist/css/print.min.css') }}">

    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/css/fileinput.min.css" media="all"
        rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/css/fileinput-rtl.min.css" media="all"
        rel="stylesheet" type="text/css" />

    <!-- jquery loader -->
    <link rel="stylesheet" href="{{ asset('loader/css/jquery.loadingModal.min.css') }}">
    <link rel="stylesheet" href="{{ asset('loader/scss/jquery.loadingModal.scss') }}">

    <!-- Pace  -->
    <link rel="stylesheet" href="{{ asset('pace/css/pace-theme-flat-top.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('pace/css/pace-theme-loading-bar.css') }}"> -->

    <!-- jQuery treeview -->
    <!-- <link rel="stylesheet" href="{{ asset('dist/css/bstreeview.min.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('dist/themes/default/style.min.css') }}" />
    <!-- <link rel="stylesheet" href="{{ asset('dist/themes/default-dark/style.min.css') }}" /> -->

    <!-- Loading Modal -->
    <link rel="stylesheet" href="{{ asset('loading-modal/css/jquery.loadingModal.css') }}">

    <!-- JQUERY -->
    <script src="{{ asset('dist/js/jquery 3.5.1/jquery.min.js') }}"></script>

    <!-- <style type="text/css">
        .main-sidebar { background-color: $your-color !important }
    </style> -->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="d-flex flex-column min-vh-100 sidebar-mini layout-fixed pace-primary">

    <div class="wrapper">

        <!-- Preloader -->
        {{-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}"
        alt="AdminLTELogo" height="60" width="60">
    </div> --}}
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" id="selfclick" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ url('home') }}" class="nav-link fw-bold">
                    <i class="fas fa-mobile-alt me-1"></i> @yield('title')
                </a>
            </li>
        </ul>




        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- pos -->
            {{-- <li class="nav-item">
                    @if (session()->has('posName'))
                    <button class="btn btn-success">{{ session()->get('posName') }}</button>
            @endif
            </li> --}}
            {{-- @can('expired-stock.index.view')
                <li class="nav-item" style="margin-left: 100px; margin-bottom: 0px;">
                    <a class="nav-link" data-widget="" href="{{ url('/expired-stock') }}" role="button"
            style="padding-top: 0px; margin-right: 0px; padding-right: 0px;">
            <i class="fax fa fa-calendar-times"></i>
            </a>
            </li>
            @endcan
            @can('low.stock.index.view')
            <li class="nav-item" style="margin-left: 25px;">
                <a class="nav-link" data-widget="" href="{{ url('/low-stock') }}" role="button"
                    style="padding-top: 0px; margin-right: 0px; padding-right: 0px;">
                    <i class="fax fa fa-exclamation-triangle"></i>
                </a>
            </li>
            @endcan
            @can('pos.index.view')
            <li class="nav-item">
                @if(session()->has('storeId') && session()->has('posId'))
                <a class="nav-link" data-widget="" href="{{ url('/pos') }}" role="button" style="padding-top: 0px;">
                    <i class="fax fa fa-cash-register"></i>
                </a>
                @else
                <a class="nav-link" data-widget="" href="{{ url('/pos-login') }}" role="button"
                    style="padding-top: 0px;">
                    <i class="fax fa fa-cash-register"></i>
                </a>
                @endif
            </li>
            @endcan --}}
            <li class="nav-item">
                <a class="nav-link" data-widget="" role="button" onclick="openFullscreen();"
                    style="padding-top: 0px; margin-right: 0px; padding-right: 0px;">
                    <i class="fax fa fa-tv"></i>
                </a>
            </li>
            <!-- Notifications Dropdown Menu -->


            <li class="nav-item dropdown">
                <a class="nav-link notification" data-toggle="dropdown" href="#" style="padding-top: 0px;">
                    <i class="fax fa fa-user-cog"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">Settings</span>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('profile') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i>Profile
                    </a>

                    <div class="dropdown-divider"></div>
                    <a href="{{ route('reset.password') }}" class="dropdown-item">
                        <i class="fas fa-unlock mr-2"></i>Reset Password
                    </a>

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        {{ 'Logout' }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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
        <img src="{{ asset('images/raeno/raeno.png')}}" alt="Logo"
            class="img-circle elevation-3"
            style="max-width: 100px; max-height: 100px; width: 200%; height: auto; object-fit: contain; border-radius: 50%;">
    </a>
        <!-- Sidebar -->

        <div class="sidebar">

            <!-- Sidebar user panel (optional) -->
            {{-- <div class="user-panel mt-2 pb-2 mb-2">
                <div class="image">

                    <img src="{{ asset('dist/img/user1.jpg') }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                </div>
            </div> --}}
                    {{-- <img src="{{ asset('dist/img/user1.jpg') }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()->name }}</a>
                </div>
            </div> --}}


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

                        @canany(['category.list.view','subcategory.list.view','brand.list.view','manufacturer.list.view','model.list.view','product.list.view',])
                        {{-- 'product-excel.create', 'product.in.create', 'product.transfer.create', 'product.adjustment.create' --}}
                        <li class="nav-item {{ Request::is('category-*') || Request::is('subcategory-*') || Request::is('brand-*') || Request::is('manufacturer-*') || Request::is('model-create') || Request::is('product-details/*') || Request::is('product-edit/*') || Request::is('product-list') || Request::is('product-create') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('category-*') || Request::is('subcategory-*') || Request::is('brand-*') || Request::is('manufacturer-*') || Request::is('model-create') || Request::is('product-details/*') || Request::is('product-edit/*') || Request::is('product-list') || Request::is('product-create') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    Products
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>

                            @can('category.list.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/category-list') }}" class="nav-link {{ Request::is('category-list') || Request::is('category-create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Category</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan

                            @can('subcategory.list.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/subcategory-list') }}" class="nav-link {{ Request::is('subcategory-list') || Request::is('subcategory-create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Sub-Category</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan

                            @can('brand.list.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/brand-list') }}" class="nav-link {{ Request::is('brand-list') || Request::is('brand-create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Brand</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan

                            @can('manufacturer.list.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/manufacturer-list') }}" class="nav-link {{ Request::is('manufacturer-list') || Request::is('manufacturer-create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Manufacturer</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan

                            @can('model.list.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/model-list') }}" class="nav-link {{ Request::is('model-list') || Request::is('model-create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Model</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan

                            @can('product.list.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/product-list') }}" class="nav-link {{ Request::is('product-list') || Request::is('product-create') || Request::is('product-edit/*') || Request::is('product-details/*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Product</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan

                            {{-- @can('product-excel.create')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/product-excel-import') }}" class="nav-link {{ Request::is('product-excel-import') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Excel Upload</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan

                            @can('product.in.create')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/product-in') }}" class="nav-link {{ Request::is('product-in') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Product In</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan

                            @can('product.transfer.create')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/product-transfer') }}" class="nav-link {{ Request::is('product-transfer') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Product Transfer</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan

                            @can('product.adjustment.create')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/product-adjustment') }}" class="nav-link {{ Request::is('product-adjustment') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Product Adjustment</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan --}}
                        </li>
                        @endcanany

                        @canany(['pricing-list.index.view', 'pricing.index.view'])
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-receipt"></i>
                                <p>
                                    Pricing
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            @can('pricing-list.index.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/pricing-list') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pricing list</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('pricing.index.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/pricing-report') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pricing Report</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                        </li>
                        @endcanany

                        @canany(['imei.in.view', 'info.view'])
                        <li class="nav-item has-treeview {{ Request::is('imei-in*') || Request::is('imei-info*') || Request::is('filter-imei-info*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('imei-in*') || Request::is('imei-info*') || Request::is('filter-imei-info*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-mobile-alt"></i>
                                <p>
                                    IMEI Database
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            @can('imei.in.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/imei-in') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>IMEI In</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('info.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/imei-info') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>IMEI Info</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/filter-imei-info') }}" class="nav-link {{ Request::is('filter-imei-info*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Filter IMEI Info</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                        </li>
                        @endcanany

                        @canany(['primary.in.view', 'primary.stock.view'])
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-receipt"></i>
                                <p>
                                    Primary Stock
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            @can('primary.in.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/primary-in') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Primary Stock In</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('primary.stock.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/primary-stock') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Primary In Devices</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                        </li>
                        @endcanany

                        @canany(['distributor.index.view', 'dum.index.view'])
                        <li class="nav-item {{ Request::is('distributor') || Request::is('distributor-create') || Request::is('dum*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('distributor') || Request::is('distributor-create') || Request::is('dum*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    Distributors
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                                @can('distributor.index.view')
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/distributor') }}" class="nav-link {{ Request::is('distributor') || Request::is('distributor-create') || Request::is('distributor/*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Distributors</p>
                                        </a>
                                    </li>
                                </ul>
                                @endcan

                                @can('dum.index.view')
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/dum') }}" class="nav-link {{ Request::is('dum') || Request::is('dum/*') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Distributors User Map</p>
                                        </a>
                                    </li>
                                </ul>
                                @endcan

                                {{-- @can('approved-sales-order.index.view') --}}
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/approved-sales-order') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Approved Sales Order</p>
                                        </a>
                                    </li>
                                </ul>
                                {{-- @endcan --}}
                        </li>
                        @endcan

                        @canany(['distributor_requisition.index.view','account-requisition.index.view','sales-requisition.index.view','operation-requisition.index.view','approved-requisitions.index.view','all-type-requisitions.index.view'])
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-dolly"></i>
                                <p>
                                    Requisitions
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-3">
                                    @can('distributor_requisition.index.view')
                                    <li class="nav-item">
                                        <a href="{{ url('/distributor-requisition/index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Sales Requisition</p>
                                        </a>
                                    </li>
                                    @endcan
                                @can('account-requisition.index.view')
                                <li class="nav-item">
                                    <a href="{{ route('account-requisition.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>ACC Requisition</p>
                                    </a>
                                </li>
                                @endcan
                                @can('sales-requisition.index.view')
                                <li class="nav-item">
                                    <a href="{{ route('sales-requisition.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>HOS Requisition</p>
                                    </a>
                                </li>
                                @endcan
                                {{-- @can('operation-requisition.index.view')
                                <li class="nav-item">
                                    <a href="{{ route('operation-requisition.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>HOP Requisition</p>
                                    </a>
                                </li>
                                @endcan --}}
                                @can('approved-requisitions.index.view')
                                <li class="nav-item">
                                    <a href="{{ route('approved-requisitions.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Approved Requisitions</p>
                                    </a>
                                </li>
                                @endcan
                                @can('all-type-requisitions.index.view')
                                 <li class="nav-item">
                                    <a href="{{ route('all-type-requisitions.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>All Requisitions</p>
                                    </a>
                                </li>
                                @endcan

                            </ul>
                        </li>
                        @endcanany

                        @canany(['delivery.index.view', 'delivery.all.view','delivery-devices.view'])
                        <li class="nav-item has-treeview {{ Request::is('delivery*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('delivery*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-shipping-fast"></i>
                                <p>
                                    Delivery
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-3">
                                @can('delivery.index.view')
                                <li class="nav-item">
                                    <a href="{{ url('/delivery-request') }}" class="nav-link {{ Request::is('delivery-request') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Delivery Request</p>
                                    </a>
                                </li>
                                @endcan
                                @can('delivery.all.view')
                                <li class="nav-item">
                                    <a href="{{ url('/delivery-sent') }}" class="nav-link {{ Request::is('delivery-sent') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Sent Deliveries</p>
                                    </a>
                                </li>
                                @endcan
                                @can('delivery-devices.view')
                                <li class="nav-item">
                                    <a href="{{ url('/delivery-devices') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Delivered Devices</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        @canany(['receive.index.view', 'receivable.index.view', 'received.data.view', 'distributor.out.view'])
                        <li class="nav-item has-treeview {{ Request::is('receive*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('receive*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-receipt"></i>
                                <p>
                                    Distributor Receive
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            @can('receivable.index.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/order-receivable') }}" class="nav-link {{ Request::is('receive-order/*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Order Receivable</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('receive.index.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/receive-order') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Received Order</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('received.data.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/distributor-in') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Received Devices</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('distributor.out.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/distributor-out') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Sell Out</p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                        </li>
                        @endcanany

                        @canany(['return.store.create', 'return.all.view'])
                        <li class="nav-item has-treeview {{ Request::is('product-return*') || Request::is('return-details/*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('product-return*') || Request::is('return-details/*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-shipping-fast"></i>
                                <p>
                                    Product Return
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-3">
                                @can('return.store.create')
                                <li class="nav-item">
                                    <a href="{{ url('/product-return') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Product Return</p>
                                    </a>
                                </li>
                                @endcan
                                @can('return.all.view')
                                <li class="nav-item">
                                    <a href="{{ url('/product-return-list') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Product Return List</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/returned-devices') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Returned Devices</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        @canany(['retails.index.view', 'retailer.stock.view', 'retailer.sell.view'])
                        <li class="nav-item {{ Request::is('retails') || Request::is('retails/create') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('retails') || Request::is('retails/create') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-shopping-bag"></i>
                                <p>
                                    Retailer
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>

                            @can('retails.index.view')
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/retails') }}" class="nav-link {{ Request::is('retails') || Request::is('retails/create') ? 'active' : '' }}">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Retailers</p>
                                        </a>
                                    </li>
                                </ul>
                            @endcan
                            @can('retailer.stock.view')
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/retailer-stock') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Retailer Stock</p>
                                        </a>
                                    </li>
                                </ul>
                            @endcan
                            @can('retailer.sell.view')
                                <ul class="nav nav-treeview pl-3">
                                    <li class="nav-item">
                                        <a href="{{ url('/retailer-sell') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Sell Device</p>
                                        </a>
                                    </li>
                                </ul>
                            @endcan
                        </li>
                        @endcanany

                        @canany(['customer.create', 'customer.list.view', 'customer-credit.create.view', 'customer-deposit.create', 'customer.transection.view'])
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Customer
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            @can('customer.create')
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
                            @endcan
                            @can('customer.list.view')
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
                            @endcan
                            @can('customer-credit.create.view')
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
                            @endcan
                            @can('customer-deposit.create')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/deposit-create') }}" class="nav-link">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Customer Deposit
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('customer.transection.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/customer-transection-report') }}" class="nav-link">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Transection Report
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                        </li>
                        @endcanany

                        @canany(['supplier.create', 'supplier.list.view', 'supplier-payment.create', 'supplier.transection.view'])
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-friends"></i>
                                <p>
                                    Supplier
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            @can('supplier.create')
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
                            @endcan
                            @can('supplier.list.view')
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
                            @endcan
                            @can('supplier-payment.create')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/supplier-payment-create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Supplier Payment
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('supplier-payment.create')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/supplier-payment-report') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Supplier Payment Report
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('supplier.transection.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/supplier-transection') }}" class="nav-link">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Supplier Transection
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('supplier.transection.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/supplier-transection-report') }}" class="nav-link">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p>
                                            Transection Report
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                        </li>
                        @endcanany

                @canany(['purchase.create', 'purchase.list.view', 'purchase-return.create', 'purchase-return.list.view'])
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-money-check-alt"></i>
                        <p>
                            Purchase
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    @can('purchase.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/purchase-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>New Purchase</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('purchase.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/purchase-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Purchase List</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('purchase-return.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/purchase-return-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Purchase Return</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('purchase-return.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/purchase-return-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Purchase Return List</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcanany

                @canany(['primary.in.report.view','distributor.in.report.view','distributor.stock.reports.view','inventory.stock.report.view','warehouse.stock.reports.view','store.stock.reports.view','low.stock.index.view','inventory.low.stock.index.view'])
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>
                            Stock
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    @can('primary.in.report.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/primary-in-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Primary Stock
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('distributor.in.report.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/distributor-in-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Distributor Stock
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('inventory.stock.report.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/inventory-stock-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Warehouse Stock <span class="p-4 ml-2">(Variant)</span></p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('warehouse.stock.reports.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/warehouse-stock-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Warehouse Stock</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('store.stock.reports.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/store-stock-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Store Stock <span class="p-4 ml-2">(Variant)</span></p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('store.stock.reports.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/store-stock') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Store Stock</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('low.stock.index.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/low-stock') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Low Stock</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('inventory.low.stock.index.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/inventory-low-stock') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Low Stock (Warehouse)</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcanany

                @canany(['rsm.index.view', 'asm.index.view', 'tsm.index.view','regions.index.view','areas.index.view','territories.index.view'])
                <li class="nav-item {{ Request::is('regions*') || Request::is('areas*') || Request::is('territories*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('regions*') || Request::is('areas*') || Request::is('territories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map"></i>
                        <p>
                            Geo Mapping
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item {{ Request::is('regions*') || Request::is('areas*') || Request::is('territories*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('regions*') || Request::is('areas*') || Request::is('territories*') ? 'active' : '' }}">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Market Mapping
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview pl-3">
                                @can('regions.index.view')
                                <li class="nav-item">
                                    <a href="{{ url('/regions') }}" class="nav-link {{ Request::is('regions*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Region</p>
                                    </a>
                                </li>
                                @endcan
                                @can('areas.index.view')
                                <li class="nav-item">
                                    <a href="{{ url('/areas') }}" class="nav-link {{ Request::is('areas*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Area</p>
                                    </a>
                                </li>
                                @endcan
                                @can('territories.index.view')
                                <li class="nav-item">
                                    <a href="{{ url('/territories') }}" class="nav-link {{ Request::is('territories*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Territory</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon far fa-circle"></i>
                                <p>User Mapping</p>
                                <i class="fas fa-angle-left right"></i>
                            </a>
                            <ul class="nav nav-treeview pl-3">
                                @can('rsm.index.view')
                                <li class="nav-item">
                                    <a href="{{ url('/rsm') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>RSM</p>
                                    </a>
                                </li>
                                @endcan
                                @can('asm.index.view')
                                <li class="nav-item">
                                    <a href="{{ url('/asm') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>ASM</p>
                                    </a>
                                </li>
                                @endcan
                                @can('tsm.index.view')
                                <li class="nav-item">
                                    <a href="{{ url('/tsm') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>TSM</p>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </li>
                @endcanany

                @canany(['product.report.view','distributor.stock.reports.view','secondary.stock.report.view','secondary.in.reports.view','retail.in.out.view','employee.report.view','sales.return.report.create','due.report.details.view','primary.stock.reports.view','deposit.report.create','summary.report.view','product.in.report.create','leave-report.view'])
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            Reports
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    @can('product.report.view')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Report (Product)</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('employee.report.view')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/employee-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Report (Employee)</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    {{-- @can('sales.return.report.create')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/sales-return-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sales Return Report</p>
                            </a>
                        </li>
                    </ul>
                    @endcan --}}
                    @can('due.report.details.view')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/due-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Due-Reports
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('primary.stock.reports.view')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/primary-stock-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Primary-In Report</span></p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('distributor.stock.reports.view')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/distributor-stock-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Secondary In/Out Report</span></p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('secondary.stock.report.view')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/secondary-stock-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Distributor In/Out Report</span></p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('secondary.in.reports.view')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/secondary-in-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Distributor Stock Report</span></p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('retail.in.out.view')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/retailer-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Retail In/Out Report</span></p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('deposit.report.create')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/deposit-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Deposit-Reports
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('summary.report.view')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/summary-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Summary-Reports
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('product.in.report.create')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/profit-calculation-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Profit Calculation <span class="p-4 ml-2">Reports</span>
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('leave-report.view')
                    <ul class="nav nav-treeview pl-2">
                        <li class="nav-item">
                            <a href="{{ url('/leave-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Attendance Reports</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcanany

                @canany(['bank.create', 'bank.list.view'])
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-university nav-icon"></i>
                        <p>
                            Bank
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    @can('bank.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/bank-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Bank</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('bank.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/bank-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bank List</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcanany

                @canany(['chart-of-accounts.list.view','purchase-voucher.create','sales-voucher.create','expense-voucher.create','add-account.create','account-list.view','opening-balance.create','distributor-deposit.create.index','distributor-transaction-report.view','journal-voucher.create'])
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            Account
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    @can('chart-of-accounts.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/chart-of-accounts') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Chart of Accounts
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('purchase-voucher.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/purchase-voucher-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Purchase Voucher
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('sales-voucher.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/sales-voucher-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Sales Voucher
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('sales-return.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ route('sales-return.create.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Sales Return
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan


                    @can('expense-voucher.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/expense-voucher-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Expense Voucher
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('add-account.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/add-account') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Add Account
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('account-list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/account-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Account List
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('opening-balance.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/opening-balance-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Opening Balance
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('distributor-deposit.create.index')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ route('distributor-deposit.create.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Distributor Deposit
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                     @can('distributor-transaction-report.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ route('distributor-transaction-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Distributor Ledger
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('operating-expense-voucher.create')
                     <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/operating-expense-voucher-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Operating Expense Voucher
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('journal-voucher.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/journal-voucher-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Adjustment Voucher
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcanany

                @canany(['trial-balance.view','income-statement.view','balance-sheet.view','bank.report.view','purchase-voucher.create','sales-voucher.create','expense-voucher.create','opening-balance.create','customer-receive.create','journal-voucher.create','report.general-ledger.view'])
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>
                            Accounting Report
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    {{-- @can('trial-balance.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/trial-balance') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Trial Balance
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan --}}
                    {{-- @can('income-statement.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/income-statement') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Income Statement
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan --}}
                    {{-- @can('balance-sheet.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/balance-sheet') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Balance Sheet
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan --}}
                    @can('bank.report.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/bank-reports') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Bank Book</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('purchase-voucher.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/purchase-voucher-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Purchase Voucher Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('sales-voucher.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/sales-voucher-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Sales Voucher Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('distributor-deposit.create.index')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ route('distributor-deposit.report.view') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Distributor Deposit Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('sales-return.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ route('sales-return.report.view') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Sales Return Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('expense-voucher.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/expense-voucher-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Expense Voucher Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('opening-balance.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/opening-balance-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Opening Balance Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    {{-- @can('customer-receive.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/customer-receive-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Customer Receive Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan --}}
                    @can('distributor-sales-summary-report.view')
                     <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/distributor-sales-summary-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Distributor Sales Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('journal-voucher.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/journal-voucher-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Adjustment Voucher Report
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('report.general-ledger.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/general-ledger') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    General Ledger
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcanany

                @canany(['expense.create', 'expense.list.view', 'expense.report.create', 'expense.store.report.view', 'expense.category.create', 'expense.category.list.view'])
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Expense
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    @can('expense.create')
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
                    @endcan
                    @can('expense.list.view')
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
                    @endcan
                    @can('expense.report.create')
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
                    @endcan
                    @can('expense.store.report.view')
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
                    @endcan
                    @can('expense.category.create')
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
                    @endcan
                    @can('expense.category.list.view')
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
                    @endcan
                </li>
                @endcanany
                @canany([
                    'designation.create', 'designation.list.view','employee-department.create', 'employee-department.list.view','employee.create', 'employee.list.view',
                    'shift.list.view', 'holiday.list.view', 'weekend.list.view',
                    'leave-apply.create', 'leave-apply.list.view','attendance.create.view','benefit.create', 'benefit.list.view','salary.create', 'salary.list.view',
                    'salary-create.create.view', 'weekly-salary-create.create.view','pay-benefit.create.view'
                ])
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>
                            Employee Management
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    @can('designation.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/add-designation') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Add Designation
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('designation.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/designation-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Designations
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('employee-department.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/employee-department-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Add Department
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('employee-department.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/employee-department-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Departments
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('employee.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/employee-create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Add Employee
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('employee.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/employee-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Employee List
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('shift.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/shift-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Shift
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('shift.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/employeeshift') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Shift Wise Employee
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('holiday.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/holiday-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Holiday
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('weekend.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/weekend-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Weekend
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('leave-apply.create')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/leave-apply') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Apply Leave
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    {{-- @can('leave-type.list.view') --}}
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/leave-type-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Leave Types
                                </p>
                            </a>
                        </li>
                    </ul>
                    {{-- @endcan --}}
                    @can('leave-apply.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/leave-apply-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Applied Leaves
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    @can('attendance.create.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/attendance') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Attendance
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>
                                    Payroll
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            @can('benefit.create')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/benefit-create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Add Benifit
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('benefit.list.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/benefit-list') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Benifit List
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('salary.create')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/salary-add') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Salary Setup
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('salary.list.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/salary-list') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Salary List
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('salary-create.create.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/salary-create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Add Salary (Monthly)
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('weekly-salary-create.create.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/weekly-salary-create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Add Salary (Weekly)
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                            @can('pay-benefit.create.view')
                            <ul class="nav nav-treeview pl-3">
                                <li class="nav-item">
                                    <a href="{{ url('/pay-benefit-create') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Pay Benefit
                                        </p>
                                    </a>
                                </li>
                            </ul>
                            @endcan
                        </li>
                    </ul>
                    @can('')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Leave
                                </p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcanany

                {{-- @canany(['delivery.index.view', 'delivery.all.view','delivery-devices.view']) --}}
                <li class="nav-item has-treeview {{ Request::is('kpi-create') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Request::is('kpi-create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>
                            KPI
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview pl-3">
                        {{-- @can('delivery.index.view') --}}
                        <li class="nav-item">
                            <a href="{{ url('/kpi-monthly') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Monthly / Quarterly</p>
                            </a>
                        </li>
                        {{-- @endcan --}}
                        {{-- @can('delivery.all.view') --}}
                        <li class="nav-item">
                            <a href="{{ url('/kpi-model') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Model Based</p>
                            </a>
                        </li>
                        {{-- @endcan --}}
                        {{-- @can('delivery.all.view') --}}
                        <li class="nav-item">
                            <a href="{{ url('/monthly-kpi-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Monthly KPI Report</p>
                            </a>
                        </li>
                        {{-- @endcan --}}
                        {{-- @can('delivery.all.view') --}}
                        <li class="nav-item">
                            <a href="{{ url('/quarterly-kpi-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Quarterly KPI Report</p>
                            </a>
                        </li>
                        {{-- @endcan --}}
                        {{-- @can('delivery.all.view') --}}
                        <li class="nav-item">
                            <a href="{{ url('/model-kpi-report') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Model KPI Report</p>
                            </a>
                        </li>
                        {{-- @endcan --}}
                        {{-- @can('delivery.all.view') --}}
                        <li class="nav-item">
                            <a href="{{ url('/region-area-terrritory') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>RSM ASM TSM</p>
                            </a>
                        </li>
                        {{-- @endcan --}}
                        {{-- @can('delivery.all.view') --}}
                        <li class="nav-item">
                            <a href="{{ url('/model-rsm') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Model Based</p>
                            </a>
                        </li>
                        {{-- @endcan --}}
                    </ul>
                </li>
                {{-- @endcanany --}}

                @canany(['stores.list.view','transports.index.view', 'pos.list.view', 'vat.list.view','discount.list.view', 'product-unit.list.view','payment-method.list.view'])
                <li class="nav-item {{ request()->is([
                        'warehouse*', 'pos*', 'vat*', 'discount*',
                        'product-unit*', 'payment-method*', 'transports*'
                    ]) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is([
                        'warehouse*', 'pos*', 'vat*', 'discount*',
                        'product-unit*', 'payment-method*', 'transports*'
                    ]) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-wrench"></i>
                        <p>
                            Application Settings
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    @can('stores.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/warehouse-list') }}" class="nav-link {{ request()->is('warehouse*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Store Settings</p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('pos.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/pos-list') }}" class="nav-link {{ request()->is('pos*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>POS Devices Settings</p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('vat.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/vat-list') }}" class="nav-link {{ request()->is('vat*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>VAT/Tax List</p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('discount.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/discount-list') }}" class="nav-link {{ request()->is('discount*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Discounts List</p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('product-unit.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/product-unit-list') }}" class="nav-link {{ request()->is('product-unit*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Units List</p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('payment-method.list.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/payment-method-list') }}" class="nav-link {{ request()->is('payment-method*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Payment Types List</p>
                            </a>
                        </li>
                    </ul>
                    @endcan

                    @can('transports.index.view')
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/transports') }}" class="nav-link {{ request()->is('transports*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Transport List</p>
                            </a>
                        </li>
                    </ul>
                    @endcan
                </li>
                @endcanany
                @role('Admin|MIS')
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>
                            User Management
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
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/permission-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Permission List
                                </p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview pl-3">
                        <li class="nav-item">
                            <a href="{{ url('/permission-group-list') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Permission Group List
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Main content -->
   <div class="content flex-grow-1">
       @yield('content')
   </div>


    </div>
    <!-- ./wrapper -->

    <div class="layout-footer-fixed">
        <footer class="main-footer">
            <strong>Copyright &copy; <a href="https://inovexidea.com/">INovex Idea Solution</a>.</strong>
            All rights reserved.
        </footer>
    </div>


    <!-- REQUIRED SCRIPTS -->
    @yield('script')

    <!-- jQuery -->
    @yield('jQuery')

    <!-- jQuery -->

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <script type="text/javascript">
    var elem = document.documentElement;

    function openFullscreen() {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) {
            /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            /* IE11 */
            elem.msRequestFullscreen();
        }
        if (!window.screenTop && !window.screenY) {
            // alert('Browser is in fullscreen');
            closeFullscreen()
        }
    }

    /* Close fullscreen */
    function closeFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            /* IE11 */
            document.msExitFullscreen();
        }
        if (window.screenTop && window.screenY) {
            // alert('Browser is in fullscreen');
            openFullscreen()
        }
    }
    $(function() {
        var url = window.location;
        var length = url.toString().length - 1
        lastChar = url.toString()[length]

        if (lastChar === "#") {
            url = url.toString().slice(0, -1)
        }

        // for single sidebar menu
        $('ul.nav-sidebar a').filter(function() {
            return this.href == url;
        }).addClass('active');

        // for sidebar menu and treeview
        $('ul.nav-treeview a').filter(function() {
                return this.href == url;
            }).parentsUntil(".nav-sidebar > .nav-treeview")
            .css({
                'display': 'block'
            })
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
    <script src="{{ asset('dist/js/umd/popper.min.js') }}"></script>
    {{-- <script src="{{asset('dist/js/umd/popper.min.js.map')}}"> </script> --}}

    <script src="{{ asset('dist/js/bootstrap/bootstrap.min.js') }}"></script>

    <!-- DataTable -->

    <script src="{{ asset('dataTable/datatables.min.js') }}"></script>
    <script src="{{ asset('dataTable/Buttons-2.2.2/js/buttons.bootstrap.min.js') }}"></script>
    <script src="{{ asset('dataTable/JSZip-2.5.0/jszip.min.js') }}"></script>
    <script src="{{ asset('dataTable/pdfmake-0.1.36/pdfmake.js') }}"></script>
    <script src="{{ asset('dataTable/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dataTable/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dataTable/Buttons-2.2.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dataTable/Buttons-2.2.2/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dataTable/Responsive-2.2.9/js/dataTables.responsive.min.js') }}"></script>


    <!-- <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

        CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION CAUTION
                                            IMPORTANT FOR DATATABLE SOLUTION
    -->


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
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

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
    <script src="{{ asset('dist/js/bootstrap/bootstrap-select.min.js') }}"></script>

    <!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
    wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <!-- <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/js/plugins/piexif.min.js"
        type="text/javascript"></script> -->
    <script type="text/javascript" src="{{ asset('kartik-v-bootstrap-fileinput-ab06a9c/js/plugins/piexif.min.js') }}">
    </script>

    <!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.
        This must be loaded before fileinput.min.js -->
    <!-- <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/js/plugins/sortable.min.js"
        type="text/javascript"></script> -->
    <script type="text/javascript" src="{{ asset('kartik-v-bootstrap-fileinput-ab06a9c/js/plugins/sortable.min.js') }}">
    </script>

    <!-- bootstrap.bundle.min.js below is needed if you wish to zoom and preview file content in a detail modal
        dialog. bootstrap 5.x or 4.x is supported. You can also use the bootstrap js 3.3.x versions. -->
    <script src="{{ asset('dist/js/bootstrap/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>

    <!-- the main fileinput plugin script JS file -->
    <!-- <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/js/fileinput.min.js"></script> -->
    <script type="text/javascript" src="{{ asset('kartik-v-bootstrap-fileinput-ab06a9c/js/fileinput.min.js') }}">
    </script>

    <!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`). Uncomment if needed. -->
    <!-- script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.2.5/themes/fas/theme.min.js"></script -->

    <!-- optionally if you need translation for your language then include the locale file as mentioned below (replace LANG.js with your language locale) -->
    <script src="{{ asset('dist/js/bootstrap-fileinput@5.2.5/LANG.js') }}"></script>

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
    <!-- jQuery treeView -->
    <!-- <script src="{{ asset('dist/js/bstreeview.min.js') }}"></script> -->
    <script src="{{ asset('dist/jstree.min.js') }}"></script>
    <script src="{{ asset('loading-modal/js/jquery.loadingModal.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>

</html>
