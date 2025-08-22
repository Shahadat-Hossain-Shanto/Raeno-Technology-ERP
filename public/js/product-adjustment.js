function productAddToTable() {
    this.event.preventDefault();

    var storeId = $("#store").val();
    var store = $("#store").find("option:selected").text();
    var productId = $("#product").val();
    var product = $("#product").find("option:selected").text();
    var quantity = $("#qty").val();

    if ($("#variant").find("option:selected").text() == "Select variant") {
        var variant = "default";
        var variantId = 0;
    } else {
        var variant = $("#variant").find("option:selected").text();
        var variantId = $("#variant").find("option:selected").val();
    }
    var type = $("#type").val();


    function identification_Number(){
        let row = $("#product_table_body tr").length;

        if($("#number").val()=='' && type!='Serialize'){
            return 'N/A'
        }
        else if($("#number").val()=='' && type=='Serialize'){
            return '<button id="add_serial" style="background: transparent;" value="'+row+'"><i class="fas fa-plus-circle" style="color: blue;"></i></button>';
        }
        else{
            return $("#number").val();
        }
    }


    if(parseInt(quantity)>parseInt($("#onHand").val())){$.notify('Low Stock',{className: 'error', position: 'bottom right'})}
    else{
        if (productId != "default" && variantId != "default" && storeId != "default" && quantity.length != 0 || productId != "" && variantId != "" && storeId != "" && quantity.length != 0) {
            $("#adjustment").prop("hidden", false);
            $("#errorMsg").empty();
            $("#product_table_body").append(
                '<tr>\
                    <td style="display: none;">'+storeId+'</td>\
                    <td>'+store+'</td>\
                    <td style="display: none;">'+productId+'</td>\
                    <td>'+product+'</td>\
                    <td style="display: none;">'+variantId+'</td>\
                    <td>'+variant+'</td>\
                    <td>'+quantity+'</td>\
                    <td>'+type+'</td>\
                    <td>'+identification_Number()+'</td>\
                    <td><button class="btn-remove" style="background: transparent;" value=""><i class="fas fa-minus-circle" style="color: red;"></i></button></td>\
                </tr>'
            );
            resetButton();
        } else {
            // $.notify("Invalid selection")
            $("#errorMsg").text("Please fill up the required fields.");
        }
    }
}

$("#product_table").on("click", ".btn-remove", function () {
    $(this).closest("tr").remove();
});

function productAdjustInToServer() {
    this.event.preventDefault();

    let products = {};
    let productList = [];
    var time = new Date().getTime();
    var productOutNum = time.toString();

    if ($("#product_table tr").length > 1) {
        $("#errorMsg").empty();
        var productTable = $("#product_table");
        var d = new Date();
        var date =d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
        var serialOk=1;
        var serials = [];
        $('#serial_table').find('> tbody > tr').each(function () {
            let serialNumber = $(this).find("td:eq(1)").text().trim(); // Get serial number
            if (serialNumber) {
                serials.push(serialNumber); // Add to array if not empty
            }
        });
        $(productTable).find("> tbody > tr").each(function () {
            let product = {};

            product["storeId"]      = $(this).find("td:eq(0)").text();
            product["store"]        = $(this).find("td:eq(1)").text();
            product["productId"]    = $(this).find("td:eq(2)").text();
            product["product"]      = $(this).find("td:eq(3)").text();
            product["variantId"]    = $(this).find("td:eq(4)").text();
            product["variant"]      = $(this).find("td:eq(5)").text();
            product["quantity"]     = $(this).find("td:eq(6)").text();
            product["Type"]         = $(this).find("td:eq(7)").text();
            if (serials.length > 0) {
                product["number"] = serials.join(", "); // Join multiple serial numbers
            }
            else
            product["number"]       = $(this).find("td:eq(8)").text();
        
            product["productOutNum"]= productOutNum;
            product["date"]         = date;
            if($(this).find("td:eq(8)").text()==''){
                serialOk=0;
                $(this).notify('Serial Number Needed!!!', {className: 'error', position: 'right'});
            }

            productList.push(product);
        });

        products["productList"] = productList;
        if(serialOk==1){
            // console.log(products);
            productAdjust(products);
        }
    } else {
        $("#errorMsg").text("Please enter at least one product!");
    }
}

