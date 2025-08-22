

function fetchProduct(id){
	// alert(id)
	// e.preventDefault();
		$.ajax({
		type: "GET",
		url: "/product-edit/"+id,
		success: function(response){

			if (response.status == 200) {

                // if (response.product[0].subcategory == null){
                //     subcategory = ""
				// }else{
                //     subcategory = response.product[0].subcategory
                //     console.log(subcategory)
				// }

                // console.log(response.product[0].variant_image);
				// $('#edit_measurement').val(response.product[0].variant_measurement).change();
                // $('.measurement').val(response.product[0].variant_measurement).change();
                // alert(response.product[0].variant_measurement)
				$('#edit_productname').val(response.product[0].productName);
				// $('#edit_productlabel').val(response.product[0].productLabel);
				$('#edit_productbrand').val(response.product[0].brand).change();
				$('#edit_productmodel').val(response.product[0].model).change();
				$('#edit_productmanufacturer').val(response.product[0].manufacturer).change();
				$('#edit_categoryid').val(response.product[0].category).change();
                $('#edit_subcategoryname').val(response.product[0].subcategory).change();
				$('#edit_type').val(response.product[0].type).change()
				$('#edit_sku').val(response.product[0].sku);
				// $('#edit_barcode').val(response.product[0].barcode);
                // $('#edit_supplier').val(response.product[0].supplier).change();


				// $('#edit_productimage').val(response.product[0].productImage);
				// $('#edit_availablediscount').val(response.product[0].available_discount).change();
				// $('#edit_discounttype').val(response.product[0].discount_type).change();
				// $('#edit_discount').val(response.product[0].discount);
				// $('#edit_availableoffer').val(response.product[0].available_offer).change();
				// $('#edit_offeritemid').val(response.product[0].offerItemId).change();
				// $('#edit_freeitemname').val(response.product[0].freeItemName).change();
				// $('#edit_requiredquantity').val(response.product[0].requiredQuantity);
				// $('#edit_freequantity').val(response.product[0].freeQuantity);
				// $('#edit_taxname').val(response.product[0].taxName).change();
				// $('#edit_tax').val(response.product[0].tax);
				// $('#edit_taxexcluded').val(response.product[0].isExcludedTax).change();

                // $('#edit_variantname').val(response.product[0].variant_name);
                // $('#edit_variantdescription').val(response.product[0].variant_description);
                // $('#previousvariantimage').text(response.product[0].variant_image);
                // console.log(response.product[0].variant_image);

				// if(response.product[0].variant_description == 'default'){
				// 	var x = response.product[0].productImage
				// 	var productImage = '../uploads/products/'+x+''
				// 	$('#edit_productimage').attr("src", productImage);
				// 	$('#edit_defaultimage').attr("src", productImage);

				// }else{
				// 	var x = response.product[0].variant_image

				// 	var productImage = '../uploads/variants/'+x+''
				// 	var defaultX = '../uploads/products/default.jpg'
				// 	$('#edit_productimage').attr("src", productImage);
				// 	$('#edit_defaultimage').attr("src", defaultX);
				// }


				// $('#edit_productimage').attr("src", productImage);
				// $('#variantid').val(response.product[0].id);

			}
		}
	});
}

// var loadFile = function(event) {
// 	var image = document.getElementById('edit_productimage');
// 	image.src = URL.createObjectURL(event.target.files[0]);
// };


