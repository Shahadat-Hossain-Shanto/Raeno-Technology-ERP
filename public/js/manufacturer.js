$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE BRAND
	$(document).on('submit', '#AddManufacturerForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddManufacturerForm')[0]);

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
			url: "/manufacturer-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				// console.log(response.message);

				if($.isEmptyObject(response.error)){

             		$(location).attr('href','/manufacturer-list');

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
            $('#wrongmanufacturername').empty();
            $('#wrongmanufacturerorigin').empty();




			if(message.manufacturername == null){
				manufacturername = ""
			}else{
				manufacturername = message.manufacturername[0]
			}

			if(message.manufacturerorigin == null){
				manufacturerorigin = ""
			}else{
				manufacturerorigin = message.manufacturerorigin[0]
			}

            $('#wrongmanufacturername').append('<span id="">'+manufacturername+'</span>');
            $('#wrongmanufacturerorigin').append('<span id="">'+manufacturerorigin+'</span>');

        // });
    }

});


	//BRAND LIST

	// fetchBrand();
	// function fetchBrand(){

	// 	// var subscriberId = $('#subscriberid').val();

	// 	$.ajax({
	// 		type: "GET",
	// 		url: "/brand-list-data",
	// 		dataType:"json",
	// 		success: function(response){
	// 			console.log(response);
	// 			$('tbody').html("");
	// 			$.each(response.brand, function(key, item) {

	// 				if(item.brand_origin == null){
	// 					brand_origin = 'N/A'
	// 				}else{
	// 					brand_origin = item.brand_origin;
	// 				}

	// 				if(item.brand_logo == null){
	// 					brand_logo = 'default.jpg'
	// 				}else{
	// 					brand_logo = item.brand_logo;
	// 				}

	// 				$('tbody').append('\
	// 				<tr>\
	// 					<td></td>\
	// 					<td>'+item.brand_name+'</td>\
	// 					<td>'+brand_origin+'</td>\
	// 					<td><img src="uploads/brands/'+brand_logo+'" width="50px" height="50px" alt="image" class="rounded-circle"></td>\
	// 					<td>\
	//         				<button type="button" value="'+item.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i>\
	//                     	</button>\
	//                     	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+item.id+'"><i class="fas fa-trash"></i></a>\
	//         			</td>\
	//         		</tr>');
	// 			})
	// 		}
	// 	});
	// }



	//EDIT BRAND
	$(document).on('click', '.edit_btn', function (e) {
		e.preventDefault();

		var manufacturerId = $(this).val();
		// alert(manufacturerId);
		$('#EDITManufacturerMODAL').modal('show');

			$.ajax({
			type: "GET",
			url: "/manufacturer-edit/"+manufacturerId,
			success: function(response){
				if (response.status == 200) {
					$('#edit_manufacturername').val(response.manufacturer.manufacturer_name);

					// if(response.manufacturer.manufacturer_logo == null){
					// 	manufacturer_logo = 'default.jpg'
					// }else{
					// 	manufacturer_logo = response.manufacturer.manufacturer_logo
					// }

					// $('#edit_manufacturerimage').attr("src", "../uploads/manufacturers/"+manufacturer_logo);
					// $('#edit_manufacturerlogo').val(response.manufacturer.manufacturer_logo);
					$('#edit_manufacturerorigin').val(response.manufacturer.manufacturer_origin).change();
					$('#manufacturerid').val(manufacturerId);
				}
			}
		});
	});

	//UPDATE manufacturer
	$(document).on('submit', '#UPDATEManufacturerFORM', function (e)
	{
		e.preventDefault();

		var id = $('#manufacturerid').val();

		let EditFormData = new FormData($('#UPDATEManufacturerFORM')[0]);

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
			url: "/manufacturer-edit/"+id,
			data: EditFormData,
			contentType: false,
			processData: false,
			success: function(response){

				if($.isEmptyObject(response.error)){
                    // alert(response.message);
                    $('#EDITManufacturerMODAL').modal('hide');
                    // $.notify(response.message, 'success')
                    $(location).attr('href','/manufacturer-list');
                }else{
                	// console.log(response.error)
                    // printErrorMsg(response.error);
                    $('body').loadingModal('destroy');
                    $('#edit_wrongmanufacturername').empty();
					$('#edit_wrongmanufacturerorigin').empty();



					if(response.error.manufacturername == null){
						manufacturername = ""
					}else{
						manufacturername = response.error.manufacturername[0]
					}
					if(response.error.manufacturerorigin == null){
						manufacturerorigin = ""
					}else{
						manufacturerorigin = response.error.manufacturerorigin[0]
					}


	                $('#edit_wrongmanufacturername').append('<span id="">'+manufacturername+'</span>');
	                $('#edit_wrongmanufacturerorigin').append('<span id="">'+manufacturerorigin+'</span>');

                }


			}
		});
	});

	//Delete manufacturer
	$(document).ready( function () {
		$('#manufacturer_table').on('click', '.delete_btn', function(){

			var manufacturerId = $(this).data("value");

			$('#manufacturerid').val(manufacturerId);
			$('#DELETEManufacturerFORM').attr('action', '/manufacturer-delete/'+manufacturerId);
			$('#DELETEManufacturerMODAL').modal('show');

		});
	});

function resetButton(){
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}


// DATA TABLE
$(document).ready(function () {
    var t = $('#manufacturer_table').DataTable({
    	"processing": true,
        "serverSide": true,
        ajax: {
        	headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
            "url": "/manufacturer-list-data",
            "dataSrc": "data",
            "dataType": "json",
         	"type": "POST",
        },
        columns: [
          	{data: null},

            { data: 'manufacturer_name' },

            {
            	data: 'manufacturer_origin',
            	render: checkOrigin
            },

            // {
            //     data: 'manufacturer_logo',
            //     render: getImg
            // },

            {
                data: 'id',
                render: getBtns
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
	    	var PageInfo = $('#manufacturer_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();


});

function checkOrigin(data, type, full, meta) {

    var origin = data;

    if (origin === null) {
       origin = "N/A"
    } else {
        origin = origin
    }

     return origin;
}

// function getImg(data, type, full, meta) {

//     var manufacturer_logo = data;

//     if (manufacturer_logo === null) {
//        manufacturer_logo = "default.jpg"
//     } else {
//         manufacturer_logo = manufacturer_logo
//     }

//      return '<img src="uploads/manufacturers/'+manufacturer_logo+'" width="50px" height="50px" alt="image" class="rounded-circle">';
// }

function getBtns(data, type, full, meta) {

    var id = data;
    return '<button type="button" value="'+id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button>\
            <a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+id+'"><i class="fas fa-trash"></i></a>';
}
