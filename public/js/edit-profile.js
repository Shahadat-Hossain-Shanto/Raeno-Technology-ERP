$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/profile",
        success: function(response){
            // console.log(response.user)
            if (response.status == 200) {
                $('#edit_name').val(response.user.name);
                $('#edit_email').val(response.user.email);
                $('#edit_contactnumber').val(response.user.contact_number);
                $('#edit_store').val(response.user.store_id).change();
                // $('#edit_password').val(response.user.password);
                // $('#edit_password_confirmation').val(response.user.password);

            }
        }
    });
});

function resetButton(){
    $(location).attr('href','/home');
}

$(document).on('submit', '#EditUserForm', function (e){
    e.preventDefault();
    var id = $('#user_id').val();

    // alert(productId)
    let EditFormData = new FormData($('#EditUserForm')[0]);
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
        url: "/update-profile/"+id,
        data: EditFormData,
        contentType: false,
        processData: false,
        success: function (response) {

        // imageUpdate()
        // $.notify(response.message);
        if($.isEmptyObject(response.error)){
            // imageUpdate()
             // $.notify(response.message, 'success');
             // resetButton()
              $(location).attr('href','/home');

         }else{
             //
             $('body').loadingModal('destroy');
             printErrorEditMsg(response.error);
         }

    }
    });
});

function printErrorEditMsg (message) {
    $('#edit_wrongname').empty();
    $('#edit_wrongemail').empty();
    $('#edit_wrongcontactnumber').empty();
    $('#edit_wrongstore').empty();
    $('#edit_wrongroles').empty();
    $('#edit_wrongpassword').empty();
    $('#edit_wrongpassword_confirmation').empty();


    if(message.name == null){
        name = ""
    }else{
        name = message.name[0]
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


    $('#edit_wrongname').append('<span id="">'+name+'</span>');
    $('#edit_wrongemail').append('<span id="">'+email+'</span>');

    // $('#wrongpassword_confirmation').append('<span id="">'+password_confirmation+'</span>');
    $('#edit_wrongcontactnumber').append('<span id="">'+contactnumber+'</span>');
    $('#edit_wrongstore').append('<span id="">'+store+'</span>');
    $('#edit_wrongroles').append('<span id="">'+roles+'</span>');
    $('#edit_wrongpassword').append('<span id="">'+password+'</span>');
    // if(message.password_confirmation == null){
    // 	password_confirmation = ""
    // }else{
    // 	password_confirmation = message.password_confirmation[0]
    //


// });
}
