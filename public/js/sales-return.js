$(document).ready(function () {
    $(document).on('change', '#search', function (e) {
		e.preventDefault();
		// alert('Success')

		if( $('#search').val().length == 0){
			// alert('Success')
			var msg = "Invoice No. is required."
			$('#wrongsearch').empty();
        	$('#wrongsearch').append('<span id="">'+msg+'</span>');
		}else{
			var invoiceNo = $('#search').val()
			// alert(invoiceNo)
			$.ajax({
				type: "GET",
				url: "/sales-return-create/"+invoiceNo,
				dataType:"json",
				success: function(response){
					// console.log(response)
					if(response.message == "Invoice no. doesn't exist!!!"){
						$('#search_btn').notify(response.message, {className: 'error', position: 'bottom right'});
						// $('#search').val('')
					}else{
						$('#errMsg').empty()
						if(response.data.length === 0){
							var msg = "No data found."
							$('#wrongsearch').empty();
	        				$('#wrongsearch').append('<span id="">'+msg+'</span>');
						}else{
							$('#wrongsearch').empty();
							// alert(response.message)
                            // console.log(response.data[0].quantity)

                            clientId = response.data[0].clientId
							if(clientId== 0){
								$('#customerName').text("Walking Customer")
								$('#customerId').text(clientId)
                                $('#customerMobile').text("N/A")
                                // console.log(response)
							}else{
                                $('#customerName').text(response.clientName)
                                $('#customerId').text(response.clientId)
                                $('#customerMobile').text(response.clientMobile)
							}
                            $('#storeId').text(response.order[0].store_id)
                            $('#orderDate').text(response.order[0].orderDate)
                            $('#totalPrice').text(response.order[0].total)
                            $('#totalDiscount').text(response.order[0].totalDiscount)
                            $('#specialDiscount').text(response.order[0].specialDiscount)
                            $('#totalTax').text(response.order[0].totalTax)
                            $('#grandTotal').text(response.order[0].grandTotal)

							// $('#date').val('')
                            // var date = response.order[0].orderDate
							// $('#date').val(date)

							// $('#storeid').val('')
                            // var storeid = response.order[0].store_id
							// $('#storeid').val(storeid)


							$("#return_table").find("tr:gt(0)").remove();
							$.each(response.data, function(key, item) {

								var totalPrice = parseFloat(item.totalPrice)
								var qty = parseFloat(item.quantity)
                                // alert(qty)
								var price = parseFloat(totalPrice/qty)
								if(item.totalDiscount == null){
                                    var totalDiscount = 0;
								}else{
                                    var totalDiscount = parseFloat(item.totalDiscount);
									var discount = parseFloat(totalDiscount/qty)
                                    // alert(totalDiscount)
								}

								if(item.totalTax == null){
									var totalTax = 0;
								}else{
									var totalTax = item.totalTax;
									var tax = parseFloat(totalTax/qty);
								}

								$('#return_table_body').append('\
								<tr>\
									<td>'+item.productName+'</td>\
									<td>'+item.quantity+'</td>\
									<td><input type="number" class="form-control" name="" id="returnqty" value="0"></td>\
									<td>'+price.toFixed(2)+'</td>\
									<td><input type="number" class="form-control" name="" id="deduction" value=""></td>\
									<td><input type="number" class="form-control" name="" id="total" value="0" disabled></td>\
									<td class="hidden"></td>\
									<td class="hidden"><input type="number" class="form-control" name="" id="deduction" value="0" disabled></td>\
									<td><spam id="" >'+ discount.toFixed(2) +'</spam></td>\
									<td><spam id="tax" >'+ tax.toFixed(2) +'</spam></td>\
									<td class="hidden"><input type="number" class="form-control" name="" id="taxamount" value="0" disabled></td>\
				        			<td class="hidden">'+item.productId+'</td>\
				        			<td class="hidden">'+item.variant_id+'</td>\
				        		</tr>');


							})

							$("#free_return_table").find("tr:gt(0)").remove();
							$.each(response.data2, function(key, item) {

								if(item.freeItemName == null || item.freeItemName == 'NULL'){
									$('#freeItemTableDiv').hide()
									var freeItemName = "No offer"
								}else{

									var freeItemName = item.freeItemName
									$('#freeItemTableDiv').show()

								}

								if(item.offerItemId == null){
									var offerItemId = 0
								}else{
									var offerItemId = item.offerItemId
								}

				        		$('#free_return_table_body').append('\
								<tr>\
									<td>'+freeItemName+'</td>\
									<td>'+item.requiredQuantity+'</td>\
									<td>'+item.freeQuantity+'</td>\
									<td>'+item.productName+'</td>\
									<td class="hidden">'+offerItemId+'</td>\
				        		</tr>');
							})
						}
					}

				}
			});
		}
	})
})

