$(document).ready(function () {
	//CREATE Expense
	$(document).on('submit', '#AddExpenseForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddExpenseForm')[0]);

		$.ajax({
			ajaxStart: $('body').loadingModal({
			  position: 'auto',
			  text: 'Please Wait',
			  color: '#fff',
			  opacity: '0.7',
			  backgroundColor: 'rgb(0,0,0)',
			  animation: 'foldingCube'
			}),
			type: "POST",
			url: "/expense-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);	
				if($.isEmptyObject(response.error)){
                    
                    // alert(response.message)
             		$(location).attr('href','/expense-list');

                }else{
                	// console.log(response.error)
                	$('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
			}
		});
	});

	function printErrorMsg (message) {

            $('#wrongexpensecategory').empty();
            $('#wrongexpenseamount').empty();
            $('#wrongstore').empty();
            $('#wrongexpensedate').empty();

			if(message.expensecategory == null){
				expensecategory = ""
			}else{
				expensecategory = message.expensecategory[0]
			}

			if(message.expenseamount == null){
				expenseamount = ""
			}else{
				expenseamount = message.expenseamount[0]
			}

			if(message.store == null){
				store = ""
			}else{
				store = message.store[0]
			}

			if(message.expensedate == null){
				expensedate = ""
			}else{
				expensedate = message.expensedate[0]
			}

            $('#wrongexpensecategory').append('<span id="">'+expensecategory+'</span>');
            $('#wrongexpenseamount').append('<span id="">'+expenseamount+'</span>');
            $('#wrongstore').append('<span id="">'+store+'</span>');
            $('#wrongexpensedate').append('<span id="">'+expensedate+'</span>');
        // });
    }
});


$(document).ready(function () {
    var t = $('#expense_table').DataTable({
        ajax: {
            "url": "/expense-list-data",
            "dataSrc": "data",
        },
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
	        { "render": function ( data, type, row, meta ){ 
            		if(row.image == null){
						var image = "default.jpg"
					}else{
						var image = row.image
					}
            		return '<img src="uploads/expenses/'+image+'" width="50px" height="50px" alt="image" class="rounded-circle">'
	            } 
	        },
            { "render": function ( data, type, row, meta ){ 
            		
            		return '<button type="button" value="'+row.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button>\
                    	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+row.id+'"><i class="fas fa-trash"></i></a>'
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
        order: [[1, 'desc']],
        pageLength : 10,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });


    t.on('order.dt search.dt', function () {

	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#expense_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();


});



//EDIT Expense
$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var expenseId = $(this).val();
	// alert(batchId);
	$('#EDITExpenseMODAL').modal('show');
		
		$.ajax({
		type: "GET",
		url: "/expense-edit/"+expenseId,
		success: function(response){
			if (response.status == 200) {
				$('#edit_expensecategory').val(response.data.expense_type).change();;
				$('#edit_expenseamount').val(response.data.amount);
				$('#edit_store').val(response.data.store_id).change();;
				$('#edit_expensedate').val(response.data.expense_date);
				$('#edit_note').val(response.data.note);
				if(response.data.image == null){
					$('#edit_image').attr("src", "../uploads/expenses/default.jpg");
				}else{
					$('#edit_image').attr("src", "../uploads/expenses/"+response.data.image);
				}
				$('#expenseid').val(expenseId);
			}
		}
	});
});

//UPDATE Expense
$(document).on('submit', '#UPDATEExpenseFORM', function (e)
{
	e.preventDefault();

	var id = $('#expenseid').val(); 

	let EditFormData = new FormData($('#UPDATEExpenseFORM')[0]);

	EditFormData.append('_method', 'PUT');

	$.ajax({
		ajaxStart: $('body').loadingModal({
			  position: 'auto',
			  text: 'Please Wait',
			  color: '#fff',
			  opacity: '0.7',
			  backgroundColor: 'rgb(0,0,0)',
			  animation: 'foldingCube'
			}),
		type: "POST",
		url: "/expense-edit/"+id,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function(response){
			
			if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#EDITExpenseMODAL').modal('hide');
                // $.notify(response.message, 'success')
                $(location).attr('href','/expense-list');
            }else{
            	// console.log(response.error)
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $('#edit_wrongexpensecategory').empty();
				$('#edit_wrongexpenseamount').empty();
				$('#edit_wrongstore').empty();
				$('#edit_wrongexpensedate').empty();

				if(response.error.expensecategory == null){
					expensecategory = ""
				}else{
					expensecategory = response.error.expensecategory[0]
				}

				if(response.error.expenseamount == null){
					expenseamount = ""
				}else{
					expenseamount = response.error.expenseamount[0]
				}

				if(response.error.store == null){
					store = ""
				}else{
					store = response.error.store[0]
				}

				if(response.error.expensedate == null){
					expensedate = ""
				}else{
					expensedate = response.error.expensedate[0]
				}
				
				

                $('#edit_wrongexpensecategory').append('<span id="">'+expensecategory+'</span>');
                $('#edit_wrongexpenseamount').append('<span id="">'+expenseamount+'</span>');
                $('#edit_wrongstore').append('<span id="">'+store+'</span>');
                $('#edit_wrongexpensedate').append('<span id="">'+expensedate+'</span>');
                
            }
		}
	});
});

//Delete Expense
$(document).ready( function () {
	$('#expense_table').on('click', '.delete_btn', function(){

		var expenseId = $(this).data("value");

		$('#expenseid').val(expenseId);
		$('#DELETEExpenseFORM').attr('action', '/expense-delete/'+expenseId);
		$('#DELETEExpenseMODAL').modal('show');

	});
});
