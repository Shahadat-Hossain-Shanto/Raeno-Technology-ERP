$(document).ready(function () {


	$.ajax({
		type: "GET",
		url: "/dashboard-report",
		dataType:"json",
		success: function(response){
			// console.log(response.data3);
			// alert(response.message)

			var zero = parseFloat(0)

			//SALE --------------------------------------------------------
			var todaySale = parseFloat(response.todaySale)
			var monthSale = parseFloat(response.totalMonthSale)
			var totalSale = parseFloat(response.totalSale)


			if(response.todaySale == null){
				$('#todaySale').text(zero.toFixed(2));
			}else{
				$('#todaySale').text(todaySale.toFixed(2));
			}

			if(response.totalMonthSale == null){
				$('#monthSale').text(zero.toFixed(2));
			}else{
				$('#monthSale').text(monthSale.toFixed(2));
			}

			if(response.totalSale == null){
				$('#totalSale').text(zero.toFixed(2));
			}else{
				$('#totalSale').text(totalSale.toFixed(2));
			}

			//DUE --------------------------------------------------------
			var todayDue = parseFloat(response.todayDue)
			var monthDue = parseFloat(response.totalMonthDue)
			var totalDue = parseFloat(response.totalDue)

			if(response.todayDue == null){
				$('#todayDue').text(zero.toFixed(2));
			}else{
				$('#todayDue').text(todayDue.toFixed(2));
			}

			if(response.totalMonthDue == null){
				$('#monthDue').text(zero.toFixed(2));
			}else{
				$('#monthDue').text(monthDue.toFixed(2));
			}

			if(response.totalDue == null){
				$('#totalDue').text(zero.toFixed(2));
			}else{
				$('#totalDue').text(totalDue.toFixed(2));
			}

			//EXPENSE--------------------------------------------------------
			var todayExpense = parseFloat(response.todayExpense)
			var monthExpense = parseFloat(response.totalMonthExpense)
			var totalExpense = parseFloat(response.totalExpense)

			if(response.todayExpense == null){
				$('#todayExpense').text(zero.toFixed(2));
			}else{
				$('#todayExpense').text(todayExpense.toFixed(2));
			}

			if(response.totalMonthExpense == null){
				$('#monthExpense').text(zero.toFixed(2));
			}else{
				$('#monthExpense').text(monthExpense.toFixed(2));
			}

			if(response.totalExpense == null){
				$('#totalExpense').text(zero.toFixed(2));
			}else{
				$('#totalExpense').text(totalExpense.toFixed(2));
			}

			//PURCHASE --------------------------------------------------------
			var todayExpense = parseFloat(response.todayPurchase)
			var monthExpense = parseFloat(response.totalMonthPurchase)
			var totalExpense = parseFloat(response.totalPurchase)


			if(response.todayPurchase == null){
				$('#todayPurchase').text(zero.toFixed(2));
			}else{
				$('#todayPurchase').text(todayExpense.toFixed(2));
			}

			if(response.totalMonthPurchase == null){
				$('#monthPurchase').text(zero.toFixed(2));
			}else{
				$('#monthPurchase').text(monthExpense.toFixed(2));
			}

			if(response.totalPurchase == null){
				$('#totalPurchase').text(zero.toFixed(2));
			}else{
				$('#totalPurchase').text(totalExpense.toFixed(2));
			}





			// Populate series for container1
            var processed_json = new Array();
            for (i = 0; i < response.data.length; i++) {
                processed_json.push([response.data[i].orderDate, parseFloat(response.data[i].grandTotal)]);
            }

            var processed_json1 = new Array();
            for (i = 0; i < response.data1.length; i++) {
                processed_json1.push([response.data1[i].productName, parseFloat(response.data1[i].qty)]);
            }

            var processed_json2 = new Array();
            for (i = 0; i < response.data2.length; i++) {
                processed_json2.push([response.data2[i].expense_date, parseFloat(response.data2[i].amount)]);
            }

            var processed_json3 = new Array();
            for (i = 0; i < response.data3.length; i++) {
                processed_json3.push([response.data3[i].name, parseFloat(response.data3[i].grandTotal)]);
            }

            var processed_json4 = new Array();
            arr = $.parseJSON(response.yearlySale)
            for(i=1; i<=12; i++){
            	if(arr[i] == null){
            		processed_json4.push(0);
            	}else{
            		processed_json4.push(parseFloat(arr[i]));
            	}
            }

            var processed_json5 = new Array();
            arrX = $.parseJSON(response.yearlyPurchase)
            for(i=1; i<=12; i++){
            	if(arrX[i] == null){
            		processed_json5.push(0);
            	}else{
            		processed_json5.push(parseFloat(arrX[i]));
            	}
            }


            // console.log(response.yearlySale)
            // console.log(response.yearlyPurchase)
            // console.log(processed_json4);
            // console.log(processed_json5);



        	// Create the chart
			Highcharts.chart('container1', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: 'Date wise total sale of last 30 days'
			    },
			    // subtitle: {
			    //     text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
			    // },
			    accessibility: {
			        announceNewData: {
			            enabled: true
			        }
			    },
			    xAxis: {
			        type: 'category',
			        title: {
			            text: 'Date'
			        }
			    },
			    yAxis: {
			        title: {
			            text: 'Amount'
			        }

			    },
			    legend: {
			        enabled: false
			    },
			    plotOptions: {
			        series: {
			            borderWidth: 0,
			            dataLabels: {
			                enabled: true,
			                format:'{point.y:.1f}'
			            }
			        }
			    },

			    tooltip: {
			        headerFormat: '<span style="font-size:11px"></span><br>',
			        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.1f}</b> total<br/>'
			    },

			    series: [{
			            data: processed_json
			        }],

			    drilldown: {
			        breadcrumbs: {
			            position: {
			                align: 'right'
			            }
			        },
			        series: [

			        ]
			    },
			    credits: {
				    enabled: false
				},
			});

			Highcharts.chart('container2', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: 'Top 10 products sale quantity'
			    },
			    // subtitle: {
			    //     text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
			    // },
			    accessibility: {
			        announceNewData: {
			            enabled: true
			        }
			    },
			    xAxis: {
			        type: 'category',
			        title: {
			            text: 'Product'
			        }
			    },
			    yAxis: {
			        title: {
			            text: 'Quantity'
			        }

			    },
			    legend: {
			        enabled: false
			    },
			    plotOptions: {
			        series: {
			            borderWidth: 0,
			            dataLabels: {
			                enabled: true,
			                format: '{point.y:.0f}'
			            }
			        }
			    },

			    tooltip: {
			        headerFormat: '<span style="font-size:11px"></span><br>',
			        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> total<br/>'
			    },

			    series: [{
			            data: processed_json1
			        }],

			    drilldown: {
			        breadcrumbs: {
			            position: {
			                align: 'right'
			            }
			        },
			        series: [

			        ]
			    },
			    credits: {
				    enabled: false
				},
			});

			Highcharts.chart('container4', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: 'Top sellers'
			    },
			    // subtitle: {
			    //     text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
			    // },
			    accessibility: {
			        announceNewData: {
			            enabled: true
			        }
			    },
			    xAxis: {
			        type: 'category',
			        title: {
			            text: 'Employee'
			        }
			    },
			    yAxis: {
			        title: {
			            text: 'Amount'
			        }

			    },
			    legend: {
			        enabled: false
			    },
			    plotOptions: {
			        series: {
			            borderWidth: 0,
			            dataLabels: {
			                enabled: true,
			                format: '{point.y:.1f}'
			            }
			        }
			    },

			    tooltip: {
			        headerFormat: '<span style="font-size:11px"></span><br>',
			        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.1f}</b> total<br/>'
			    },

			    series: [{
			            data: processed_json3
			        }],

			    drilldown: {
			        breadcrumbs: {
			            position: {
			                align: 'right'
			            }
			        },
			        series: [

			        ]
			    },
			    credits: {
				    enabled: false
				},
			});

			Highcharts.chart('container3', {
			    chart: {
			        type: 'line'
			    },
			    title: {
			        text: 'Date wise expenses of last 10 days'
			    },
			    // subtitle: {
			    //     text: 'Click the columns to view versions. Source: <a href="http://statcounter.com" target="_blank">statcounter.com</a>'
			    // },
			    accessibility: {
			        announceNewData: {
			            enabled: true
			        }
			    },
			    xAxis: {
			        type: 'category',
			        title: {
			            text: 'Date'
			        }
			    },
			    yAxis: {
			        title: {
			            text: 'Amount'
			        }

			    },
			    legend: {
			        enabled: false
			    },
			    plotOptions: {
			        series: {
			            borderWidth: 0,
			            dataLabels: {
			                enabled: true,
			                format: '{point.y:.1f}'
			            }
			        }
			    },

			    tooltip: {
			        headerFormat: '<span style="font-size:11px"></span><br>',
			        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.1f}</b> total<br/>'
			    },

			    series: [{
			            data: processed_json2
			        }],

			    drilldown: {
			        breadcrumbs: {
			            position: {
			                align: 'right'
			            }
			        },
			        series: [

			        ]
			    },
			    credits: {
				    enabled: false
				},
			});

			Highcharts.chart('container5', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: 'Monthly sale and purhcase amount of current year'
			    },
			    xAxis: {
			        categories: [
			            'Jan',
			            'Feb',
			            'Mar',
			            'Apr',
			            'May',
			            'Jun',
			            'Jul',
			            'Aug',
			            'Sep',
			            'Oct',
			            'Nov',
			            'Dec'
			        ],
			        // crosshair: true
			    },
			    yAxis: {
			        min: 0,
			        title: {
			            text: 'Amount'
			        }
			    },
			    tooltip: {
			        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
			        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
			            '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
			        footerFormat: '</table>',
			        shared: true,
			        useHTML: true
			    },
			    plotOptions: {
			        column: {
			            pointPadding: 0.2,
			            borderWidth: 0
			        }
			    },
			    series: [{
			        name: 'Purchase',
			        data: processed_json5

			    }, {
			        name: 'Sale',
			        data: processed_json4

			    }],
			    // credits: {
				//     enabled: false
				// }
			});


		}
	});
})

