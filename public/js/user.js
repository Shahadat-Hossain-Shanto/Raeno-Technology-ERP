$(document).ready(function () {
	//CREATE UNIT
	$(document).on('submit', '#AddUserForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddUserForm')[0]);

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
			url: "/create-user",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);
				if($.isEmptyObject(response.error)){
                    // alert('Success')
             		$(location).attr('href','/users-list');

                }else{
                	// console.log(response.error)
                	$('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
			}
		});
	});

	function printErrorMsg (message) {
            $('#wrongname').empty();
            $('#wrongusername').empty();
            $('#wrongaddress').empty();
            $('#wrongemail').empty();
            $('#wrongpassword').empty();
            $('#wrongpassword_confirmation').empty();
            $('#wrongcontactnumber').empty();
            $('#wrongstore').empty();
            $('#wrongroles').empty();

			if(message.name == null){
				name = ""
			}else{
				name = message.name[0]
			}
            if(message.address == null){
                address = "";
            }else{
                address = message.address[0];
            }

            if(message.username == null){
                username = "";
            }else{
                username = message.username[0];
            }
			if(message.email == null){
				email = ""
			}else{
				email = message.email[0]
			}

			if(message.contactnumber == null){
				contactnumber = ""
			}else{
				contactnumber = message.contactnumber[0]
			}

			if(message.roles == null){
				roles = ""
			}else{
				roles = message.roles[0]
			}

			if(message.store == null){
				store = ""
			}else{
				store = message.store[0]
			}

			if(message.password == null){
				password = ""
			}else{
				password = message.password[0]
			}


			// if(message.password_confirmation == null){
			// 	password_confirmation = ""
			// }else{
			// 	password_confirmation = message.password_confirmation[0]
			// }

            $('#wrongname').append('<span id="">'+name+'</span>');
            $('#wrongaddress').append('<span>'+address+'</span>');
            $('#wrongusername').append('<span>'+username+'</span>');
            $('#wrongemail').append('<span id="">'+email+'</span>');
            $('#wrongpassword').append('<span id="">'+password+'</span>');
            // $('#wrongpassword_confirmation').append('<span id="">'+password_confirmation+'</span>');
            $('#wrongcontactnumber').append('<span id="">'+contactnumber+'</span>');
            $('#wrongstore').append('<span id="">'+store+'</span>');
            $('#wrongroles').append('<span id="">'+roles+'</span>');


        // });
    }



});
