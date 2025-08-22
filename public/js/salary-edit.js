

$(document).ready(function () {

    var addition_table = $('#addition_table');

    var deduction_table = $('#deduction_table');

    var spcialBenefit_table = $('#special_addition_table_per');

    $(addition_table).find('> tbody > tr').each(function () {
        var percentageAmount = parseFloat($(this).closest("tr").find("td:eq(1) input[type='text']").val())
        var amountType = $(this).closest("tr").find("td:eq(2)").text()


        if ($('#basicpay').val().length == 0) {
            alert('Please enter basic pay first.')
        } else {
            // 
            if (isNaN(percentageAmount)) {
                percentageAmount = 0
                $(this).closest("tr").find("td:eq(5)").text(percentageAmount)
            }
            var basicPay = parseFloat($('#basicpay').val())
            if (percentageAmount.length != 0 && amountType == '%') {
                amount = percentageAmount * (basicPay / 100)
                $(this).closest("tr").find("td:eq(5)").text(amount)

                var totalAddition = 0;
                $('#addition_table').find('> tbody > tr').each(function () {
                    var amount = parseFloat($(this).find("td:eq(5)").text());
                    totalAddition = totalAddition + amount;

                    totalAfterAddition = basicPay + totalAddition;

                });

                // x = x + amount;
                // totalAmount = basicPay + x
                // finalAmount = finalAmount + totalAmount
            } else if (percentageAmount.length != 0 && amountType == 'amount') {
                amount = percentageAmount
                $(this).closest("tr").find("td:eq(5)").text(amount)

                var totalAddition = 0;
                $('#addition_table').find('> tbody > tr').each(function () {
                    var amount = parseFloat($(this).find("td:eq(5)").text());
                    totalAddition = totalAddition + amount;

                    totalAfterAddition = basicPay + totalAddition;

                });
            }
        }

        $('#additionamount').val(totalAddition)

        var totalAdditionX = parseFloat($('#additionamount').val())
        var totalDeductionX = parseFloat($('#deductionamount').val())

        var grossSalary = (basicPay + totalAdditionX) - totalDeductionX

        $('#grsalary').val(grossSalary)

    })
    $(deduction_table).find('> tbody > tr').each(function () {
        var percentageAmount = parseFloat($(this).closest("tr").find("td:eq(1) input[type='text']").val())
        var amountType = $(this).closest("tr").find("td:eq(2)").text()

        if ($('#basicpay').val().length == 0) {
            alert('Please enter basic pay first.')
        } else {
            // 
            if (isNaN(percentageAmount)) {
                percentageAmount = 0
                $(this).closest("tr").find("td:eq(5)").text(percentageAmount)
            }
            var basicPay = parseFloat($('#basicpay').val())
            if (percentageAmount.length != 0 && amountType == '%') {
                amount = percentageAmount * (basicPay / 100)
                $(this).closest("tr").find("td:eq(5)").text(amount)

                var totalDeduction = 0;
                $('#deduction_table').find('> tbody > tr').each(function () {
                    var amount = parseFloat($(this).find("td:eq(5)").text());
                    totalDeduction = totalDeduction + amount;

                    totalAfterDeduction = basicPay - totalDeduction;

                });

                // x = x + amount;
                // totalAmount = basicPay + x
                // finalAmount = finalAmount + totalAmount
            } else if (percentageAmount.length != 0 && amountType == 'amount') {
                amount = percentageAmount
                $(this).closest("tr").find("td:eq(5)").text(amount)

                var totalDeduction = 0;
                $('#deduction_table').find('> tbody > tr').each(function () {
                    var amount = parseFloat($(this).find("td:eq(5)").text());
                    totalDeduction = totalDeduction + amount;

                    totalAfterDeduction = basicPay - totalDeduction;

                });
            }
        }

        $('#deductionamount').val(totalDeduction)


        var totalAdditionX = parseFloat($('#additionamount').val())
        var totalDeductionX = parseFloat($('#deductionamount').val())

        var grossSalary = (basicPay + totalAdditionX) - totalDeductionX

        $('#grsalary').val(grossSalary)
    })
    $(spcialBenefit_table).find('> tbody > tr').each(function () {
        var percentageAmount = parseFloat($(this).closest("tr").find("td:eq(1) input[type='text']").val())
        var amountType = $(this).closest("tr").find("td:eq(2)").text()

        if ($('#basicpay').val().length == 0) {
            alert('Please enter basic pay first.')
        } else {
            // 
            if (isNaN(percentageAmount)) {
                percentageAmount = 0
                $(this).closest("tr").find("td:eq(5)").text(percentageAmount)
            }
            var basicPay = parseFloat($('#basicpay').val())
            if (percentageAmount.length != 0 && amountType == '%') {
                amount = percentageAmount * (basicPay / 100)
                $(this).closest("tr").find("td:eq(5)").text(amount)

                var totalDeduction = 0;
                $('#deduction_table').find('> tbody > tr').each(function () {
                    var amount = parseFloat($(this).find("td:eq(5)").text());
                    totalDeduction = totalDeduction + amount;

                    totalAfterDeduction = basicPay - totalDeduction;

                });

                // x = x + amount;
                // totalAmount = basicPay + x
                // finalAmount = finalAmount + totalAmount
            } else if (percentageAmount.length != 0 && amountType == 'amount') {
                amount = percentageAmount
                $(this).closest("tr").find("td:eq(5)").text(amount)

                var totalDeduction = 0;
                $('#deduction_table').find('> tbody > tr').each(function () {
                    var amount = parseFloat($(this).find("td:eq(5)").text());
                    totalDeduction = totalDeduction + amount;

                    totalAfterDeduction = basicPay - totalDeduction;

                });
            }
        }

        $('#deductionamount').val(totalDeduction)


        var totalAdditionX = parseFloat($('#additionamount').val())
        var totalDeductionX = parseFloat($('#deductionamount').val())

        var grossSalary = (basicPay + totalAdditionX) - totalDeductionX

        $('#grsalary').val(grossSalary)
    })
  
});

