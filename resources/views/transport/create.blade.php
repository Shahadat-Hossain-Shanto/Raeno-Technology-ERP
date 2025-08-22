@extends('layouts.master')
@section('title', 'RAENO :: Add Transport')

@section('content')
<div class="content-wrapper py-4 px-3">
    <div class="container-fluid">
        <div class="row justify-content-start">
            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-info text-white">
                        <h5><strong><i class="fas fa-plus-circle"></i> Add New Transport</strong></h5>
                    </div>

                    <div class="card-body">
                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    <form action="{{ route('transports.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="name">Transport Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter transport name" value="{{ old('name') }}" required>
                        </div>

                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('transports.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
