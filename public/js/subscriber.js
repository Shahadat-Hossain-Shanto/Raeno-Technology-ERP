function showPassword() {
    var x = document.getElementById("password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }

$(document).ready(function () {
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $(document).on('submit', '#AddSubscriberForm', function (e) {
      e.preventDefault();

      let formData = new FormData($('#AddSubscriberForm')[0]);
      console.log(formData);

      let orgname = $('#orgname').val();
      let orgaddress = $('#orgaddress').val();
      let ownername = $('#ownername').val();
      let contactnumber = $('#contactnumber').val();
      let password = $('#password').val();
      let registrationType = $('#registrationType').val();
      if(orgname==''|| orgaddress==''|| ownername==''|| contactnumber==''|| password==''|| registrationType==''){
          $.notify("Fillup the required fields", {className:"error", position:"top-right"})
      }
      else{
          $.ajax({
              type: "POST",
              url: "/subscriber-create",
              data: formData,
              contentType: false,
              processData: false,
              success: function(response){
                  $.notify(response.message, {className:"success", position:"top-right"})
                  if(response.message=='Subscription Successful'){
                      alert('Your Subscribtion is Successful, our Sales Team will contact you with in 24 hours');
                      $(location).attr('href', '/');
                  }
              }
          });
      }

  });
});
