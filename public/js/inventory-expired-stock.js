fetchStoreDefault()
function fetchStoreDefault(){
	// var storeId = $(this).val()
	// alert(storeId)
	$.ajax({
		type: "GET",
		url: "/inventory-expired-stock-data",
		dataType:"json",
		success: function(response){
			// console.log(response.data)
			// alert(response.message)
			// $('#storename').text(response.storename)
			// $('#storeaddress').text(response.store_address)



				const formatYmd = date => date.toISOString().slice(0, 10);

				
				var today = formatYmd(new Date());      // 2022-05-16

			$.each(response.data, function(key, item) {

				
				var expiryDate = item.expiry_date

				if(expiryDate <= today){

					$('tbody').append('\
					<tr>\
						<td></td>\
						<td>'+item.productName+'</td>\
						<td>'+item.batch_number+'</td>\
						<td>'+item.expiry_date+'</td>\
						<td>'+item.onHand+'</td>\
	        		</tr>');
				}


				
			})
		}
	})
}

dataTableX()
function dataTableX(){
	//DATA TABLE
	$(document).ready( function () {
		$('#store_stock_report_table').DataTable({
		    pageLength : 15,
		    lengthMenu: [[5, 10, 15, 20, -1], [5, 10, 15, 20, 'Todos']],
		    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			    //debugger;
			    var index = iDisplayIndexFull + 1;
			    $("td:first", nRow).html(index);
			    return nRow;
		  	},
		  	dom: 'Bfrtip',
	        buttons: [
	            'copy', 'csv', 'excel', 'pdf', 'print'
	        ]
		});
	});
}