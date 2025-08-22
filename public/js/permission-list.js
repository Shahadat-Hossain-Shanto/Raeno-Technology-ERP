$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    //CREATE Permission
    $(document).on("submit", "#AddPermissionForm", function (e) {
        e.preventDefault();

        let formData = new FormData($("#AddPermissionForm")[0]);

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
            url: "/permission-create",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                //
                $(location).attr("href", "/permission-list");
                if ($.isEmptyObject(response.error)) {
                    $(location).attr("href", "/permission-list");
                } else {
                    // 
                    $('body').loadingModal('destroy');
                    printErrorMsg(response.error);
                }
            },
        });
    });

    function printErrorMsg(message) {
        // $(".print-error-msg").find("ul").html('');
        // $(".print-error-msg").css('display','block');

        // $.each( message, function( key, item ) {
        // $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
        $("#wrong_permission_name").empty();
        $("#wrong_permission_group").empty();

        if (message.permission_name == null) {
            permission_name = "";
        } else {
            permission_name = message.name[0];
        }
        if (message.permission_group == null) {
            permission_group = "";
        } else {
            permission_group = message.group[0];
        }

        $("#wrong_permission_name").append('<span id="">' + name + "</span>");
        $("#wrong_permission_group").append(
            '<span id="">' + group_name + "</span>"
        );

        // });
    }
});

$(document).ready(function () {
    var t = $('#permission_table').DataTable({
        ajax: {
            "url": "/permission-list-data",
            "dataSrc": "permissions",
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'permissions_name' },
            { data: 'group_name' },
            { "render": function ( data, type, row, meta ){ 
                    
                    return '<button type="button" value="' +row.id +'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button>\
                    <a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="' + row.id +'"><i class="fas fa-trash"></i></a>'
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
        order: [[1, 'asc']],
        pageLength : 10,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });


    t.on('order.dt search.dt', function () {

        t.on( 'draw.dt', function () {
            var PageInfo = $('#permission_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();


});

//EDIT Permission
$(document).on("click", ".edit_btn", function (e) {
    e.preventDefault();

    var Id = $(this).val();
    // alert(vatId);
    $("#EDITPermissionMODAL").modal("show");

    $.ajax({
        type: "GET",
        url: "/permission-edit/" + Id,
        success: function (response) {
            if (response.status == 200) {
                $("#edit_permission_name").val(
                    response.permission.permissions_name
                );
                // $("#edit_permission_group").val(response.permission.group_name);
                // $("#edit_store").val(response.vat.store);
                $("#id").val(response.permission.id);
                $("#edit_permission_group").val(response.permission.group_name);
                
                // $("#edit_permission_group_type").empty();
                var newOption = $(
                    '<option value="' +
                        response.permission.group_name +
                        '">' +
                        response.permission.group_name +
                        "</option>"
                        
                );
                // $('#edit_permission_group').append('<option value=" " selected="" disabled="">' + "Select  Sub-Group Code" + '</option>');
                // $.each(response.permission_groups, function (key, item) {
                //      var group_name=item.group_name;

                //      
                    
                //      $('#edit_permission_group').append('<option value="' + group_name + '">' + group_name + '</option>');
                // })
                // $('.selectpicker').selectpicker('refresh');
                // $("#edit_permission_group").append(newOption);
                $('.selectpicker').selectpicker('refresh');

                $("#edit_route_name").val(response.permission.name);

                // var newOption_permission_type = $(
                //     '<option value="' +
                //         response.permission.permission_type +
                //         '" selected>' +
                //         response.permission.permission_type +
                //         "</option>"

                       
                // );
                // if ($('#newOption_permission_type').val() != ''){
                //     // 
                //     $("#edit_permission_group_type").append(newOption_permission_type);
                // }
                // else{
                
                //         $("#edit_permission_group_type").empty();
                               
                  
                // }
               
            }
        },
    });
});

//UPDATE VAT
$(document).on("submit", "#UPDATEPermissionFORM", function (e) {
    e.preventDefault();

    var id = $("#id").val();

    let EditFormData = new FormData($("#UPDATEPermissionFORM")[0]);

    EditFormData.append("_method", "PUT");

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
        url: "/permission-edit/" + id,
        data: EditFormData,
        contentType: false,
        processData: false,
        success: function (response) {
            if ($.isEmptyObject(response.error)) {
                // alert(response.message);
                $("#EDITVatMODAL").modal("hide");
                $.notify(response.message, "success");
                $(location).attr("href", "/permission-list");
            } else {
                // 
                // printErrorMsg(response.error);
                $('body').loadingModal('destroy');
                $("#wrong_permission_name").empty();
                $("#wrong_permission_group").empty();
            }
        },
    });
});

//Delete Vat
$(document).ready(function () {
    $("#permission_table").on("click", ".delete_btn", function () {
        var Id = $(this).data("value");

        $("#id").val(Id);

        $("#DELETEPermissionFORM").attr("action", "/permission-delete/" + Id);

        $("#DELETEPermissionMODAL").modal("show");
    });
});


function resetButton() {
    $("form").on("reset", function () {
        setTimeout(function () {
            $(".selectpicker").selectpicker("refresh");
        });
    });
}
