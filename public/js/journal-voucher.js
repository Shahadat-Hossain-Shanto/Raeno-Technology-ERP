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

$(document).on('change', '#accounthead', function (e) {
	e.preventDefault();

	var accountHeadCode = $(this).val();
	// alert(debitHeadCode)
	$('#accountheadcode').val(accountHeadCode)
})

function resetButton(){
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
	$("#journal_table").find("tr:gt(0)").remove();
}

function addX() {
	this.event.preventDefault();

	// alert('debit added')
	var accHeadName		=	$("#accounthead option:selected").text();
	var accHeadNameVal	=	$("#accounthead option:selected").val();
	var accHeadCode   	=   $("#accountheadcode").val();

	var debitAmount  	=   $("#debitamount").val();
    var creditAmount  	=   $("#creditamount").val();



    if(accHeadNameVal != 'option_select' && accHeadCode.length != 0 && (debitAmount.length != 0 || creditAmount.length != 0) && (debitAmount != 0 || creditAmount != 0)){
    	// alert(debitHeadName)

    	if(debitAmount.length == 0 && debitAmount == 0){
			if(creditAmount.length == 0 && creditAmount == 0){
				$.notify("Debit or Credit must be filled.", {className: 'error', position: 'bottom right'});
			}else{
				debitAmount = 0

				if(debitAmount >= 0 && creditAmount >= 0){
		    		$("#journal_table tbody").append(
					"<tr>" +
						"<td>" + accHeadName + "</td>" +
						"<td>" + accHeadCode + "</td>" +
						"<td>" + debitAmount + "</td>" +
						"<td>" + creditAmount + "</td>" +
						"<td>" +
							"<button type='button' class='delete-btn btn btn-outline-danger btn-sm'><i class='fas fa-trash'></button>" +
						"</td>" +
					"</tr>");

			    	totalJournalTable()

			    	$("#accounthead").val('option_select');
					$("#accounthead").selectpicker("refresh");
					$("#accountheadcode").val("");
					$("#creditamount").val("");
					$("#debitamount").val("");
		    	}else{
		    		$.notify("Please enter valid amount.", {className: 'error', position: 'bottom right'});
		    	}
			}
		}else{
			if(creditAmount.length != 0 && creditAmount != 0){
				$.notify("Please select Debit or Credit at a time.", {className: 'error', position: 'bottom right'});
			}else{
				creditAmount = 0
				if(debitAmount >= 0 && creditAmount >= 0){
		    		$("#journal_table tbody").append(
					"<tr>" +
						"<td>" + accHeadName + "</td>" +
						"<td>" + accHeadCode + "</td>" +
						"<td>" + debitAmount + "</td>" +
						"<td>" + creditAmount + "</td>" +
						"<td>" +
							"<button type='button' class='delete-btn btn btn-outline-danger btn-sm'><i class='fas fa-trash'></button>" +
						"</td>" +
					"</tr>");

			    	totalJournalTable()

			    	$("#accounthead").val('option_select');
					$("#accounthead").selectpicker("refresh");
					$("#accountheadcode").val("");
					$("#creditamount").val("");
					$("#debitamount").val("");
		    	}else{
		    		$.notify("Please enter valid amount.", {className: 'error', position: 'bottom right'});
		    	}
			}
		}
    }else{
    	$.notify("Please fill up all the fields.", {className: 'error', position: 'bottom right'});
    }
}

function totalJournalTable(){
	this.event.preventDefault();
	var totalDebit = 0;
	$('#journal_table').find('> tbody > tr').each(function () {
		var debitAmountX = parseFloat($(this).find("td:eq(2)").text());
		totalDebit = totalDebit + debitAmountX;

	});

	var totalCredit = 0;
	$('#journal_table').find('> tbody > tr').each(function () {
		var creditAmountX = parseFloat($(this).find("td:eq(3)").text());
		totalCredit = totalCredit + creditAmountX;

	});

	$("#journal_table tfoot").empty()

	$("#journal_table tfoot").append(
	"<tr>" +
		"<td></td>" +
		"<td><b>Total </b></td>" +
		"<td>" + totalDebit + "</td>" +
		"<td>" + totalCredit + "</td>" +
	"</tr>");

}

