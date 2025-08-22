$(document).ready(function () {
    $("#grnd_total").hide();
    $("#total_qty").hide();
    $("#total_dscnt").hide();
    $("#total_prc").hide();
    $("#all_report").hide();

    $(".gt_report").click(function (e) {
        $("#grnd_total").show();
        $("#total_qty").hide();
        $("#total_dscnt").hide();
        $("#total_prc").hide();
        $("#all_report").hide();
    });

    $(".qty_report").click(function (e) {
        $("#grnd_total").hide();
        $("#total_dscnt").hide();
        $("#total_prc").hide();
        $("#all_report").hide();
        $("#total_qty").show();
    });

    $(".dscnt_report").click(function (e) {
        $("#grnd_total").hide();
        $("#total_qty").hide();
        $("#total_prc").hide();
        $("#all_report").hide();
        $("#total_dscnt").show();
    });

    $(".tp_report").click(function (e) {
        $("#grnd_total").hide();
        $("#total_qty").hide();
        $("#total_dscnt").hide();
        $("#all_report").hide();
        $("#total_prc").show();
    });

    $(".all_report").click(function (e) {
        $("#grnd_total").hide();
        $("#total_qty").hide();
        $("#total_dscnt").hide();
        $("#total_prc").hide();
        $("#all_report").show();
    });

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});

$("#ReportForm").on("submit", function (e) {

    e.preventDefault();

    //$('.chartp').hide();
    let formData = new FormData($("#ReportForm")[0]);
    $.ajax({
        type: "POST",
        url: "/summary-reports",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            // console.log(response.data);
            // alert(response.message);

            // Populate series for container1
            var processed_json = new Array();
            // console.log(response.data);

            for (i = 0; i < response.data.length; i++) {
                processed_json.push([
                    response.data[i].productName,
                    parseInt(response.data[i].gt),
                ]);
            }


            Highcharts.chart("container1", {
                chart: {
                    type: "area",
                },
                title: {
                    text: "Grand Total Report",
                },
                subtitle: {
                    text: "",
                },
                xAxis: {
                    type: "category",
                    title: {
                        text: "Products",
                    },
                },
                yAxis: {
                    title: {
                        text: "Grand total Amount ",
                    },
                },
                tooltip: {
                    split: true,
                    valueSuffix: "Tk",
                },
                plotOptions: {
                    area: {
                        stacking: "normal",
                        lineColor: "#666666",
                        lineWidth: 1,
                        marker: {
                            lineWidth: 1,
                            lineColor: "#666666",
                        },
                    },
                },
                series: [
                    {
                        name: "Grand Total (TK)",
                        data: processed_json,
                    },
                ],
            });
        },
    });
});
//***************End of Grand total**********************

//***************total Quantity**********************

$("#ReportForm1").on("submit", function (e) {


    e.preventDefault();

    //$('.chartp').hide();
    let formData = new FormData($("#ReportForm1")[0]);
    $.ajax({
        type: "POST",
        url: "/summary-reports",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            // console.log(response.data);
            // alert(response.message);

            var processed_json1 = new Array();
            // console.log(response.data1);
            for (i = 0; i < response.data1.length; i++) {
                processed_json1.push([
                    response.data1[i].productName,
                    parseInt(response.data1[i].qty),
                ]);
            }

            Highcharts.chart("container2", {
                chart: {
                    type: "area",
                },
                title: {
                    text: "Summary Report",
                },
                subtitle: {
                    text: "",
                },
                xAxis: {
                    type: "category",
                    title: {
                        text: "Products",
                    },
                },
                yAxis: {
                    title: {
                        text: "Quantity",
                    },
                },
                tooltip: {
                    split: true,
                    valueSuffix: "Unit",
                },
                plotOptions: {
                    area: {
                        stacking: "normal",
                        lineColor: "#666666",
                        lineWidth: 1,
                        marker: {
                            lineWidth: 1,
                            lineColor: "#666666",
                        },
                    },
                },
                series: [
                    {
                        name: "Quantity (Unit)",
                        data: processed_json1,
                    },
                ],
            });
        },
    });
});
//***************End of total Quantity*******************

//***************total Discount**********************

$("#ReportForm2").on("submit", function (e) {
    // $.ajaxSetup({
    // 	headers: {
    // 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    // 	}
    // });

    e.preventDefault();

    //$('.chartp').hide();
    let formData = new FormData($("#ReportForm2")[0]);
    $.ajax({
        type: "POST",
        url: "/summary-reports",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            // console.log(response.data);
            // alert(response.message);

            var processed_json2 = new Array();
            // console.log(response.data2);
            for (i = 0; i < response.data2.length; i++) {
                processed_json2.push([
                    response.data2[i].productName,
                    parseInt(response.data2[i].td),
                ]);
            }

            Highcharts.chart("container3", {
                chart: {
                    type: "area",
                },
                title: {
                    text: "Summary Report",
                },
                subtitle: {
                    text: "",
                },
                xAxis: {
                    type: "category",
                    title: {
                        text: "Products",
                    },
                },
                yAxis: {
                    title: {
                        text: "Discount Amount",
                    },
                },
                tooltip: {
                    split: true,
                    valueSuffix: "Tk",
                },
                plotOptions: {
                    area: {
                        stacking: "normal",
                        lineColor: "#666666",
                        lineWidth: 1,
                        marker: {
                            lineWidth: 1,
                            lineColor: "#666666",
                        },
                    },
                },
                series: [
                    {
                        name: "Discount (Tk)",
                        data: processed_json2,
                    },
                ],
            });
        },
    });
});
//***************End of total Discount*******************

//***************total Price**********************

