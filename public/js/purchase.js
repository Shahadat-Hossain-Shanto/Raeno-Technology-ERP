// $('#totalpricepurchase').on('click', function (e) {
// 	e.preventDefault();

// 	var total = $("#receivedqty").val() * $("#unitprice").val();

// 	$("#totalpricepurchase").val(total);

// });

	function productAddToTable() {
		this.event.preventDefault();
		var productId		=	$("#product option:selected").val();
		var productName     =   $("#product option:selected").text();
	    var quantity  		=   parseInt($("#receivedqty").val());
	    var unitPrice       =   parseFloat($("#unitprice").val());
	    var mrp       		=   $("#mrp").val();
        var total           = parseFloat(quantity * unitPrice);
	    // var total			= 	$("#totalpricepurchase").val();

	    if($("#variant").find("option:selected").text() == "Select variant"){
	    	var variant = "default"
	    	var variantId = 0
	    }else{
	    	var variant   =   $("#variant").find("option:selected").text();
        	var variantId =   $("#variant").find("option:selected").val();
	    }

	    // var batchName =   $('#purchase_table tr > td:first-child:contains('+productId+')').closest("tr").find("td:eq(4)").text()

        let sameProduct = 0;
        $('#purchase_table').find('> tbody > tr').each(function () {
            let tableProductName	= $(this).find("td:eq(1)").text();
            let tableVariantName	= $(this).find("td:eq(2)").text();
            if(tableProductName==productName && tableVariantName==variant){
                sameProduct = 1;
            }
        });
		if(productId != 'option_select' && quantity.length != 0 && unitPrice.length != 0 && total.length != 0 && mrp.length != 0 && variantId != 0){
	    	$('#errorMsg').empty()
		    if(sameProduct == 0){
                $("#purchase_table tbody").append(
                    "<tr>" +
                    "<td hidden=true >" + productId + "</td>" +
                    "<td>" + productName + "</td>" +
                    "<td>" + variant + "</td>" +
                    "<td>" + quantity + "</td>" +
                    "<td>" + unitPrice + "</td>" +
                    "<td>" + mrp + "</td>" +
                    "<td class='hidden'>" + variantId + "</td>" +
                    "<td>" + total + "</td>" +
                    "<td>" +
                    "<button type='button' class='delete-btn btn btn-outline-danger btn-sm'><i class='fas fa-trash'></button>" +
                    "</td>" +
                    "</tr>");
			}
            else{
                $('#purchase_table').find('> tbody > tr').each(function () {
                    let tableProductName	= $(this).find("td:eq(1)").text();
                    let tableVariantName	= $(this).find("td:eq(2)").text();
                    if(tableProductName==productName && tableVariantName==variant){
                        let previousQty = parseInt($(this).find("td:eq(3)").text());
                        $(this).find("td:eq(3)").text(quantity+previousQty);
                        let previousCost = parseFloat($(this).find("td:eq(7)").text());
                        $(this).find("td:eq(7)").text(total+previousCost);
                    }
                });
            }
            resetButton()
		}else{
			// $.notify("Invalid selection", "error");
			$('#errorMsg').text('Please fill up the required fields.')
			// $("#errorMsg").fadeOut(4000);
		}
	}


	$("#purchase_table").on('click', '.delete-btn', function () {
	    $(this).closest('tr').remove();
	    subTotal()
	});



	function productListSubmitToServer() {
		this.event.preventDefault();

		let purchaseProducts= {};

		if($("#discount").val().length == 0){
			var discount = 0;
		}else{
			var discount = parseFloat($("#discount").val());
		}

		if($("#othercost").val().length == 0){
			var otherCost = 0;
		}else{
			var otherCost = parseFloat($("#othercost").val());
		}

		var totalPrice = parseFloat($("#totalprice").val())
		var grandTotal = (totalPrice - discount) + otherCost

		purchaseProducts["supplierId"] 		= $("#supplierid").val();
		purchaseProducts["store"] 			= $("#store").find("option:selected").text()
        purchaseProducts["poNumber"]        = $("#poNumber").val();
		purchaseProducts["totalPrice"] 		= $("#totalprice").val();
		purchaseProducts["discount"] 		= discount
		purchaseProducts["otherCost"] 		= otherCost
		purchaseProducts["grandTotal"] 		= grandTotal
		purchaseProducts["purchaseDate"]	= $("#purchasedate").val();
		purchaseProducts["purchaseNote"] 	= $("#purchasenote").val();

		var T = $('.table');
		var purchaseProductList=[];

	    $(T).find('> tbody > tr').each(function () {
	    	let purchaseProduct= {};
	    	purchaseProduct["productId"]	= $(this).find("td:eq(0)").text();
	    	purchaseProduct["productName"]	= $(this).find("td:eq(1)").text();
	    	purchaseProduct["variant"]		= $(this).find("td:eq(2)").text();
	    	purchaseProduct["quantity"]		= $(this).find("td:eq(3)").text();
	    	purchaseProduct["unitPrice"]	= $(this).find("td:eq(4)").text();
	    	purchaseProduct["mrp"]			= $(this).find("td:eq(5)").text();
	    	purchaseProduct["variantId"]	= $(this).find("td:eq(6)").text();
	    	purchaseProduct["totalPrice"]	= $(this).find("td:eq(7)").text();
	    	purchaseProductList.push(purchaseProduct);


	    });

	    purchaseProducts["productList"]=purchaseProductList;

	    if(purchaseProductList.length>0){
	    	$('#errorMsg').empty()
	    	$('#errorMsg1').empty()

	    	// console.log(purchaseProducts);
	    	submitToServer(purchaseProducts);
	    }else{
	    	// $.notify("Please purchase at least one product!");
	    	$('#errorMsg1').text('Please purchase at least one product.')
	    }

	}

    $('#discount').on('change', function () {

        if ($("#discount").val().length == 0) {
            var discount = 0;
        } else {
            var discount = parseFloat($("#discount").val());
        }

        if ($("#othercost").val().length == 0) {
            var otherCost = 0;
        } else {
            var otherCost = parseFloat($("#othercost").val());
        }

        var totalPrice = parseFloat($("#totalprice").val())
        var grandTotal = (totalPrice - discount) + otherCost
        // alert(grandTotal)
        $("#grandtotal").val(grandTotal)
    })

    $('#othercost').on('change', function () {

        if ($("#discount").val().length == 0) {
            var discount = 0;
        } else {
            var discount = parseFloat($("#discount").val());
        }

        if ($("#othercost").val().length == 0) {
            var otherCost = 0;
        } else {
            var otherCost = parseFloat($("#othercost").val());
        }

        var totalPrice = parseFloat($("#totalprice").val())
        var grandTotal = (totalPrice - discount) + otherCost
        // alert(grandTotal)
        $("#grandtotal").val(grandTotal)
    })

	$('#addProduct').on('click', function(){
		subTotal();
        if ($("#discount").val().length == 0) {
            var discount = 0;
        } else {
            var discount = parseFloat($("#discount").val());
        }

        if ($("#othercost").val().length == 0) {
            var otherCost = 0;
        } else {
            var otherCost = parseFloat($("#othercost").val());
        }

        var totalPrice = parseFloat($("#totalprice").val())
        var grandTotal = (totalPrice - discount) + otherCost
        // alert(grandTotal)
        $("#grandtotal").val(grandTotal)
	})

	function subTotal(){
		var rowCount = $('.table tr').length

		var T = $('.table');
		var s = 0;
	    $(T).find('> tbody > tr').each(function () {

	    	var p = parseFloat($(this).find("td:eq(7)").text());

            // console.log(p);
			s = s + p;

			$('#totalprice').val(s);

	    });

	    // alert(rowCount)
	    if(rowCount == 1){
	    	$('#totalprice').val(s);
	    }
	}





	function submitToServer(jsonData) {

	    // let url = baseUrl +'/api/distributor-requisition'

	    // console.log(jsonData)

	    var supplier = $("#supplierid option:selected").val();
	    var store = $("#store option:selected").val();
        var poNum = $("#poNumber").val();
	    var total = $("#totalprice").val();
	    var discount = $("#discount").val();
	    var purchaseDate = $("#purchasedate").val();

	    if(supplier != 'option_select' && store != 'option_select' && poNum.length != 0 && total.length != 0 && purchaseDate.length != 0 ){
	    // if(supplier != 'option_select' && store != 'option_select'  && total.length != 0 && purchaseDate.length != 0 ){
	    	$('#errorMsg').empty()
	    	// console.log('Submit')
            // console.log(jsonData)

	    	$.ajax({
	    	// 	ajaxStart: $('body').loadingModal({
			//   position: 'auto',
			//   text: 'Please Wait',
			//   color: '#fff',
			//   opacity: '0.7',
			//   backgroundColor: 'rgb(0,0,0)',
			//   animation: 'foldingCube'
			// }),
		        type: "POST",
		        contentType: "application/json",
		        url: "/purchase-create",
		        data: JSON.stringify(jsonData),
		        dataType : "json",
		        headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            },
		        success: function (response) {
                    // console.log(response);
                    // if (response.status==200)
                    // {
                    //     // console.log(response.error.poNumber);
                    //     // $("#errorMsg1").text(response.error.poNumber);
                    // }
                    if (response.status == 400) {
		        	// $.notify(response.message, {className: 'success', position: 'bottom right'});
		                $(location).attr('href','/purchase-list');
                    }


		        }
		    });

	    }else{
	    	// $.notify('Invalid Selection')
	    	// $('#errorMsg').text('Invalid Selection.')
	    	$('#errorMsg1').text('Please fill up the required fields.')


	    }


	}


	function resetButton(){
		// $('#form_div').find('form')[0].reset();

		$('form').on('reset', function() {
		  	setTimeout(function() {
			    $('.selectpicker').selectpicker('refresh');
		  	});
		});
		$('#form_div1').find('form')[0].reset();
		// $("#purchase_table").find("tr:gt(0)").remove();
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
	$('#purchasedate').attr('value', today);

    var time = new Date().getTime();
    var poNumber = time.toString();
    $('#poNumber').attr('value', poNumber);

})

