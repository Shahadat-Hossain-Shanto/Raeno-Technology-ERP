// fetchInventoryStock()
// function fetchInventoryStock(){
// 	// var storeId = $(this).val()
// 	// alert(storeId)
// 	var count=1;
// 	$.ajax({
// 		type: "GET",
// 		url: "/inventory-stock-report-data",
// 		dataType:"json",
// 		success: function(response){
// 			// console.log(response.data)
// 			// alert(response.message)
// 			$.each(response.data, function(key, item) {

// 				if(item.expiry_date == null){
// 					var expiry_date = 'N/A'
// 				}else{
// 					var expiry_date = item.expiry_date
// 				}

// 				if(item.variant_name == 'default'){
// 					var variant_name = 'Default'
// 				}else{
// 					var variant_name = item.variant_name
// 				}

// 				var mrp = parseFloat(item.mrp)
// 				var price = parseFloat(item.price)
// 				var onHand = parseFloat(item.onHand)
// 				var stockSalePrice = mrp * item.onHand
// 				var stockPurchaseCost = price * item.onHand


// 				$('tbody').append('\
// 				<tr>\
// 					<td>'+count+'</td>\
// 					<td>'+item.productName+'</td>\
// 					<td>'+variant_name+'</td>\
// 					<td>'+expiry_date+'</td>\
// 					<td>'+item.brand+'</td>\
// 					<td>'+mrp.toFixed(2)+'</td>\
// 					<td>'+price.toFixed(2)+'</td>\
// 					<td>'+item.productIncoming+'</td>\
// 					<td>'+item.productOutgoing+'</td>\
// 					<td>'+item.onHand+'</td>\
// 					<td>'+stockSalePrice.toFixed(2)+'</td>\
// 					<td>'+stockPurchaseCost.toFixed(2)+'</td>\
//         		</tr>');
//         		count++;
// 			})
// 		}
// 	})
// }


$(document).ready(function () {
    var t = $('#stock_report_table').DataTable({
        ajax: {
            "url": "/inventory-stock-report-data",
            "dataSrc": "data"
        },
        columns: [
            { data: null },
            { data: 'productName' },
            { "render": function ( data, type, row, meta ){
            	if(row.variant_name == 'default'){
					var variant_name = 'Default'
				}else{
					var variant_name = row.variant_name
				}
            	return variant_name;
	            }
	        },
	        { "render": function ( data, type, row, meta ){
            	if(row.expiry_date == null){
					var expiry_date = 'N/A'
				}else{
					var expiry_date = row.expiry_date
				}
            	return expiry_date;
	            }
	        },
	        { data: 'brand' },
            { "render": function ( data, type, row, meta ){ var mrp = parseFloat(row.mrp); return mrp.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var price = parseFloat(row.price); return price.toFixed(2);} },
	        { data: 'productIncoming' },
	        { data: 'productOutgoing' },
            { "render": function ( data, type, row, meta ){
                if(row.onHand>0){
                    return '<button class="stock" value="'+row.id+'"><span class="badge badge-success">'+row.onHand+'</span></button>'
                }
                else{
                    return '<span class="badge badge-danger">'+row.onHand+'</span>'
                }
            }},
	        // { data: 'onHand' },

            { "render": function ( data, type, row, meta ){
            	var mrp = parseFloat(row.mrp)
				var onHand = parseFloat(row.onHand)
				var stockSalePrice = parseFloat(mrp * onHand)
            	return stockSalePrice.toFixed(2);}
            },
            { "render": function ( data, type, row, meta ){
				var price = parseFloat(row.price)
				var onHand = parseFloat(row.onHand)
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
	    	var PageInfo = $('#stock_report_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});

$(document).on('click', '.stock', function () {
    let id=$(this).val();
    // alert(id);
    $.ajax({
        type: "get",
        url: "get-serial/"+id,
        success: function (response) {
            // console.log(response)
            loadTable(response.data)
            $('#exampleModalCenter').modal('show')
        }
    });
});

$(document).on('click', '.exit', function (e) {
    e.preventDefault();
    $('#exampleModalCenter').modal('hide')
});

function loadTable(JSON){
    $.fn.dataTable.ext.errMode = 'none';
    $('#info_table').DataTable().clear().destroy()
    var t = $('#info_table').DataTable({
        data:JSON,
        columns: [
            { data: null },
            { data: 'productName' },
            { data: 'type' },
            { data: 'variantName' },
            { data: 'serialNumber' },
        ],
        columnDefs: [
	        {
	            searchable: true,
	            orderable: true,
	            targets: 0,
	        },
	    ],
        responsive:true,
	    order: [[1, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#info_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}
