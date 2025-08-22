@extends('layouts.master')
@section('title', 'Add Pricing')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <!-- Optional header content -->
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid ">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0"><strong><i class="fas fa-plus"></i> ADD PRICING</strong></h5>
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

                                <form action="{{ route('pricing.store') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="product" class="form-label"
                                                    style="font-weight: normal;">Product Name <span
                                                        class="text-danger"><strong>*</strong></span></label>
                                                <select name="product_id" id="product"
                                                    class="form-control w-75 selectpicker" data-live-search="true"
                                                    required>
                                                    <option disabled selected>Select Product</option>
                                                    @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->productName }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="variant" class="form-label"
                                                    style="font-weight: normal;">Variant <span
                                                        style="font-size: 14px; color: grey;">(optional)</span></label>
                                                <select name="variant_id" id="variant"
                                                    class="form-control w-75 selectpicker" data-live-search="true">
                                                    <option disabled selected>Select Variant</option>
                                                </select>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group pt-1">
                                                <label for="landed_cost" class="form-label"
                                                    style="font-weight: normal;">Landed Cost</label>
                                                <input type="number" step="0.01" name="landed_cost" id="landed_cost"
                                                    class="form-control w-75" placeholder="e.g. 150.00">
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group pt-1">
                                                <label for="dealer_cost" class="form-label"
                                                    style="font-weight: normal;">Dealer Cost</label>
                                                <input type="number" step="0.01" name="dealer_cost" id="dealer_cost"
                                                    class="form-control w-75" placeholder="e.g. 130.00">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group pt-1">
                                                <label for="vat_tax" class="form-label" style="font-weight: normal;">VAT
                                                    Tax</label>
                                                <select name="vat_tax" id="vat_tax" class="form-control w-75">
                                                    <option value="1" selected>Include</option>
                                                    <option value="0">Exclude</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group pt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Create
                                        </button>
                                        <button type="reset" class="btn btn-outline-danger">
                                            <i class="fas fa-eraser"></i> Reset
                                        </button>
                                        <a href="{{ route('pricing.index') }}" class="btn btn-secondary">
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#product').on('change', function() {
        let productId = $(this).val();

        if (productId) {
            $.ajax({
                url: '/get-variants/' + productId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#variant').empty().append(
                        '<option disabled selected>Select Variant</option>');
                    $.each(data, function(index, variant) {
                        $('#variant').append('<option value="' + variant
                            .id + '">' + variant.variant_name +
                            '</option>');
                    });
                    $('#variant').selectpicker(
                    'refresh');
                },
                error: function() {
                    alert('Failed to load variants.');
                }
            });
        } else {
            $('#variant').empty().append('<option disabled >Select Variant</option>');
            $('#variant').selectpicker('refresh');
        }
    });
});
</script>
