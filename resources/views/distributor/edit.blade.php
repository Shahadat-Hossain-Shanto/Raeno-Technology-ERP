@extends('layouts.master')
@section('title', 'Edit Distributor')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong><i class="fas fa-user-edit"></i> Edit Distributor</strong></h5>
                        </div>

                        <div class="card-body">
                            <div class="container">

                                @if($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> Please fix the following issues:
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form action="{{ route('distributor.update', $distributor->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Distributor Name <span class="text-danger">*</span></label>
                                                <input type="text" name="distributor_name" class="form-control" value="{{ old('distributor_name', $distributor->distributor_name) }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Owner Name <span class="text-danger">*</span></label>
                                                <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name', $distributor->owner_name) }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label>NID</label>
                                                <input type="text" name="nid" class="form-control" value="{{ old('nid', $distributor->nid) }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Contact No <span class="text-danger">*</span></label>
                                                <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no', $distributor->contact_no) }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ old('email', $distributor->email) }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea name="address" class="form-control" rows="2">{{ old('address', $distributor->address) }}</textarea>
                                            </div>
                                            <div class="form-group position-relative">
                                                <label for="districtSearchInput">District <span class="text-danger">*</span></label>
                                                <input type="text" id="districtSearchInput" name="district_display" class="form-control"
                                                    placeholder="Search District..." autocomplete="off"
                                                    value="{{ old('district_display', $distributor->district->name ?? '') }}" required>

                                                <div id="districtDropdown" class="dropdown-menu w-100 mt-1 shadow"
                                                    style="max-height: 220px; overflow-y: auto; display: none;">
                                                    @foreach($districts as $district)
                                                        <a href="#" class="dropdown-item"
                                                        onclick="selectDistrict(event, '{{ $district->name }}', '{{ $district->id }}')">
                                                            {{ $district->name }}
                                                        </a>
                                                    @endforeach
                                                </div>

                                                <input type="hidden" name="district_id" id="selectedDistrict"
                                                    value="{{ old('district_id', $distributor->district_id ?? '') }}" required>
                                            </div>
                                           <div class="form-group">
                                            <label for="">Area <span class="text-danger">*</span></label>
                                            <select name="area_id" id="area_id_select" class="form-control selectpicker" data-live-search="true" required>
                                                <option value="">Select Area</option>
                                                @foreach ($areas as $area)
                                                    <option
                                                        value="{{ $area->id }}"
                                                        data-region-name="{{ $area->region_name }}"
                                                        {{ old('area_id', $distributor->area_id) == $area->id ? 'selected' : '' }}
                                                    >
                                                        {{ $area->area_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                           </div>

                                            <input type="hidden" name="region_id" id="region_id" value="{{ $distributor->region_id }}">


                                            <div class="form-group">
                                                <label for="">Territory <span class="text-danger">*</span></label>
                                                <select name="territory_id" id="territory_id_select" class="form-control selectpicker" data-live-search="true" required>
                                                    <option value="{{ $distributor->territory_id }}" selected>
                                                        {{ $distributor->territory->territory_name ?? 'Select Territory' }}
                                                    </option>
                                                </select>

                                            </div>




                                            <div class="form-group">
                                                <label>Business Type <span class="text-danger">*</span></label>
                                                @php
                                                    $types = ['Sole Proprietorship', 'Partnership', 'Private Ltd. Company'];
                                                @endphp
                                                @foreach($types as $type)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="business_type" id="{{ $type }}" value="{{ $type }}"
                                                        {{ old('business_type', $distributor->business_type) == $type ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="{{ $type }}">{{ $type }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Trade License No</label>
                                                <input type="text" name="trade_license_no" class="form-control" value="{{ old('trade_license_no', $distributor->trade_license_no) }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Trade License Validity</label>
                                                <input type="date" name="trade_license_validity" class="form-control" value="{{ old('trade_license_validity', $distributor->trade_license_validity) }}">
                                            </div>

                                            <div class="form-group">
                                                <label>TIN</label>
                                                <input type="text" name="tin" class="form-control" value="{{ old('tin', $distributor->tin) }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Bank Name</label>
                                                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $distributor->bank_name) }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Branch</label>
                                                <input type="text" name="branch" class="form-control" value="{{ old('branch', $distributor->branch) }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Account Name</label>
                                                <input type="text" name="account_name" class="form-control" value="{{ old('account_name', $distributor->account_name) }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Account No</label>
                                                <input type="text" name="account_no" class="form-control" value="{{ old('account_no', $distributor->account_no) }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Existing Distributor Brands</label>
                                                <input type="text" name="existing_distributor_brands" class="form-control" value="{{ old('existing_distributor_brands', $distributor->existing_distributor_brands) }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group pt-3 text-left">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Distributor
                                        </button>
                                        <a href="{{ route('distributor.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div> <!-- /.container -->
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
    </div> <!-- /.content -->
</div> <!-- /.content-wrapper -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('districtSearchInput');
        const dropdown = document.getElementById('districtDropdown');
        const hidden = document.getElementById('selectedDistrict');

        input.addEventListener('focus', () => {
            dropdown.style.display = 'block';
            filter();
        });

        input.addEventListener('input', filter);

        function filter() {
            const filter = input.value.trim().toUpperCase();
            const items = dropdown.querySelectorAll('.dropdown-item');
            let hasVisible = false;

            items.forEach(item => {
                const text = item.textContent.trim().toUpperCase();
                const visible = text.includes(filter);
                item.style.display = visible ? 'block' : 'none';
                if (visible) hasVisible = true;
            });

            dropdown.style.display = hasVisible ? 'block' : 'none';
        }

        // 👇 This handles the ID and Name on selection
        window.selectDistrict = function (e, name, id) {
            e.preventDefault();
            input.value = name;
            hidden.value = id;
            dropdown.style.display = 'none';
        };

        document.addEventListener('click', function (event) {
            if (!input.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        $('#area_id_select').on('change', function () {
            const selectedOption = $(this).find('option:selected');
            const regionName = selectedOption.data('region-name');
            const areaName = selectedOption.text().trim();

            // Fetch and set region_id
            $.ajax({
                url: '/get-region-id-by-name',
                type: 'GET',
                data: { region_name: regionName },
                success: function (response) {
                    $('#region_id').val(response.region_id);
                },
                error: function () {
                    $('#region_id').val('');
                }
            });

            // Fetch territories for new area
            $.ajax({
                url: '/get-territories-by-area-name',
                type: 'GET',
                data: { area_name: areaName },
                success: function (data) {
                    $('#territory_id_select').empty().append('<option value="">Select Territory</option>');
                    $.each(data, function (index, territory) {
                        $('#territory_id_select').append(
                            `<option value="${territory.id}">${territory.territory_name}</option>`
                        );
                    });
                    $('#territory_id_select').selectpicker('refresh');
                },
                error: function () {
                    $('#territory_id_select').empty().append('<option value="">Select Territory</option>').selectpicker('refresh');
                }
            });
        });

    });
</script>
@endsection
