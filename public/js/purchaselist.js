// fetchPurchase();
// function fetchPurchase(){
// 		$.ajax({
// 			type: "GET",
// 			url: "/purchase-list-data",
// 			dataType:"json",
// 			success: function(response){
// 				$('tbody').html("");
// 				$.each(response.purchase, function(key, item) {

// 					if(item.purchaseNote == null){
// 						purchaseNote = 'N/A'
// 					}else{
// 						purchaseNote = item.purchaseNote
// 					}

// 					if(item.status == "pending"){
// 						status = '<span class="badge badge-danger">Pending</span>';
// 					}else{
// 						status = '<span class="badge badge-success">Received</span>';
// 					}

// 					$('tbody').append('<tr>\
// 					<td></td>\
// 					<td>'+item.name+'</td>\
// 					<td>'+item.store+'</td>\
// 					<td>'+item.poNumber+'</td>\
// 					<td>'+item.purchaseDate+'</td>\
// 					<td>'+item.totalPrice+'</td>\
// 					<td>'+item.discount+'</td>\
// 					<td>'+item.other_cost+'</td>\
// 					<td>'+item.grandTotal+'</td>\
// 					<td>'+purchaseNote+'</td>\
// 					<td>'+status+'</td>\
// 					<td>\
// 						<a type="button" class="edit_btn btn btn-info btn-sm" href="/purchase-product-list/'+item.id+'"><i class="fas fa-info-circle"></i> Details</a>\
// 	    			</td>\
// 	    		</tr>');
// 				})
// 			}
// 		});
// 	}

$(document).ready(function () {
    const date = new Date();

    let day = date.getDate();
    let month = date.getMonth() + 1;
    let year = date.getFullYear();

    let enddate = `${year}-${month}-${day}`;
    if(month == 1)
    {
        month = 13;
        year = year-1;
    }
    let startdate = `${year}-${month - 1}-${day}`;

    var t = $('#purchaseProduct_table').DataTable({
        ajax: {
            "url": "/purchase-list-data/" + startdate + "/" + enddate,
            "dataSrc": "purchase"
        },
        columns: [
            { data: null },
            { data: 'name' },
            { data: 'store' },
            { data: 'poNumber' },
            { data: 'purchaseDate' },
            { "render": function ( data, type, row, meta ){ var totalPrice = parseFloat(row.totalPrice); return totalPrice.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var discount = parseFloat(row.discount); return discount.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var other_cost = parseFloat(row.other_cost); return other_cost.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var grandTotal = parseFloat(row.grandTotal); return grandTotal.toFixed(2);} },
            { "render": function ( data, type, row, meta ){
            		if(row.purchaseNote == null){
						purchaseNote = 'N/A'
					}else{
						purchaseNote = row.purchaseNote
					}
            		return purchaseNote;
            	}
            },
            { "render": function ( data, type, row, meta ){
                    if(row.status == "pending"){
                        status = '<span class="badge badge-danger">Pending</span>';
                    }else if(row.status == "partial recieved"){
                        status = '<span class="badge badge-danger">Partial Recieved</span>';
                    }else{
						status = '<span class="badge badge-success">Received</span>';
					}
            		return status;
            	}
            },
            {
                "render": function (data, type, row, meta) {
                    var zero = 0;
                    if (row.purchaseReturn == '') {
                        var purchaseReturn = zero.toFixed(2);
                    }
                    else {
                        var purchaseReturn = '<a type="button" class="edit_btn btn btn-info btn-sm" href="/purchase-return-details/' + row.purchaseReturn.return_number + '">' + parseFloat(row.purchaseReturn.net_return).toFixed(2) + '</a>'
                    }
                    return purchaseReturn;
                }
            },
            { render: getBtns },

        ],
        columnDefs: [
	        {
	            searchable: true,
	            orderable: true,
	            targets: 0,
	        },
	    ],
	    // order: [[4, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#purchaseProduct_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});


function getBtns(data, type, row, meta) {

    var id = row.id;
    return '<a type="button" class="edit_btn btn btn-info btn-sm" href="/purchase-product-list/'+id+'"><i class="fas fa-info-circle"></i> Details</a>';
}
