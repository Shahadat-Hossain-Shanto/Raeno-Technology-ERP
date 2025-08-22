$(document).ready(function () {

	$(document).on('submit', '#AddWeekendForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddWeekendForm')[0]);

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
			url: "/weekend-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);	
				if($.isEmptyObject(response.error)){

                    // alert(response.message)
             		$(location).attr('href','/weekend-list');

                }else{
                	// console.log(response.error)
                	$('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
			}
		});
	});

	function printErrorMsg (message) {
        // $(".print-error-msg").find("ul").html('');
        // $(".print-error-msg").css('display','block');

        // $.each( message, function( key, item ) {
            // $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            $('#wrongweekendname').empty();
            

			if(message.weekendname == null){
				weekendname = ""
			}else{
				weekendname = message.weekendname[0]
			}


            $('#wrongweekendname').append('<span id="">'+weekendname+'</span>');
            
        // });
    }
});

//WEEKEND LIST

// fetchWeekend();
// function fetchWeekend(){

// 	// var subscriberId = $('#subscriberid').val();

// 	$.ajax({
// 		type: "GET",
// 		url: "/weekend-list-data",
// 		dataType:"json",
// 		success: function(response){
// 			// console.log(response);
// 			// $('tbody').html("");
// 			$.each(response.weekend, function(key, item) {

// 				$('tbody').append('\
// 				<tr>\
// 					<td></td>\
// 					<td>'+item.weekend_name+'</td>\
// 					<td>\
//                     	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+item.id+'"><i class="fas fa-trash"></i></a>\
//         			</td>\
//         		</tr>');
// 			})	
// 		}
// 	});
// }

$(document).ready(function () {
    var t = $('#weekend_table').DataTable({
        ajax: {
            "url": "/weekend-list-data",
            "dataSrc": "weekend",
        },
        columns: [
            { data: null },
            { data: 'weekend_name' },
            { "render": function ( data, type, row, meta ){ 
                    
                    return '<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+row.id+'" title="Delete"><i class="fas fa-trash"></i></a>'
                } 
            },
        ],
        columnDefs: [
            {
                searchable: true,
                orderable: true,
                targets: 0,
            },
        ],
        order: [[1, 'desc']],
        pageLength : 10,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });


    t.on('order.dt search.dt', function () {

        t.on( 'draw.dt', function () {
            var PageInfo = $('#weekend_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();

});

//Delete WEEKEND
$(document).ready( function () {
	$('#weekend_table').on('click', '.delete_btn', function(){

		var weekendId = $(this).data("value");

		$('#weekendid').val(weekendId);
		$('#DELETEWeekendFORM').attr('action', '/weekend-delete/'+weekendId);
		$('#DELETEWeekendMODAL').modal('show');

	});
});


//DATA TABLE
// $(document).ready( function () {
// 	$('#weekend_table').DataTable({
// 	    pageLength : 10,
// 	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
// 	    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
// 		    //debugger;
// 		    var index = iDisplayIndexFull + 1;
// 		    $("td:first", nRow).html(index);
// 		    return nRow;
// 	  	},
// 	});
// });