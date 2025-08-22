$(document).on('click', '#pending', function (e) {
	e.preventDefault();
    recieve();
})

function recieve(){
    var purchaseId = $('#pending').val();
	// alert(purchaseId)

	let purchaseProducts= {}
	var purchaseProductList=[];

	var T = $('.display');


	$(T).find('> tbody > tr').each(function () {
		let purchaseProduct= {};
		purchaseProduct["productId"]	= $(this).find("td:eq(1)").text();
    	purchaseProduct["productName"]	= $(this).find("td:eq(2)").text();
    	purchaseProduct["variant"]	    = $(this).find("td:eq(3)").text();
    	purchaseProduct["variantId"]	= $(this).find("td:eq(4)").text();
    	purchaseProduct["quantity"]		= $(this).find("td:eq(5)").text();
    	purchaseProduct["unitPrice"]	= $(this).find("td:eq(6)").text();
    	purchaseProduct["mrp"]			= $(this).find("td:eq(7)").text();
    	purchaseProduct["totalPrice"]	= $(this).find("td:eq(8)").text();
    	purchaseProduct["recieve_quantity"]	= $(this).find("td:eq(9)").text();
		purchaseProduct["id"]	= $(this).find("td:eq(10)").text();
    	purchaseProductList.push(purchaseProduct);
	})

	purchaseProducts["store"]= $('#storeSpan').text();
	purchaseProducts["onlyNonSerilize"]= $('#onlyNonSerilize').val();
	purchaseProducts["productList"]=purchaseProductList;

	// console.log(purchaseProducts)

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
        contentType: "application/json",
        url: "/purchase-product-receive/"+purchaseId,
        data: JSON.stringify(purchaseProducts),
        dataType : "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
        	// $.notify(response.message, "success");
        	// alert(response.message);
            if(response.status==200){
                $('#onlyNonSerilize').val('');
                location.reload();
            }
            else{
                if(response.error=='Serialize Product'){
                    // alert('hi')
                    $('#allRecieveModal').modal('show');
                    $("#non-serilize_recieve").attr("hidden",true);
                }
                else if(response.error=='Non-Serialize Product'){
                    // alert('hi');
                    $('#allRecieveModal').modal('show');
                    if(response.nonSerializeRecieve==1){
                        $("#non-serilize_recieve").attr("hidden",true);
                    }
                }
                // $.notify(response.error, { className: 'error', position: 'bottom right' });
            }

        }
    });
}

$(document).on('click', '#non-serilize_recieve', function (e) {
	e.preventDefault();
    $('#onlyNonSerilize').val('yes');
    recieve();
})

