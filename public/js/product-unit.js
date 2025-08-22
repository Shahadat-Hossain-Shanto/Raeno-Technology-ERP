$(document).ready(function () {
	//CREATE UNIT
	$(document).on('submit', '#AddUnitForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddUnitForm')[0]);

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
			url: "/product-unit-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);
				if($.isEmptyObject(response.error)){
                    // alert('Success')
             		$(location).attr('href','/product-unit-list');

                }else{
                	// console.log(response.error)
                	$('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
			}
		});
	});

	function printErrorMsg (message) {
            $('#wrongunitname').empty();

			if(message.unitname == null){
				unitname = ""
			}else{
				unitname = message.unitname[0]
			}

            $('#wrongunitname').append('<span id="">'+unitname+'</span>');

        // });
    }
});

// fetchUnit();
// function fetchUnit(){

// 	// var subscriberId = $('#subscriberid').val();

// 	$.ajax({
// 		type: "GET",
// 		url: "/product-unit-list-data",
// 		dataType:"json",
// 		success: function(response){
// 			// console.log(response);
// 			// $('tbody').html("");
// 			$.each(response.unit, function(key, item) {

// 				if(item.description == null){
// 					var description = 'N/A'
// 				}else{
// 					var description = item.description
// 				}

// 				$('tbody').append('\
// 				<tr>\
// 					<td></td>\
// 					<td>'+item.name+'</td>\
// 					<td>'+description+'</td>\
// 					<td>\
//         				<button type="button" value="'+item.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i>\
//                     	</button>\
//                     	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+item.id+'"><i class="fas fa-trash"></i></a>\
//         			</td>\
//         		</tr>');
// 			})
// 		}
// 	});
// }

//DISCOUNT LIST
$(document).ready(function () {
    var t = $('#unit_table').DataTable({
        ajax: {
            "url": "/product-unit-list-data",
            "dataSrc": "unit",
        },
        columns: [
          	{ data: null },
            { data: 'name' },
            { "render": function ( data, type, row, meta ){
            		if(row.description == null){
						var description = 'N/A'
					}else{
						var description = row.description
					}
            		return description
	            }
	        },
            { "render": function ( data, type, row, meta ){

            		return '<button type="button" value="'+row.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button>\
                    	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+row.id+'"><i class="fas fa-trash"></i></a>'
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
	    	var PageInfo = $('#unit_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();

});

//EDIT UNIT
$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var unitId = $(this).val();

	$('#EDITUnitMODAL').modal('show');

		$.ajax({
		type: "GET",
		url: "/product-unit-edit/"+unitId,
		success: function(response){
			if (response.status == 200) {
				$('#edit_unitname').val(response.unit.name);
				$('#edit_description').val(response.unit.description);
				$('#unitid').val(unitId);
			}
		}
	});
});

//UPDATE UNIT
$(document).on('submit', '#UPDATEUnitFORM', function (e)
{
	e.preventDefault();

	var id = $('#unitid').val();

	let EditFormData = new FormData($('#UPDATEUnitFORM')[0]);

	EditFormData.append('_method', 'PUT');

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
		url: "/product-unit-edit/"+id,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function(response){

			if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#EDITUnitMODAL').modal('hide');
                // $.notify(response.message, 'success')
                $(location).attr('href','/product-unit-list');
            }else{
            	// console.log(response.error)
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $('#edit_wrongunitname').empty();

				if(response.error.unitname == null){
					unitname = ""
				}else{
					unitname = response.error.unitname[0]
				}

                $('#edit_wrongunitname').append('<span id="">'+unitname+'</span>');


            }
		}
	});
});

//Delete UNIT
$(document).ready( function () {
	$('#unit_table').on('click', '.delete_btn', function(){

		var unitId = $(this).data("value");
        // alert(unitId)

		$('#unitid').val(unitId);
		$('#DELETEUnitFORM').attr('action', '/product-unit-delete/'+unitId);
		$('#DELETEUnitMODAL').modal('show');

	});
});