$('#product').on('change', function() {
    var productId = $(this).val()
    var productName = $("#product").find("option:selected").text()
    $('#variant').prop("disabled", false);

    // alert(productId)

    $.ajax({
        type: "GET",
        url: "/product-wise-variant/"+productId,
        dataType:"json",
        success: function(response){
            // console.log(response.data)
            // alert(response.message)

            $('#variant').empty();
            $('#variant').append('<option value="default" selected disabled>Select variant</option>');
            $.each(response.data, function(key, item){
                 $('#variant').append('<option value="'+ item.id +'">'+ item.variant_name +'</option>');
            });

            $('#variant').appendTo('#variant').selectpicker('refresh');

        }
    })

})

$(document).on('change','#mrp', function(){
    if(parseFloat($('#unitprice').val())>parseFloat($('#mrp').val())){
        $('#mrp').val('')
        $.notify('MRP can not be less than Unit Price')
    }
});

$('#unitprice').change(function (e) {
    e.preventDefault();
    if(parseFloat($('#unitprice').val())>parseFloat($('#mrp').val())){
        $('#unitprice').val('')
        $.notify('Unit Price can not be greater than MRP ')
    }
});

$(document).ready(function () {

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!

    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    today = yyyy + '-' + mm + '-' + dd;
    // console.log(today)
    $('#purchasedate').attr('value', today);

    var time = new Date().getTime();
    var poNumber = time.toString();
    $('#poNumber').attr('value', poNumber);

})
// $('#store').on('change', function() {
//     var storeId = $(this).val()
//     var storeName = $("#store").find("option:selected").text()


