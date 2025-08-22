function productSubmitToServer(){
	this.event.preventDefault();
    var measurement = $('#measurement').val()
    // if (measurement!='')
    // {

	let products = {};

	products["productName"]			= $('#productname').val();
	// products["productLabel"]		= $('#productlabel').val();

	products["category"]			= $('#categoryid').val();
	products["category_name"]		= $('#categoryid :selected').text()

	products["subcategory"]			= $('#subcategoryname').val();
	products["subcategory_name"]	= $('#subcategoryname :selected').text();

	products["sku"]					= $('#productsku').val();
	products["type"]					= $('#type').val();
	// products["barcode"]				= $('#productbarcode').val();
	// products["supplier"]			= $('#suppliername').val();


	// products["available_discount"]	= $('#availablediscount').val();
	// products["discount"]			= $('#discount').val();
	// products["discount_type"]		= $('#discounttype').val();
	// products["offerItemId"]			= $('#offeritemid').val();
	// products["available_offer"]		= $('#availableoffer').val();
	// products["freeItemName"]		= $('#offeritemid option:selected').text()
	// products["requiredQuantity"]	= $('#requiredquantity').val();
	// products["freeQuantity"]		= $('#freequantity').val();
	// products["taxName"]				= $('#taxname').val();
	// products["isExcludedTax"]		= $('#taxexcluded').val();
	// products["tax"]					= $('#tax').val();

	// var filename = $('input[type=file]').val().split('\\').pop();

	// products["productImage"]		= filename;

	products["brand"]				= $('#brandname').val();
	products["manufacturer"]	    = $('#manufacturername').val();
	products["model"]				= $('#modelname').val();


	var T = $('#variant_table_data');
	var variants = [];
    // variant["variantMeasurement"] = $('#measurement').val();

   	var rowCount = $('#variant_table_data tr').length;

   	// var check = 0
   	if(rowCount > 0){
   		$(T).find('> tbody > tr').each(function (){
            let variant = {};
			variant["variantName"]			= $(this).find("td:eq(0)").text();
			variant["variantMeasurement"]	= $(this).find("td:eq(1)").text();
			variant["variantDescription"]	= $(this).find("td:eq(2)").text();

			// var filename = $(this).find("td:eq(3) input[type='file']").val().split('\\').pop()
			// variant["variantimage"]			= filename

            if($('#variant_taxDiscount_table_data tr').length!=1)
            {
                $('#variant_taxDiscount_table_data').find('> tbody > tr').each(function (){
                    if($(this).find("td:eq(0)").text()==variant["variantName"] ||$(this).find("td:eq(0)").text()=='All Variant')
                    {
                        variant["available_discount"]		= $(this).find("td:eq(1)").text();
                        variant["discount_type"]			= $(this).find("td:eq(2)").text();
                        variant["discount"]			        = $(this).find("td:eq(3)").text();
                        variant["taxName"]			        = $(this).find("td:eq(4)").text();
                        variant["tax"]			            = $(this).find("td:eq(5)").text();
                        variant["isExcludedTax"]            = $(this).find("td:eq(6)").text();
                    }
                    else{
                        variant["available_discount"]		= 'N/A';
                        variant["discount_type"]			= 'N/A';
                        variant["discount"]			        = 0;
                        variant["taxName"]			        = 'N/A';
                        variant["isExcludedTax"]			= 'N/A';
                        variant["tax"]			            = 0;
                    }
                });
            }
            else{
                variant["available_discount"]		= 'N/A';
                variant["discount_type"]			= 'N/A';
                variant["discount"]			        = 0;
                variant["taxName"]			        = 'N/A';
                variant["isExcludedTax"]			= 'N/A';
                variant["tax"]			            = 0;
            }


			variants.push(variant);
			// if(filename == ''){
			// 	$.notify('Please add variant image!', 'error')
			// 	check = 1
			// 	return false
			// }else{
			// 	check = 0

			// }
		})
   	}else{
        let variant = {};
		variant["variantName"]			= 'N/A';
		variant["variantDescription"]	= 'N/A';
		// variant["variantimage"]			= 'N/A';

        variants.push(variant);
    }

	products["variants"] = variants;
    // console.log(products)

    if (rowCount > 0) {
        submitToServer(products);
    }
    else {
        $.notify("Fill up the required fields.", { className: 'error', position: 'bottom right' });
    }

	// if(check == 1){
	// 	return false
	// }else{
	// 	// alert(1)

	// }




	//

    // }
    // else
    // {
    //     $.notify("Fill up required fields.", { className: 'error', position: 'bottom right' });
    // }
}



