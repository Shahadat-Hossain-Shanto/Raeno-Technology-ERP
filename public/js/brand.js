$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE BRAND
	$(document).on('submit', '#AddBrandForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddBrandForm')[0]);

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
			url: "/brand-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				// console.log(response.message);

				if($.isEmptyObject(response.error)){

             		$(location).attr('href','/brand-list');

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
            $('#wrongbrandname').empty();
            $('#wrongbrandorigin').empty();




			if(message.brandname == null){
				brandname = ""
			}else{
				brandname = message.brandname[0]
			}

			if(message.brandorigin == null){
				brandorigin = ""
			}else{
				brandorigin = message.brandorigin[0]
			}

            $('#wrongbrandname').append('<span id="">'+brandname+'</span>');
            $('#wrongbrandorigin').append('<span id="">'+brandorigin+'</span>');

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

		var brandId = $(this).val();
		// alert(brandId);
		$('#EDITBrandMODAL').modal('show');

			$.ajax({
			type: "GET",
			url: "/brand-edit/"+brandId,
			success: function(response){
				if (response.status == 200) {
					$('#edit_brandname').val(response.brand.brand_name);

					if(response.brand.brand_logo == null){
						brand_logo = 'default.jpg'
					}else{
						brand_logo = response.brand.brand_logo
					}

					$('#edit_brandimage').attr("src", "../uploads/brands/"+brand_logo);
					// $('#edit_brandlogo').val(response.brand.brand_logo);
					$('#edit_brandorigin').val(response.brand.brand_origin).change();
					$('#brandid').val(brandId);
				}
			}
		});
	});

	//UPDATE BRAND
	$(document).on('submit', '#UPDATEBrandFORM', function (e)
	{
		e.preventDefault();

		var id = $('#brandid').val();

		let EditFormData = new FormData($('#UPDATEBrandFORM')[0]);

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
			url: "/brand-edit/"+id,
			data: EditFormData,
			contentType: false,
			processData: false,
			success: function(response){

				if($.isEmptyObject(response.error)){
                    // alert(response.message);
                    $('#EDITBrandMODAL').modal('hide');
                    // $.notify(response.message, 'success')
                    $(location).attr('href','/brand-list');
                }else{
                	// console.log(response.error)
                    // printErrorMsg(response.error);
                    $('body').loadingModal('destroy');
                    $('#edit_wrongbrandname').empty();
					$('#edit_wrongbrandorigin').empty();



					if(response.error.brandname == null){
						brandname = ""
					}else{
						brandname = response.error.brandname[0]
					}
					if(response.error.brandorigin == null){
						brandorigin = ""
					}else{
						brandorigin = response.error.brandorigin[0]
					}


	                $('#edit_wrongbrandname').append('<span id="">'+brandname+'</span>');
	                $('#edit_wrongbrandorigin').append('<span id="">'+brandorigin+'</span>');

                }


			}
		});
	});

	//Delete BRAND
	$(document).ready( function () {
		$('#brand_table').on('click', '.delete_btn', function(){

			var brandId = $(this).data("value");

			$('#brandid').val(brandId);
			$('#DELETEBrandFORM').attr('action', '/brand-delete/'+brandId);
			$('#DELETEBrandMODAL').modal('show');

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
    var t = $('#brand_table').DataTable({
    	"processing": true,
        "serverSide": true,
        ajax: {
        	headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
            "url": "/brand-list-data",
            "dataSrc": "data",
            "dataType": "json",
         	"type": "POST",
        },
        columns: [
          	{data: null},

            { data: 'brand_name' },

            {
            	data: 'brand_origin',
            	render: checkOrigin
            },

            // {
            //     data: 'brand_logo',
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
	    	var PageInfo = $('#brand_table').DataTable().page.info();
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

//     var brand_logo = data;

//     if (brand_logo === null) {
//        brand_logo = "default.jpg"
//     } else {
//         brand_logo = brand_logo
//     }

//      return '<img src="uploads/brands/'+brand_logo+'" width="50px" height="50px" alt="image" class="rounded-circle">';
// }

function getBtns(data, type, full, meta) {

    var id = data;
    return '<button type="button" value="'+id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button>\
            <a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+id+'"><i class="fas fa-trash"></i></a>';
}

