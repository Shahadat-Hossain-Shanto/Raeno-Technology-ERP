$(document).ready(function () {

    $(document).on('submit', '#AddDesignationForm', function (e) {
        e.preventDefault();
        let formData = new FormData($('#AddDesignationForm')[0]);

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
            url: "/add-designation",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if ($.isEmptyObject(response.error)) {
                    $(location).attr('href', '/designation-list');
                } else {
                    $('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
            }
        });

        function printErrorMsg(message) {
            $('#wrong_designation_name').empty();
            // $('#wrong_designation_description').empty();


            if (message.designation_name == null) {
                designation_name = ""
            } else {
                designation_name = message.designation_name[0]
            }

            // if (message.designation_description == null) {
            //     designation_description = ""
            // } else {
            //     designation_description = message.designation_description[0]
            // }



            $('#wrong_designation_name').append('<span id="">' + designation_name + '</span>');
            // $('#wrong_designation_description').append('<span id="">' + designation_description + '</span>');



            // });
        }
    });

    function resetButton() {
        $('form').on('reset', function () {
            setTimeout(function () {
                $('.selectpicker').selectpicker('refresh');
            });
        });
    }

});

//list
// fetchDesignation();

// function fetchDesignation() {
//     $.ajax({
//         type: "GET",
//         url: "/designation-list-data",
//         dataType: "json",
//         success: function (response) {
//             // console.log(response.designation)
//             $('tbody').html("");
//             $.each(response.designation, function (key, item) {

//                 if(item.designation_description == null){
//                     designation_description = "N/A"
//                 }else{
//                     designation_description = item.designation_description
//                 }

//                 $('tbody').append('<tr>\
// 					<td>1</td>\
// 					<td>' + item.designation_name + '</td>\
// 					<td>' + designation_description + '</td>\
// 					<td>\
//         				<button type="button" value="' + item.id + '" class="edit_btn btn btn-secondary btn-sm" title="Edit"><i class="fas fa-edit"></i>\
//                     	</button>\
//                     	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="' + item.id + '" title="Delete"><i class="fas fa-trash"></i></a>\
//         			</td>\
//         		</tr>');
//             })
//         }
//     });
// }

$(document).ready(function () {
    var t = $('#designation_table').DataTable({
        ajax: {
            "url": "/designation-list-data",
            "dataSrc": "designation",
        },
        columns: [
            { data: null },
            { data: 'designation_name' },
            { "render": function ( data, type, row, meta ){ 
                    
                    if(row.designation_description == null){
                        var designation_description = "N/A"
                    }else{
                        var designation_description = row.designation_description
                    }

                    return designation_description
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
            var PageInfo = $('#designation_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();

});

//edit
$(document).on('click', '.edit_btn', function (e) {
    e.preventDefault();

    var desognation_id = $(this).val();
    // alert(clientId);
    $('#EDITDesignationMODAL').modal('show');
        
        $.ajax({
        type: "GET",
        url: "/designation-edit/"+desognation_id,
        success: function(response){
            if (response.status == 200) {
                // console.log(response.designation.designation_description)
                $('#edit_designation_name').val(response.designation.designation_name);
                $('#edit_designation_description').val(response.designation.designation_description);
                $('#designationid').val(response.designation.id);
                
            }
        }
    });
});

//update
$(document).on('submit', '#UPDATEDesignationFORM', function (e)
	{
		e.preventDefault();

		var id = $('#designationid').val(); 

		let EditFormData = new FormData($('#UPDATEDesignationFORM')[0]);

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
			url: "/designation-edit/"+id,
			data: EditFormData,
			contentType: false,
			processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			success: function(response){
				// if(response.status == 200){
				// 	$('#EDITCustomerMODAL').modal('hide');
				// 	// alert(response.message);
				// 	// fetchCustomer();
				// 	$(location).attr('href','/customer-list');
				// }
				if($.isEmptyObject(response.error)){
                    // alert(response.message);
                    $('#EDITDesignationMODAL').modal('hide');
                    $.notify(response.message, 'success')
                    $(location).attr('href','/designation-list');
                }else{
                	$('body').loadingModal('destroy');
                    printEditErrorMsg(response.error);
                }
			}
		});
	});

//update error msg
    function printEditErrorMsg(message) {
        $('#edit_wrong_designation_name').empty();
        // $('#edit_wrong_designation_description').empty();


        if (message.designation_name == null) {
            designation_name = ""
        } else {
            designation_name = message.designation_name[0]
        }

        // if (message.designation_description == null) {
        //     designation_description = ""
        // } else {
        //     designation_description = message.designation_description[0]
        // }



        $('#edit_wrong_designation_name').append('<span id="">' + designation_name + '</span>');
        // $('#edit_wrong_designation_description').append('<span id="">' + designation_description + '</span>');



        // });
    }

    //delete
    $(document).ready( function () {
		$('#designation_table').on('click', '.delete_btn', function(){

			var id = $(this).data("value");

			$('#delete_designationid').val(id);

			$('#DELETEDesignationFORM').attr('action', '/designation-delete/'+id);

			$('#DELETEDesignationMODAL').modal('show');

		});
	});


//data table
// $(document).ready(function () {
//     $('#designation_table').DataTable({
//         pageLength: 10,
//         lengthMenu: [
//             [5, 10, 20, -1],
//             [5, 10, 20, 'Todos']
//         ],
//         "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
//             //debugger;
//             var index = iDisplayIndexFull + 1;
//             $("td:first", nRow).html(index);
//             return nRow;
//         },
//     });
// });