function productUpdateToServer(){
		this.event.preventDefault();

		productId = $('#productid').val();

		let products = {};

		products["productName"]			= $('#edit_productname').val();
		// products["productLabel"]		= $('#edit_productlabel').val();

		products["brand"]			= $('#edit_productbrand').val();
		products["brand_name"]	= $('#edit_productbrand option:selected').text();

        products["model"]       = $('#edit_productmodel').val();
        products["model_name"]  = $('#edit_productmodel option:selected').text();

        products["manufacturer"]      = $('#edit_productmanufacturer').val();
        products["manufacturer_name"] = $('#edit_productmanufacturer option:selected').text();

		products["category"]			= $('#edit_categoryid').val();
		products["category_name"]		= $('#edit_categoryid option:selected').text();

		products["subcategory"]			= $('#edit_subcategoryname').val();
		products["subcategory_name"]	= $('#edit_subcategoryname option:selected').text();


		products["type"]				= $('#edit_type').val();
		products["sku"]					= $('#edit_sku').val();
		// products["barcode"]				= $('#edit_barcode').val();

		// products["supplier"]			= $('#edit_supplier').val();

		// products["available_discount"]	= $('#edit_availablediscount').val();
		// products["discount"]			= $('#edit_discount').val();
		// products["discount_type"]		= $('#edit_discounttype').val();
		// products["offerItemId"]			= $('#edit_offeritemid').val();
		// products["available_offer"]		= $('#edit_availableoffer').val();
		// products["freeItemName"]		= $('#edit_offeritemid option:selected').text();
		// products["requiredQuantity"]	= $('#edit_requiredquantity').val();
		// products["freeQuantity"]		= $('#edit_freequantity').val();
		// products["taxName"]				= $('#edit_taxname').val();
		// products["isExcludedTax"]		= $('#edit_taxexcluded').val();
		// products["tax"]					= $('#edit_tax').val();

		// products["variant_name"]		= $('#edit_variantname').val();
		// products["variant_measurement"]					= $('#edit_measurement').val();
		// products["variant_description"]					= $('#edit_variantdescription').val();
        // products["variant_image"] = $('#variantimage').val().split('\\').pop();



		// var filename = $('input[type=file').val().split('\\').pop();

		// var defaultImage = $('#input-b1').val().split('\\').pop();
		// var variantImaege = $('#edit_productimage').val().split('\\').pop();


		 // products["productImage"]		= filename
		// products["defaultImage"]		= defaultImage
		// products["variantImaege"]		= variantImaege

    	// submitToServer(products, productId);

	}


// function submitToServer(jsonData, productId) {

// 	// console.log(jsonData);

// 	// imageUpdate()

