@extends('layouts.master')
@section('title', 'Create Distributor')
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
                            <h5 class="m-0"><strong><i class="fas fa-user-plus"></i> Add Distributor</strong></h5>
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

                                <form action="{{ route('distributor.store') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Distributor Name <span class="text-danger">*</span></label>
                                                <input type="text" name="distributor_name" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Owner Name <span class="text-danger">*</span></label>
                                                <input type="text" name="owner_name" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label>NID</label>
                                                <input type="text" name="nid" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Contact No <span class="text-danger">*</span></label>
                                                <input type="text" name="contact_no" class="form-control" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Address</label>
                                                <textarea name="address" class="form-control" rows="2"></textarea>
                                            </div>

                                            <div class="form-group position-relative">
                                                <label for="districtSearchInput">District <span class="text-danger">*</span></label>
                                                <input type="text" id="districtSearchInput" name="district_display" class="form-control" placeholder="Search District..." autocomplete="off" required>

                                                <div id="districtDropdown" class="dropdown-menu w-100 mt-1 shadow" style="max-height: 220px; overflow-y: auto; display: none;">
                                                    @foreach($districts as $district)
                                                        <a href="#" class="dropdown-item" onclick="selectDistrict(event, '{{ $district->name }}', '{{ $district->id }}')">
                                                            {{ $district->name }}
                                                        </a>
                                                    @endforeach
                                                </div>

                                                <input type="hidden" name="district_id" id="selectedDistrict" required>
                                            </div>


                                           <!-- Area Dropdown -->
                                        <!-- Area Dropdown -->
                                        <div class="form-group">
                                             <label for="">Area <span class="text-danger">*</span></label>
                                             <select name="area_id" id="area_id_select" class="form-control selectpicker" data-live-search="true" required>
                                                <option value="">Select Area</option>
                                                @foreach ($areas as $area)
                                                    <option
                                                        value="{{ $area->id }}"
                                                        data-region-name="{{ $area->region_name }}"
                                                    >
                                                        {{ $area->area_name }}
                                                    </option>
                                                @endforeach
                                             </select>

                                        </div>


                                        <!-- Territory Dropdown -->
                                        <div class="form-group">
                                             <label for="">Territory <span class="text-danger">*</span></label>
                                             <select name="territory_id" id="territory_id_select" class="form-control selectpicker" data-live-search="true" required>
                                                <option value="">Select Territory</option>
                                             </select>

                                        </div>


                                        <!-- Hidden region_id -->
                                        <input type="hidden" name="region_id" id="region_id">




                                            <div class="form-group">
                                                <label>Business Type <span class="text-danger">*</span></label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="business_type" id="sole" value="Sole Proprietorship" required>
                                                    <label class="form-check-label" for="sole">Sole Proprietorship</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="business_type" id="partnership" value="Partnership">
                                                    <label class="form-check-label" for="partnership">Partnership</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="business_type" id="private" value="Private Ltd. Company">
                                                    <label class="form-check-label" for="private">Private Ltd. Company</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Trade License No</label>
                                                <input type="text" name="trade_license_no" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Trade License Validity</label>
                                                <input type="date" name="trade_license_validity" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>TIN</label>
                                                <input type="text" name="tin" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Bank Name</label>
                                                <input type="text" name="bank_name" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Branch</label>
                                                <input type="text" name="branch" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Account Name</label>
                                                <input type="text" name="account_name" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Account No</label>
                                                <input type="text" name="account_no" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Credit Limit</label>
                                                <input type="number" step="0.01" name="credit_limit" class="form-control">
                                            </div>

                                            <div class="form-group">
                                                <label>Existing Distributor Brands</label>
                                                <input type="text" name="existing_distributor_brands" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group pt-3 text-left">

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save Distributor
                                        </button>
                                         <button type="reset" class="btn btn-outline-danger" id="reset">
                                            <i class="fas fa-eraser"></i> Reset
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
    const districtInput = document.getElementById('districtSearchInput');
    const districtDropdown = document.getElementById('districtDropdown');
    const selectedDistrictInput = document.getElementById('selectedDistrict');

    districtInput.addEventListener('focus', () => {
        districtDropdown.style.display = 'block';
        filterDistricts();
    });

    districtInput.addEventListener('input', filterDistricts);

    function filterDistricts() {
        const filter = districtInput.value.trim().toUpperCase();
        const items = districtDropdown.querySelectorAll('.dropdown-item');
        let hasVisible = false;

        items.forEach(item => {
            const text = item.textContent.trim().toUpperCase();
            const match = text.includes(filter);
            item.style.display = match ? 'block' : 'none';
            if (match) hasVisible = true;
        });

        districtDropdown.style.display = hasVisible ? 'block' : 'none';
    }

    function selectDistrict(e, name, id) {
        e.preventDefault();
        districtInput.value = name;
        selectedDistrictInput.value = id;
        districtDropdown.style.display = 'none';
    }

    document.addEventListener('click', function(event) {
        if (!districtInput.contains(event.target) && !districtDropdown.contains(event.target)) {
            districtDropdown.style.display = 'none';
        }
    });
      document.getElementById('reset').addEventListener('click', function () {
        $('.selectpicker').val('').selectpicker('refresh');
        districtInput.value = '';
        selectedDistrictInput.value = '';
    });


    $('#area_id_select').on('change', function () {
        let selectedOption = $(this).find(':selected');
        let areaId = $(this).val();
        let regionName = selectedOption.data('region-name');
        let areaName = selectedOption.text().trim();

        // Get region_id by region_name
        $.ajax({
            url: '/get-region-id-by-name',
            type: 'GET',
            data: { region_name: regionName },
            success: function (response) {
                $('#region_id').val(response.region_id);
            },
            error: function () {
                alert('Failed to fetch region ID');
                $('#region_id').val('');
            }
        });

        // Load territories by area_name
        $('#territory_id_select').empty().append('<option value="">Loading...</option>').selectpicker('refresh');

        $.ajax({
            url: '/get-territories-by-area-name',
            type: 'GET',
            data: { area_name: areaName }, // now correctly defined
            success: function (data) {
                $('#territory_id_select').empty().append('<option value="">Select Territory</option>');
                $.each(data, function (index, territory) {
                    $('#territory_id_select').append(
                        '<option value="' + territory.id + '">' + territory.territory_name + '</option>'
                    );
                });
                $('#territory_id_select').selectpicker('refresh');
            },
            error: function () {
                alert('Failed to load territories.');
                $('#territory_id_select').empty().append('<option value="">Select Territory</option>').selectpicker('refresh');
            }
        });
    });


</script>
@endsection
