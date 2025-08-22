fetchStoreDefault()
function fetchStoreDefault(){
	$.ajax({
		type: "GET",
		url: "/store-stock-report-data-default",
		dataType:"json",
		success: function(response){
			$('#storename').text(response.storename)
		}
	})
}


$(document).ready(function () {
    var t = $('#store_stock_report_table').DataTable({
        ajax: {
            "url": "/store-stock-report-data-default",
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
	        // { data: 'onHand' },
            { "render": function ( data, type, row, meta ){
                if(row.onHand>0){
                    return '<button class="stock" value="'+row.id+'"><span class="badge badge-success">'+row.onHand+'</span></button>'
                }
                else{
                    return '<span class="badge badge-danger">'+row.onHand+'</span>'
                }
            }},

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
	    	var PageInfo = $('#store_stock_report_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});



// var storeId = storeId.value;
$('#store').on('change', function() {
	var storeId = $(this).val()
	var storeName = $("#store").find("option:selected").text()

	onChangeDataTable(storeId)

	$.ajax({
		type: "GET",
		url: "/store-stock-report-data/"+storeId,
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
            "url": "/store-stock-report-data/"+storeId,
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
	        // { data: 'onHand' },
            { "render": function ( data, type, row, meta ){
                if(row.onHand>0){
                    return '<button class="stock" value="'+row.id+'"><span class="badge badge-success">'+row.onHand+'</span></button>'
                }
                else{
                    return '<span class="badge badge-danger">'+row.onHand+'</span>'
                }
            }},

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
	    	var PageInfo = $('#store_stock_report_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}

$(document).on('click', '.stock', function () {
    let id=$(this).val();
    // alert(id);
    $.ajax({
        type: "get",
        url: "get-store-serial/"+id,
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
