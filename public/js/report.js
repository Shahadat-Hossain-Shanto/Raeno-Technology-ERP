$(document).ready(function () {
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	if( $('#employee option:selected') && $('#startdate').val() && $('#enddate').val() ){

		//CREATE BRAND
		$('#ReportForm').on('submit',  function (e) {
			e.preventDefault();

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$('.chartp').hide();

			let formData = new FormData($('#ReportForm')[0]);

			var empId = $('#employee').val();
			alert(empId);

			$.ajax({
				type: "POST",
				url: "/reports-emp/"+empId,
				data: formData,
				contentType: false,
				processData: false,
				success: function(response){
					console.log(response.data);	
					alert(response.message)
					// $(location).attr('href','/brand-list');

					// Populate series
		            var processed_json = new Array();
		            for (i = 0; i < response.data.length; i++) {
		                processed_json.push([response.data[i].productName, parseInt(response.data[i].qty)]);
		            }

	            	// Create the chart
					Highcharts.chart('container', {
					    chart: {
					        type: 'column'
					    },
					    title: {
					        text: 'Sales By Products'
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
					            text: 'Products'
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
					        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
					        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b> of total<br/>'
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
					    }
					});
				}
			});
		});


	}else if( $('#startdate').val() && $('#enddate').val() ){

		//CREATE BRAND
		$('#ReportForm').on('submit',  function (e) {
			e.preventDefault();

			$('.chartp').hide();

			let formData = new FormData($('#ReportForm')[0]);

			$.ajax({
				type: "POST",
				url: "/reports",
				data: formData,
				contentType: false,
				processData: false,
				success: function(response){
					console.log(response.data);	
					alert(response.message)
					// $(location).attr('href','/brand-list');

					// Populate series
		            var processed_json = new Array();
		            for (i = 0; i < response.data.length; i++) {
		                processed_json.push([response.data[i].productName, parseInt(response.data[i].qty)]);
		            }

		            // $('#container').Highcharts({
		            //     chart: {
		            //         type: "column"
		            //     },
		            //     title: {
		            //         text: "City Population"
		            //     },
		            //     xAxis: {
		            //         allowDecimals: false,
		            //         title: {
		            //             text: "Products"
		            //         }
		            //     },
		            //     yAxis: {
		            //         title: {
		            //             text: "Quantity"
		            //         }

		            //     },
		            //     series: [{
		            //         data: processed_json
		            //     }]
	            	// });

	            	// Create the chart
					Highcharts.chart('container', {
					    chart: {
					        type: 'column'
					    },
					    title: {
					        text: 'Top Five Products'
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
					            text: 'Products'
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
					                format: '{point.y:.1f}%'
					            }
					        }
					    },

					    tooltip: {
					        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
					        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
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
					    }
					});
				}
			});
		});

	}
	// else{

	// 	$(document).ready(function () {
	// 		e.preventDefault();

	// 		$.ajaxSetup({
	// 			headers: {
	// 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	// 			}
	// 		});
		

	// 		alert('Somwthing Wrong!')
	// 	});
			
	// }

});

	







