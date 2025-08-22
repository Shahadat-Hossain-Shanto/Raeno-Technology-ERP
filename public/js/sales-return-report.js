$(document).ready(function () {
    var t = $('#return_table').DataTable({
        ajax: {
            "url": "/sales-return-report",
            "dataSrc": "data"
        },
        columns: [
            { data: null },
            { data: 'invoice_no' },
            { data: 'return_number' },
            { "render": function ( data, type, row, meta ){ 
            	if(row.name == null){
					var name = "Walking Customer"
				}else{
					var name = row.name
				}
            	return name;
	            } 
	        },
	        { "render": function ( data, type, row, meta ){ 
            	if(row.mobile == null){
					var mobile = "N/A"
				}else{
					var mobile = row.mobile
				}
            	return mobile;
	            } 
	        },
	        { data: 'product_name' },
	        { data: 'return_qty' },
            { "render": function ( data, type, row, meta ){ var total_deduction = parseFloat(row.total_deduction); return total_deduction.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var total_tax_return = parseFloat(row.total_tax_return); return total_tax_return.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var net_return = parseFloat(row.net_return); return net_return.toFixed(2);} },
	        { "render": function ( data, type, row, meta ){ 
            	if(row.note == null){
					var note = "N/A"
				}else{
					var note = row.note
				}
            	return note;
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
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#return_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});




$('#SalesReturnReport').on('submit',  function (e) {
	e.preventDefault();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	let formData = new FormData($('#SalesReturnReport')[0]);

	$.ajax({
		type: "POST",
		url: "/sales-return-reports",
		data: formData,
		contentType: false,
		processData: false,
		success: function(response){
			onChangeDataTable(response.data)
		}
	})
})

function onChangeDataTable(json){

	$('#return_table').DataTable().clear().destroy()

	var t = $('#return_table').DataTable({
     
        data : json,
        columns: [
            { data: null },
            { data: 'invoice_no' },
            { data: 'return_number' },
            { "render": function ( data, type, row, meta ){ 
            	if(row.name == null){
					var name = "Walking Customer"
				}else{
					var name = row.name
				}
            	return name;
	            } 
	        },
	        { "render": function ( data, type, row, meta ){ 
            	if(row.mobile == null){
					var mobile = "N/A"
				}else{
					var mobile = row.mobile
				}
            	return mobile;
	            } 
	        },
	        { data: 'product_name' },
	        { data: 'return_qty' },
            { "render": function ( data, type, row, meta ){ var total_deduction = parseFloat(row.total_deduction); return total_deduction.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var total_tax_return = parseFloat(row.total_tax_return); return total_tax_return.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var net_return = parseFloat(row.net_return); return net_return.toFixed(2);} },
	        { "render": function ( data, type, row, meta ){ 
            	if(row.note == null){
					var note = "N/A"
				}else{
					var note = row.note
				}
            	return note;
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
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#return_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}


function resetButton(){
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}