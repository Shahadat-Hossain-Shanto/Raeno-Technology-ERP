@extends('layouts.master')
@section('title', 'RAENO :: Add Territory')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        {{-- Optional header content --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </div>

    <div class="content col-lg-8">
        <div class="container">
            <div class="card card-primary">
                <div class="card-header">
                    <h5><strong><i class="fas fa-map-marker-alt"></i> Add New Territory</strong></h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('territories.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-2 col-lg-6">
                                <label for="region_name" class="form-label">Region Name <span class="text-danger">*</span></label>
                                <select name="region_name" id="region_name" class="form-control" required>
                                    <option value="">-- Select Region --</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->region_name }}" {{ old('region_name') == $region->region_name ? 'selected' : '' }}>
                                            {{ $region->region_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-2 col-lg-6">
                                <label for="area_name" class="form-label">Area Name <span class="text-danger">*</span></label>
                                <select name="area_name" id="area_name" class="form-control" required>
                                    <option value="">-- Select Area --</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->area_name }}" {{ old('area_name') == $area->area_name ? 'selected' : '' }}>
                                            {{ $area->area_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-2 col-lg-6">
                                <label for="territory_name" class="form-label">Territory Name <span class="text-danger">*</span></label>
                                <input type="text" name="territory_name" class="form-control" id="territory_name" value="{{ old('territory_name') }}" required>
                            </div>

                            {{-- <div class="mb-2 col-lg-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="">-- Select Status --</option>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div> --}}
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success me-2">Save Territory</button>
                            <a href="{{ route('territories.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
