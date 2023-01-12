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
    console.log($data,'__data');
    $counts = [];
    $categories = [];
    $data.forEach(da => {
        $categories.push(getMonthNameShort(parseInt(da.fiscalperiod)))
        $counts.push(parseInt(da.dollarssold/1000))
    });
    var options = {
        series: [{
            name: 'sales',
            data: $counts
        }],
        chart: {
            foreColor: '#9ba7b2',
            type: 'bar',
            height: 360
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '30%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        title: {
            text: 'Column Chart',
            align: 'left',
            style: {
                fontSize: '14px'
            }
        },
        colors: ["#29cc39"],
        xaxis: {
            // categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Dec'],
            categories: $categories,
        },
        yaxis: {
            title: {
                text: '$ (thousands)'
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$ " + val + " thousands"
                }
            }
        }
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