function productAddToTable() {
    this.event.preventDefault();

    var store       =   $("#store").find("option:selected").text();
    var storeId     =   $("#store").val();
    var product     =   $("#product").find("option:selected").text();
    var productId   =   $("#product").val();
    var quantity    =   parseInt($("#qty").val());
    var unitPrice   =   $("#unitprice").val();
    var mrp         =   $("#mrp").val();

    if($("#variant").find("option:selected").text() == "Select variant"){
        var variant   =   "default";
         var variantId   =   0;
    }else{
        var variant   =   $("#variant").find("option:selected").text();
        var variantId =   $("#variant").find("option:selected").val();
    }
    let sameProduct = 0;
    $('#productin_table').find('> tbody > tr').each(function () {
		let tableStoreName	= $(this).find("td:eq(0)").text();
        let tableProductName	= $(this).find("td:eq(2)").text();
		let tableVariantName	= $(this).find("td:eq(4)").text();
        if(tableStoreName==store && tableProductName==product && tableVariantName==variant){
            sameProduct = 1;
        }
    });

        if(productId != "default" && storeId != "default" && quantity.length != 0 && quantity > 0 && unitPrice.length != 0 && mrp.length != 0 && variantId != 0){
            let productType = '';
            let barcode = '';
            let row = $("#productin_table_body tr").length;
            $('#errorMsg').empty()
            $('#errorMsg1').empty()
            $.ajax({
                type: "get",
                url: "/product-info/"+productId,
                success: function (response) {
                    if(response.status==200){
                        productType=(response.productType);
                        barcode=(response.barcode);
                        function identification_Number(){
                            if(productType=='Serialize'){
                                return '<button id="add_serial" style="background: transparent;" value="'+row+'"><i class="fas fa-plus-circle" style="color: blue;"></i></button>';
                            }
                            else{
                                if(barcode==null){
                                    return 'N/A';
                                }
                                else{
                                return barcode;
                                }
                            }
                        }
                        if(sameProduct==0){
                            $('#errorMsg').empty()
                            $('#productin_table_body').append('<tr>\
                                <td>'+store+'</td>\
                                <td style="display: none;">'+storeId+'</td>\
                                <td>'+product+'</td>\
                                <td style="display: none;">'+productId+'</td>\
                                <td>'+variant+'</td>\
                                <td style="display: none;">'+variantId+'</td>\
                                <td style="display: none;">'+productType+'</td>\
                                <td>'+identification_Number()+'</td>\
                                <td>'+quantity+'</td>\
                                <td>'+unitPrice+'</td>\
                                <td>'+mrp+'</td>\
                                <td><button class="btn-remove" style="background: transparent;" value=""><i class="fas fa-minus-circle" style="color: red;"></i></button></td>\
                            </tr>');
                        }
                        else{
                            $('#productin_table').find('> tbody > tr').each(function () {
                                let tableStoreName	= $(this).find("td:eq(0)").text();
                                let tableProductName	= $(this).find("td:eq(2)").text();
                                let tableVariantName	= $(this).find("td:eq(4)").text();
                                if(tableStoreName==store && tableProductName==product && tableVariantName==variant){
                                    let previousQty = parseInt($(this).find("td:eq(8)").text());
                                    $(this).find("td:eq(8)").text(quantity+previousQty);
                                }
                            });
                        }
                        resetButton();
                        resetButtonFields();
                    }
                }
            });
        }else{
            // $.notify("Invalid selection")
            $('#errorMsg').text('Please fill up the required fields.')
        }
}

$(document).on('click', '#add_serial', function (e) {
	e.preventDefault();
    let row=parseInt($(this).val());
    $('#row').val(row);
    $('#serialModal').modal('show');
})

$(document).on('click', '#addSerial', function (e) {
    e.preventDefault();
    let numberOfProduct= $('#productin_table_body tr:eq('+parseInt($('#row').val())+') td:eq(8)').text();

    let rowCount = $("#serial_table tr").length;
    let serialNumber=$('#serialNumber').val();

    if(serialNumber!='' && numberOfProduct>rowCount-1){
        let table_serial_number=0;
        $('#serial_table').find('> tbody > tr').each(function () {
            if($(this).find("td:eq(1)").text()==serialNumber){
                table_serial_number=1;
            }
        })
        if(table_serial_number!=1){
            $('#serial_table tbody').append(
                '<tr>\
                    <td>'+rowCount+'</td>\
                    <td>'+serialNumber+'</td>\
                    <td>\
                    <a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></a>\
                    </td>\
                </tr>'
            );
        }
        else{
            $.notify("Serial Number already added on the table", { className: 'error', position: 'bottom right' });
        }
    }
    else{
        $.notify("Serial Number Can't be empty and can't add more than recieved quantity ", { className: 'error', position: 'bottom right' });
    }
});

$("#serial_table").on('click', '.delete_btn', function () {
    $(this).closest('tr').remove();
});