$(document).on('keyup', '#benefitamount', function (e) {



    var percentageAmount = parseFloat($(this).closest("tr").find("td:eq(1) input[type='text']").val())
    var amountType = $(this).closest("tr").find("td:eq(2)").text()

    if ($('#basicpay').val().length == 0) {
        alert('Please enter basic pay first.')
    } else {
        // 
        if (isNaN(percentageAmount)) {
            percentageAmount = 0
            $(this).closest("tr").find("td:eq(5)").text(percentageAmount)
        }
        var basicPay = parseFloat($('#basicpay').val())
        if (percentageAmount.length != 0 && amountType == '%') {
            amount = percentageAmount * (basicPay / 100)
            $(this).closest("tr").find("td:eq(5)").text(amount)

            var totalAddition = 0;
            $('#addition_table').find('> tbody > tr').each(function () {
                var amount = parseFloat($(this).find("td:eq(5)").text());
                totalAddition = totalAddition + amount;

                totalAfterAddition = basicPay + totalAddition;

            });

            // x = x + amount;
            // totalAmount = basicPay + x
            // finalAmount = finalAmount + totalAmount
        } else if (percentageAmount.length != 0 && amountType == 'amount') {
            amount = percentageAmount
            $(this).closest("tr").find("td:eq(5)").text(amount)

            var totalAddition = 0;
            $('#addition_table').find('> tbody > tr').each(function () {
                var amount = parseFloat($(this).find("td:eq(5)").text());
                totalAddition = totalAddition + amount;

                totalAfterAddition = basicPay + totalAddition;

            });
        }
    }

    $('#additionamount').val(totalAddition)

    var totalAdditionX = parseFloat($('#additionamount').val())
    var totalDeductionX = parseFloat($('#deductionamount').val())

    var grossSalary = (basicPay + totalAdditionX) - totalDeductionX

    $('#grsalary').val(grossSalary)

});

