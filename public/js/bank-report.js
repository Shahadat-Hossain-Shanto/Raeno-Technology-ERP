

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

	var startdate = $('#startdate').val().trim();
    var enddate = $('#enddate').val().trim();
	var employee = $('#employee').val().trim();
	if (startdate && enddate && employee) {

		$.ajax({
			type: "POST",
			url: "/bank-report",
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
	}else{

	$.notify("Please fillup all the field", "error");
	}
	

});



function onChangeDataTable(json){

	$('#product_table').DataTable().clear().destroy()	
	var count=json.length;
	if(count>0){
		var account_name=$('#employee').find("option:selected").text()
		$("#opening_balance").text(json[count-1].balance);
		$("#closing_balance").text(json[0].balance);
		$("#bank_name").text(json[0].head_name);
		$("#account_name").text(account_name);
	}
	var t = $('#product_table').DataTable({
     
        data : json,
        columns: [
            { data: null },
			{ data: 'transaction_id' },
			{ data: 'transaction_type' },
			{ data: 'transaction_date' },
			{ data: 'debit' },
			{ data: 'credit' },
			{ data: 'balance' },
			
        ],
        columnDefs: [
	        {
	            searchable: true,
	            orderable: false,
	            targets: 0,
	        },
	    ],
	    order: [[3, 'asc']],
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


