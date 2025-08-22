$(document).ready(function () {
    $(document).on('change', '#search', function (e) {
		e.preventDefault();
		// alert('Success')

		if( $('#search').val() == null){
			// alert('Success')
			var msg = "Purchase Order no. is required."
			$('#wrongsearch').empty();
	    	$('#wrongsearch').append('<span id="">'+msg+'</span>');
		}else{
			// alert('ok')
			var poNo = $('#search').val()
			// alert(poNo)
			$.ajax({
				type: "GET",
				url: "/purchase-return-create/"+poNo,
				dataType:"json",
				success: function(response){
					console.log(response)
					// $('#errMsg').empty()
					if(response.message == 'No data found.'){
						$('#wrongsearch').empty();
        				$('#wrongsearch').append('<span id="">'+response.message+'</span>');
					}
					else{
						$('#wrongsearch').empty();
					// 	// alert(response.message)
						// console.log(response.data)

						supplierId = response.data[0].supplierId
						$('#suppliername').val('')
						var supplier = response.supplierName
						$('#suppliername').val(supplier)
						$('#supplierid').val(supplierId)

						$('#date').val('')
						var date = response.data[0].purchaseDate
						$('#date').val(date)

						$('#storeid').val('')
						var storeid = response.storeId
						$('#storeid').val(storeid)

						$('#store').val('')
						var store = response.data[0].store
						$('#store').val(store)

                        $('#discount').val('')
						var discount = response.data[0].discount
						$('#discount').val(parseFloat(discount).toFixed(2))

                        $('#other_cost').val('')
						var other_cost = response.data[0].other_cost
						$('#other_cost').val(parseFloat(other_cost).toFixed(2))

						$("#return_table").find("tr:gt(0)").remove();
						$.each(response.data, function(key, item) {

							// var totalPrice = parseFloat(item.totalPrice)
							// var qty = parseFloat(item.quantity)
							// var price = parseFloat(totalPrice/qty)
							var price = parseFloat(item.unitPrice).toFixed(2)

							if(item.discount == null){
								var discount = 0;
							}else{
								// var discount = parseFloat(item.discount);
								// var discount = parseFloat(discount/qty)
								var discount = parseFloat(item.discount).toFixed(2)
							}

							if(item.other_cost == null){
								var other_cost = 0;
							}else{
								var other_cost = parseFloat(item.other_cost).toFixed(2)
								// var tax = parseFloat(other_cost/qty);
							}

							$('#return_table_body').append('\
							<tr>\
								<td>'+item.productName+'</td>\
								<td>'+item.quantity+'</td>\
								<td><input type="number" class="form-control" name="" id="returnqty" value="0"></td>\
								<td>'+parseFloat(price).toFixed(2)+'</td>\
								<td><input type="number" class="form-control" name="" id="deduction" value=""></td>\
								<td><input type="number" class="form-control" name="" id="total" value="0.00" disabled></td>\
								<td class="hidden"></td>\
								<td class="hidden"><input type="number" class="form-control" name="" id="deduction" value="0" disabled></td>\
								<td class="hidden"><input type="number" class="form-control" name="" id="taxamount" value="0" disabled></td>\
			        			<td class="hidden">'+item.productId+'</td>\
                            </tr>');

								// <td>'+discount.toFixed(2)+'</td>\
								// <td>'+other_cost.toFixed(2)+'</td>\

						})
					}
				}
			})
		}
	})
})


$(document).on('keyup', '#returnqty', function(e) {
  // alert( "Handler for .keyup() called." );
  var returnQty = $(this).val()
  var soldQty = parseFloat($(this).closest("tr").find("td:eq(1)").text())

  var price = parseFloat($(this).closest("tr").find("td:eq(3)").text())
  var totalPrice = parseFloat(returnQty*price).toFixed(2)

  var otherCost = parseFloat($(this).closest("tr").find("td:eq(9)").text()).toFixed(2)



  if(returnQty <= soldQty && returnQty >= 0){
  	$(this).closest("tr").find("td:eq(5) input[type='number']").val(parseFloat(totalPrice).toFixed(2))

  	//Deduction
    var deduction = $(this).closest("tr").find("td:eq(4) input[type='number']").val()
	var deductionAmount = parseFloat(returnQty*price*(deduction/100)).toFixed(2)
	$(this).closest("tr").find("td:eq(7) input[type='number']").val(deductionAmount)

    var totalAfterDeduction = totalPrice - deductionAmount
	$(this).closest("tr").find("td:eq(5) input[type='number']").val(parseFloat(totalAfterDeduction).toFixed(2))

    var totalDeduction = 0
  	$('#return_table').find('> tbody > tr').each(function () {
    	var deduct = $(this).closest("tr").find("td:eq(7) input[type='number']").val();
		totalDeduction = parseFloat(totalDeduction) + parseFloat(deduct)
		// console.log(totalDeduction)

		$('#totaldeduction').val(parseFloat(totalDeduction).toFixed(2));
    });


    //Tax
 //    var taxAmount = parseFloat(otherCost)
 //    var totalTaxReturn = parseFloat(taxAmount)
 //    $(this).closest("tr").find("td:eq(10) input[type='number']").val(totalTaxReturn)

 //    var totalAfterTaxReturn = totalAfterDeduction + totalTaxReturn
	// $(this).closest("tr").find("td:eq(5) input[type='number']").val(totalAfterTaxReturn.toFixed(2))

 //    var totalTax = 0
 //  	$('#return_table').find('> tbody > tr').each(function () {
 //    	var taXx = $(this).closest("tr").find("td:eq(10) input[type='number']").val();
	// 	totalTax = parseFloat(totalTax) + parseFloat(taXx)
	// 	// console.log(totalDeduction)

	// 	$('#totaltax').val(totalTax.toFixed(2));
 //    });

  	//total
    var total = 0
  	$('#return_table').find('> tbody > tr').each(function () {
    	var p = parseFloat($(this).closest("tr").find("td:eq(5) input[type='number']").val());
		total = parseFloat(total + p);
		// alert(total)
		$('#netreturn').val(parseFloat(total).toFixed(2));
    });

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

		var deductionAmount = parseFloat(returnQty*price*(deduction/100)).toFixed(2)
		$(this).closest("tr").find("td:eq(7) input[type='number']").val(deductionAmount)


		var soldQty = parseFloat($(this).closest("tr").find("td:eq(1)").text())
		var totalPrice = parseFloat(returnQty*price).toFixed(2)
		var otherCost = parseFloat($(this).closest("tr").find("td:eq(9)").text()).toFixed(2)


		// var totalPrice = parseFloat($(this).closest("tr").find("td:eq(5) input[type='number']").val())


		var totalAfterDeduction = totalPrice - deductionAmount
		$(this).closest("tr").find("td:eq(5) input[type='number']").val(parseFloat(totalAfterDeduction).toFixed(2))

		// $('#totaldeduction').val(deductionAmount.toFixed(2));

	    var totalDeduction = 0
	  	$('#return_table').find('> tbody > tr').each(function () {
	    	var deduct = $(this).closest("tr").find("td:eq(7) input[type='number']").val();
			totalDeduction = parseFloat(totalDeduction) + parseFloat(deduct)
			// console.log(totalDeduction)

			$('#totaldeduction').val(parseFloat(totalDeduction).toFixed(2));
	    });



		var total = 0
	  	$('#return_table').find('> tbody > tr').each(function () {
	    	var p = parseFloat($(this).closest("tr").find("td:eq(5) input[type='number']").val());
			total = parseFloat(total + p);
			// alert(total)
			$('#netreturn').val(parseFloat(total).toFixed(2));
	    });
	}


});

