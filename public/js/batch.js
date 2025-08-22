$(document).ready(function () {
	//CREATE BATCH
	$(document).on('submit', '#AddBatchForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddBatchForm')[0]);

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
			url: "/batch-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);	
				if($.isEmptyObject(response.error)){
                    
             		$(location).attr('href','/batch-list');

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
            $('#wrongbatchnumber').empty();
            $('#wrongexpirydate').empty();

			if(message.batchnumber == null){
				batchnumber = ""
			}else{
				batchnumber = message.batchnumber[0]
			}
			if(message.expirydate == null){
				expirydate = ""
			}else{
				expirydate = message.expirydate[0]
			}

            $('#wrongbatchnumber').append('<span id="">'+batchnumber+'</span>');
            $('#wrongexpirydate').append('<span id="">'+expirydate+'</span>');
        // });
    }
});

//BATCH LIST

fetchBatch();
function fetchBatch(){

	// var subscriberId = $('#subscriberid').val();

	$.ajax({
		type: "GET",
		url: "/batch-list-data",
		dataType:"json",
		success: function(response){
			// console.log(response);
			// $('tbody').html("");
			$.each(response.batch, function(key, item) {

				$('tbody').append('\
				<tr>\
					<td></td>\
					<td class="hidden">'+item.id+'</td>\
					<td>'+item.batch_number+'</td>\
					<td>'+item.expiry_date+'</td>\
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

//EDIT BATCH
$(document).on('click', '.edit_btn', function (e) {
	e.preventDefault();

	var batchId = $(this).val();
	// alert(batchId);
	$('#EDITBatchMODAL').modal('show');
		
		$.ajax({
		type: "GET",
		url: "/batch-edit/"+batchId,
		success: function(response){
			if (response.status == 200) {
				$('#edit_batchnumber').val(response.batch.batch_number);
				$('#edit_expirydate').val(response.batch.expiry_date);
				$('#batchid').val(batchId);
			}
		}
	});
});

//UPDATE BATCH
$(document).on('submit', '#UPDATEBatchFORM', function (e)
{
	e.preventDefault();

	var id = $('#batchid').val(); 

	let EditFormData = new FormData($('#UPDATEBatchFORM')[0]);

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
		url: "/batch-edit/"+id,
		data: EditFormData,
		contentType: false,
		processData: false,
		success: function(response){
			
			if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#EDITBatchMODAL').modal('hide');
                // $.notify(response.message, 'success')
                $(location).attr('href','/batch-list');
            }else{
            	// console.log(response.error)
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $('#edit_wrongbatchnumber').empty();
				$('#edit_wrongexpirydate').empty();

				if(response.error.batchnumber == null){
					batchnumber = ""
				}else{
					batchnumber = response.error.batchnumber[0]
				}
				if(response.error.expirydate == null){
					expirydate = ""
				}else{
					expirydate = response.error.expirydate[0]
				}
				

                $('#edit_wrongbatchnumber').append('<span id="">'+batchnumber+'</span>');
                $('#edit_wrongexpirydate').append('<span id="">'+expirydate+'</span>');
                
            }
		}
	});
});

//Delete BATCH
$(document).ready( function () {
	$('#batch_table').on('click', '.delete_btn', function(){

		var batchId = $(this).data("value");

		$('#batchid').val(batchId);
		$('#DELETEBatchFORM').attr('action', '/batch-delete/'+batchId);
		$('#DELETEBatchMODAL').modal('show');

	});
});


//DATA TABLE
$(document).ready( function () {
	$('#batch_table').DataTable({
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