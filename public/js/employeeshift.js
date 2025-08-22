$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	// CREATE SUPPLIER
	

	function printErrorMsg (message) {
        // $(".print-error-msg").find("ul").html('');
        // $(".print-error-msg").css('display','block');

        // $.each( message, function( key, item ) {
            // $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            $('#wrongcustomername').empty();
            $('#wrongmobile').empty();
			

			if(message.customername == null){
				customername = ""
			}else{
				customername = message.customername[0]
			}
			if(message.mobile == null){
				mobile = ""
			}else{
				mobile = message.mobile[0]
			}

            $('#wrongcustomername').append('<span id="">'+customername+'</span>');
            $('#wrongmobile').append('<span id="">'+mobile+'</span>');

        // });
    }

});

	

	// EDIT SUPPLIER
	$(document).on('click', '.edit_btn', function (e) {
		e.preventDefault();

		var shiftId = $(this).val();
		//alert(shiftId);
		$('#EDITshiftMODAL').modal('show');
			$.ajax({
			type: "GET",
			url: "/employeeshift-edit/"+shiftId,
			success: function(response){
               // console.log(response);
				if (response.status == 200){
					$('#edit_name').val(response.ShiftAllocation.name);
					$('#edit_shift_name').val(response.ShiftAllocation.shift_name);
					$('#edit_startdate').val(response.ShiftAllocation.start_date);
					$('#edit_enddate').val(response.ShiftAllocation.end_date);
					//$('#employee_id').val(response.ShiftAllocation.employee_id);
					//$('#shiftwise_id').val(response.ShiftAllocation.shift_id);
					//alert(response.ShiftAllocation.end_date)
					
					
					$('#shiftId').val(shiftId);
				}
                else{
                    //alert("Hi");
                }
			}
		});
	});

	// //UPDATE SUPPLIER
	$(document).on('submit', '#UPDATEshiftFORM', function (e)
	{
		e.preventDefault();

		var id = $('#shiftId').val();
		//alert(id) 
		var data ={
			'shift_name': $('#edit_shift_name').val(),
			'start_date': $('#edit_startdate').val(),
			'end_date': $('#edit_enddate').val()
		}
		
		//let EditFormData = new FormData($('#UPDATEshiftFORM')[0]);
		//alert(EditFormData);
		
		//EditFormData.append('_method', 'PUT');
		$(document).ready(function () {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
		});

		$.ajax({
			ajaxStart: $('body').loadingModal({
			  position: 'auto',
			  text: 'Please Wait',
			  color: '#fff',
			  opacity: '0.7',
			  backgroundColor: 'rgb(0,0,0)',
			  animation: 'foldingCube'
			}),
			type: "PUT",
			url: "/employeeshift-update/"+id,
			data: data,
			datatype: "json",
			success: function(response){
				//console.log(response);
				// if(response.status == 200){
				// 	$('#EDITCustomerMODAL').modal('hide');
				// 	// alert(response.message);
				// 	$(location).attr('href','/customer-list');
				// }
				if(response.status == 200){
                    // alert(response.message);
                    $('#EDITshiftMODAL').modal('hide');
                    $.notify(response.message, 'success')
                    $(location).attr('href','/employeeshift');
                }else{
					alert("hi");
                	// console.log(response.error)
                    // printErrorMsg(response.error);
                    // $('body').loadingModal('destroy');
                    // $('#edit_wrongcustomername').empty();
					// $('#edit_wrongcontactnumber').empty();
				

					// if(response.error.customername == null){
					// 	customername = ""
					// }else{
					// 	customername = response.error.customername[0]
					// }
					// if(response.error.mobile == null){
					// 	mobile = ""
					// }else{
					// 	mobile = response.error.mobile[0]
					// }
					

	                // $('#edit_wrongcustomername').append('<span id="">'+customername+'</span>');
	                // $('#edit_wrongcontactnumber').append('<span id="">'+mobile+'</span>');
	       
                }
			}
		});
	});

	// //DELETE SUPPLIER
	$(document).ready( function () {
		$('#employee_shift').on('click', '.delete_btn', function(){

			var shiftId = $(this).data("value");

			$('#shiftId').val(shiftId);

			$('#DELETEshiftFORM').attr('action', '/employee_shift_delete/'+shiftId);

			$('#DELETEshiftMODAL').modal('show');
            //console.log(shiftId);

		});
	});



//list

$(document).ready(function () {
    var t = $('#employee_shift').DataTable({
        ajax: {
            "url": "/employeeshift-data",
            "dataSrc": "ShiftAllocation"
        },
        columns: [
           

            // { data: 'employee_id' },
            { data: 'shift_name' },
            { data: 'name'},
            { data: 'department' },
            { data: 'start_date' },
            { data: 'end_date' },

            
            { 
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


});

function getBtns(data, type, row, meta) {

    var id = row.id;
    return '<button type="button" value="'+id+'" class="edit_btn btn btn-secondary btn-sm" title="Edit"><i class="fas fa-edit"></i></button>\
        	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+id+'" title="Delete"><i class="fas fa-trash"></i></a>';
}
