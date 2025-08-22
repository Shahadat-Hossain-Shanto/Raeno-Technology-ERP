$(document).ready(function () {
	//CREATE DEPOSIT
	$(document).on('submit', '#AddDepositForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddDepositForm')[0]);

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
			url: "/deposit-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);	
				if($.isEmptyObject(response.error)){
                    
                    // alert(response.message)
             		$(location).attr('href','/customer-credits');

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
            $('#wrongid').empty();
            $('#wrongstoreId').empty();
            $('#wrongdeposit_type').empty();
            $('#wrongdeposit').empty();
            $('#wrongdepositDate').empty();

			if(message.id == null){
				id = ""
			}else{
				id = message.id[0]
			}

			if(message.storeId == null){
				storeId = ""
			}else{
				storeId = message.storeId[0]
			}

			if(message.deposit_type == null){
				deposit_type = ""
			}else{
				deposit_type = message.deposit_type[0]
			}

			if(message.deposit == null){
				deposit = ""
			}else{
				deposit = message.deposit[0]
			}

			if(message.depositDate == null){
				depositDate = ""
			}else{
				depositDate = message.depositDate[0]
			}

            $('#wrongid').append('<span id="">'+id+'</span>');
            $('#wrongstoreId').append('<span id="">'+storeId+'</span>');
            $('#wrongdeposit_type').append('<span id="">'+deposit_type+'</span>');
            $('#wrongdeposit').append('<span id="">'+deposit+'</span>');
            $('#wrongdepositDate').append('<span id="">'+depositDate+'</span>');
        // });
    }
});