$("#ReportForm3").on("submit", function (e) {
    // $.ajaxSetup({
    // 	headers: {
    // 		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    // 	}
    // });

    e.preventDefault();

    //$('.chartp').hide();
    let formData = new FormData($("#ReportForm3")[0]);
    $.ajax({
        type: "POST",
        url: "/summary-reports",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            // console.log(response.data3);
            // alert(response.message);

            var processed_json3 = new Array();
            // console.log(response.data3);
            for (i = 0; i < response.data3.length; i++) {
                processed_json3.push([
                    response.data3[i].productName,
                    parseInt(response.data3[i].tp),
                ]);
            }

            Highcharts.chart("container4", {
                chart: {
                    type: "area",
                },
                title: {
                    text: "Summary Report",
                },
                subtitle: {
                    text: "",
                },
                xAxis: {
                    type: "category",
                    title: {
                        text: "Products",
                    },
                },
                yAxis: {
                    title: {
                        text: "Total Price Amount(TK)",
                    },
                },
                tooltip: {
                    split: true,
                    valueSuffix: "Tk",
                },
                plotOptions: {
                    area: {
                        stacking: "normal",
                        lineColor: "#666666",
                        lineWidth: 1,
                        marker: {
                            lineWidth: 1,
                            lineColor: "#666666",
                        },
                    },
                },
                series: [
                    {
                        name: "Total Price (Tk)",
                        data: processed_json3,
                    },
                ],
            });
        },
    });
});
//***************End of total Price*******************

//***************All Summary Report**********************

$("#ReportForm4").on("submit", function (e) {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    e.preventDefault();

    $(".chartp").hide();

    $("#product_table td").remove();

    let formData = new FormData($("#ReportForm4")[0]);

    $.ajax({
        type: "POST",
        url: "/summary-reports",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            // console.log(response);
            // alert(response.message);

            // Populate series for container1
            var processed_json = new Array();
            // console.log(response.data);
            for (i = 0; i < response.data.length; i++) {
                processed_json.push([
                    response.data[i].productName,
                    parseInt(response.data[i].gt),
                ]);
            }

            var processed_json1 = new Array();
            // console.log(response.data1);
            for (i = 0; i < response.data1.length; i++) {
                processed_json1.push([
                    response.data1[i].productName,
                    parseInt(response.data1[i].qty),
                ]);
            }

            var processed_json2 = new Array();
            // console.log(response.data2);
            for (i = 0; i < response.data2.length; i++) {
                processed_json2.push([
                    response.data2[i].productName,
                    parseInt(response.data2[i].td),
                ]);
            }

            var processed_json3 = new Array();
            // console.log(response.data3);
            for (i = 0; i < response.data3.length; i++) {
                processed_json3.push([
                    response.data3[i].productName,
                    parseInt(response.data3[i].tp),
                ]);
            }

            var processed_json4 = new Array();
            // console.log(response.data4);
            for (i = 0; i < response.data4.length; i++) {
                processed_json4.push([
                    response.data4[i].productName,
                    parseInt(response.data4[i].tt),
                ]);
            }
            //grand total Chart

            // Highcharts.chart('container', {

            // 					chart: {
            // 						type: 'area'
            // 					},
            // 					title: {
            // 						text: 'Summary Report'
            // 					},
            // 					subtitle: {
            // 						text: ''
            // 					},
            // 					xAxis: {
            // 						type: 'category',
            // 				        title: {
            // 				            text: 'Products'
            // 				        }
            // 					},
            // 					yAxis: {
            // 				        title: {
            // 				            text: 'Quantity'
            // 				        }

            // 				    },
            // 					tooltip: {
            // 						split: true,
            // 						valueSuffix: ''
            // 					},
            // 					plotOptions: {
            // 						area: {
            // 							stacking: 'normal',
            // 							lineColor: '#666666',
            // 							lineWidth: 1,
            // 							marker: {
            // 								lineWidth: 1,
            // 								lineColor: '#666666'
            // 							}
            // 						}
            // 					},
            // 					series: [{
            // 						name: 'Grand Total (TK)',
            // 						data: processed_json
            // 					}, {
            // 						name: 'Quantity (Unit)',
            // 				        data: processed_json1
            // 					},
            // 					{
            // 						name: 'Total Discount (TK)',
            // 				        data: processed_json2
            // 					},
            // 					{
            // 						name: 'Total Price (TK)',
            // 				        data: processed_json3
            // 					}]
            // 				});

            ///************high chart using 4 charts*********************

            Highcharts.chart("container5", {
                chart: {
                    type: "area",
                },
                title: {
                    text: "Summary Report",
                },
                subtitle: {
                    text: "",
                },
                xAxis: {
                    type: "category",
                    title: {
                        text: "Products",
                    },
                },
                yAxis: {
                    title: {
                        text: "Quantity",
                    },
                },
                tooltip: {
                    split: true,
                    valueSuffix: "",
                },
                plotOptions: {
                    area: {
                        stacking: "normal",
                        lineColor: "#666666",
                        lineWidth: 1,
                        marker: {
                            lineWidth: 1,
                            lineColor: "#666666",
                        },
                    },
                },
                series: [
                    {
                        name: "Grand Total (TK)",
                        data: processed_json,
                    },
                    {
                        name: "Quantity (Unit)",
                        data: processed_json1,
                    },
                    {
                        name: "Total Discount (TK)",
                        data: processed_json2,
                    },
                    {
                        name: "Total Tax (TK)",
                        data: processed_json4,
                    },
                    {
                        name: "Total Price (TK)",
                        data: processed_json3,
                    },
                ],
            });
        },
    });
});

// //**********************high hart ends here **********************************

// }

// });

// });