function collectingData() {
	this.event.preventDefault();

	let products = {};
    let productList = []
    // let freeProductList = []
    var time = new Date().getTime();
    var returnNum = time.toString();

    var totalReturnQty = 0
  	$('#return_table').find('> tbody > tr').each(function () {
    	var p = parseFloat($(this).closest("tr").find("td:eq(2) input[type='number']").val());
		totalReturnQty = parseFloat(totalReturnQty + p).toFixed(2);
    });
	// alert(totalReturnQty)

    if( $('#return_table tr').length > 1 && totalReturnQty > 0){
            $('#errMsg').empty()
            // alert('rowCount')
            var returnTable = $('#return_table');
            $(returnTable).find('> tbody > tr').each(function () {

                let product = {}
				product["productId"]        = $(this).find("td:eq(9)").text();
                product["productName"]      = $(this).find("td:eq(0)").text();
                product["quantity"]         = $(this).find("td:eq(1)").text();
                product["returnQty"]        = $(this).closest("tr").find("td:eq(2) input[type='number']").val()
                product["price"]        	= $(this).find("td:eq(3)").text();
                product["deductionPercent"] = $(this).closest("tr").find("td:eq(4) input[type='number']").val()
                product["total"]        	= $(this).closest("tr").find("td:eq(5) input[type='number']").val()
                product["deductionAmount"]  = $(this).closest("tr").find("td:eq(7) input[type='number']").val()
                // product["discount"]        	= $(this).find("td:eq(8)").text();
                // product["otherCost"]        = $(this).find("td:eq(9)").text();
                product["taxAmount"]        = $(this).closest("tr").find("td:eq(8) input[type='number']").val()

                product["discount"]        = $('#discount').val()
                product["otherCost"]        = $('#other_cost').val()
                productList.push(product);

            })

            // var freeReturnTable = $('#free_return_table');
            // $(freeReturnTable).find('> tbody > tr').each(function () {

            //     let freeProduct = {}

            //     freeProduct["freeItemName"]      = $(this).find("td:eq(0)").text();
            //     freeProduct["requiredQuantity"]  = $(this).find("td:eq(1)").text();
            //     freeProduct["freeQuantity"]      = $(this).find("td:eq(2)").text();
            //     freeProduct["onProduct"]         = $(this).find("td:eq(3)").text();
            //     freeProduct["offerItemId"]       = $(this).find("td:eq(4)").text();


            //     freeProductList.push(freeProduct);

            // })

  			products["poNo"] = $('#search').val()
  			products["supplierId"] = $('#supplierid').val()
  			products["supplierName"] = $('#suppliername').val()
  			products["storeId"] = $('#storeid').val()
  			products["date"] = $('#date').val()
  			products["note"] = $('#note').val()

  			products["totalDeduction"] = $('#totaldeduction').val()
  			// products["totalTax"] = $('#totaltax').val()
  			products["netReturn"] = $('#netreturn').val()

            products["productList"] = productList
            // products["freeProductList"] = freeProductList
            products["returnNumber"] = returnNum

            // console.log(products);
            purchaseReturn(products)
			// alert('Succs')


        }else{
            $('#errMsg').text("*Please add products to return.");
        }
}


function purchaseReturn(jsonData){

    console.log(jsonData)
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
        url: "/purchase-return",
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
	        	// alert(response.message)
	        	$('body').loadingModal('destroy');
	            $.notify(response.message, {className: 'error', position: 'bottom right'});
	        }else{
	        	// alert(response.message)
	        	$.notify(response.message, {className: 'success', position: 'bottom right'});
	        	$(location).attr('href','/purchase-return-list');
	        }
        }
    });

}
