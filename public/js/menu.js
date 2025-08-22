$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE BRAND
	$(document).on('submit', '#AddMenuForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddMenuForm')[0]);

		$.ajax({
			type: "POST",
			url: "/menu-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				// console.log(response.message);	
				$(location).attr('href','/menu-list');
			}
		});

	});

});
//CATEGORY LIST
fetchMenu();
function fetchMenu(){
    $.ajax({
        type: "GET",
        url: "/menu-list",
        dataType:"json",
        success: function(response){
            $('tbody').html("");
            $.each(response.menu, function(key, item) {
                $('tbody').append('<tr>\
                <td>'+item.id+'</td>\
                <td>'+item.name+'</td>\
                <td>'+item.href+'</td>\
                <td>\
                    <button type="button" value="'+item.id+'" class="edit_btn btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i>\
                    </button>\
                    <a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+item.id+'"><i class="fas fa-trash"></i></a>\
                </td>\
            </tr>');
            })	
        }
    });
}

//EDIT CATEGORY
$(document).on('click', '.edit_btn', function (e) {
    e.preventDefault();

    var menuId = $(this).val();
    // alert(categoryId);
    $('#EDITMenuMODAL').modal('show');
        
        $.ajax({
        type: "GET",
        url: "/menu-edit/"+menuId,
        success: function(response){
            if (response.status == 200) {
                $('#edit_menuname').val(response.menu.name);
                $('#edit_menulink').val(response.menu.href);
                $('#id').val(menuId);
            }
        }
    });
});

//UPDATE Menu
$(document).on('submit', '#UPDATEMenuFORM', function (e)
{
    e.preventDefault();

    var id = $('#id').val(); 

    let EditFormData = new FormData($('#UPDATEMenuFORM')[0]);

    EditFormData.append('_method', 'PUT');

    $.ajax({
        type: "POST",
        url: "/menu-edit/"+id,
        data: EditFormData,
        contentType: false,
        processData: false,
        success: function(response){
            if(response.status == 200){
                $('#EDITMenuMODAL').modal('hide');
                alert(response.message);
                fetchMenu();
            }
        }
    });
});

//DELETE CATEGORY
$(document).ready( function () {
    $('#menu_table').on('click', '.delete_btn', function(){

        var menuId = $(this).data("value");

        $('#id').val(menuId);

        $('#DELETEMenuFORM').attr('action', '/menu-delete/'+menuId);

        $('#DELETEMenuMODAL').modal('show');

    });
});