//     // fetchStoreStock(storeId)

//     if(storeId == "Warehouse"){
//     	// alert(storeId)
//     	$.ajax({
// 	        type: "GET",
// 	        url: "/inventory-wise-product",
// 	        dataType:"json",
// 	        success: function(response){
// 	            // console.log(response.data)
// 	            // alert(response.message)

// 	            $('#product').empty();
// 	            $('#product').append('<option value="default" selected disabled>Select product</option>');
// 	            $.each(response.data, function(key, item){
// 	                 $('#product').append('<option value="'+ item.productId +'">'+ item.productName +'</option>');
// 	            });

// 	            $('#product').appendTo('#product').selectpicker('refresh');

// 	        }
// 	    })
//     }else{
//     	// alert(storeId)
//     	$.ajax({
// 	        type: "GET",
// 	        url: "/store-wise-product/"+storeId,
// 	        dataType:"json",
// 	        success: function(response){
// 	            // console.log(response.data)
// 	            // alert(response.message)

// 	            $('#product').empty();
// 	            $('#product').append('<option value="default" selected disabled>Select product</option>');
// 	            $.each(response.data, function(key, item){
// 	                 $('#product').append('<option value="'+ item.id +'">'+ item.productName +'</option>');
// 	            });

// 	            $('#product').appendTo('#product').selectpicker('refresh');

// 	        }
// 	    })
//     }

// })



