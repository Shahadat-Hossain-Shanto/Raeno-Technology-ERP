@extends('layouts.master')
@section('title', 'Distributor Sales Summery Report')


<style>
#transaction-table {
    width: 100%;
    border-collapse: collapse;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 13px;
    color: #000;
    background-color: #fff; /* Ensure white background */
}

#transaction-table thead th {
    border: 1px solid #bdbdbd;
    background-color: #fff; /* Make header white for printing */
    font-weight: bold;
    text-align: center;
    padding: 8px;
}

#transaction-table tbody td {
    border: 1px solid #d3d3d3;
    padding: 6px 10px;
    text-align: center; /* Center all body cells */
    vertical-align: middle;
    background-color: #fff; /* White background for print */
}

#transaction-table tfoot td {
    border: 1px solid #bdbdbd;
    background-color: #fff; /* Remove gray background */
    padding: 8px;
    text-align: center;
}

#transaction-table tfoot td:first-child {
    text-align: center;
}

#transaction-table tr {
    page-break-inside: avoid;
}

#transaction-table tr.bold-row {
    font-weight: bold;
    background-color: #f9f9f9; /* Optional: subtle highlight for 'Opening Balance' */
}

/* Make totals bold */
#transaction-table tfoot td {
    font-weight: bold; /* Make footer values bold */
}
#transaction-table th:nth-child(2),
#transaction-table td:nth-child(2) {
    width: 10%; /* Date */
}

#transaction-table th:nth-child(3),
#transaction-table td:nth-child(3) {
    width: 15%; /* Transaction ID */
}

#transaction-table th:nth-child(4),
#transaction-table td:nth-child(4) {
    width: 15%; /* Transaction Type */
}


#transaction-table th:nth-child(5), 
#transaction-table td:nth-child(5) {
    width: 23%; 
}



@media print {
    #print-area {
        display: block !important;
    }
    #transaction {
        display: none !important; /* Hide original transaction table */
    }
    /* Hide DataTables search input */
    .dataTables_filter {
        display: none !important;
    }

    /* Hide DataTables length (show entries) dropdown */
    .dataTables_length {
        display: none !important;
    }

    /* Hide the print button */
    #print-report {
        display: none !important;
    }
    /* Optional: Hide any other UI controls you want */
    /* Example: Hide card header if not needed */
    .card-header {
        display: none !important;
    }
    
    /* Hide your filter section if still visible */
    #filter-section {
        display: none !important;
    }
    /* footer.main-footer,
    #copyright {
        display: none !important;
    } */
    .page-break {
        page-break-after: always;
    }
    .page-header {
        margin: 0;
        word-break: break-word; /* Handles long strings with no spaces */
        white-space: normal; 
        
    }
    thead { display: table-header-group; }

    footer.main-footer {
        display: none !important;
    }
   .print-page {
    display: flex;
    flex-direction: column;
    min-height: auto;
    box-sizing: border-box;
    page-break-inside: avoid;
    break-inside: avoid;
    padding: 2px;
    }

    .table-page {
        flex-grow: 1;
    }
    
    .system-generated-print {
        text-align: right;
        font-size: 14px;
        font-weight: bold;
        color: #000;
        margin-top: 2vh; 
        /* padding-top: 10px; */
    }

}
@media screen {
    #print-area {
        display: none;
    }
}

</style>


@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
               
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h5 class="m-0">
                                <strong><i class="fas fa-list-alt"></i> Distributor Sales Summery Report</strong>
                            </h5>
                        </div>
                        <div class="card-body">


                          <div class="row" id="filter-section"> <!-- Add this ID -->
                            <!-- Distributor Filter -->
                           

                              {{-- <div class="col-md-6 mb-2">
                                  <label for="">Distributor</label>
                                  <select class="selectpicker form-control" id="distributor-head-code" name="distributor" data-live-search="true">
                                      <option value="">Select Distributor</option>
                          
                                      @foreach ($distributors as $distributor)
                                          <option 
                                            
                                          >
                                              {{ $distributor->distributor_name }}
                                          </option>
                                      @endforeach
                                  </select>
                              </div> --}}
                              
                        
                            <!-- Start Date Filter -->
                            <div class="col-md-3 mb-2">
                                <label for="filterStartDate">Start Date</label>
                                <input type="date" class="form-control" id="filterStartDate">
                            </div>
                        
                            <!-- End Date Filter -->
                            <div class="col-md-3 mb-2">
                                <label for=" filterEndDate">End Date</label>
                                <input type="date" class="form-control" id="filterEndDate">
                            </div>
                          </div>

                        <div id="print-area"></div>


                        <div id="transaction" style="display:none;">
                            <table id="transaction-table" class="display" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Distributor Name</th>
                                        <th>Distributor Status</th>
                                        <th>Opening Balance</th>
                                        <th>Total Sales</th>
                                        <th>Sales Return</th>
                                        <th>Discount</th>
                                        <th>Ledger Adjustment</th>
                                        <th>Total Collection</th>
                                        <th>Closing Balance</th>
                                        {{-- <th>Ledger Adjustment</th>
                                        <th>Total Collection</th>
                                        <th>Closing Balance</th> --}}
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                                               
                          <button id="print-report" class="btn btn-primary mb-3">Print Report</button>                          
                        </div> <!-- Card -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>

