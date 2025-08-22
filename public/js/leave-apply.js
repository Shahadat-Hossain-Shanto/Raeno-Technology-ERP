$(document).ready(function () {

	$(document).on('submit', '#AddLeaveApplyForm', function (e) {
		e.preventDefault();

		var employeeName = $('#employee :selected').text()
		var leaveTypeName = $('#leavetype :selected').text()

		let formData = new FormData($('#AddLeaveApplyForm')[0]);
		formData.append('employeename', employeeName);
		formData.append('leavetypename', leaveTypeName);

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
			url: "/leave-apply",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);	
				if($.isEmptyObject(response.error)){
					$(location).attr('href','/leave-apply-list');
                    $.notify("Application submitted successfully.", {className: 'success', position: 'bottom right'});   
             		// $(location).attr('href','/leave-apply-list');
             		// resetButton()

                }else{
                	// console.log(response.error)
                	$('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
			}
		});
	});

	function printErrorMsg (message) {
            $('#wrongemployee').empty();
            $('#wrongleavetype').empty();
            $('#wrongstartdate').empty();
            $('#wrongenddate').empty();

			if(message.employee == null){
				employee = ""
			}else{
				employee = message.employee[0]
			}

			if(message.leavetype == null){
				leavetype = ""
			}else{
				leavetype = message.leavetype[0]
			}

			if(message.startdate == null){
				startdate = ""
			}else{
				startdate = message.startdate[0]
			}

			if(message.enddate == null){
				enddate = ""
			}else{
				enddate = message.enddate[0]
			}

            $('#wrongemployee').append('<span id="">'+employee+'</span>');
            $('#wrongleavetype').append('<span id="">'+leavetype+'</span>');
            $('#wrongstartdate').append('<span id="">'+startdate+'</span>');
            $('#wrongenddate').append('<span id="">'+enddate+'</span>');
        // });
    }
});

$(document).on('change', '#enddate', function (e) {
	e.preventDefault();
	countDays()
})

$(document).on('change', '#startdate', function (e) {
	e.preventDefault();
	countDays()
})

$(document).on('change', '#leavetype', function (e) {
	e.preventDefault();
	countDays()
})

function countDays(){
	const diffDays = (date, otherDate) => Math.ceil(Math.abs(date - otherDate) / (1000 * 60 * 60 * 24));
	// alert(endDateValue)
	if($('#leavetype').val().length != 0 && $('#startdate').val().length != 0 && $('#enddate').val().length != 0){

		var leaveTypeId = $('#leavetype').val()
		var startDate = $('#startdate').val()
		var endDate = $('#enddate').val()

		if(endDate > startDate){
			$.ajax({
				type: "GET",
				url: "/leave-type/"+leaveTypeId,
				dataType:"json",
				success: function(response){
					if(response.leaveType.holiday_included == "true"){
						// var countDays = diffDays(new Date(startDate), new Date(endDate)) + 1;
						// $('#daycount').val(countDays)
						$.ajax({
							type: "POST",
							url: "/leave-count-with-holidays",
							data: { startDate : startDate, endDate : endDate },
							dataType: 'json',    
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							success: function(response){
								// alert(response.data)
								// console.log(response.data)
								$('#daycount').val(response.data)
								// alert(response.message)
								resetButton()
							}
						})
					}else{
						$.ajax({
							type: "POST",
							url: "/leave-count-without-holidays",
							data: { startDate : startDate, endDate : endDate },
							dataType: 'json',    
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							success: function(response){
								
								// alert(response.message)
								// alert(response.data)

								console.log(response.data)
								$('#daycount').val(response.data)
								resetButton()
							}
						})
					}
				}
			});
			
		}
		else if(endDate == startDate){
			$.ajax({
				type: "GET",
				url: "/leave-type/"+leaveTypeId,
				dataType:"json",
				success: function(response){
					if(response.leaveType.holiday_included == "true"){
						// var countDays = diffDays(new Date(startDate), new Date(endDate)) + 1;
						// $('#daycount').val(countDays)
						$.ajax({
							type: "POST",
							url: "/leave-count-with-holidays",
							data: { startDate : startDate, endDate : endDate },
							dataType: 'json',    
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							success: function(response){
								// alert(response.data)
								// console.log(response.data)
								$('#daycount').val("1")
								// alert(response.message)
								resetButton()
							}
						})
					}else{
						$.ajax({
							type: "POST",
							url: "/leave-count-without-holidays",
							data: { startDate : startDate, endDate : endDate },
							dataType: 'json',    
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
							success: function(response){
								
								// alert(response.message)
								// alert(response.data)

								//console.log(response.data)
								$('#daycount').val("1")
								resetButton()
							}
						})
					}
				}
			});
			
		}else{
			$.notify("Invalid date selection!!!", {className: 'error', position: 'bottom right'});   
		}
		
	}
}

// fetchLeave();
// function fetchLeave(){

// 	// var subscriberId = $('#subscriberid').val();

