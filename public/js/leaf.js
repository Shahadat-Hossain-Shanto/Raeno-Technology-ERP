$(document).ready(function () {
	//CREATE LEAF
	$(document).on('submit', '#AddLeafForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddLeafForm')[0]);

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
			url: "/leaf-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);	
				if($.isEmptyObject(response.error)){
                    
             		$(location).attr('href','/leaf-list');

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
            $('#wrongleaftype').empty();
            $('#wrongtotalnumberofperbox').empty();
			
			

			if(message.leaftype == null){
				leaftype = ""
			}else{
				leaftype = message.leaftype[0]
			}
			if(message.totalnumberofperbox == null){
				totalnumberofperbox = ""
			}else{
				totalnumberofperbox = message.totalnumberofperbox[0]
			}
			

            $('#wrongleaftype').append('<span id="">'+leaftype+'</span>');
            $('#wrongtotalnumberofperbox').append('<span id="">'+totalnumberofperbox+'</span>');
            
        // });
    }
});

//LEAF LIST

fetchLeaf();
function fetchLeaf(){

	$.ajax({
		type: "GET",
		url: "/leaf-list-data",
		dataType:"json",
		success: function(response){
			// console.log(response);
			// $('tbody').html("");
			$.each(response.leaf, function(key, item) {

				$('tbody').append('\
				<tr>\
					<td></td>\
					<td class="hidden">'+item.id+'</td>\
					<td>'+item.leaf_type+'</td>\
					<td>'+item.total_number_of_per_box+'</td>\
					<td>\
        				<button type="button" value="'+item.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i>\
                    	</button>\
                    	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+item.id+'"><i class="fas fa-trash"></i></a>\
        			</td>\
        		</tr>');
			})	
		}
	});
}

//EDIT LEAF
$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var leafId = $(this).val();
	// alert(leafId);
	$('#EDITLeafMODAL').modal('show');
		
		$.ajax({
		type: "GET",
		url: "/leaf-edit/"+leafId,
		success: function(response){
			if (response.status == 200) {
				$('#edit_leaftype').val(response.leaf.leaf_type);
				$('#edit_totalnumberofperbox').val(response.leaf.total_number_of_per_box);
				$('#leafid').val(leafId);
			}
		}
	});
});

//UPDATE LEAF
$(document).on('submit', '#UPDATELeafFORM', function (e)
{
	e.preventDefault();

	var id = $('#leafid').val(); 

	let EditFormData = new FormData($('#UPDATELeafFORM')[0]);

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
		url: "/leaf-edit/"+id,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function(response){
			
			if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#EDITLeafMODAL').modal('hide');
                // $.notify(response.message, 'success')
                $(location).attr('href','/leaf-list');

            }else{
            	// console.log(response.error)
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $('#edit_wrongleaftype').empty();
				$('#edit_wrongtotalnumberofperbox').empty();
				

				if(response.error.leaftype == null){
					leaftype = ""
				}else{
					leaftype = response.error.leaftype[0]
				}
				if(response.error.totalnumberofperbox == null){
					totalnumberofperbox = ""
				}else{
					totalnumberofperbox = response.error.totalnumberofperbox[0]
				}

                $('#edit_wrongleaftype').append('<span id="">'+leaftype+'</span>');
                $('#edit_wrongtotalnumberofperbox').append('<span id="">'+totalnumberofperbox+'</span>');
                
            }
		}
	});
});

//Delete LEAF
$(document).ready( function () {
	$('#leaf_table').on('click', '.delete_btn', function(){

		var leafId = $(this).data("value");

		$('#leafid').val(leafId);
		$('#DELETELeafFORM').attr('action', '/leaf-delete/'+leafId);
		$('#DELETELeafMODAL').modal('show');

	});
});


//DATA TABLE
$(document).ready( function () {
	$('#leaf_table').DataTable({
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
		    //debugger;
		    var index = iDisplayIndexFull + 1;
		    $("td:first", nRow).html(index);
		    return nRow;
	  	},
	});
});