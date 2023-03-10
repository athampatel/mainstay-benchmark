window.addEventListener("load", function() {
    // customer open orders chart
    GetCustomerOpenOrders();
    // customerOpenOrders1() // uncomment
    //customer spending chart
    customerSpendingChart() // uncomment
    // dashboard customer invoice order table
    customer_invoice_orders() // uncomment
    customerSalesHistory() // uncomment
});
let customer_sales_chart;
let customer_open_orders;
let customer_total_spedning_chart;

function customerSalesHistory(){

    if(typeof(sales_orders) != 'undefined') 
        return false; 

    $.ajax({
        type: 'GET',
        url: '/customersales',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend:function(){
            $('#customer_sales_history .chart-loader-div').removeClass('d-none');
        },
        success: function (res) {  
            customerSalesChartDisplays(res)
        },
        complete:function(){
            $('#customer_sales_history .chart-loader-div').addClass('d-none');
        }
    });
}

// dashboard customer sales order display
function customerSalesChartDisplays(resp,status){
    $counts = [];
    $categories = [];
    if(status == 1){
        console.log(resp);
        $.each(resp,function(index,vale){
            $counts.push(Math.round(vale.total));
            $categories.push(vale.month)
        });
    }else{
        $categories = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $res = JSON.parse(resp);        
        $customer_data = $res.data.data.customersaleshistory;
        for(let i = 1; i <= 12 ; i++ ){
            let is_add = true;
            $customer_data.forEach(da => {
                if(da.fiscalperiod == i){
                    is_add = false;
                    $counts.push(Math.round(da.dollarssold))
                }
            })
            if(is_add){
                $counts.push(0);
            }
        }
    }
    // console.log($counts,'___counts');
    var options = {
        show:true,
        series: [{
                name: 'Sales',
                data: $counts
            },
        ],
        chart: {
            foreColor: '#9ba7b2',
            type: 'line',
            height: 360,
            zoom:{
                enabled:false
            },
            toolbar : {
                show:false
            },
            dropShadow:{
                enabled:true,
                top:3,
                left:14,
                blur:4,
                opacity:0.10,
            }
        },
        stroke: {
            width: 3,
            curve:'straight'
        },

        xaxis: {
            type:'month',
            categories: $categories,
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                gradientToColors: ['#A4CD3C'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        tooltip: {
            x: {
                show: false
            },
            y: {
                formatter: function($counts, series) {
                return '$'+ numberWithCommas($counts);
                }
            }
        },
        markers: {
            size: 4,
            colors: ["#A4CD3C"],
            strokeColors: "#A4CD3C",
            strokeWidth: 2,
            hover: {
                size: 7,
            }
        },
        colors: ["#A4CD3C"],
        dataLabels: {
            formatter(val, opts) {
            const name = opts.w.globals.labels[opts.seriesIndex]
            return [name, val.toFixed(1) + '%']
            },
        }, 
        yaxis: {
            title: {
                text: ''
            },
            labels: {
                formatter:function($counts, series) {
                    return '$'+ numberWithCommas($counts);
                }
            }
        },
        grid: {
            show: true,
            borderColor: '#797B7D',
            xaxis: {
            lines: {
                show: true 
            },
            labels: {
                formatter: function(val, index) {
                    return '$'+ numberWithCommas(val);
                }
            }
            },  
            yaxis: {
            lines: { 
                show: true 
            },
            labels: {
                formatter: function(val, index) {
                    return '$'+ numberWithCommas(val);
                }
            }

            },   
        },
    };

    customer_sales_chart = new ApexCharts(document.querySelector("#customer_sales_history"), options);
    customer_sales_chart.render();
}

function GetCustomerOpenOrders(){
    $.ajax({
        type: 'GET',
        url: '/getCustomerOpenOrders',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend:function(){
            $('#dashboard-open-orders-chart .chart-loader-div').removeClass('d-none');
        },
        success: function (res) {  
            res = JSON.parse(res);
            if(res.success){
                customerOpenOrders(res.data.data)
                $('#open-orders-total-amount').text(`$ ${numberWithCommas(Math.round(res.data.count))}`)               
            }
        },
        complete:function(){
            $('#dashboard-open-orders-chart .chart-loader-div').addClass('d-none');
        }
    });
}

// customer open orders chart display
function customerOpenOrders($array){
    let arr = [];
    $array.forEach(ar => {
        // arr.push(ar.toFixed(2));
        arr.push(Math.round(ar));
    })
    var options = {
        series: [{
                name: 'Sales',
                data: arr
            },
        ],
        chart: {
            foreColor: '#9ba7b2',
            type: 'line',
            height: 360,
            zoom:{
                enabled:false
            },
            toolbar : {
                show:false
            },
            dropShadow:{
                enabled:true,
                top:3,
                left:14,
                blur:4,
                opacity:0.10,
            }
        },
        stroke: {
            width: 3,
            curve:'straight'
        },

        xaxis: {
            type:'month',
            categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                gradientToColors: ['#A4CD3C'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        markers: {
        	size: 4,
        	colors: ["#A4CD3C"],
        	strokeColors: "#A4CD3C",
        	strokeWidth: 2,
        	hover: {
        		size: 7,
        	}
        },
        colors: ["#A4CD3C"],
        
        yaxis: {
            title: {
                text: ''
            },
            labels: {
                formatter: function(arr, index) {
                    return '$'+ numberWithCommas(arr);
                 }
              }
        },
        tooltip: {
            x: {
                show: false
            },
            y: {
                formatter: function(arr, series) {
                return '$'+ numberWithCommas(arr);
                }
            }
        },
        grid: {
            borderColor: '#797B7D',
            show: true,
            xaxis: {
                lines: {
                    show: true 
                },
                labels: {
                    formatter: function(val, index) {
                        return '$'+ numberWithCommas(val);
                     }
                  }
            },  
            yaxis: {
                lines: { 
                    show: true 
                },
                labels: {
                    formatter: function(val, index) {
                        return '$'+ numberWithCommas(val);
                     }
                  }
            },   
        },
    };

    customer_open_orders = new ApexCharts(document.querySelector("#dashboard-open-orders-chart"), options);
    customer_open_orders.render();
}

// customer spending chart display
function customerSpendingChart(){
    var labels = [];
    var value = [];
    if(typeof(data_bycat) != 'undefined'){
        $.each(data_bycat,function(index,values){
            var _label = values.label;
            labels.push(_label);            
            value.push(Math.round(values.value));
        });
    }
    // var options = {
    //     series: value,
    //     chart: {
    //     // type: 'donut',
    //     type: 'line',
    //     width: '70%', 
    //     // foreColor: '#373d3f'       
    //     foreColor: '#9ba7b2'       
    //   },
    //   labels: labels,    
    //   dataLabels: {
    //     formatter(val, opts) {
    //       const name = opts.w.globals.labels[opts.seriesIndex]
    //       return [name, val.toFixed(1) + '%']
    //     },
    //   },      
    //   tooltip: {
    //     x: {
    //       show: false
    //     },
    //     y: {
    //       formatter: function(value, series) {
    //         return '$'+ numberWithCommas(value);
    //       }
    //     }
    //   },
    //   xaxis: {
    //     type:'products',
    //     categories:labels ,
    // },
    //   total: {
    //     show: false,
    //     showAlways: false,
    //     label: 'Total',
    //     fontSize: '22px',
    //     fontFamily: 'Helvetica, Arial, sans-serif',
    //     fontWeight: 600,
    //     color: '#373d3f',
    //     formatter: function (w) {
    //       return w.globals.seriesTotals.reduce((a, b) => {
    //         return a + b
    //       }, 0)
    //     }
    //   }         
    // };

    var options = {
        series: [{
                name: 'Sales',
                data: value
            },
        ],
        chart: {
            foreColor: '#9ba7b2',
            type: 'line',
            height: 360,
            zoom:{
                enabled:false
            },
            toolbar : {
                show:false
            },
            dropShadow:{
                enabled:true,
                top:3,
                left:14,
                blur:4,
                opacity:0.10,
            }
        },
        stroke: {
            width: 3,
            curve:'straight'
        },

        xaxis: {
            type:'month',
            categories: labels,
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                gradientToColors: ['#A4CD3C'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        markers: {
        	size: 4,
        	colors: ["#A4CD3C"],
        	strokeColors: "#A4CD3C",
        	strokeWidth: 2,
        	hover: {
        		size: 7,
        	}
        },
        colors: ["#A4CD3C"],
        
        yaxis: {
            title: {
                text: ''
            },
            labels: {
                formatter: function(value, index) {
                    return '$'+ numberWithCommas(value);
                 }
              }
        },
        tooltip: {
            x: {
                show: true
            },
            y: {
                formatter: function(value, series) {
                return '$'+ numberWithCommas(value);
                }
            }
        },
        grid: {
            borderColor: '#797B7D',
            show: true,
            xaxis: {
                lines: {
                    show: true 
                },
                labels: {
                    formatter: function(val, index) {
                        return '$'+ numberWithCommas(val);
                     }
                  }
            },  
            yaxis: {
                lines: { 
                    show: true 
                },
                labels: {
                    formatter: function(val, index) {
                        return '$'+ numberWithCommas(val);
                     }
                  }
            },   
        },
    };


    customer_total_spedning_chart = new ApexCharts(document.querySelector("#customer-spending-chart"), options);
    customer_total_spedning_chart.render();
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// dashboard invoice order table
let dashboard_invoice_order_table;
function customer_invoice_orders(){
    if(typeof(recent_orders) != 'undefined') 
        return false; 

    $.ajax({
        type: 'GET',
        url: '/customer-invoice-orders',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend:function(){
            $('#dashboard-recent-invoice-order-table-div .chart-loader-div').removeClass('d-none');
        },
        success: function (res) {  
            $('#dashboard-recent-invoice-order-table-div').html(res.table_code);
            dashboard_invoice_order_table = $('#dashboard-recent-invoice-order-table').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength: 5,
                paging: true,
                ordering: false,
                info: false,
            });
        },
        complete:function(){
            $('#dashboard-recent-invoice-order-table-div .chart-loader-div').addClass('d-none');
        }
    });
}

// recent invoice order datatable search
$('#open-orders-chart-search').keyup(function(){
    dashboard_invoice_order_table.search($(this).val()).draw() ;
})
// recent invoice order datatable count filter
$(document).on('change','#dashboard-open-orders-filter-count',function(){
    let val = parseInt($("#dashboard-open-orders-filter-count option:selected").val());
    dashboard_invoice_order_table.page.len(val).draw();
})

// export chart functions

// customer sales orders
$(document).on('click','#export-sales-invoice',function(e){
    e.preventDefault();
    $('#export-sales-invoice-drop').toggleClass('d-none')
})
$(document).on('click','.export-sales-invoice-item',function(e){
    e.preventDefault();
    let type = $(e.currentTarget).data('type');
    exportChart(customer_sales_chart,type);
    $('#export-sales-invoice-drop').toggleClass('d-none')
})

// customer open orders
$(document).on('click','#export-open-oders-chart',function(e){
    e.preventDefault();
    $('#export-open-orders-drop').toggleClass('d-none')
})
$(document).on('click','.export-open-orders-item',function(e){
    e.preventDefault();
    let type = $(e.currentTarget).data('type');
    exportChart(customer_open_orders,type);
    $('#export-open-orders-drop').toggleClass('d-none')
})

// total customers spending
$(document).on('click','#export-total-spending-chart',function(e){
    e.preventDefault();
    $('#export-total-spending-drop').toggleClass('d-none')
})
$(document).on('click','.export-total-spending-item',function(e){
    e.preventDefault();
    let type = $(e.currentTarget).data('type');
    exportChart(customer_total_spedning_chart,type);
    $('#export-total-spending-drop').toggleClass('d-none')
})

function exportChart(chartname,type){
    var cts = chartname.ctx;
    var w = chartname.w;
    if(type == 'svg'){
        chartname.exports.exportToSVG(cts)
    } 
    if(type == 'png'){
        chartname.exports.exportToPng(cts);
    }
    if(type == 'csv'){
        chartname.exports.exportToCSV({
            series: w.config.series,
            columnDelimiter:','
        });
    }
}