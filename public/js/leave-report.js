// fetchLeave()
// function fetchLeave(){
// 	$.ajax({
// 		type: "GET",
// 		url: "/leave-report-onload",
// 		dataType:"json",
// 		success: function(response){
// 			// console.log(response.message)
// 			$('#date').text(response.monthName)
// 			$.each(response.data, function(key, item) {
// 				$('tbody').append('\
// 				<tr>\
// 					<td></td>\
// 					<td>'+item.employeeName+'</td>\
// 					<td>'+item.employeeDepartment+'</td>\
// 					<td>'+item.employeeDesignation+'</td>\
// 					<td>'+item.totalPresent+'</td>\
// 					<td>'+item.totalAbsent+'</td>\
// 					<td>'+item.totalLateIn+'</td>\
// 					<td>'+item.totalEarlyOut+'</td>\
// 					<td>\
// 	                	<a style="color: white" href="leave-report-details/'+item.employeeId+'"><button type="button" class="action btn btn-outline-info btn-sm">Details</button></a>\
// 	    			</td>\
// 	    		</tr>');
// 			})
			

// 		}
// 	})
// }

$(document).ready(function () {
    var t = $('#leave_report_table').DataTable({
        ajax: {
            "url": "/leave-report-onload",
            "dataSrc": "data"
        },
        columns: [
            { data: null },
            { data: "employeeName" },
            { data: "employeeDepartment" },
            { data: "employeeDesignation" },
            { data: "totalPresent" },
            { data: "totalAbsent" },
            { data: "totalLateIn" },
            { data: "totalEarlyOut" },
            { "render": function ( data, type, row, meta ){ 
            	
            	return '<a style="color: white" href="leave-report-details/'+row.employeeId+'"><button type="button" class="action btn btn-outline-info btn-sm">Details</button></a>';
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
	    order: [[1, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#leave_report_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});



$('#LeaveReportForm').on('submit',  function (e) {
	e.preventDefault();
	

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	let formData = new FormData($('#LeaveReportForm')[0]);

	startDate = $('#startdate').val()
	endDate = $('#enddate').val()
	

	$.ajax({
		type: "POST",
		url: "/leave-report",
		data: formData,
		contentType: false,
		processData: false,
		success: function(response){
			// alert(response.message)
			// console.log(response.data)



			if(startDate.length != 0 && endDate.length == 0){
				$('#date').text('')
				$('#date').text(startDate)
			}else if(startDate.length == 0 && endDate.length != 0){
				$('#date').text('')
				$('#date').text(endDate)
			}else if(startDate.length != 0 && endDate.length != 0){
				$('#date').text('')
				$('#date').text(startDate+' to '+endDate)
			}

			onChangeDataTable(response.data, startDate, endDate)

			// $('#leave_report_table').DataTable().clear().destroy()
			// dataTableX()

			// if(response.data == null){
			// 	// $.notify('No Data Found', 'error')
			// 	$('#gen_btn').notify("No Data Found.", {className: 'error', position: 'bottom left'})
			// }else{
			// 	$('tbody').html("");
			// 	$.each(response.data, function(key, item) {

			// 		if(startDate.length == 0 && endDate.length == 0){
			// 			$('tbody').append('<tr>\
			// 				<td></td>\
			// 				<td>'+item.employeeName+'</td>\
			// 				<td>'+item.employeeDepartment+'</td>\
			// 				<td>'+item.employeeDesignation+'</td>\
			// 				<td>'+item.totalPresent+'</td>\
			// 				<td>'+item.totalAbsent+'</td>\
			// 				<td>'+item.totalLateIn+'</td>\
			// 				<td>'+item.totalEarlyOut+'</td>\
			// 				<td>\
			//                 	<a style="color: white" href="leave-report-details/'+item.employeeId+'"><button type="button" class="action btn btn-outline-info btn-sm">Details</button></a>\
			//     			</td>\
		 //        		</tr>');
			// 		}else if(startDate.length != 0 && endDate.length == 0){
			// 			$('tbody').append('<tr>\
			// 				<td></td>\
			// 				<td>'+item.employeeName+'</td>\
			// 				<td>'+item.employeeDepartment+'</td>\
			// 				<td>'+item.employeeDesignation+'</td>\
			// 				<td>'+item.totalPresent+'</td>\
			// 				<td>'+item.totalAbsent+'</td>\
			// 				<td>'+item.totalLateIn+'</td>\
			// 				<td>'+item.totalEarlyOut+'</td>\
			// 				<td>\
			//                 	<a style="color: white" href="leave-report-details/'+item.employeeId+'/'+startDate+'"><button type="button" class="action btn btn-outline-info btn-sm">Details</button></a>\
			//     			</td>\
		 //        		</tr>');
			// 		}else if(startDate.length == 0 && endDate.length != 0){
			// 			$('tbody').append('<tr>\
			// 				<td></td>\
			// 				<td>'+item.employeeName+'</td>\
			// 				<td>'+item.employeeDepartment+'</td>\
			// 				<td>'+item.employeeDesignation+'</td>\
			// 				<td>'+item.totalPresent+'</td>\
			// 				<td>'+item.totalAbsent+'</td>\
			// 				<td>'+item.totalLateIn+'</td>\
			// 				<td>'+item.totalEarlyOut+'</td>\
			// 				<td>\
			//                 	<a style="color: white" href="leave-report-details/'+item.employeeId+'/'+endDate+'"><button type="button" class="action btn btn-outline-info btn-sm">Details</button></a>\
			//     			</td>\
		 //        		</tr>');
			// 		}else if(startDate.length != 0 && endDate.length != 0){
			// 			$('tbody').append('<tr>\
			// 				<td></td>\
			// 				<td>'+item.employeeName+'</td>\
			// 				<td>'+item.employeeDepartment+'</td>\
			// 				<td>'+item.employeeDesignation+'</td>\
			// 				<td>'+item.totalPresent+'</td>\
			// 				<td>'+item.totalAbsent+'</td>\
			// 				<td>'+item.totalLateIn+'</td>\
			// 				<td>'+item.totalEarlyOut+'</td>\
			// 				<td>\
			//                 	<a style="color: white" href="leave-report-details/'+item.employeeId+'/'+startDate+'/'+endDate+'"><button type="button" class="action btn btn-outline-info btn-sm">Details</button></a>\
			//     			</td>\
		 //        		</tr>');
			// 		}

					
			// 	})
			// }
		}
	})
})

function onChangeDataTable(json, startDate, endDate){

	$('#leave_report_table').DataTable().clear().destroy()

	var t = $('#leave_report_table').DataTable({
     
        data : json,
        columns: [
            { data: null },
         	{ data: "employeeName" },
            { data: "employeeDepartment" },
            { data: "employeeDesignation" },
            { data: "totalPresent" },
            { data: "totalAbsent" },
            { data: "totalLateIn" },
            { data: "totalEarlyOut" },
            { "render": function ( data, type, row, meta ){ 

	            	if(startDate.length == 0 && endDate.length == 0){
	            		return '<a style="color: white" href="leave-report-details/'+row.employeeId+'"><button type="button" class="action btn btn-outline-info btn-sm">Details</button></a>';
	            	}else if(startDate.length != 0 && endDate.length == 0){
	            		return '<a style="color: white" href="leave-report-details/'+row.employeeId+'/'+startDate+'"><button type="button" class="action btn btn-outline-info btn-sm">Details</button></a>'
	            	}else if(startDate.length == 0 && endDate.length != 0){
	            		return '<a style="color: white" href="leave-report-details/'+row.employeeId+'/'+endDate+'"><button type="button" class="action btn btn-outline-info btn-sm">Details</button></a>'
	            	}else if(startDate.length != 0 && endDate.length != 0){
	            		return '<a style="color: white" href="leave-report-details/'+row.employeeId+'/'+startDate+'/'+endDate+'"><button type="button" class="action btn btn-outline-info btn-sm">Details</button></a>'
	            	}
            	
            	
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
	    order: [[1, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#leave_report_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}

