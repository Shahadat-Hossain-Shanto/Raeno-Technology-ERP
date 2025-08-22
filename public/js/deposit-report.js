fetchDeposit()
function fetchDeposit(){
	$.ajax({
		type: "GET",
		url: "/deposit-report",
		dataType:"json",
		success: function(response){
			if(response.totalSale != null){
				var totalSale = parseFloat(response.totalSale)
				$('#totalSale').text(totalSale.toFixed(2))
			}
			if(response.totalDeposit != null){
				var totalDeposit = parseFloat(response.totalDeposit)
				$('#totalDeposit').text(totalDeposit.toFixed(2))
			}
			if(response.totalDue != null){
				var totalDue = parseFloat(response.totalDue)
				$('#totalDue').text(totalDue.toFixed(2))
			}
			onChangeDataTable(response.data)
		}
	})
}

function onChangeDataTable(json){

	$('#deposit_table').DataTable().clear().destroy()

	var t = $('#deposit_table').DataTable({
     
        data : json,
        columns: [
            { data: null },
            { "render": function ( data, type, row, meta ){ 
            	
            	return '<td><span class="badge badge-primary"><button id="depositDate" value="'+row.deposit_date+'">'+row.deposit_date+'</button></span></td>';
	            } 
	        },
            { "render": function ( data, type, row, meta ){ var deposit = parseFloat(row.deposit); return deposit.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var due = parseFloat(row.due); return due.toFixed(2);} },
            
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
	    	var PageInfo = $('#deposit_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}

$(document).on('click', '#depositDate', function (e) {
	e.preventDefault();

	$('#deposit_table_details').show()

	var depositDate = $(this).val();
	$('#detailDate').text("")
	$('#detailDate').text(depositDate);
	
	// alert(depositDate)
	$.ajax({
		type: "GET",
		url: "/deposit-report-in-details/"+depositDate,
		dataType:"json",
		success: function(response){
			
			onChangeDataTableDetails(response.data)

			
		}
	})
})

function onChangeDataTableDetails(json){

	$('#deposit_table_details').DataTable().clear().destroy()

	var t = $('#deposit_table_details').DataTable({
     
        data : json,
        columns: [
            { data: null },
            { "render": function ( data, type, row, meta ){ 
		        	if(row.supplier_id == 0 && row.client_id != 0){
						name = row.clientName
					}else if(row.client_id == 0 && row.supplier_id != 0){
						name = row.supplierName
					}else if(row.supplier_id == 0 && row.client_id == 0){
						name = "Payment"
					}
		        	return name;
	            } 
	        },
            { "render": function ( data, type, row, meta ){ var deposit = parseFloat(row.deposit); return deposit.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var due = parseFloat(row.due); return due.toFixed(2);} },
            
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
	    	var PageInfo = $('#deposit_table_details').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}

$('#DepositReport').on('submit',  function (e) {
	e.preventDefault();

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	let formData = new FormData($('#DepositReport')[0]);
	

	$.ajax({
		type: "POST",
		url: "/deposit-reports",
		data: formData,
		contentType: false,
		processData: false,
		success: function(response){
			// $("#expense_table").find("tr:gt(0)").remove();
			onChangeDataTable(response.data)
		}
	})
})


function resetButton(){
	// dataTableX()
	// fetchExpense()
    // $("#expense_table").find("tr:gt(0)").remove(); 
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}