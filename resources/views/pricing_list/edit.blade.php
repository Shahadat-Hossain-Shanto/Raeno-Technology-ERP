@extends('layouts.master')
@section('title', 'Edit Pricing')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <!-- Optional breadcrumb/title -->
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong><i class="fas fa-edit"></i> EDIT PRICING</strong></h5>
                        </div>

                        <div class="card-body">
                            <div class="container">

                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> Please fix the following issues:
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form action="{{ route('pricing-list.update', $pricing->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="product" class="form-label" style="font-weight: normal;">Product Name <span class="text-danger"><strong>*</strong></span></label>
                                                <select name="product_id" id="product" class="form-control w-75 selectpicker" data-live-search="true" required>
                                                    <option value="{{ $pricing->product_id }}" selected>{{ $pricing->product_name }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="variant" class="form-label" style="font-weight: normal;">Variant <span style="font-size: 14px; color: grey;">(optional)</span></label>
                                                <select name="variant" id="variant" class="form-control w-75 selectpicker" data-live-search="true">
                                                <option value="{{ $pricing->variant_name }}" selected>{{ $pricing->variant_name }}</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group pt-1">
                                                <label for="landed_cost" class="form-label" style="font-weight: normal;">Landed Cost</label>
                                                <input type="number" step="0.01" name="landed_cost" id="landed_cost" class="form-control w-75" value="{{ $pricing->landed_cost }}" placeholder="e.g. 150.00">
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group pt-1">
                                                <label for="dealer_cost" class="form-label" style="font-weight: normal;">Dealer Cost</label>
                                                <input type="number" step="0.01" name="dealer_cost" id="dealer_cost" class="form-control w-75" value="{{ $pricing->dealer_cost }}" placeholder="e.g. 130.00">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group pt-1">
                                                <label for="vat_tax" class="form-label" style="font-weight: normal;">VAT Tax</label>
                                                <select name="vat_tax" id="vat_tax" class="form-control w-75">
                                                    <option value="1" {{ $pricing->vat_tax == 1 ? 'selected' : '' }}>Include</option>
                                                    <option value="0" {{ $pricing->vat_tax == 0 ? 'selected' : '' }}>Exclude</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group pt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update
                                        </button>
                                        <a href="{{ route('pricing-list.index') }}" class="btn btn-secondary">
                                            Cancel
                                        </a>
                                    </div>
                                </form>
                            </div> <!-- container -->
                        </div> <!-- card-body -->
                    </div> <!-- card -->
                </div> <!-- col -->
            </div> <!-- row -->
        </div> <!-- container-fluid -->
    </div> <!-- content -->
</div> <!-- content-wrapper -->



@endsection




