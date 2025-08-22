$(document).ready(function () {
    var t = $('#due_table').DataTable({
        ajax: {
            "url": "/due-reports-data",
            "dataSrc": "due"
        },
        columns: [
            { data: null },
            { "render": function ( data, type, row, meta ){ 
            	
            	return '<td><span class="badge badge-primary"><a style="color: white" href="due-in-details/'+row.clientId+'">'+row.name+'</a></span></td>';
	            } 
	        },
            { "render": function ( data, type, row, meta ){ var td = parseFloat(row.td); return td.toFixed(2);} },
            
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
	    	var PageInfo = $('#due_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});
	