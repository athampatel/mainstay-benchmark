let is_table = localStorage.getItem('is_table');
let tab_input = document.getElementById('tab_input');
if(is_table){
    if( parseInt(is_table) == 1 ){
        $('#analysis_table_container').removeClass('d-none');
        $('#analysis_table_chart').addClass('d-none');
        console.log('table show');
    } else {
        // chart show
        tab_input.checked = true;
        $('#analysis_table_container').addClass('d-none');
        $('#analysis_table_chart').removeClass('d-none');
        console.log('chart show');
    }
} else {
    // table show
    console.log('table show');
    localStorage.setItem('is_table',1);
}

window.addEventListener('load', function() {
    $('.backdrop').addClass('d-none');
});

getAnalysispageData()

let analysis_page_table;

function getAnalysispageData(){
    $.ajax({
        type: 'GET',
        url: '/get-analysis-page-data',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        beforeSend:function(){},
        success: function (res) {  
            response_ = res.response;
            $('#invoice-order-page-table-div').html(res.table_code);
            let pagecount = parseInt($("#analysis-page-filter-count option:selected").val());
            analysis_page_table = $('#analysis-page-table').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength:pagecount,
                paging: true,
                ordering: false,
                info: false,
                responsive: true
            });
            $counts = getChartData(response_);
            renderAnalysisChart($counts)

        },
        complete:function(){}
    });
}

if ($('#dataTable').length) {
    $('#dataTable').DataTable({
        responsive: true
    });
}

$('#analysis-page-search').keyup(function(){
    analysis_page_table.search($(this).val()).draw() ;
})

//analysis page table filter
$(document).on('change','#analysis-page-filter-count',function(){
    let val = parseInt($("#analysis-page-filter-count option:selected").val());
    analysis_page_table.page.len(val).draw();
})

$(document).on('change','#analysis_item_select',function(){
    console.log('__changed');
})
$(document).on('change','#analysis_range_select',function(){
    $(this).closest('.down-arrow').css("transform", "rotate(-180deg)");
})

$(document).on('change','#tab_input',function(){
    if($('#tab_input').is(':checked')){
        localStorage.setItem('is_table',0);
        $('#analysis_table_container').addClass('d-none');
        $('#analysis_table_chart').removeClass('d-none');
    } else {
        localStorage.setItem('is_table',1);
        $('#analysis_table_container').removeClass('d-none');
        $('#analysis_table_chart').addClass('d-none');
    }
});

$(document).on('change','select#analysis_range_select',function(){
    if($(this).val() == 4){
        $('.date-range-field').removeClass('d-none');
    }else{
        $('.date-range-field').addClass('d-none');
    }
});


$('input[name="daterange"]').daterangepicker({
        locale: {
          format: 'MM-DD-YYYY'
        },
    });
// getChartData(response_)
function getChartData(res){
    let arr1 = [];
    
    res.forEach(da => {
        let month  =moment(da.date,'yyyy-mm-dd').format('mm')
        if(arr1[month]){
            arr1[month] += da.total_amount;
        } else {
            arr1[month] = da.total_amount;
        }
    });
    
    let final = [];
    
    for(let num = 01;num<=12;num++){
        let num1 = num < 9 ? `0${num}`: num;
        if(arr1[num1]){
            final.push(arr1[num1]);
        } else {
            final.push(0);
        }
    }

    return final;
}
    
function renderAnalysisChart($counts){
    var options = {
        series: [{
                name: 'Sales',
                data: $counts
            },
        ],
        chart: {
            foreColor: '#9ba7b2',
            type: 'bar',
            height: 600,
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
            width: 2,
            curve:'straight'
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '36%',
            },
        },
        dataLabels: {
             enabled: false //changeable
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
            strokeColors: "#fff",
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
            formatter: function(value, series) {
                return '$'+ numberWithCommas(value);
              }
        },
    };

    var chart = new ApexCharts(document.querySelector("#analysis_page_chart"), options);
    chart.render();
}