$("#journal_table").on('click', '.delete-btn', function () {
    $(this).closest('tr').remove();

    var rowCount = $('#journal_table tr').length;
    if(rowCount > 2){
    	totalJournalTable()
    	// alert(rowCount)
    }else{
    	// $('#debit_table tfoot').empty();
    	// alert('rowCount')
    	$("#journal_table").find("tr:gt(0)").remove();
    }

});

function processData(){

	this.event.preventDefault();


	var totalDebit = 0;
	$('#journal_table').find('> tbody > tr').each(function () {
		var debitAmountX = parseFloat($(this).find("td:eq(2)").text());
		totalDebit = totalDebit + debitAmountX;
	});

	var totalCredit = 0;
	$('#journal_table').find('> tbody > tr').each(function () {
		var creditAmountX = parseFloat($(this).find("td:eq(3)").text());
		totalCredit = totalCredit + creditAmountX;
	});


	if($('#transactionid').val().length != 0 && $('#transactiondate').val().length != 0){

		var rowCount = $('#journal_table tr').length;

		if(rowCount > 1){

			if(totalDebit == totalCredit){

				let purchaseVoucher= {};

				purchaseVoucher["transactionId"] 		= $("#transactionid").val();
				// purchaseVoucher["poNumber"] 			= $("#ordernumber").val();
				purchaseVoucher["transactionDate"] 		= $("#transactiondate").val();
				// purchaseVoucher["supplier"] 			= $("#supplier").val();
				purchaseVoucher["referenceNote"] 		= $("#referencenote").val();
				purchaseVoucher["totalDebitAmount"] 	= totalDebit;
                purchaseVoucher["totalCreditAmount"] = totalCredit;
				purchaseVoucher['voucher'] = 'Adjustment Voucher';

				var journalTable = $('#journal_table');
				var voucherHeads=[];

				$(journalTable).find('> tbody > tr').each(function () {
			    	let accVoucherHead= {};
			    	accVoucherHead["headName"]	= $(this).find("td:eq(0)").text();
			    	accVoucherHead["headCode"]	= $(this).find("td:eq(1)").text();
			    	accVoucherHead["debitAmount"]	= $(this).find("td:eq(2)").text();
			    	accVoucherHead["creditAmount"]	= $(this).find("td:eq(3)").text();
			    	// accVoucherHead["type"]		= "debit";
			    	voucherHeads.push(accVoucherHead);

			    });



			    purchaseVoucher["voucherHeads"] 	= voucherHeads;

			    submitToServer(purchaseVoucher);

			}else{
				$.notify("Debit and credit does not match! Please check your entry.", {className: 'error', position: 'bottom right'});
			}
		}
		// else if(rowCount > 1){

		// 	let purchaseVoucher= {};

		// 	purchaseVoucher["transactionId"] 		= $("#transactionid").val();
		// 	// purchaseVoucher["poNumber"] 			= $("#ordernumber").val();
		// 	purchaseVoucher["transactionDate"] 		= $("#transactiondate").val();
		// 	// purchaseVoucher["supplier"] 			= $("#supplier").val();
		// 	purchaseVoucher["referenceNote"] 		= $("#referencenote").val();
		// 	purchaseVoucher["totalDebitAmount"] 	= totalDebit;
		// 	purchaseVoucher["totalCreditAmount"] 	= totalCredit;

		// 	var journalTable = $('#journal_table');
		// 	var voucherHeads=[];

		// 	$(journalTable).find('> tbody > tr').each(function () {
		//     	let accVoucherHead= {};
		//     	accVoucherHead["headName"]	= $(this).find("td:eq(0)").text();
		//     	accVoucherHead["headCode"]	= $(this).find("td:eq(1)").text();
		//     	accVoucherHead["debitAmount"]	= $(this).find("td:eq(2)").text();
		//     	accVoucherHead["creditAmount"]	= $(this).find("td:eq(3)").text();
		//     	// accVoucherHead["type"]		= "debit";
		//     	voucherHeads.push(accVoucherHead);

		//     });



		//     purchaseVoucher["voucherHeads"] 	= voucherHeads;

		//     submitToServer(purchaseVoucher);
		// }
		else{
			$.notify("Please enter data in journal table.", {className: 'error', position: 'bottom right'});
		}
	}else{
			// alert('here')
			$.notify("Please fill up all the fields.", {className: 'error', position: 'bottom right'});
	}
}

function submitToServer(jsonData){

		// alert('Success')
	    // console.log(jsonData)
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
		        url: "/journal-voucher-create",
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
