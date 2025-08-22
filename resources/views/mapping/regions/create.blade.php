@extends('layouts.master')
@section('title', 'RAENO :: Add Region')

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
                    <h5><strong><i class="fas fa-map-marker-alt"></i> Add New Region</strong></h5>
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

                    <form action="{{ route('regions.store') }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="mb-2 col-lg-6">
                                <label for="region_name" class="form-label">Region Name <span class="text-danger">*</span></label>
                                <input type="text" name="region_name" class="form-control" id="region_name" value="{{ old('region_name') }}" required>
                            </div>

                            <div class="mb-2 col-lg-6 d-flex justify-content-start">
                                <button type="submit" class="btn btn-success me-2 align-self-end">Save Region</button>
                                <a href="{{ route('regions.index') }}" class="btn btn-secondary align-self-end">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
