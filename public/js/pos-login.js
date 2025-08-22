$('select').on('change', function() {
    if ($('#storeid').val() != $('#userStoreId').val()) {
        // alert('You dont belong to ' + $("#storeid").find("option:selected").text() + ' store')
        ajaxStart: $('body').loadingModal({
            position: 'auto',
            text: 'You dont belong to ' + $("#storeid").find("option:selected").text() + ' store',
            color: '#fff',
            opacity: '0.7',
            backgroundColor: 'rgb(0,0,0)',
            animation: 'foldingCube'
        }),
            window.location.reload();
        $("#posHeading").empty()
        $("#poses").empty()
        $("#posPin").empty();
        $("#pinerror").empty()
    }
    else {
//   if(this.value != "default"){

    var storeId = this.value;

    $.ajax({
      type: "GET",
      url: "/pos-search/"+storeId,
      dataType: "json",
      success: function(response){
        $("#posHeading").empty()
        $("#pinerror").empty()
        $("#posPin").empty();
        $("#posHeading").append('<h3 class="">POS</h3>');
        $("#poses").empty()
        $.each(response.poses, function(key, item) {

          $("#poses").append('\
            <label class="">\
              <div class="card card-dark card-outline border-dark shadow bg-body rounded mr-3" style="width: 10em; cursor: pointer;">\
                <div class="card-body text-dark text-center">\
                  <i class="far fa-cash-register fa-2x" style="display: block; margin-left: auto; margin-right: auto;"></i>\
                  <h5 class="card-text pt-2">'+item.pos_name+'</h5>\
                  <div class="round" style=" position:absolute; top:10px; right:25px;">\
                    <input type="checkbox" name="posid" id="checkbox" value="'+item.id+'" onchange="Checked(this)">\
                    <label for="checkbox"></label>\
                  </div>\
                </div>\
              </div>\
            </label>');

        })
      }
    });


//   }else{
    $("#posHeading").empty()
    $("#poses").empty()
    $("#posPin").empty();
    $("#pinerror").empty()
  }

});



function Checked(checkbox) {
  if (checkbox.checked) {
    $("#posPin").empty();
    $("#pinerror").empty()

    $("#posPin").append('\
      <div class="col-auto">\
          <label for="pospin" class="col-form-label fw-normal">POS-PIN</label>\
      </div>\
      <div class="col-3">\
        <input class="form-control" type="password" name="pospin" id="pospin">\
      </div>\
      <div class="col-3">\
        <button type="submit" class="btn btn-primary"> <i class="fas fa-forward"></i></button>\
      </div>');

  } else {
    alert ("No POS selected! Select a POS to continue.");
    $("#posPin").empty();
    $("#pinerror").empty()

  }
}

$(document).ready(function () {

  $(document).on('submit', '#posPinForm', function (e){
  e.preventDefault();

  let formData= {};

  formData["storeId"]     = $('#storeid').val()
  formData["storeName"]   = $("#storeid").find("option:selected").text()
  formData["posId"]       = $('#checkbox:checked').val()
  formData["posPin"]      = $('#pospin').val()
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
      url: "/pos-login",
      data: formData,
      dataType: "json",
      headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
      success: function(response){
        if(response.url != null){

          window.location=response.url;
        }else{
          $('body').loadingModal('destroy');
          $("#pinerror").empty()
          $("#pinerror").append('\
            <div class="col-6">\
              <div id="emailHelp" class="form-text text-danger" style="padding-left: 75px; font-weight: bold;">Wrong password.</div>\
            </div>')
        }

      }
    })
  })

})
