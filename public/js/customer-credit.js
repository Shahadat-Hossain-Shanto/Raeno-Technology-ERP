


$(document).ready(function () {

    var t = $('#due_table').DataTable({
        ajax: {
            "url": "/customer-credits-data",
            "dataSrc": "due"
        },
        columns: [
            { data: null },
            {
                "render": function (data, type, row, meta) {
                    return '<span class="badge badge-primary"><a style="color: white" href="due-in-details/' + row.client_id + '">' + row.name + '</a></span>'
                }
            },
            {
                "render": function (data, type, row, meta) { return row.mobile; }
            },
            { "render": function (data, type, row, meta) { var totalPurchase = parseFloat(row.totalPurchase); return totalPurchase.toFixed(2); } },
            { "render": function (data, type, row, meta) { var totalDeposit = parseFloat(row.totalDeposit); return totalDeposit.toFixed(2); } },
            {
                "render": function (data, type, row, meta) {
                    var balance = parseFloat(row.balance);
                    if (balance > 0) {
                        return balance.toFixed(2) + ' Dr';
                    }
                    else {
                        return -1 * balance.toFixed(2) + ' Cr';
                    }
                }
            },
        ],
        columnDefs: [
            {
                searchable: true,
                orderable: true,
                targets: 0,
            },
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
        dom: 'Bfrtip',
        buttons: [

            {
                extend: 'copy',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5],
                }
            },
        ]
    });

    t.on('order.dt search.dt', function () {
        t.on('draw.dt', function () {
            var PageInfo = $('#due_table').DataTable().page.info();
            t.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });

    }).draw();
});
