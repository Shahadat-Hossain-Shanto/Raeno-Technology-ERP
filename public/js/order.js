// //PRODUCT LIST

// 	fetchOrder();
// 	function fetchOrder(){
// 		$.ajax({
// 			type: "GET",
// 			url: "/order-list-data",
// 			dataType:"json",
// 			success: function(response){
// 				// console.log(response);
// 				$('tbody').html("");
// 				$.each(response.order, function(key, item) {

// 					if(item.name == null){
// 						var clientName = 'Walking Customer'
// 					}else{
// 						var clientName = item.name
// 					}

// 					if(item.mobile == null){
// 						var mobile = 'N/A'
// 					}else{
// 						var mobile = item.mobile
// 					}

// 					var total = parseFloat(item.total)
// 					var totalDiscount = parseFloat(item.totalDiscount)
// 					var grandTotal = parseFloat(item.grandTotal)
// 					var totalTax = parseFloat(item.totalTax)

// 					$('tbody').append('<tr>\
// 					<td></td>\
// 					<td>'+item.orderId +'</td>\
// 					<td>'+clientName+'</td>\
// 					<td>'+mobile+'</td>\
// 					<td>'+total.toFixed(2)+'</td>\
// 					<td>'+totalDiscount.toFixed(2)+'</td>\
// 					<td>'+grandTotal.toFixed(2)+'</td>\
// 					<td>'+item.orderDate+'</td>\
// 					<td>'+totalTax.toFixed(2)+'</td>\
// 					<td>\
//         				<a type="button" class="edit_btn btn btn-info btn-sm" href="/order-product-list/'+item.id+'"><i class="fas fa-info-circle"></i> Details</a>\
//         			</td>\
//         		</tr>');
// 				})
// 			}
// 		});
// 	}

// //DATA TABLE
// $(document).ready( function () {
// 	$('#order_table').DataTable({
// 	    pageLength : 20,
// 	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
// 	    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
// 		    //debugger;
// 		    var index = iDisplayIndexFull + 1;
// 		    $("td:first", nRow).html(index);
// 		    return nRow;
// 	  	},
// 	});
// });

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(document).on("submit", "#ReportForm", function (e) {
        e.preventDefault();

        let formData = new FormData($("#ReportForm")[0]);
        $('#order_table').DataTable().clear().destroy()
        var start = $("#startdate").val();
        var end = $("#enddate").val();

        var t = $('#order_table').DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                "url": "/order-list-data/" + start + "/" + end,
                "dataSrc": "data",
                "dataType": "json",
                "type": "POST",
            },
            columns: [
                { data: null },

                { data: 'orderId' },

                {
                    data: 'name',
                    render: checkName
                },

                {
                    data: 'mobile',
                    render: checkMobile
                },

                {
                    data: 'orderDate'
                },

                {
                    data: 'total',
                    render: getTotal
                },

                {
                    data: 'totalDiscount',
                    render: getTotalDiscount
                },
                {
                    data: 'specialDiscount',
                    render: getSpecialDiscount
                },

                {
                    data: 'totalTax',
                    render: getTotalTax
                },
                {
                    data: 'grandTotal',
                    render: getGrandTotal
                },
                {
                    data: 'salesReturn',
                    render: getSalesReturn
                },

                {
                    data: 'id',
                    render: getBtns
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
            pageLength: 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
        });


        t.on('order.dt search.dt', function () {

            t.on('draw.dt', function () {
                var PageInfo = $('#order_table').DataTable().page.info();
                t.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1 + PageInfo.start;
                });
            });

        }).draw();


    });


});

// DATA TABLE
$(document).ready(function () {
    var t = $('#order_table').DataTable({
    	"processing": true,
        "serverSide": true,
        ajax: {
        	headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
            "url": "/order-list-data",
            "dataSrc": "data",
            "dataType": "json",
         	"type": "POST",
        },
        columns: [
          	{ data: null },

            { data: 'orderId' },

            {
            	data: 'name',
            	render: checkName
            },

            {
                data: 'mobile',
                render: checkMobile
            },

            {
                data: 'orderDate'
            },

            {
                data: 'total',
                render: getTotal
            },

            {
                data: 'totalDiscount',
                render: getTotalDiscount
            },
            {
                data: 'specialDiscount',
                render: getSpecialDiscount
            },

            {
                data: 'totalTax',
                render: getTotalTax
            },
            {
                data: 'grandTotal',
                render: getGrandTotal
            },
            {
                data: 'salesReturn',
                render: getSalesReturn
            },

            {
                data: 'id',
                render: getBtns
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
    });


    t.on('order.dt search.dt', function () {

	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#order_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();


});

function checkName(data, type, full, meta) {
    var name = data;
    if (name === null) {
       name = "Walking Customer"
    } else {
        name = name
    }
     return name;
}

function checkMobile(data, type, full, meta) {
    var mobile = data;
    if (mobile === null) {
       mobile = "N/A"
    } else {
        mobile = mobile
    }
     return mobile;
}

function getTotal(data, type, full, meta) {
    var total = parseFloat(data);
    return total.toFixed(2);
}

function getSpecialDiscount(data, type, full, meta) {
    var total = parseFloat(data);
    return total.toFixed(2);
}

function getTotalDiscount(data, type, full, meta) {
    var totalDiscount = parseFloat(data);
    return totalDiscount.toFixed(2);
}

function getSalesReturn(data, type, full, meta) {
    // var grandTotal = parseFloat(data);
    var zero=0;
    if (data=='') {
        var salesReturn = zero.toFixed(2);
    }
    else{
        var salesReturn = '<a type="button" class="edit_btn btn btn-info btn-sm" href="/sales-return-details/' + data.return_number + '">' + parseFloat(data.net_return).toFixed(2) +'</a>'
    }
    return salesReturn;
}

function getGrandTotal(data, type, full, meta) {
    var grandTotal = parseFloat(data);
    return grandTotal.toFixed(2);
}

function getTotalTax(data, type, full, meta) {
    var totalTax = parseFloat(data);
    return totalTax.toFixed(2);
}


function getBtns(data, type, full, meta) {

    var id = data;
    return '<a type="button" class="edit_btn btn btn-info btn-sm" href="/order-product-list/'+id+'"><i class="fas fa-info-circle"></i> Details</a>';
}