//     $.ajax({
// 		type: "PUT",
//         contentType: "application/json",
//         url: "/product-edit/"+productId,
//         data: JSON.stringify(jsonData),
//         dataType : "json",
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (response) {
//         	// console.log(response.message);
//         	// imageUpdate()
//         	// $.notify(response.message);

//         	if($.isEmptyObject(response.error)){
//             	// imageUpdate()
//                  $.notify(response.message, 'Success');
//                  // resetButton()
//           		$(location).attr('href','/product-list');

//              }else{
//              	// console.log(response.error)
//                  printErrorMsg(response.error);
//              }

//         }
//     });
// }



$(document).on('submit', '#EditProductForm', function (e){
	e.preventDefault();
    $('#edit_tax').prop('disabled', false);
    $('#edit_taxexcluded').prop('disabled', false);
	var productId = $('#productid').val();
    // var variantId = $('#variantid').val();
    var measurement = $('#edit_measurement').val();
    // var variantimage = $('#variantimage').val().split('\\').pop();
    // alert($('#edit_subcategoryname').val())
    if (measurement != '')
    {
    // alert(measurement)
	// console.log(variantId)

	let EditFormData = new FormData($('#EditProductForm')[0]);
        // console.log(EditFormData)

	EditFormData.append('_method', 'PUT');
    // EditFormData.append('variantId', variantId);
    // EditFormData.append('variant_image', variantimage);
    // EditFormData.append('measurement', measurement);


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
		url: "/product-edit/"+productId,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function (response) {
	    	// console.log(response.message);
	    	// imageUpdate()
	    	// $.notify(response.message);
	    	if($.isEmptyObject(response.error)){
	        	// imageUpdate()
	             // $.notify(response.message, 'success');
	             // resetButton()
                 $(location).attr('href','/product-list');
                 variantImageStore()
                }else{
                    // console.log(response.error)
                    $('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }

	    }
	});

    }
    else
    {
        $.notify("Fill up required fields.", { className: 'error', position: 'bottom right' });
    }
});

function imageUpdate(){

	var productId = $('#productid').val();
	$: FormData = new FormData($('#EditProductForm')[0]);

	$.ajax({
		type: "POST",
		url: "/product-image-update/"+productId,
		data: FormData,
		contentType: false,
		processData: false,
		headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
		success: function(response){
			if(response.status == 200){
				// $('#ImageModal').modal('hide');
				// console.log(response.imageName);
				// $('#productimage').val(response.imageName);
				// fetchProduct(productId);
				// alert(response.message)

			}
		}
	});

}

function printErrorMsg (message) {
    // $(".print-error-msg").find("ul").html('');
    // $(".print-error-msg").css('display','block');

    // $.each( message, function( key, item ) {
        // $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        $('#edit_wrongproductname').empty();
        // $('#edit_wrongproductlabel').empty();
		$('#edit_wrongbrandname').empty();
        $('#edit_wrongmodelname').empty();
        $('#edit_wrongmanufacturername').empty();
		$('#edit_wrongcategoryid').empty();
		$('#edit_wrongstartingstock').empty();
		$('#edit_wrongunit').empty();
		$('#edit_wrongproductincoming').empty();
		$('#edit_wrongmrp').empty();
		$('#edit_wrongprice').empty();
		$('#edit_wrongpurchasedate').empty();
        $('#wrongtype').empty();


		if(message.productname == null){
			productname = ""
		}else{
			productname = message.productname[0]
		}

		// if(message.productlabel == null){
		// 	productlabel = ""
		// }else{
		// 	productlabel = message.productlabel[0]
		// }

		if(message.productbrand == null){
			productbrand = ""
		}else{
			productbrand = message.productbrand[0]
		}

        if(message.productmodel == null){
            productmodel = "";
        } else {
            productmodel = message.productmodel[0];
        }

        if(message.productmanufacturer == null){
            productmanufacturer = "";
        } else {
            productmanufacturer = message.productmanufacturer[0];
        }

		if(message.categoryid == null){
			categoryid = ""
		}else{
			categoryid = message.categoryid[0]
		}

		if(message.startingstock == null){
			startingstock = ""
		}else{
			startingstock = message.startingstock[0]
		}

		if(message.unit == null){
			unit = ""
		}else{
			unit = message.unit[0]
		}

		if(message.productincoming == null){
			productincoming = ""
		}else{
			productincoming = message.productincoming[0]
		}

		if(message.sellingprice == null){
			sellingprice = ""
		}else{
			sellingprice = message.sellingprice[0]
		}
		if(message.purchasecost == null){
			purchasecost = ""
		}else{
			purchasecost = message.purchasecost[0]
		}
		if(message.purchasedate == null){
			purchasedate = ""
		}else{
			purchasedate = message.purchasedate[0]
		}

        if (message.type == null) {
            type = ""
        } else {
            type = message.type[0]
        }

        $('#edit_wrongproductname').append('<span id="">'+productname+'</span>');
        $('#edit_wrongproductlabel').append('<span id="">'+productlabel+'</span>');
        $('#edit_wrongbrandname').append('<span id="">'+productbrand+'</span>');
        $('#edit_wrongmodelname').append('<span id="">'+productmodel+'</span>');
        $('#edit_wrongmanufacturername').append('<span id="">'+productmanufacturer+'</span>');
        $('#edit_wrongcategoryid').append('<span id="">'+categoryid+'</span>');
        $('#edit_wrongstartingstock').append('<span id="">'+startingstock+'</span>');
        $('#edit_wrongunit').append('<span id="">'+unit+'</span>');
        $('#edit_wrongproductincoming').append('<span id="">'+productincoming+'</span>');
        $('#edit_wrongmrp').append('<span id="">'+sellingprice+'</span>');
        $('#edit_wrongprice').append('<span id="">'+purchasecost+'</span>');
        $('#edit_wrongpurchasedate').append('<span id="">'+purchasedate+'</span>');
        $('#wrongtype').append('<span id="">' + type + '</span>');

    // });
}

function deleteProduct() {

	var productId = $('#productid').val();
    $.ajax({
        type: "get",
        url: "/product-delete/"+productId,
        success: function (response) {
            // console.log(response);
            if(response.status==200){
                $.notify(response.message, {className: 'success', position: 'bottom right'});
                $(location).attr('href','/product-list');
            }
            else{
                $.notify(response.message, {className: 'error', position: 'bottom right'});
            }

        }
    });
}