function submitToServer(jsonData) {

	// console.log(jsonData)
	// variantImageStore()

    $.ajax({
		type: "POST",
        contentType: "application/json",
        url: "/product-create",
        data: JSON.stringify(jsonData),
        dataType : "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {

            if(response.status!=400)
            {
                if($.isEmptyObject(response.error)){
                    $(location).attr('href','/product-list');


                }else{
                    $('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                    $.notify("Fill up required fields.", { className: 'error', position: 'bottom right' });
                }
            }
            else{
                $.notify(response.message, { className: 'error', position: 'bottom right' });
            }
        }
    });

}

function printErrorMsg (message) {

    $('#wrongproductname').empty();
    // $('#wrongproductlabel').empty();
	$('#wrongbrandname').empty();
	// $('#wrongmanufacturername').empty();
	// $('#wrongmodelname').empty();
	$('#wrongcategoryid').empty();
    $('#wrongtype').empty();


	if(message.productName == null){
		productName = ""
	}else{
		productName = message.productName[0]
	}

	// if(message.productLabel == null){
	// 	productLabel = ""
	// }else{
	// 	productLabel = message.productLabel[0]
	// }

	if(message.brand == null){
		brand = ""
	}else{
		brand = message.brand[0]
	}

	if(message.category == null){
		category = ""
	}else{
		category = message.category[0]
	}

    if (message.type == null) {
        type = ""
    } else {
        type = message.type[0]
    }

    $('#wrongproductname').append('<span id="">'+productName+'</span>');
    // $('#wrongproductlabel').append('<span id="">'+productLabel+'</span>');
    $('#wrongbrandname').append('<span id="">'+brand+'</span>');
    $('#wrongcategoryid').append('<span id="">'+category+'</span>');
    $('#wrongtype').append('<span id="">' + type + '</span>');

}



function resetButton(){
	// $('#subcategoryname').prop('selectedIndex',0)
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});

	$('#form_div').find('form')[0].reset();
	$('select[name="subcategoryname"]').empty();
	$('select[name="subcategoryname"]').append('\
		<option value="default" selected>Select subcategory</option>');
}



$(document).on('change', '#batchnumber', function (e) {
	e.preventDefault();

	var expiryDate = $('#batchnumber').val()
	$('#expirydate').val(expiryDate)
})



$(document).on('change', '#boxsize', function (e) {
	e.preventDefault();

	if($('#startingstock').val().length != 0){
		var boxSizeXstartStock = $('#boxsize').val() * $('#startingstock').val()
		$('#productincoming').val(boxSizeXstartStock)
	}else{
		var boxSize = $('#boxsize').val()
		$('#productincoming').val(boxSize)

	}

})



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

})



function imageStore(){

		$: FormData = new FormData($('#AddProductForm')[0]);

		$.ajax({
			type: "POST",
			url: "/product-image-create",
			data: FormData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				if(response.status == 200){
					// alert(response.imageName)
				}
			}
		});
}

function variantImageStore(){
	// this.event.preventDefault();

	var T = $('#variant_table_data');
    var i=0;
    var formData = new FormData();
	$(T).find('> tbody > tr').each(function (){
        // alert('hi')

		// var files = $(this).find("td:eq(3) input[type='file']").files;
		formData.append("variantImage"+i, $(this).find("td:eq(3) input[type='file']").get(0).files[0]);

        i++;
	})
    formData.append("index", i);
    // console.log(formData)
		$.ajax({
			type: "POST",
			url: "/variant-image-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				if(response.status == 200){
                    $(location).attr('href','/product-list');
                }
			}
		});
    // $(location).attr('href','/product-list');
}


