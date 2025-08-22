fetchIncomeStatement();
function fetchIncomeStatement(){

	// var subscriberId = $('#subscriberid').val();

	$.ajax({
		type: "GET",
		url: "/balance-sheet-data",
		dataType:"json",
		success: function(response){
			// alert(response.message)
			console.log(response.dataX);
			console.log(response.dataY);


			// var accountReceivable = parseFloat(response.totalBalanceAccountReceivable) 
			// var cashandCashEquivalent = parseFloat(response.totalBalanceCashandCashEquivalent)
			// var inventory = parseFloat(response.totalBalanceInventory)
			// var totalAsset = parseFloat(response.totalAsset)

			$('#asset_thead').append('\
			<tr>\
				<th width="20%">Asset</th>\
				<th colspan="2" class="text-right">Total</th>\
				<th width="20%" class="text-right">'+response.totalAsset.toFixed(2)+'</th>\
    		</tr>');

			$.each(response.dataX, function(key, item) {

				// if(item.totalBalanceAccountReceivable != 0){
				// 	var head_name = 'Account Receivable'
				// }

				$('#asset_tbody').append('\
				<tr>\
					<td width="25%"></td>\
					<td width="25%">'+item.head_name+'</td>\
					<td width="25%">'+item.amount.toFixed(2)+'</td>\
					<td width="25%"></td>\
        		</tr>');
			})	
			$('#asset_tfoot').append('\
			<tr>\
				<td colspan="2"></td>\
				<th>'+response.totalAsset.toFixed(2)+'</th>\
				<th></th>\
    		</tr>');


			//---------------------------------------------------------------------------------------

    		$('#liabilities_thead').append('\
			<tr>\
				<th width="20%">Liabilities</th>\
				<th colspan="2" class="text-right">Total</th>\
				<th width="20%">'+response.totalLiability.toFixed(2)+'</th>\
    		</tr>');

			$.each(response.dataY, function(key, item) {

				// if(item.totalBalanceAccountReceivable != 0){
				// 	var head_name = 'Account Receivable'
				// }

				$('#liabilities_tbody').append('\
				<tr>\
					<td width="25%"></td>\
					<td width="25%">'+item.head_name+'</td>\
					<td width="25%">'+item.amount.toFixed(2)+'</td>\
					<td width="25%"></td>\
        		</tr>');
			})	

			$('#liabilities_tfoot').append('\
			<tr>\
				<td colspan="2"></td>\
				<th>'+response.totalLiability.toFixed(2)+'</th>\
				<th></th>\
    		</tr>');

    		//---------------------------------------------------------------------------------------

    		$('#equity_thead').append('\
			<tr>\
				<th width="20%">Owners Equity</th>\
				<th colspan="2" class="text-right">Total</th>\
				<th width="20%">'+response.totalEquity.toFixed(2)+'</th>\
    		</tr>');

			$.each(response.dataZ, function(key, item) {

				// if(item.totalBalanceAccountReceivable != 0){
				// 	var head_name = 'Account Receivable'
				// }

				$('#equity_tbody').append('\
				<tr>\
					<td width="25%"></td>\
					<td width="25%">'+item.head_name+'</td>\
					<td width="25%">'+item.amount.toFixed(2)+'</td>\
					<td width="25%"></td>\
        		</tr>');
			})	

			$('#equity_tfoot').append('\
			<tr>\
				<td colspan="2"></td>\
				<th>'+response.totalEquity.toFixed(2)+'</th>\
				<th></th>\
    		</tr>');

    		$('#total_owners_liabilities_tbody').append('\
			<tr>\
				<th width="" colspan="3" class="text-right"></th>\
				<th width="25%" class="">'+response.totalEquityLiability.toFixed(2)+'</th>\
    		</tr>');

    		
		}
	});
}

function print(){
	$.print("#balance_sheet");
}
