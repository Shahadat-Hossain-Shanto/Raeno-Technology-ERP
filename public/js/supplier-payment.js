function resetButton(){

	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
	$("#debit_table").find("tr:gt(0)").remove();
	$("#credit_table").find("tr:gt(0)").remove();

}

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

$(document).on('change', '#debitheadname', function (e) {
	e.preventDefault();

    // var supplierName = $("#debitheadname option:selected").text();
    // alert(supplierName)
    // $('#supplier').val(supplierName)

	var debitHeadCode = $(this).val();
	$('#debitheadcode').val(debitHeadCode)
})

$(document).on('change', '#creditheadname', function (e) {
	e.preventDefault();

	var creditHeadCode = $(this).val();
	// alert(creditHeadCode)
	$('#creditheadcode').val(creditHeadCode)
})

function addDebitX() {
	this.event.preventDefault();

	// alert('debit added')
	var debitHeadName		=	$("#debitheadname option:selected").text();
	var debitHeadNameVal	=	$("#debitheadname option:selected").val();
	var debitHeadCode   	=   $("#debitheadcode").val();
    var debitAmount  		=   $("#debitamount").val();

    if(debitHeadNameVal != 'option_select' && debitHeadCode.length != 0 && debitAmount.length != 0){
    	// alert(debitHeadName)
    	if(debitAmount > 0){
    		$("#debit_table tbody").append(
			"<tr>" +
				"<td>" + debitHeadName + "</td>" +
				"<td>" + debitHeadCode + "</td>" +
				"<td>" + debitAmount + "</td>" +
				"<td>" +
					"<button type='button' class='delete-btn btn btn-outline-danger btn-sm'><i class='fas fa-trash'></button>" +
				"</td>" +
			"</tr>");

	    	totalDebitTable()

	    	$("#debitheadname").val('option_select');
			$("#debitheadname").selectpicker("refresh");
			$("#debitheadcode").val("");
			$("#debitamount").val("");
    	}else{
    		$.notify("Please enter valid amount.", {className: 'error', position: 'bottom right'});
    	}


    }else{
    	$.notify("Please fill up all the fields.", {className: 'error', position: 'bottom right'});
    }
}

function totalDebitTable(){
	this.event.preventDefault();
	var totalDebit = 0;
	$('#debit_table').find('> tbody > tr').each(function () {
		var debitAmountX = parseFloat($(this).find("td:eq(2)").text());
		totalDebit = totalDebit + debitAmountX;

	});

	$("#debit_table tfoot").empty()

	$("#debit_table tfoot").append(
	"<tr>" +
		"<td></td>" +
		"<td><b>Total Debit</b></td>" +
		"<td>" + totalDebit + "</td>" +
	"</tr>");

}

function totalCreditTable(){
	this.event.preventDefault();
	var totalCredit = 0;
	$('#credit_table').find('> tbody > tr').each(function () {
		var creditAmountX = parseFloat($(this).find("td:eq(2)").text());
		totalCredit = totalCredit + creditAmountX;

	});

	$("#credit_table tfoot").empty()

	$("#credit_table tfoot").append(
	"<tr>" +
		"<td></td>" +
		"<td><b>Total Credit</b></td>" +
		"<td>" + totalCredit + "</td>" +
	"</tr>");

}


function addCreditX() {
	this.event.preventDefault();

	// alert('debit added')
	var creditHeadName		=	$("#creditheadname option:selected").text();
	var creditHeadNameVal	=	$("#creditheadname option:selected").val();
	var creditHeadCode   	=   $("#creditheadcode").val();
    var creditAmount  		=   $("#creditamount").val();

    if(creditHeadNameVal != 'option_select' && creditHeadCode.length != 0 && creditAmount.length != 0){
    	// alert(debitHeadName)
    	if(creditAmount > 0){
    		$("#credit_table tbody").append(
			"<tr>" +
				"<td>" + creditHeadName + "</td>" +
				"<td>" + creditHeadNameVal + "</td>" +
				"<td>" + creditAmount + "</td>" +
				"<td>" +
					"<button type='button' class='delete-btn-x btn btn-outline-danger btn-sm'><i class='fas fa-trash'></button>" +
				"</td>" +
			"</tr>");

	    	totalCreditTable()

	    	$("#creditheadname").val('option_select');
			$("#creditheadname").selectpicker("refresh");
			$("#creditheadcode").val("");
			$("#creditamount").val("");
    	}else{
    		$.notify("Please enter valid amount.", {className: 'error', position: 'bottom right'});
    	}


    }else{
    	$.notify("Please fill up all the fields.", {className: 'error', position: 'bottom right'});
    }
}




