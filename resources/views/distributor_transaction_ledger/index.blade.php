@extends('layouts.master')
@section('title', 'Distributor Transaction Report')


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


#transaction-table th:nth-child(5), /* Particulars */
#transaction-table td:nth-child(5) {
    width: 23%; /* Increase width */
}



@media print {
    #print-area {
        display: block !important;
    }
    #transaction {
        display: none !important; /* Hide original transaction table */
    }
    #transaction-table th:first-child,
    #transaction-table td:first-child {
        display: none !important;
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

    /* Hide pagination controls (Previous, Next buttons, page numbers) */
    .dataTables_paginate {
        display: none !important;
    }

    /* Hide DataTables info (e.g. "Showing 1 to 10 of 100 entries") */
    .dataTables_info {
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
    tfoot { display: table-footer-group; }
    footer.main-footer {
        display: none !important;
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
                                <strong><i class="fas fa-list-alt"></i> Distributor Transaction Report</strong>
                            </h5>
                        </div>
                        <div class="card-body">


                          <div class="row" id="filter-section"> <!-- Add this ID -->
                            <!-- Distributor Filter -->
                            



@if (!$user->distributor)
    <div class="col-md-6 mb-2">
        <label for="">Distributor</label>
        <select class="selectpicker form-control" id="distributor-head-code" name="distributor" data-live-search="true">
            <option value="">Select Distributor</option>

            @foreach ($distributors as $distributor)
                <option 
                    value="{{ $distributor->head_code }}"
                    data-name="{{ $distributor->distributor_name }}"
                    data-contact="{{ $distributor->contact_no }}"
                    data-address="{{ $distributor->address }}"
                    data-district="{{ $distributor->district }}"
                >
                    {{ $distributor->distributor_name }}
                </option>
            @endforeach
        </select>
    </div>
@endif

                           
                        
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
                                        <th>Date</th>
                                        <th>Transaction ID</th>
                                        <th>Transaction Type</th>
                                        <th>Particulars</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><strong>Total:</strong></td>
                                        <td id="total-debit"></td>
                                        <td id="total-credit"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
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
    function getSelectedDistributorInfo() {
    const selectedOption = $('#distributor-head-code option:selected');
    return {
        name: selectedOption.data('name'),
        contact: selectedOption.data('contact'),
        address: selectedOption.data('address'),
        district: selectedOption.data('district')
    };
}

function buildPaginatedReport(dateRangeText) {
    const rowsPerPage = 30;
    const printArea = document.getElementById('print-area');
    printArea.innerHTML = '';

    //  Ensure the table is visible during row extraction
    const transactionSection = document.getElementById('transaction');
    const wasHidden = transactionSection.style.display === 'none';
    if (wasHidden) transactionSection.style.display = 'block';

    const distributor = getSelectedDistributorInfo();

   const headerHtml = `
    <div class="page-header text-center mb-3">
        <h3 class="mb-0">Raeno Technologies</h3>
        <h5 class="mb-0">Badda</h5>
        <h5 class="text-center">Statement of Account</h5>
        <p class="fw-bold mt-2 text-center" style="font-size: 16px;">${dateRangeText}</p>

        <div class="d-flex justify-content-between mt-3" style="text-align: left;">
            <div style="flex: 1;">
                <p style="margin: 0; word-break: break-word;"><strong>Distributor Name:</strong> ${distributor.name}</p>
                <p style="margin: 0; word-break: break-word;"><strong>Contact:</strong> ${distributor.contact}</p>
                <p style="margin: 0; word-break: break-word;"><strong>Address:</strong> ${distributor.address}</p>
            </div>
            <div style="text-align: right;">
                <p style="margin: 0;"><strong>District:</strong> ${distributor.district}</p>
            </div>
        </div>
    </div>
    `;



    const originalTable = document.querySelector('#transaction-table');
    const allRows = Array.from(originalTable.querySelectorAll('tbody tr'));
    const theadHtml = originalTable.querySelector('thead').outerHTML;
    const tfootHtml = originalTable.querySelector('tfoot')?.outerHTML || '';

    for (let i = 0; i < allRows.length; i += rowsPerPage) {
        const chunk = allRows.slice(i, i + rowsPerPage);
        const tableHtml = `
            ${headerHtml}
            <div class="table-page">
                <table id ="transaction-table" class="display" width="100%" style="width:100%; border-collapse: collapse; font-family: 'Segoe UI'; font-size: 13px;">
                    ${theadHtml}
                    <tbody>
                        ${chunk.map(row => row.outerHTML).join('')}
                    </tbody>
                    ${tfootHtml}
                </table>
            </div>
        `;

        const pageDiv = document.createElement('div');
        //  Add page-break class only if NOT the last chunk
    if (i + rowsPerPage < allRows.length) {
        pageDiv.classList.add('page-break');
    }
        
        pageDiv.innerHTML = tableHtml;
        printArea.appendChild(pageDiv);
    }

    if (wasHidden) transactionSection.style.display = 'none'; // hide again if it was hidden before

    window.print();
}


    $('.selectpicker').selectpicker();
    let dataTable = $('#transaction-table').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        ajax: {
            url: '{{ route("distributor-transaction-report.data") }}',
            type: 'GET',
            data: function (d) {
                d.distributorHeadCode = $('#distributor-head-code').val();
                d.start_date = $('#filterStartDate').val();
                d.end_date = $('#filterEndDate').val();
            }
        },
        columns: [
            { data: 'sl', name: 'sl' },
            { data: 'transaction_date', name: 'transaction_date' },
            { data: 'transaction_id', name: 'transaction_id' },
            { data: 'transaction_type', name: 'transaction_type' },
            { data: 'reference_note', name: 'reference_note' },
            { data: 'debit', name: 'debit' },
            { data: 'credit', name: 'credit' },
            { data: 'balance', name: 'balance'},
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

       createdRow: function (row, data, dataIndex) {
           if (data.reference_note === 'Opening Balance') {
               $(row).addClass('bold-row');
           }
        }

    });

    function allFiltersSelected() {
        return $('#distributor-head-code').val() &&
               $('#filterStartDate').val() &&
               $('#filterEndDate').val();
    }

  $('#distributor-head-code, #filterStartDate, #filterEndDate').on('change', function () {
    if (allFiltersSelected()) {
        $('#filter-section').hide();
        $('#transaction').show();

        let startDate = formatDate($('#filterStartDate').val());
        let endDate = formatDate($('#filterEndDate').val());
        $('#selected-date-range').text(`From ${startDate} to ${endDate}`);

        dataTable.ajax.reload(null, false); // Reload data, don't reset paging
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
    
        dataTable.page.len(-1).draw();
    
       dataTable.one('draw', function () {
        // Wait until DOM updates are complete
        setTimeout(() => {
            buildPaginatedReport(dateRangeText);  // Now rows are available
            dataTable.page.len(25).draw();
        }, 300); // Delay can be 200-300ms to ensure DOM has updated
        });
    
       });
    
});


</script>
@endsection