function productAdjust(jsonData) {
    // console.log(jsonData)
    $.ajax({
        type: "POST",
        url: "/product-adjustment",
        data: JSON.stringify(jsonData),
        dataType: "json",
        contentType: "application/json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // console.log(response)

            if (response.status == 200) {
                $.notify(response.message, { className: 'success', position: 'bottom right' });
                resetButton();
                location.href = "/product-adjustment";
            }
            else {
                $.notify(response.message, { className: 'error', position: 'bottom right' });
            }
        },
    });
}

function resetButton() {
    $("#product").val("").trigger("change");
    $("#variant").val("").trigger("change");
    $("#qty").val("");
    $("#number").val("");
    $("#onHand").val("");
    $("#type").val("");
}

$('#store').on('change', function() {
    var storeId = $(this).val()
    var storeName = $("#store").find("option:selected").text()

    $('#product').prop("disabled", false);
    $('#number').val('');
    $('#variant').val('');
    $("#variant").prop("disabled", true);
    $('#variant').selectpicker('refresh');
    $('#qty').val('');
    $('#qty').prop("disabled", true);

    // alert(storeId)
    if(storeId==0){inventoryWiseProducts();}
    else{
        $.ajax({
            type: "GET",
            url: "/store-wise-product/"+storeId,
            dataType:"json",
            success: function(response){
                // console.log(response.data)
                // alert(response.message)

                $('#product').empty();
                $('#product').append('<option value="default" selected disabled>Select product</option>');
                $.each(response.data, function(key, item){
                     $('#product').append('<option value="'+ item.id +'">'+ item.productName +'</option>');
                });

                $('#product').appendTo('#product').selectpicker('refresh');

            }
        })
    }
})

function inventoryWiseProducts(){
    $.ajax({
        type: "GET",
        url: "/inventory-wise-product",
        dataType:"json",
        success: function(response){
            // console.log(response.data)
            // alert(response.message)

            $('#product').empty();
            $('#product').append('<option value="default" selected disabled>Select product</option>');
            $.each(response.data, function(key, item){
                 $('#product').append('<option value="'+ item.id +'">'+ item.productName +'</option>');
            });

            $('#product').appendTo('#product').selectpicker('refresh');

        }
    })
}

$("#product").on("change", function () {
    var productId = $(this).val();
    var productName = $("#product").find("option:selected").text();
    if($('#store').val()==0){
        var storeId = 'inventory';
    }
    else{
        var storeId = $('#store').val();
    }
    $("#variant").prop("disabled", false);
    // alert(productId)

    $.ajax({
        type: "GET",
        url: "/product-wise-variant/" + productId +"/"+storeId,
        dataType: "json",
        success: function (response) {
            // console.log(response)
            // alert(response.message)
            $('#type').val(response.type)
            if($('#type').val()!='Serialize'){
                $('#number').val(response.barcode);
            }
            else{
                $('#number').val('');
            }
            $("#variant").empty();
            $("#variant").append(
                '<option value="default" selected disabled>Select variant</option>'
            );
            $.each(response.data, function (key, item) {
                $("#variant").append(
                    '<option value="' +
                        item.variant_id +
                        '">' +
                        item.variant_name +
                        "</option>"
                );
            });

            $("#variant").appendTo("#variant").selectpicker("refresh");
        },
    });
});

$("#variant").on("change", function () {
    var variantId = $(this).val();
    var productId = $("#product").val();
    var storeId = $("#store").val();

    $("#qty").prop("disabled", false);
    // alert(variantId)
    // alert(productId)
    // alert(storeId)

    $.ajax({
        type: "GET",
        url: "/product-adjustment-onHand/" + productId + "/" + variantId + "/" + storeId,
        dataType: "json",
        success: function (response) {
            // console.log(response)
            $("#onHand").val(response.onHand);
        },
    });
});