function addVariant(){
	this.event.preventDefault();

	// var filename = $('input[type=file]').val().split('\\').pop();
	// var filename = $('#variantimage').val().split('\\').pop()

	var variantName			=	$("#variantname").val();
	var variantMeasurement	=	$("#measurement option:selected").val();
	var variantDescription  =   $("#variantdescription").val();
    // var variantimage = $("#image").val();
    // alert(variantimage)
    // alert(filename)
    // var variantimage = '<input id="image" value="' + myFile +'" name="variantimage" type="file" class="form-control" data-browse-on-zone-click="true" disabled>'

    if (variantMeasurement != ''){

        if (variantName == '') {variantName='N/A';}
        if (variantDescription == '') { variantDescription ='N/A';}


    	if($('#variant_table_data tr > td:first-child:contains('+variantName+')').length == 0){
    		$("#variant_table_data tbody").append(
			"<tr>" +
				"<td width='20%'>"+variantName+"</td>"+
				"<td width='20%'>"+variantMeasurement+"</td>"+
				"<td width='25%'>"+variantDescription+"</td>"+
                // "<td width='25%'>" + variantimage +"</td>"+
                // "<td width='25%'><input id='variantimage' value='' name='variantimage' type='file' class='form-control' data-browse-on-zone-click='true'></td>"+
				"<td width='10%'>"+
					"<button type='button' class='delete-btn btn btn-outline-danger btn-sm'><i class='fas fa-trash'></button>"+
				"</td>"+
			"</tr>");

            // alert($("#variantname").val())
			$("#measurement").selectpicker("refresh");
			$("#variantname").val("");
            // $("#variantimage").val("");
			$("#variantdescription").val("");

            $('#selectVariant').prop('disabled', false);
            $('#selectVariant').selectpicker('refresh');

            $('#selectVariant').append('<option value="'+variantName+'">'+variantName+'</option>');
            $('#selectVariant').selectpicker('refresh');
    	}else{
    		$.notify("Variant already added once!", {className: 'error', position: 'bottom right'});
    	}





    }else{
        $.notify("Measurement is required.", {className: 'error', position: 'bottom right'});
    }
}