$("#debit_table").on('click', '.delete-btn', function () {
    $(this).closest('tr').remove();

    var rowCountDebit = $('#debit_table tr').length;
    if(rowCountDebit > 2){
    	totalDebitTable()
    }else{
    	$("#debit_table").find("tr:gt(0)").remove();
    }


});

$("#credit_table").on('click', '.delete-btn-x', function () {
    $(this).closest('tr').remove();

    var rowCountDebit = $('#credit_table tr').length;
    if(rowCountDebit > 2){
    	totalCreditTable()
    }else{
    	$("#credit_table").find("tr:gt(0)").remove();
    }
});


function processData(){

	this.event.preventDefault();


    var totalDebit = $("#debitamount").val();
	// var totalDebit = 0;
	// $('#debit_table').find('> tbody > tr').each(function () {
	// 	var debitAmountX = parseFloat($(this).find("td:eq(2)").text());
	// 	totalDebit = totalDebit + debitAmountX;

	// });
	// alert(totalDebit)

	var totalCredit = 0;
	$('#credit_table').find('> tbody > tr').each(function () {
		var creditAmountX = parseFloat($(this).find("td:eq(2)").text());
		totalCredit = totalCredit + creditAmountX;

	});
	// alert(totalCredit)

    if ($('#transactionid').val().length != 0 && $('#transactiondate').val().length != 0 && $("#debitheadname option:selected").text() != null){

		// alert($('#supplier').val())

		// var rowCountDebit = $('#debit_table tr').length;
		var rowCountCredit = $('#credit_table tr').length;

		if(rowCountCredit > 1){

			if(totalDebit == totalCredit){

				let purchaseVoucher= {};

				purchaseVoucher["transactionId"] 		= $("#transactionid").val();
				purchaseVoucher["poNumber"] 			= $("#ponumber").val();
				purchaseVoucher["transactionDate"] 		= $("#transactiondate").val();
                purchaseVoucher["supplier"] = $("#debitheadname option:selected").text();
				purchaseVoucher["referenceNote"] 		= $("#referencenote").val();
				purchaseVoucher["totalDebitAmount"] 	= totalDebit;
                purchaseVoucher["totalCreditAmount"] = totalCredit;
                purchaseVoucher["transaction_type"] = 'Supplier Payment';

				// var debitTable = $('#debit_table');
				var creditTable = $('#credit_table');
				var voucherHeads=[];
                let debitVoucherHead = {};
                debitVoucherHead["headName"] = $("#debitheadname option:selected").text();
                debitVoucherHead["headCode"] = $("#debitheadcode").val();
                debitVoucherHead["amount"] = $("#debitamount").val();
			    debitVoucherHead["type"]		= "debit";
                voucherHeads.push(debitVoucherHead);
                console.log(voucherHeads)

				// $(debitTable).find('> tbody > tr').each(function () {
			    // 	let debitVoucherHead= {};
			    // 	debitVoucherHead["headName"]	= $(this).find("td:eq(0)").text();
			    // 	debitVoucherHead["headCode"]	= $(this).find("td:eq(1)").text();
			    // 	debitVoucherHead["amount"]		= $(this).find("td:eq(2)").text();
			    // 	debitVoucherHead["type"]		= "debit";
			    // 	voucherHeads.push(debitVoucherHead);

			    // });

			    $(creditTable).find('> tbody > tr').each(function () {
			    	let creditVoucherHead= {};
			    	creditVoucherHead["headName"]	= $(this).find("td:eq(0)").text();
			    	creditVoucherHead["headCode"]	= $(this).find("td:eq(1)").text();
			    	creditVoucherHead["amount"]		= $(this).find("td:eq(2)").text();
			    	creditVoucherHead["type"]		= "credit";

			    	voucherHeads.push(creditVoucherHead);

			    });

			    purchaseVoucher["voucherHeads"] 	= voucherHeads;

			    submitToServer(purchaseVoucher);

			}else{
				$.notify("Debit and credit does not match! Please check your entry.", {className: 'error', position: 'bottom right'});
			}
		}else{
			$.notify("Please enter data in credit table.", {className: 'error', position: 'bottom right'});
		}
	}else{
			$.notify("Please fill up all the necessary fields.", {className: 'error', position: 'bottom right'});
	}
}

function submitToServer(jsonData){

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
	        url: "/supplier-payment-create",
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
