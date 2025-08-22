@extends('layouts.master')
@section('title', 'Requisition Details')

@section('content')
<style>
    footer {
        display: none !important;
    }

    @media print {
        .content > .container-fluid,
        .content-header,
        .btn,
        .d-print-none {
            display: none !important;
        }

        #print-area {
        display: block !important;
        visibility: visible !important;
        }

       /* .print-page {
        position: relative;
        min-height: 100vh;
        page-break-inside: avoid;
        } */
        .print-page {
            display: flex;
            flex-direction: column;
            min-height: auto;
            box-sizing: border-box;
            page-break-inside: avoid;
            break-inside: avoid;
        }

       .page-break {
           page-break-after: always;
       }

       .system-generated-print {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
            color: #000;
            margin-top: 2vh;
        }
        .page-header {
            padding-top: 5px;
        }
        hr {
        border: none;
        border-top: 1px solid #808080ff;
        border-bottom: none;
        margin: 0;
        display: block;
        }

    }

    #print-area {
    display: none;
    visibility: hidden;
    }

</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print
            </button>
           
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                      @if(session('success'))
                      <div class="alert alert-success">
                          {{ session('success') }}
                      </div>
                      @endif
                      
                      @if(session('error'))
                          <div class="alert alert-danger">
                              {{ session('error') }}
                          </div>
                      @endif

                    <div class="text-center mb-4">
                        <h3 class="mb-0">Raeno Technologies</h3>
                        <h5 class="text-muted">Order Slip</h5>
                        <hr>
                    </div>

                    <div class="row">
                        <!-- Left Side -->
                        <div class="col-md-6">
                            <p><strong>Distributor Name:</strong> {{ $requisition->name }}</p>
                            <p><strong>Mobile:</strong> {{ $requisition->mobile }}</p>
                            <p><strong>Address:</strong> {{ $requisition->address }}</p>
                            

                            <p><strong>Status:</strong> {{ ucfirst($requisition->status == 0 ? 'Pending' : ($requisition->status == 1 ? 'Approved' : 'Canceled')) }}</p>
                        </div>

                        
                        <!-- Right Side -->
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Requisition ID:</strong> {{ $requisition->requisition_id }}</p>
                            <p class="mb-1"><strong>Requisition Date:</strong> {{ $requisition->requisition_date }}</p>
                            <p class="mb-1">
                                <strong>ACC Approval:</strong>
                                {{ ucfirst($requisition->accounts_approved_status == 0 ? 'Pending' : ($requisition->accounts_approved_status == 1 ? 'Approved' : 'Canceled')) }}
                                @if($requisition->accounts_approved_status == 1 && $requisition->accounts_approved_date)
                                     <span class="badge bg-success ms-2">
                                         {{ \Carbon\Carbon::parse($requisition->accounts_approved_date)->format('d M Y, h:i A') }}
                                     </span>
                                @endif
                            </p>
                        
                            <p class="mb-1">
                                <strong>HOS Approval:</strong>
                                {{ ucfirst($requisition->sales_approved_status == 0 ? 'Pending' : ($requisition->sales_approved_status == 1 ? 'Approved' : 'Canceled')) }}
                                @if($requisition->sales_approved_status == 1 && $requisition->sales_approved_date)
                                     <span class="badge bg-success ms-2">
                                         {{ \Carbon\Carbon::parse($requisition->sales_approved_date)->format('d M Y, h:i A') }}
                                     </span>
                                    @elseif($requisition->sales_approved_status == 2 && $requisition->sales_approved_date)
                                     <span class="badge bg-danger ms-2">
                                         {{ \Carbon\Carbon::parse($requisition->sales_approved_date)->format('d M Y, h:i A') }}
                                     </span>
                                @endif
                            </p>

                        
                        </div>

                    </div>

                    <hr>

                    <h5>Product Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>SL#</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Rebate</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($details as $detail)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->product_details }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->rate, 2) }}</td>
                                    <td>{{ number_format($detail->amount, 2) }}</td>
                                    <td>{{ number_format($detail->rebate, 2) }}</td>
                                    <td>{{ number_format($detail->total_amount, 2) }}</td>
                                </tr>
                                @endforeach

                                <tr>
                                    <td></td>
                                    <td><strong>Total</strong></td>
                                    <td><strong>{{ $requisition->quantity }}</strong></td>
                                    <td></td>
                                    <td><strong>{{ number_format($requisition->amount, 2) }}</strong></td>
                                    <td><strong>{{ number_format($requisition->rebate, 2) }}</strong></td>
                                    <td><strong>{{ number_format($requisition->total_amount, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p class="mb-1"><strong>Distributor Note:</strong> {{ $requisition->requisition_note }}</p>
                    <p class="mb-1"><strong>ACC Note:</strong> {{ $requisition->accounts_approved_note }}</p>
                    <p class="mb-1"><strong>HOS Note:</strong> {{ $requisition->sales_approved_note }}</p>

                   <a href="{{ route('approved-requisitions.index') }}" class="btn btn-secondary d-print-none">
                              <i class="fas fa-arrow-left"></i> Back to List
                    </a>



                </div>
            </div>
        </div>
    </div>
</div>
<div id="print-area"></div>

@endsection


@section('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const rowsPerPage = 25;
    const printArea = document.getElementById('print-area');
    const productTable = document.querySelector('.table tbody');
    const allDataRows = Array.from(productTable.querySelectorAll('tr')).filter((row, index, arr) => index < arr.length - 1);

    const headerHtml = `
        <div class="page-header text-center mb-3">
            <h3 class="mb-0">Raeno Technologies</h3>
            <h5 class="mb-4">Order Slip</h5>
            <div class="d-flex justify-content-between">
                <div class="text-start">
                    <p><strong>Distributor Name:</strong> {{ $requisition->name }}</p>
                    <p><strong>Mobile:</strong> {{ $requisition->mobile }}</p>
                    <p><strong>Address:</strong> {{ $requisition->address }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($requisition->status == 0 ? 'Pending' : ($requisition->status == 1 ? 'Approved' : 'Canceled')) }}</p>
                </div>
                <div class="text-end pr-1">
                    <p><strong>Requisition ID:</strong> {{ $requisition->requisition_id }}</p>
                    <p><strong>Requisition Date:</strong> {{ $requisition->requisition_date }}</p>
                   <p class="mb-1">
                                <strong>ACC Approval:</strong>
                                {{ ucfirst($requisition->accounts_approved_status == 0 ? 'Pending' : ($requisition->accounts_approved_status == 1 ? 'Approved' : 'Canceled')) }}
                                @if($requisition->accounts_approved_status == 1 && $requisition->accounts_approved_date)
                                     <span class="badge bg-success ms-2">
                                         {{ \Carbon\Carbon::parse($requisition->accounts_approved_date)->format('d M Y, h:i A') }}
                                     </span>
                                @endif
                    </p>
                        
                    <p class="mb-1">
                        <strong>HOS Approval:</strong>
                        {{ ucfirst($requisition->sales_approved_status == 0 ? 'Pending' : ($requisition->sales_approved_status == 1 ? 'Approved' : 'Canceled')) }}
                        @if($requisition->sales_approved_status == 1 && $requisition->sales_approved_date)
                             <span class="badge bg-success ms-2">
                                 {{ \Carbon\Carbon::parse($requisition->sales_approved_date)->format('d M Y, h:i A') }}
                             </span>
                            @elseif($requisition->sales_approved_status == 2 && $requisition->sales_approved_date)
                             <span class="badge bg-danger ms-2">
                                 {{ \Carbon\Carbon::parse($requisition->sales_approved_date)->format('d M Y, h:i A') }}
                             </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    `;

    const tableHead = `
        <thead class="thead-light">
            <tr>
                <th>SL#</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Rebate</th>
                <th>Total Amount</th>
            </tr>
        </thead>
    `;

    const totalRow = `
        <tr>
            <td></td>
            <td><strong>Total</strong></td>
            <td><strong>{{ $requisition->quantity }}</strong></td>
            <td></td>
            <td><strong>{{ number_format($requisition->amount, 2) }}</strong></td>
            <td><strong>{{ number_format($requisition->rebate, 2) }}</strong></td>
            <td><strong>{{ number_format($requisition->total_amount, 2) }}</strong></td>
        </tr>
    `;

    const notesHtml = `
        <p><strong>Distributor Note:</strong> {{ $requisition->requisition_note }}</p>
        <p><strong>ACC Note:</strong> {{ $requisition->accounts_approved_note }}</p>
        <p><strong>HOS Note:</strong> {{ $requisition->sales_approved_note }}</p>
        <hr>
    `;

    function buildPaginatedOutput() {
        printArea.innerHTML = '';
        const totalRows = allDataRows.length;

        if (totalRows <= rowsPerPage) {
            const tableRows = allDataRows.map(row => row.outerHTML).join('');
            printArea.innerHTML = `
                <div class="print-page">
                    ${headerHtml}
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            ${tableHead}
                            <tbody>
                                ${tableRows}
                                ${totalRow}
                            </tbody>
                        </table>
                    </div>
                    ${notesHtml}
                    <div class="system-generated-print">
                        <strong>System Generated</strong>
                    </div>
                </div>
            `;
            return;
        }

        const totalPages = Math.ceil(totalRows / rowsPerPage);
        let output = '';

        for (let i = 0; i < totalRows; i += rowsPerPage) {
            const chunk = allDataRows.slice(i, i + rowsPerPage);
            const tableRows = chunk.map(row => row.outerHTML).join('');
            const currentPage = Math.floor(i / rowsPerPage) + 1;
            const isLastPage = currentPage === totalPages;
            const pageClass = `print-page${!isLastPage ? ' page-break' : ''}`;

            output += `
                <div class="${pageClass}">
                    ${headerHtml}
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            ${tableHead}
                            <tbody>
                                ${tableRows}
                                ${isLastPage ? totalRow : ''}
                            </tbody>
                        </table>
                    </div>
                    ${isLastPage ? notesHtml : ''}
                    ${isLastPage ? `
                        <div class="system-generated-print">
                            <strong>System Generated</strong>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        printArea.innerHTML = output;
    }

    // Custom print trigger to allow rendering before printing
    window.handlePrint = function () {
        buildPaginatedOutput();
        setTimeout(() => {
            window.print();
        }, 300); // Wait for DOM to render
    };

    // If user uses browser print shortcut (Ctrl+P), build print layout too
    window.addEventListener('beforeprint', buildPaginatedOutput);
});
</script>
@endsection