function addDiscountTax(){
	this.event.preventDefault();

	var selectVariant	    =	$("#selectVariant option:selected").val();
	var availablediscount	=	$("#availablediscount option:selected").val();
    if(availablediscount==''){availablediscount='N/A'}
	var discounttype	    =	$("#discounttype option:selected").val();
    if(discounttype==''){discounttype='N/A'}
	var discount		    =	$("#discount").val();
    if(discount==''){discount=0}
	var taxname	            =	$("#taxname option:selected").val();
    if(taxname==''){taxname='N/A'}
	var tax                 =   $("#tax").val();
    if(tax==''){tax=0}
	var taxexcluded	        =	$("#taxexcluded option:selected").val();
    if(taxexcluded==''){taxexcluded='N/A'}
    // alert($("#variant_taxDiscount_table_data_body tr").length)
    // alert(selectVariant)
    // alert(availablediscount)
    // alert(discounttype)
    // alert(discount)
    // alert(taxname)
    // alert(tax)
    // alert(taxexcluded)

    if(discount!=0||tax!=0)
    {
        // let element = document.getElementById("variant_tax&discount_table_data");
        // element.removeAttribute("hidden");
        $( "#variant_taxDiscount_table_data" ).removeAttr("hidden");
        // if($('#variant_taxDiscount_table_data_body tr > td:first-child:contains('+selectVariant+')').length == 0 && $('#variant_taxDiscount_table_data_body tr > td:first-child:contains(All Variant)').length == 0){
            if($('#variant_taxDiscount_table_data_body tr > td:first-child:contains('+selectVariant+')').length == 0 && $('#variant_taxDiscount_table_data_body tr > td:first-child:contains(All Variant)').length == 0 && selectVariant!='All Variant' || $("#variant_taxDiscount_table_data_body tr").length==0 && selectVariant=='All Variant'){
            $("#variant_taxDiscount_table_data tbody").append(
                		"<tr>" +
                			"<td width='20%'>"+selectVariant+"</td>"+
                			"<td width='20%'>"+availablediscount+"</td>"+
                			"<td width='20%'>"+discounttype+"</td>"+
                			"<td width='25%'>"+discount+"</td>"+
                            "<td width='25%'>" + taxname +"</td>"+
                            "<td width='25%'>" + tax +"</td>"+
                            "<td width='25%'>" + taxexcluded +"</td>"+
                            "<td width='10%'><button type='button' class='delete-btn btn btn-outline-danger btn-sm'><i class='fas fa-trash'></button></td>"+
                		"</tr>");
            $("#selectVariant").val('').trigger('change');
            $("#selectVariant").selectpicker("refresh");
            $("#availablediscount").val('').trigger('change');
            $("#availablediscount").selectpicker("refresh");
            $("#discounttype").val('').trigger('change');
            $("#discounttype").selectpicker("refresh");
            $("#discount").val("");
            $("#taxname").val('').trigger('change');
            $("#taxname").selectpicker("refresh");
            $("#tax").val("");
            $("#taxexcluded").val('').trigger('change');
            $("#taxexcluded").selectpicker("refresh");
        }
        else{
            $.notify("Variant details already added once!", {className: 'error', position: 'bottom right'});
        }
    }else{
        $.notify("FillUp all fields.", {className: 'error', position: 'bottom right'});
    }
    // // var variantimage = '<input id="image" value="' + myFile +'" name="variantimage" type="file" class="form-control" data-browse-on-zone-click="true" disabled>'

    // if (variantMeasurement != ''){

    //     if (variantName == '') {variantName='N/A';}
    //     if (variantDescription == '') { variantDescription ='N/A';}


    // 	if($('#variant_table_data tr > td:first-child:contains('+variantName+')').length == 0){
    // 		$("#variant_table_data tbody").append(
	// 		"<tr>" +
	// 			"<td width='20%'>"+variantName+"</td>"+
	// 			"<td width='20%'>"+variantMeasurement+"</td>"+
	// 			"<td width='25%'>"+variantDescription+"</td>"+
    //             // "<td width='25%'>" + variantimage +"</td>"+
    //             "<td width='25%'><input id='variantimage' value='' name='variantimage' type='file' class='form-control' data-browse-on-zone-click='true'></td>"+
	// 			"<td width='10%'>"+
	// 				"<button type='button' class='delete-btn btn btn-outline-danger btn-sm'><i class='fas fa-trash'></button>"+
	// 			"</td>"+
	// 		"</tr>");

    //         // alert($("#variantname").val())
	// 		$("#measurement").selectpicker("refresh");
	// 		$("#variantname").val("");
    //         $("#variantimage").val("");
	// 		$("#variantdescription").val("");

    //         $('#selectVariant').prop('disabled', false);
    //         $('#selectVariant').selectpicker('refresh');

    //         $('#selectVariant').append('<option value="'+variantName+'('+variantMeasurement+')">'+variantName+' ('+variantMeasurement+')</option>');
    //         $('#selectVariant').selectpicker('refresh');
    // 	}else{
    // 		$.notify("Variant already added once!", {className: 'error', position: 'bottom right'});
    // 	}





    // }else{
    //     $.notify("Measurement is required.", {className: 'error', position: 'bottom right'});
    // }
}

$("#variant_table_data").on('click', '.delete-btn', function () {
    $(this).closest('tr').remove();
});
$("#variant_taxDiscount_table_data").on('click', '.delete-btn', function () {
    $(this).closest('tr').remove();
});


