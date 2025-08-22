@extends('layouts.master')
@section('title', 'IMEI-In')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- Page Title can go here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                        @if (session('success'))
                            <div class="alert alert-success">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {!! session('error') !!}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{!! $error !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong><i class="fas fa-chevron-circle-down"></i> IMEI-IN</strong></h5>
                        </div>
                        <div class="card-body">

                            <div id="form_div">
                                {{-- <form id="" method="" enctype="multipart/form-data"> --}}
                                    <input type="hidden" name="store" value="1">
                                    <div class="row pt-3">

                                        <!-- Product Select -->
                                        <div class="form-group col-md-6 col-lg-2">
                                            <label for="product" class="font-weight-normal">Product<span class="text-danger">*</span></label>
                                            <select class="selectpicker form-control" data-live-search="true" name="product" id="product" data-width="100%">
                                                <option value="default" selected disabled>Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}"
                                                            data-name="{{ $product->productName }}"
                                                            data-model="{{ $product->model }}"> <!-- Add model here -->
                                                        {{ $product->productName }} ({{ $product->brand }} - {{ $product->category_name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Variant Select -->
                                        <div class="form-group col-md-6 col-lg-2">
                                            <label for="variant" class="font-weight-normal">Variant<span class="text-danger">*</span></label>
                                            <select class="selectpicker form-control" data-live-search="true" name="variant" id="variant" disabled data-width="100%">
                                                <option value="default" selected disabled>Select Variant</option>
                                                @foreach($variants as $variant)
                                                    <option value="{{ $variant->id }}" data-name="{{ $variant->variant_name }}">
                                                        {{ $variant->variant_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Selected Product Name Display -->
                                        <div class="form-group col-md-6 col-lg-2">
                                            <label>Selected Product Name:</label>
                                            <div class="input-group">
                                                <input type="text" id="selectedProductName" class="form-control" readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('selectedProductName')">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Selected Product Model Display -->
                                        <div class="form-group col-md-6 col-lg-2">
                                            <label>Selected Product Model:</label>
                                            <div class="input-group">
                                                <input type="text" id="selectedProductModel" class="form-control" readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('selectedProductModel')">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Selected Variant Name Display -->
                                        <div class="form-group col-md-6 col-lg-2">
                                            <label>Selected Variant Name:</label>
                                            <div class="input-group">
                                                <input type="text" id="selectedVariantName" class="form-control" readonly>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('selectedVariantName')">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reset Button -->
                                        <div class="col-md-6 col-lg-2 d-flex justify-content-center align-items-center" style="margin-top: 18px;">
                                            <button class="btn btn-outline-danger" type="reset" onclick="resetButton()">
                                                <i class="fas fa-eraser"></i> Reset
                                            </button>
                                        </div>

                                        <!-- Copy Flash Message -->
                                        {{-- <div class="col-md-6 col-lg-2 text-start mt-4" id="copyMessage" style="display:none; color: green; font-weight: bold;">
                                            Copied!
                                        </div> --}}
                                    </div>

                                    <form action="{{ route('imei.in.upload') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mt-5 justify-content-start">
                                            <div class="col-12 d-flex flex-wrap align-items-center">

                                                <!-- Left Group: File Input + Upload -->
                                                <div class="d-flex flex-wrap align-items-center gap-3">
                                                    <!-- File Upload Section -->
                                                    <div>
                                                        <div class="input-group">
                                                            <input type="file" name="excel_file" class="form-control" id="excelFile" accept=".xlsx, .xls" required>
                                                            <label class="input-group-text" for="excelFile">Browse</label>
                                                        </div>
                                                    </div>

                                                    <!-- Upload Button -->
                                                    <div>
                                                        <button type="submit" class="btn btn-info">
                                                            <i class="fas fa-upload"></i> Upload Excel
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Right-aligned Download Button -->
                                                <div class="ms-auto">
                                                    <a href="{{ asset('uploads/excel/imei_in.xlsx') }}" class="btn btn-success" download>
                                                        <i class="fas fa-download"></i> Download Demo Excel
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Error Message -->
                                            <div class="col-12 text-center">
                                                <h6 class="text-danger mt-2" id="errorMsg"></h6>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- Message Display Area -->
                                    <div class="row mt-3">
                                        <div class="card-header mt-0 bg-primary" style="padding-bottom: 5px;">
                                            <div class="row w-100">
                                                <div class="col-md-6 d-flex align-items-center">
                                                    <h5 class="mb-0 text-white">
                                                        <strong><i class="fas fa-list-alt"></i> IMEI List</strong>
                                                    </h5>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-end align-items-center gap-2">
                                                    <span style="color: rgb(217, 255, 0); font-weight: bold;">
                                                        Click Proceed After Check IMEI List Properly
                                                    </span>

                                                    <!-- Proceed Form -->
                                                    <form action="{{ route('imei-info.store') }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn bg-dark">
                                                            Proceed
                                                        </button>
                                                    </form>

                                                    <!-- Delete All Form -->
                                                    <form action="{{ route('imei-in.deleteAll') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete all IMEI entries?');">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger">
                                                            Delete All
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive" style="padding-top: 10px;">
                                            <table id="imeiTable" class="table table-bordered table-striped dt-responsive nowrap" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>IMEI 1</th>
                                                        <th>IMEI 2</th>
                                                        <th>Serial</th>
                                                        <th>Product Name</th>
                                                        <th>Model</th>
                                                        <th>Variant</th>
                                                        {{-- <th>Actions</th> --}}
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                {{-- </form> --}}
                            </div>

                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->

                </div> <!-- /.col-lg-12 -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $('#imeiTable').DataTable({
            ajax: {
                url: '{{ route('imei.data') }}',
                dataSrc: 'data'
            },
            responsive: true,
            columns: [
                {
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'imei_1',
                },
                { data: 'imei_2' },
                { data: 'serial_number' },
                { data: 'product_name' },
                { data: 'model' },
                { data: 'variant' },
                // {
                //     data: null,
                //     render: function(row) {
                //         return `
                //             <form action="/imei-in/${row.id}" method="POST" style="display:inline;">
                //                 @csrf
                //                 @method('DELETE')
                //                 <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                //                     <i class="fas fa-trash"></i>
                //                 </button>
                //             </form>`;
                //     },
                //     orderable: false,
                //     searchable: false
                // }
            ]
        });

        // Auto-hide success message
        const alertBox = document.querySelector('.alert-dismissible');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('fade');
                alertBox.style.display = 'none';
            }, 3000);
        }
    });
