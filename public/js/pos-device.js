$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE POS
	$(document).on('submit', '#AddPosForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddPosForm')[0]);

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
			url: "/pos-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				// console.log(response.message);	
				if($.isEmptyObject(response.error)){
                    
             		$(location).attr('href','/pos-list');

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
            $('#wrongstoreid').empty();
            $('#wrongposname').empty();
			$('#wrongpospin').empty();
			

			if(message.storeid == null){
				store_id = ""
			}else{
				store_id = message.storeid[0]
			}
			if(message.pos_name == null){
				pos_name = ""
			}else{
				pos_name = message.pos_name[0]
			}
			
			if(message.pospin == null){
				pos_pin = ""
			}else{
				pos_pin = message.pospin[0]
			}

            $('#wrongstoreid').append('<span id="">'+store_id+'</span>');
            $('#wrongposname').append('<span id="">'+pos_name+'</span>');
            $('#wrongpospin').append('<span id="">'+pos_pin+'</span>');
        // });
    }

});

//POS LIST
$(document).ready(function () {
    var t = $('#pos_table').DataTable({
        ajax: {
            "url": "/pos-list-data",
            "dataSrc": "pos",
        },
        columns: [
          	{ data: null },
            { data: 'pos_name' },
            { data: 'pos_status' },
            { data: 'store_name' },
            { "render": function ( data, type, row, meta ){ 
            		
            		return '<button type="button" value="'+row.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button>\
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
	    	var PageInfo = $('#pos_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();

});

	//EDIT POS
	$(document).on('click', '.edit_btn', function (e) {
		e.preventDefault();

		var posId = $(this).val();
		// alert(posId);
		$('#EDITPosMODAL').modal('show');
			
			$.ajax({
			type: "GET",
			url: "/pos-edit/"+posId,
			success: function(response){
				if (response.status == 200) {
					$('#edit_posname').val(response.pos.pos_name);
					$('#edit_posstatus').val(response.pos.pos_status);
					$('#edit_storeid').val(response.pos.store_id);
					$('#edit_pospin').val(response.pos.pos_pin);
					$('#posid').val(posId);
				}
			}
		});
	});

	//UPDATE POS
	$(document).on('submit', '#UPDATEPosFORM', function (e){
		e.preventDefault();

		var id = $('#posid').val(); 

		let EditFormData = new FormData($('#UPDATEPosFORM')[0]);

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
			url: "/pos-edit/"+id,
			data: EditFormData,
			contentType: false,
			processData: false,
			success: function(response){
				if($.isEmptyObject(response.error)){
                    // alert(response.message);
                    $('#EDITPosMODAL').modal('hide');
                    // $.notify(response.message, 'success')
                    $(location).attr('href','/pos-list');
                }else{
                	// console.log(response.error)
                    // printErrorMsg(response.error);
                    $('body').loadingModal('destroy');
                    $('#edit_wrongstoreid').empty();
					$('#edit_wrongposname').empty();
					$('#edit_wrongposstatus').empty();
					$('#edit_wrongpospin').empty();

					if(response.error.storeid == null){
						store_id = ""
					}else{
						store_id = response.error.storeid[0]
					}
					if(response.error.pos_name == null){
						pos_name = ""
					}else{
						pos_name = response.error.pos_name[0]
					}
					if(response.error.posstatus == null){
						posstatus = ""
					}else{
						posstatus = response.error.posstatus[0]
					}
					if(response.error.pospin == null){
						pospin = ""
					}else{
						pospin = response.error.pospin[0]
					}

	                $('#edit_wrongstoreid').append('<span id="">'+store_id+'</span>');
	                $('#edit_wrongposname').append('<span id="">'+pos_name+'</span>');
	                $('#edit_wrongposstatus').append('<span id="">'+posstatus+'</span>');
	                $('#edit_wrongpospin').append('<span id="">'+pospin+'</span>');
                }
			}
		});
	});

	//Delete Client
	$(document).ready( function () {
		$('#pos_table').on('click', '.delete_btn', function(){

			var posId = $(this).data("value");

			$('#posid').val(posId);

			$('#DELETEPosFORM').attr('action', '/pos-delete/'+posId);

			$('#DELETEPosMODAL').modal('show');

		});
	});

function resetButton(){
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}

