@extends('layouts.master')
@section('title', 'Requisition Details')

@section('content')
<style>
    footer {
        display: none !important;
    }

    .page-break {
        page-break-after: always;
    }

   @media print {
    .print-page {
        page-break-inside: avoid;
    }

    .page-break {
        page-break-after: always;
    }

    #print-area {
        display: block !important;
    }

    .content > .container-fluid,
    .content-header {
        display: none !important;
    }
     .system-generated-print {
        position: fixed;
        bottom: 20px;
        right: 30px;
        font-size: 14px;
        font-weight: bold;
        color: #000;
    }
}

    /* Default hidden */
    #print-area {
        display: none;
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

                    <div class="row d-flex justify-content-center">
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
                  
                        </div>

                    </div>

                    <hr>

                    <h5>Product Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="product-table">
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

 

                   

                   <a href="{{ route('approved-sales-order.index') }}" class="btn btn-secondary d-print-none">
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
    const productTable = document.querySelector('#product-table tbody');
    const allDataRows = Array.from(productTable.querySelectorAll('tr')).filter((row, index, arr) => index < arr.length - 1);


    const headerHtml = `
        <div class="page-header text-center mb-3">
            <h3 class="mb-0">Raeno Technologies</h3>
            <h5 class="mb-0">Order Slip</h5>
            <hr>
            <div class=" d-flex justify-content-between">
                <div class="text-start">
                    <p><strong>Distributor Name:</strong> {{ $requisition->name }}</p>
                    <p><strong>Mobile:</strong> {{ $requisition->mobile }}</p>
                    <p><strong>Address:</strong> {{ $requisition->address }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($requisition->status == 0 ? 'Pending' : ($requisition->status == 1 ? 'Approved' : 'Canceled')) }}</p>
                </div>
                <div class="">
                    <p><strong>Requisition ID:</strong> {{ $requisition->requisition_id }}</p>
                    <p><strong>Requisition Date:</strong> {{ $requisition->requisition_date }}</p>
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

function buildPaginatedOutput() {
    printArea.innerHTML = ''; // Clear previous content
    const totalRows = allDataRows.length;

    if (totalRows <= rowsPerPage) {
        // Case: Single page — no page break
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
           <div class="system-generated-print">
               <strong>System Generated</strong>
           </div>
       </div>
     `;

        return;
    }

    // Case: Multi-page print
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    let output = '';

    for (let i = 0; i < totalRows; i += rowsPerPage) {
        const chunk = allDataRows.slice(i, i + rowsPerPage);
        const tableRows = chunk.map(row => row.outerHTML).join('');
        const currentPage = Math.floor(i / rowsPerPage) + 1;
        const isLastPage = currentPage === totalPages;

        // Apply page-break only if not last page
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
                
            </div>
            <div class="system-generated-print">
                <strong>System Generated</strong>
            </div>
        `;
    }

    printArea.innerHTML = output;
}




    // Build print content just before printing
    window.addEventListener('beforeprint', buildPaginatedOutput);
});
</script>
@endsection




