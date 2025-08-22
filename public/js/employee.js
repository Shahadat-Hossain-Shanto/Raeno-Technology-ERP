$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	//add

	$('#salarygrade_hourly_div').hide()
	
	$('select[name="salarytype"]').on('change', function () {
		// var payment_type=$('#paymenttype').find("option:selected").text()
		var salarytype = $(this).val();

		if(salarytype == 'hourly'){
			$('#salarygrade_hourly_div').show()
			$('#salarygrade_div').hide()
		}
		else{
			$('#salarygrade_div').show()
			$('#salarygrade_hourly_div').hide()
		}
	});

	$('select[name="salarytype"]').on('change', function () {
		// var payment_type=$('#paymenttype').find("option:selected").text()
		var salarytype = $(this).val();

		if(salarytype == 'hourly'){
			$('#salarygrade_hourly_div').show()
			$('#salarygrade_div').hide()
		}
		else{
			$('#salarygrade_div').show()
			$('#salarygrade_hourly_div').hide()
		}
	});

	//edit
	
	// var edit_hourlypaymentamount=$('#edit_hourlypaymentamount_hold').val()
	// var type=$('#edit_salarytype').find("option:selected").text()

	var edit_hourlypaymentamount=$('#edit_hourlypaymentamount_hold').val()
	if(edit_hourlypaymentamount != 0){
		
		$('#edit_salarygrade_div').hide()
		$('#edit_salarygrade_hourly_div').show()
		// var hourlypaymentamount=$('#edit_hourlypaymentamount').val(edit_hourlypaymentamount)
		
	}

	else{
		$('#edit_salarygrade_div').show()
		$('#edit_salarygrade_hourly_div').hide()
		// var hourlypaymentamount=$('#edit_hourlypaymentamount').val('');
		
	}
	
	$('#edit_salarytype').on('change', function () {
		var edit_hourlypaymentamount=$('#edit_hourlypaymentamount_hold').val()
		// var payment_type=$('#paymenttype').find("option:selected").text()
		var salarytype = $(this).val();

		if(salarytype == 'hourly'){
			$('#edit_salarygrade_hourly_div').show()
			$('#edit_salarygrade_div').hide()
		 $('#edit_hourlypaymentamount').val(edit_hourlypaymentamount)

		}
		else{
			$('#edit_salarygrade_div').show()
			$('#edit_salarygrade_hourly_div').hide()
		    $('#edit_hourlypaymentamount').val('');

		}
	});
	// $('#salarygrade_hourly_div').hide()
	
	


	//CREATE EMP
	$(document).on('submit', '#AddEmployeeForm', function (e) {
		e.preventDefault();

		var employeedepartment_name = $("#employeedepartment").find("option:selected").text()
		var salary_grade = $("#salarygrade").find("option:selected").text()
		
	
	// //console.log('employeedepartment '+employeedepartment)

		let formData = new FormData($('#AddEmployeeForm')[0]);

		formData.append('employeedepartment_name', employeedepartment_name);
		formData.append('salary_grade', salary_grade);

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
			url: "/employee-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				////console.log(response.message);	
			
				if ($.isEmptyObject(response.error)) {
					$(location).attr('href','/employee-list');
                } else {
                	$('body').loadingModal('destroy');
					printErrorMsg(response.error);
                }
			}
		});

	});

	function printErrorMsg(message) {
		//console.log ('error')
		$('#wrong_employeename').empty();
		$('#wrong_phone').empty();
		$('#wrong_designation').empty();
		$('#wrong_email').empty();
		$('#wrong_employeetype').empty();
		$('#wrong_bloodgroup').empty();
		$('#wrong_address').empty();
		$('#wrong_city').empty();
		$('#wrong_image').empty();
		$('#wrong_status').empty();
		$('#wrong_employeedepartment').empty();
		$('#wrong_salarygrade').empty();


	
	
		if (message.employee_name == null) {
			employee_name = ""
		} else {
			employee_name = message.employee_name[0]
		}
	
		if (message.phone == null) {
			phone = ""
		} else {
			phone = message.phone[0]
		}
	
		if (message.email == null) {
			email = ""
		} else {
			email = message.email[0]
		}
	
		if (message.designation == null) {
			designation = ""
		} else {
			designation = message.designation[0]
		}
	
		if (message.employee_type == null) {
			employee_type = ""
		} else {
			employee_type = message.employee_type[0]
		}
	
		if (message.blood_group == null) {
			blood_group = ""
		} else {
			blood_group = message.blood_group[0]
		}
	
		if (message.address == null) {
			address = ""
		} else {
			address = message.address[0]
		}
	
		if (message.city == null) {
			city = ""
		} else {
			city = message.city[0]
		}
	
		if (message.image == null) {
			image = ""
		} else {
			image = message.image[0]
		}
	
		if (message.status == null) {
			status = ""
		} else {
			status = message.status[0]
		}

		if (message.employeedepartment == null) {
			employeedepartment = ""
		} else {
			employeedepartment = message.employeedepartment[0]
		}

		if (message.salarygrade == null) {
			salarygrade = ""
		} else {
			salarygrade = message.salarygrade[0]
		}
	
	
	
	
	
		$('#wrong_employeename').append('<span id="">' + employee_name + '</span>');
		$('#wrong_phone').append('<span id="">' + phone + '</span>');
		$('#wrong_email').append('<span id="">' + email + '</span>');
		$('#wrong_designation').append('<span id="">' + designation + '</span>');
		$('#wrong_employeetype').append('<span id="">' + employee_type + '</span>');
		$('#wrong_bloodgroup').append('<span id="">' + blood_group + '</span>');
		$('#wrong_address').append('<span id="">' + address + '</span>');
		$('#wrong_city').append('<span id="">' + city + '</span>');
		$('#wrong_image').append('<span id="">' + image + '</span>');
		$('#wrong_status').append('<span id="">' + status + '</span>');
		$('#wrong_employeedepartment').append('<span id="">' + employeedepartment + '</span>');
		$('#wrong_salarygrade').append('<span id="">' + salarygrade + '</span>');
		



	
	
		// });
	}

});


