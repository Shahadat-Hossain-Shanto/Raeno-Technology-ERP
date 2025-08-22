$(document).ready(function () {

	var customerId =  $('#customerid').val();

    var t = $('#order_table').DataTable({
        ajax: {
            "url": "/customer-details/"+customerId,
            "dataSrc": "order"
        },
        columns: [
            { data: null },
            { data: 'orderId' },
            { "render": function ( data, type, row, meta ){ var total = parseFloat(row.total); return total.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var totalDiscount = parseFloat(row.totalDiscount); return totalDiscount.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var totalTax = parseFloat(row.totalTax); return totalTax.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var grandTotal = parseFloat(row.grandTotal); return grandTotal.toFixed(2);} },
            { data: 'orderDate' },
        ],
        columnDefs: [
	        {
	            searchable: true,
	            orderable: true,
	            targets: 0,
	        },
	    ],
	    order: [[1, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#order_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});


