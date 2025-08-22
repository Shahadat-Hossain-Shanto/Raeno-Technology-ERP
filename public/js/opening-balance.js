$(document).ready( function() {

    var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!

	var yyyy = today.getFullYear();
	if(dd<10){
		dd='0'+dd
	}
	if(mm<10){
		mm='0'+mm
	}
	today = yyyy+'-'+mm+'-'+dd;
	// console.log(today)
	$('#transactiondate').attr('value', today);

	var time = new Date().getTime();
	var transactionId = time.toString();
	$('#transactionid').attr('value', transactionId);

})


$(document).on('change', '#transactiontypeX', function (e) {
	e.preventDefault();

	var transactionTypeX = $(this).val();
	if(transactionTypeX == 'debit'){
		$('#transactiontypeY').val('credit')
		$('#tableHeader').text('Credit Head')

	}else{
		$('#transactiontypeY').val('debit')
		$('#tableHeader').text('Debit Head')
	}

})

$(document).on('keyup', '#amountX', function(e) {
  // alert( "Handler for .keyup() called." );
  var amountX = $(this).val()
  $('#amountY').val(amountX)

})


function resetButton(){
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
	$("#opening_table").find("tr:gt(0)").remove();
}

$(document).on('change', '#accountY', function (e) {
	e.preventDefault();

	var accountHeadCode = $(this).val();
	// alert(debitHeadCode)
	$('#accountheadcode').val(accountHeadCode)
})


function processData(){

	this.event.preventDefault();


	var totalY = 0;
	$('#opening_table').find('> thead > tr').each(function () {
		var amountY = parseFloat($(this).closest("tr").find("th:eq(3) input[type='number']").val());
		totalY = parseFloat(totalY) + amountY;
		// alert(amountY)
	});



	var amountX = parseFloat($('#amountX').val())




	if($('#transactionid').val().length != 0 && $('#transactiondate').val().length != 0 &&  $('#accountX').val() != 'option_select' &&  $('#amountX').val().length != 0
			&&  $('#transactiontypeX').val() != 'option_select'){

		var rowCount = $('#opening_table tr').length;

		if(rowCount > 0){

			if(amountX == totalY){

				let purchaseVoucher= {};

				purchaseVoucher["transactionId"] 		= $("#transactionid").val();
				// purchaseVoucher["poNumber"] 			= $("#ordernumber").val();
				purchaseVoucher["transactionDate"] 		= $("#transactiondate").val();
				// purchaseVoucher["supplier"] 			= $("#supplier").val();
				purchaseVoucher["referenceNote"] 		= $("#referencenote").val();
				purchaseVoucher["totalXAmount"] 	= amountX;
                purchaseVoucher["totalYAmount"] = totalY;
                // purchaseVoucher["transaction_type"] = 'Opening Balance';
				purchaseVoucher['voucher'] = 'Opening Balance';

				var journalTable = $('#opening_table');
				var voucherHeads=[];

				let accXhead = {};
				accXhead["headName"]	= $('#accountX :selected').text();
		    	accXhead["headCode"]	= $('#accountX :selected').val();
				let accYheadName = $('#accountY :selected').text();

		    	if($('#transactiontypeX').val() == 'debit'){
		    		accXhead["type"]	= $('#transactiontypeX').val()
		    		accXhead["debitAmount"]	= $('#amountX').val()
					accXhead['transaction_type'] = 'Opening Balance ' + accYheadName;


		    	}else{
		    		accXhead["debitAmount"]	= 0
		    	}

		    	if($('#transactiontypeX').val() == 'credit'){
		    		accXhead["type"]	= $('#transactiontypeX').val()
		    		accXhead["creditAmount"]	= $('#amountX').val()
					accXhead['transaction_type'] = 'Opening Balance ' + accYheadName;


		    	}else{
		    		accXhead["creditAmount"]	= 0
		    	}

		    	voucherHeads.push(accXhead);

				$(journalTable).find('> thead > tr').each(function () {
                let accVoucherHead = {};
                accVoucherHead["headName"] = $('#accountY :selected').text();
                accVoucherHead["headCode"] = $(this).closest("tr").find("th:eq(1) input[type='text']").val();
                accVoucherHead["type"] = $(this).closest("tr").find("th:eq(2) input[type='text']").val();
            
                var type = accVoucherHead["type"];
                if (type == "debit") {
                    accVoucherHead["debitAmount"] = $(this).closest("tr").find("th:eq(3) input[type='number']").val();
                    accVoucherHead["creditAmount"] = 0;
                    // Debit row → show Credit head (X) name
                    accVoucherHead['transaction_type'] = 'Opening Balance ' + $('#accountX :selected').text();
                }
                if (type == "credit") {
                    accVoucherHead["creditAmount"] = $(this).closest("tr").find("th:eq(3) input[type='number']").val();
                    accVoucherHead["debitAmount"] = 0;
                    // Credit row → show Debit head (X) name
                    accVoucherHead['transaction_type'] = 'Opening Balance ' + $('#accountX :selected').text();
                }
            
                voucherHeads.push(accVoucherHead);
            });
            



			    purchaseVoucher["voucherHeads"] 	= voucherHeads;

			    submitToServer(purchaseVoucher);

			}else{
				$.notify("Debit and credit does not match! Please check your entry.", {className: 'error', position: 'bottom right'});
			}
		}else{
			$.notify("Please enter data in the table.", {className: 'error', position: 'bottom right'});
		}
	}else{
			// alert('here')
			$.notify("Please fill up all the fields.", {className: 'error', position: 'bottom right'});
	}
}

function submitToServer(jsonData){

		// alert('Success')
	 //    console.log(jsonData)
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
		        contentType: "application/json",
		        url: "/opening-balance-create",
		        data: JSON.stringify(jsonData),
		        dataType : "json",
		        headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
		        success: function (response) {
		        	$.notify(response.message, {className: 'success', position: 'bottom right'});
		        	// resetButton()
		        	location.reload();
		        	// console.log(response.message);
		        	// $.notify(response.message, {className: 'success', position: 'bottom right'});
		            // $(location).attr('href','/purchase-list');

		        }
		    });
}


