$(document).ready(function () {
    defaultData();
});

function defaultData(){
    $.ajax({
        type: "get",
        url: "/profit-calculation-reports-data/"+0,
        success: function (response) {
            // console.log(response);
            load(response);
        }
    });
}

function load(response){
    if(response.status==200)
    {
        document.getElementById("period").setAttribute('hidden','hidden')
        // Selling Cost Value
        let sellingCostAmount=0
        if(response.sellingCostAmount!=null){
            sellingCostAmount=response.sellingCostAmount;
        }
        $('#sellingCostAmount').text(parseFloat(sellingCostAmount).toFixed(2));
        let returnCostAmount=0
        if(response.returnCostAmount!=null){
            returnCostAmount=response.returnCostAmount;
        }
        $('#returnCostAmount').text(parseFloat(returnCostAmount).toFixed(2));
        let totalSellingCostAmount=parseFloat(sellingCostAmount)-parseFloat(returnCostAmount)
        $('#totalSellingCostAmount').text(parseFloat(totalSellingCostAmount).toFixed(2));

        // Stock Transaction
        let purchaseAmount=0
        if(response.purchaseAmount!=null){
            purchaseAmount=response.purchaseAmount;
        }
        $('#purchaseAmount').text(parseFloat(purchaseAmount).toFixed(2));
        let productInAmount=0
        if(response.productInAmount!=null){
            productInAmount=response.productInAmount;
        }
        $('#productInStockAmount').text(parseFloat(productInAmount).toFixed(2));
        let productTransferIntoAmount=0
        if(response.productTransferIntoAmount!=null){
            productTransferIntoAmount=response.productTransferIntoAmount;
        }
        $('#productTransferIntoStockAmount').text(parseFloat(productTransferIntoAmount).toFixed(2));
        let totalStockAmount=parseFloat(productTransferIntoAmount)+parseFloat(productInAmount)+parseFloat(purchaseAmount);
        $('#totalStockAmount').text(parseFloat(totalStockAmount).toFixed(2));

        $('#ttotalStockAmount').text(parseFloat(totalStockAmount).toFixed(2));
        $('#transactionAmount').text(parseFloat(totalSellingCostAmount).toFixed(2));
        $('#productTransferFromAmount').text(parseFloat(response.productTransferFromAmount).toFixed(2));
        let returnAmount=0
        if(response.returnAmount!=null){
            returnAmount=response.returnAmount;
        }
        $('#returnAmount').text(parseFloat(returnAmount).toFixed(2));
        let closingStockAmount= parseFloat(parseFloat(totalStockAmount)-parseFloat(totalSellingCostAmount)-parseFloat(returnAmount)).toFixed(2);
        $('#closingStockAmount').text(parseFloat(closingStockAmount).toFixed(2));

        // Net Sell value
        let totalSellAmount=0
        if(response.totalSellAmount!=null){
            totalSellAmount=response.totalSellAmount;
        }
        $('#totalSellAmount').text(parseFloat(totalSellAmount).toFixed(2));
        let totalVatAmount=0
        if(response.totalVatAmount!=null){
            totalVatAmount=response.totalVatAmount;
        }
        $('#totalVatAmount').text(parseFloat(totalVatAmount).toFixed(2));
        let totalDiscountAmount=0
        if(response.totalDiscountAmount!=null){
            totalDiscountAmount=response.totalDiscountAmount;
        }
        $('#totalDiscountAmount').text(parseFloat(totalDiscountAmount).toFixed(2));
        let totalSpecialDiscountAmount=0
        if(response.totalSpecialDiscountAmount!=null){
            totalSpecialDiscountAmount=response.totalSpecialDiscountAmount;
        }
        $('#totalSpecialDiscountAmount').text(parseFloat(totalSpecialDiscountAmount).toFixed(2));
        $('#totalReturnAmount').text(parseFloat(returnCostAmount).toFixed(2));
        let netSellAmount=parseFloat(totalSellAmount)+parseFloat(totalVatAmount)-parseFloat(totalDiscountAmount)-parseFloat(totalSpecialDiscountAmount)-parseFloat(returnCostAmount)
        $('#netSellAmount').text(parseFloat(netSellAmount).toFixed(2));

        // Profit
        $('#nnetSellAmount').text(parseFloat(netSellAmount).toFixed(2));
        $('#totalSellCostAmount').text(parseFloat(totalSellingCostAmount).toFixed(2));
        let netProfit=netSellAmount-totalSellingCostAmount
        $('#netProfit').text(parseFloat(netProfit).toFixed(2));
    }
}

