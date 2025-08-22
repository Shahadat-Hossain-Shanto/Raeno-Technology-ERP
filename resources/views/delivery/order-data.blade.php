@extends('layouts.master')
@section('title', 'RAENO :: Order Receive')

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
		                	<h5 class="m-0"><strong><i class="fas fa-chevron-circle-down"></i> Order Receive</strong></h5>
		              </div>
		              <div class="card-body">

	                	<div id="form_div">
                            <div id="storediv" style="display: disabled">
                                <div class="row pt-2">
                                    <div class="col-12">
                                        <div class="d-flex flex-wrap justify-content-start align-items-center gap-5 px-4 py-3 bg-light rounded shadow-sm border">
                                            <div class="d-flex align-items-center">
                                                <strong class="me-2">Order ID:</strong>
                                                <span class="badge bg-info fs-6">{{ $order->order_id }}</span>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <strong class="me-2">Order Date:</strong>
                                                <span class="badge bg-info text-dark fs-6">{{ $orderDate}}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <strong class="me-2">Distributor ID:</strong>
                                                <span class="badge bg-info fs-6">{{ $order->distributor_id }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <strong class="me-2">Distributor Name:</strong>
                                                <span class="badge bg-info fs-6">{{ $order->distributor_name }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <strong class="me-2">Quantity:</strong>
                                                <span class="badge bg-info fs-6">{{ $order->quantity }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped text-center align-middle shadow-sm bg-white" style="table-layout: fixed; width: 100%;">
                                                <thead>
                                                    <tr class="bg-primary text-white">
                                                        <th style="padding: 6px 8px;">Product / Model / Variant</th>
                                                        <th style="padding: 6px 8px;">Delivered</th>
                                                        <th style="padding: 6px 8px;">Received</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="variant_summary">
                                                    @foreach ($orderDetails as $detail)
                                                        <tr data-variant="{{ $detail->product_name }}||{{ $detail->model }}||{{ $detail->variant }}">
                                                            <td class="fw-semibold text-dark" style="padding: 6px 8px;">{{ $detail->product_name }} / {{ $detail->model }} / {{ $detail->variant }}</td>
                                                            <td class="fw-bold required text-primary" style="padding: 6px 8px;">{{ $detail->quantity }}</td>
                                                            <td class="fw-bold added text-info" style="padding: 6px 8px;">0</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                    <tfoot>
                                                        <tr class="bg-light">
                                                            <td class="fw-bold text-end">Total:</td>
                                                            <td class="fw-bold text-primary" id="total_required">-</td>
                                                            <td class="fw-bold text-info" id="imei_count_display">0</td>
                                                        </tr>
                                                    </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <form id="addPreStockForm" method="GET">
                                    @csrf
                                    <div class="row pt-1 align-items-end">
                                        <div class="form-group col-md-6 col-lg-3">
                                            <label for="imei" class="fw-bold">IMEI <span class="text-danger">*</span></label>
                                            <input type="text" name="imei" id="imei" class="form-control" required autofocus placeholder="Scan or type IMEI">
                                        </div>

                                        <div class="form-group col-md-6 col-lg-2 d-flex justify-content-start">
                                            <button id="add_btn" type="submit" class="btn btn-info" data-delivery-id="{{ $order->id }}">
                                                <i class="fas fa-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </form>
		                	</div>
	                	</div>

                        <form action="{{ route('distributor.in.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->order_id }}">
                            <input type="hidden" name="delivery_id" value="{{ $order->id }}">
                            <input type="hidden" id="imei_count" name="imei_count" value="0">
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
                            <div id="variant_hidden_inputs"></div>

                            <div class="row pt-3">
                                <div class="col-12 text-end">
                                    <button id="openModal" type="button" class="btn btn-primary">
                                        <i class="fas fa-arrow-alt-circle-down"></i> Receive Orders
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

<!--Modal -->
<div class="modal fade" id="confirmReceiveModal" tabindex="-1" aria-labelledby="confirmReceiveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm rounded-3">

      <div class="modal-header bg-light py-2">
        <h6 class="modal-title text-primary fw-bold" id="confirmReceiveModalLabel">
          Confirm Receive Orders
        </h6>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body py-3 text-center">
        <p class="mb-0 text-dark ">Are you sure you want to proceed?</p>
      </div>

      <div class="modal-footer bg-light py-2 d-flex justify-content-end">
        <button type="button" class="btn btn-md btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" id="finalSubmit" class="btn btn-md btn-primary ms-2">
          Submit
        </button>
      </div>

    </div>
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    $(document).ready(function () {
        // Optional: Handle Enter key press
        $('#imei').on('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $('#addPreStockForm').submit();
            }
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

$('#add_btn').on('click', function(e) {
    e.preventDefault();

    const imei = $('#imei').val().trim();
    const deliveryId = $(this).data('delivery-id');

    if (!imei) return toastr.error('IMEI is required');

    $.ajax({
        url: '/delivery-info/' + imei + '/' + deliveryId,
        method: 'GET',
        success: function(data) {
            const variantKey = `${data.product_name}||${data.model}||${data.variant}`;
            const $variantRow = $(`#variant_summary tr[data-variant="${variantKey}"]`);

            // Check if IMEI 1 already exists
            let exists = false;
            $('#preTable tbody tr').each(function () {
                const existingImei = $(this).find('td:eq(1)').text().trim();
                if (existingImei === data.imei_1) {
                    exists = true;
                    return false;
                }
            });
            if (exists) {
                toastr.warning('This IMEI has already been added.');
                $('#imei').val('').focus();
                return;
            }

            // Validate against required quantity
            if ($variantRow.length) {
                const required = parseInt($variantRow.find('.required').text());
                const added = parseInt($variantRow.find('.added').text());

                if (added >= required) {
                    toastr.warning(`Cannot add more for variant "${data.variant}" (limit: ${required})`);
                    $('#imei').val('').focus();
                    return;
                }

                // Update added quantity
                $variantRow.find('.added').text(added + 1);
            }

            const index = rowIndex++;
            const row = `
                <tr data-variant="${variantKey}">
                    <td>${index}</td>
                    <td><input type="hidden" name="items[${index}][imei_1]" value="${data.imei_1}">${data.imei_1}</td>
                    <td><input type="hidden" name="items[${index}][imei_2]" value="${data.imei_2}">${data.imei_2 ?? ''}</td>
                    <td><input type="hidden" name="items[${index}][serial_number]" value="${data.serial_number}">${data.serial_number}</td>
                    <td><input type="hidden" name="items[${index}][product_name]" value="${data.product_name}">${data.product_name}</td>
                    <td><input type="hidden" name="items[${index}][brand]" value="${data.brand ?? ''}">${data.brand ?? ''}</td>
                    <td><input type="hidden" name="items[${index}][model]" value="${data.model}">${data.model}</td>
                    <td><input type="hidden" name="items[${index}][manufacturer]" value="${data.manufacturer ?? ''}">${data.manufacturer ?? ''}</td>
                    <td><input type="hidden" name="items[${index}][variant]" value="${data.variant}">${data.variant}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                </tr>
            `;

            $('#preTable tbody').append(row);
            $('#imei').val('').focus();
            updateRowIndexes();
        },
        error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Something went wrong!');
        }
    });
});

// Remove row handler
$('#preTable').on('click', '.remove-row', function () {
    const row = $(this).closest('tr');
    const variantKey = row.data('variant');
    const $variantRow = $(`#variant_summary tr[data-variant="${variantKey}"]`);

    if ($variantRow.length) {
        const added = parseInt($variantRow.find('.added').text());
        $variantRow.find('.added').text(Math.max(0, added - 1));
    }

    row.remove();
    updateRowIndexes();
});

function updateRowIndexes() {
    rowIndex = 1;
    $('#preTable tbody tr').each(function () {
        $(this).find('td:first').text(rowIndex++);
    });

    const total = $('#preTable tbody tr').length;
    $('#imei_count').val(total);
    $('#imei_count_display').text(total);
}
</script>
<script>
    $(document).ready(function () {

        $('#openModal').on('click', function () {
            const imeiCount = parseInt($('#imei_count').val());

            if (imeiCount === 0) {
                toastr.warning('Nothing added to deliver.');
                return;
            }

            $('#confirmReceiveModal').modal('show');
        });

        $('#finalSubmit').on('click', function () {
            $('#variant_hidden_inputs').empty();
            $('#variant_summary tr').each(function () {
                const variantKey = $(this).data('variant');
                const added = $(this).find('.added').text();

                if (parseInt(added) > 0) {
                    const [product, model, variant] = variantKey.split('||');
                    const input = `
                        <input type="hidden" name="variants[${product}][${model}][${variant}]" value="${added}">
                    `;
                    $('#variant_hidden_inputs').append(input);
                }
            });

            $('form').submit();
        });
    });
</script>

@endsection
