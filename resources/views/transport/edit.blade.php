@extends('layouts.master')
@section('title', 'RAENO :: Edit Transport')

@section('content')
<div class="content-wrapper py-4 px-3">
    <div class="container-fluid">
        <div class="row justify-content-start">
            <div class="col-md-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-warning text-dark">
                        <h5><strong><i class="fas fa-edit"></i> Edit Transport</strong></h5>
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

                        <form action="{{ route('transports.update', $transport->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-4">
                                <label for="name">Transport Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter transport name"
                                    value="{{ old('name', $transport->name) }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('transports.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