$(document).on('click', '#confirm_serial', function (e) {
    e.preventDefault();

	let productSerial= {}
	productSerial["StoreId"]    =$('#productin_table_body tr:eq('+parseInt($('#row').val())+') td:eq(1)').text();
	productSerial["Product"]    =$('#productin_table_body tr:eq('+parseInt($('#row').val())+') td:eq(2)').text();
	productSerial["ProductId"]  =$('#productin_table_body tr:eq('+parseInt($('#row').val())+') td:eq(3)').text();
	productSerial["Variant"]    =$('#productin_table_body tr:eq('+parseInt($('#row').val())+') td:eq(4)').text();
	productSerial["VariantId"]  =$('#productin_table_body tr:eq('+parseInt($('#row').val())+') td:eq(5)').text();
	productSerial["Quantity"]   =$('#productin_table_body tr:eq('+parseInt($('#row').val())+') td:eq(8)').text();


	var serialNumbers=[];
    $('#serial_table').find('> tbody > tr').each(function () {
        let serialNumber= {};
		serialNumber["index"]	= $(this).find("td:eq(0)").text();
		serialNumber["serialNumber"]	= $(this).find("td:eq(1)").text();
    	serialNumbers.push(serialNumber);
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

	productSerial["transferDate"]=today;
	productSerial["serialNumbers"]=serialNumbers;
    let rowCount = $("#serial_table tbody tr").length;
    if(rowCount==productSerial["Quantity"])
    {
        $.ajax({
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(productSerial),
            dataType : "json",
            url: "/product-serial",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if(response.status==200){
                    $('#serialModal').modal('hide');
                    $('#serialNumber').val('')
                    $("#serial_table tbody tr").remove();
                    $('#productin_table_body tr:eq('+parseInt($('#row').val())+') td:eq(7)').text("Serial number added");
                    $('#productin_table_body tr:eq('+parseInt($('#row').val())+') td:eq(11)').text("");
                    $.notify(response.message, { className: 'success', position: 'bottom right' });
                }
                else{
                    $.notify(response.serialNumber+' '+response.error, { className: 'error', position: 'bottom right' });
                }
            }
        });
    }
    else{
        $.notify('Need to enter serial number for all products', { className: 'error', position: 'bottom right' });
    }

});

$(document).on('click', '.exit', function (e) {
    e.preventDefault();
    $('#serialModal').modal('hide');
    $('#serialNumber').val('')
});

$("#productin_table").on('click', '.btn-remove', function () {
    $(this).closest('tr').remove();
})

function productInToServer() {
    this.event.preventDefault();

    let products = {};
    let productList = []
    var time = new Date().getTime();
    var productInNum = time.toString();

        if( $('#productin_table tr').length > 1){
            $('#errorMsg').empty()
            var productTable = $('#productin_table');
            var d = new Date();
            var date = d.getFullYear() +"-"+ (d.getMonth()+1) +"-"+ d.getDate() ;
            let productType=0;
            $(productTable).find('> tbody > tr').each(function () {
                let product = {}

                product["store"]        = $(this).find("td:eq(0)").text();
                product["storeId"]      = $(this).find("td:eq(1)").text();
                product["product"]      = $(this).find("td:eq(2)").text();
                product["productId"]    = $(this).find("td:eq(3)").text();
                product["variant"]      = $(this).find("td:eq(4)").text();
                product["variantId"]    = $(this).find("td:eq(5)").text();

                let type    = $(this).find("td:eq(6)").text();
                let identification_Number    = $(this).find("td:eq(7)").text();
                if(type=='Serialize' && identification_Number!='Serial number added'){
                    productType=1
                }

                product["quantity"]     = $(this).find("td:eq(8)").text();
                product["unitPrice"]    = $(this).find("td:eq(9)").text();
                product["mrp"]          = $(this).find("td:eq(10)").text();
                product["productInNum"] = productInNum;
                product["date"]         = date;
                product["type"]         = type;

                productList.push(product);

            })

            products["productList"] = productList

            if(productType == 0){
                productIn(products)

            }else{
                $.notify('Serial Number Required', {className: 'error', position: 'bottom right'});
            }
        }else{
            $('#errorMsg').text("Please enter at least one product!");
        }
}

function productIn(jsonData){
    $.ajax({
        type: "POST",
        url: "/product-in",
        data: JSON.stringify(jsonData),
        dataType : "json",
        contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        success: function (response) {
            if(response.message == 'Store does not have this product.'){
                $.notify(response.message, {className: 'error', position: 'bottom right'});
            }else if(response.message == 'You have add the product in the Warehouse first.'){
                $.notify(response.message, {className: 'error', position: 'bottom right'});
            }else{
                $.notify(response.message, {className: 'success', position: 'bottom right'});

                resetButton()
                location.href = "/product-in";
            }
        }
    });

}

function resetButton(){

    $('#product').val('').trigger('change')
    $('#variant').val('').trigger('change');

}

function resetButtonFields(){

    // $("#productin_table").find("tr:gt(0)").remove();
    $('form').on('reset', function() {
        setTimeout(function() {
            $('.selectpicker').selectpicker('refresh');
        });
    });
}

$('#product').on('change', function() {
    var productId = $(this).val()
    var productName = $("#product").find("option:selected").text()

    $('#variant').prop("disabled", false);

    $.ajax({
        type: "GET",
        url: "/product-wise-variant/"+productId,
        dataType:"json",
        success: function(response){
            $('#variant').empty();
            $('#variant').append('<option value="default" selected disabled>Select variant</option>');
            $.each(response.data, function(key, item){
                 $('#variant').append('<option value="'+ item.id +'">'+ item.variant_name +'</option>');
            });

            $('#variant').appendTo('#variant').selectpicker('refresh');

        }
    })

})

$('#variant').on('change', function () {
    var variantId = $(this).val()
    var productId = $("#product").val()
    var variantName = $("#variant").find("option:selected").text()

    $.ajax({
        type: "GET",
        url: "/get-product-price/" + productId + "/" + variantId,
        dataType: "json",
        success: function (response) {
            $('#unitprice').val(response.products[0].price)
            $('#mrp').val(response.products[0].mrp)

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