</script>

<style>
    /* #galleryTable_filter {
        margin-right: 10px;
    } */
</style>





<script>
    function resetButton() {
        $('#product').val('default').selectpicker('refresh');
        $('#variant').val('default').prop('disabled', true).selectpicker('refresh');

        $('#selectedProductName').val('');
        $('#selectedProductModel').val('');
        $('#selectedVariantName').val('');

        $('#copyMessage').hide();
    }

    $('form').on('reset', function() {
        setTimeout(function() {
            $('.selectpicker').selectpicker('refresh');

            $('#selectedProductName').val('');
            $('#selectedProductModel').val('');
            $('#selectedVariantName').val('');
            $('#copyMessage').hide();
        }, 0);
    });

    $('#product').on('change', function() {
        var productId = $(this).val()
        var productName = $("#product").find("option:selected").text()

        $('#variant').prop("disabled", false);

        $.ajax({
            type: "GET",
            url: "/product-wise-variant/"+productId,
            dataType:"json",
            success: function(response){
                $('#variant').empty();
                $('#variant').append('<option value="default" selected disabled>Select variant</option>');
                $.each(response.data, function(key, item){
                    $('#variant').append('<option value="'+ item.id +'">'+ item.variant_name +'</option>');
                });

                $('#variant').appendTo('#variant').selectpicker('refresh');

            }
        })

    })

    $('#variant').on('change', function () {
        var variantId = $(this).val()
        var productId = $("#product").val()
        var variantName = $("#variant").find("option:selected").text()

        $.ajax({
            type: "GET",
            url: "/get-product-price/" + productId + "/" + variantId,
            dataType: "json",
            success: function (response) {
                $('#unitprice').val(response.products[0].price)
                $('#mrp').val(response.products[0].mrp)

            }
        })

    })

    $('#product').on('change', function() {
        var selectedName = $("#product option:selected").data('name');
        var selectedModel = $("#product option:selected").data('model');

        $('#selectedProductName').val(selectedName);
        $('#selectedProductModel').val(selectedModel);

        $('#variant').prop('disabled', false).selectpicker('refresh');
    });

    $('#variant').on('change', function() {
        var selectedText = $("#variant option:selected").text();
        $('#selectedVariantName').val(selectedText);
    });

    function copyToClipboard(elementId) {
        var copyText = document.getElementById(elementId);
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");

        showFlashMessage("Copied!");
    }

    function showFlashMessage(message) {
        var flash = $('<div></div>', {
            text: message,
            css: {
                position: 'fixed',
                top: '55px',
                right: '20px',
                background: '#28a745',
                color: 'white',
                padding: '8px 12px',
                borderRadius: '4px',
                boxShadow: '0 0 10px rgba(0,0,0,0.2)',
                zIndex: 9999,
                display: 'none'
            }
        }).appendTo('body');

        flash.fadeIn(300).delay(1000).fadeOut(300, function() {
            $(this).remove();
        });
    }

    // Show selected file name
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

</script>

@endsection



