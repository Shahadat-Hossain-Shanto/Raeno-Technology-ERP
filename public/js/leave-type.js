$(document).ready(function () {
	//CREATE LEAVE TYPE
	$(document).on('submit', '#AddLeaveTypeForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddLeaveTypeForm')[0]);

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
			url: "/leave-type-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);	
				if($.isEmptyObject(response.error)){
                    
             		$(location).attr('href','/leave-type-list');

                }else{
                	// console.log(response.error)
                	$('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
			}
		});
	});

	function printErrorMsg (message) {
            $('#wrongleavetype').empty();

			if(message.leavetype == null){
				leavetype = ""
			}else{
				leavetype = message.leavetype[0]
			}
			

            $('#wrongleavetype').append('<span id="">'+leavetype+'</span>');
        // });
    }
});

//LEAVE TYPE LIST

// 

$(document).ready(function () {
    var t = $('#leave_type_table').DataTable({
        ajax: {
            "url": "/leave-type-list-data",
            "dataSrc": "leaveType",
        },
        columns: [
            { data: null },
            { data: 'leave_type' },
            { "render": function ( data, type, row, meta ){ 
                    
                    if(row.holiday_included == "true"){
                        var holiday_included = "Yes"
                    }else{
                        var holiday_included = "No"
                    }

                    return holiday_included
                } 
            },
            { "render": function ( data, type, row, meta ){ 
                    
                    return '<button type="button" value="'+row.id+'" class="edit_btn btn btn-secondary btn-sm" title="Edit"><i class="fas fa-edit"></i></button>\
                        <a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+row.id+'" title="Delete"><i class="fas fa-trash"></i></a>'
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
            var PageInfo = $('#leave_type_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();

});

//EDIT LEAVE TYPE
$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var leavetypeId = $(this).val();
	// alert(leavetypeId);
	$('#EDITLeaveTypeMODAL').modal('show');
		
		$.ajax({
		type: "GET",
		url: "/leave-type-edit/"+leavetypeId,
		success: function(response){
			if (response.status == 200) {
				$('#edit_leavetype').val(response.leaveType.leave_type);
				$('#leavetypeid').val(leavetypeId);
			}
		}
	});
});

//UPDATE LEAVE TYPE
$(document).on('submit', '#UPDATELeaveTypeFORM', function (e)
{
	e.preventDefault();

	var id = $('#leavetypeid').val(); 

	let EditFormData = new FormData($('#UPDATELeaveTypeFORM')[0]);

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
		url: "/leave-type-edit/"+id,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function(response){
			
			if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#EDITLeaveTypeMODAL').modal('hide');
                // $.notify(response.message, 'success')
                $(location).attr('href','/leave-type-list');
            }else{
            	// console.log(response.error)
                // printErrorMsg(response.error);
                
                $('body').loadingModal('destroy');
                $('#edit_wrongleavetype').empty();

				if(response.error.leavetype == null){
					leavetype = ""
				}else{
					leavetype = response.error.leavetype[0]
				}
				
				

                $('#edit_wrongleavetype').append('<span id="">'+leavetype+'</span>');
                
            }
		}
	});
});

//Delete LEAVE TYPE
$(document).ready( function () {
	$('#leave_type_table').on('click', '.delete_btn', function(){

		var leavetypeId = $(this).data("value");

		$('#leavetypeid').val(leavetypeId);
		$('#DELETELeaveTypeFORM').attr('action', '/leave-type-delete/'+leavetypeId);
		$('#DELETELeaveTypeMODAL').modal('show');

	});
});


// //DATA TABLE
// $(document).ready( function () {
// 	$('#leave_type_table').DataTable({
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