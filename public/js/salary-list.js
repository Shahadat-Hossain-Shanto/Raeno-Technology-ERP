

$(document).ready(function () {
    var t = $('#salary_table').DataTable({
        ajax: {
            "url": "/salary-list-data",
            "dataSrc": "SalaryGrade",
        },
        columns: [
            { data: null },
            { data: 'grade_name' },
            { data: 'salary_type' },
            { data: 'basic_pay' },
            { data: 'addition' },
            { data: 'deduction' },
            { data: 'gross_salary' },
            { "render": function ( data, type, row, meta ){ 
                    
                    return '<a type="button" class="edit_btn btn btn-secondary btn-sm" href="/salary-edit/'+row.id+'"><i class="fas fa-edit"></i></a>\
                			<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+row.id+'"><i class="fas fa-trash"></i></a>'
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
            var PageInfo = $('#salary_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();

});

//Delete salary
$(document).ready( function () {
	$('#salary_table').on('click', '.delete_btn', function(){

		var salaryId = $(this).data("value");

		$('#salaryid').val(salaryId);
		$('#DELETESalaryFORM').attr('action', '/salary-delete/'+salaryId);
		$('#DELETESalaryMODAL').modal('show');

	});
});



