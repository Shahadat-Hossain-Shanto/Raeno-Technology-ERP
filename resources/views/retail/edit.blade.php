@extends('layouts.master')
@section('title', 'RAENO :: Edit Retail')

@section('content')
<div class="content-wrapper py-4 px-3">
    <div class="container-fluid">
        <div class="row justify-content-start">
            <div class="col-md-10">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white">
                        <h5><strong><i class="fas fa-user-edit"></i> Edit Retail</strong></h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('retails.update', $retail->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                {{-- Column 1 --}}
                                <div class="col-md-6">
                                    <label for="retail_name" class="form-label">Retail Name <span class="text-danger">*</span></label>
                                    <input type="text" name="retail_name" id="retail_name" class="form-control" value="{{ old('retail_name', $retail->retail_name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="owner_name" class="form-label">Owner Name</label>
                                    <input type="text" name="owner_name" id="owner_name" class="form-control" value="{{ old('owner_name', $retail->owner_name) }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="nid" class="form-label">NID <span class="text-danger">*</span></label>
                                    <input type="text" name="nid" id="nid" class="form-control" value="{{ old('nid', $retail->nid) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="contact_no" class="form-label">Contact No <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_no" id="contact_no" class="form-control" value="{{ old('contact_no', $retail->contact_no) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $retail->email) }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="bkash_no" class="form-label">Bkash No</label>
                                    <input type="text" name="bkash_no" id="bkash_no" class="form-control" value="{{ old('bkash_no', $retail->bkash_no) }}">
                                </div>

                                <!-- District -->
                                <div class="col-md-6">
                                    <label for="district" class="form-label">District <span class="text-danger">*</span></label>
                                    <select name="district_id" id="district" class="form-select select2" required>
                                        <option value="">Select District</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district->id }}"
                                                {{ old('district', $retail->district_id) == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Upazila -->
                                <div class="col-md-6">
                                    <label for="upazila" class="form-label">Upazila <span class="text-danger">*</span></label>
                                    <select name="upazila_id" id="upazila" class="form-select select2" required>
                                        <option value="{{ $retail->upazila_id }}" selected>{{ $retail->upazilaRelation->name ?? 'Select Upazila' }}</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="type" class="form-label">Business Type</label>
                                    <select name="type" id="type" class="form-select">
                                        <option value="">-- Select Type --</option>
                                        <option value="Sole Proprietorship" {{ old('type', $retail->type) == 'Sole Proprietorship' ? 'selected' : '' }}>Sole Proprietorship</option>
                                        <option value="Partnership" {{ old('type', $retail->type) == 'Partnership' ? 'selected' : '' }}>Partnership</option>
                                        <option value="Private Ltd. Company" {{ old('type', $retail->type) == 'Private Ltd. Company' ? 'selected' : '' }}>Private Ltd. Company</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="tin" class="form-label">TIN</label>
                                    <input type="text" name="tin" id="tin" class="form-control" value="{{ old('tin', $retail->tin) }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="trade_license_no" class="form-label">Trade License No</label>
                                    <input type="text" name="trade_license_no" id="trade_license_no" class="form-control" value="{{ old('trade_license_no', $retail->trade_license_no) }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="trade_license_validity" class="form-label">Trade License Validity</label>
                                    <input type="date" name="trade_license_validity" id="trade_license_validity" class="form-control" value="{{ old('trade_license_validity', $retail->trade_license_validity) }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="distributor_id" class="form-label">Distributor <span class="text-danger">*</span></label>
                                    <select name="distributor_id" id="distributor_id" class="form-select select2" required>
                                        <option value="">Select Distributor</option>
                                        @foreach($distributor as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('distributor_id', $retail->distributor_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->distributor_name }}({{ $item->id }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-6">
                                    <label for="retail_address" class="form-label">Retail Address</label>
                                    <textarea name="retail_address" id="retail_address" class="form-control" rows="3">{{ old('retail_address', $retail->retail_address) }}</textarea>
                                </div>
                            </div>

                            <div class="text-right mt-4">
                                <a href="{{ route('retails.index') }}" class="btn btn-secondary px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary px-4">Update Retail</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Style override for Select2 --}}
<style>
    .select2-container .select2-selection--single {
        height: 40px !important;
        padding-top: 5px;
    }
</style>

{{-- Initialize Select2 --}}
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Select',
            allowClear: true,
            width: '100%'
        });

        let selectedUpazilaId = '{{ $retail->upazila_id }}';

        function loadUpazilas(districtId, selectedId = null) {
            $('#upazila').html('<option value="">Loading...</option>');

            if (districtId) {
                $.ajax({
                    url: '/get-upazilas-by-id/' + districtId,
                    type: 'GET',
                    success: function (data) {
                        let options = '<option value="">Select Upazila</option>';
                        data.forEach(function (upazila) {
                            options += `<option value="${upazila.id}" ${selectedId == upazila.id ? 'selected' : ''}>${upazila.name}</option>`;
                        });
                        $('#upazila').html(options);
                    }
                });
            } else {
                $('#upazila').html('<option value="">Select Upazila</option>');
            }
        }

        $('#district').on('change', function () {
            const districtId = $(this).val();
            loadUpazilas(districtId);
        });

        // Load upazilas on page load
        if ($('#district').val()) {
            loadUpazilas($('#district').val(), selectedUpazilaId);
        }
    });
</script>
@endsection
