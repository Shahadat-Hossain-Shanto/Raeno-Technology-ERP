$('#yearly_allotment_div').hide()
var benefit_regularity;


$(document).ready(function () {
    $('#benefit_regularity').on('change', function () {
        benefit_regularity = $('#benefit_regularity').find("option:selected").val()
        if (benefit_regularity == 'regular') {
            $('#yearly_allotment_div').hide()
        } else if (benefit_regularity == 'special') {
            $('#yearly_allotment_div').show()
        }
    });


    $(document).on('submit', '#AddBenefitForm', function (e) {
        e.preventDefault();
        let formData = new FormData($('#AddBenefitForm')[0]);

        // benefit_regularity=$('#benefit_regularity').find("option:selected").val();
        var yearly_allotment = $('#yearly_allotment').val();
        // console.log('yearly_allotment :' + yearly_allotment)
      
        
        // else if(yearly_allotment.length==0){
        //     $('#wrong_yearly_allotment').text('Yearly Allotment is required.');
        //   }
     
          
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
                url: "/benefit-create",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if ($.isEmptyObject(response.error)) {
                        $(location).attr('href', '/benefit-list');
                    } else {
                        $('body').loadingModal('destroy');
                        printErrorMsg(response.error);
                    }
                }
            });

        


        function printErrorMsg(message) {
            $('#wrong_benefit_name').empty();
            $('#wrong_benefit_type').empty();

            $('#wrong_status').empty();
            $('#wrong_payment_type').empty();
            $('#wrong_benefit_regularity').empty();

            if (message.benefit_name == null) {
                benefit_name = ""
            } else {
                benefit_name = message.benefit_name[0]
            }
            if (message.benefit_type == null) {
                benefit_type = ""
            } else {
                benefit_type = message.benefit_type[0]
            }

            if (message.status == null) {
                status = ""
            } else {
                status = message.status[0]
            }

            if (message.payment_type == null) {
                payment_type = ""
            } else {
                payment_type = message.payment_type[0]
            }
            if (message.benefit_regularity == null) {
                benefit_regularity = ""
            } else {
                benefit_regularity = message.benefit_regularity[0]
            }



            $('#wrong_benefit_name').append('<span id="">' + benefit_name + '</span>');
            $('#wrong_benefit_type').append('<span id="">' + benefit_type + '</span>');
            $('#wrong_status').append('<span id="">' + status + '</span>');
            $('#wrong_payment_type').append('<span id="">' + payment_type + '</span>');
            $('#wrong_benefit_regularity').append('<span id="">' + benefit_regularity + '</span>');





            // });
        }
    });



    


    //edit
    var check_benefit_regularity;
    $(document).on('click', '.edit_btn', function (e) {
        e.preventDefault();
        $('#edit_yearly_allotment_div').hide()

        var id = $(this).val();

        $('#EDITBenefitMODAL').modal('show');

        $.ajax({
            type: "GET",
            url: "/benefit-edit/" + id,
            success: function (response) {
                // console.log(response.status)
                if (response.status == 200) {
                    // console.log(response.designation.designation_description)
                    $('#edit_benefit_name').val(response.benefit.benefit_name);
                    $('#edit_benefit_type').val(response.benefit.benefit_type).change();
                    $('#edit_status').val(response.benefit.status).change();
                    $('#edit_payment_type').val(response.benefit.payment_type).change();
                    $('#edit_benefit_regularity').val(response.benefit.benefit_regularity).change();
                    $('#edit_benefit_regularity_check').val(response.benefit.benefit_regularity)
                    $('#benefitId').val(response.benefit.id);
                  
                   

                }

                // check_benefit_regularity = $('#edit_benefit_regularity_check').val();
                // if (check_benefit_regularity == 'regular') {
                //     $('#edit_yearly_allotment_div').hide()
                // }
                // else {
                //     $('#edit_yearly_allotment_div').show()
                // }


                // $('#edit_benefit_regularity').on('change', function () {
                //     benefit_regularity = $('#edit_benefit_regularity').find("option:selected").val()
                //     if (benefit_regularity == 'regular') {
                //         $('#edit_yearly_allotment_div').hide()
                //         $('#edit_benefit_regularity_check').val(benefit_regularity)
                //     } else if (benefit_regularity == 'special') {
                //         $('#edit_yearly_allotment_div').show()
                //         $('#edit_benefit_regularity_check').val(benefit_regularity)
                //     }
                // });


            }
        });

    });

});