$('#store').on('change', function() {
	var storeId = $(this).val()
	var storeName = $("#store").find("option:selected").text()
	// alert(storeName)
	$.ajax({
		type: "GET",
		url: "/dashboard-report-seller/"+storeId,
		dataType:"json",
		success: function(response){
			// alert(response.message)
			// console.log(response.data3)

			var processed_json3 = new Array();
            for (i = 0; i < response.data3.length; i++) {
                processed_json3.push([response.data3[i].name, parseFloat(response.data3[i].grandTotal)]);
            }

			Highcharts.chart('container4', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: storeName
			    },
			    subtitle: {
			        text: 'Top Sellers'
			    },
			    accessibility: {
			        announceNewData: {
			            enabled: true
			        }
			    },
			    xAxis: {
			        type: 'category',
			        title: {
			            text: 'Employee'
			        }
			    },
			    yAxis: {
			        title: {
			            text: 'Amount'
			        }

			    },
			    legend: {
			        enabled: false
			    },
			    plotOptions: {
			        series: {
			            borderWidth: 0,
			            dataLabels: {
			                enabled: true,
			                format: '{point.y:.1f}'
			            }
			        }
			    },

			    tooltip: {
			        headerFormat: '<span style="font-size:11px"></span><br>',
			        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.1f}</b> total<br/>'
			    },

			    series: [{
			            data: processed_json3
			        }],

			    drilldown: {
			        breadcrumbs: {
			            position: {
			                align: 'right'
			            }
			        },
			        series: [

			        ]
			    },
			    credits: {
				    enabled: false
				},
			});
		}
	})
})

