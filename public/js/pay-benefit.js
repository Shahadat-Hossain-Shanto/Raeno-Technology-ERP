
function fetchData(){
	$.ajax({
		type: "GET",
		url: "/pay-benefit-create-onload",
		dataType:"json",
		success: function(response){
			// console.log(response.data)
			// alert(response.message)

			if(response.data == null){
				$.notify('No data found.', {className: "error", position: 'top right'})
			}else{
				onChangeDataTable(response.data)
			}



		}
	})
}


function historyData(){
    //alert();
   var id= $("#empId").val()

	$.ajax({
		type: "GET",
		url: "/histories/"+id,
		dataType:"json",
		success: function(response){
			console.log(response)

			// alert(response.message)
			if(response.data == null){
				$.notify('No data found.', {className: "error", position: 'top right'})
			}else{
				$('#history_table').DataTable().clear().destroy()

	var t = $('#history_table').DataTable({

        data : response.data,
        columns: [

            { data: 'employee_id' },//0
            { data: 'employee_name' },//1
            { data: 'designation' },//2
            { data: 'department' },//3
           { data: 'benefit_name' },//4
           { data: 'amount' },//5
           { data: 'month' },//6
           { data: 'id', className: "hidden" },//7

            { "render": function ( data, type, row, meta ){


						pay = '  <a style="color: white" href="/pay-benefit-slip/'+row.employee_id+'/'+row.id+'"><button type="button" class="action btn btn-info ">Pay Slip</button></a>'

	            	return pay;}
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
		}
		}
	})
}




function onChangeDataTable(json){

	$('#salary_table').DataTable().clear().destroy()

	var t = $('#salary_table').DataTable({

        data : json,
        columns: [
            { data: null },//0
            { data: 'employee_id' },//1
            { data: 'employee_name' },//2
            { data: 'designation' },//3
            { data: 'department' },//4
            { data: 'department_id', className: "hidden" },//5
            { data: 'benefit_name' },//6
            { data: 'benefit_id', className: "hidden" },//7
            { data: 'total_amount' },//8
            { "render": function ( data, type, row, meta ){ //9

					if(row.yearly_allocation == null){
						var yearly_allocation = 0
					}else{
						var yearly_allocation = row.yearly_allocation
					}

	            	return yearly_allocation;
	            }
	        },
            { data: 'benefitPaid' },//10

            { "render": function ( data, type, row, meta ){

					if(row.yearly_allocation>0 && row.benefitPaid == 0){
						pay = '<button type="button" value="'+row.employee_id+'" class="pay_btn btn btn-outline-danger">Pay</button>\
                        <a href="/histories/'+row.employee_id+'" class="history_btn btn btn-outline-primary">history</a>'
					}else if(row.benefitPaid != 0 && row.benefitPaid < row.yearly_allocation){
						pay = '<button type="button" value="'+row.employee_id+'" class="pay_btn btn btn-outline-danger " >Pay</button>\
                        <a href="/history/'+row.employee_id+'" class="history_btn btn btn-outline-primary">history</a>'
					}
                    else if( row.yearly_allocation==null){
						pay = '<button type="button" value="'+row.employee_id+'" class="pay_btn btn btn-outline-danger "disabled>Pay</button>\
                        <a href="/histories/'+row.employee_id+'" class="history_btn btn btn-outline-primary">history</a>'
					}
                    else if(row.benefitPaid == row.yearly_allocation){
						pay = '<button type="button" value="" class="paid_btn btn btn-success" disabled>Paid</button>\
                        <a href="/histories/'+row.employee_id+'" class="history_btn btn btn-outline-primary">history</a'
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
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}

//PAY
$(document).on('click', '.pay_btn', function (e) {
	e.preventDefault();

	var empId = $(this).val();
	var $row = $(this).parents('tr');

	let benefitList = {};
	var benefits=[];
	let benefit = {};

	benefit["employee_id"]	  = $row.find("td:eq(1)").text();
	benefit["employee_name"] = $row.find("td:eq(2)").text();
	benefit["designation"]	  = $row.find("td:eq(3)").text();
	benefit["department"]	  = $row.find("td:eq(4)").text();
	benefit["department_id"]	  = $row.find("td:eq(5)").text();
	benefit["benefit_name"]			  = $row.find("td:eq(6)").text();
	benefit["benefit_id"]	  = $row.find("td:eq(7)").text();
	benefit["total_amount"]	= $row.find("td:eq(8)").text();
	benefit["yearly_allocation"]	= $row.find("td:eq(9)").text();
	benefit["benefitPaid"]	= $row.find("td:eq(10)").text();


	benefits.push(benefit);

	benefitList["benefitList"]	= benefits


	// console.log(benefitList)
	submitToServer(benefitList)

});


function submitToServer(jsonData) {

	console.log(jsonData)

	$.ajax({
        type: "POST",
        contentType: "application/json",
        url: "/pay-benefit-store",
        data: JSON.stringify(jsonData),
        dataType : "json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
        	// alert(response.message);
        	$.notify(response.message, {className: 'success', position: 'bottom right'});
            // $(location).attr('href','/pay-benefit-create');

        }
    });
}

function collection(){

	if($('#specialbenefit').val().length != '' || $('#department').val() != ''){
		let object= {};

		object['benefit'] = $('#specialbenefit').val();
		object['department'] = $('#department').val();

		filter(object)
	}else{
		$('#gen_btn').notify('Please select at least one field.', {className: 'error', position: 'bottom right'});
	}



}

function filter(jsonData){

	// console.log(JSON.stringify(jsonData))

	$.ajax({
		type: "POST",
		contentType: "application/json",
		url: "/pay-benefit-create-filter",
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

				onChangeDataTable(response.data)
				// $('#salary_table').DataTable().clear().destroy()
				// dataTableX()
				// $.each(response.data, function(key, item) {


				// 	if(item.benefitPaid == 0){
				// 		pay = '<button type="button" value="'+item.employee_id+'" class="pay_btn btn btn-outline-danger">Pay</button>\
				// 			   <button title="Pay first to see Pay Slip" type="button" class="action btn btn-outline-info" disabled>Pay Slip</button>'
				// 	}else if(item.benefitPaid != 0 && item.benefitPaid < item.yearly_allocation){
				// 		pay = '<button type="button" value="'+item.employee_id+'" class="pay_btn btn btn-outline-danger " >Pay</button>\
				// 			   <a style="color: white" href=""><button type="button" class="action btn btn-info ">Pay Slip</button></a>'
				// 	}else if(item.benefitPaid == item.yearly_allocation){
				// 		pay = '<button type="button" value="" class="paid_btn btn btn-success" disabled>Paid</button>\
				// 			   <a style="color: white" href=""><button type="button" class="action btn btn-info ">Pay Slip</button></a>'
				// 	}


				// 	$('tbody').append('\
				// 	<tr>\
				// 		<td>'+item.employee_id+'</td>\
				// 		<td>'+item.employee_name+'</td>\
				// 		<td>'+item.designation+'</td>\
				// 		<td>'+item.department+'</td>\
				// 		<td class="hidden">'+item.department_id+'</td>\
				// 		<td>'+item.benefit_name+'</td>\
				// 		<td class="hidden">'+item.benefit_id+'</td>\
				// 		<td>'+item.total_amount+'</td>\
				// 		<td>'+item.yearly_allocation+'</td>\
				// 		<td>'+item.benefitPaid+'</td>\
				// 		<td>'+pay+'</td>\
		  //   		</tr>');
				// })
			}


		}
	})
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

