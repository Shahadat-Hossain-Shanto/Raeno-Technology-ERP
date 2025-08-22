$(document).on('change', '#roothead', function (e) {
	e.preventDefault();
	var rootHeadCode = $(this).val();
	// alert(rootHeadCode)
	$.ajax({
		type: "GET",
		url: "/account-parent-head/"+rootHeadCode,
		dataType:"json",
		success: function(response){
			console.log(response.data)
			$('select[name="parenthead"]').empty();
		    $('select[name="parenthead"]').append('<option value="" selected disabled>Select Parent Head</option>');
		    $.each(response.data, function(key, item){ 
		         $('select[name="parenthead"]').append('<option value="'+ item.head_code +'">'+ item.head_name +'</option>');
		    });

		    $('#parenthead').appendTo('#parenthead').selectpicker('refresh');
		}
	});

})

function resetButton(){
	
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});

}

$(document).on('submit', '#AddAccountForm', function (e) {
		e.preventDefault();

		let formData = new FormData($('#AddAccountForm')[0]);

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
			url: "/account-create",
			data: formData,
			contentType: false,
			processData: false,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response){
				// alert(response.message);	
				if($.isEmptyObject(response.error)){
					// alert(response.message);	
                    
             		$(location).attr('href','/account-list');

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
            $('#wrongroothead').empty();
            $('#wrongparenthead').empty();
            $('#wrongheadname').empty();

			if(message.roothead == null){
				roothead = ""
			}else{
				roothead = message.roothead[0]
			}
			if(message.parenthead == null){
				parenthead = ""
			}else{
				parenthead = message.parenthead[0]
			}
			if(message.headname == null){
				headname = ""
			}else{
				headname = message.headname[0]
			}

            $('#wrongroothead').append('<span id="">'+roothead+'</span>');
            $('#wrongparenthead').append('<span id="">'+parenthead+'</span>');
            $('#wrongheadname').append('<span id="">'+headname+'</span>');
        // });
    }

$(document).ready(function () {
    var t = $('#account_table').DataTable({
        ajax: {
            "url": "/account-list-data",
            "dataSrc": "data",
        },
        columns: [
          	{data: null},
            { data: 'head_name' },
            { data: 'head_code' },
            { "render": function ( data, type, row, meta ){ var balance = parseFloat(row.balance); return balance.toFixed(2);} },
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
	    	var PageInfo = $('#account_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    } );

    }).draw();


});
