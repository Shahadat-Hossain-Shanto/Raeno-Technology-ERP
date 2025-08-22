$(document).ready(function () {
	var returnNumber =  $('#returnnumber').val();
    var t = $('#return_product_table').DataTable({
        ajax: {
            "url": "/sales-return-details/"+returnNumber,
            "dataSrc": "returnProducts"
        },
        columns: [
            { data: null },
            { data: 'product_name' },
            { data: 'return_qty' },
            { "render": function ( data, type, row, meta ){ var price = parseFloat(row.price); return price.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var tax_return = parseFloat(row.tax_return); return tax_return.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var deduction = parseFloat(row.deduction); return deduction.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ 
            	var deduction = parseFloat(row.deduction); 
            	var tax_return = parseFloat(row.tax_return);
            	var price = parseFloat(row.price);
				var return_qty = parseFloat(row.return_qty)

				var total =  parseFloat(((return_qty*price)-deduction)+tax_return)

            	return total.toFixed(2);} 
            }
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
	    	var PageInfo = $('#return_product_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});
