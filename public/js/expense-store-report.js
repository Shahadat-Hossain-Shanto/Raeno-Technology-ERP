$('#ExpenseStoreReport').on('submit',  function (e) {
	e.preventDefault();
	// $("#expense_table").empty();-

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	let formData = new FormData($('#ExpenseStoreReport')[0]);
	
	if($('#store').val() == 'option_select'){
		$('#gen_btn').notify("Please select store.", {className: 'error', position: 'bottom left'})
	}else{
		$.ajax({
			type: "POST",
			url: "/expense-store-reports",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				// $("#expense_table").find("tr:gt(0)").remove();
				
				// alert(response.message)
				// console.log(response.totalSalary)

				var zero = parseFloat(0)
				$('#totalExpense').text(zero.toFixed(2))
				$('#totalSalary').text(zero.toFixed(2))
				$('#totalPurchase').text(zero.toFixed(2))
				$('#totalRent').text(zero.toFixed(2))
				$('#totalBill').text(zero.toFixed(2))
				$('#totalOther').text(zero.toFixed(2))


				if(response.totalExpenseAmount != null){
					var totalExpenseAmount = parseFloat(response.totalExpenseAmount)
					$('#totalExpense').text(totalExpenseAmount.toFixed(2))
				}

				if(response.totalSalary != null){
					var totalSalary = parseFloat(response.totalSalary)
					$('#totalSalary').text(totalSalary.toFixed(2))
				}

				if(response.totalPurchase != null){
					var totalPurchase = parseFloat(response.totalPurchase)
					$('#totalPurchase').text(totalPurchase.toFixed(2))
				}

				if(response.totalRent != null){
					var totalRent = parseFloat(response.totalRent)
					$('#totalRent').text(totalRent.toFixed(2))
				}

				if(response.totalBill != null){
					var totalBill = parseFloat(response.totalBill)
					$('#totalBill').text(totalBill.toFixed(2))
				}

				if(response.totalOther != null){
					var totalOther = parseFloat(response.totalOther)
					$('#totalOther').text(totalOther.toFixed(2))
				}


				
				if(response.data == null){
					$('#gen_btn').notify("No Data Found.", {className: 'error', position: 'bottom left'})
				}else{
					onChangeDataTable(response.data)
				}
			}
		})
	}
	
})

$(document).ready( function () {
	$('#expense_table').on('click', '.show_img', function(){

		$('#ExpenseImageModal').modal('show');
		
		var image = $(this).data("value");
		$('#expenseimage').attr("src", "../uploads/expenses/"+image);

	});
});

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

function onChangeDataTable(json){

	$('#expense_table').DataTable().clear().destroy()

	var t = $('#expense_table').DataTable({
        data : json,
        columns: [
          	{ data: null },
            { data: 'expense_type' },
            { "render": function ( data, type, row, meta ){ var amount = parseFloat(row.amount); return amount.toFixed(2);} },
            { data: 'store_name' },
            { data: 'expense_date' },
            { "render": function ( data, type, row, meta ){ 
            		if(row.note == null){
						var note = "N/A"
					}else{
						var note = row.note
					}
            		return note
	            } 
	        },
	        { data: 'submitted_by' },
	        { "render": function ( data, type, row, meta ){ 
            		if(row.image == null){
						image = "default.jpg"
						noimage= "No image."
					}else{
						image = row.image
						noimage = row.image
					}
            		return '<a type="button" class="show_img" href="javascript:void(0)" data-value="'+image+'">'+noimage+'</a>'
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
	    	var PageInfo = $('#expense_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}

