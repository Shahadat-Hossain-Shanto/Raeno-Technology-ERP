$(document).ready(function () {
    var t = $('#product_table').DataTable({
        ajax: {
            "url": "/employee-report",
            "dataSrc": "data"
        },
        columns: [
            { data: null },
            { data: 'userName' },
            { "render": function ( data, type, row, meta ){ var grossSales = parseFloat(row.grossSales); return grossSales.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var discounts = parseFloat(row.discounts); return discounts.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var netSales = parseFloat(row.netSales); return netSales.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var averageSale = parseFloat(row.averageSale); return averageSale.toFixed(2);} },
	        { data: 'orderCount' },
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
	    	var PageInfo = $('#product_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});

$('#ReportForm').on('submit',  function (e) {

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	e.preventDefault();

	// $('.chartp').hide();

	$("#product_table td").remove();

	let formData = new FormData($('#ReportForm')[0]);

	$.ajax({
		type: "POST",
		url: "/employee-reports",
		data: formData,
		contentType: false,
		processData: false,
		success: function(response){
			if(response.message == 'No Data available'){
				$.notify(response.message);
			}else{
				
				onChangeDataTable(response.data)
					
			}
			
		}
	});
});



function onChangeDataTable(json){

	$('#product_table').DataTable().clear().destroy()

	var t = $('#product_table').DataTable({
     
        data : json,
        columns: [
            { data: null },
            { data: 'userName' },
            { "render": function ( data, type, row, meta ){ var grossSales = parseFloat(row.grossSales); return grossSales.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var discounts = parseFloat(row.discounts); return discounts.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var netSales = parseFloat(row.netSales); return netSales.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var averageSale = parseFloat(row.averageSale); return averageSale.toFixed(2);} },
	        { data: 'orderCount' },
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
	    	var PageInfo = $('#product_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}

function resetButton(){
    // $("#product_table").find("tr:gt(0)").remove(); 
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}