//EMP LIST
// fetchEmp();
// function fetchEmp(){
// 	$.ajax({
// 		type: "GET",
// 		url: "/employee-list-dataX",
// 		dataType:"json",
// 		success: function(response){
// 			// //console.log(response.employee+'asdasdasd')
// 			// alert(response.message)
// 			$('tbody').html("");
// 			$.each(response.employee, function(key, item) {
// 				if (item.status == 1) {
// 					var status='Active'
// 				} else{
// 					var status='Inactive'
// 				}

// 				$('tbody').append('<tr>\
// 				<td>'+item.id+'</td>\
// 				<td>'+item.employee_name+'</td>\
// 				<td>'+item.designation+'</td>\
// 				<td>'+item.email+'</td>\
// 				<td>'+item.phone+'</td>\
// 				<td>'+item.address+'</td>\
//                 <td>'+status+'</td>\
//                 <td>'+item.salary_grade+'</td>\
// 				<td><img src="uploads/employee/'+item.image+'" width="50px" height="50px" alt="image" class="rounded-circle"></td>\
// 				<td>\
// 					<button type="button" value="'+item.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i>\
//                     </button>\
//                 	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+item.id+'"><i class="fas fa-trash"></i></a>\
//     			</td>\
//     		</tr>');
// 			})	
// 		}
// 	});
// }


