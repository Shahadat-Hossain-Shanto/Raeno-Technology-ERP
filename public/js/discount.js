$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE PRODUCT
	$(document).on('submit', '#AddDiscountForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddDiscountForm')[0]);

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
			url: "/discount-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				//console.log(response.message);	
				// $(location).attr('href','/discount-list');
				if($.isEmptyObject(response.error)){
                    
             		$(location).attr('href','/discount-list');

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
                $('#wrongdiscountname').empty();
                $('#wrongdiscounttype').empty();
				$('#wrongvalue').empty();
				$('#wrongstore').empty();
				$('#wrongstorex').empty();
				

				if(message.discountname == null){
					discountName = ""
				}else{
					discountName = message.discountname[0]
				}

				if(message.discounttype == null){
					discountType = ""
				}else{
					discountType = message.discounttype[0]
				}
				
				if(message.value == null){
					discount = ""
				}else{
					discount = message.value[0]
				}

				if(message.store == null){
					store = ""
				}else{
					store = message.store[0]
				}

				if(message.storex == null){
					storex = ""
				}else{
					storex = message.storex[0]
				}

                $('#wrongdiscountname').append('<span id="">'+discountName+'</span>');
                $('#wrongdiscounttype').append('<span id="">'+discountType+'</span>');
                $('#wrongvalue').append('<span id="">'+discount+'</span>');
                $('#wrongstore').append('<span id="">'+store+'</span>');
                $('#wrongstorex').append('<span id="">'+storex+'</span>');
            // });
        }

});

//DISCOUNT LIST
$(document).ready(function () {
    var t = $('#discount_table').DataTable({
        ajax: {
            "url": "/discount-list-data",
            "dataSrc": "discount",
        },
        columns: [
          	{ data: null },
            { data: 'discountName' },
            { data: 'discountType' },
            { data: 'discount' },
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
	    	var PageInfo = $('#discount_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();

});

//EDIT DISCOUNT
	$(document).on('click', '.edit_btn', function (e) {
		e.preventDefault();

		var discountId = $(this).val();
		// alert(discountId);
		$('#EDITDiscountMODAL').modal('show');
			
			$.ajax({
			type: "GET",
			url: "/discount-edit/"+discountId,
			success: function(response){
				if (response.status == 200) {
					// console.log(response.discount)
					$('#edit_discountname').val(response.discount.discountName);
					$('#edit_discounttype').val(response.discount.discountType);
					$('#edit_value').val(response.discount.discount);
					// $('#edit_isrestricted').val(response.discount.isRestricted);
					$('#edit_store').val(response.discount.storeId).change();
					$('#discountid').val(discountId);
				}
			}
		});
	});

	//UPDATE DISCOUNT
	$(document).on('submit', '#UPDATEDiscountFORM', function (e)
	{
		e.preventDefault();

		var id = $('#discountid').val(); 

		let EditFormData = new FormData($('#UPDATEDiscountFORM')[0]);

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
			url: "/discount-edit/"+id,
			data: EditFormData,
			contentType: false,
			processData: false,
			success: function(response){
				if($.isEmptyObject(response.error)){
                    // alert(response.message);
                    $('#EDITDiscountMODAL').modal('hide');
                    $.notify(response.message, 'success')
                    $(location).attr('href','/discount-list');
                }else{
                	$('body').loadingModal('destroy');
                	// console.log(response.error)
                    // printErrorMsg(response.error);
                    $('#edit_wrongdiscountname').empty();
					$('#edit_wrongdiscounttype').empty();
					$('#edit_wrongvalue').empty();
					$('#edit_wrongstore').empty();

					if(response.error.discountname == null){
						discountname = ""
					}else{
						discountname = response.error.discountname[0]
					}
					if(response.error.discounttype == null){
						discounttype = ""
					}else{
						discounttype = response.error.discounttype[0]
					}
					if(response.error.value == null){
						value = ""
					}else{
						value = response.error.value[0]
					}
					if(response.error.store == null){
						store = ""
					}else{
						store = response.error.store[0]
					}

	                $('#edit_wrongdiscountname').append('<span id="">'+discountname+'</span>');
	                $('#edit_wrongdiscounttype').append('<span id="">'+discounttype+'</span>');
	                $('#edit_wrongvalue').append('<span id="">'+value+'</span>');
	                $('#edit_wrongstore').append('<span id="">'+store+'</span>');
                }
			}
		});
	});

//Delete Discount
$(document).ready( function () {
	$('#discount_table').on('click', '.delete_btn', function(){

		var discountId = $(this).data("value");

		$('#discountid').val(discountId);

		$('#DELETEDiscountFORM').attr('action', '/discount-delete/'+discountId);

		$('#DELETEDiscountMODAL').modal('show');

	});
});




function resetButton(){
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}