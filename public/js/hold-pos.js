fetchHoldListData()
function fetchHoldListData(){
	$.ajax({
		type: "GET",
		url: "/hold-list-data",
		dataType:"json",
		success: function(response){

			var countHold = Object.keys(response.holds).length;
			$('#totalHolds').text(countHold)

			$.each(response.holds, function(key, item) {
				$('#hold_table').append('<tr>\
				<td>'+item.id+'</td>\
				<td>'+item.orderDate+'</td>\
				<td>'+item.reference+'</td>\
				<td>\
    				<button type="button" value="'+item.reference+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i>\
                	</button>\
                	<button type="button" value="'+item.reference+'" class="delete_btn btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i>\
                	</button>\
    			</td>\
    		</tr>');
			})
		}
	});
}


$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var referenceId = $(this).val();

		$.ajax({
			type: "GET",
			url: "/hold-data/"+referenceId,
			success: function(response){
                // console.log(response)
				if (response.status == 200) {
					$('#referenceid').val(referenceId)

					$("#product_table").find("tr:gt(0)").remove();
					$("#free_product_table").find("tr:gt(0)").remove();
					$("#free_product_table").hide();

					var rowCount = $('#product_table tr').length;

					if(rowCount == 1){
				    	$('#total').text(0);
				    	$('#discount').text(0);
				    	$('#vatS').text(0);
                        $('#grandTotal').text(0);
                        $('#specialdiscount').val(0);
				    }

				    $('#customeridhidden').val(null);
				    $('#customername').text('');
					$('#customercontact').text('');
					$('#HoldListModal').modal('hide');


					$.each(response.holds, function(key, item) {

						if(item.client_id == 0){
							$('#customeridhidden').val(null);
						}else{
							$('#customeridhidden').val(item.client_id)
						}

						if(item.client_name == 0){
							$('#customername').text('N/A');
						}else{
							$('#customername').text(item.client_name)
						}

						if(item.client_mobile == 0){
							$('#customercontact').text('N/A');
						}else{
							$('#customercontact').text(item.client_mobile)
						}

                        if (item.specialDiscount == 0){
                            $('#specialdiscount').val('');
						}else{
                            $('#specialdiscount').val(item.specialDiscount)
						}
                        $('#grandTotal').text(item.grandTotal)
						qty = parseInt(item.quantity)

						$('#product_table_body').append('<tr>\
							<td>'+item.productName+'</td>\
							<td>'+item.mrp+'</td>\
							<td><input type="number" class="form-control w-75 border-left-0 border-right-0 border-top-0 rounded-0 pb-4" name="count" id="count" value="'+item.quantity+'"></td>\
							<td>'+item.mrp * item.quantity+'</td>\
							<td style="display:none;">'+item.discount+'</td>\
							<td style="display:none;">'+item.tax+'</td>\
							<td style="display:none;">'+item.discount+'</td>\
							<td style="display:none;">'+item.tax+'</td>\
							<td style="display:none;">'+item.productId+'</td>\
							<td style="display:none;">'+item.availableOffer+'</td>\
							<td style="display:none;">'+item.requiredQuantity+'</td>\
							<td style="display:none;">'+item.freeItemQty+'</td>\
							<td style="display:none;">'+item.offerName+'</td>\
							<td style="display:none;">'+item.offerItemId+'</td>\
							<td style="display:none;">'+item.productQty+'</td>\
                            <td style="display:none;">'+item.variant_id+'</td>\
                            <td style="display:none;">'+item.variant_id+'</td>\
							<td><button class="btn-remove" style="background: transparent;" value="'+item.id+'"><i class="fas fa-minus-circle" style="color: red;"></i></button></td>\
                            <td style="display:none;">'+item.productId+'</td>\
                            <td style="display:none;">'+item.type+'</td>\
                            <td style="display:none;">'+item.product_serial+'</td>\
                        </tr>');


						var qty = $('#product_table tr > td:first-child:contains('+item.productName+')').closest("tr").find("td:eq(2) input[type='number']").val()
						var res = Math.floor(qty/item.requiredQuantity)
						var remainder =  (qty%item.requiredQuantity)

						if(item.availableOffer == 'true' && remainder == 0){

							var freeQty = Math.floor(qty/item.requiredQuantity)

							if(freeQty > 0){
								if($('#free_product_table tr > td:contains('+item.productName+')').length == 0){
									alert('Offer Avaialble! Buy '+item.requiredQuantity+ ' ' +item.productName +' & Get '+item.freeItemQty+ ' '+ item.offerName)
									$('#free_product_table').show();
									$('#free_product_table_body').append('<tr>\
										<td>'+item.offerName+'</td>\
										<td>'+item.freeItemQty+'</td>\
										<td>'+item.productName+'</td>\
										<td style="display:none;">'+item.offerItemId+'</td>\
					        		</tr>');
								}else{
									alert('Offer Item Added for '+item.productName+'!')
									var add =  parseInt($('#free_product_table tr > td:contains('+item.productName+')').closest("tr").find("td:eq(1)").text()) + item.freeQuantity
									$('#free_product_table tr > td:contains('+item.productName+')').closest("tr").find("td:eq(1)").text(add)
								}
							}
						}

						var total = 0;
						$('#product_table').find('> tbody > tr').each(function () {
					    	var price = parseFloat($(this).find("td:eq(3)").text());
							total = total + price;
							$('#total').text(total.toFixed(2));
					    });

						var discountTotal = 0;
						$('#product_table').find('> tbody > tr').each(function () {
                            var d = parseFloat($(this).find("td:eq(4)").text());
                            var q = parseFloat($(this).find("td:eq(2) input[type = 'number']").val());
							discountTotal = discountTotal + d*q;
							$('#discount').text(discountTotal.toFixed(2));
					    });

					    var vatTotal = 0;
						$('#product_table').find('> tbody > tr').each(function () {
					    	var d = parseFloat($(this).find("td:eq(5)").text());
							vatTotal = vatTotal + d;
							$('#vatS').text(vatTotal.toFixed(2));
					    });

					    // $('#grandTotal').text(((parseFloat($('#total').text()) - parseFloat($('#discount').text())) + parseFloat($('#vatS').text())).toFixed(2) );
				    })
				}
			}
		});
});

