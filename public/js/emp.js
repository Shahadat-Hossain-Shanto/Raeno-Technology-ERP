$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE EMP
	$(document).on('submit', '#AddEmpForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddEmpForm')[0]);

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
				//console.log(response.message);	
				$(location).attr('href','/employee-list');
			}
		});

	});

});

//EMP LIST
fetchEmp();
function fetchEmp(){
	$.ajax({
		type: "GET",
		url: "/api/employee-list",
		dataType:"json",
		success: function(response){
			$('tbody').html("");
			$.each(response.employee, function(key, item) {
				$('tbody').append('<tr>\
				<td>'+item.id+'</td>\
				<td>'+item.empName+'</td>\
				<td>'+item.empEmail+'</td>\
				<td>'+item.empMobile+'</td>\
				<td>'+item.role+'</td>\
				<td>\
					<button type="button" value="'+item.id+'" class="edit_btn btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i>\
                    </button>\
                	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+item.id+'"><i class="fas fa-trash"></i></a>\
    			</td>\
    		</tr>');
			})	
		}
	});
}

//EDIT EMP
	$(document).on('click', '.edit_btn', function (e) {
		e.preventDefault();

		var empId = $(this).val();
		// alert(empId);
		$('#EDITEmpMODAL').modal('show');
			
			$.ajax({
			type: "GET",
			url: "/employee-edit/"+empId,
			success: function(response){
				if (response.status == 200) {
					$('#edit_empname').val(response.employee.empName);
					$('#edit_empemail').val(response.employee.empEmail);
					$('#edit_empmobile').val(response.employee.empMobile);
					$('#edit_role').val(response.employee.role);
					$('#empid').val(empId);
				}
			}
		});
	});

	//UPDATE EMP
	$(document).on('submit', '#UPDATEEmpFORM', function (e)
	{
		e.preventDefault();

		var id = $('#empid').val(); 

		let EditFormData = new FormData($('#UPDATEEmpFORM')[0]);

		EditFormData.append('_method', 'PUT');

		$.ajax({
			type: "POST",
			url: "/employee-edit/"+id,
			data: EditFormData,
			contentType: false,
			processData: false,
			success: function(response){
				if(response.status == 200){
					$('#EDITEmpMODAL').modal('hide');
					alert(response.message);
					fetchEmp();
				}
			}
		});
	});

//DELETE EMP
$(document).ready( function () {
	$('#emp_table').on('click', '.delete_btn', function(){

		var empId = $(this).data("value");

		$('#empid').val(empId);

		$('#DELETEEmpFORM').attr('action', '/employee-delete/'+empId);

		$('#DELETEEmpMODAL').modal('show');

	});
});


//DATA TABLE
$(document).ready( function () {
	$('#emp_table').DataTable({
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    scrollY:        450,
	    scrollCollapse: true,
	    scroller:       true,
	});
});