$(document).ready(function () {
    var t = $('#emp_table').DataTable({
        ajax: {
            "url": "/employee-list-dataX",
            "dataSrc": "employee",
        },
        columns: [
            { data: null },
            { data: 'id' },
            { data: 'employee_name' },
            { data: 'designation' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'address' },
            { "render": function ( data, type, row, meta ){ 
                    
                    if(row.status == 1){
                        var status = "Active"
                    }else{
                        var status = "Inactive"
                    }

                    return status
                } 
            },
            { data: 'salary_grade' },
            { "render": function ( data, type, row, meta ){ 
                    
                    return '<img src="uploads/employee/'+row.image+'" width="50px" height="50px" alt="image" class="rounded-circle">'
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
            var PageInfo = $('#emp_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();

});

//EDIT EMP
$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var empId = $(this).val();
	
	window.location.href='/employee-edit/'+empId;

});

//Update EMP

	$(document).on('submit', '#UpdateEmployeeForm', function (e){
		e.preventDefault();

		var user_id = $('#userid').val();
		var employeedepartment_name = $("#edit_employeedepartment").find("option:selected").text()
		var salary_grade = $("#edit_salarygrade").find("option:selected").text()

		// alert(productId)
		let EditFormData = new FormData($('#UpdateEmployeeForm')[0]);
		EditFormData.append('employeedepartment_name', employeedepartment_name);
		EditFormData.append('salary_grade', salary_grade);

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
			url: "/employee-edit/"+user_id,
			data: EditFormData,
			contentType: false,
			processData: false,
			headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			success: function (response) {
			// //console.log(response.message);
			// imageUpdate()
			// $.notify(response.message);	
			if($.isEmptyObject(response.error)){
				// imageUpdate()
				 // $.notify(response.message, 'success');	
				 // resetButton() 
				  $(location).attr('href','/employee-list');
	
			 }else{
				 // //console.log(response.error)
				 $('body').loadingModal('destroy');
				 printUpdateErrorMsg(response.error);
			 }            
			
		}
		});
	});
	
	function printUpdateErrorMsg (message) {
		$('#edit_wrong_employeename').empty();
		$('#edit_wrong_phone').empty();
		$('#edit_wrong_designation').empty();
		$('#edit_wrong_email').empty();
		$('#edit_wrong_employeetype').empty();
		$('#edit_wrong_bloodgroup').empty();
		$('#edit_wrong_address').empty();
		$('#edit_wrong_city').empty();
		// $('#edit_wrong_image').empty();
		$('#edit_wrong_status').empty();
		$('#edit_wrong_salarygrade').empty();

		if(message.employee_name == null){
			employee_name = ""
		}else{
			employee_name = message.employee_name[0]
		}

		if(message.email == null){
			email = ""
		}else{
			email = message.email[0]
		}

		if(message.phone == null){
			phone = ""
		}else{
			phone = message.phone[0]
		}

		if(message.designation == null){
			designation = ""
		}else{
			designation = message.designation[0]
		}

		if(message.employee_type == null){
			employee_type = ""
		}else{
			employee_type = message.employee_type[0]
		}

		if(message.blood_group == null){
			blood_group = ""
		}else{
			blood_group = message.blood_group[0]
		}

		if(message.address == null){
			address = ""
		}else{
			address = message.address[0]
		}

		if(message.city == null){
			city = ""
		}else{
			city = message.city[0]
		}

		if(message.status == null){
			status = ""
		}else{
			status = message.status[0]
		}

		if(message.salarygrade == null){
			salarygrade = ""
		}else{
			salarygrade = message.salarygrade[0]
		}


		// if(message.password_confirmation == null){
		// 	password_confirmation = ""
		// }else{
		// 	password_confirmation = message.password_confirmation[0]
		// }

		$('#edit_wrong_employeename').append('<span id="">'+employee_name+'</span>');
		$('#edit_wrong_phone').append('<span id="">'+phone+'</span>');
		$('#edit_wrong_designation').append('<span id="">'+designation+'</span>');
		 $('#edit_wrong_email').append('<span id="">'+email+'</span>');
		$('#edit_wrong_employeetype').append('<span id="">'+employee_type+'</span>');
		$('#edit_wrong_bloodgroup').append('<span id="">'+blood_group+'</span>');
		$('#edit_wrong_address').append('<span id="">'+address+'</span>');
		$('#edit_wrong_city').append('<span id="">'+city+'</span>');
		$('#edit_wrong_status').append('<span id="">'+status+'</span>');
		$('#edit_wrong_salarygrade').append('<span id="">'+salarygrade+'</span>');


		

		
	// });
}

//DELETE Employee
	$(document).ready( function () {
		$('#emp_table').on('click', '.delete_btn', function(){

			var empid = $(this).data("value");

			$('#supplierid').val(empid);

			$('#DELETEEmpFORM').attr('action', '/employee-delete/'+empid);

			$('#DELETEEmpMODAL').modal('show');

		});
	});

function resetButton() {
	$('form').on('reset', function () {
		setTimeout(function () {
			$('.selectpicker').selectpicker('refresh');
		});
	});
}


//DATA TABLE
// $(document).ready( function () {
// 	$('#emp_table').DataTable({
// 	    pageLength : 10,
// 	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
// 	});
// });





