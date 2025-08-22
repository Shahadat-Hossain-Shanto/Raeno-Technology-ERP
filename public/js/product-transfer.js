function productAddToTable() {

    this.event.preventDefault();

    var fromStore       =   $("#fromstore").find("option:selected").text();
    var fromStoreId     =   $("#fromstore").val()
    var toStore         =   $("#tostore").find("option:selected").text();
    var toStoreId       =   $("#tostore").val()
    var product         =   $("#product").find("option:selected").text();
    var productId       =   $("#product").val()

    if($("#variant").find("option:selected").text() == "Select variant"){
        var variant   =   "default";
         var variantId   =   0;
    }else{
        var variant   =   $("#variant").find("option:selected").text();
        var variantId =   $("#variant").find("option:selected").val();
    }

    var quantity        =   parseInt($("#qty").val());

    let sameProduct = 0;
    $('#product_transfer_table').find('> tbody > tr').each(function () {
		let tableFromStoreName	= $(this).find("td:eq(0)").text();
		let tableToStoreName	= $(this).find("td:eq(2)").text();
        let tableProductName	= $(this).find("td:eq(4)").text();
		let tableVariantName	= $(this).find("td:eq(6)").text();
        if(tableFromStoreName==fromStore && tableToStoreName==toStore && tableProductName==product && tableVariantName==variant){
            sameProduct = 1;
        }
    });

    if(fromStoreId != 'fromdefault' && toStore != 'Select Store' && productId != 'default' && quantity.length != 0 && variantId != 0){
        if(fromStoreId == toStoreId){
            $('#errorMsg').text('Please select different stores.')

        }else{
            let productType = '';
            let barcode = '';
            let rowCount = $("#product_transfer_table_body tr").length+1;
            // alert(rowCount);
            $('#errorMsg').empty()
            $('#errorMsg1').empty()
            $.ajax({
                type: "get",
                url: "/product-transfer/"+productId+"/"+variantId+"/"+fromStoreId,
                success: function (response) {
                    if(response.status==200){
                        if(response.productQuantity>=quantity && quantity>0 ){
                            productType=(response.productType);
                            barcode=(response.barcode);
                            function identification_Number(){
                                if(productType=='Serialize'){
                                    return '<button id="add_serial" style="background: transparent;" value="'+rowCount+'"><i class="fas fa-plus-circle" style="color: blue;"></i></button>';
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
                                $('#product_transfer_table_body').append('<tr>\
                                    <td>'+fromStore+'</td>\
                                    <td style="display: none;">'+fromStoreId+'</td>\
                                    <td>'+toStore+'</td>\
                                    <td style="display: none;">'+toStoreId+'</td>\
                                    <td>'+product+'</td>\
                                    <td style="display: none;">'+productId+'</td>\
                                    <td>'+variant+'</td>\
                                    <td style="display: none;">'+variantId+'</td>\
                                    <td>'+quantity+'</td>\
                                    <td style="display: none;">'+rowCount+'</td>\
                                    <td style="display: none;">'+productType+'</td>\
                                    <td>'+identification_Number()+'</td>\
                                    <td><button class="btn-remove" style="background: transparent;" value=""><i class="fas fa-minus-circle" style="color: red;"></i></button></td>\
                                </tr>');
                            }
                            else{
                                $('#product_transfer_table').find('> tbody > tr').each(function () {
                                    let tableFromStoreName	= $(this).find("td:eq(0)").text();
                                    let tableToStoreName	= $(this).find("td:eq(2)").text();
                                    let tableProductName	= $(this).find("td:eq(4)").text();
                                    let tableVariantName	= $(this).find("td:eq(6)").text();
                                    if(tableFromStoreName==fromStore && tableToStoreName==toStore && tableProductName==product && tableVariantName==variant){
                                        let previousQty = parseInt($(this).find("td:eq(8)").text());
                                        $(this).find("td:eq(8)").text(quantity+previousQty);
                                    }
                                });
                            }
                        }
                        else{
                            $.notify("Invalid Quantity!", { className: 'error', position: 'bottom right' });
                        }
                    }
                }
            });
        }

    }else{
        // alert('Invalid Selection!')
        $('#errorMsg').text('Please fill up the required fields.')
    }
}

$(document).on('click', '#add_serial', function (e) {
	e.preventDefault();
    // alert($(this).val())
    let row=parseInt($(this).val())-1;
    $('#row').val(row);
    let qty=($('#product_transfer_table_body tr:eq('+row+') td:eq(8)').text());
    // console.log($('#product_transfer_table_body tr:eq('+row+') td:eq(8)').text());
    // alert(qty);
    $('#serialModal').modal('show');
})

$(document).on('click', '#addSerial', function (e) {
    e.preventDefault();
    let numberOfProduct=$('#product_transfer_table_body tr:eq('+parseInt($('#row').val())+') td:eq(8)').text();
    let rowCount = $("#serial_table tr").length;
    let serialNumber=$('#serialNumber').val();
    if(serialNumber!='' && numberOfProduct>rowCount-1){
        let table_serial_number=0;
        $('#serial_table').find('> tbody > tr').each(function () {
            if($(this).find("td:eq(1)").text()==serialNumber){
                table_serial_number=1;
            }
        })
        // alert(table_serial_number);
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

$(document).on('click', '#confirm_serial', function (e) {
    e.preventDefault();

	let transferProduct= {}
	transferProduct["FromStoreId"]=$('#product_transfer_table_body tr:eq('+parseInt($('#row').val())+') td:eq(1)').text();
	transferProduct["ToStoreId"]=$('#product_transfer_table_body tr:eq('+parseInt($('#row').val())+') td:eq(3)').text();
	transferProduct["ProductId"]=$('#product_transfer_table_body tr:eq('+parseInt($('#row').val())+') td:eq(5)').text();
	transferProduct["VariantId"]=$('#product_transfer_table_body tr:eq('+parseInt($('#row').val())+') td:eq(7)').text();
	transferProduct["Quantity"]=$('#product_transfer_table_body tr:eq('+parseInt($('#row').val())+') td:eq(8)').text();


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
	// console.log(today)

	transferProduct["transferDate"]=today;
	transferProduct["serialNumbers"]=serialNumbers;
    let rowCount = $("#serial_table tbody tr").length;
    // alert(rowCount);
    if(rowCount==transferProduct["Quantity"])
    {
        // console.log(transferProduct);
        $.ajax({
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(transferProduct),
            dataType : "json",
            url: "/product-transfer-serial",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // console.log(response);
                if(response.status==200){
                    $('#serialModal').modal('hide');
                    $('#serialNumber').val('')
                    $("#serial_table tbody tr").remove();
                    $('#product_transfer_table_body tr:eq('+parseInt($('#row').val())+') td:eq(11)').text("Serial number added");
                    $('#product_transfer_table_body tr:eq('+parseInt($('#row').val())+') td:eq(12)').text("");
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

$("#serial_table").on('click', '.delete_btn', function () {
    $(this).closest('tr').remove();
});

$(document).on('click', '.exit', function (e) {
    e.preventDefault();
    $('#serialModal').modal('hide');
    $('#serialNumber').val('')
    // $("#serial_table tr").remove();
});

$("#product_transfer_table").on('click', '.btn-remove', function () {
    $(this).closest('tr').remove();
})

function productTransferToServer() {
    this.event.preventDefault();

    let products = {};
    let productList = []
    let transfer=0;

    if( $('#product_transfer_table tr').length > 1){
        // alert('rowCount')
        $('#errorMsg').empty()
        $('#errorMsg1').empty()
        var productTable = $('#product_transfer_table');
        $(productTable).find('> tbody > tr').each(function () {
            let product = {}

            product["fromStore"]    = $(this).find("td:eq(0)").text();
            product["fromStoreId"]  = $(this).find("td:eq(1)").text();
            product["toStore"]      = $(this).find("td:eq(2)").text();
            product["toStoreId"]    = $(this).find("td:eq(3)").text();
            product["product"]      = $(this).find("td:eq(4)").text();
            product["productId"]    = $(this).find("td:eq(5)").text();
            product["variant"]      = $(this).find("td:eq(6)").text();
            product["variantId"]    = $(this).find("td:eq(7)").text();
            product["quantity"]     = $(this).find("td:eq(8)").text();

            if($(this).find("td:eq(10)").text()=='Serialize' && $(this).find("td:eq(11)").text()!='Serial number added'){transfer=1;}

            productList.push(product);

        })

        products["productList"] = productList

        if(transfer==0){
            // alert('Transfer')
            // console.log(products);
            productTransfer(products)
        }
        else{
            $.notify('Mention the Serial Numbers of the Serialize product',{className: 'error', position: 'bottom right'});
        }

    }else{
        // alert("Please Enter at least one product!");
        $('#errorMsg1').text('Please Enter at least one product.')
    }
}

function productTransfer(jsonData){

    // alert("Requesting Transfer")
    // var data = JSON.stringify(jsonData)

    // console.log(jsonData)

    $.ajax({
        type: "POST",
        url: "/product-transfer",
        data: JSON.stringify(jsonData),
        dataType : "json",
        contentType: "application/json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // console.log(response.request)
            // invoice(response.orderId)
            // alert(response.message);
            $.notify(response.message, {className: 'success', position: 'bottom right'});
            resetButton()
        }
    });

}

function resetButton(){
    $('#errorMsg').empty()
    $('#errorMsg1').empty()
    $('#form_div').find('form')[0].reset();
    $("#product_transfer_table").find("tr:gt(0)").remove();
    $('form').on('reset', function() {
        setTimeout(function() {
            $('.selectpicker').selectpicker('refresh');
        });
    });
}

$('#fromstore').on('change', function() {
    var storeId = $(this).val()
    var storeName = $("#fromstore").find("option:selected").text()

    // alert(storeId)
    // fetchStoreStock(storeId)
    if(storeId == "inventory"){
        inventoryWiseProducts()
    }else{
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

$('#product').on('change', function() {
    var productId = $(this).val()
    var productName = $("#product").find("option:selected").text()
    var storeId = $("#fromstore").val()

    // alert(storeId)

    $.ajax({
        type: "GET",
        url: "/product-wise-variant/"+productId+"/"+storeId,
        dataType:"json",
        success: function(response){
            // console.log(response.data)
            // alert(response.message)

            $('#variant').empty();
            $('#variant').append('<option value="default" selected disabled>Select variant</option>');
            $.each(response.data, function(key, item){
                 $('#variant').append('<option value="'+ item.variant_id +'">'+ item.variant_name +'</option>');
            });

            $('#variant').appendTo('#variant').selectpicker('refresh');

        }
    })

})