$(document).on('keyup', '#returnqty', function(e) {
  // alert( "Handler for .keyup() called." );
  var returnQty = $(this).val()
  var soldQty = parseFloat($(this).closest("tr").find("td:eq(1)").text())
    // alert(soldQty)
  var price = parseFloat($(this).closest("tr").find("td:eq(3)").text())
  var totalPrice = parseFloat(returnQty*price)

  var tax = parseFloat($(this).closest("tr").find("td:eq(9)").text())



  if(returnQty <= soldQty && returnQty >= 0){
      $(this).closest("tr").find("td:eq(5) input[type='number']").val(totalPrice.toFixed(2))

  	//Deduction
    var deduction = $(this).closest("tr").find("td:eq(4) input[type='number']").val()
	var deductionAmount = parseFloat(returnQty*price*(deduction/100))
	$(this).closest("tr").find("td:eq(7) input[type='number']").val(deductionAmount)

    // var totalAfterDeduction = totalPrice - deductionAmount
	// $(this).closest("tr").find("td:eq(5) input[type='number']").val(totalAfterDeduction.toFixed(2))

    var totalDeduction = 0
  	$('#return_table').find('> tbody > tr').each(function () {
    	var deduct = $(this).closest("tr").find("td:eq(7) input[type='number']").val();
		totalDeduction = parseFloat(totalDeduction) + parseFloat(deduct)
		// console.log(totalDeduction)

		$('#totaldeduction').val(totalDeduction.toFixed(2));
    });


    //Tax
    var taxAmount = parseFloat(tax)
    var totalTaxReturn = parseFloat(taxAmount*returnQty)
    $(this).closest("tr").find("td:eq(10) input[type='number']").val(totalTaxReturn)

    // var totalAfterTaxReturn = totalAfterDeduction + totalTaxReturn
	// $(this).closest("tr").find("td:eq(5) input[type='number']").val(totalAfterTaxReturn.toFixed(2))

    var totalTax = 0
  	$('#return_table').find('> tbody > tr').each(function () {
    	var taXx = $(this).closest("tr").find("td:eq(10) input[type='number']").val();
		totalTax = parseFloat(totalTax) + parseFloat(taXx)
		// console.log(totalDeduction)

		$('#totaltax').val(totalTax.toFixed(2));
    });

      //Discount Deduction

      var discountDeduction = 0
      $('#return_table').find('> tbody > tr').each(function () {
          var discount = $(this).find("td:eq(8)").text();
          discountDeduction = parseFloat(discountDeduction) + parseFloat(discount) * returnQty
          // console.log(discountDeduction)
        });
        $('#discountdeduction').val(discountDeduction.toFixed(2));
      $('#discount').text(discountDeduction.toFixed(2));

  	//total
    var total = 0
  	$('#return_table').find('> tbody > tr').each(function () {
    	var p = parseFloat($(this).closest("tr").find("td:eq(5) input[type='number']").val());
            total = parseFloat(total + p);
        });
      var d = parseFloat($('#discountdeduction').val());
      var t = parseFloat($('#totaltax').val());
      var totaldeduction = parseFloat($('#totaldeduction').val());
      var net = parseFloat(total - d + t - totaldeduction);
      // alert(total)
      $('#netreturn').val(net.toFixed(2));

  }else{
  	// $.notify("Invalid Return Qty", "error");
  	$.notify("Invalid Return Qty", {className: 'error', position: 'bottom right'});
  	$(this).closest("tr").find("td:eq(2) input[type='number']").val(0)
  	// $(this).closest("tr").find("td:eq(5) input[type='number']").val(soldQty*price)
  }

})


  $(document).on('keyup', '#deduction', function(e) {
  	// var key = e.which;
	// if(key == 13){  // the enter key code
		var deduction = $(this).val()

		if(deduction > 100 || deduction < 0){
			$.notify('Invalid Deduction!', {className: 'error', position: 'bottom right'});
			$(this).val(0)
		}else{
			var price = parseFloat($(this).closest("tr").find("td:eq(3)").text())
	  		var returnQty = parseFloat($(this).closest("tr").find("td:eq(2) input[type='number']").val());

	  		var deductionAmount = parseFloat(returnQty*price*(deduction/100))
	  		$(this).closest("tr").find("td:eq(7) input[type='number']").val(deductionAmount)


			var soldQty = parseFloat($(this).closest("tr").find("td:eq(1)").text())
			var totalPrice = parseFloat(returnQty*price)
	  		var tax = parseFloat($(this).closest("tr").find("td:eq(9)").text())


	  		// var totalPrice = parseFloat($(this).closest("tr").find("td:eq(5) input[type='number']").val())


	  		// var totalAfterDeduction = totalPrice - deductionAmount
	  		// $(this).closest("tr").find("td:eq(5) input[type='number']").val(totalAfterDeduction.toFixed(2))

	  		// $('#totaldeduction').val(deductionAmount.toFixed(2));

		    var totalDeduction = 0
		  	$('#return_table').find('> tbody > tr').each(function () {
		    	var deduct = $(this).closest("tr").find("td:eq(7) input[type='number']").val();
				totalDeduction = parseFloat(totalDeduction) + parseFloat(deduct)
				// console.log(totalDeduction)

				$('#totaldeduction').val(totalDeduction.toFixed(2));
		    });

		    //Tax
			var taxAmount = parseFloat(tax)
			var totalTaxReturn = parseFloat(taxAmount*returnQty)
			$(this).closest("tr").find("td:eq(10) input[type='number']").val(totalTaxReturn)

			// var totalAfterTaxReturn = totalAfterDeduction + totalTaxReturn
			// $(this).closest("tr").find("td:eq(5) input[type='number']").val(totalAfterTaxReturn.toFixed(2))

			var totalTax = 0
				$('#return_table').find('> tbody > tr').each(function () {
				var taXx = $(this).closest("tr").find("td:eq(10) input[type='number']").val();
				totalTax = parseFloat(totalTax) + parseFloat(taXx)
				// console.log(totalDeduction)

				$('#totaltax').val(totalTax.toFixed(2));
			});

            var discountDeduction = 0
            $('#return_table').find('> tbody > tr').each(function () {
                var discount = $(this).find("td:eq(8)").text();
                discountDeduction = parseFloat(discountDeduction) + parseFloat(discount) * returnQty
                // console.log(discountDeduction)
            });
            $('#discountdeduction').val(discountDeduction.toFixed(2));
            $('#discount').text(discountDeduction.toFixed(2));

			var total = 0
		  	$('#return_table').find('> tbody > tr').each(function () {
		    	var p = parseFloat($(this).closest("tr").find("td:eq(5) input[type='number']").val());
                total = parseFloat(total + p);
		    });
            var d = parseFloat($('#discountdeduction').val());
            var t = parseFloat($('#totaltax').val());
            var totaldeduction = parseFloat($('#totaldeduction').val());
            var net = parseFloat(total - d + t - totaldeduction);
            // alert(total)
            $('#netreturn').val(net.toFixed(2));
		}


});


