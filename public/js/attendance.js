fetchEmployee();
function fetchEmployee(){

	// var subscriberId = $('#subscriberid').val();

	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!

	var yyyy = today.getFullYear();
	if(dd<10){
		dd='0'+dd
	}
	if(mm<10){
		mm='0'+mm
	}

	today = yyyy+'-'+mm+'-'+dd;

	var count = 1
	$('#date').val(today)

	$.ajax({
		type: "GET",
		url: "/employee-list-data",
		dataType:"json",
		success: function(response){


			if(response.message == "holiday"){

         		$.each(response.data, function(key, item) {

				$('tbody').append('\
					<tr>\
						<td>'+count+'</td>\
						<td>'+item.employee_name+'</td>\
						<td>'+item.designation+'</td>\
						<td>'+item.department+'</td>\
						<td><input type="date" value="'+today+'" class="form-control" name="" id="" disabled></td>\
						<td><input type="time" value="" class="form-control" name="signin" id="signin"></td>\
						<td><input type="time" value="" class="form-control" name="signout" id="signout" onchange=""></td>\
						<td></td>\
						<td>'+response.holidayName+'</td>\
						<td class="hidden">'+item.id+'</td>\
						<td><button class="btn-remove btn btn-danger btn-sm" value="">Reset</button></td>\
	        		</tr>');

	        		count++
				})

            }else if(response.message == "weekend"){

         		$.each(response.data, function(key, item) {


				$('tbody').append('\
					<tr>\
						<td>'+count+'</td>\
						<td>'+item.employee_name+'</td>\
						<td>'+item.designation+'</td>\
						<td>'+item.department+'</td>\
						<td><input type="date" value="'+today+'" class="form-control" name="" id="" disabled></td>\
						<td><input type="time" value="" class="form-control" name="signin" id="signin"></td>\
						<td><input type="time" value="" class="form-control" name="signout" id="signout" onchange=""></td>\
						<td></td>\
						<td>'+response.weekendName+'</td>\
						<td class="hidden">'+item.id+'</td>\
						<td><button class="btn-remove btn btn-danger btn-sm" value="">Reset</button></td>\
	        		</tr>');

	        		count++
				})
            }else{


                $.each(response.data, function(key, item) {

                if(item.stay_time == null){
                	stay_time = ""
                }else{
                	stay_time = item.stay_time
                }

                if(item.attendance_status == null){
                	attendance_status = "Absent"
                }else{
                	attendance_status = item.attendance_status
                }




				$('tbody').append('\
					<tr>\
						<td>'+count+'</td>\
						<td>'+item.employee_name+'</td>\
						<td>'+item.designation+'</td>\
						<td>'+item.department+'</td>\
						<td><input type="date" value="'+today+'" class="form-control" name="" id="" disabled></td>\
						<td><input type="time" value="'+item.sign_in+'" class="form-control" name="signin" id="signin"></td>\
						<td><input type="time" value="'+item.sign_out+'" class="form-control" name="signout" id="signout" onchange=""></td>\
						<td>'+stay_time+'</td>\
						<td>'+attendance_status+'</td>\
						<td class="hidden">'+item.id+'</td>\
						<td><button class="btn-remove btn btn-danger btn-sm" value="">Reset</button></td>\
	        		</tr>');

	        		count++
				})
            }
            dataTableX()
		}
	});

}

$(document).on('change', '#signout', function(e) {
	var signInTime = $(this).closest("tr").find("td:eq(5) input[type='time']").val()
	var signOutTime = $(this).closest("tr").find("td:eq(6) input[type='time']").val()
	if(signOutTime>signInTime){
	var total = ( new Date("1970-1-1 " + signOutTime) - new Date("1970-1-1 " + signInTime) ) / 1000 / 60;

	$status = $(this).closest("tr").find("td:eq(8)")

	// var total = $('.mins').val();

    var hrs = Math.floor(total / 60);

    var min = total % 60;
    var stayTime = hrs + " Hours " + min + " Mins";


    $(this).closest("tr").find("td:eq(7)").text(stayTime)


	var employeeId = $(this).closest("tr").find("td:eq(9)").text()

	let attendance = {}
	attendance["employeeId"]      = employeeId
    attendance["signInTime"]      = signInTime
    attendance["signOutTime"]     = signOutTime


	// console.log(employeeId)
	 $.ajax({
        type: "POST",
        url: "/attendance-status",
        data: JSON.stringify(attendance),
        dataType : "json",
        contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        success: function (response) {
	        // console.log(response.message)
	        $status.text(response.message);

        }
    });}
	else{
		$.notify("Invalid time selection!!!", {className: 'error', position: 'bottom right'});
	}

})

