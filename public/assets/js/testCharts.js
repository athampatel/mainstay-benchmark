// cutomersaleshistory
$(function(){
    $.ajax({
        type: 'GET',
        url: '/customersales',
        dataType: "JSON",
        // data: { "_token": $('meta[name="csrf-token"]').attr('content')},
        success: function (res) {
            chartDisplay(res.customersaleshistory)
            // window.location = 'http://www.google.com';
        }
    });
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