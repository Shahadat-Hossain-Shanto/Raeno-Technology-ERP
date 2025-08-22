fetchStoreDefault()
function fetchStoreDefault(){
	// var storeId = $(this).val()
	// alert(storeId)
	$.ajax({
		type: "GET",
		url: "/expired-stock-data",
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
						<td><span class="badge badge-danger">'+item.expiry_date+'</span></td>\
						<td><span class="badge badge-danger">'+item.onHand+'</span></td>\
						<td>'+item.store_name+'</td>\
	        		</tr>');
				}


				
			})
		}
	})
}


$('#store').on('change', function() {
	var storeId = $(this).val()
	var storeName = $("#store").find("option:selected").text()

	// alert(storeId)
	// fetchStoreStock(storeId)

	$.ajax({
		type: "GET",
		url: "/expired-stock-report-data/"+storeId,
		dataType:"json",
		success: function(response){
			// console.log(response.data)
			// alert(response.message)

			// $('#storename').text(storeName)

			$('#store_stock_report_table').DataTable().clear().destroy()
			dataTableX()

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
						<td><span class="badge badge-danger">'+item.expiry_date+'</span></td>\
						<td><span class="badge badge-danger">'+item.onHand+'</span></td>\
						<td>'+item.store_name+'</td>\
	        		</tr>');
				}
			})
		}	
	})
	
})

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