</script>

<script>
$(document).ready(function () {
    

function buildPaginatedReport(dateRangeText) {
    const rowsPerPage = 25;
    const printArea = document.getElementById('print-area');
    printArea.innerHTML = '';

    const transactionSection = document.getElementById('transaction');

   const headerHtml = `
    <div class="page-header text-center mb-3">
        <h3 class="mb-0">Raeno Technologies</h3>
        <h5 class="mb-0">Badda</h5>
        <h5 class="text-center">Distributor Sales Summery Report</h5>
        <p class="fw-bold mt-2 text-center" style="font-size: 16px;">${dateRangeText}</p>

    </div>
    `;



    const originalTable = document.querySelector('#transaction-table');
    const allRows = Array.from(originalTable.querySelectorAll('tbody tr'));
    const theadHtml = originalTable.querySelector('thead').outerHTML;
    const tfootHtml = originalTable.querySelector('tfoot')?.outerHTML || '';

    for (let i = 0; i < allRows.length; i += rowsPerPage) {
        const chunk = allRows.slice(i, i + rowsPerPage);
        const isLastPage = i + rowsPerPage >= allRows.length;
        const tableHtml = `
        <div class="print-page">
            ${headerHtml}
            <div class="table-page">
                <table id ="transaction-table" class="display" width="100%" style="width:100%; border-collapse: collapse; font-family: 'Segoe UI'; font-size: 13px;">
                    ${theadHtml}
                    <tbody>
                        ${chunk.map(row => row.outerHTML).join('')}
                    </tbody>
                </table>
                <div class="system-generated-print">
                    <strong>System Generated</strong>
                </div>               
            </div>
        </div>
             
        `;

    const pageDiv = document.createElement('div');
    pageDiv.classList.add('print-page');
    if (!isLastPage) {
        pageDiv.classList.add('page-break');
    }
    printArea.appendChild(pageDiv);
    pageDiv.innerHTML = tableHtml;
    
    }

    window.print();
}


    $('.selectpicker').selectpicker();
    let dataTable = $('#transaction-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: '{{ route("distributor-sales-summary-report.data") }}',
            type: 'GET',
            data: function (d) {
                d.start_date = $('#filterStartDate').val();
                d.end_date = $('#filterEndDate').val();
            }
        },
        columns: [
            { data: 'sl' },
            { data: 'distributor_name' },
            { data: 'distributor_status' },
            { data: 'opening_balance' },
            { data: 'total_sales' },
            { data: 'sales_return' },
            { data: 'discount'},
            { data: 'ledger_adjustment'},
            { data: 'total_collection'},
            { data: 'closing_balance'},


        ],
      footerCallback: function (row, data, start, end, display) {
      const parseFloatSafe = function (i) {
          return typeof i === 'string'
              ? parseFloat(i.replace(/,/g, '')) || 0
              : typeof i === 'number'
              ? i
              : 0;
      };
  
      let totalDebit = 0;
      let totalCredit = 0;
  
      data.forEach(function (d) {
          if (d.reference_note !== 'Opening Balance') {
              totalDebit += parseFloatSafe(d.debit);
              totalCredit += parseFloatSafe(d.credit);
          }
        });
  
      $('#total-debit').html(totalDebit.toLocaleString(undefined, { minimumFractionDigits: 2 }));
      $('#total-credit').html(totalCredit.toLocaleString(undefined, { minimumFractionDigits: 2 }));
    },

        responsive: true,
        autoWidth: false,
        ordering: true,
        pageLength: 25,


    });

   function allFiltersSelected() {
    return $('#filterStartDate').val() && $('#filterEndDate').val();
}



  $('#filterStartDate, #filterEndDate').on('change', function () {
    if (allFiltersSelected()) {
        $('#filter-section').hide();
        $('#transaction').show();

        let startDate = formatDate($('#filterStartDate').val());
        let endDate = formatDate($('#filterEndDate').val());
        $('#selected-date-range').text(`From ${startDate} to ${endDate}`);

        dataTable.ajax.reload(null, false);
    }
});


    function formatDate(dateStr) {
        let options = { year: 'numeric', month: 'long', day: 'numeric' };
        let date = new Date(dateStr);
        return date.toLocaleDateString('en-US', options);
    }
    
       $('#print-report').on('click', function () {
    let startDate = formatDate($('#filterStartDate').val());
    let endDate = formatDate($('#filterEndDate').val());
    let dateRangeText = `From ${startDate} to ${endDate}`;

    // Show table temporarily so DataTables renders rows
    $('#transaction').show();

    dataTable.page.len(-1).draw();

    dataTable.one('draw', function () {
        setTimeout(() => {
            buildPaginatedReport(dateRangeText);
            dataTable.page.len(25).draw();
        }, 500);
    });
});

    
});


</script>
@endsection
