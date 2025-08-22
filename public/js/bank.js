$(document).ready(function () {
	//CREATE BANK
	$(document).on('submit', '#AddBankForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddBankForm')[0]);

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
			url: "/bank-create",
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
             		$(location).attr('href','/bank-list');

                }else{
                	// console.log(response.error)
                	$('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
			}
		});
	});

	function printErrorMsg (message) {
        
            $('#wrongbankname').empty();
            $('#wrongaccountname').empty();
            $('#wrongaccountnumber').empty();
            $('#wrongaccounthead').empty();
            $('#wrongstatus').empty();

			if(message.bankname == null){
				bankname = ""
			}else{
				bankname = message.bankname[0]
			}

			if(message.accountname == null){
				accountname = ""
			}else{
				accountname = message.accountname[0]
			}

			if(message.accountnumber == null){
				accountnumber = ""
			}else{
				accountnumber = message.accountnumber[0]
			}

			if(message.accounthead == null){
				accounthead = ""
			}else{
				accounthead = message.accounthead[0]
			}

			if(message.status == null){
				status = ""
			}else{
				status = message.status[0]
			}

            $('#wrongbankname').append('<span id="">'+bankname+'</span>');
            $('#wrongaccountname').append('<span id="">'+accountname+'</span>');
            $('#wrongaccountnumber').append('<span id="">'+accountnumber+'</span>');
            $('#wrongaccounthead').append('<span id="">'+accounthead+'</span>');
            $('#wrongstatus').append('<span id="">'+status+'</span>');
        // });
    }
});

//BANK LIST

// fetchBank();
// function fetchBank(){

// 	// var subscriberId = $('#subscriberid').val();

// 	$.ajax({
// 		type: "GET",
// 		url: "/bank-list-data",
// 		dataType:"json",
// 		success: function(response){
// 			// console.log(response);
// 			// $('tbody').html("");
// 			$.each(response.bank, function(key, item) {


// 				if(item.branch == null){
// 					var branch = "N/A"
// 				}else{
// 					var branch = item.branch
// 				}

// 				if(item.sign_cheque_image == null){
// 					var sign_cheque_image = "default.jpg"
// 				}else{
// 					var sign_cheque_image = item.sign_cheque_image
// 				}

// 				$('tbody').append('\
// 				<tr>\
// 					<td></td>\
// 					<td class="hidden">'+item.id+'</td>\
// 					<td>'+item.bank_name+'</td>\
// 					<td>'+item.account_name+'</td>\
// 					<td>'+item.account_number+'</td>\
// 					<td>'+branch+'</td>\
// 					<td><img src="uploads/banks/'+sign_cheque_image+'" width="50" height="50" alt="image" class=""></td>\
// 					<td>'+item.balance+'</td>\
// 					<td>\
//         				<button type="button" value="'+item.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i>\
//                     	</button>\
//                     	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+item.id+'"><i class="fas fa-trash"></i></a>\
//         			</td>\
//         		</tr>');
// 			})	
// 		}
// 	});
// }

// DATA TABLE
$(document).ready(function () {
    var t = $('#bank_table').DataTable({
        ajax: {
            "url": "/bank-list-data",
            "dataSrc": "bank",
        },
        columns: [
          	{data: null},

            { data: 'bank_name' },
            { data: 'account_name' },
            { data: 'account_number' },
            { "render": function ( data, type, row, meta ){ 
            		if(row.branch == null){
						var branch = "N/A"
					}else{
						var branch = row.branch
					}
            		return branch
	            } 
	        },
	        { "render": function ( data, type, row, meta ){ 
            		if(row.sign_cheque_image == null){
						var sign_cheque_image = "default.jpg"
					}else{
						var sign_cheque_image = row.sign_cheque_image
					}
            		return '<img src="uploads/banks/'+sign_cheque_image+'" width="50" height="50" alt="image" class="">'
	            } 
	        },
            { data: 'balance' },
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
	    	var PageInfo = $('#bank_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();


});

//EDIT BANK
$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var bankId = $(this).val();
	// alert(bankId);
	$('#EDITBankMODAL').modal('show');
		



		$.ajax({
		type: "GET",
		url: "/bank-edit/"+bankId,
		success: function(response){
			if (response.status == 200) {

				if(response.bank.branch == null){
					var branch = "N/A"
				}else{
					var branch = response.bank.branch
				}

				if(response.bank.sign_cheque_image == null){
					var sign_cheque_image = "default.jpg"
				}else{
					var sign_cheque_image = response.bank.sign_cheque_image
				}

				$('#edit_bankname').val(response.bank.bank_name);
				$('#edit_accountname').val(response.bank.account_name);
				$('#edit_accountnumber').val(response.bank.account_number);
				$('#edit_branch').val(branch);
				$('#edit_balance').val(response.bank.balance);
				$('#edit_status').val(response.bank.status);
				$('#edit_bankimage').attr("src", "../uploads/banks/"+sign_cheque_image);
				$('#bankid').val(bankId);
			}
		}
	});
});

//UPDATE BANK
$(document).on('submit', '#UPDATEBankFORM', function (e)
{
	e.preventDefault();

	var id = $('#bankid').val(); 

	let EditFormData = new FormData($('#UPDATEBankFORM')[0]);

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
		url: "/bank-edit/"+id,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function(response){
			
			if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#EDITBankMODAL').modal('hide');
                // $.notify(response.message, 'success')
                $(location).attr('href','/bank-list');
            }else{
            	// console.log(response.error)
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $('#edit_wrongbankname').empty();
				$('#edit_wrongaccountname').empty();
				$('#edit_wrongaccountnumber').empty();
				$('#edit_wrongstatus').empty();

				if(response.error.bankname == null){
					bankname = ""
				}else{
					bankname = response.error.bankname[0]
				}

				if(response.error.accountname == null){
					accountname = ""
				}else{
					accountname = response.error.accountname[0]
				}

				if(response.error.accountnumber == null){
					accountnumber = ""
				}else{
					accountnumber = response.error.accountnumber[0]
				}

				if(response.error.status == null){
					status = ""
				}else{
					status = response.error.status[0]
				}
				

                $('#edit_wrongbankname').append('<span id="">'+bankname+'</span>');
                $('#edit_wrongaccountname').append('<span id="">'+accountname+'</span>');
                $('#edit_wrongaccountnumber').append('<span id="">'+accountnumber+'</span>');
                $('#edit_wrongstatus').append('<span id="">'+status+'</span>');
                
            }
		}
	});
});

//Delete BANK
$(document).ready( function () {
	$('#bank_table').on('click', '.delete_btn', function(){

		var bankId = $(this).data("value");

		$('#bankid').val(bankId);
		$('#DELETEBankFORM').attr('action', '/bank-delete/'+bankId);
		$('#DELETEBankMODAL').modal('show');

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

// //DATA TABLE
// $(document).ready( function () {
// 	$('#bank_table').DataTable({
// 	    pageLength : 10,
// 	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
// 	    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
// 		    //debugger;
// 		    var index = iDisplayIndexFull + 1;
// 		    $("td:first", nRow).html(index);
// 		    return nRow;
// 	  	},
// 	});
// });

