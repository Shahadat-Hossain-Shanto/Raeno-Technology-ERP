fetchProduct()

$('#ReportForm').on('submit',  function (e) {

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	e.preventDefault();

	$('.chartp').hide();

	$("#product_table td").remove();

	let formData = new FormData($('#ReportForm')[0]);

	$.ajax({
		type: "POST",
		url: "/reports",
		data: formData,
		contentType: false,
		processData: false,
		success: function(response){
			// console.log(response.data);	
			// alert(response.message)


			// Populate series for container1
            var processed_json = new Array();
            for (i = 0; i < response.data.length; i++) {
                processed_json.push([response.data[i].productName, parseInt(response.data[i].qty)]);
            }

        	// Create the chart
			Highcharts.chart('container1', {
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
			                format: '{point.y:.1f}'
			            }
			        }
			    },

			    tooltip: {
			        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
			        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> total<br/>'
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



			// Populate series for container2
            var processed_json1 = new Array();
            for (i = 0; i < response.data1.length; i++) {
                processed_json1.push([response.data1[i].productName, parseInt(response.data1[i].gt)]);
            }

			Highcharts.chart('container2', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: 'Sales by Products'
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

			onChangeDataTable(response.data2)

		}
	});
});

function fetchProduct(){
	$.ajax({
		type: "GET",
		url: "/report",
		dataType:"json",
		success: function(response){
			var processed_json = new Array();
            for (i = 0; i < response.data.length; i++) {
                processed_json.push([response.data[i].productName, parseInt(response.data[i].qty)]);
            }

        	// Create the chart
			Highcharts.chart('container1', {
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
			                format: '{point.y:.1f}'
			            }
			        }
			    },

			    tooltip: {
			        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
			        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> total<br/>'
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



			// Populate series for container2
            var processed_json1 = new Array();
            for (i = 0; i < response.data1.length; i++) {
                processed_json1.push([response.data1[i].productName, parseInt(response.data1[i].gt)]);
            }

			Highcharts.chart('container2', {
			    chart: {
			        type: 'column'
			    },
			    title: {
			        text: 'Sales by Products'
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

		}
	})
}

function resetButton(){
    // $("#product_table").find("tr:gt(0)").remove(); 
    $('.chartp').hide();
	$("#product_table td").remove();
	
	$('form').on('reset', function() {
	  	setTimeout(function() {
		    $('.selectpicker').selectpicker('refresh');
	  	});
	});
}

$(document).ready(function () {
    var t = $('#product_table').DataTable({
        ajax: {
            "url": "/report",
            "dataSrc": "data2"
        },
        columns: [
            { data: null },
            { data: 'productName' },
            { data: 'qty' },
	        { "render": function ( data, type, row, meta ){ var tp = parseFloat(row.tp); return tp.toFixed(2);} },
	        { "render": function ( data, type, row, meta ){ var td = parseFloat(row.td); return td.toFixed(2);} },
	        { "render": function ( data, type, row, meta ){ var tt = parseFloat(row.tt); return tt.toFixed(2);} },
	        { "render": function ( data, type, row, meta ){ var gt = parseFloat(row.gt); return gt.toFixed(2);} },
        ],
        columnDefs: [
	        {
	            searchable: true,
	            orderable: true,
	            targets: 0,
	        },
	    ],
	    order: [[1, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#product_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
});

function onChangeDataTable(json){

	$('#product_table').DataTable().clear().destroy()

	var t = $('#product_table').DataTable({
        // ajax: {
        //     "url": "/low-stock-report-data/"+storeId,
        //     "dataSrc": "data"
        // },
        data : json,
        columns: [
            { data: null },
            { data: 'productName' },
            { data: 'qty' },
	        { "render": function ( data, type, row, meta ){ var tp = parseFloat(row.tp); return tp.toFixed(2);} },
	        { "render": function ( data, type, row, meta ){ var td = parseFloat(row.td); return td.toFixed(2);} },
	        { "render": function ( data, type, row, meta ){ var tt = parseFloat(row.tt); return tt.toFixed(2);} },
	        { "render": function ( data, type, row, meta ){ var gt = parseFloat(row.gt); return gt.toFixed(2);} },
        ],
        columnDefs: [
	        {
	            searchable: true,
	            orderable: true,
	            targets: 0,
	        },
	    ],
	    order: [[1, 'asc']],
	    pageLength : 10,
	    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Todos']],
	    dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    t.on('order.dt search.dt', function () {
	    t.on( 'draw.dt', function () {
	    	var PageInfo = $('#product_table').DataTable().page.info();
	         t.column(0, { page: 'current' }).nodes().each( function (cell, i) {
	            cell.innerHTML = i + 1 + PageInfo.start;
	        } );
	    });

    }).draw();
}




 