$(document).ready(function () {
    var t = $('#benefit_table').DataTable({
        ajax: {
            "url": "/benefit-list-data",
            "dataSrc": "benefit",
        },
        columns: [
            { data: null },
            { data: 'benefit_name' },
            { data: 'benefit_type' },
            { data: 'payment_type' },
            { data: 'benefit_regularity' },
            { "render": function ( data, type, row, meta ){ 
                    
                    if(row.status == 1){
                        var status = "Active"
                    }else{
                        var status = "Inactive"
                    }

                    return status
                } 
            },
            { "render": function ( data, type, row, meta ){ 
                    
                    return '<button type="button" value="' + row.id + '" class="edit_btn btn btn-secondary btn-sm" title="Edit"><i class="fas fa-edit"></i></button>\
                        <a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+ row.id +'" title="Delete"><i class="fas fa-trash"></i></a>'
                } 
            },
            { "render": function ( data, type, row, meta ){ 
                    
                    return '<input type="hidden" class="form-control" name="benefit_regularity_check" id="benefit_regularity_check" value="'+row.benefit_regularity+'">'
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
            var PageInfo = $('#benefit_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();

});

//update
$(document).on('submit', '#UPDATEBenefitFORM', function (e) {
    e.preventDefault();

    var id = $('#benefitId').val();

    // console.log(id)

    let EditFormData = new FormData($('#UPDATEBenefitFORM')[0]);

    EditFormData.append('_method', 'PUT');

  
    // console.log('yearly_allotment :' + yearly_allotment)
  
    
    
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
            url: "/benefit-edit/" + id,
            data: EditFormData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
    
                if ($.isEmptyObject(response.error)) {
                    // alert(response.message);
                    $('#EDITBenefitMODAL').modal('hide');
                    $.notify(response.message, 'success')
                    $(location).attr('href', '/benefit-list');
                } else {
    
                    printEditErrorMsg(response.error);
                }
            }
        });
    

    
});

//update error msg
function printEditErrorMsg(message) {
    $('#edit_wrong_benefit_name').empty();
    $('#edit_wrong_benefit_type').empty();
    $('#edit_wrong_status').empty();
    $('#edit_wrong_payment_type').empty();


    if (message.benefit_name == null) {
        benefit_name = ""
    } else {
        benefit_name = message.benefit_name[0]
    }
    if (message.benefit_type == null) {
        benefit_type = ""
    } else {
        benefit_type = message.benefit_type[0]
    }

    if (message.status == null) {
        status = ""
    } else {
        status = message.status[0]
    }

    if (message.payment_type == null) {
        payment_type = ""
    } else {
        payment_type = message.payment_type[0]
    }


    $('#edit_wrong_benefit_name').append('<span id="">' + benefit_name + '</span>');
    $('#edit_wrong_benefit_type').append('<span id="">' + benefit_type + '</span>');
    $('#edit_wrong_status').append('<span id="">' + status + '</span>');
    $('#edit_wrong_payment_type').append('<span id="">' + payment_type + '</span>');


    // });
}

//delete
$(document).ready(function () {
    $('#benefit_table').on('click', '.delete_btn', function () {

        var id = $(this).data("value");

        $('#delete_benefitid').val(id);

        $('#DELETEBenefitFORM').attr('action', '/benefit-delete/' + id);

        $('#DELETEBenefitMODAL').modal('show');

    });
});


function resetButton() {
    $('form').on('reset', function () {
        setTimeout(function () {
            $('.selectpicker').selectpicker('refresh');
        });
    });
}

//data table
// $(document).ready(function () {
//     $('#benefit_table').DataTable({
//         pageLength: 10,
//         lengthMenu: [
//             [5, 10, 20, -1],
//             [5, 10, 20, 'Todos']
//         ],

//     });
// });
