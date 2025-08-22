$(document).ready(function () {
    var t = $('#product_table').DataTable({
        ajax: {
            "url": "/product-in-report",
            "dataSrc": "data"
        },
        columns: [
            { data: null },
            { data: "store_name" },
            { "render": function ( data, type, row, meta ){

            	return '<td><span class="badge badge-info"><a style="color: white" href="product-in-details/'+row.store+'/'+row.product+'/'+row.variant_id+'">'+row.product_name+'</a></span></td>';
	            }
	        },{ "render": function ( data, type, row, meta ){

            	return '<td>'+row.variant_name+'</td>';
	            }
	        },
            { data: "qty" },

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


$('#productInReportForm').on('submit',  function (e) {
	e.preventDefault();

	let formData = new FormData($('#productInReportForm')[0]);
		$.ajax({
			type: "POST",
			url: "/product-in-reports",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				onChangeDataTable(response.data)
			}
		})
	// }
})


function onChangeDataTable(json){

	$('#product_table').DataTable().clear().destroy()

	var t = $('#product_table').DataTable({

        data : json,
        columns: [
            { data: null },
            { data: "store_name" },
            { "render": function ( data, type, row, meta ){

            	return '<td><span class="badge badge-info"><a style="color: white" href="product-in-details/'+row.store+'/'+row.product+'/'+row.variant_id+'">'+row.product_name+'</a></span></td>';
	            }
	        },{ "render": function ( data, type, row, meta ){

            	return '<td>'+row.variant_name+'</td>';
	            }
	        },
            { data: "qty" },

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
}


function resetButton(){
	$('#form_div').find('form')[0].reset();
	// $("#product_table").find("tr:gt(0)").remove();
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}



