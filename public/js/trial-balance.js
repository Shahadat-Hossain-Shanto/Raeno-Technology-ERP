$(document).ready(function () {
    $.ajax({
        type: "get",
        url: "/trial-balance-data",
        contentType: false,
        processData: false,
        success: function(response){
            var totalDebit = 0;
            var totalCredit = 0;
            response.data.forEach(balance => {
                totalCredit = totalCredit + parseFloat(balance.totalCredit);
                totalDebit = totalDebit + parseFloat(balance.totalDebit);
            });
            $('#debit').text(totalDebit);
            $('#credit').text(totalCredit);
            onChangeDataTable(response.data);
        }
    })

});


$('#TrialBalance').on('submit',  function (e) {
	e.preventDefault();

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	let formData = new FormData($('#TrialBalance')[0]);

	if($('#startdate').val().length != 0 || $('#enddate').val().length != 0 ){
		$.ajax({
			type: "POST",
			url: "/trial-balance-datewise",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
                var totalDebit = 0;
                var totalCredit = 0;
                response.data.forEach(balance => {
                    totalCredit = totalCredit + balance.totalCredit;
                    totalDebit = totalDebit + balance.totalDebit;
                });
                $('#debit').text(totalDebit);
                $('#credit').text(totalCredit);
				onChangeDataTable(response.data);
			}
		})
	}else{
		$.notify("Please select at lease one date.", {className: 'error', position: 'bottom right'});

	}

})

function onChangeDataTable(json){

	$('#trial_balance_table').DataTable().clear().destroy()

	var t = $('#trial_balance_table').DataTable({
        data : json,
        columns: [
          	{data: null},
            { data: 'head_name' },
            { data: 'head_code' },
            { "render": function ( data, type, row, meta ){
                var totalDebit = parseFloat(row.totalDebit);
                var totalCredit = parseFloat(row.totalCredit)
                var balance = totalDebit - totalCredit
                if (balance > 0) {
                    return parseFloat(balance).toFixed(2);
                }
                else
                    return 0.00;
            } },
            { "render": function ( data, type, row, meta ){
                var totalDebit = parseFloat(row.totalDebit);
                var totalCredit = parseFloat(row.totalCredit)
                var balance = totalDebit - totalCredit
                if (balance > 0) {
                    return 0.00;
                }
                else
                    return parseFloat(-1 * balance).toFixed(2);
                // var totalCredit = parseFloat(row.totalCredit);
                // return totalCredit.toFixed(2);
            } },
        ],
        columnDefs: [
	        {
	            searchable: true,
	            orderable: true,
	            targets: 0,
	        },
	    ],
	    order: [[1, 'asc']],
	    pageLength : 50,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: [1, 2, 3, 4],
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: [1, 2, 3, 4],
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: [1, 2, 3, 4],
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [1, 2, 3, 4],
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [1, 2, 3, 4],
                }
            },
        ],
        paging: false
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#trial_balance_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}

