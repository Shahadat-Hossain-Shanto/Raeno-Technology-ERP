$(document).ready(function () {
	//CREATE BATCH
	$(document).on('submit', '#AddEmployeeDepartmentForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddEmployeeDepartmentForm')[0]);

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
			url: "/employee-department-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);	
				if($.isEmptyObject(response.error)){
                    
             		$(location).attr('href','/employee-department-list');

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
            $('#wrongdepartmentname').empty();
            // $('#wrongjobdescription').empty();

			if(message.departmentname == null){
				departmentname = ""
			}else{
				departmentname = message.departmentname[0]
			}
			

            $('#wrongdepartmentname').append('<span id="">'+departmentname+'</span>');
            // $('#wrongexpirydate').append('<span id="">'+expirydate+'</span>');
        // });
    }
});

//BATCH LIST

$(document).ready(function () {
    var t = $('#employee_department_table').DataTable({
        ajax: {
            "url": "/employee-department-list-data",
            "dataSrc": "employeeDepartment",
        },
        columns: [
            { data: null },
            { data: 'department_name' },
            { "render": function ( data, type, row, meta ){ 
                    
                    if(row.job_description == null){
                        var job_description = "N/A"
                    }else{
                        var job_description = row.job_description
                    }

                    return job_description
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
            var PageInfo = $('#employee_department_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();

});

//EDIT BATCH
$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var employeeDepartmentId = $(this).val();
	// alert(employeeDepartmentId);
	$('#EDITEmployeeDepartmentMODAL').modal('show');
		
		$.ajax({
		type: "GET",
		url: "/employee-department-edit/"+employeeDepartmentId,
		success: function(response){
			if (response.status == 200) {
				$('#edit_departmentname').val(response.employeeDepartment.department_name);
				$('#edit_jobdescription').val(response.employeeDepartment.job_description);
				$('#employeedepartmentid').val(employeeDepartmentId);
			}
		}
	});
});

//UPDATE BATCH
$(document).on('submit', '#UPDATEEmployeeDepartmentFORM', function (e)
{
	e.preventDefault();

	var id = $('#employeedepartmentid').val(); 

	let EditFormData = new FormData($('#UPDATEEmployeeDepartmentFORM')[0]);

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
		url: "/employee-department-edit/"+id,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function(response){
			
			if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#EDITEmployeeDepartmentMODAL').modal('hide');
                // $.notify(response.message, 'success')
                $(location).attr('href','/employee-department-list');
            }else{
            	// console.log(response.error)
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $('#edit_wrongdepartmentname').empty();
				// $('#edit_wrongexpirydate').empty();

				if(response.error.departmentname == null){
					departmentname = ""
				}else{
					departmentname = response.error.departmentname[0]
				}
				
				

                $('#edit_wrongdepartmentname').append('<span id="">'+departmentname+'</span>');
                // $('#edit_wrongexpirydate').append('<span id="">'+expirydate+'</span>');
                
            }
		}
	});
});

//Delete BATCH
$(document).ready( function () {
	$('#employee_department_table').on('click', '.delete_btn', function(){

		var employeeDepartmentId = $(this).data("value");

		$('#employeedepartmentid').val(employeeDepartmentId);
		$('#DELETEEmployeeDepartmentFORM').attr('action', '/employee-department-delete/'+employeeDepartmentId);
		$('#DELETEEmployeeDepartmentMODAL').modal('show');

	});
});


//DATA TABLE
// $(document).ready( function () {
// 	$('#employee_department_table').DataTable({
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