$(document).on('change', '#date', function(e) {

	var date = $(this).val()
	var count = 1
	// alert(date)
	$.ajax({
		type: "GET",
		url: "/employee-list-datewise/"+date,
		dataType:"json",
		success: function(response){
			// alert(response.message)
			// console.log(response.data);


			$('#attendance_table').DataTable().clear().destroy()
			dataTableX()

			if(response.message == "holiday"){

         		// $.notify('No attendance!', {className: 'error', position: 'bottom right'});
         		$.each(response.data, function(key, item) {

				$('tbody').append('\
					<tr>\
						<td>'+count+'</td>\
						<td>'+item.employee_name+'</td>\
						<td>'+item.designation+'</td>\
						<td>'+item.department+'</td>\
						<td><input type="date" value="'+date+'" class="form-control" name="" id=""></td>\
						<td><input type="time" value="" class="form-control" name="signin" id="signin"></td>\
						<td><input type="time" value="" class="form-control" name="signout" id="signout" onchange=""></td>\
						<td></td>\
						<td>'+response.holidayName+'</td>\
						<td class="hidden">'+item.id+'</td>\
						<td><button class="btn-remove btn btn-danger btn-sm" value="">Reset</button></td>\
	        		</tr>');

	        		count++
				})

            }else if(response.message == "weekend"){
            	// alert(response.message)

         		$.each(response.data, function(key, item) {


				$('tbody').append('\
					<tr>\
						<td>'+count+'</td>\
						<td>'+item.employee_name+'</td>\
						<td>'+item.designation+'</td>\
						<td>'+item.department+'</td>\
						<td><input type="date" value="'+date+'" class="form-control" name="" id=""></td>\
						<td><input type="time" value="" class="form-control" name="signin" id="signin"></td>\
						<td><input type="time" value="" class="form-control" name="signout" id="signout" onchange=""></td>\
						<td></td>\
						<td>'+response.weekendName+'</td>\
						<td class="hidden">'+item.id+'</td>\
						<td><button class="btn-remove btn btn-danger btn-sm" value="">Reset</button></td>\
	        		</tr>');

	        		count++
				})
            }else if(response.message == "absent"){
            	// alert(response.message)

         		$.each(response.data, function(key, item) {


     			if(item.attendance_status == null){
                	attendance_status = "Absent"
                }else{
                	attendance_status = item.attendance_status
                }


				$('tbody').append('\
					<tr>\
						<td>'+count+'</td>\
						<td>'+item.employee_name+'</td>\
						<td>'+item.designation+'</td>\
						<td>'+item.department+'</td>\
						<td><input type="date" value="'+date+'" class="form-control" name="" id=""disabled></td>\
						<td><input type="time" value="" class="form-control" name="signin" id="signin"></td>\
						<td><input type="time" value="" class="form-control" name="signout" id="signout" onchange=""></td>\
						<td></td>\
						<td>'+attendance_status+'</td>\
						<td class="hidden">'+item.id+'</td>\
						<td><button class="btn-remove btn btn-danger btn-sm" value="">Reset</button></td>\
	        		</tr>');

	        		count++
				})
            }else if(response.message == "leave"){
            	// alert(response.message)
            	// console.log(response.data)

         		$.each(response.data, function(key, item) {

         			if(item.leave_status == "approve"){
	                	// alert("On leave")
	                	attendance_status = "On leave"
	                }else{
	                	attendance_status = "Absent"
	                }


				$('tbody').append('\
					<tr>\
						<td>'+count+'</td>\
						<td>'+item.employee_name+'</td>\
						<td>'+item.designation+'</td>\
						<td>'+item.department+'</td>\
						<td><input type="date" value="'+date+'" class="form-control" name="" id="" disabled></td>\
						<td><input type="time" value="" class="form-control" name="signin" id="signin"></td>\
						<td><input type="time" value="" class="form-control" name="signout" id="signout" onchange=""></td>\
						<td></td>\
						<td>'+attendance_status+'</td>\
						<td class="hidden">'+item.id+'</td>\
						<td><button class="btn-remove btn btn-danger btn-sm" value="">Reset</button></td>\
	        		</tr>');

	        		count++
				})
            }
            else{

                // $.notify('Attendance!', {className: 'success', position: 'bottom right'});
                $.each(response.data, function(key, item) {

                if(item.stay_time == null){
                	stay_time = "0 Hours"
                }else{
                	stay_time = item.stay_time
                }

                if(item.attendance_status == null){
                	attendance_status = "Absent"
                }else{
                	attendance_status = item.attendance_status
                }





				$('tbody').append('\
					<tr>\
						<td>'+count+'</td>\
						<td>'+item.employee_name+'</td>\
						<td>'+item.designation+'</td>\
						<td>'+item.department+'</td>\
						<td><input type="date" value="'+item.attendance_date+'" class="form-control" name="" id="" disabled></td>\
						<td><input type="time" value="'+item.sign_in+'" class="form-control" name="signin" id="signin"></td>\
						<td><input type="time" value="'+item.sign_out+'" class="form-control" name="signout" id="signout" onchange=""></td>\
						<td>'+stay_time+'</td>\
						<td>'+attendance_status+'</td>\
						<td class="hidden">'+item.employee_id+'</td>\
						<td><button class="btn-remove btn btn-danger btn-sm" value="">Reset</button></td>\
	        		</tr>');

	        		count++
				})
            }


		}
	});
})

