$(document).ready(function () {

	$(document).on('submit', '#AddExpenseCategoryForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddExpenseCategoryForm')[0]);

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
			url: "/expense-category-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);	
				if($.isEmptyObject(response.error)){
            		$('#wrongexpensecategoryname').empty();
             		$(location).attr('href','/expense-category-list');

                }else{
                	// console.log(response.error)
                	$('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
			}
		});
	});

	function printErrorMsg (message) {
        // $(".print-error-msg").find("ul").html('');
        // $(".print-error-msg").css('display','block');

        // $.each( message, function( key, item ) {
            // $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            $('#wrongexpensecategoryname').empty();

			if(message.expensecategoryname == null){
				expensecategoryname = ""
			}else{
				expensecategoryname = message.expensecategoryname[0]
			}
			

            $('#wrongexpensecategoryname').append('<span id="">'+expensecategoryname+'</span>');
        // });
    }
});

fetchExpenseTypes();
function fetchExpenseTypes(){

	// var subscriberId = $('#subscriberid').val();

	$.ajax({
		type: "GET",
		url: "/expense-category-list-data",
		dataType:"json",
		success: function(response){
			// console.log(response);
			// $('tbody').html("");
			$.each(response.data, function(key, item) {

				if(item.note == null){
					note = "No notes."
				}else{
					note = item.note
				}

				$('tbody').append('\
				<tr>\
					<td></td>\
					<td class="hidden">'+item.id+'</td>\
					<td>'+item.expense_type_name+'</td>\
					<td>'+note+'</td>\
					<td>\
        				<button type="button" value="'+item.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i>\
                    	</button>\
                    	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+item.id+'"><i class="fas fa-trash"></i></a>\
        			</td>\
        		</tr>');
			})	
		}
	});
}

//EDIT ExpenseType
$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var expensecategoryid = $(this).val();
	// alert(expensecategoryid);
	$('#EDITExpenseCategoryMODAL').modal('show');
		
		$.ajax({
		type: "GET",
		url: "/expense-category-edit/"+expensecategoryid,
		success: function(response){
			if (response.status == 200) {
				$('#edit_expensecategory').val(response.data.expense_type_name);
				$('#edit_note').val(response.data.note);
				$('#expensecategoryid').val(expensecategoryid);
			}
		}
	});
});

//UPDATE ExpenseType
$(document).on('submit', '#UPDATEExpenseCategoryFORM', function (e)
{
	e.preventDefault();

	var id = $('#expensecategoryid').val(); 

	let EditFormData = new FormData($('#UPDATEExpenseCategoryFORM')[0]);

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
		url: "/expense-category-edit/"+id,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function(response){
			
			if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#EDITExpenseCategoryMODAL').modal('hide');
                // $.notify(response.message, 'success')
                $(location).attr('href','/expense-category-list');
            }else{
            	// console.log(response.error)
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $('#edit_wrongexpensecategory').empty();

				if(response.error.expensecategory == null){
					expensecategory = ""
				}else{
					expensecategory = response.error.expensecategory[0]
				}
				
				

                $('#edit_wrongexpensecategory').append('<span id="">'+expensecategory+'</span>');
            }
		}
	});
});

//Delete ExpenseType
$(document).ready( function () {
	$('#expense_category_table').on('click', '.delete_btn', function(){

		var expensecategoryId = $(this).data("value");

		$('#expensecategoryid').val(expensecategoryId);
		$('#DELETEExpenseCategoryFORM').attr('action', '/expense-category-delete/'+expensecategoryId);
		$('#DELETEExpenseCategoryMODAL').modal('show');

	});
});


//DATA TABLE
$(document).ready( function () {
	$('#expense_category_table').DataTable({
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
		    //debugger;
		    var index = iDisplayIndexFull + 1;
		    $("td:first", nRow).html(index);
		    return nRow;
	  	},
	});
});