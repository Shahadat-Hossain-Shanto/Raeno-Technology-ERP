fetchOrderProduct()
function fetchOrderProduct(){

	var orderId = $('#orderid').val();

	$.ajax({
		type: "GET",
		url: "/order-product-list-data/"+orderId,
		dataType:"json",
		success: function(response){
            // console.log(response);
			$('tbody').html("");
			$.each(response.productList, function(key, item) {

				var totalPrice = parseFloat(item.totalPrice)
				var totalDiscount = parseFloat(item.totalDiscount)
                var specialDiscount = parseFloat(item.specialDiscount)
				var totalTax = parseFloat(item.totalTax)
				var grandTotal = parseFloat(item.grandTotal)

                function product_name(){
                    if(item.productType=='Serialize'){
                        return '<span class="badge badge-primary"><a style="color: white" href="order-product-serial/' + orderId + '/'+item.productId+ '/'+item.variant_id+'">' + item.productName + '</a></span>';
                    }
                    else{
                        return item.productName;
                    }
                }
				$('tbody').append('<tr>\
					<td></td>\
					<td>'+product_name()+'</td>\
					<td>'+item.quantity+'</td>\
					<td>'+totalPrice.toFixed(2)+'</td>\
					<td>'+totalDiscount.toFixed(2)+'</td>\
					<td>'+totalTax.toFixed(2)+'</td>\
					<td>'+grandTotal.toFixed(2)+'</td>\
                    </tr>');
                })
                // <td>'+specialDiscount.toFixed(2)+'</td>\
		}
	});
}


//DATA TABLE
$(document).ready( function () {
	$('#products_table').DataTable({
	    pageLength : 5,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
		    //debugger;
		    var index = iDisplayIndexFull + 1;
		    $("td:first", nRow).html(index);
		    return nRow;
	  	},
	});
});
