
if ($('#dataTable').length) {
    $('#dataTable').DataTable({
        responsive: true
    });
}

if($(document.body).find('#analysis-page-table').length > 0){
    let pagecount = parseInt($("#analysis-page-filter-count option:selected").val());
    const analysis_page_table = $('#analysis-page-table').DataTable( {
        searching: true,
        lengthChange: true,
        pageLength:pagecount,
        paging: true,
        ordering: false,
        info: false,
    });
    
    // open orders table search
    $('#analysis-page-search').keyup(function(){
        analysis_page_table.search($(this).val()).draw() ;
    })
    
    //open orders table filter
    $(document).on('change','#analysis-page-filter-count',function(){
        let val = parseInt($("#analysis-page-filter-count option:selected").val());
        analysis_page_table.page.len(val).draw();
    })
}

$(document).on('change','#analysis_item_select',function(){
    console.log('__changed');
})
$(document).on('change','#analysis_range_select',function(){
    $(this).closest('.down-arrow').css("transform", "rotate(-180deg)");
})

$(document).on('change','#tab_input',function(){
    if($('#tab_input').is(':checked')){
        $('#table-chart').removeClass('d-none');
        $('#table-table').addClass('d-none');
    } else {
        $('#table-chart').addClass('d-none');
        $('#table-table').removeClass('d-none');
    }
})
getChartData(response_);
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
    $counts = getChartData(response_);
    
    var options = {
        series: [{
                name: 'sales',
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
                show:true
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
            }
        },
    };

    var chart = new ApexCharts(document.querySelector("#analysis_page_chart"), options);
    chart.render();