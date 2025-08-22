fetchIncomeStatement();
function fetchIncomeStatement(){

	// var subscriberId = $('#subscriberid').val();

	$.ajax({
		type: "GET",
		url: "/income-statement-data",
		dataType:"json",
		success: function(response){
			// alert(response.message)
			// console.log(response.data);

			var lossProfit = parseFloat(response.totalRevenue) - parseFloat(response.totalExpense)
			var totalRevenue = parseFloat(response.totalRevenue)
			var totalExpense = parseFloat(response.totalExpense)


			$('#sales_thead').append('\
			<tr>\
				<th width="20%">Sales Revenue</th>\
				<th colspan="2"></th>\
				<th width="20%">'+totalRevenue.toFixed(2)+'</th>\
    		</tr>');

			$.each(response.data, function(key, item) {

				if(item.totalCredit == 0){
					var amount = parseFloat(item.totalDebit)
				}else{
					var amount = parseFloat(item.totalCredit)
				}

				$('#sales_tbody').append('\
				<tr>\
					<td width="25%"></td>\
					<td width="25%">'+item.head_name+'</td>\
					<td width="25%">'+amount.toFixed(2)+'</td>\
					<td width="25%"></td>\
        		</tr>');
			})	

			$('#sales_tfoot').append('\
			<tr>\
				<td colspan="2"></td>\
				<th>'+totalRevenue.toFixed(2)+'</th>\
				<th></th>\
    		</tr>');

    		$('#expense_thead').append('\
			<tr>\
				<th width="20%">Expense</th>\
				<th colspan="2"></th>\
				<th width="20%">'+totalExpense.toFixed(2)+'</th>\
    		</tr>');

			$.each(response.data1, function(key, item) {

				if(item.totalCredit == 0){
					var amount = parseFloat(item.totalDebit)
				}else{
					var amount = parseFloat(item.totalCredit)
				}

				$('#expense_tbody').append('\
				<tr>\
					<td width="25%"></td>\
					<td width="25%">'+item.head_name+'</td>\
					<td width="25%">'+amount.toFixed(2)+'</td>\
					<td width="25%"></td>\
        		</tr>');
			})	

			$('#expense_tfoot').append('\
			<tr>\
				<td colspan="2"></td>\
				<th>'+totalExpense.toFixed(2)+'</th>\
				<th></th>\
    		</tr>');

    		$('#total_tbody').append('\
			<tr>\
				<td width="" colspan="3"></td>\
				<th width="25%">'+lossProfit.toFixed(2)+'</th>\
    		</tr>');
		}
	});
}

function print(){
	$.print("#income_statement");
}