$('#number').on('keyup', function(e){
	e.preventDefault();

	var keyword = $('#number').val();
    if($('#store').val()==0){
        var storeId = 'inventory';
    }
    else{
        var storeId = $('#store').val();
    }

	if( $('#number').val()){
	 	$.ajax({
			type: "GET",
			url: "/product-search/"+storeId+'/'+keyword,
			dataType: "json",
			success: function(response){
				if($.isEmptyObject(response.products)){
             		$('#number').notify('No product found!!!', {className: 'error', position: 'bottom right'});
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
                        // console.log(response)
                        $('#onHand').val(response.products[0].onHand)
                        // $('#serialNumber').val(response.products[0].serialNumber)
                        $('#type').val(response.products[0].type)
                        if($('#type').val()=='Serialize'){
                            $('#qty').val(1)
                            $("#qty").prop('disabled', true);
                            $("#variant").prop('disabled', true);
                            $("#variant").selectpicker('refresh');
                        }
                        else{
                            $("#qty").prop('disabled', false);
                            $("#variant").prop('disabled', false);
                            $("#variant").selectpicker('refresh');
                        }
                        $('#number').notify('Product Added', {className: 'success', position: 'bottom right'});
                    }
                    else{
                        $('#number').notify('Product Sold!!!', {className: 'error', position: 'bottom right'});
                    }
				}

			}
		})
	}
    else{
        $('#qty').val('')
        $("#qty").prop('disabled', true);
        $('#variant').empty();
        $("#variant").prop('disabled', true);
        $("#variant").selectpicker('refresh');
        $("#product").val($('#product option[value="0"]').val()).change();
        $('#type').val('')
    }
})

$('#qty').keyup(function (e) {
    if(parseFloat($(this).val())>parseFloat($('#onHand').val())){
        $(this).notify("Stock Limited!", {className: 'error', position: 'bottom right'});
        $(this).val($('#onHand').val())
    }
});

$(document).on('click', '#add_serial', function (e) {
	e.preventDefault();
    let row=parseInt($(this).val());
    $('#row').val(row);
    $('#serialModal').modal('show');
})

$(document).on('click', '#addSerial', function (e) {
    e.preventDefault();
    let numberOfProduct= $('#product_table_body tr:eq('+parseInt($('#row').val())+') td:eq(6)').text();

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
	productSerial["StoreId"]    =$('#product_table_body tr:eq('+parseInt($('#row').val())+') td:eq(0)').text();
	productSerial["ProductId"]  =$('#product_table_body tr:eq('+parseInt($('#row').val())+') td:eq(2)').text();
	productSerial["Product"]    =$('#product_table_body tr:eq('+parseInt($('#row').val())+') td:eq(3)').text();
	productSerial["VariantId"]  =$('#product_table_body tr:eq('+parseInt($('#row').val())+') td:eq(4)').text();
	productSerial["Variant"]    =$('#product_table_body tr:eq('+parseInt($('#row').val())+') td:eq(5)').text();
	productSerial["Quantity"]   =$('#product_table_body tr:eq('+parseInt($('#row').val())+') td:eq(6)').text();


	var serialNumbers=[];
    $('#serial_table').find('> tbody > tr').each(function () {
        let serialNumber= {};
		serialNumber["index"]	= $(this).find("td:eq(0)").text();
		serialNumber["serialNumber"]	= $(this).find("td:eq(1)").text();
    	serialNumbers.push(serialNumber);
	})
	productSerial["serialNumbers"]=serialNumbers;
    let rowCount = $("#serial_table tbody tr").length;
    if(rowCount==productSerial["Quantity"])
    {
        $.ajax({
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(productSerial),
            dataType : "json",
            url: "/product-serial-delete",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if(response.status==200){
                    $('#serialModal').modal('hide');
                    $('#serialNumber').val('')
                    $("#serial_table tbody tr").remove();
                    $('#product_table_body tr:eq('+parseInt($('#row').val())+') td:eq(8)').text("Serial number added");
                    $('#product_table_body tr:eq('+parseInt($('#row').val())+') td:eq(9)').text("");
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