$(document).on('keyup', '#deductamount', function (e) {



    var percentageAmount = parseFloat($(this).closest("tr").find("td:eq(1) input[type='text']").val())
    var amountType = $(this).closest("tr").find("td:eq(2)").text()

    if ($('#basicpay').val().length == 0) {
        alert('Please enter basic pay first.')
    } else {
        // 
        if (isNaN(percentageAmount)) {
            percentageAmount = 0
            $(this).closest("tr").find("td:eq(5)").text(percentageAmount)
        }
        var basicPay = parseFloat($('#basicpay').val())
        if (percentageAmount.length != 0 && amountType == '%') {
            amount = percentageAmount * (basicPay / 100)
            $(this).closest("tr").find("td:eq(5)").text(amount)

            var totalDeduction = 0;
            $('#deduction_table').find('> tbody > tr').each(function () {
                var amount = parseFloat($(this).find("td:eq(5)").text());
                totalDeduction = totalDeduction + amount;

                totalAfterDeduction = basicPay - totalDeduction;

            });

            // x = x + amount;
            // totalAmount = basicPay + x
            // finalAmount = finalAmount + totalAmount
        } else if (percentageAmount.length != 0 && amountType == 'amount') {
            amount = percentageAmount
            $(this).closest("tr").find("td:eq(5)").text(amount)

            var totalDeduction = 0;
            $('#deduction_table').find('> tbody > tr').each(function () {
                var amount = parseFloat($(this).find("td:eq(5)").text());
                totalDeduction = totalDeduction + amount;

                totalAfterDeduction = basicPay - totalDeduction;

            });
        }
    }

    $('#deductionamount').val(totalDeduction)


    var totalAdditionX = parseFloat($('#additionamount').val())
    var totalDeductionX = parseFloat($('#deductionamount').val())

    var grossSalary = (basicPay + totalAdditionX) - totalDeductionX

    $('#grsalary').val(grossSalary)
    // console.log(totalAfterDeduction);

});

$(document).on('keyup', '#special_benefitamount', function (e) {
     
   

    var percentageAmount = parseFloat($(this).closest("tr").find("td:eq(1) input[type='text']").val())

    var amountType = $(this).closest("tr").find("td:eq(2)").text()

    // var addition_type = $(this).closest("tr").find("td:eq(6)").val()
   

    if (percentageAmount.length != 0 && amountType == 'amount') {
        if (isNaN(percentageAmount)) {
            percentageAmount = 0
            $(this).closest("tr").find("td:eq(5)").text(percentageAmount)
        }
        amount = percentageAmount
        $(this).closest("tr").find("td:eq(5)").text(amount)

        var totalAddition = 0;
        $('#special_addition_table_per').find('> tbody > tr').each(function () {
            amount = parseFloat($(this).find("td:eq(5)").text());
            // console.log('amount ' + amount)
            totalAddition = amount;

            // totalAfterAddition = basicPay + totalAddition;

            // special_additionamount

        });
        $('#special_additionamount').val(totalAddition)
    } else if (percentageAmount.length != 0 && amountType == '%') {
        var add_to_salary;
        

        if (isNaN(percentageAmount)) {
            percentageAmount = 0
            $(this).closest("tr").find("td:eq(5)").text(percentageAmount)
            $('#special_additionamount').val('')
            $('#add_to_salary option:first').prop('selected',true).change();

        }
    
        $(document).on('change', '#add_to_salary', function (e) {
            e.preventDefault();
           
            add_to_salary = $(this).val();
            // console.log('add_to_salary ' + add_to_salary)
            if (add_to_salary == 'basic_salary') {
                if ($('#basicpay').val().length == 0) {
                    alert('Please enter basic pay first.')
                }
                else {

                    var basicPay = parseFloat($('#basicpay').val())
                    amount = percentageAmount * (basicPay / 100)
                    // console.log('amount ' + amount)
                    $(this).closest("tr").find("td:eq(5)").text(amount)

                    var totalAddition = 0;
                    $('#special_addition_table_per').find('> tbody > tr').each(function () {
                        var amount = parseFloat($(this).find("td:eq(5)").text());
                        totalAddition = totalAddition + amount;

                        totalAfterAddition = basicPay + totalAddition;

                    });
                }
                $('#special_additionamount').val(totalAddition)
            }
            else if (add_to_salary == 'gross_salary') {
                if ($('#grsalary').val().length == 0) {
                    alert('Please enter gross salary pay first.')
                }
                else{
                    var gross_salary = parseFloat($('#grsalary').val())
                    // console.log('gross_salary '+gross_salary)
                    amount = percentageAmount * (gross_salary / 100)
                    // console.log('gross_salary amount ' + amount)
                    $(this).closest("tr").find("td:eq(5)").text(amount)

                    var totalAddition = 0;
                    $('#special_addition_table_per').find('> tbody > tr').each(function () {
                        var amount = parseFloat($(this).find("td:eq(5)").text());
                        totalAddition = totalAddition + amount;

                        totalAfterAddition = basicPay + totalAddition;

                    });
                }
                $('#special_additionamount').val(totalAddition)
            }
            
        });
        

    }


});

