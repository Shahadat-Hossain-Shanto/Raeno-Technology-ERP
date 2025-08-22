@extends('layouts.master')
@section('title', 'RAENO :: Product Return Invoice')

@section('content')
<div class="content-wrapper">

    <div class="container px-0 d-print-none mb-2 mt-2">
        <div class="row">
            <div class="col-12 d-flex flex-wrap align-items-center position-relative" style="padding-left: 30px;">
                <button onclick="printArea('invoiceArea')" class="btn btn-sm btn-outline-primary mt-3 mt-md-0">
                    <i class="fas fa-print"></i> Print Invoice
                </button>
                <h3 class="mx-auto w-100 text-center mt-2 mt-md-0">Product Return Invoice</h3>
            </div>
        </div>
    </div>

    <div id="invoiceArea" class="bg-white border rounded shadow-sm container-fluid px-5 py-5" style="max-width: 1100px; margin: 0 auto;">

        <div class="text-end text-muted small">
            System Generated
        </div>

        <div class="text-center mb-3">
            <h2 class="fw-bold text-uppercase mb-1" style="font-size: 22px;">Raeno Technology</h2>
            <p class="text-muted">Product Return Slip</p>
        </div>

        {{-- Order Info --}}
        <div class="row  mb-3">
            <div class="col-6">
                <p class="mb-1">
                    <strong>Order IDs:</strong>
                    {{ $returnQuantity->pluck('order_id')->unique()->implode(', ') }}
                </p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-1"><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
            </div>
        </div>

        {{-- Distributor Info --}}
        <div class="row  mb-3">
            <div class="col-6">
                <p class="mb-1"><strong>Customer ID:</strong> {{ $return->distributor_id ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Customer Name:</strong> {{ $return->distributor_name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Return Medium:</strong> {{ $return->medium ?? 'N/A' }}</p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-1"><strong>Customer Number:</strong> {{ $return->mobile ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Customer Address:</strong> {{ $distributor->address ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Return Date:</strong> {{ $return->created_at ? $return->created_at->format('d M Y') : 'N/A' }}</p>
            </div>
            <div class="col-12">
                <p style="border: 1px dashed #ccc; padding: 10px 12px; margin-bottom: 8px; border-radius: 4px; background-color: #f9f9f9;">
                    <strong>Note:</strong> {{ $return->note ?? 'N/A' }}
                </p>
            </div>
        </div>

        {{-- Product Summary --}}
        <h6 class="fw-bold mb-2 border-bottom pb-1">Product Summary</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>Model</th>
                        <th>Variant</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Rate</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Rebate</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalQty = 0;
                        $totalAmount = 0;
                        $totalRebate = 0;
                        $grandTotal = 0;
                    @endphp
                    @forelse($returnQuantity as $item)
                        @php
                            $totalQty += $item->quantity;
                            $totalAmount += $item->amount;
                            $totalRebate += $item->rebate;
                            $grandTotal += $item->total;
                        @endphp
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->model }}</td>
                            <td>{{ $item->variant }}</td>
                            <td class="text-end">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                            <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                            <td class="text-end">{{ number_format($item->rebate, 2) }}</td>
                            <td class="text-end">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-danger">No product return summary found.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if($returnQuantity->count())
                    <tfoot>
                        <tr class="table-primary fw-semibold">
                            <td colspan="3" class="text-end">Total:</td>
                            <td class="text-end">{{ $totalQty }}</td>
                            <td class="text-end"></td>
                            <td class="text-end">{{ number_format($totalAmount, 2) }}</td>
                            <td class="text-end">{{ number_format($totalRebate, 2) }}</td>
                            <td class="text-end">{{ number_format($grandTotal, 2) }}</td>
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

<script>
    function printArea(divId) {
        const printContents = document.getElementById(divId).innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

<style>
@media print {
    @page {
        margin: 5mm 15mm;
    }
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        font-size: 12px;
    }
    body * {
        visibility: hidden;
    }
    #invoiceArea, #invoiceArea * {
        visibility: visible;
    }
    .d-print-none {
        display: none !important;
    }
    .table th, .table td {
        border: 1px solid #000 !important;
    }
}
</style>
@endsection
