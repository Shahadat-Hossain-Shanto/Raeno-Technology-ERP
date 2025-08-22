$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE SUB-CATEGORY
	$(document).on('submit', '#AddSubcategoryForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddSubcategoryForm')[0]);

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
			url: "/subcategory-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				// console.log(response.message);
				if($.isEmptyObject(response.error)){

             		$(location).attr('href','/subcategory-list');

                }else{
                	// console.log(response.error)
                	$('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
			}
		});

	});

	function printErrorMsg (message) {
        // $(".print-error-msg").find("ul").html('');
        // $(".print-error-msg").css('display','block');

        // $.each( message, function( key, item ) {
            // $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            $('#wrongcategoryid').empty();
            $('#wrongsubcategoryname').empty();

			if(message.categoryid == null){
				categoryid = ""
			}else{
				categoryid = message.categoryid[0]
			}
			if(message.subcategoryname == null){
				subcategoryname = ""
			}else{
				subcategoryname = message.subcategoryname[0]
			}

            $('#wrongcategoryid').append('<span id="">'+categoryid+'</span>');
            $('#wrongsubcategoryname').append('<span id="">'+subcategoryname+'</span>');

        // });
    }

});
	//SUB-CATEGORY LIST
	// fetchSubcategory();
	// function fetchSubcategory(){
	// 	$.ajax({
	// 		type: "GET",
	// 		url: "/subcategory-list-data",
	// 		dataType:"json",
	// 		success: function(response){
	// 			$('tbody').html("");
	// 			$.each(response.subcategory, function(key, item) {
	// 				$('tbody').append('<tr>\
	// 				<td></td>\
	// 				<td>'+item.subcategory_name+'</td>\
	// 				<td>'+item.category_name+'</td>\
	// 				<td>\
 //        				<button type="button" value="'+item.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i>\
 //                    	</button>\
 //                    	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+item.id+'"><i class="fas fa-trash"></i></a>\
 //        			</td>\
 //        		</tr>');
	// 			})
	// 		}
	// 	});
	// }

	//EDIT SUB-CATEGORY
	$(document).on('click', '.edit_btn', function (e) {
		e.preventDefault();

		var subcategoryId = $(this).val();
		// alert(categoryId);
		$('#EDITSubcategoryMODAL').modal('show');

			$.ajax({
			type: "GET",
			url: "/subcategory-edit/"+subcategoryId,
			success: function(response){
                // console.log(response)
                if (response.status == 200) {
                    $('#categoryname').val(response.category.category_name);
					$('#edit_subcategoryname').val(response.subcategory.subcategory_name);
					$('#subcategoryid').val(subcategoryId);
				}
			}
		});
	});

	//UPDATE SUB-CATEGORY
	$(document).on('submit', '#UPDATESubcategoryFORM', function (e)
	{
		e.preventDefault();

		var id = $('#subcategoryid').val();
		// alert(id)

		let EditFormData = new FormData($('#UPDATESubcategoryFORM')[0]);

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
			url: "/subcategory-edit/"+id,
			data: EditFormData,
			contentType: false,
			processData: false,
			success: function(response){
				if($.isEmptyObject(response.error)){
                    // alert(response.message);
                    $('#EDITSubcategoryMODAL').modal('hide');
                    // $.notify(response.message, 'success')
                    $(location).attr('href','/subcategory-list');
                }else{
                	// console.log(response.error)
                    // printErrorMsg(response.error);
                    $('body').loadingModal('destroy');
                    $('#edit_wrongsubcategoryname').empty();

					if(response.error.subcategoryname == null){
						subcategoryname = ""
					}else{
						subcategoryname = response.error.subcategoryname[0]
					}

	                $('#edit_wrongsubcategoryname').append('<span id="">'+subcategoryname+'</span>');
                }
			}
		});
	});

	//DELETE SUB-CATEGORY
	$(document).ready( function () {
		$('#subcategory_table').on('click', '.delete_btn', function(){

			var subcategoryId = $(this).data("value");

			$('#subcategoryid').val(subcategoryId);

			$('#DELETESubcategoryFORM').attr('action', '/subcategory-delete/'+subcategoryId);

			$('#DELETESubcategoryMODAL').modal('show');

		});
	});


//DATA TABLE
// $(document).ready( function () {
// 	$('#subcategory_table').DataTable({
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



$(document).ready(function () {
    var t = $('#subcategory_table').DataTable({
        ajax: {
            "url": "/subcategory-list-data",
            "dataSrc": "subcategory"
        },
        columns: [
            { data: null },

            { data: 'subcategory_name' },


            { data: 'category_name' },


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

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#subcategory_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});

function getBtns(data, type, row, meta) {

    var id = row.id;
    return '<button type="button" value="'+id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button>\
            <a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+id+'"><i class="fas fa-trash"></i></a>';
}
