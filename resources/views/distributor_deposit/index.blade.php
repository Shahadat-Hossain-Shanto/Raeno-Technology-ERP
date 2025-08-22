@extends('layouts.master')
@section('title', 'Distributor Deposit')

@section('content')
<div class="content-wrapper">
	<div class="content-header">
		<div class="container-fluid">
			<div class="row">
				<!-- Header -->
			</div>
		</div>
	</div>

	<div class="content">
		<div class="container-fluid">
			<div class="row">
      			<div class="col-lg-12">
          			<div class="card shadow rounded-xl">
			            <div class="card-header bg-primary text-white">
			                <h5 class="m-0 font-weight-bold"><i class="fas fa-receipt"></i> Distributor Deposit</h5>
			            </div>
			            <div class="card-body">
			            <form id="AddPurchaseVoucherForm" method="POST" enctype="multipart/form-data">

	        				<div class="row">
	        					<div class="col-md-6">
	        						<div class="form-group">
										<label for="transactionid">Transaction ID <span class="text-danger">*</span></label>
										<input type="text" name="transactionid" id="transactionid" class="form-control" readonly>
									</div>
	        					</div>
	        					<div class="col-md-6">
	        						<div class="form-group">
										<label for="transactiondate">Date <span class="text-danger">*</span></label>
										<input type="date" name="transactiondate" id="transactiondate" class="form-control">
									</div>
	        					</div>
	        				</div>

							<div class="form-group row">
                            	<label class="col-sm-2 col-form-label">Distributor Account</label>
                            	<div class="col-sm-6">
                            		<select name='' id='distributorNameCredit' class='form-control selectpicker' data-live-search="true">
                            			<option value='' selected disabled>Select Distributor</option>
                            			@foreach($distributorNames as $distributorName)
                            				<option value='{{ $distributorName->head_code  }}'>{{ $distributorName->head_name  }}</option>
                            			@endforeach
                            		</select>
                            	</div>
                            	
                            	<div class="col-sm-2">
                            		<input type="text" name="creditheadcode" id="creditheadcode" class="form-control" readonly>
                            	</div>
                            </div>
			

							<hr>


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="debitamount">Deposit Amount <span class="text-danger">*</span></label>
                                        <input type="number" name="debitamount" id="debitamount" class="form-control" placeholder="Enter amount">
                                    </div>
                                </div>
                            
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="debitheadname">Deposit Type <span class="text-danger">*</span></label>
                                        <select name="debitheadname" id="debitheadname" class="form-control selectpicker" data-live-search="true">
                                            <option value="option_select" selected disabled>Select Debit Head</option>
                                            @foreach($assetAccounts as $assetAccount)
                                                <option value="{{ $assetAccount->head_code }}">{{ $assetAccount->head_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>	
                                </div>
                            
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="invisible">Head Code</label>
                                        <input type="text" name="debitheadcode" id="debitheadcode" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>

					                           
                            <div class="form-group">
                            	<label for="referencenote">Reference Note</label>
                            	<textarea class="form-control" name="referencenote" id="referencenote" rows="2" placeholder="If any notes..."></textarea>
                            </div>


							<div class="pt-3 text-right">
								<button type="button" onclick="processData()" class="btn btn-info">
									<i class="fas fa-plus"></i> Add Voucher
								</button>
								<button type="reset" class="btn btn-outline-danger ml-2" onclick="resetButton()">
									<i class="fas fa-eraser"></i> Reset
								</button>
								<h6 class="text-danger mt-2" id="errorMsgCredit"></h6>
							</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')


<script type="text/javascript">

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

	var debitHeadCode = $(this).val();
	// alert(debitHeadCode)
	$('#debitheadcode').val(debitHeadCode)
})

// $(document).on('change', '#creditheadname', function (e) {
// 	e.preventDefault();

// 	var creditHeadCode = $(this).val();
// 	// alert(creditHeadCode)
// 	$('#creditheadcode').val(creditHeadCode)
// })

$(document).on('change', '#distributorNameCredit', function (e) {
	e.preventDefault();

	var creditHeadCode = $(this).val();
	// alert(creditHeadCode)
	$('#creditheadcode').val(creditHeadCode)
})



function resetButton(){

	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
	$("#debit_table").find("tr:gt(0)").remove();
	$("#credit_table").find("tr:gt(0)").remove();
}


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

function processData() {
	event.preventDefault();

	let transactionId = $("#transactionid").val();
	let transactionDate = $("#transactiondate").val();
	let distributorHeadCode = $("#creditheadcode").val();
	let distributorName = $("#distributorNameCredit option:selected").text();
	let referenceNote = $("#referencenote").val();
	let debitHeadCode = $("#debitheadcode").val();
	let debitHeadName = $("#debitheadname option:selected").text();
	let debitAmount = $("#debitamount").val();

	// Basic validation
	if (!transactionId || !transactionDate || !distributorHeadCode || !debitHeadCode || !debitAmount) {
		$.notify("Please fill up all required fields.", { className: 'error', position: 'bottom right' });
		return;
	}

	if (debitAmount <= 0) {
		$.notify("Please enter a valid deposit amount.", { className: 'error', position: 'bottom right' });
		return;
	}

	let purchaseVoucher = {
		transactionId: transactionId,
		transactionDate: transactionDate,
		referenceNote: referenceNote,
		voucher: 'Distributor Deposit',
		totalDebitAmount: parseFloat(debitAmount),
		totalCreditAmount: parseFloat(debitAmount),
		voucherHeads: [
			{
				headName: debitHeadName,
				headCode: debitHeadCode,
				amount: debitAmount,
				transaction_type: 'Distributor Deposit By ' + distributorName,
				type: "debit"
			},
			{
				headName: distributorName,
				headCode: distributorHeadCode,
				amount: debitAmount,
				transaction_type: 'Distributor Deposit In ' + debitHeadName,
				type: "credit"
			}
		]
	};

	submitToServer(purchaseVoucher);
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
		        url: "/distributor-deposit-create",
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





	

	
</script>
@endsection