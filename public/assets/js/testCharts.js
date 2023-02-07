// cutomersaleshistory
$(function(){
    
    $.ajax({
        type: 'GET',
        url: '/customersales',
        dataType: "JSON",
        success: function (res) {
            chartDisplay(res.customersaleshistory)            
        }
    });



    // chart 2
	var optionsLine = {
		chart: {
			foreColor: '#9ba7b2',
			height: 360,
			type: 'line',
			zoom: {
				enabled: false
			},
			dropShadow: {
				enabled: true,
				top: 3,
				left: 2,
				blur: 4,
				opacity: 0.1,
			}
		},
		stroke: {
			curve: 'smooth',
			width: 5
		},
		colors: ["#A4CD3C", '#29cc39'],
		series: [{
			name: "Music",
			data: [1, 15, 56, 20, 33, 27]
		}, {
			name: "Photos",
			data: [30, 33, 21, 42, 19, 32]
		}],
		title: {
			text: 'Multiline Chart',
			align: 'left',
			offsetY: 25,
			offsetX: 20
		},
		subtitle: {
			text: 'Statistics',
			offsetY: 55,
			offsetX: 20
		},
		markers: {
			size: 4,
			strokeWidth: 0,
			hover: {
				size: 7
			}
		},
		grid: {
			show: true,
			padding: {
				bottom: 0
			}
		},
		labels: ['01/15/2002', '01/16/2002', '01/17/2002', '01/18/2002', '01/19/2002', '01/20/2002'],
		xaxis: {
			tooltip: {
				enabled: false
			}
		},
		legend: {
			position: 'top',
			horizontalAlign: 'right',
			offsetY: -20
		}
	}
	var chartLine = new ApexCharts(document.querySelector('#chart2'), optionsLine);
	chartLine.render();
    

    // chart 3
	var options = {
		series: [{
			name: 'series1',
			data: [31, 40, 68, 31, 92, 55, 100]
		}, {
			name: 'series2',
			data: [11, 82, 45, 80, 34, 52, 41]
		}],
		chart: {
			foreColor: '#9ba7b2',
			height: 360,
			type: 'area',
			zoom: {
				enabled: false
			},
			toolbar: {
				show: true
			},
		},
		colors: ["#A4CD3C", '#f41127'],
		title: {
			text: 'Area Chart',
			align: 'left',
			style: {
				fontSize: "16px",
				color: '#666'
			}
		},
		dataLabels: {
			enabled: false
		},
		stroke: {
			curve: 'smooth'
		},
		xaxis: {
			type: 'datetime',
			categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
		},
		tooltip: {
			x: {
				format: 'dd/MM/yy HH:mm'
			},
		},
	};
	var chart = new ApexCharts(document.querySelector("#chart3"), options);
	chart.render();

});





function chartDisplay($data){
    // console.log($data,'__data');
    $counts = [];
    $categories = [];
    $data.forEach(da => {
        $categories.push(getMonthNameShort(parseInt(da.fiscalperiod)))
        // $counts.push(parseInt(da.dollarssold/1000))
        $counts.push(da.dollarssold)
    });
    var options = {
        series: [{
                name: 'sales',
                data: $counts
                // data: [30,25,45,78,47,45,12,65,84,45,77,63]
            },
            // {
            //     name: 'sales1',
            //     // data: $counts
            //     data: [45.57,95,43,65,75,15,44,66,22,34,50]
            // }
        ],
        // series: [],
        chart: {
            foreColor: '#9ba7b2',
            // type: 'line',
            type: 'area',
            height: 360,
            zoom:{
                enabled:false
            },
            dropShadow:{
                enabled:true,
                top:3,
                left:14,
                blur:4,
                opacity:0.10,
            }
        },
        // plotOptions: {
        //     bar: {
        //         horizontal: false,
        //         columnWidth: '30%',
        //         endingShape: 'rounded'
        //     },
        // },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 5,
            curve:'straight'
            // curve:'smooth'
            // colors: ['transparent']
        },
        // title: {
        //     text: 'Column Chart',
        //     align: 'left',
        //     style: {
        //         fontSize: '14px'
        //     }
        // },
        colors: ["#A4CD3C","#00bbf9"], // to define the color
        xaxis: {
            // categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Dec'],
            categories: $categories,
        },
        yaxis: {
            title: {
                text: '$ (thousands)'
            }
        },
        // fill: {
        //     type: 'gradient',
		// 	gradient: {
		// 		shade: 'light',
		// 		gradientToColors: ['#A4CD3C'],
		// 		shadeIntensity: 1,
		// 		type: 'horizontal',
		// 		opacityFrom: 1,
		// 		opacityTo: 1,
		// 		stops: [0, 100, 100, 100]
		// 	},
        // },
        // tooltip: {
        //     y: {
        //         formatter: function (val) {
        //             return "$ " + val + " thousands"
        //         }
        //     }
        // },
        // markers: {
		// 	size: 4,
		// 	colors: ["#A4CD3C"],
		// 	strokeColors: "#fff",
		// 	strokeWidth: 2,
		// 	hover: {
		// 		size: 7,
		// 	}
		// },
    };
    var chart = new ApexCharts(document.querySelector("#cutomersaleshistory"), options);
    chart.render();
}

function getMonthNameShort(monthNumber) {
    const date = new Date();
    date.setMonth(monthNumber - 1);
    return date.toLocaleString('en-US', { month: 'short' });
}

function getMonthNameLong(monthNumber) {
    const date = new Date();
    date.setMonth(monthNumber - 1);
    return date.toLocaleString('en-US', { month: 'long' });
}