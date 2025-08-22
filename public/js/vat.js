$(document).ready(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	//CREATE VAT
	$(document).on('submit', '#AddVatForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddVatForm')[0]);

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
			url: "/vat-create",
			data: formData,
			contentType: false,
			processData: false,
			success: function(response){
				//console.log(response.message);	
				// $(location).attr('href','/vat-list');
				if($.isEmptyObject(response.error)){
					$(location).attr('href','/vat-list');
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
                $('#wrongvatname').empty();
                $('#wrongvatrate').empty();
				$('#wrongvattype').empty();
				$('#wrongvatoption').empty();
				

				if(message.vatname == null){
					vatname = ""
				}else{
					vatname = message.vatname[0]
				}
				if(message.vatrate == null){
					vatrate = ""
				}else{
					vatrate = message.vatrate[0]
				}
				
				if(message.vattype == null){
					vattype = ""
				}else{
					vattype = message.vattype[0]
				}
				if(message.vatoption == null){
					vatoption = ""
				}else{
					vatoption = message.vatoption[0]
				}

                $('#wrongvatname').append('<span id="">'+vatname+'</span>');
                $('#wrongvatrate').append('<span id="">'+vatrate+'</span>');
                $('#wrongvattype').append('<span id="">'+vattype+'</span>');
                $('#wrongvatoption').append('<span id="">'+vatoption+'</span>');
            // });
        }

});

//VAT LIST
$(document).ready(function () {
    var t = $('#vat_table').DataTable({
        ajax: {
            "url": "/vat-list-data",
            "dataSrc": "vat",
        },
        columns: [
          	{ data: null },
            { data: 'taxName' },
            { data: 'taxAmount' },
            { "render": function ( data, type, row, meta ){ 
            		if(row.vatType == "included"){
						vatType = "Included"
					}else{
						vatType = "Will be added"
					}
            		return vatType
	            } 
	        },
	        { "render": function ( data, type, row, meta ){ 
            		if(row.vatOption == "new items"){
						vatOption = "New items"
					}else if(row.vatOption == "existing items"){
						vatOption = "Existing items"
					}else{
						vatOption = "New and existing items"
					}
            		return vatOption
	            } 
	        },
            { "render": function ( data, type, row, meta ){ 
            		
            		return '<button type="button" value="'+row.id+'" class="edit_btn btn btn-secondary btn-sm"><i class="fas fa-edit"></i></button>\
                    	<a href="javascript:void(0)" class="delete_btn btn btn-outline-danger btn-sm" data-value="'+row.id+'"><i class="fas fa-trash"></i></a>'
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
	    	var PageInfo = $('#vat_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();

});


//EDIT VAT
	$(document).on('click', '.edit_btn', function (e) {
		e.preventDefault();

		var vatId = $(this).val();
		// alert(vatId);
		$('#EDITVatMODAL').modal('show');
			
			$.ajax({
			type: "GET",
			url: "/vat-edit/"+vatId,
			success: function(response){
				if (response.status == 200) {
					$('#edit_vatname').val(response.vat.taxName);
					$('#edit_vatrate').val(response.vat.taxAmount);
					$('#edit_vattype').val(response.vat.vatType);
					$('#edit_vatoption').val(response.vat.vatOption);
					// $('#edit_store').val(response.vat.store);
					$('#vatid').val(vatId);
				}
			}
		});
	});

	//UPDATE VAT
	$(document).on('submit', '#UPDATEVatFORM', function (e)
	{
		e.preventDefault();

		var id = $('#vatid').val(); 

		let EditFormData = new FormData($('#UPDATEVatFORM')[0]);

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
			url: "/vat-edit/"+id,
			data: EditFormData,
			contentType: false,
			processData: false,
			success: function(response){
				if($.isEmptyObject(response.error)){
                    // alert(response.message);
                    $('#EDITVatMODAL').modal('hide');
                    $.notify(response.message, 'success')
                    $(location).attr('href','/vat-list');
                }else{
                	// console.log(response.error)
                    // printErrorMsg(response.error);
                    $('body').loadingModal('destroy');
                    $('#edit_wrongvatname').empty();
					$('#edit_wrongvatrate').empty();
					$('#edit_wrongvattype').empty();
					$('#edit_wrongvatoption').empty();

					if(response.error.vatname == null){
						vatname = ""
					}else{
						vatname = response.error.vatname[0]
					}
					if(response.error.vatrate == null){
						vatrate = ""
					}else{
						vatrate = response.error.vatrate[0]
					}
					
					if(response.error.vattype == null){
						vattype = ""
					}else{
						vattype = response.error.vattype[0]
					}
					if(response.error.vatoption == null){
						vatoption = ""
					}else{
						vatoption = response.error.vatoption[0]
					}

	                $('#edit_wrongvatname').append('<span id="">'+vatname+'</span>');
	                $('#edit_wrongvatrate').append('<span id="">'+vatrate+'</span>');
	                $('#edit_wrongvattype').append('<span id="">'+vattype+'</span>');
	                $('#edit_wrongvatoption').append('<span id="">'+vatoption+'</span>');
                }
			}
		});
	});

//Delete Vat
$(document).ready( function () {
	$('#vat_table').on('click', '.delete_btn', function(){

		var vatId = $(this).data("value");

		$('#vatid').val(vatId);

		$('#DELETEVatFORM').attr('action', '/vat-delete/'+vatId);

		$('#DELETEVatMODAL').modal('show');

	});
});

function resetButton(){
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}