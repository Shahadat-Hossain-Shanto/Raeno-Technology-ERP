@extends('layouts.master')
@section('title', 'RAENO :: Distributor Sell Out')

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
		                	<h5 class="m-0"><strong><i class="fas fa-chevron-circle-down"></i> DISRIBUTOR SELL OUT</strong></h5>
		              </div>
		              <div class="card-body">

                            <div id="form_div">
                                <form id="addPreStockForm">
                                    @csrf
                                    <div class="row pt-1 align-items-end">

                                        <!-- Retail select -->
                                        <div class="form-group col-md-6 col-lg-3">
                                            <label for="retail" class="font-weight-normal">
                                                Retail Name <span class="text-danger">*</span>
                                            </label>
                                            <select name="retail_id" id="retail_id" class="form-control select2" required>
                                                <option value="">Select Retail</option>
                                                @foreach($retails as $retail)
                                                    <option value="{{ $retail->id }}">{{ $retail->retail_name }}({{ $retail->contact_no }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- IMEI Select -->
                                        <div class="form-group col-md-6 col-lg-3">
                                            <label for="imei_select" class="font-weight-normal">
                                                Select IMEI <span class="text-danger">*</span>
                                            </label>
                                            <select name="imei_select" id="imei_select" class="form-control select2">
                                                <option value="">Select IMEI</option>
                                                @foreach($imeis as $item)
                                                    <option value="{{ $item->imei_1 }}">{{ $item->imei_1 }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- OR and Scan IMEI input -->
                                        <div class="form-group col-md-6 col-lg-3">
                                            <label class="font-weight-normal d-block">&nbsp;</label>
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="text-muted mb-2" style="font-weight: 600;">OR Type/Scan</div>
                                                <input type="text" name="imei_input" id="imei_input" class="form-control" placeholder="Scan IMEI" autocomplete="off" >
                                            </div>
                                        </div>

                                        <!-- Add Button -->
                                        <div class="form-group col-md-6 col-lg-2 d-flex justify-content-start align-items-end">
                                            <button id="add_btn" type="submit" class="btn btn-info w-100">
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>


                            <form action="{{ route('distributor.out.store') }}" method="POST">
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
                                                        <th>Retail</th>
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
                                            <i class="fas fa-arrow-alt-circle-down"></i> Submit
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
<style>
    .select2-container .select2-selection--single {
        height: 40px !important;
    }
</style>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    $(document).ready(function () {
        $('#imei').on('select2:select', function () {
            $('#add_btn').click();
        });

        $('#imei').on('keypress', function (e) {
            if (e.key === 'Enter' && $(this).val().trim()) {
                e.preventDefault();
                $('#add_btn').click();
            }
        });
        $('.select2').select2({
            placeholder: 'Select',
            allowClear: true,
            width: '100%'
        });
    });
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

function addImeiRow(imei, retail_id, retail_name) {
    $.ajax({
        url: '/distributor-info/' + imei,
        method: 'GET',
        success: function(data) {
            const row = `
                <tr>
                    <td>${rowIndex}</td>
                    <td><input type="hidden" name="items[${rowIndex}][imei_1]" value="${data.imei_1}">${data.imei_1}</td>
                    <td><input type="hidden" name="items[${rowIndex}][imei_2]" value="${data.imei_2}">${data.imei_2 ?? 'N/A'}</td>
                    <td><input type="hidden" name="items[${rowIndex}][serial_number]" value="${data.serial_number}">${data.serial_number}</td>
                    <td><input type="hidden" name="items[${rowIndex}][product_name]" value="${data.product_name}">${data.product_name}</td>
                    <td><input type="hidden" name="items[${rowIndex}][brand]" value="${data.brand}">${data.brand ?? 'N/A'}</td>
                    <td><input type="hidden" name="items[${rowIndex}][model]" value="${data.model}">${data.model}</td>
                    <td><input type="hidden" name="items[${rowIndex}][manufacturer]" value="${data.manufacturer}">${data.manufacturer ?? 'N/A'}</td>
                    <td><input type="hidden" name="items[${rowIndex}][variant]" value="${data.variant}">${data.variant}</td>
                    <td><input type="hidden" name="items[${rowIndex}][retail_id]" value="${retail_id}">${retail_name}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                </tr>
            `;
            $('#preTable tbody').append(row);
            $('#imei_input').val('');
            $('#imei_select').val('').trigger('change');
            rowIndex++;
        },
        error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Something went wrong!');
        }
    });
}

$('#add_btn').on('click', function(e) {
    e.preventDefault();

    const imei_input = $('#imei_input').val().trim();
    const imei_select = $('#imei_select').val();
    const imei = imei_input || imei_select;

    const retail_id = $('#retail_id').val();
    const retail_name = $('#retail_id option:selected').text();

    if (!imei) return toastr.error('IMEI is required');
    if (!retail_id) return toastr.error('Retail is required');

    let imeiExists = false;
    $('#preTable tbody tr').each(function () {
        const existingImei = $(this).find('input[name$="[imei_1]"]').val();
        if (existingImei === imei) {
            imeiExists = true;
            return false;
        }
    });

    if (imeiExists) {
        return toastr.warning('This IMEI is already added.');
    }

    addImeiRow(imei, retail_id, retail_name);
});

// Scan IMEI auto submit
$('#imei_input').on('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        $('#add_btn').click();
    }
});

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
