@extends('layouts.master')
@section('title', 'Primary Stock Data')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- Optional: Page title here --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-10">

                    <div class="card card-primary shadow-sm">
                        <div class="card-header">
                            <h5 class="m-0">
                                <strong><i class="fas fa-file-contract"></i> PRIMARY STOCK DATA</strong>
                            </h5>
                        </div>

                        <div class="card-body">
                            <!-- Search Form -->
                            <form id="productInReportForm" method="GET">
                                {{-- @csrf --}}
                                <div class="form-row g-2 align-items-end">

                                    <div class="form-group col-md-3">
                                        <label for="product_name">Product Name</label>
                                        <select class="form-control select2" id="product_name" name="product_name">
                                            <option value="">All</option>
                                            @foreach ($product as $item)
                                                <option value="{{ $item->productName }}">{{ $item->productName }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="model">Model</label>
                                        <select class="form-control select2" id="model" name="model">
                                            <option value="">All</option>
                                            @foreach ($model as $item)
                                                <option value="{{ $item->model_name }}">{{ $item->model_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="variant_name">Variant</label>
                                        <select class="form-control select2" id="variant_name" name="variant_name">
                                            <option value="">All</option>
                                            @foreach ($variant as $item)
                                                <option value="{{ $item->variant_name }}">{{ $item->variant_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-chart-bar"></i> Generate
                                        </button>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <button type="button" class="btn btn-outline-danger w-100" onclick="resetButton()">
                                            <i class="fas fa-eraser"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <hr>
                            <div id="tablePart" class="pt-3">
                                <div class="d-flex justify-content-between align-items-center pb-3">
                                    <h5 class="m-0"><strong>Primary Stock Data</strong></h5>
                                    <div class="text-end">
                                        <span class="badge bg-primary px-3 py-2 fs-6">
                                            Total: <span id="totalCount">0</span>
                                        </span>
                                        <span class="badge bg-info text-dark px-3 py-2 fs-6">
                                            Filtered: <span id="filteredCount">0</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="primaryInTable" class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Brand</th>
                                                <th>Manufacturer</th>
                                                <th>Model</th>
                                                <th>Variant</th>
                                                <th>Qty</th>
                                                <th>Added By</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->

                </div> <!-- /.col -->
            </div> <!-- /.row -->
        </div> <!-- /.container-fluid -->
        </div> <!-- /.content -->
    </div> <!-- /.content-wrapper -->

<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Select',
            allowClear: true,
            width: '100%'
        });

        const table = $('#primaryInTable').DataTable({
            ajax: {
                url: '{{ route('primary.in.report.data') }}',
                dataSrc: 'data',
                data: function (d) {
                    d.product_name = $('#product_name').val();
                    d.model = $('#model').val();
                    d.variant_name = $('#variant_name').val();
                    return d;
                }
            },
            processing: true,
            serverSide: true,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'print'
            ],
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false,
                    searchable: false,
                    title: '#'
                },
                { data: 'product_name', title: 'Product' },
                { data: 'brand', title: 'Brand' },
                { data: 'manufacturer', title: 'Manufacturer' },
                { data: 'model', title: 'Model' },
                { data: 'variant_name', title: 'Variant' },
                { data: 'quantity', title: 'Qty' },
                {
                    data: 'created_by_name',
                    title: 'Added By',
                    render: function (data) {
                        return data ?? 'N/A';
                    }
                }
            ],
            order: [[1, 'asc']]
        });

        table.on('xhr', function () {
            const json = table.ajax.json();
            $('#totalCount').text(json.recordsTotal ?? 0);
            $('#filteredCount').text(json.recordsFiltered ?? 0);
        });

        $('#productInReportForm').on('submit', function (e) {
            e.preventDefault();
            table.ajax.reload();
        });

        // 👉 On Reset Button Click
        window.resetButton = function () {
            $('#productInReportForm')[0].reset();

            $('#product_name').val('').trigger('change');
            $('#model').val('').trigger('change');
            $('#variant_name').val('').trigger('change');

            $('#startdate').trigger('change');
            $('#enddate').trigger('change');

            table.ajax.reload();
        };
    });
</script>



<style>
    .select2-container--default .select2-selection--single {
        height: 38px !important;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
    }

    .select2-selection__rendered {
        line-height: 24px !important;
    }

    .select2-selection__arrow {
        height: 36px !important;
        top: 1px !important;
    }
</style>
@endsection
