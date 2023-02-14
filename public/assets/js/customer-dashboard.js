// let height = window.innerHeight;
// const backdrop = document.querySelector('.backdrop');
// backdrop.style.height = `${height}px`;
// console.log(height);
// $('.backdrop').
$(function(){
    // customer sales history
    if ($("#customer_sales_history").length) {
        AjaxRequestCom('/customersales','GET','','customerSalesChartDisplay');
    }
});
// customer open orders chart
customerOpenOrders()
//customer spending chart
customerSpendingChart()
// dashboard customer invoice order table
customer_invoice_orders()

window.addEventListener('load', function() {
    $('.backdrop').addClass('d-none');
});

// dashboard customer sales order display
function customerSalesChartDisplay($res){
    $counts = [];
    $categories = [];
    $customer_data = $res.data.data.customersaleshistory;
    for(let i = 1; i <= 12 ; i++ ){
        let is_add = true;
        $customer_data.forEach(da => {
            if(da.fiscalperiod == i){
                is_add = false;
                $counts.push(da.dollarssold)
            }
        })
        if(is_add){
            $counts.push(0);
        }
    }
    
    var options = {
        show:true,
        series: [{
                name: 'sales',
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
        },
        grid: {
            show: true,
            borderColor: '#797B7D',
            xaxis: {
              lines: {
                show: true 
               }
             },  
            yaxis: {
              lines: { 
                show: true 
               }
             },   
          },
    };

    var chart = new ApexCharts(document.querySelector("#customer_sales_history"), options);
    chart.render();
}

// customer open orders chart display
function customerOpenOrders(){
    var options = {
        series: [{
                name: 'sales',
                data: [10,2,30,100,50,60,45,80,90,30,110,48]
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
            min: 0,
        },
        grid: {
            borderColor: '#797B7D',
            show: true,
            xaxis: {
                lines: {
                    show: true 
                }
            },  
            yaxis: {
                lines: { 
                    show: true 
                }
            },   
        },
    };

    var chart = new ApexCharts(document.querySelector("#dashboard-open-orders-chart"), options);
    chart.render();
}

// customer spending chart display
function customerSpendingChart(){
    var options = {
        series: [{
                name: 'sales',
                data: [10,2,30,100,50,60,45,80,90,30,110,48]
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
            min: 0,
        },
        grid: {
            borderColor: '#797B7D',
            show: true,
            xaxis: {
                lines: {
                    show: true 
                }
            },  
            yaxis: {
                lines: { 
                    show: true 
                }
            },   
        },
    };

    var chart = new ApexCharts(document.querySelector("#customer-spending-chart"), options);
    chart.render();
}

// dashboard invoice order table
let dashboard_invoice_order_table;
function customer_invoice_orders(){
    $.ajax({
        type: 'GET',
        url: '/customer-invoice-orders',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend:function(){
            // $('.backdrop').removeClass('d-none');
        },
        success: function (res) {  
            // $('#pagination_disp').html(res.pagination_code);
            console.log(res,'___response');
            // dashboard-recent-invoice-order-table-div
            $('#dashboard-recent-invoice-order-table-div').html(res.table_code);
            /* data table generate */
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
            // $('.backdrop').addClass('d-none');
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



