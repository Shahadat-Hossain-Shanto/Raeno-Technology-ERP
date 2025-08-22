@extends('layouts.master')
@section('title', 'RAENO :: Primary-Stock-In')

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
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <strong>{{ session('success') }}</strong>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {!! session('error') !!}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{!! $error !!}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
		          	<div class="card card-primary">
		              <div class="card-header">
		                	<h5 class="m-0"><strong><i class="fas fa-chevron-circle-down"></i> PRIMARY-STOCK-IN</strong></h5>
		              </div>
		              <div class="card-body">

	                	<div id="form_div">
	                		{{-- <form id="" method="" enctype="multipart/form-data"> --}}
	                			<div id="storediv" style="display: disabled">
                                {{-- <div class="row pt-3">
                                    <!-- Product Select -->
                                    <div class="form-group col-md-6 col-lg-2">
                                        <label for="product" class="font-weight-normal">Product</label>
                                        <select class="selectpicker form-control" data-live-search="true" name="product" id="product" data-width="100%">
                                            <option value="default" selected disabled>Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                        data-name="{{ $product->productName }}"
                                                        data-model="{{ $product->model }}">
                                                    {{ $product->productName }} ({{ $product->brand }} - {{ $product->category_name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Variant Select -->
                                    <div class="form-group col-md-6 col-lg-2">
                                        <label for="variant" class="font-weight-normal">Variant</label>
                                        <select class="selectpicker form-control" data-live-search="true" name="variant" id="variant" disabled data-width="100%">
                                            <option value="default" selected disabled>Select Variant</option>
                                            @foreach($variants as $variant)
                                                <option value="{{ $variant->id }}" data-name="{{ $variant->variant_name }}">
                                                    {{ $variant->variant_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Display Product Name -->
                                    <div class="form-group col-md-6 col-lg-2">
                                        <label for="selected_product_name" class="font-weight-normal">Product Name</label>
                                        <input type="text" id="selected_product_name" class="form-control" readonly>
                                    </div>

                                    <!-- Display Product Model -->
                                    <div class="form-group col-md-6 col-lg-2">
                                        <label for="selected_product_model" class="font-weight-normal">Model</label>
                                        <input type="text" id="selected_product_model" class="form-control" readonly>
                                    </div>

                                    <!-- Reset Button -->
                                    <div class="form-group col-md-6 col-lg-2 d-flex align-items-end justify-content-center">
                                        <button class="btn btn-outline-danger w-100" type="button" onclick="resetButton()">
                                            <i class="fas fa-eraser"></i> Reset
                                        </button>
                                    </div>
                                </div> --}}
                                <form id="addPreStockForm" method="POST">
                                    @csrf
                                    <div class="row pt-1 align-items-end">
                                        <div class="form-group col-md-6 col-lg-3">
                                            <label for="imei" class="font-weight-normal">IMEI <span class="text-danger">*</span></label>
                                            <input type="text" name="imei" id="imei" class="form-control" required autofocus placeholder="Scan or type IMEI">
                                        </div>
                                        <div class="form-group col-md-6 col-lg-2 d-flex justify-content-start">
                                            <button id="add_btn" type="submit" class="btn btn-info">
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </form>
		                	</div>
	                		{{-- </form> --}}
	                	</div>

                            <form action="{{ route('primary.in.store') }}" method="POST">
                                @csrf

                                <div class="row pt-2">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="preTable" class="table table-bordered table-striped table-hover w-100">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>IMEI 1</th>
                                                        <th>IMEI 2</th>
                                                        <th>Serial</th>
                                                        <th>Product Name</th>
                                                        <th>Brand</th>
                                                        <th>Model</th>
                                                        <th>Manufacturer</th>
                                                        <th>Variant</th>
                                                        {{-- <th>Unit Price</th> --}}
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-3">
                                    <div class="col-12 text-end">
                                        <button id="submit" type="submit" class="btn btn-primary">
                                            <i class="fas fa-arrow-alt-circle-down"></i> Primary Stock
                                        </button>
                                    </div>
                                </div>
                            </form>
		              </div> <!-- Card-body -->
		            </div>	<!-- Card -->

		        </div>   <!-- /.col-lg-6 -->
        	</div><!-- /.row -->
        </div> <!-- container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
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
    $(document).ready(function () {
        // Optional: Handle Enter key press
        $('#imei').on('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#addPreStockForm').submit();
            }
        });

        $('#product').on('change', function () {
            const selected = $(this).find('option:selected');
            const name = selected.data('name') || '';
            const model = selected.data('model') || '';

            $('#selected_product_name').val(name);
            $('#selected_product_model').val(model);

            $('#variant').prop('disabled', false).selectpicker('refresh');
        });
    });

    function resetButton() {
        // Reset selects
        $('#product').val('default').selectpicker('refresh');
        $('#variant').val('default').prop('disabled', true).selectpicker('refresh');

        // Clear readonly fields
        $('#selected_product_name').val('');
        $('#selected_product_model').val('');
    }
</script>
<script>
    setTimeout(function () {
        document.querySelectorAll('.alert-dismissible').forEach(function(el) {
            el.classList.remove('show');
            el.classList.add('fade');
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);
</script>

<script>
let rowIndex = 1;

$('#add_btn').on('click', function(e) {
    e.preventDefault();
    const imei = $('#imei').val().trim();
    if (!imei) return toastr.error('IMEI is required');

    $.ajax({
        url: '/get-imei-info/' + imei,
        method: 'GET',
        success: function(data) {
            const row = `
                <tr>
                    <td>${rowIndex++}</td>
                    <td><input type="hidden" name="items[${rowIndex}][imei_1]" value="${data.imei_1}">${data.imei_1}</td>
                    <td><input type="hidden" name="items[${rowIndex}][imei_2]" value="${data.imei_2}">${data.imei_2 ?? ''}</td>
                    <td><input type="hidden" name="items[${rowIndex}][serial_number]" value="${data.serial_number}">${data.serial_number}</td>
                    <td><input type="hidden" name="items[${rowIndex}][product_name]" value="${data.product_name}">${data.product_name}</td>
                    <td><input type="hidden" name="items[${rowIndex}][brand]" value="${data.brand}">${data.brand ?? ''}</td>
                    <td><input type="hidden" name="items[${rowIndex}][model]" value="${data.model}">${data.model}</td>
                    <td><input type="hidden" name="items[${rowIndex}][manufacturer]" value="${data.manufacturer ?? ''}">${data.manufacturer ?? ''}</td>
                    <td><input type="hidden" name="items[${rowIndex}][variant]" value="${data.variant}">${data.variant}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                </tr>
            `;
            $('#preTable tbody').append(row);
            $('#imei').val('').focus();
        },
        error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Something went wrong!');
        }
    });
});

// Remove row handler
$('#preTable').on('click', '.remove-row', function () {
    $(this).closest('tr').remove();
    updateRowIndexes();
});

function updateRowIndexes() {
    rowIndex = 1;
    $('#preTable tbody tr').each(function () {
        $(this).find('td:first').text(rowIndex++);
    });
}
</script>
@endsection



