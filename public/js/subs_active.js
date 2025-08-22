function showData(){
	$.ajax({
		type: "GET",
		url: "/subscriber-actives",
		dataType:"json",
		success: function(response){
			//console.log(response)

			// alert(response.message)
			if(response.data == null){
				$.notify('No data found.', {className: "error", position: 'top right'})
			}else{
				$('#subscriber_table').DataTable().clear().destroy()

	var t = $('#subscriber_table').DataTable({

        data : response.data,
        columns: [

            { data: 'store_name' },//2
            { data: 'name' },//1
            { data: 'mobile' },//3
            //{ data: 'email' },//4
            { "render": function ( data, type, row, meta ){

                if(row.email==null){
                    return 'N/A';
                }
                else{
                    return row.email;
                }
            }},
            { data: 'address' },//8
            { data: 'registration_type' },//8
            //{ data: 'package' },//8
            { "render": function ( data, type, row, meta ){

                if(row.package==null){
                    return 'N/A';
                }
                else{
                    return row.package;
                }
            }},
            //{ data: 'branch' },//8
            { "render": function ( data, type, row, meta ){

                if(row.branch==null){
                    return 'N/A';
                }
                else{
                    return row.branch;
                }
            }},
            { data: 'time' },
            { data: 'status' },

            { "render": function ( data, type, row, meta ){
                if(row.status=="Active"){
                    return '<button type="button" value="'+row.mobile+'" class="activate btn btn-outline-danger ">Inactivate</button>'
                }
                else{
                    return '<div class="row">\
                                <div class="col-7">\
                                <button type="button" value="'+row.mobile+'" class="activate btn btn-outline-success ">Activate</button>\
                                </div>\
                                <div class="col-5">\
                                <button type="button" value="'+row.mobile+'" class="delete btn btn-outline-danger"><i class="fas fa-trash"></i></button>\
                                </div>\
                            </div>'
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
	    order: [[8, 'desc']],
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

$(document).on('click', '.activate', function (e) {
	e.preventDefault();

	var id = $(this).val();
   // console.log(id);
    $.ajax({
        type: "GET",
        url: "/subscriber-activ/"+id,
        success: function(response){
            showData();
        }
    });
});


$(document).on('click', '.delete', function (e) {
    e.preventDefault();
    var id = $(this).val();
    // console.log(id);
    $.ajax({
        type: "GET",
        url: "/subscriber-data/"+id,
        success: function(response){
            // $("#info_table tbody").closest('tr:last-child').remove();
            $("#info_table tbody tr").remove();
            $("#info_table tbody").append(
                "<tr>" +
                    "<td>" + response.org_name + "</td>" +
                    "<td>" + response.owner_name + "</td>" +
                    "<td>" + response.contact_number + "</td>" +
                "</tr>");
            $('#deleteValue').val(response.contact_number);
            $('#exampleModalCenter').modal('show')
        }
    });
});

$(document).on('click', '.exit', function (e) {
    e.preventDefault();
    $('#exampleModalCenter').modal('hide')
});

$(document).on('click', '.deleteConfirmed', function (e) {
    e.preventDefault();
    var id = $('#deleteValue').val();
    // alert(id);
    $.ajax({
        type: "GET",
        url: "/subscriber-delete/"+id,
        success: function(response){
            location.reload();
        }
    });
});