function collectingData() {
    this.event.preventDefault();
    let salaryData = {};

    var addition_table = $('#addition_table');
    let additionList = []

    var basicPay = parseFloat($('#basicpay').val())
    var gradeName = $('#salarygradename').val()
    var salarytype = $('#salarytype').val()
    grsalary = $('#grsalary').val()

    var paymentType = $('#type').text()

    // var paymentType = $('#type').text()   

    var totalAddition = parseFloat($('#additionamount').val())
    var totalDeduction = parseFloat($('#deductionamount').val())

    var specialAdditionAmount = parseFloat($('#special_additionamount').val())


    //console.log('gradeName '+gradeName)


    $(addition_table).find('> tbody > tr').each(function () {

        let addition = {}

        if (paymentType == '%') {
            //console.log('hello this is %')   
        }
        var benefit_id = $(this).closest("tr").find("td:eq(3) input[type='text']").val();
        var benefit_type = $(this).closest("tr").find("td:eq(4)").text();
        var total_amount=$('#additionamount').val();

        // console.log('hello total_amount '+total_amount)  

        addition["additionField"] = $(this).closest("tr").find("td:eq(0)").text();
        addition["additionAmount"] = $(this).closest("tr").find("td:eq(1) input[type='text']").val();
        addition["paymentType"] = paymentType;
        addition["benefit_id"] = benefit_id;
        addition["benefit_type"] = benefit_type;
        addition["total_amount"] = parseFloat($(this).find("td:eq(5)").text());


        additionList.push(addition);


    })

    //console.log('addition list : '+JSON.stringify(additionList))

    var deduction_table = $('#deduction_table');
    let deductionList = []

    $(deduction_table).find('> tbody > tr').each(function () {

        let deduction = {}
        var benefit_id = $(this).closest("tr").find("td:eq(3) input[type='text']").val();
        var benefit_type = $(this).closest("tr").find("td:eq(4)").text();
        var total_amount=$('#deductionamount').val();

        deduction["deductionField"] = $(this).closest("tr").find("td:eq(0)").text();
        deduction["deductionAmount"] = $(this).closest("tr").find("td:eq(1) input[type='text']").val()
        deduction["paymentType"] = paymentType;
        deduction["benefit_id"] = benefit_id;
        deduction["benefit_type"] = benefit_type;
        deduction["total_amount"] = parseFloat($(this).find("td:eq(5)").text());



        // for (var i = 0; i < deduction.length; i++) {
        //     if (deduction[i] !== "" && deduction[i] !== null) {
        //         deductionList.push(deduction[i]);
        //     }
        // }
        //console.log('deduction list'+deductionList)
        deductionList.push(deduction);

    })

    var spcialBenefit_table = $('#special_addition_table_per');
    let specialBenefitList = []

    $(spcialBenefit_table).find('> tbody > tr').each(function () {

        let special_benefit = {}
        var benefit_id = $(this).closest("tr").find("td:eq(3) input[type='text']").val();
        var benefit_type = $(this).closest("tr").find("td:eq(4)").text();
        var total_amount=$('#special_additionamount').val();

        //    var Allotment= $(this).closest("tr").find("td:eq(6) input[type='text']").val()

        //    console.log('Allotment '+Allotment)

        special_benefit["specialAdditionField"] = $(this).closest("tr").find("td:eq(0)").text();
        special_benefit["specialAdditionAmount"] = $(this).closest("tr").find("td:eq(1) input[type='text']").val()
       var allotmentAmount= $(this).closest("tr").find("td:eq(6) input[type='text']").val()

         
        // special_benefit["allotmentAmount"] = $(this).closest("tr").find("td:eq(6) input[type='text']").val()
        special_benefit["paymentType"] = paymentType;
        special_benefit["benefit_id"] = benefit_id;
        special_benefit["benefit_type"] = benefit_type;
        special_benefit["total_amount"] = parseFloat($(this).find("td:eq(5)").text());
        special_benefit["allotmentAmount"] = $(this).closest("tr").find("td:eq(6) input[type='text']").val()




        // for (var i = 0; i < deduction.length; i++) {
        //     if (deduction[i] !== "" && deduction[i] !== null) {
        //         deductionList.push(deduction[i]);
        //     }
        // }
        // console.log('specialBenefitList list'+specialBenefitList)
        specialBenefitList.push(special_benefit);

    })

    salaryData["additionList"] = additionList
    salaryData["deductionList"] = deductionList
    salaryData["specialBenefitList"] = specialBenefitList
    salaryData["basicPay"] = basicPay
    salaryData["gradeName"] = gradeName
    salaryData["salarytype"] = salarytype
    salaryData["grsalary"] = grsalary
    salaryData["totalAddition"] = totalAddition
    salaryData["totalDeduction"] = totalDeduction
    salaryData["specialAdditionAmount"] = specialAdditionAmount

    //console.log(salaryData)

    salaryUpdate(salaryData)
 



}