$(document).ready(function () {

	var purchaseProductId = $('#purchaseid').val();
    var t = $('#product_table').DataTable({
        ajax: {
            "url": "/purchase-product-list-data/"+purchaseProductId,
            "dataSrc": "productList"
        },
        columns: [
            { data: null },
            { data: 'productId',  className: "hidden" },
            { data: 'productName' },
            { data: 'variant_name' },
            { data: 'variant_id',  className: "hidden" },
            { data: 'quantity' },
            { "render": function ( data, type, row, meta ){ var unitPrice = parseFloat(row.unitPrice); return unitPrice.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var mrp = parseFloat(row.mrp); return mrp.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var totalPrice = parseFloat(row.totalPrice); return totalPrice.toFixed(2);} },
            { data: 'recieve_quantity' },
            { data: 'id',  className: "hidden" },
            { "render": function ( data, type, row, meta ){
                var quantity = parseFloat(row.quantity);
                var recieve_quantity = parseFloat(row.recieve_quantity);
                if(recieve_quantity==0)
                {
                    var id=(row.id);
                    return '<a href="#"><button type="button" data-toggle="modal" data-target="#recieveModal" class="recieve btn btn-danger float-right ml-1" value="'+id+'">Receive</button></a>';
                }
                else if(recieve_quantity<quantity && recieve_quantity>0)
                {
                    var id=(row.id);
                    return '<a href="#"><button type="button" data-toggle="modal" data-target="#recieveModal" class="recieve btn btn-danger float-right ml-1" value="'+id+'">Partially Received</button></a>';
                }
                else{
                    return '<span class="badge badge-success">Received</span>';
                }
            } }
        ],
        columnDefs: [
	        {
	            searchable: true,
	            orderable: true,
	            targets: 0,
	        },
	    ],
	    order: [[1, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#product_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});

$(document).on('click', '.recieve', function (e) {
    e.preventDefault();
	var productId = $(this).val();
    $("#id").val(productId);
});

function partialRecieve(){
    var id = $("#id").val();
    var qty =$("#recieve_qty").val();
    // alert(id);
    // alert(qty);

    var purchaseId = $('#pending').val();
	// alert(purchaseId)

	let purchaseProducts= {}
	var purchaseProductList=[];

	var T = $('.display');


	$(T).find('> tbody > tr').each(function () {

        if($(this).find("td:eq(10)").text()==id)
		{let purchaseProduct= {};
		purchaseProduct["id"]	= $(this).find("td:eq(10)").text();
		purchaseProduct["productId"]	= $(this).find("td:eq(1)").text();
    	purchaseProduct["productName"]	= $(this).find("td:eq(2)").text();
    	purchaseProduct["variant"]	    = $(this).find("td:eq(3)").text();
    	purchaseProduct["variantId"]	= $(this).find("td:eq(4)").text();
    	purchaseProduct["quantity"]		= $(this).find("td:eq(5)").text();
    	purchaseProduct["unitPrice"]	= $(this).find("td:eq(6)").text();
    	purchaseProduct["mrp"]			= $(this).find("td:eq(7)").text();
    	purchaseProduct["totalPrice"]	= $(this).find("td:eq(8)").text();
    	purchaseProduct["recieve_quantity"]	= parseInt(qty);
    	purchaseProduct["total_recieve_quantity"]	= parseInt($(this).find("td:eq(9)").text())+parseInt(qty);

    	purchaseProductList.push(purchaseProduct);}
	})

	purchaseProducts["serialNumbers"]= $("#serial_table tr").length-1;
	purchaseProducts["store"]= $('#storeSpan').text();
	purchaseProducts["productList"]=purchaseProductList;
	purchaseProducts["purchaseId"]=purchaseId;


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

	purchaseProducts["purchaseDate"]=today;

    if(purchaseProducts.productList[0].total_recieve_quantity>purchaseProducts.productList[0].quantity){
        $.notify("Recieve Quantity can't be greater than Purchase Quantity", { className: 'error', position: 'bottom right' });
    }
    else{
        $.ajax({
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify(purchaseProducts),
            dataType : "json",
            url: "/purchase-product-partial-receive/"+id+"/"+qty,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // console.log(response);
                if(response.status==200){
                    $("#recieve_qty").val('');
                    $('#serialNumber').val('');
                    location.reload();
                }
                else{
                    $('#recieveModal').hide();
                    $('.modal-backdrop').hide();
                    $('#productSerialModal').modal('show');
                    $.notify(response.error, { className: 'error', position: 'bottom right' });
                }
            }
        });
    }
}

$(document).on('click', '#confirm_recieve', function (e) {
    e.preventDefault();
    partialRecieve()
});

$(document).on('click', '#addSerial', function (e) {
    e.preventDefault();
    let numberOfProduct=$("#recieve_qty").val();
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

$("#serial_table").on('click', '.delete_btn', function () {
    $(this).closest('tr').remove();
});

$(document).on('click', '#confirm_serial', function (e) {
    e.preventDefault();
    // $('#productSerialModal').modal('hide');
    // alert('serial ok');
    // alert($('#serialNumber').val());

    var id = $("#id").val();
    var purchaseId = $('#pending').val();

	let purchaseProduct= {}

	var T = $('.display');


	$(T).find('> tbody > tr').each(function () {

        if($(this).find("td:eq(10)").text()==id){
            purchaseProduct["purchaseProductId"]	= $(this).find("td:eq(10)").text();
            purchaseProduct["productId"]	= $(this).find("td:eq(1)").text();
            purchaseProduct["productName"]	= $(this).find("td:eq(2)").text();
            purchaseProduct["variant"]	    = $(this).find("td:eq(3)").text();
            purchaseProduct["variantId"]	= $(this).find("td:eq(4)").text();
        }
	})

	purchaseProduct["store"]= $('#storeSpan').text();
	purchaseProduct["purchaseId"]=purchaseId;


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

	purchaseProduct["purchaseDate"]=today;
	purchaseProduct["serialNumbers"]=serialNumbers;
    // console.log(purchaseProduct);

    $.ajax({
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(purchaseProduct),
        dataType : "json",
        url: "/purchase-product-serial",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            // console.log(response);
            if(response.status==200){
                partialRecieve();
                $('#productSerialModal').modal('hide');
            }
            else{
                $.notify(response.serialNumber+' '+response.error, { className: 'error', position: 'bottom right' });
            }
        }
    });
});

$(document).on('click', '.exit', function (e) {
    e.preventDefault();
    $('#productSerialModal').modal('hide');
    $('#allRecieveModal').modal('hide');
});