function dateWiseLoad(response){
    if(response.status==200){
        document.getElementById("period").removeAttribute('hidden')
        let startdate=$('#startdate').val();
        const startDate = new Date(startdate);
        //extract the parts of the date
        const startDay = startDate.getDate();
        const startMonth = startDate.getMonth() + 1;
        const startYear = startDate.getFullYear();
        $('#fromDate').text(startDay+'/'+startMonth+'/'+startYear);

        let enddate=$('#enddate').val();
        const endDate = new Date(enddate);
        //extract the parts of the date
        const endDay = endDate.getDate();
        const endMonth = endDate.getMonth() + 1;
        const endYear = endDate.getFullYear();
        $('#toDate').text(endDay+'/'+endMonth+'/'+endYear);

        // Selling Cost Value
        let sellingCostAmount=0
        if(response.sellingCostAmount!=null){
            sellingCostAmount=response.sellingCostAmount;
        }
        $('#sellingCostAmount').text(parseFloat(sellingCostAmount).toFixed(2));
        let returnCostAmount=0
        if(response.returnCostAmount!=null){
            returnCostAmount=response.returnCostAmount;
        }
        $('#returnCostAmount').text(parseFloat(returnCostAmount).toFixed(2));
        let totalSellingCostAmount=parseFloat(sellingCostAmount)-parseFloat(returnCostAmount)
        $('#totalSellingCostAmount').text(parseFloat(totalSellingCostAmount).toFixed(2));

        // Net Sell value
        let totalSellAmount=0
        if(response.totalSellAmount!=null){
            totalSellAmount=response.totalSellAmount;
        }
        $('#totalSellAmount').text(parseFloat(totalSellAmount).toFixed(2));
        let totalVatAmount=0
        if(response.totalVatAmount!=null){
            totalVatAmount=response.totalVatAmount;
        }
        $('#totalVatAmount').text(parseFloat(totalVatAmount).toFixed(2));
        let totalDiscountAmount=0
        if(response.totalDiscountAmount!=null){
            totalDiscountAmount=response.totalDiscountAmount;
        }
        $('#totalDiscountAmount').text(parseFloat(totalDiscountAmount).toFixed(2));
        let totalSpecialDiscountAmount=0
        if(response.totalSpecialDiscountAmount!=null){
            totalSpecialDiscountAmount=response.totalSpecialDiscountAmount;
        }
        $('#totalSpecialDiscountAmount').text(parseFloat(totalSpecialDiscountAmount).toFixed(2));
        $('#totalReturnAmount').text(parseFloat(returnCostAmount).toFixed(2));
        let netSellAmount=parseFloat(totalSellAmount)+parseFloat(totalVatAmount)-parseFloat(totalDiscountAmount)-parseFloat(totalSpecialDiscountAmount)-parseFloat(returnCostAmount)
        $('#netSellAmount').text(parseFloat(netSellAmount).toFixed(2));

        // Profit
        $('#nnetSellAmount').text(parseFloat(netSellAmount).toFixed(2));
        $('#totalSellCostAmount').text(parseFloat(totalSellingCostAmount).toFixed(2));
        let netProfit=netSellAmount-totalSellingCostAmount
        $('#netProfit').text(parseFloat(netProfit).toFixed(2));
    }
    else{
        $.notify('FillUp Required Fields', {className: 'error', position: 'bottom right'})
    }
}
$('#ProfitCalculationReportForm').on('submit',  function (e) {
	e.preventDefault();
	let formData = new FormData($('#ProfitCalculationReportForm')[0]);
    // console.log(formData);
    $.ajax({
        type: "POST",
        url: "/profit-calculation-reports-data",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response){
            // console.log(response);
            dateWiseLoad(response);
        }
    })
})

function printButton(){
    $.print('#tablePart')
}

function resetButton(){
    defaultData();
    $('#currentStore').text("All Store");
    $('#store').prop('selectedIndex',0);
	$('#form_div').find('form')[0].reset();
    $('.selectpicker').selectpicker('refresh');
}

$('#store').on('change', function() {
	var storeId = $(this).val()
	var storeName = $("#store").find("option:selected").text()
    $('#currentStore').text(storeName);
    $('#startdate').val('');
    $('#enddate').val('');
    // alert(storeId)
	$.ajax({
        type: "get",
        url: "/profit-calculation-reports-data/"+storeId,
        success: function (response) {
            // console.log(response);
            load(response);
        }
    });
})