// fetchStoreSale()
// function fetchStoreSale(){
// 	$.ajax({
// 		type: "GET",
// 		url: "/dashboard-store-sale",
// 		dataType:"json",
// 		success: function(response){
// 			// console.log(response.data)
// 			$.each(response.data, function(key, item) {

// 				var grandTotal = parseFloat(item.grandTotal)

// 				$('tbody').append('\
// 				<tr>\
// 					<td></td>\
// 					<td>'+item.store_name+'</td>\
// 					<td>'+grandTotal.toFixed(2)+'</td>\
//         		</tr>');
// 			})
// 		}
// 	});
// }

$(document).ready(function () {
    var t = $('#store_sale_table').DataTable({
        ajax: {
            "url": "/dashboard-store-sale",
            "dataSrc": "data",
        },
        columns: [
            { data: null },
            { data: 'store_name' },
            { "render": function ( data, type, row, meta ){

                    var grandTotal = parseFloat(row.grandTotal)

                    return grandTotal.toFixed(2)
                }
            },
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
            var PageInfo = $('#store_sale_table').DataTable().page.info();
             t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            } );
        } );

    }).draw();

});

//DATA TABLE
// $(document).ready( function () {
// 	$('#store_sale_table').DataTable({
// 	    pageLength : 5,
// 	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
// 	    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
// 		    //debugger;
// 		    var index = iDisplayIndexFull + 1;
// 		    $("td:first", nRow).html(index);
// 		    return nRow;
// 	  	},
// 	  	searching: false,
// 	});
// });
