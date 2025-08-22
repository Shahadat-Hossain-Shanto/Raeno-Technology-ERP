@extends('layouts.master')
@section('title', 'RAENO :: Delivery Invoice')

@section('content')
<div class="content-wrapper">

    {{-- First Print Button (Prints #invoiceArea) --}}
    <div class="container px-0 d-print-none mb-2 mt-2">
        <div class="row">
            <div class="col-12 d-flex flex-wrap align-items-center position-relative" style="padding-left: 30px;">
                <button onclick="printArea('invoiceArea')" class="btn btn-sm btn-outline-primary mt-3 mt-md-0">
                    <i class="fas fa-print"></i> Print Invoice
                </button>
                <h3 class="mx-auto w-100 text-center mt-2 mt-md-0" style="position: static; transform: none; margin-top: 0;">
                    Order Receive Invoice
                </h3>
            </div>
        </div>
    </div>

    {{-- First Invoice Area --}}
    <div id="invoiceArea" class="bg-white border rounded shadow-sm container-fluid px-5 py-5" style="max-width: 1100px; margin: 0 auto;">

        <div class="text-end text-muted small">
            System Generated
        </div>

        <div class="text-center mb-3">
            <h2 class="fw-bold text-uppercase mb-1" style="font-size: 22px;">Raeno Technology</h2>
            <p class="text-muted">Order Receive Slip</p>
        </div>

        {{-- Order Info --}}
        <div class="row  mb-3">
            <div class="col-6">
                <p class="mb-1"><strong>Order No:</strong> {{ $delivery->order_id }}</p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-1"><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
            </div>
        </div>

        {{-- Distributor Info --}}
        <div class="row  mb-3">
            <div class="col-6">
                <p class="mb-1"><strong>Customer ID:</strong> {{ $delivery->distributor_id ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Customer Name:</strong> {{ $delivery->distributor_name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Mobile:</strong> {{ $delivery->mobile ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Customer Address:</strong> {{ $distributor->address ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Delivery Address:</strong> {{ $distributor->address ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Delivery Medium:</strong> {{ $recentDelivery->medium ?? 'N/A' }}</p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-1"><strong>Delivery ID:</strong> {{ $recentDelivery->id ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Order Date:</strong> {{ $order->requisition_date ? \Carbon\Carbon::parse($order->requisition_date)->format('d M Y') : 'N/A' }}</p>
                <p class="mb-1"><strong>Approved Date:</strong> {{ $order->sales_approved_date ? \Carbon\Carbon::parse($order->sales_approved_date)->format('d M Y') : 'N/A' }}</p>
                <p class="mb-1"><strong>Delivery Date:</strong> {{ $recentDelivery->created_at ? $recentDelivery->created_at->format('d M Y') : 'N/A' }}</p>
                <p class="mb-1"><strong>Receive Date:</strong> {{ $delivery->receive_date ? \Carbon\Carbon::parse($delivery->receive_date)->format('d M Y') : 'N/A' }}</p>
            </div>
            {{-- <div class="col-">
            </div> --}}
            <div class="col-12">
                <p style="border: 1px dashed #ccc; padding: 10px 12px; margin-bottom: 8px; border-radius: 4px; background-color: #f9f9f9;">
                    <strong>Note:</strong> {{ $recentDelivery->note ?? 'N/A' }}
                </p>
            </div>
        </div>

        {{-- Product Summary --}}
        <h6 class="fw-bold  mb-2 border-bottom pb-1">Product Summary</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>Model</th>
                        <th>Variant</th>
                        <th class="text-end">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grouped = $recent->groupBy(fn($item) => $item->product_name . '|' . $item->model . '|' . $item->variant);
                    @endphp
                        @forelse($grouped as $key => $items)
                            @php
                                [$productName, $model, $variant] = explode('|', $key);
                                $qty = $items->sum('quantity');
                            @endphp
                            <tr>
                                <td>{{ $productName }}</td>
                                <td>{{ $model }}</td>
                                <td>{{ $variant }}</td>
                                <td class="text-end">{{ $qty }} {{ Str::plural('unit', $qty) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-danger">No data available.</td>
                            </tr>
                        @endforelse
                </tbody>

                @if($recent->count())
                    <tfoot>
                        <tr class="table-primary fw-semibold">
                            <td colspan="3" class="text-end">Total Quantity:</td>
                            <td class="text-end">{{ $recentDelivery->quantity }} {{ Str::plural('unit', $recentDelivery->quantity) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        {{-- Footer --}}
        <div class="border-top pt-4 mt-5">
            <div class="row align-items-center">
                <div class="col-md-6 text-muted small">
                    <p class="mb-0">Thank you for doing business with <strong>Raeno Technology</strong>.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-1 fw-semibold">Receiver's Signature</p>
                    <div style="width: 160px; border-top: 1px solid #999; margin-left: auto;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript to print a specific area --}}
<script>
    function printArea(divId) {
        const printContents = document.getElementById(divId).innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        // location.reload();
    }
</script>

<style>
@media print {
    @page {
        size: auto;
        margin: 5mm 15mm;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        font-size: 12px;
        height: auto !important;
    }

    body * {
        visibility: hidden !important;
    }

    #invoiceArea, #invoiceArea * {
        visibility: visible !important;
    }

    #invoiceArea2, #invoiceArea2 * {
        visibility: visible !important;
    }

    .d-print-none,
    header, footer, nav,
    .main-header, .main-footer,
    .sidebar, .breadcrumb,
    .app-header, .app-footer,
    .content-header,
    .content-wrapper + footer,
    body > footer,
    .container > .text-muted {
        display: none !important;
    }

    .table th, .table td {
        border: 1px solid #000 !important;
    }
}
</style>
@endsection
