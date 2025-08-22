$('#totalpricepurchase').on('click', function (e) {
	e.preventDefault();

	var total = $("#receivedqty").val() * $("#unitprice").val();

	$("#totalpricepurchase").val(total);

});

function productAddToTable() {
	this.event.preventDefault();
	var productId		=	$("#product option:selected").val();
	var productName     =   $("#product option:selected").text();
	var mrp       		=   parseFloat($("#mrp").val()).toFixed(2)
	var quantity  		=   $("#qty").val()
	var discount  		=   $("#discount1").val()
	var vat  			=   $("#tax1").val()
	var total			= 	(parseFloat(mrp) * parseFloat(quantity)).toFixed(2)

	var availableOffer  	= 	$("#availableoffer").val()
	var requiredQuantity	= 	$("#requiredQuantity").val()
	var freeQuantity  		= 	$("#freeQuantity").val()
	var freeItemName  		= 	$("#freeItemName").val()
	var offerItemId  		= 	$("#offerItemId").val()
	var productQty  		= 	$('#productQty').val()
	var serialNumber  		= 	$('#serialNumber').val()
	var productType  		= 	$('#productType').val()

	var isExcludedTax  		= 	$("#isExcludedTax").val()

	var variantId		=	$("#variant option:selected").val();
	var variantName     =   $("#variant option:selected").text();

	// if(isExcludedTax == "true"){
	// 	var tax = (parseFloat(mrp)/100) * parseFloat(tax);
	// }else{
	// 	var tax = 0;
	// }

    if(serialNumber==''&&productType=='Serialize'){
        $('#serialModal').modal('show');
    }
    else{
        if(productId != 'option_select' && quantity.length != 0  && mrp.length != 0 && (variantId != 0 || variantId != 'option_select')){

            if($('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').length == 0){
                // alert(quantity)
                // alert(productQty)

                if (parseFloat(quantity) > parseFloat(productQty)){
                    $.notify("Stock limited!", "error");

                }else{
                    $('#product_table_body').append('<tr>\
                        <td>'+productName+'('+variantName+'-'+serialNumber+')</td>\
                        <td>'+mrp+'</td>\
                        <td>'+quantity+'</td>\
                        <td>'+total+'</td>\
                        <td style="display:none;">'+discount*quantity+'</td>\
                        <td style="display:none;">'+vat*quantity+'</td>\
                        <td style="display:none;">'+discount+'</td>\
                        <td style="display:none;">'+vat+'</td>\
                        <td style="display:none;">'+productId+'</td>\
                        <td style="display:none;">'+availableOffer+'</td>\
                        <td style="display:none;">'+requiredQuantity+'</td>\
                        <td style="display:none;">'+freeQuantity+'</td>\
                        <td style="display:none;">'+freeItemName+'</td>\
                        <td style="display:none;">'+offerItemId+'</td>\
                        <td style="display:none;">'+productQty+'</td>\
                        <td style="display:none;">'+variantId+'</td>\
                        <td style="display:none;">'+serialNumber+'</td>\
                        <td style="display:none;">'+productType+'</td>\
                        <td style="display:none;">'+productName+'('+variantName+')</td>\
                        <td><button class="btn-remove" style="background: transparent;" value="'+productId+'"><i class="fas fa-minus-circle" style="color: red;"></i></button></td>\
                    </tr>');
                    resetButton();
                }


            }else{
                if(productType=='Serialize'){
                    $.notify("Serial is already added!", "error");
                    $('#serialModal').modal('show');
                }
                else{
                    var trid = $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").index();
                    var qty = $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(2)").text()
                    var discount1 = $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(4)").text()
                    var vat1 = $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(5)").text()
                    var totalProductQty = parseFloat($('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(14)").text())
                    var variantId = $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(15)").text()

                    // alert(totalProductQty)

                    var add = parseFloat(qty) + parseFloat(quantity)

                    if (parseFloat(add) > parseFloat(totalProductQty)){

                        // alert('Stock limited!')
                        $.notify("Stock limited!", "error");

                        $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(2)").text(totalProductQty)

                        var subTotal = (totalProductQty * parseFloat(mrp)).toFixed(2)
                        $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(3)").text(subTotal)


                        var totalDiscount = (totalProductQty * parseFloat(discount)).toFixed(2)
                        $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(4)").text(totalDiscount)

                        var totalVat = (totalProductQty * parseFloat(vat)).toFixed(2)
                        $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(5)").text(totalVat)
                        resetButton();
                    }else{

                        $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(2)").text(add)

                        var subTotal = (add * parseFloat(mrp)).toFixed(2)
                        $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(3)").text(subTotal)


                        var totalDiscount = (add * parseFloat(discount) ).toFixed(2)
                        $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(4)").text(totalDiscount)

                        var totalVat = (add * parseFloat(vat)).toFixed(2)
                        $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(5)").text(totalVat)
                        resetButton();

                    }
                }

            }

            var qty = $('#product_table tr > td:first-child:contains('+productName+'('+variantName+'-'+serialNumber+')').closest("tr").find("td:eq(2)").text()
            var res = Math.floor(qty/requiredQuantity)
            var remainder =  (qty%requiredQuantity)
            // alert(remainder)

            if(availableOffer == 'true' && remainder == 0){

                var freeQty = Math.floor(qty/requiredQuantity)

                if(freeQty > 0){
                    if($('#free_product_table tr > td:contains('+productName+')').length == 0){
                        alert('Offer Avaialble! Buy '+requiredQuantity+ ' ' +productName +' & Get '+freeQuantity+ ' '+ freeItemName)
                        $('#free_product_table').show();
                        $('#free_product_table_body').append('<tr>\
                            <td>'+freeItemName+'</td>\
                            <td>'+freeQuantity+'</td>\
                            <td>'+productName+'</td>\
                            <td style="display:none;">'+offerItemId+'</td>\
                        </tr>');
                    }else{
                        alert('Offer Item Added for '+productName+'!')
                        var add =  parseFloat($('#free_product_table tr > td:contains('+productName+')').closest("tr").find("td:eq(1)").text()) + freeQuantity
                        $('#free_product_table tr > td:contains('+productName+')').closest("tr").find("td:eq(1)").text(add)
                    }
                }
            }

            var total = 0;
            $('#product_table').find('> tbody > tr').each(function () {
                var price = parseFloat($(this).find("td:eq(3)").text());
                total = total + price;
                $('#totalX').val(total.toFixed(2));
            });

            var discountTotal = 0;
            $('#product_table').find('> tbody > tr').each(function () {
                var d = parseFloat($(this).find("td:eq(4)").text());
                discountTotal = discountTotal + d;
                $('#discount').val(discountTotal.toFixed(2));
            });

            var vatTotal = 0;
            $('#product_table').find('> tbody > tr').each(function () {
                var d = parseFloat($(this).find("td:eq(5)").text());
                vatTotal = vatTotal + d;
                $('#tax').val(vatTotal.toFixed(2));
            });
            if($('#specialdiscount').val()=='')
            {
                var specialdiscount = 0;
            }
            else
            {
                var specialdiscount = parseFloat($('#specialdiscount').val());
            }

            $('#grandtotal').val(((parseFloat($('#totalX').val()) - parseFloat($('#discount').val())) + parseFloat($('#tax').val()) - specialdiscount).toFixed(2) );


        }else{

            $('#addProduct').notify('Please fill up all the required fields.', {className: 'error', position: 'bottom right'});

        }
    }
}

$('.serial_submit').on('click', function (e){
    if ($("#serial").val() != '') {
        var storeId		    =	$("#store option:selected").val();
        if(storeId=='inventory'){
            storeId=0;
        }

        var productId		=	$("#product option:selected").val();

        var variantId		=	$("#variant option:selected").val();

        var serial = $('#serial').val();

        $.ajax({
			type: "get",
			url: "/product-serial-check/"+productId+"/"+variantId+"/"+storeId+"/"+serial,
			dataType: "json",
			success: function(response){
                if(response.status==200){
                    $('#serialNumber').val(response.serialNumber);
                    $('#serial').val('');
                    $('#serialModal').modal('hide');
                }
                else{
                    $.notify(response.message, "error");
                }
            }
        });
    }
    else {
        $.notify("Enter Serial Number!", "error");
    }
})

$("#product_table").on('click', '.btn-remove', function () {
    $(this).closest('tr').remove();

    var total = 0;
	$('#product_table').find('> tbody > tr').each(function () {
    	var price = parseFloat($(this).find("td:eq(3)").text());
		total = total + price;
		$('#totalX').val(total.toFixed(2));
    });

	var discountTotal = 0;
	$('#product_table').find('> tbody > tr').each(function () {
    	var d = parseFloat($(this).find("td:eq(4)").text());
		discountTotal = discountTotal + d;
		$('#discount').val(discountTotal.toFixed(2));
    });

    var vatTotal = 0;
	$('#product_table').find('> tbody > tr').each(function () {
    	var d = parseFloat($(this).find("td:eq(5)").text());
		vatTotal = vatTotal + d;
		$('#tax').val(vatTotal.toFixed(2));
    });

    $('#grandtotal').val(((parseFloat($('#totalX').val()) - parseFloat($('#discount').val()) - parseFloat($('#specialdiscount').val())) + parseFloat($('#tax').val())).toFixed(2) );

    var rowCount = $('#product_table tr').length;
    // alert(rowCount)

    if(rowCount == 1){
    	$('#totalX').val('0.00');
    	$('#discount').val('0.00');
    	$('#tax').val('0.00');
    	$('#specialdiscount').val('');
    	$('#grandtotal').val('0.00');
    }

    var availableOffer   = $(this).closest("tr").find("td:eq(9)").text()
    var requiredQuantity = parseFloat($(this).closest("tr").find("td:eq(10)").text())
    var freeQuantity 	 = parseFloat($(this).closest("tr").find("td:eq(11)").text())
    var freeItemName 	 = $(this).closest("tr").find("td:eq(12)").text()
    var productName 	 = $(this).closest("tr").find("td:eq(0)").text()

    var qty = $(this).closest("tr").find("td:eq(2) input[type='number']").val()

    if(availableOffer == "true"){

    	$('#free_product_table tr > td:contains('+productName+')').closest("tr").remove()
    	if($('#free_product_table tr').length == 1){
			$('#free_product_table').hide()
		}
    }
});

$(document).on('click', '#save', function (e){
    if ($("#grandtotal").val() != 0) {
        var paidamount = parseFloat($('#grandtotal').val());
        $('#paidp').text(paidamount.toFixed(2))
        $('#iPaidAmount').text(paidamount.toFixed(2));

        OrderSubmitToServer();
    }
    else {
        $.notify("Fillup the required fields!", "error");
    }
})

function OrderSubmitToServer() {
	this.event.preventDefault();

	// var time = new Date().getTime();
	// var orderId = time.toString();

	var d = new Date();
	var date = d.getFullYear() +"-"+ (d.getMonth()+1) +"-"+ d.getDate();

	let orders= {};
	// let due={}

	var client = $("#clientid option:selected").text();
	var clientName = client.substr(0, client.indexOf(' ('));

	var clientId = $("#clientid option:selected").val();

	if(clientId == 'Select Client'){
		clientId = ""
        clientName = "N/A"
	}

	orders["orderId"] 			= $("#invoiceno").val();
	orders["clientId"] 			= clientId
	orders["clientName"] 		= clientName
	orders["total"] 			= $("#totalX").val();
	orders["totalDiscount"] 	= $("#discount").val();
	orders["totalTax"] 			= $("#tax").val();
    if ($("#specialdiscount").val()=='')
    {
        orders["specialDiscount"] =0;
        // alert(orders["specialDiscount"])
    }
    else{
        orders["specialDiscount"] 	= $("#specialdiscount").val();
        // alert(orders["specialDiscount"])
    }

	orders["grandTotal"] 		= $("#grandtotal").val();

	orders["subscriberId"]		= $("#subscriberid").val();
	orders["storeId"] 			= $("#store").val();
	// orders["posId"] 			= $("#pos").val();
	orders["salesBy"] 			= $("#salesby").val();
	orders["orderDate"] 		= $("#orderdate").val();
    orders["due"] = $('#duep').text();
    orders["deposit"] = $('#paidp').text();


	// orders["referenceId"] 	= $('#referenceid').val();


	var productTable = $('#product_table');
	var freeProductTable = $('#free_product_table');
	var productList = [];
	// var discountDetails = [];
	// var taxDetails=[];
	var paymentDetails=[];
	let cashObj= {};
	let mobileBankingObj= {};
	let bankObj= {};
	let paymentObj= {};

	//---------------------PAYMENT DETAILS--------------------------------------------------------------------

	paymentObj["amount"] = $("#grandtotal").val()
	paymentObj["id"]	 = "0000"
    paymentObj["payment_type"] = "Cash"

	paymentDetails.push(paymentObj);


	if( $('#cashamount').val() && $('#cashamount').val() > 0){

		cashObj["amount"]	= $('#cashamount').val();
		cashObj["id"]		= "0000"
        cashObj["payment_type"]		= "Cash"

		paymentDetails.push(cashObj);
	}

	if( $('#mobilebankingamount').val() && $('#mobilebankingamount').val() > 0 && $('#mobilebanking').val() != "default"){

		mobileBankingObj["amount"]	= $('#mobilebankingamount').val();
		mobileBankingObj["id"]		= $('#mobilebanking').val();
		mobileBankingObj["type"]	= $("#mobilebanking").find("option:selected").text();

		mobileBankingObj["payment_type"]		= 'mobilebanking';
		paymentDetails.push(mobileBankingObj);
	}

    if ($('#bankamount').val() && $('#bankamount').val() > 0 && $('#bank').val() != "default"){

		bankObj["amount"]	= $('#bankamount').val();
        bankObj["id"] = $('#bank').val();
        bankObj["type"] = $("#bank").find("option:selected").text();

        bankObj["payment_type"] = 'Card';
		paymentDetails.push(bankObj);
	}

    $(productTable).find('> tbody > tr').each(function () {


    	// var discountDetails = [];
    	//----------------------------------------------------------------------Product Object
    	let product= {};
    	// let discountObj= {};
    	// let taxObj= {};


    	product["productId"]		= $(this).find("td:eq(8)").text();
    	product["variantId"]		= $(this).find("td:eq(15)").text();
    	product["productName"]		= $(this).find("td:eq(18)").text();
    	product["quantity"]			= $(this).find("td:eq(2)").text();
    	product["serialNumber"]			= $(this).find("td:eq(16)").text();
    	product["type"]			= $(this).find("td:eq(17)").text();

    	if( ($(this).find("td:eq(9)").text() == "true") && ( product["quantity"] >= parseFloat( $(this).find("td:eq(10)").text()) ) ){

    		$(freeProductTable).find('> tbody > tr').each(function () {
	    		if( product["productName"] == $(this).find("td:eq(2)").text() ){
	    			product["offerItemId"]		= $(this).find("td:eq(3)").text();
			    	product["offerName"]		= $(this).find("td:eq(0)").text();
			    	product["offerQuantity"]	= $(this).find("td:eq(1)").text();
	    		}
	    	 })
    	}else{
    		if( ($(this).find("td:eq(9)").text() == "true") ){

				product["offerItemId"]		= $(this).find("td:eq(13)").text()
		    	product["offerName"]		= $(this).find("td:eq(12)").text()
		    	product["offerQuantity"]	= 0;

    		}else{
    			product["offerItemId"]		= "null";
		    	product["offerName"]		= "null";
		    	product["offerQuantity"]	= 0;
    		}

    	}

        if ($("#specialdiscount").val() == '') {
            product["specialDiscount"] = 0;
            // alert(product["specialDiscount"])
        }
        else {
            product["specialDiscount"] = $("#specialdiscount").val();
            // alert(product["specialDiscount"])
        }
    	product["totalPrice"]		= $(this).find("td:eq(3)").text();
        product["totalDiscount"] = parseFloat($(this).find("td:eq(4)").text());
        // product["totalDiscount"] = $("#discount").val();
        // product["totalTax"] = $("#tax").val();
        product["totalTax"] = parseFloat($(this).find("td:eq(5)").text());

    	// product["grandTotal"]		= ((parseFloat($(this).find("td:eq(3)").text()) - parseFloat($(this).find("td:eq(4)").text())) + parseFloat($(this).find("td:eq(5)").text()));
        product["grandTotal"] = product["totalPrice"] - product["totalDiscount"] + product["totalTax"];
    	product["discount"]			= $(this).find("td:eq(6)").text()

    	var price = $(this).find("td:eq(1)").text();
		var tax = parseFloat($(this).find("td:eq(7)").text());
		var taxAmount = parseFloat((tax*100)/price);
		product["tax"]			= taxAmount;


    	productList.push(product);

    });

    orders["orderDetails"]=productList;
    orders["paymentDetails"]=paymentDetails;


    if(productList.length>0){
        // console.log(orders);
    	if($('#clientid').val() != 'Select Client' && $('#store').val() != 'option_select' && $('#pos').val() != 'option_select'){
    		submitOrder(orders);
    	}else{
    		$.notify("Please fill up the required fields!", "error");
    	}


    }else{
    	$.notify("Please select at least one product!", "error");
    }

}

$(document).on('keyup', '#specialdiscount', function (e) {
    var grandTotal = parseFloat($("#grandtotal").val());
    var sDiscount = parseFloat($("#specialdiscount").val());
    var totalPrice = $("#totalX").val();
    var totalTax = $("#tax").val();
    var totalDiscount = $("#discount").val();
    var zero=0;

    if (grandTotal.length != 0 || grandTotal != 0) {
        if (sDiscount <= grandTotal && sDiscount > zero) {
            gTotal = (parseFloat(totalPrice) + parseFloat(totalTax)) - parseFloat(sDiscount) - parseFloat(totalDiscount)
            $("#grandtotal").val(gTotal.toFixed(2))
        } else {
            $("#specialdiscount").val('');
            gTotal = (parseFloat(totalPrice) + parseFloat(totalTax)) - parseFloat(totalDiscount)
            $("#grandtotal").val(gTotal.toFixed(2))
        }

    }

    if (sDiscount.length == 0) {
        gTotal = (parseFloat(totalPrice) + parseFloat(totalTax)) - parseFloat(totalDiscount)
        $("#grandtotal").val(gTotal.toFixed(2))
    }


})

function submitOrder(jsonData) {

    // console.log(jsonData)
    var storeId = $('#store').val();
    var storeAddress;
    var storeMobile;
    $.ajax({
        type: "get",
        url: "/get-store/" + storeId,
        contentType: "application/json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            storeAddress = response.store[0].store_address
            storeMobile = response.store[0].contact_number
            $('#iStoreAddress').text(storeAddress)
            $('#iStoreContact').text(storeMobile)
        }
    });
    // if ($('#paidp').text() == 0.00 && $('#duep').text() == 0.00)
    // {
        // alert('hi');
        $.ajax({
            type: "POST",
            url: "/check-offer-quantity",
            data: JSON.stringify(jsonData),
            dataType : "json",
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                if(response.check == "True"){
                    $.ajax({
    	// ajaxStart: $('body').loadingModal({
		// 	  position: 'auto',
		// 	  text: 'Please Wait',
		// 	  color: '#fff',
		// 	  opacity: '0.7',
		// 	  backgroundColor: 'rgb(0,0,0)',
		// 	  animation: 'foldingCube'
		// 	}),
        type: "POST",
        url: "/get-order",
        data: JSON.stringify(jsonData),
        dataType : "json",
        contentType: "application/json",
        headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
        success: function (response) {
        	// console.log(response.testData)
        	invoice(response.orderId)
        	// alert(response.message);
        	// location.reload();

        }
    });
}else{
        $.notify('Offer Item low stock!!!', {className: 'error', position: 'bottom right'})
    }

}
});
    // }
    // else{
    //     $.ajax({
    //         type: "POST",
    //         url: "/get-allorder",
    //         data: JSON.stringify(jsonData),
    //         dataType: "json",
    //         contentType: "application/json",
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         success: function (response) {
    //             // console.log(response.testData)
    //             invoice(response.orderId)
    //             // alert(response.message);
    //             // location.reload();

    //         }
    //     });
    // }

}

function resetButton(){

	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
	$('#form_div1').find('form')[0].reset();
    $('#variant').empty();
    $("#qty").prop('disabled', false);
}

$(document).ready( function() {
    var storeId = $('#store').val()
    	$.ajax({
	        type: "GET",
	        url: "/store-wise-product/"+storeId,
	        dataType:"json",
	        success: function(response){
	            // console.log(response.data)
	            // alert(response.message)

	            $('#product').empty();
	            $('#product').append('<option value="option_select" selected disabled>Select product</option>');
	            $.each(response.data, function(key, item){
	                 $('#product').append('<option value="'+ item.id +'">'+ item.productName +'</option>');
	            });

	            $('#product').appendTo('#product').selectpicker('refresh');

	        }
	    })

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
	$('#orderdate').attr('value', today);

	var time = new Date().getTime();
	var orderId = time.toString();
	$('#invoiceno').attr('value', orderId);

})

$('#product').on('change', function() {
    var productId = $(this).val()
    var storeId = $('#store').val()
    $('#serialNumber').val('')

    // alert(storeId)
    var productName = $("#product").find("option:selected").text()

    $.ajax({
        type: "GET",
        url: "/product-wise-variant/"+productId+"/"+storeId,
        dataType:"json",
        success: function(response){
            // console.log(response)
            $('#productType').val(response.type)
            if(response.type!='Serialize'){
                $('#serialNumber').val(response.barcode)
                $("#qty").prop('disabled', false);
                $('#qty').val('')
            }
            else{
                $('#qty').val(1)
                $("#qty").prop('disabled', true);
            }

            $('#variant').empty();
            $('#variant').append('<option value="default" selected disabled>Select variant</option>');
            $.each(response.data, function(key, item){
                 $('#variant').append('<option value="'+ item.variant_id +'">'+ item.variant_name +'</option>');
            });

            $('#variant').appendTo('#variant').selectpicker('refresh');

        }
    })

})

$('#variant').on('change', function() {
    var variantId = $(this).val()
    var variantName = $("#variant").find("option:selected").text()

    var productId = $("#product").find("option:selected").val()

    var storeId = $("#store").find("option:selected").val()

    if(storeId == 'option_select'){
    	$.notify("Please select store first!", "error");
    }else{
    	$.ajax({
			type: "GET",
			url: "/product-add/"+productId+"/"+variantId+"/"+storeId,
			dataType: "json",
			success: function(response){
				// console.log(response.products)
				$.each(response.products, function(key, item){

					if(item.isExcludedTax == "true"){
                        var tax = parseFloat((item.mrp/100) * item.tax);
					}else{
						var tax = 0;
					}

					if(item.discount_type == "Percentage"){
						var discountChecked = (item.mrp/100) * item.discount;
					}else{
						var discountChecked = item.discount;
					}

					$('#mrp').val(item.mrp)
					$('#discount1').val(discountChecked)
                    $('#tax1').val(tax)

					$('#availableoffer').val(item.available_offer)
					$('#requiredQuantity').val(item.requiredQuantity)
					$('#freeQuantity').val(item.freeQuantity)
					$('#freeItemName').val(item.freeItemName)
					$('#offerItemId').val(item.offerItemId)
					// $('#productQty').val(0)
					$('#isExcludedTax').val(tax)

				})
                // console.log(response.onHand)
				$('#productQty').val(response.onHand)
			}
		})
    }





})

$('#search_btn').on('click', function(e){
	e.preventDefault();

	var keyword = $('#search').val();
	var storeId = $('#store').val();

	if( $('#search').val() ){
	 	$.ajax({

			type: "GET",
			url: "/product-search/"+storeId+'/'+keyword,
			dataType: "json",
			success: function(response){
				if($.isEmptyObject(response.products)){
             		$('#search_btn').notify('No product found!!!', {className: 'error', position: 'bottom right'});
                }else{
                    if(response.products[0].saleId==0){
                        $("#product").val(response.products[0].id).change();

                        $('#variant').empty();
                        $('#variant').append('<option value="default" selected disabled>Select variant</option>');
                        $.each(response.products, function(key, item){
                             $('#variant').append('<option value="'+ item.variant_id +'">'+ item.variant_name +'</option>');
                        });

                        $("#variant").selectpicker('refresh');
                        $("#variant").val(response.products[0].variant_id).change();

                        $.each(response.products, function(key, item){

                            if(item.isExcludedTax == "true"){
                                var tax = parseFloat((item.mrp/100) * item.tax);
                            }else{
                                var tax = 0;
                            }

                            if(item.discount_type == "Percentage"){
                                var discountChecked = (item.mrp/100) * item.discount;
                            }else{
                                var discountChecked = item.discount;
                            }

                            $('#mrp').val(item.mrp)
                            $('#discount1').val(discountChecked)
                            $('#tax1').val(tax)
                        })
                        // console.log(response.onHand)
                        $('#productQty').val(response.products[0].onHand)
                        $('#serialNumber').val(response.products[0].serialNumber)
                        $('#productType').val(response.products[0].type)
                        if($('#productType').val()=='Serialize'){
                            $('#qty').val(1)
                            $("#qty").prop('disabled', true);
                        }
                    }
                    else{
                        $.notify("Product Sold!", "error");
                    }
				}

			}
		})
	}
})

$(document).on('click', '#pay_btn', function (e){

    if ($("#grandtotal").val() != 0) {
        $('#cashamount').val('')
        $('#mobilebankingamount').val('')
        $('#bankamount').val('')
        $('#PaymentModal').modal('show');

        var grandTotal = parseFloat($('#grandtotal').val())
        var zero = 0

        $('#totalp').text(grandTotal.toFixed(2))
        $('#paidp').text(zero.toFixed(2))
        $('#duep').text(grandTotal.toFixed(2))
    }
    else {
        $.notify("Fillup the required fields!", "error");
    }
})

$("#cashamount").change(function(){
  // alert("The text has been changed.");

  if( $('#cashamount').val() < 0){
		$('#cashamount').val('')
      var grandTotal = parseFloat($('#grandtotal').val())
      var zero = 0

      $('#totalp').text(grandTotal.toFixed(2))
      $('#paidp').text(zero.toFixed(2))
      $('#duep').text(grandTotal.toFixed(2))
	}
    else{

  var cashAmount = parseFloat($('#cashamount').val())
  var grandTotal = parseFloat($('#grandtotal').val())

  if($('#mobilebankingamount').val() > 0 && $('#bankamount').val() > 0){
  	var totalPaid = parseFloat( $('#mobilebankingamount').val()) + parseFloat($('#bankamount').val()) + cashAmount
  }else if($('#mobilebankingamount').val() > 0){
  	var totalPaid = parseFloat( $('#mobilebankingamount').val()) + cashAmount
  }else if($('#bankamount').val() > 0){
  	var totalPaid = parseFloat( $('#bankamount').val()) + cashAmount
  }else{
  	var totalPaid = cashAmount
  }

    if (parseFloat(grandTotal) < parseFloat(totalPaid))
    {
        $('#cashamount').val('')
        $('#bankamount').val('')
        $('#mobilebankingamount').val('')
        var zero = 0
        $('#paidp').text(zero.toFixed(2))
        $('#duep').text(grandTotal.toFixed(2))
    }
    else
    {
        var totalDue = parseFloat(grandTotal) - parseFloat(totalPaid)

        $('#paidp').text(totalPaid.toFixed(2))
        $('#duep').text(totalDue.toFixed(2))
    }
}

});

$("#mobilebankingamount").change(function(){

	if( $('#mobilebankingamount').val()<0 ){
		$('#mobilebankingamount').val('')
        var grandTotal = parseFloat($('#grandtotal').val())
        var zero = 0

        $('#totalp').text(grandTotal.toFixed(2))
        $('#paidp').text(zero.toFixed(2))
        $('#duep').text(grandTotal.toFixed(2))
	}
    else{

	var mobileBankingAmount = parseFloat($('#mobilebankingamount').val())
	var grandTotal = parseFloat($('#grandtotal').val())

	if($('#cashamount').val() > 0 && $('#bankamount').val() > 0){
		var totalPaid = parseFloat( $('#cashamount').val()) + parseFloat($('#bankamount').val()) + mobileBankingAmount
	}else if($('#cashamount').val() > 0){
		var totalPaid = parseFloat( $('#cashamount').val()) + mobileBankingAmount
	}else if($('#bankamount').val() > 0){
		var totalPaid = parseFloat( $('#bankamount').val()) + mobileBankingAmount
	}else{
		var totalPaid = mobileBankingAmount
	}

    if (parseFloat(grandTotal) < parseFloat(totalPaid)) {
        $('#cashamount').val('')
        $('#bankamount').val('')
        $('#mobilebankingamount').val('')
        var zero = 0
        $('#paidp').text(zero.toFixed(2))
        $('#duep').text(grandTotal.toFixed(2))
    }
    else {
        var totalDue = parseFloat(grandTotal) - parseFloat(totalPaid)

        $('#paidp').text(totalPaid.toFixed(2))
        $('#duep').text(totalDue.toFixed(2))
    }
    }
});

$("#bankamount").change(function(){

	if( $('#bankamount').val() <0){
		$('#bankamount').val('')
        var grandTotal = parseFloat($('#grandtotal').val())
        var zero = 0

        $('#totalp').text(grandTotal.toFixed(2))
        $('#paidp').text(zero.toFixed(2))
        $('#duep').text(grandTotal.toFixed(2))
	}
else{
	var bankAmount = parseFloat($('#bankamount').val())
	var grandTotal = parseFloat($('#grandtotal').val())

	if($('#cashamount').val() > 0 && $('#mobilebankingamount').val() > 0){
		var totalPaid = parseFloat( $('#cashamount').val()) + parseFloat($('#mobilebankingamount').val()) + bankAmount
	}else if($('#cashamount').val() > 0){
		var totalPaid = parseFloat( $('#cashamount').val()) + bankAmount
	}else if($('#mobilebankingamount').val() > 0){
		var totalPaid = parseFloat( $('#mobilebankingamount').val()) + bankAmount
	}else{
		var totalPaid = bankAmount
	}

    if (parseFloat(grandTotal) < parseFloat(totalPaid)) {
        $('#cashamount').val('')
        $('#bankamount').val('')
        $('#mobilebankingamount').val('')
        var zero = 0
        $('#paidp').text(zero.toFixed(2))
        $('#duep').text(grandTotal.toFixed(2))
    }
    else {
        var totalDue = parseFloat(grandTotal) - parseFloat(totalPaid)

        $('#paidp').text(totalPaid.toFixed(2))
        $('#duep').text(totalDue.toFixed(2))
    }
}
});

$(document).on('click', '#pay_btnP', function (e){
    var paidamount = parseFloat($('#paidp').text());
    $('#iPaidAmount').text(paidamount.toFixed(2));
    var dueamount = parseFloat($('#duep').text());
    $('#iDue').text(dueamount.toFixed(2));

	let due = {}

	var d = new Date();
	var date = d.getFullYear() +"-"+ (d.getMonth()+1) +"-"+ d.getDate();

	var time = new Date().getTime();
	var orderId = time.toString();

	var client = $("#clientid option:selected").text();
	var clientName = client.substr(0, client.indexOf(' ('));

	due["card"] 			= $('#bankamount').val();
	due["cash"] 			= $('#cashamount').val();
	due["clientId"] 		= $("#clientid option:selected").val();
	due["due_amount"] 		= parseFloat($('#duep').text());
	due["mobile_bank"] 		= $('#mobilebankingamount').val();
	due["mobile_bank_type"] = $("#mobilebanking").find("option:selected").text()
	due["paid_amount"] 		= $("#paidp").text();


	due["storeId"] 		= $("#store").val();
	due["subscriberId"] = $("#subscriberid").val();
	due["total"] 		= parseFloat($("#totalp").text());
	due["userId"] 		= $("#salesby").val();
	due["depositDate"] 	= date;
    due["orderId"]      = $("#invoiceno").val();

	var dueAmount = parseFloat($('#duep').text());
	var customerId = $("#clientid option:selected").val();
	var grandTotal = parseFloat($('#grandtotal').val())

	if( (dueAmount > 0 && customerId == 'Select Client') || (dueAmount > 0 && customerId == '0')){
		$.notify("Customer is not eligible for due!", "error");
	}else if(grandTotal == 0.00){
		$.notify("Please Enter at least one product!", "error");
	}else if(dueAmount > 0){
		submitDue(due);
		OrderSubmitToServer();
		// alert('Due & Order!')
	}else{
		OrderSubmitToServer();

		// alert('Order')
	}
	// alert(dueAmount)// alert('Submitted')

	e.preventDefault();
});

function submitDue(jsonData){
	// console.log(jsonData)
	// alert('Due Submitted')

	// invoice(1)

	$.ajax({
        type: "POST",
        url: "/due-payment",
        data: JSON.stringify(jsonData),
        dataType : "json",
        contentType: "application/json",
        headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
        success: function (response) {
        	// console.log(response.message)
        	// invoice(response.orderId)
        	// alert(response.message);

        }
    });
}

function invoice(data){

	$('#invoice_table_body').empty()
	var storeName = $("#store option:selected").text();
	// var orgName = $('#orgname').val();

	// var binNumber = $('#binnumber').val();

	var cashier = $("#username").val();
	 var posId = $("#pos option:selected").val();

	var time = new Date().getTime();
	var orderId = data

	var d = new Date();
	var date = d.getDate() +"-"+ (d.getMonth()+1) +"-"+ d.getFullYear()

	var d = new Date(); // for now
	var hours = d.getHours(); // => 9
	var miutes = d.getMinutes(); // =>  30
	var seconds = d.getSeconds(); // => 51
	var iTime = d.getHours()+":"+d.getMinutes()

	var customerId = $("#clientid option:selected").text();


	$('#iStoreName').text(storeName)
	// $('#iOrgName').text(orgName)

	// $('#iBin').text(binNumber)

	$('#iCashier').text(cashier)
	$('#iPosId').text(posId)
	$('#iInvoice').text(orderId)
	$('#iDate').text(date+" "+iTime)
	if(customerId.length == 0 || customerId == "Select Client"){
		$('#iCustomerId').text('Walkin Customer')
	}else{
		$('#iCustomerId').text(customerId)
	}

	var productTable = $('#product_table');
	var index = 0

	$(productTable).find('> tbody > tr').each(function () {
		var itemDescription = $(this).find("td:eq(0)").text()
		var unitPrice = parseFloat($(this).find("td:eq(1)").text())
		var qty = parseFloat($(this).find("td:eq(2)").text())
		var total = parseFloat($(this).find("td:eq(3)").text())
		index = index + 1
		$('#invoice_table_body').append('\
			<tr>\
				<td>'+index+'</td>\
				<td>'+ itemDescription+'</td>\
				<td>'+ unitPrice.toFixed(2) +'</td>\
				<td>'+ qty.toFixed(2) +'</td>\
				<td>'+ total.toFixed(2) +'</td>\
    		</tr>')


	})

	// $("#total").text();
	// $("#discount").text();
	// $("#vatS").text();
	// $("#grandTotal").text();

	var subTotal = parseFloat($("#totalX").val());
    var discount = parseFloat($("#discount").val());
	var afterDiscount = subTotal - discount
    if ($("#specialdiscount").val() == '') {
        var specialDiscount =0;
    }
    else{
        var specialDiscount = parseFloat($("#specialdiscount").val());
    }
    var afterSpecialDiscount = afterDiscount - specialDiscount
	var vat = parseFloat($("#tax").val());
    var afterVat = afterSpecialDiscount + vat
	var rounding = Math.round(afterVat)

	$('#iSubTotal').text(subTotal.toFixed(2))
	$('#iDiscount').text(discount.toFixed(2))
    $('#iAfterDiscount').text(afterDiscount.toFixed(2))
    $('#iSpecialDiscount').text(specialDiscount.toFixed(2))
    $('#iAfterAllDiscount').text(afterSpecialDiscount.toFixed(2))
	$('#iVat').text(vat.toFixed(2))
	$('#iAfterVat').text(afterVat.toFixed(2))
	$('#iRounding').text(rounding.toFixed(2))
	$('#iNetPayable').text(rounding.toFixed(2))



	$('#ReceiptModal').modal('show');
    $.print("#invoice");
    $('#ReceiptModal').on('hidden.bs.modal', function () {
	 location.reload();
	})

}