$(document).on('click', '.delete_btn', function (e) {
	e.preventDefault();

	var referenceId = $(this).val();
	$.ajax({
	    url: "/hold-data-delete/"+referenceId,
	    type: 'DELETE',
	    success: function(result) {
	        location.reload()
	    }
	});

})


function dataCollectionForHold() {
	this.event.preventDefault();

	if($('#referencenumber').val().length != 0){

		var d = new Date();
		var date = d.getFullYear() +"-"+ (d.getMonth()+1) +"-"+ d.getDate();

		let orders= {};

		orders["reference"]     = $("#referencenumber").val();
		orders["clientId"] 		= $("#customeridhidden").val();
		orders["clientName"] 	= $("#customername").text();
		orders["clientMobile"] 	= $("#customercontact").text();
		orders["subscriberId"]	= $("#subscriberid").val();
		orders["storeId"] 		= $("#storeid").val();
		orders["posId"] 		= $("#posid").val();
		orders["salesBy"] 		= $("#salesby").val();
		orders["orderDate"] 	= date;
		orders["specialDiscount"]    = $('#specialdiscount').val();
		orders["grandTotal"]       = $('#grandTotal').text();

		var productTable = $('#product_table');
		var freeProductTable = $('#free_product_table');
		var productList = [];


		//---------------------PAYMENT DETAILS--------------------------------------------------------------------

	    $(productTable).find('> tbody > tr').each(function () {



	    	//----------------------------------------------------------------------Product Object
	    	let product= {};



	    	product["productId"]		= $(this).find("td:eq(8)").text();
	    	product["productName"]		= $(this).find("td:eq(0)").text();
	    	product["mrp"]				= $(this).find("td:eq(1)").text();
	    	product["quantity"]			= $(this).find("td:eq(2) input[type='number']").val();
	    	product["type"]		        = $(this).find("td:eq(19)").text();
	    	product["productSerial"]	= $(this).find("td:eq(20)").text();
	    	product["totalPrice"]		= $(this).find("td:eq(3)").text();
	    	product["totalDiscount"]	= $(this).find("td:eq(4)").text();
	    	if ($('#specialdiscount').val()== ''){
                specialDiscount=0;
			}else{
                specialDiscount=$('#specialdiscount').val()
			}
            product["specialDiscount"]    = specialDiscount;
	    	product["totalTax"]			= $(this).find("td:eq(5)").text();
            if($(this).find("td:eq(9)").text()=='')
            {
                product["available_offer"]	=0;
            }
            else{
                product["available_offer"]	= $(this).find("td:eq(9)").text();
            }
            if($(this).find("td:eq(10)").text()=='')
            {
                product["requiredQuantity"]	=0;
            }
            else{
                product["requiredQuantity"]	= $(this).find("td:eq(10)").text();
            }
	    	product["productQty"]		= $(this).find("td:eq(14)").text();
	    	product["freeItemQty"]		= $(this).find("td:eq(11)").text();

	    	product["variant_id"]		= $(this).find("td:eq(15)").text();
	    	product["variant_name"]		= $(this).find("td:eq(16)").text();

	    	// product["grandTotal"]		= ((parseFloat($(this).find("td:eq(3)").text()) - parseFloat($(this).find("td:eq(4)").text())) + parseFloat($(this).find("td:eq(5)").text()));
            product["grandTotal"]       = $('#grandTotal').text();
	    	product["discount"]			= $(this).find("td:eq(6)").text()

	    	if( ($(this).find("td:eq(9)").text() == "true") && ( product["quantity"] >= parseInt( $(this).find("td:eq(10)").text()) ) ){

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



	    	var price = $(this).find("td:eq(1)").text();
			var tax = parseFloat($(this).find("td:eq(7)").text());
			var taxAmount = parseFloat((tax*100)/price);
			product["tax"]			= taxAmount;


	    	productList.push(product);

	    });

	    orders["orderDetails"]=productList;
        // console.log(orders)
	    submitHold(orders);

	}else{
		$('#wrongreferencenumber').empty();
		$('#wrongreferencenumber').append('<span id="">Reference number is required.</span>');
	}

}

function submitHold(jsonData){


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
        url: "/hold-sale-create",
        data: JSON.stringify(jsonData),
        dataType : "json",
        contentType: "application/json",
        success: function (response) {
            $('#specialdiscount').val();
        	location.reload();
        }
    });

}

function printErrorMsg (message) {

        $('#wrongreferencenumber').empty();

		if(message.referencenumber == null){
			referencenumber = ""
		}else{
			referencenumber = message.referencenumber[0]
		}

        $('#wrongreferencenumber').append('<span id="">'+referencenumber+'</span>');

}

// dataTableX()
function dataTableX(){
	$(document).ready( function () {
		$('#hold_table').DataTable({
		    pageLength : 10,
		    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
		     "bLengthChange": false,
		});
	});
}
