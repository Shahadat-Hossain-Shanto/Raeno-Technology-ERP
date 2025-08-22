
// function fetchCOAdata(){
//   var treeData = [];
//   $.ajax({
//     type: "GET",
//     url: "/chart-of-accounts-data",
//     dataType:"json",
//     success: function(response){
//       console.log(response.data);
      
//       for(var i = 0; i<response.data.length; i++){
//         treeData.push(response.data[i])
//       }
      
      
//     }
//   });

//   return treeData;
// }

$(document).ready(function () {
    $.ajax({
      type: "GET",
      url: "/chart-of-accounts-data",
      dataType:"json",
      success: function(response){
        // console.log(response.data);
          $('#jstree').jstree({ 
            'core' : { 'data' : response.data},
            'state' : {'opened' : true, 'selected' : true }
           });
           x()
      }
    });

   // $("#jstree").jstree("open_all");

    $('#jstree').on('ready.jstree', function() {
        $("#jstree").jstree("open_all");          
    });
})


  
  function x(){
    $('#jstree')
    // listen for event
    .on('changed.jstree', function (e, data) {

      // $('#ViewAccountModal').modal('show');
      var i, j, r = [];
      for(i = 0, j = data.selected.length; i < j; i++) {
        var headCode = data.instance.get_node(data.selected[i]).id
        var parent = data.instance.get_node(data.selected[i]).id
        var text = data.instance.get_node(data.selected[i]).id
      }
      // $('#event_result').html('Selected: ' + r.join(', '));
      // alert(id);

      $.ajax({
        type: "GET",
        url: "/chart-of-accounts-get-data/"+headCode,
        dataType:"json",
        success: function(response){
          // console.log(response.data);
          $('#ViewAccountModal').modal('show');
          

          if (response.status == 200) {
            $('#edit_headcode').val(response.data[0].head_code);
            $('#edit_headname').val(response.data[0].head_name);
            $('#edit_parenthead').val(response.data[0].parent_head);
            $('#edit_parentheadlevel').val(response.data[0].parent_head_level);
            $('#edit_headtype').val(response.data[0].head_type);
            
            $('#edit_istransaction').val(response.data[0].is_transaction);
            $('#edit_isactive').val(response.data[0].is_active);
            $('#edit_isgl').val(response.data[0].is_general_ledger);

            // console.log($('#edit_istransaction').val())
            if($('#edit_istransaction').val() == 1){
              $('#edit_istransaction').attr("checked", "checked")
            }else{
              $('#edit_istransaction').removeAttr('checked')
            }

            if($('#edit_isactive').val() == 1){
              $('#edit_isactive').attr("checked", "checked")
            }else{
              $('#edit_isactive').removeAttr('checked')
            }

            if($('#edit_isgl').val() == 1){
              $('#edit_isgl').attr("checked", "checked")
            }else{
              $('#edit_isgl').removeAttr('checked')
            }

            $('#coaid').val(response.data[0].id);
          }
          
        }
      });

    })
    // create the instance
    .jstree();


  }

//UPDATE BATCH
$(document).on('submit', '#ViewAccountFORM', function (e)
{
  e.preventDefault();

  var id = $('#coaid').val(); 

  let EditFormData = new FormData($('#ViewAccountFORM')[0]);

  if ($('#edit_istransaction').is(":checked")) {
      EditFormData.append('istransactionX', 1);
  }else{
      EditFormData.append('istransactionX', 0);
  }

  if ($('#edit_isactive').is(":checked")) {
      EditFormData.append('isactiveX', 1);
  }else{
      EditFormData.append('isactiveX', 0);
  }

  if ($('#edit_isgl').is(":checked")) {
      EditFormData.append('isglX', 1);
  }else{
      EditFormData.append('isglX', 0);
  }

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
    url: "/chart-of-accounts-get-data/"+id,
    data: EditFormData,
    contentType: false,
    processData: false,
    success: function(response){
      
      if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#ViewAccountModal').modal('hide');
                // $.notify(response.message, 'success')
                $(location).attr('href','/chart-of-accounts');
            }else{
                // console.log(response.error)
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $('#wrongheadname').empty();

                if(response.error.headname == null){
                  headname = ""
                }else{
                  headname = response.error.headname[0]
                }
                
                $('#wrongheadname').append('<span id="">'+headname+'</span>');
                
            }
    }
  });
});


//NEW---------------
$(document).on('click', '#newBtn', function (e) {
  e.preventDefault();

  // Remove focus from button to avoid accessibility warning
  document.activeElement.blur();

  // Wait for ViewAccountModal to fully hide before processing
  $('#ViewAccountModal').on('hidden.bs.modal', function () {

    // Unbind immediately to avoid duplicate triggers
    $('#ViewAccountModal').off('hidden.bs.modal');

    // Now fetch the data and show the NewAccountModal after it's ready
    var headCode = $('#edit_headcode').val();

    $.ajax({
      type: "GET",
      url: "/chart-of-accounts-get-data-new/" + headCode,
      dataType: "json",
      success: function (response) {

        if ($.isEmptyObject(response.data)) {
          $.ajax({
            type: "GET",
            url: "/chart-of-accounts-get-data/" + headCode,           
            dataType: "json",
            success: function (response) {
              fillNewModal(response.data[0]);
              $('#NewAccountModal').modal('show');
              console.log(response.data[0]);
            }
          });
        } else {
          fillNewModal(response.data);
          $('#NewAccountModal').modal('show');
          console.log(response.data);
          
        }

      }
    });

  });

  $('#ViewAccountModal').modal('hide');
});

function fillNewModal(data) {
  let nextHeadCode = data.latest_child_code
    ? parseInt(data.latest_child_code) + 1
    : (parseInt(data.parent_head_level) * 100) + 1;

  $('#headcode').val(nextHeadCode);

  $('#parenthead').val(data.parent_head_name || data.head_name);
  $('#parentheadlevel').val(data.parent_head_level);
  $('#headtype').val(data.head_type);
  $('#istransaction').prop('checked', data.is_transaction == 1);
  $('#isactive').prop('checked', data.is_active == 1);
  $('#isgl').prop('checked', data.is_general_ledger == 1);
  $('#coaid').val(data.id);
}




//NEW BATCH
$(document).on('submit', '#NewAccountFORM', function (e)
{
  e.preventDefault();

  // var id = $('#coaid').val(); 

  let formData = new FormData($('#NewAccountFORM')[0]);

  if ($('#edit_istransaction').is(":checked")) {
      formData.append('istransactionX', 1);
  }else{
      formData.append('istransactionX', 0);
  }

  if ($('#edit_isactive').is(":checked")) {
      formData.append('isactiveX', 1);
  }else{
      formData.append('isactiveX', 0);
  }

  if ($('#edit_isgl').is(":checked")) {
      formData.append('isglX', 1);
  }else{
      formData.append('isglX', 0);
  }

  // formData.append('_method', 'PUT');
  
  // console.log(JSON.stringify(formData))

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
    url: "/chart-of-account-create",
    data: formData,
    contentType: false,
    processData: false,
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
    success: function(response){
      
      if($.isEmptyObject(response.error)){
                // alert(response.message);
                $('#NewAccountModal').modal('hide');
                
                // $.notify(response.message, 'success')
                $(location).attr('href','/chart-of-accounts');
            }else{
                // console.log(response.error)
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $('#wrongheadnameX').empty();

                if(response.error.headname == null){
                  headname = ""
                }else{
                  headname = response.error.headname[0]
                }
                
                $('#wrongheadnameX').append('<span id="">'+headname+'</span>');
                
            }
    }
  });
});



 

    