// 	$.ajax({
// 		type: "GET",
// 		url: "/leave-apply-list-data",
// 		dataType:"json",
// 		success: function(response){
// 			// console.log(response);
// 			// $('tbody').html("");
// 			$.each(response.leave, function(key, item) {

// 				if(item.note == null){
// 					var note = 'N/A'
// 				}else{
// 					var note = item.note
// 				}

// 				if(item.leave_status == 'pending'){
// 					var leave_status = '<span class="badge badge-warning">Pending</span>'
// 				}else if(item.leave_status == 'reject'){
// 					var leave_status = '<span class="badge badge-danger">Rejected</span>'
// 				}else{
// 					var leave_status = '<span class="badge badge-success">Approved</span>'
// 				}

// 				$('tbody').append('\
// 				<tr>\
// 					<td></td>\
// 					<td>'+item.employee_name+'</td>\
// 					<td>'+item.leave_type+'</td>\
// 					<td>'+item.start_date+'</td>\
// 					<td>'+item.end_date+'</td>\
// 					<td>'+note+'</td>\
// 					<td>'+leave_status+'</td>\
// 					<td>\
//                     	<button type="button" value="'+item.id+'" class="action btn btn-danger btn-sm">Action \
//                     	</button>\
//         			</td>\
//         		</tr>');
// 			})	
// 		}
// 	});
// }

$(document).ready(function () {
    var t = $('#leave_table').DataTable({
        ajax: {
            "url": "/leave-apply-list-data",
            "dataSrc": "leave",
        },
        columns: [
            { data: null },
            { data: 'employee_name' },
            { data: 'leave_type' },
            { data: 'start_date' },
            { data: 'end_date' },
            { "render": function ( data, type, row, meta ){ 
                    
                    if(row.note == null){
                        var note = "N/A"
                    }else{
                        var note = row.note
                    }

                    return note
                } 
            },
            { "render": function ( data, type, row, meta ){ 
                    
                    if(row.leave_status == 'pending'){
						var leave_status = '<span class="badge badge-warning">Pending</span>'
					}else if(row.leave_status == 'reject'){
						var leave_status = '<span class="badge badge-danger">Rejected</span>'
					}else{
						var leave_status = '<span class="badge badge-success">Approved</span>'
					}

                    return leave_status
                } 
            },
            { "render": function ( data, type, row, meta ){ 
                    
                    return '<button type="button" value="'+row.id+'" class="update btn btn-primary btn-sm"><i class="fas fa-edit"></i></button>\
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
            var PageInfo = $('#leave_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();

});

//EDIT LEAVE
$(document).on('click', '.update', function (e) {
	e.preventDefault();

	var leaveId = $(this).val();
	// alert(leaveId);
	$('#EDITLeaveApplyMODAL').modal('show');
		
		$.ajax({
			type: "GET",
			url: "/leave-apply-edit/"+leaveId,
			success: function(response){
				if (response.status == 200) {
					$('#edit_startdate').val(response.leave.start_date);
					$('#edit_enddate').val(response.leave.end_date);
					$('#edit_status').val(response.leave.leave_status);
					$('#edit_note').val(response.leave.note);
					$('#leaveid').val(leaveId);
				}
			}
		});
});


//UPDATE LEAVE
$(document).on('submit', '#UPDATELeaveApplyFORM', function (e)
{
	e.preventDefault();

	var id = $('#leaveid').val(); 

	let EditFormData = new FormData($('#UPDATELeaveApplyFORM')[0]);

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
		url: "/leave-apply-edit/"+id,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function(response){
			
			if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#EDITLeaveApplyMODAL').modal('hide');
                // $.notify(response.message, 'success')
                $(location).attr('href','/leave-apply-list');
            }else{
            	// console.log(response.error)
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $('#edit_wrongstartdate').empty();
				$('#edit_wrongenddate').empty();
				$('#edit_wrongstatus').empty();

				if(response.error.startdate == null){
					startdate = ""
				}else{
					startdate = response.error.startdate[0]
				}

				if(response.error.enddate == null){
					enddate = ""
				}else{
					enddate = response.error.enddate[0]
				}

				if(response.error.status == null){
					status = ""
				}else{
					status = response.error.status[0]
				}
				

                $('#edit_wrongstartdate').append('<span id="">'+startdate+'</span>');
                $('#edit_wrongenddate').append('<span id="">'+enddate+'</span>');
                $('#edit_wrongstatus').append('<span id="">'+status+'</span>');
                
            }
		}
	});
});


// $(document).ready( function () {
// 	$('#leave_table').DataTable({
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

function resetButton(){
	
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}


// //DELETE leave application

	$('#leave_table').on('click', '.delete_btn', function(e){
		e.preventDefault();

		var leaveId = $(this).data("value");

		$('#leaveid').val(leaveId);
		$('#DELETEleaveFORM').attr('action', '/leave-delete/'+leaveId);
		$('#DELETEleaveMODAL').modal('show');

	});
	
	

