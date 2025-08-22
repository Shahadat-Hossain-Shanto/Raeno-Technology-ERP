$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE STORE
	$(document).on('submit', '#AddStoreForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddStoreForm')[0]);

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
                url: "/warehouse-create",
				data: formData,
				contentType: false,
				processData: false,
				success: function(response){
					// console.log(response.message);
					if($.isEmptyObject(response.error)){
                        // alert(response.message);
                        $(location).attr('href','/warehouse-list');
                    }else{
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
                $('#wrongstorename').empty();
				$('#wrongstoreaddress').empty();
				$('#wrongcontactnumber').empty();

				if(message.store_name == null){
					store_name = ""
				}else{
					store_name = message.store_name[0]
				}
				if(message.storeaddress == null){
					storeaddress = ""
				}else{
					storeaddress = message.storeaddress[0]
				}
				if(message.contactnumber == null){
					contactnumber = ""
				}else{
					contactnumber = message.contactnumber[0]
				}

                $('#wrongstorename').append('<span id="">'+store_name+'</span>');
                $('#wrongstoreaddress').append('<span id="">'+storeaddress+'</span>');
                $('#wrongcontactnumber').append('<span id="">'+contactnumber+'</span>');
            // });
        }

});

$(document).ready(function () {
    var t = $('#store_table').DataTable({
        ajax: {
            "url": "/warehouse-list-data",
            "dataSrc": "store",
        },
        columns: [
          	{ data: null },
            { data: 'store_name' },
            { data: 'store_address' },
            { data: 'contact_number' },
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
	    	var PageInfo = $('#store_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();

});

	//EDIT STORE
	$(document).on('click', '.edit_btn', function (e) {
		e.preventDefault();

		var storeId = $(this).val();
		// alert(storeId);
        $('#EDITStoreMODAL').modal('show');

			$.ajax({
			type: "GET",
                url: "/warehouse-edit/"+storeId,
			success: function(response){
                console.log(response)
				if (response.status == 200) {
					$('#edit_storename').val(response.store.store_name);
					$('#edit_storeaddress').val(response.store.store_address);
					$('#edit_contactnumber').val(response.store.contact_number);
					$('#storeid').val(storeId);
				}
			}
		});
	});

	//UPDATE STORE
	$(document).on('submit', '#UPDATEStoreFORM', function (e)
	{
		e.preventDefault();

		var id = $('#storeid').val();

		let EditFormData = new FormData($('#UPDATEStoreFORM')[0]);

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
            url: "/warehouse-edit/"+id,
			data: EditFormData,
			contentType: false,
			processData: false,
			success: function(response){
				if($.isEmptyObject(response.error)){
                    // alert(response.message);
                    $('#EDITStoreMODAL').modal('hide');
                    $.notify(response.message, 'success')
                    // fetchStore()
                    $(location).attr('href','/warehouse-list');
                }else{
                    // printErrorMsg(response.error);
                    $('body').loadingModal('destroy');
                    $('#edit_wrongstorename').empty();
					$('#edit_wrongstoreaddress').empty();
					$('#edit_wrongcontactnumber').empty();

					if(response.error.store_name == null){
						store_name = ""
					}else{
						store_name = response.error.store_name[0]
					}
					if(response.error.storeaddress == null){
						storeaddress = ""
					}else{
						storeaddress = response.error.storeaddress[0]
					}
					if(response.error.contactnumber == null){
						contactnumber = ""
					}else{
						contactnumber = response.error.contactnumber[0]
					}

	                $('#edit_wrongstorename').append('<span id="">'+store_name+'</span>');
	                $('#edit_wrongstoreaddress').append('<span id="">'+storeaddress+'</span>');
	                $('#edit_wrongcontactnumber').append('<span id="">'+contactnumber+'</span>');
                }
			}
		});
	});

	//Delete Client
	$(document).ready( function () {
		$('#store_table').on('click', '.delete_btn', function(){

			var storeId = $(this).data("value");

			$('#storeid').val(storeId);
            $('#DELETEStoreFORM').attr('action', '/warehouse-delete/'+storeId);
			$('#DELETEStoreMODAL').modal('show');

		});
	});


//DATA TABLE
