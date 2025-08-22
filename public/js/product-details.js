$(document).ready(function () {
    load();
})

function load(){
	var productId = $('#productid').val();

	$.ajax({
		type: "GET",
		url: "/product-details-data/"+productId,
		success: function(response){

			if(response.data.sku == null){
				sku = "N/A"
			}else{
				sku = response.data.sku
			}

			if(response.data.subcategory_name == null){
				subcategory_name = "N/A"
			}else{
				subcategory_name = response.data.subcategory_name
			}

			// if(response.data.supplier == null){
			// 	supplier = "N/A"
			// }else{
			// 	supplier = response.data.supplier
			// }
            if (response.data.variant_measurement == null){
				size = "N/A"
			}else{
                size = response.data.variant_measurement
			}

			$('#productname').text(response.data.productName)
			$('#brandname').text(response.data.brand)
			$('#category').text(response.data.category_name)
			$('#sku').text(sku)
			$('#subcategory').text(subcategory_name)
			// $('#supplier').text(supplier)

            loadTable(response.variants);
		}
	});
}
function loadTable(JSON){
    $.fn.dataTable.ext.errMode = 'none';
    $('#variant_table').DataTable().clear().destroy()
    var t = $('#variant_table').DataTable({
        data:JSON,
        columns: [
            { data: null },
            { data: 'variant_name' },
            { data: 'variant_measurement' },
            { data: 'variant_description' },
            // { data: 'available_discount' },
            // { data: 'discount_type' },
            // { data: 'discount' },
            // { data: 'taxName' },
            // { data: 'isExcludedTax' },
            // { data: 'tax' },
            {
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
        responsive:true,
	    order: [[1, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#variant_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}

function getBtns(data, type, row, meta) {
    return '<button onclick="edit_btn('+row.id+')" class="edit_btn btn btn-primary" data-toggle="modal" data-target="#editModal"><i class="fas fa-info-circle"></i></button>\
            <button onclick="delete_btn('+row.id+')" class="delete_btn btn btn-danger" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-minus-circle"></i></button>'
}

$('#add_btn').on('click', function () {
    $('#addVariantModal').modal('show');
});

function addVariant(){
    let id = $('#productid').val();
    let variant={}
    variant['variant_name']=$('#name').val();
    variant['variant_measurement']=$('#measurement').val();

    if($('#description').val()=='')
    {
        variant['variant_description']='N/A';
    }
    else{
        variant['variant_description']=$('#description').val();
    }

    // if($('#availablediscount').val()=='false'||$('#availablediscount').val()=='')
    // {
    //     variant['available_discount']='N/A';
    // }
    // else{
    //     variant['available_discount']=$('#availablediscount').val();
    // }

    // if($('#discounttype').val()=='')
    // {
    //     variant['discount_type']='N/A';
    // }
    // else{
    //     variant['discount_type']=$('#discounttype').val();
    // }

    // if($('#discount').val()=='')
    // {
    //     variant['discount']=0;
    // }
    // else{
    //     variant['discount']=$('#discount').val();
    // }

    // if($('#taxname').val()=='null'||$('#taxname').val()=='')
    // {
    //     variant['taxName']='N/A';
    // }
    // else{
    //     variant['taxName']=$('#taxname').val();
    // }

    // if($('#tax').val()=='')
    // {
    //     variant['tax']=0;
    // }
    // else{
    //     variant['tax']=$('#tax').val();
    // }

    // if($('#taxexcluded').val()=='')
    // {
    //     variant['isExcludedTax']='N/A';
    // }
    // else{
    //     variant['isExcludedTax']=$('#taxexcluded').val();
    // }

    $.ajax({
        type: "POST",
        url: "/product-variant/"+id,
        data: JSON.stringify(variant),
        dataType: "json",
        contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        success: function (response) {
            if(response.status==200){
                $.notify(response.message, {className: 'success', position: 'bottom right'});
                $('#addVariantModal').modal('hide');
                load();
            }
            else{
                $.notify(response.message, {className: 'error', position: 'bottom right'});
            }
        }
    });
}

function edit_btn(id){
    $("#variantIdEdit").val(id);
    let productId = $('#productid').val();
    let variantId = id;

    $.ajax({
        type: "get",
        url: "/product-variant/"+productId+"/"+variantId,
        success: function (response) {
            $('#variantIdEdit').val(response.variant.id);
            $('#edit_name').val(response.variant.variant_name);
            $('#edit_measurement').val(response.variant.variant_measurement).change();
            $('#edit_description').val(response.variant.variant_description);

            // $('#edit_availablediscount').val(response.variant.available_discount).change();
            // $('#edit_discounttype').val(response.variant.discount_type).change();
            // $('#edit_discount').val(response.variant.discount);
            // $('#edit_taxname').val(response.variant.taxName).change();
            // $('#edit_tax').val(response.variant.tax);
            // $('#edit_taxexcluded').val(response.variant.isExcludedTax).change();

            // if(response.variant.available_discount=='true'){
            //     $('#edit_discounttype').prop('disabled', false);
            //     $('#edit_discounttype').selectpicker('refresh');
            //     $('#edit_discount').prop('disabled', false);
            // }
            $('#editVariantModal').modal('show');
        }
    });
}

function editVariant(){
    let productId = $('#productid').val();
    let variantId = $("#variantIdEdit").val();

    let variant={}
    variant['variant_name']=$('#edit_name').val();
    variant['variant_measurement']=$('#edit_measurement').val();
    if($('#edit_description').val()=='')
    {
        variant['variant_description']='N/A';
    }
    else{
        variant['variant_description']=$('#edit_description').val();
    }

    // if($('#edit_availablediscount').val()=='false'||$('#edit_availablediscount').val()=='')
    // {
    //     variant['available_discount']='N/A';
    // }
    // else{
    //     variant['available_discount']=$('#edit_availablediscount').val();
    // }

    // if($('#edit_discounttype').val()=='')
    // {
    //     variant['discount_type']='N/A';
    // }
    // else{
    //     variant['discount_type']=$('#edit_discounttype').val();
    // }

    // if($('#edit_discount').val()=='')
    // {
    //     variant['discount']=0;
    // }
    // else{
    //     variant['discount']=$('#edit_discount').val();
    // }

    // if($('#edit_taxname').val()=='null'||$('#edit_taxname').val()=='')
    // {
    //     variant['taxName']='N/A';
    // }
    // else{
    //     variant['taxName']=$('#edit_taxname').val();
    // }

    // if($('#edit_tax').val()=='')
    // {
    //     variant['tax']=0;
    // }
    // else{
    //     variant['tax']=$('#edit_tax').val();
    // }

    // if($('#edit_taxexcluded').val()=='')
    // {
    //     variant['isExcludedTax']='N/A';
    // }
    // else{
    //     variant['isExcludedTax']=$('#edit_taxexcluded').val();
    // }

    $.ajax({
        type: "POST",
        url: "/product-variant-update/"+productId+"/"+variantId,
        data: JSON.stringify(variant),
        dataType: "json",
        contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        success: function (response) {
            if(response.status==200){
                $.notify(response.message, {className: 'success', position: 'bottom right'});
                $('#editVariantModal').modal('hide');
                load();
            }
            else{
                $.notify(response.message, {className: 'error', position: 'bottom right'});
            }
        }
    });
}

function delete_btn(id){
    $("#variantIdDelete").val(id);
    $('#deleteVariantModal').modal('show');
}

function deleteVariant(){
    let productId = $('#productid').val();
    let variantId = $("#variantIdDelete").val();
    $.ajax({
        type: "get",
        url: "/product-variant-delete/"+productId+"/"+variantId,
        success: function (response) {
            if(response.status==200){
                $.notify(response.message, {className: 'success', position: 'bottom right'});
                $('#deleteVariantModal').modal('hide');
                load();
            }
            else{
                $.notify(response.message, {className: 'error', position: 'bottom right'});
            }
        }
    });

}

$(document).on('click', '.cancel_btn', function (e) {
    $('#addVariantModal').modal('hide');
    $('#editVariantModal').modal('hide');
    $('#deleteVariantModal').modal('hide');
    // $(".modal-backdrop").remove();
});

// $(document).on('change', '#availablediscount', function(){
//     if($(this).val()=='true'){
//         $('#discounttype').prop('disabled', false);
//         $('#discounttype').selectpicker('refresh');
//     }
//     else{
//         $('#discounttype').val('');
//         $('#discount').val('');
//         $('#discounttype').prop('disabled', true);
//         $('#discounttype').selectpicker('refresh');
//         $('#discount').prop('disabled', true);
//     }
// })

// $('#discounttype').change(function (e) {
//     e.preventDefault();
//     $('#discount').prop('disabled', false);
// });

// $(document).on('change', '#taxname', function (e) {
// 	e.preventDefault();
//     if($(this).val()!='null'){
//         var taxId = $(this).val();

//         $.ajax({
//             type: "GET",
//             url: "/product-create-tax/"+taxId,
//             success: function(response){
//                 $('#tax').val(response.tax[0].taxAmount);

//                 if(response.tax[0].vatType == "included"){
//                     // alert('false')
//                     $("#taxexcluded").val('false').change();
//                 }else{
//                     // alert('true')
//                     $("#taxexcluded").val('true').change();
//                 }
//             }
//         });
//     }
//     else{
//         $('#tax').val('');
//         $("#taxexcluded").val('').change();
//     }
// });

// $(document).on('change', '#edit_availablediscount', function(){
//     if($(this).val()=='true'){
//         $('#edit_discounttype').prop('disabled', false);
//         $('#edit_discounttype').selectpicker('refresh');
//     }
//     else{
//         $('#edit_discounttype').val('');
//         $('#edit_discount').val('');
//         $('#edit_discounttype').prop('disabled', true);
//         $('#edit_discounttype').selectpicker('refresh');
//         $('#edit_discount').prop('disabled', true);
//     }
// })

// $('#edit_discounttype').change(function (e) {
//     e.preventDefault();
//     $('#edit_discount').prop('disabled', false);
// });

// $(document).on('change', '#edit_taxname', function (e) {
// 	e.preventDefault();
//     if($(this).val()!='null'){
//         var taxId = $(this).val();

//         $.ajax({
//             type: "GET",
//             url: "/product-create-tax/"+taxId,
//             success: function(response){
//                 $('#edit_tax').val(response.tax[0].taxAmount);

//                 if(response.tax[0].vatType == "included"){
//                     // alert('false')
//                     $("#edit_taxexcluded").val('false').change();
//                 }else{
//                     // alert('true')
//                     $("#edit_taxexcluded").val('true').change();
//                 }
//             }
//         });
//     }
//     else{
//         $('#edit_tax').val('');
//         $("#edit_taxexcluded").val('').change();
//     }
// });
