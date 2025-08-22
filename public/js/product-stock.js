
$(document).on('change','#price', function(){
    if(parseFloat($('#purchasecost').val())>parseFloat($('#price').val())){
        $('#price').val('')
        $.notify('Selling Price can not be less than Purchase Cost')
    }
});

$('#purchasecost').change(function (e) {
    e.preventDefault();
    if(parseFloat($('#purchasecost').val())>parseFloat($('#price').val())){
        $('#purchasecost').val('')
        $.notify('Purchase Cost can not be greater than Selling Price ')
    }
});

function addStore(){
	this.event.preventDefault();

	var storeName		=	$("#store option:selected").text();
	var storeId			=	$("#store option:selected").val();
	var variant			=	$("#variant option:selected").text();
	var variantId		=	$("#variant option:selected").val();
	var qty  			=   $("#qty").val();
	var purchaseCost  	=   parseFloat($("#purchasecost").val());
	var price  			=   parseFloat($("#price").val());
	var safetyStock  	=   $("#safetystock").val();


    if(storeName != 'Select store' && variant != 'Select variant' && qty.length != 0 && purchaseCost.length != 0 && price.length != 0 && safetyStock.length != 0){

    	if(purchaseCost > price){
    		// alert(1)
			$.notify('Invalid Price!', {className: 'error', position: "bottom right"})
			$("#purchasecost").val('')
			$("#price").val('')
		}else{
			$("#store_table_data tbody").append(
			"<tr>" +
				"<td width='20%'>" + storeName + "</td>" +
				"<td width='20%'>" + variant + "</td>" +
				"<td width='10%'>" + qty + "</td>" +
				"<td width='15%'>" + purchaseCost + "</td>" +
				"<td width='15%'>" + price + "</td>" +
				"<td width='10%'>" + safetyStock + "</td>" +
				"<td class='hidden'>" + storeId + "</td>" +
				"<td class='hidden'>" + variantId + "</td>" +
				"<td width='5%'>" +
					"<button type='button' class='delete-btn btn btn-outline-danger btn-sm'><i class='fas fa-trash'></button>" +
				"</td>" +
			"</tr>");
			resetButton()
		}



    }else{
    	$.notify("Please fill up all the required fields.", {className: 'error', position: 'bottom right'});
    }
}

$("#store_table_data").on('click', '.delete-btn', function () {
    $(this).closest('tr').remove();
});


function resetButton(){
	$("#store").val('').change();
	// $("#variant").val('').change();
	$("#qty").val('');
	$("#purchasecost").val('');
	$("#price").val('');
	$("#safetystock").val('');

}

function collectData(){
	this.event.preventDefault();

	let products = {};

	products["productName"]		= $('#productname').val();
	products["productId"]		= $('#productid').val();

	var T = $('#store_table_data');
	var stores = [];

   	var rowCount = $('#store_table_data tr').length;

   	if(rowCount > 0){
   		$(T).find('> tbody > tr').each(function (){
			let store = {};


			store["storeName"]		= $(this).find("td:eq(0)").text();
			store["storeId"]		= $(this).find("td:eq(6)").text();
			store["variantName"]	= $(this).find("td:eq(1)").text();
			store["variantId"]		= $(this).find("td:eq(7)").text();
			store["qty"]			= $(this).find("td:eq(2)").text();
			store["purchaseCost"]	= $(this).find("td:eq(3)").text();
			store["price"]			= $(this).find("td:eq(4)").text();
			store["safetyStock"]	= $(this).find("td:eq(5)").text();

			stores.push(store);
		})
   	}else{
   		$.notify("Atleast one store must be selected.", {className: 'error', position: 'bottom right'});
   	}

   	products["stores"] = stores;
   	submitToServer(products);
}

function submitToServer(jsonData) {
	// console.log(jsonData)

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
        contentType: "application/json",
        url: "/product-stock-create",
        data: JSON.stringify(jsonData),
        dataType : "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {

        	// alert(response.message)
        	$(location).attr('href','/product-list');

        }
    });
}

