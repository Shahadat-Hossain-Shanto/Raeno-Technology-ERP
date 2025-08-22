@extends('layouts.master')
@section('title', 'RAENO :: Product Return')

@section('content')
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
	          	<div class="col-lg-12 mt-3">
                    <div class="card card-primary">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="card-header">
                            <h5 class="m-0"><strong><i class="fas fa-chevron-circle-down"></i> Product Return</strong></h5>
                        </div>
                        <div class="card-body">
                            {{-- IMEI Add Form --}}
                            <form id="addPreStockForm" method="GET">
                                @csrf
                                <div class="row pt-1 align-items-end">
                                    <div class="form-group col-md-6 col-lg-3">
                                        <label for="distributor_id" class="fw-bold">
                                            Distributor <span class="text-danger">*</span>
                                        </label>
                                        <select name="distributor_id" id="distributor_id" style="height: 45px;" class="form-control select2" required>
                                            <option value="">Select Distributor</option>
                                            @foreach ($distributor as $dist)
                                                <option value="{{ $dist->id }}" {{ auth()->user()->distributor == $dist->id ? 'selected' : '' }}>
                                                    {{ $dist->distributor_name }} ({{ $dist->id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6 col-lg-3">
                                        <label for="imei" class="fw-bold">IMEI <span class="text-danger">*</span></label>
                                        <input type="text" name="imei" id="imei" class="form-control" required autofocus placeholder="Scan or type IMEI">
                                    </div>

                                    <div class="form-group col-md-6 col-lg-3 d-flex justify-content-start align-items-end">
                                        <button id="add_btn" type="submit" class="btn btn-info me-2">
                                            <i class="fas fa-plus"></i> Add
                                        </button>

                                        {{-- Total Count Display --}}
                                        <div class="fw-bold text-primary ms-2">
                                            Total IMEI: <span id="imei_count_display">0</span>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            {{-- Final Submission Form --}}
                            <form action="{{ route('return.store') }}" method="POST" id="returnSubmitForm">
                                @csrf
                                <input type="hidden" id="imei_count" name="imei_count" value="0">
                                <input type="hidden" name="medium" id="form_medium">
                                <input type="hidden" name="note" id="form_note">
                                <div class="table-responsive mt-3">
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
                                                <th>Order ID</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                {{-- Confirm Button --}}
                                <div class="text-end mt-3">
                                    <button type="button" class="btn btn-primary px-5 py-1" data-bs-toggle="modal" data-bs-target="#confirmDeliveryModal">
                                        Confirm
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
		        </div>
        	</div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmDeliveryModal" tabindex="-1" aria-labelledby="confirmDeliveryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-light border-0">
        <h5 class="modal-title text-primary fw-bold mb-0" id="confirmDeliveryModalLabel">
          <i class="fas fa-truck me-1 text-primary"></i> Final Product Return Confirmation
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Add this style to prevent horizontal overflow -->
      <div class="modal-body pb-0" style="overflow-x: hidden;">
        <div class="mb-4">
          <div class="d-flex align-items-start gap-2">
            <i class="fas fa-exclamation-triangle text-warning fs-5"></i>
            <p class="text-dark fw-semibold mb-0">
              Please check all the information carefully before submitting.
            </p>
          </div>
        </div>

        <div class="row align-items-center mb-3">
            <div class="col-md-3 text-start">
                <label for="modal_medium" class="form-label fw-semibold mb-0">Transport Medium</label>
            </div>
            <div class="col-md-9">
                <textarea id="modal_medium" class="form-control" rows="2"
                style="resize: both; max-width: 100%;" placeholder="Enter transport method or details"></textarea>
            </div>
        </div>

        <div class="row align-items-center mb-3">
          <div class="col-md-3 text-start">
            <label for="modal_note" class="form-label fw-semibold mb-0">Note</label>
          </div>
          <div class="col-md-9">
            <textarea id="modal_note" class="form-control" rows="2"
              style="resize: both; max-width: 100%;" placeholder="Courier Person Name Or Other Information"></textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer bg-light border-0">
        <div class="w-100 d-flex justify-content-between align-items-center">
          <small class="text-muted">Ensure all details are correct before submitting.</small>
          <div>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
              Cancel
            </button>
            <button type="button" id="finalSubmit" class="btn btn-sm btn-primary">
              <i class="fas fa-paper-plane me-1"></i> Submit
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
</style>

<script>
let rowIndex = 1;

$('#add_btn').on('click', function(e) {
    e.preventDefault();
    const imei = $('#imei').val().trim();
    const distributorId = $('#distributor_id').val();

    if (!imei) return toastr.error('IMEI is required');
    if (!distributorId) return toastr.error('Please select a distributor');

    $.ajax({
        url: '/distributor-stock-info/' + imei + '/' + distributorId,
        method: 'GET',
        success: function(data) {
            // Check duplicate
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

            const index = rowIndex++;
            const row = `
                <tr>
                    <td>${index}</td>
                    <td><input type="hidden" name="items[${index}][imei_1]" value="${data.imei_1}">${data.imei_1}</td>
                    <td><input type="hidden" name="items[${index}][imei_2]" value="${data.imei_2}">${data.imei_2 ?? ''}</td>
                    <td><input type="hidden" name="items[${index}][serial_number]" value="${data.serial_number}">${data.serial_number}</td>
                    <td><input type="hidden" name="items[${index}][product_name]" value="${data.product_name}">${data.product_name}</td>
                    <td><input type="hidden" name="items[${index}][brand]" value="${data.brand ?? ''}">${data.brand ?? ''}</td>
                    <td><input type="hidden" name="items[${index}][model]" value="${data.model}">${data.model}</td>
                    <td><input type="hidden" name="items[${index}][manufacturer]" value="${data.manufacturer ?? ''}">${data.manufacturer ?? ''}</td>
                    <td><input type="hidden" name="items[${index}][variant]" value="${data.variant}">${data.variant}</td>
                    <td><input type="hidden" name="items[${index}][order_id]" value="${data.order_id}">${data.order_id}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button></td>
                </tr>
            `;

            $('#preTable tbody').append(row);
            $('#imei').val('').focus();
            updateRowIndexes();
            $('#distributor_id').prop('disabled', true);
            $('#distributor_id').select2({disabled: true});
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
    if ($('#preTable tbody tr').length === 0) {
        $('#distributor_id').prop('disabled', false);
        $('#distributor_id').select2({disabled: false});
    }
});

$('#finalSubmit').on('click', function () {
    const totalRows = $('#preTable tbody tr').length;

    if (totalRows === 0) {
        toastr.warning('No device added');
        return;
    }

    const medium = $('#modal_medium').val();
    const note = $('#modal_note').val();

    // if (!medium) {
    //     toastr.warning('Please select a transport medium');
    //     return;
    // }

    $('#form_medium').val(medium);
    $('#form_note').val(note);
    $('#confirmDeliveryModal').modal('hide');
    $('#returnSubmitForm').submit();
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
$(document).ready(function() {
    $('#distributor_id').select2({
        placeholder: "Select Distributor",
        allowClear: true,
        width: '100%'
    });
    // $('#distributor_id').on('change', function() {
    //     $('#preTable tbody').empty();
    //     updateRowIndexes();
    // });
});

</script>
@endsection