// function collectingData() {
//     this.event.preventDefault();
//     let salaryData = {};

//     var addition_table = $('#addition_table');
//     let additionList = []

//     var basicPay = parseFloat($('#basicpay').val())
//     var gradeName = $('#salarygradename').val()
//     var salarytype = $('#salarytype').val()
//     grsalary = $('#grsalary').val()

//     var paymentType = $('#type').text()

//     var paymentType = $('#type').text()

//     var totalAddition = parseFloat($('#additionamount').val())
//     var totalDeduction = parseFloat($('#deductionamount').val())

//     // //console.log('gradeName '+gradeName)


//     $(addition_table).find('> tbody > tr').each(function () {

//         let addition = {}

//         if (paymentType == '%') {
//             // //console.log('hello this is %')   
//         }
//         var benefit_id = $(this).closest("tr").find("td:eq(3) input[type='text']").val();
//         var benefit_type = $(this).closest("tr").find("td:eq(4)").text();

//         // //console.log('hello benefit_type '+benefit_type)  

//         addition["additionField"] = $(this).closest("tr").find("td:eq(0)").text();
//         addition["additionAmount"] = $(this).closest("tr").find("td:eq(1) input[type='text']").val();
//         addition["paymentType"] = paymentType;
//         addition["benefit_id"] = benefit_id;
//         addition["benefit_type"] = benefit_type;


//         additionList.push(addition);


//     })

//     // //console.log('addition list : '+JSON.stringify(additionList))

//     var deduction_table = $('#deduction_table');
//     let deductionList = []

//     $(deduction_table).find('> tbody > tr').each(function () {

//         let deduction = {}
//         var benefit_id = $(this).closest("tr").find("td:eq(3) input[type='text']").val();
//         var benefit_type = $(this).closest("tr").find("td:eq(4)").text();

//         deduction["deductionField"] = $(this).closest("tr").find("td:eq(0)").text();
//         deduction["deductionAmount"] = $(this).closest("tr").find("td:eq(1) input[type='text']").val()
//         deduction["paymentType"] = paymentType;
//         deduction["benefit_id"] = benefit_id;
//         deduction["benefit_type"] = benefit_type;

//         deductionList.push(deduction);

//     })

//     var salarygradeid = $('#salarygradeid').val();

//     salaryData["additionList"] = additionList
//     salaryData["deductionList"] = deductionList
//     salaryData["basicPay"] = basicPay
//     salaryData["gradeName"] = gradeName
//     salaryData["salarytype"] = salarytype
//     salaryData["grsalary"] = grsalary
//     salaryData["totalAddition"] = totalAddition
//     salaryData["totalDeduction"] = totalDeduction
//     salaryData["salarygradeid"] = salarygradeid