$("#attendance_table").on('click', '.btn-remove', function () {
   $(this).closest("tr").find("td:eq(5) input[type='time']").val('')
   $(this).closest("tr").find("td:eq(6) input[type='time']").val('')
   $(this).closest("tr").find("td:eq(7)").text('');
   $(this).closest("tr").find("td:eq(8)").text('');
})

function processData(){
	this.event.preventDefault();
	let attendances = {};
    let attendanceList = []

     if( $('#attendance_table tr').length > 1){
     	var attendanceTable = $('#attendance_table');
            $(attendanceTable).find('> tbody > tr').each(function () {
            	 let attendance = {}

            	attendance["employee_name"]        	= $(this).find("td:eq(1)").text();
                attendance["employee_id"]      		= $(this).find("td:eq(9)").text();
                attendance["designation"]         	= $(this).find("td:eq(2)").text();
                attendance["department"]        	= $(this).find("td:eq(3)").text();
                attendance["attendance_date"]       = $(this).closest("tr").find("td:eq(4) input[type='date']").val()
                attendance["sign_in"] 				= $(this).closest("tr").find("td:eq(5) input[type='time']").val()
                attendance["sign_out"]        		= $(this).closest("tr").find("td:eq(6) input[type='time']").val()
                attendance["stay_time"]  			= $(this).find("td:eq(7)").text();
                attendance["status"]        		= $(this).find("td:eq(8)").text();

                attendances["attendanceDate"] = $(this).closest("tr").find("td:eq(4) input[type='date']").val()
                attendanceList.push(attendance);
            })

            attendances["attendanceList"] = attendanceList


            attendanceSubmit(attendances)
     }else{
     	$.notify('No data to submit.', {className: 'error', position: 'bottom right'});

     }

}


function attendanceSubmit(jsonData){

    // console.log(jsonData)
    $.ajax({
        type: "POST",
        url: "/attendance-submit",
        data: JSON.stringify(jsonData),
        dataType : "json",
        contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        success: function (response) {
			if(response.status==200){
				$.notify("success", {className: 'success', position: 'bottom right'});

				 // $(location).attr('href','/leave-apply-list');
				 // resetButton()

			}else{
				// console.log(response.error)
				$.notify("somthing is wrong!!!", {className: 'error', position: 'bottom right'});

			}
	        // console.log(response.message)

	        // resetButton()
	        // if(response.message == "Already returned."){
	        //     $.notify(response.message, {className: 'warn', position: 'bottom right'});
	        // }else{
	        // 	$.notify(response.message, {className: 'success', position: 'bottom right'});
	        // 	// $(location).attr('href','/sales-return-list');

	        // }
        }
    });

}



function dataTableX(){
	//DATA TABLE
	$(document).ready( function () {
		$('#attendance_table').DataTable({
		    pageLength : 15,
		    lengthMenu: [[5, 10, 15, 20, -1], [5, 10, 15, 20, 'Todos']],
		   //  "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			  //   //debugger;
			  //   var index = iDisplayIndexFull + 1;
			  //   $("td:first", nRow).html(index);
			  //   return nRow;
		  	// },
		  	// dom: 'Bfrtip',
	    //     buttons: [
	    //         'copy', 'csv', 'excel', 'pdf', 'print'
	    //     ]
		});
	});
}