function collectingData() {
	this.event.preventDefault();

	let products = {};
    let productList = []
    let freeProductList = []
    var time = new Date().getTime();
    var returnNum = time.toString();

    var totalReturnQty = 0
  	$('#return_table').find('> tbody > tr').each(function () {
    	var p = parseFloat($(this).closest("tr").find("td:eq(2) input[type='number']").val());
		totalReturnQty = parseFloat(totalReturnQty + p);
    });
	// alert(totalReturnQty)

    if( $('#return_table tr').length > 1 && totalReturnQty > 0){
            $('#errMsg').empty()
            // alert('rowCount')
            var returnTable = $('#return_table');
            $(returnTable).find('> tbody > tr').each(function () {

                let product = {}
				product["productId"]        = $(this).find("td:eq(11)").text();
				product["variantId"]        = $(this).find("td:eq(12)").text();
                product["productName"]      = $(this).find("td:eq(0)").text();
                product["quantity"]         = $(this).find("td:eq(1)").text();
                returnQuantity = $(this).closest("tr").find("td:eq(2) input[type='number']").val()
                product["returnQty"]        = $(this).closest("tr").find("td:eq(2) input[type='number']").val()
                product["price"]        	= $(this).find("td:eq(3)").text();
                price = $(this).find("td:eq(3)").text();
                returnPrice = returnQuantity * price;
                product["returnPrice"] = returnPrice;
                product["deductionPercent"] = $(this).closest("tr").find("td:eq(4) input[type='number']").val()
                product["total"]        	= $(this).closest("tr").find("td:eq(5) input[type='number']").val()
                product["deductionAmount"]  = $(this).closest("tr").find("td:eq(7) input[type='number']").val()
                product["discount"]        	= $(this).find("td:eq(8)").text();
                product["taxRev"]        	= $(this).find("td:eq(9)").text();
                discount = $(this).find("td:eq(8)").text();
                tax = $(this).find("td:eq(9)").text();
                returnDiscount = returnQuantity * discount;
                returnTax = returnQuantity * tax;
                product["returnDiscount"] = returnDiscount;
                product["returnTax"] = returnTax;
                product["taxAmount"]        = $(this).closest("tr").find("td:eq(10) input[type='number']").val()

                productList.push(product);

            })
            // console.log(productList)

            var freeReturnTable = $('#free_return_table');
            $(freeReturnTable).find('> tbody > tr').each(function () {

                let freeProduct = {}

                freeProduct["freeItemName"]      = $(this).find("td:eq(0)").text();
                freeProduct["requiredQuantity"]  = $(this).find("td:eq(1)").text();
                freeProduct["freeQuantity"]      = $(this).find("td:eq(2)").text();
                freeProduct["onProduct"]         = $(this).find("td:eq(3)").text();
                freeProduct["offerItemId"]       = $(this).find("td:eq(4)").text();


                freeProductList.push(freeProduct);

            })

  			products["invoiceNo"] = $('#search').val()
            products["clientId"] = $('#customerId').text()
            products["clientName"] = $('#customerName').text()
            products["orderDate"] = $('#orderDate').text()
  			products["note"] = $('#note').val()

            products["discountdeduction"] = $('#discountdeduction').val()
  			products["totalDeduction"] = $('#totaldeduction').val()
  			products["totalTax"] = $('#totaltax').val()
  			products["netReturn"] = $('#netreturn').val()
            products["productList"] = productList
            products["freeProductList"] = freeProductList
            products["returnNumber"] = returnNum
            products["storeId"] = $('#storeId').text()

            // console.log(products);
            salesReturn(products)
			// alert('Succs')


        }else{
            $('#errMsg').text("*Please add products to return.");
        }
}

function salesReturn(jsonData){

    // console.log(jsonData)
    // alert('Sux')
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
        url: "/sales-return",
        data: JSON.stringify(jsonData),
        dataType : "json",
        contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        success: function (response) {
	        // console.log(response.message)
	        // resetButton()
	        if(response.message == "Already returned."){
	        	$('body').loadingModal('destroy');
	            $.notify(response.message, {className: 'warn', position: 'bottom right'});
	        }else{
	        	$.notify(response.message, {className: 'success', position: 'bottom right'});
	        	$(location).attr('href','/sales-return-list');

	        }
        }
    });

}