//     // //console.log(salaryData)
//     salaryUpdate(salaryData)
//     // 	// alert('Succs')



// }

//UPDATE Salary
function salaryUpdate(salaryData) {
    var salarygradeid = $('#salarygradeid').val();
    // //console.log("json data : "+JSON.stringify(salaryData))
    // alert('Sux')
    $.ajax({
        type: "PUT",
        url: "/salary-edit/" + salarygradeid,
        data: JSON.stringify(salaryData),
        dataType: "json",
        contentType: "application/json",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {


            if ($.isEmptyObject(response.error)) {
                $('#wrong_salarygradename').empty();
                $('#wrong_basicpay').empty();
                $.notify(response.message, { className: 'success', position: 'bottom right' });
                $(location).attr('href','/salary-list');

            } else {
                printErrorMsg(response.error);
            }
        }
    });

}

function printErrorMsg(message) {

    $('#wrong_salarygradename').empty();
    // $('#wrong_salarytype').empty();
    $('#wrong_basicpay').empty();



    if (message.salarygradename == null) {
        salarygradename = ""
    } else {
        salarygradename = message.salarygradename[0]
    }


    if (message.basicpay == null) {
        basicpay = ""
    } else {
        basicpay = message.basicpay[0]
    }

    $('#wrong_salarygradename').append('<span id="">' + salarygradename + '</span>');
    $('#wrong_basicpay').append('<span id="">' + basicpay + '</span>');

}

$(document).on('keyup', '#basicpay', function (e) {
    // alert( "Handler for .keyup() called." );
    var basicPay = $(this).val()

    if (basicPay == '') {
        $('#grsalary').val('')
    } else if (basicPay == 0) {
        $('#grsalary').val(0)
    } else {
        $('#grsalary').val(basicPay)
        calculated_total_sum_addition = 0;
        basicPayAfterAddition = 0;
        var basicPayAfterDeduct = 0;

        var amountType = $('#type').text()
       
        basicPay = parseFloat($('#basicpay').val());

        $("#addition_table #benefitamount").each(function () {

            var get_textbox_value = $(this).val();
            if ($.isNumeric(get_textbox_value)) {
                calculated_total_sum_addition += parseFloat(get_textbox_value);
                basicPayAfterAddition = parseFloat(basicPay * (calculated_total_sum_addition / 100))
            }
        });
        $('#additionamount').val(basicPayAfterAddition)

        if (basicPayAfterDeduct != 0) {
            totalGrossPay = basicPay + (basicPayAfterAddition - basicPayAfterDeduct)
            $('#grsalary').val(totalGrossPay)

        }
        else if (basicPayAfterDeduct == 0 || basicPayAfterDeduct == null) {
            totalGrossPay = basicPay + basicPayAfterAddition
            $('#grsalary').val(totalGrossPay)
        } else {
            $('#grsalary').val($('#basicpay').val())
        }


        // -------------------------------------------- DEDUCTION --------------------------------------------

        calculated_total_sum_deduct = 0;
        basicPayAfterDeduct = 0;
        basicPay = parseFloat($('#basicpay').val());

    
        $("#deduction_table #deductamount").each(function () {

            var get_textbox_value_deduct = $(this).val();

            if ($.isNumeric(get_textbox_value_deduct)) {
                calculated_total_sum_deduct += parseFloat(get_textbox_value_deduct);
                basicPayAfterDeduct = parseFloat(basicPay * (calculated_total_sum_deduct / 100))
               
            }

        });
        $('#deductionamount').val(basicPayAfterDeduct)
       
        if (basicPayAfterAddition != 0) {
            totalGrossPay = basicPay + (basicPayAfterAddition - basicPayAfterDeduct)
            $('#grsalary').val(totalGrossPay)

        } else {
            $('#grsalary').val($('#basicpay').val())
        }
        if (isNaN(basicPayAfterAddition)) {
            totalGrossPay = basicPay - basicPayAfterDeduct
            $('#grsalary').val(totalGrossPay)
            if (isNaN(basicPayAfterDeduct)) {
            }
        }
    }
})



