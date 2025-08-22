fetchStoreDefault()
function fetchStoreDefault(){
	$.ajax({
		type: "GET",
		url: "/store-stock-data-default",
		dataType:"json",
		success: function(response){
			$('#storename').text(response.storename)
		}
	})
}

$(document).ready(function () {
    var t = $('#store_stock_report_table').DataTable({
        ajax: {
            "url": "/store-stock-data-default",
            "dataSrc": "data"
        },
        columns: [
            { data: null },
            { data: 'productName' },
	        { data: 'brand' },
            { "render": function ( data, type, row, meta ){ var mrp = parseFloat(row.mrp); return mrp.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var price = parseFloat(row.price); return price.toFixed(2);} },
	        { data: 'totalProductIncoming' },
	        { data: 'totalProductOutgoing' },
	        { data: 'totalOnHand' },

            { "render": function ( data, type, row, meta ){ 
            	var mrp = parseFloat(row.mrp)
				var onHand = parseFloat(row.totalOnHand)
				var stockSalePrice = parseFloat(mrp * onHand)
            	return stockSalePrice.toFixed(2);} 
            },
            { "render": function ( data, type, row, meta ){ 
				var price = parseFloat(row.price)
				var onHand = parseFloat(row.totalOnHand)
				var stockPurchaseCost = parseFloat(price * onHand)
            	return stockPurchaseCost.toFixed(2);} 
            },
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
	    	var PageInfo = $('#store_stock_report_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});

// fetchStoreDefault()
// function fetchStoreDefault(){
// 	// var storeId = $(this).val()
// 	// alert(storeId)
// 	var count=1;
// 	$.ajax({
// 		type: "GET",
// 		url: "/store-stock-data-default",
// 		dataType:"json",
// 		success: function(response){
// 			// console.log(response.data)
// 			// alert(response.message)
// 			$('#storename').text(response.storename)
// 			// $('#storeaddress').text(response.store_address)


// 			$.each(response.data, function(key, item) {

// 				// if(item.expiry_date == null){
// 				// 	var expiry_date = 'N/A'
// 				// }else{
// 				// 	var expiry_date = item.expiry_date
// 				// }

// 				var mrp = parseFloat(item.mrp)
// 				var price = parseFloat(item.price)
// 				var totalOnHand = parseFloat(item.totalOnHand)
// 				var stockSalePrice = mrp * totalOnHand
// 				var stockPurchaseCost = price * totalOnHand

// 				$('tbody').append('\
// 				<tr>\
// 				    <td>'+count+'</td>\
// 					<td>'+item.productName+'</td>\
// 					<td>'+item.brand+'</td>\
// 					<td>'+mrp.toFixed(2)+'</td>\
// 					<td>'+price.toFixed(2)+'</td>\
// 					<td>'+item.totalProductIncoming+'</td>\
// 					<td>'+item.totalProductOutgoing+'</td>\
// 					<td>'+item.totalOnHand+'</td>\
// 					<td>'+stockSalePrice.toFixed(2)+'</td>\
// 					<td>'+stockPurchaseCost.toFixed(2)+'</td>\
//         		</tr>');
//         		count++;
// 			})
// 		}
// 	})
// }


// var storeId = storeId.value;
// $('#store').on('change', function() {
// 	var storeId = $(this).val()
// 	var storeName = $("#store").find("option:selected").text()
//     var count=1;
// 	// alert(storeId)
// 	// fetchStoreStock(storeId)
	
// 	$.ajax({
// 		type: "GET",
// 		url: "/store-stock-data/"+storeId,
// 		dataType:"json",
// 		success: function(response){
// 			// console.log(response.data)
// 			// alert(response.message)

// 			$('#storename').text(storeName)

// 			$('#store_stock_report_table').DataTable().clear().destroy()
// 			dataTableX()

// 			$.each(response.data, function(key, item) {

// 				// if(item.description == null){
// 				// 	var description = 'N/A'
// 				// }else{
// 				// 	var description = item.description
// 				// }

// 				var mrp = parseFloat(item.mrp)
// 				var price = parseFloat(item.price)
// 				var totalOnHand = parseFloat(item.totalOnHand)
// 				var stockSalePrice = mrp * totalOnHand
// 				var stockPurchaseCost = price * totalOnHand

// 				$('tbody').append('\
// 				<tr>\
// 					<td>'+count+'</td>\
// 					<td>'+item.productName+'</td>\
// 					<td>'+item.brand+'</td>\
// 					<td>'+mrp.toFixed(2)+'</td>\
// 					<td>'+price.toFixed(2)+'</td>\
// 					<td>'+item.totalProductIncoming+'</td>\
// 					<td>'+item.totalProductOutgoing+'</td>\
// 					<td>'+item.totalOnHand+'</td>\
// 					<td>'+stockSalePrice.toFixed(2)+'</td>\
// 					<td>'+stockPurchaseCost.toFixed(2)+'</td>\
//         		</tr>');
//         		count++;
// 			})
// 		}
// 	})
	
// })

	
// var storeId = storeId.value;
$('#store').on('change', function() {
	var storeId = $(this).val()
	var storeName = $("#store").find("option:selected").text()

	onChangeDataTable(storeId)

	$.ajax({
		type: "GET",
		url: "/store-stock-data/"+storeId,
		dataType:"json",
		success: function(response){
			// console.log(response.data)
			// alert(response.message)
			$('#storename').text(storeName)
		}
	})
})


function onChangeDataTable(storeId){

	$('#store_stock_report_table').DataTable().clear().destroy()

	var t = $('#store_stock_report_table').DataTable({
        ajax: {
            "url": "/store-stock-data/"+storeId,
            "dataSrc": "data"
        },
        columns: [
            { data: null },
            { data: 'productName' },
	        { data: 'brand' },
            { "render": function ( data, type, row, meta ){ var mrp = parseFloat(row.mrp); return mrp.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var price = parseFloat(row.price); return price.toFixed(2);} },
	        { data: 'totalProductIncoming' },
	        { data: 'totalProductOutgoing' },
	        { data: 'totalOnHand' },

            { "render": function ( data, type, row, meta ){ 
            	var mrp = parseFloat(row.mrp)
				var onHand = parseFloat(row.totalOnHand)
				var stockSalePrice = parseFloat(mrp * onHand)
            	return stockSalePrice.toFixed(2);} 
            },
            { "render": function ( data, type, row, meta ){ 
				var price = parseFloat(row.price)
				var onHand = parseFloat(row.totalOnHand)
				var stockPurchaseCost = parseFloat(price * onHand)
            	return stockPurchaseCost.toFixed(2);} 
            },
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
	    	var PageInfo = $('#store_stock_report_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}