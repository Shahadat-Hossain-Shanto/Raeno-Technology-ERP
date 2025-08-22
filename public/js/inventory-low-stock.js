$(document).ready(function () {
    var t = $('#store_stock_report_table').DataTable({
        ajax: {
            "url": "/inventory-low-stock-data",
            "dataSrc": "data"
        },
        columns: [
            { data: null },
            { data: 'productName' },
            { "render": function ( data, type, row, meta ){ 
            	if(row.variant_name == 'default'){
					var variant_name = 'Default'
				}else{
					var variant_name = row.variant_name
				}
            	return variant_name;
	            } 
	        },
	        { data: 'brand' },
	        { data: 'productLabel' },
           
	        { data: 'safety_stock' },
	        { data: 'onHand' },
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
	    dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#store_stock_report_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});
