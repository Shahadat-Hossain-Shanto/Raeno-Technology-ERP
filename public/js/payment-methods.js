$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE PaymentMethod
	$(document).on('submit', '#AddPaymentMethodForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddPaymentMethodForm')[0]);

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
			url: "/payment-method-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				// console.log(response.message);	
				
				if($.isEmptyObject(response.error)){
                    
             		$(location).attr('href','/payment-method-list');

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
            $('#wrongpaymentmethod').empty();			

			if(message.paymentmethod == null){
				paymentmethod = ""
			}else{
				paymentmethod = message.paymentmethod[0]
			}
			
            $('#wrongpaymentmethod').append('<span id="">'+paymentmethod+'</span>');
            
    }

});


//PaymentMethod LIST
$(document).ready(function () {
    var t = $('#paymentmethod_table').DataTable({
        ajax: {
            "url": "/payment-method-list-data",
            "dataSrc": "paymentMethods",
        },
        columns: [
          	{ data: null },
            { data: 'paymentType' },
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
	    	var PageInfo = $('#paymentmethod_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();

});

	//EDIT PaymentMethod
	$(document).on('click', '.edit_btn', function (e) {
		e.preventDefault();

		var paymentMethodId = $(this).val();
		// alert(categoryId);
		$('#EDITPaymentMethodMODAL').modal('show');
			
			$.ajax({
			type: "GET",
			url: "/payment-method-edit/"+paymentMethodId,
			success: function(response){
				if (response.status == 200) {
					$('#edit_paymentmethod').val(response.paymentMethod.paymentType);
					$('#paymentmethodid').val(paymentMethodId);
				}
			}
		});
	});

	//UPDATE PaymentMethod
	$(document).on('submit', '#UPDATEPaymentMethodFORM', function (e)
	{
		e.preventDefault();

		var id = $('#paymentmethodid').val(); 

		let EditFormData = new FormData($('#UPDATEPaymentMethodFORM')[0]);

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
			url: "/payment-method-edit/"+id,
			data: EditFormData,
			contentType: false,
			processData: false,
			success: function(response){
				if($.isEmptyObject(response.error)){
                    // alert(response.message);
                    $('#EDITPaymentMethodMODAL').modal('hide');
                    // $.notify(response.message, 'success')
                    $(location).attr('href','/payment-method-list');
                }else{
                	// console.log(response.error)
                    // printErrorMsg(response.error);
                    $('body').loadingModal('destroy');
                    $('#edit_wrongpaymentmethod').empty();

					if(response.error.paymentmethod == null){
						paymentmethod = ""
					}else{
						paymentmethod = response.error.paymentmethod[0]
					}

	                $('#edit_wrongpaymentmethod').append('<span id="">'+paymentmethod+'</span>');
	                
                }
			}
		});
	});

	//DELETE PaymentMethod
	$(document).ready( function () {
		$('#paymentmethod_table').on('click', '.delete_btn', function(){

			var paymentMethodId = $(this).data("value");

			$('#paymentmethodid').val(paymentMethodId);

			$('#DELETEPaymentMethodFORM').attr('action', '/payment-method-delete/'+paymentMethodId);

			$('#DELETEPaymentMethodMODAL').modal('show');

		});
	});


