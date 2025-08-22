// fetchReturn();
// function fetchReturn(){

// 	// var subscriberId = $('#subscriberid').val();

// 	$.ajax({
// 		type: "GET",
// 		url: "/purchase-return-list-data",
// 		dataType:"json",
// 		success: function(response){
// 			// console.log(response.data);
// 			// $('tbody').html("");
// 			$.each(response.data, function(key, item) {

// 				if(item.note == null){
// 					note = "No notes."
// 				}else{
// 					note = item.note
// 				}

// 				$('tbody').append('\
// 				<tr>\
// 					<td></td>\
// 					<td>'+item.po_no+'</td>\
// 					<td>'+item.return_number+'</td>\
// 					<td>'+item.total_deduction+'</td>\
// 					<td>'+item.net_return+'</td>\
// 					<td>'+note+'</td>\
// 					<td>\
//         				<a type="button" class="details_btn btn btn-info btn-sm" href="/purchase-return-details/'+item.return_number+'" data-value="'+item.return_number+'" title="Details">\
// 						Details</a>\
//         			</td>\
//         		</tr>');
// 			})
// 		}
// 	});
// }

$(document).ready(function () {

    var t = $('#return_table').DataTable({
        ajax: {
            "url": "/purchase-return-list-data",
            "dataSrc": "data"
        },
        columns: [
            { data: null },
            { data: 'po_no' },
            { data: 'return_number' },
            { "render": function ( data, type, row, meta ){ var total_deduction = parseFloat(row.total_deduction); return total_deduction.toFixed(2);} },
            { "render": function ( data, type, row, meta ){ var net_return = parseFloat(row.net_return); return net_return.toFixed(2);} },
            { "render": function ( data, type, row, meta ){

					if(row.note == null){
						note = "N/A"
					}else{
						note = row.note
					}

	            	return note
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
	    // order: [[1, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#return_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});

function getBtns(data, type, row, meta) {

        var return_number = row.return_number;
        return '<a type="button" class="details_btn btn btn-info btn-sm" href="/purchase-return-details/'+return_number+'" data-value="'+return_number+'" title="Details">Details</a>';
    }
