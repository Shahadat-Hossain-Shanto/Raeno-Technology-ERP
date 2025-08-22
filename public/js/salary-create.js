
function fetchData(){
	$.ajax({
		type: "GET",
		url: "/salary-create-onload",
		dataType:"json",
		success: function(response){
			// console.log(response.data)
			$('#date').text(response.monthName)

			onChangeDataTable(response.data, response.monthName)

			var T = $('#salary_table');
			var X = 1
			$(T).find('> tbody > tr').each(function () {

				if($(this).find("td:eq(13)").text() == 'false'){
					X = 0
				}
			})

			if(X == 1){
				$("#payall_btn").prop('disabled',true);
			}else{
				$("#payall_btn").prop('disabled',false);
			}
		}
	})
}

function onChangeDataTable(json, monthName){

	$('#salary_table').DataTable().clear().destroy()

	var t = $('#salary_table').DataTable({

        data : json,
        columns: [

            { data: 'employeeId' },//0
            { data: 'employeeName' },//1
            { data: 'employeeDesignation' },//2
            { data: 'employeeDepartment' },//3
            { data: 'totalPresent' },//4
            { data: 'totalAbsent' },//5
            { data: 'totalLeave' },//6
			{ data: 'basic_pay' },//7

            { "render": function ( data, type, row, meta ){ //8

            	var perDaySalary = parseFloat(row.grossSalary/30)
				var totalPresent = parseFloat(row.totalPresent)
				var totalAbsent = parseFloat(row.totalAbsent)
				var totalDeduct = parseFloat(perDaySalary * totalAbsent)
				var grossSalary = parseFloat(row.grossSalary)
				var netSalary = parseFloat(grossSalary - totalDeduct)

            	var totalDeduct = parseFloat(totalDeduct);
            	return totalDeduct.toFixed(2);}
            },


			{ data: 'addition' },//9
			{ data: 'deduction' },//10

			{ "render": function ( data, type, row, meta ){ //11
				var deduction = parseFloat(row.deduction)
				//console.log(deduction);
            	var perDaySalary = parseFloat(row.grossSalary/30)
				var totalPresent = parseFloat(row.totalPresent)
				var totalAbsent = parseFloat(row.totalAbsent)
				var absentDeduct = parseFloat(perDaySalary* totalAbsent )
				var totalDeduct = parseFloat(absentDeduct+ deduction )
				var grossSalary = parseFloat(row.basic_pay + row.addition)
				var netSalary = parseFloat(grossSalary - totalDeduct)


            	return netSalary.toFixed(2);
			}
            },

            { data: 'grossSalaryId', className: "hidden", },//12
            { data: 'isSalaryPaid', className: "hidden", },//13
			//{ data: 'special_addition' },//14

            { "render": function ( data, type, row, meta ){
	            	if(row.isSalaryPaid == "false"){
						pay = '<button type="button" value="'+row.employeeId+'" class="pay_btn btn btn-outline-danger ">Pay</button>\
							   <button title="Pay first to see Pay Slip" type="button" class="action btn btn-outline-info" disabled>Pay Slip</button>'
					}else{
						pay = '<button type="button" value="" class="paid_btn btn btn-success " disabled>Paid</button>\
							   <a style="color: white" href="/salary-pay-slip/'+row.employeeId+'/'+monthName+'"><button type="button" class="action btn btn-info ">Pay Slip</button></a>'
					}
	            	return pay;
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
	    	var PageInfo = $('#salary_table').DataTable().page.info();
	         //t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	        //     cell.innerHTML = i + 1 + PageInfo.start;
	        // } );
	    });

    }).draw();
}

function collection(){

	let object= {};

	object['month'] = $('#month').val();
	object['department'] = $('#department').val();

	filter(object)

}

function filter(jsonData){

	// console.log(JSON.stringify(jsonData))

	$.ajax({
		type: "POST",
		contentType: "application/json",
		url: "/salary-create-filter",
		data: JSON.stringify(jsonData),
		dataType : "json",
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(response){
			// alert(response.message)

			// console.log(response.data)

			if(response.data == null){
				// $.notify('No Data Found', 'error')
				$('#gen_btn').notify("No data found.", {className: 'error', position: 'bottom left'})
			}else{
				$('#date').text(response.monthName)
				onChangeDataTable(response.data, response.monthName)

				var T = $('#salary_table');
				var X = 1
				$(T).find('> tbody > tr').each(function () {

					if($(this).find("td:eq(13)").text() == 'false'){
						X = 0
					}
				})

				if(X == 1){

					$("#payall_btn").prop('disabled',true);
				}else{
					$("#payall_btn").prop('disabled',false);
				}
			}
		}
	})
}

$(document).on('click', '.paid_btn', function (e) {
	e.preventDefault();

	$.notify("Already paid.", {className: 'success', position: 'bottom right'})
})

//PAY
$(document).on('click', '.pay_btn', function (e) {
	e.preventDefault();

	var empId = $(this).val();
	var $row = $(this).parents('tr');

	let salaryList = {};
	var salaries=[];
	let salary = {};

	salary["employeeId"]	  = $row.find("td:eq(0)").text();
	salary["employeeName"] = $row.find("td:eq(1)").text();
	salary["designation"]	  = $row.find("td:eq(2)").text();
	salary["department"]	  = $row.find("td:eq(3)").text();
	salary["present"]	  = $row.find("td:eq(4)").text();
	salary["absent"]			  = $row.find("td:eq(5)").text();
	salary["leave"]	  = $row.find("td:eq(6)").text();
	salary["basicPay"]	= $row.find("td:eq(7)").text();
	salary["absent_deduction"]	= $row.find("td:eq(8)").text();
	//salary["addition"]	= $row.find("td:eq(8)").text();
	salary["addition"]	= $row.find("td:eq(9)").text();
	salary["deduction"]	= $row.find("td:eq(10)").text();
	salary["netPay"]	= $row.find("td:eq(11)").text();
	salary["salaryGrade"]	= $row.find("td:eq(12)").text();
	salary["salaryMonth"]	= $('#date').text()

	salaries.push(salary);

	salaryList["salaryList"]	= salaries


	// console.log(salaryList)
	submitToServer(salaryList)

});


//PAY ALL
$(document).on('click', '#payall_btn', function (e) {
	e.preventDefault();


	let salaryList = {};
	var salaries=[];


	var T = $('#salary_table');
	$(T).find('> tbody > tr').each(function () {

		if($(this).find("td:eq(13)").text() == 'false'){
			let salary = {};
			salary["employeeId"]	  = $(this).find("td:eq(0)").text();
			salary["employeeName"] = $(this).find("td:eq(1)").text();
			salary["designation"]	  =$(this).find("td:eq(2)").text();
			salary["department"]	  = $(this).find("td:eq(3)").text();
			salary["present"]	  = $(this).find("td:eq(4)").text();
			salary["absent"]			  = $(this).find("td:eq(5)").text();
			salary["leave"]	  = $(this).find("td:eq(6)").text();
			salary["basicPay"]	= $(this).find("td:eq(7)").text();
			salary["absent_deduction"]	= $(this).find("td:eq(8)").text();
			//salary["addition"]	= $row.find("td:eq(8)").text();
			salary["addition"]	= $(this).find("td:eq(9)").text();
			salary["deduction"]	= $(this).find("td:eq(10)").text();
			salary["netPay"]	= $(this).find("td:eq(11)").text();
			salary["salaryGrade"]	= $(this).find("td:eq(12)").text();
			salary["salaryMonth"]	= $('#date').text()

			salaries.push(salary);
		}


	})

	if(salaries.length === 0){
		$.notify("Already paid.", {className: 'success', position: 'bottom right'})
	}else{
		salaryList["salaryList"]	= salaries

		 console.log(salaryList)
		submitToServer(salaryList)
	}


})

function submitToServer(jsonData) {

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
        contentType: "application/json",
        url: "/salary-store",
        data: JSON.stringify(jsonData),
        dataType : "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
        	// alert(response.message);
        	$.notify(response.message, {className: 'success', position: 'bottom right'});
            $(location).attr('href','/salary-create');

        }
    });
}


function resetButton(){
	// dataTableX()
	// fetchExpense()
    // $("#expense_table").find("tr:gt(0)").remove();
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}

// dataTableX()
// function dataTableX(){
// 	$(document).ready( function () {
// 		$('#salary_table').DataTable({
// 		    pageLength : 10,
// 		    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
// 		    dom: 'Bfrtip',
// 	        buttons: [
// 	            'copy', 'csv', 'excel', 'pdf', 'print'
// 	        ],
// 	        // responsive: true,
// 		})
// 	});

// }
