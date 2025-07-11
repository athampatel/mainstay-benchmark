var analysis_page_chart;
var analysis_page_chart_desc;
let is_table = localStorage.getItem('is_table');
let tab_input = document.getElementById('tab_input');
if(is_table){
    let analaysis_type = $('#analysis_item_select').val();
    if( parseInt(is_table) == 1 ){
        $('#analysis_table_container').removeClass('d-none');
        $('#analysis_table_chart').addClass('d-none');
        if(analaysis_type ==  '1') {
            // $('#product_line_select_label').removeClass('d-none');
            $('#analysis-page-search').attr({placeholder: 'Search By Benchmark Item Number'});
        } else {
            // $('#product_line_select_label').addClass('d-none');
            $('#analysis-page-search').attr('placeholder','Search by Invoice Number');
        }
        // $('#analysis_item_select_label').addClass('d-none'); // for table
        $('#analysis_range_select option[value="4"]').show();
    } else {
        tab_input.checked = true;
        $('#analysis_table_container').addClass('d-none');
        $('#analysis_table_chart').removeClass('d-none');
        // $('#analysis_item_select_label').removeClass('d-none'); // for table
        $('#analysis_range_select option[value="4"]').hide();
        // $('#product_line_select_label').addClass('d-none');
        $('#analysis-page-search').attr('placeholder','Search by Invoice Number');
    }
} else {
    $('#analysis_table_container').removeClass('d-none');
    $('#analysis_table_chart').addClass('d-none');
    // $('#analysis_item_select_label').addClass('d-none'); // for table
    $('#analysis_range_select option[value="4"]').show();
    // $('#product_line_select_label').addClass('d-none');
    $('#analysis-page-search').attr('placeholder','Search by Invoice Number');
    localStorage.setItem('is_table',1);
}

$('#analysis_year_select_label').removeClass('d-none')
$('#analysis_range_select').val(0).trigger('change');

$(document).on('change','#tab_input',function(){
    if($('#tab_input').is(':checked')){
        localStorage.setItem('is_table',0);
        $('#analysis_table_container').addClass('d-none');
        $('#analysis_table_chart').removeClass('d-none');
        // $('#analysis_item_select_label').removeClass('d-none'); // for table
        $('#analysis_range_select option[value="4"]').hide()
        $('#analysis_range_select_label').removeClass('d-none');
        // $('#product_line_select_label').addClass('d-none');
        $('#analysis-page-search').attr('placeholder','Search by Invoice Number');
    } else {
        localStorage.setItem('is_table',1);
        $('#analysis_table_container').removeClass('d-none');
        $('#analysis_table_chart').addClass('d-none');
        // $('#analysis_item_select_label').addClass('d-none'); // for table
        $('#analysis_range_select option[value="4"]').show();
        let is_prod = $('#analysis_item_select').val();
        if(is_prod == '1') {
            $('#analysis_range_select_label').addClass('d-none');
            $('#analysis_year_select_label').removeClass('d-none');
            // $('#product_line_select_label').removeClass('d-none');
            $('#analysis-page-search').attr({placeholder: 'Search By Benchmark Item Number'});
            
            // $('#analysis-page-search').attr('placeholder','Enter the Word');
        } else {
            // $('#product_line_select_label').addClass('d-none');
            $('#analysis-page-search').attr('placeholder','Search by Invoice Number');
            $('#analysis_range_select_label').removeClass('d-none');
            $('#analysis-page-search').attr('placeholder','Enter the Word');
        }
    }
    $('#product_line_select option.prod').remove();
    let pageCount = parseInt($("#analysis-page-filter-count option:selected").val());
    let select_by_range = parseInt($('#analysis_range_select option:selected').val());
    let current_year = parseInt($('#analysis_year_select option:selected').val());
    getAnalysispageData(0,pageCount,select_by_range,current_year)

});
// ajax
let analysis_page_table;
let pageCount = parseInt($("#analysis-page-filter-count option:selected").val());
let select_by_range = parseInt($('#analysis_range_select option:selected').val());
let current_year = parseInt($('#analysis_year_select option:selected').val());
getAnalysispageData(0,pageCount,select_by_range,current_year)

function getAnalysispageData($page,$count,range,year){
    let chart_type = parseInt($('#analysis_item_select option:selected').val());
    var _view = 2;
    if($(document.body).find('#analysis_table_chart').hasClass('d-none')){
        _view = 1;
        // chart_type = 0
    }
    
    // let prod_line = $('#product_line_select').val();
    let search_word = $('#analysis-page-search').val();
    let is_search_by_itemcode = $('#is_search_item_code').val();
    $.ajax({
        type: 'GET',
        url: '/get-analysis-page-data',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        // data: { 'page' : $page,'count': $count,'year' : year,'range':range,'chart_type': chart_type,'view_type':_view},
        // data: { 'page' : $page,'count': $count,'year' : year,'range':range,'chart_type': chart_type,'view_type':_view,prod_line},
        data: { 'page' : $page,'count': $count,'year' : year,'range':range,'chart_type': chart_type,'view_type':_view,search_word,is_search_by_itemcode,'sorting_dir': $('#sorting_dir').val()},
        beforeSend:function(){
            beforeAjax();
           $('#analysis_page_chart').html('');
        },
        success: function (res) {
            console.log(res,'___analysis response');
            /* product line type select box */
            // let prod_line_type_options = `<option value="0">Please select</option>`;
            // if(chart_type == 1 && _view == 1 ) {
            //     if(prod_line == 0) {
            //         res.prod_line_types.forEach(pro => {
            //             prod_line_type_options += `<option class="prod" value="${pro}">${pro}</option>`;
            //         })
            //     }
            // } 
            // if(prod_line == 0) {
            //     $('#product_line_select').html(prod_line_type_options);
            // }
            /* product line type select box */
            if(res.is_export){
                $('#analysis-page-export').removeClass('d-none');
            } else {
                $('#analysis-page-export').addClass('d-none');
            }
            $('#invoice-order-page-table-div').html(res.table_code);
            $('#pagination_disp').html(res.pagination_code)
            let analysis_data = res.analysis_data;
            let range_months = res.range_months;
            let range_months_year = res.range_months_year;
            let is_year_multiple = res.is_year_change;
            let product_data = res.product_data;
            let product_data_desc = res.product_data_desc;
            let is_table_action = [];
            if(_view == 1 && chart_type != 1){
                is_table_action =  [ { targets: [6], orderable: false},];
            }
            var sort_dir = $('#sorting_dir').val();
            analysis_page_table = $('#analysis-page-table').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength:$count,
                paging: true,
                ordering: true,
                info: false,
                order: [],
                columnDefs: is_table_action
            });

            analysis_page_table.on('order.dt', function() {
                var orderInfo = analysis_page_table.order();  // Returns array of [columnIndex, direction]
                console.log('Sorting changed:', orderInfo);

                // Example: check first column's sorting
                if (orderInfo.length && orderInfo[0][0] === 1) {  // 0 = first column

                    var tab_sort =  $('#sorting_dir').val();
                   if(tab_sort == 'asc'){
                        $('#sorting_dir').val('desc');
                   }else{
                        $('#sorting_dir').val('asc');
                   }
                    //$('#sorting_dir').val(orderInfo[0][1]);     
                    
                    let analysis_page_table;
                    let pageCount = parseInt($("#analysis-page-filter-count option:selected").val());
                    let select_by_range = parseInt($('#analysis_range_select option:selected').val());
                    let current_year = parseInt($('#analysis_year_select option:selected').val());
                    getAnalysispageData(0,pageCount,select_by_range,current_year)

                }
            });

            let months = [];
            let chart_count = [];
            let months_desc = [];
            
            var checkAnalysis = $('#analysis_item_select').val();
            if(checkAnalysis == 0 && analysis_data.length > 0){
                $analaysis_count =  getAnalaysisDataCount(analysis_data,range,range_months)
                months = $analaysis_count['months'];
                months_desc = $analaysis_count['months'];
                chart_count = $analaysis_count['final'];
            } else if(checkAnalysis == 1 && chart_type != 0 && _view != 1){ // add 
            // } else if(checkAnalysis == 1){
                $analaysis_count =  getProductLineCount(product_data);
                $analaysis_count_desc =  getProductLineCount(product_data_desc);
                months = $analaysis_count['products'];
                chart_count = $analaysis_count['counts'];
                months_desc = $analaysis_count_desc['products'];
            }

            if(chart_type == 1 || _view == 2){
                if(is_year_multiple) {
                    renderAnalysisChart(chart_count,range_months_year,months_desc);
                } else {
                    renderAnalysisChart(chart_count,months,months_desc);
                }
            }

        },
        complete:function(){
            AfterAjax()
        }
    });
} 
// before ajax start
function beforeAjax(){
    $('.table_loader').removeClass('d-none');
    $('#invoice-order-page-table-div').addClass('d-none');
    $('#pagination_disp').addClass('d-none');
    $('#analysis_page_chart').addClass('d-none');
    $('#analysis_item_select').prop('disabled', true);
    $('#analysis_range_select').prop('disabled', true);
    $('#analysis_year_select').prop('disabled', true);
    $('input[name="daterange"]').prop('disabled', true);
}
// After ajax complete
function AfterAjax(){
    $('.table_loader').addClass('d-none');
    $('#invoice-order-page-table-div').removeClass('d-none');
    $('#pagination_disp').removeClass('d-none');
    $('#analysis_page_chart').removeClass('d-none');
    $('#analysis_item_select').prop('disabled', false);
    $('#analysis_range_select').prop('disabled', false);
    $('#analysis_year_select').prop('disabled', false);
    $('input[name="daterange"]').prop('disabled', false);
}

// product by line chart
function getProductLineCount(product_data){
    let products = [];
    let counts = [];
    product_data.forEach(pro => {
        products.push(pro.label);
        counts.push(pro.value);
    })
    return {'products': products,'counts' : counts};
}

// analysis chart data
function getAnalaysisDataCount(data,range,range_months){
    let arr1 = [];
    let months = [];
    let test_array = [];
    data.forEach( (da,k) => {
       if(range == 0){
        arr1[da.fiscalperiod] = da.dollarssold;
        test_array.push(da.fiscalperiod);
       } else {
            if(range == 1){
                let last_month_number = moment().subtract(1, 'month').format('MM');
                test_array = [last_month_number];
                if(last_month_number == da.fiscalperiod){
                    let last_mont_name = moment().subtract(1,'month').format('MMM');
                    arr1['01'] = da.dollarssold;
                    months.push(last_mont_name);
                }
            }
            if(range == 2){
                // test_array = ['01','02','03'];
                test_array = range_months;
                if(test_array.includes(da.fiscalperiod)){
                    arr1[da.fiscalperiod] = da.dollarssold;
                }
            }
            if(range == 3){
                // test_array = ['01','02','03','04','05','06'];
                test_array = range_months;
                if(test_array.includes(da.fiscalperiod)){
                    arr1[da.fiscalperiod] = da.dollarssold;
                }
            }
            if(range == 4){
                test_array = range_months;
                if(test_array.includes(da.fiscalperiod)){
                    arr1[da.fiscalperiod] = da.dollarssold;
                }
            }
            if(range == 5) {
                test_array = range_months;
                if(test_array.includes(da.fiscalperiod)){
                    arr1[da.fiscalperiod] = da.dollarssold;
                }
            }
        }
    });

    let last_number = 1;
    if(range == 0) last_number = 12;
    if(range == 1) last_number = 1;
    if(range == 2) last_number = 3;
    if(range == 3) last_number = 6;
    if(range == 5) last_number = 12;
    if(range == 4) last_number = range_months.length;

    months = [];
    if(range == 0) {
        for(let num = 01; num <= last_number; num++){
            let num1 = num <= 9 ? `0${num}`: num;
            if(!test_array.includes(`${num1}`)){
                test_array.push(`${num1}`);
            } 
        }
    }
    test_array.forEach(mon => {
        months.push(getMonthNameShort(mon))
    })
    let final = [];
    if(range == 4){
        range_months.forEach(rd => {
            if(arr1[rd]){
                final.push(arr1[rd]);
            } else {
                final.push(0);
            }
        })
    } else {
        // for(let num = 01; num <= last_number; num++){
        //     let num1 = num <= 9 ? `0${num}`: num;
        //     if(arr1[num1]){
        //         final.push(Math.round(arr1[num1]));
        //     } else {
        //         final.push(0);
        //     }
        // }
        test_array.forEach(mo => {
            if(arr1[mo]) {
                final.push(Math.round(arr1[mo]));
            } else {
                final.push(0);
            }
        })
    }
    return {'months': months,'final' : final};
}

// datatable search
// $('#analysis-page-search').keyup(function(){
//     let search_word = $(this).val();
//     if(search_word != ''){
//         $('#pagination_disp').addClass('d-none');
//     } else {
//         $('#pagination_disp').removeClass('d-none');
//     }
//     analysis_page_table.search($(this).val()).draw() ;
// })

// product line search in datatable search
$('#analysis-page-search').keyup(function(){
    let search_word = $(this).val();
    if(search_word == ''){
        commomAjaxDataCall();
    } 
})

$(document).on('click','#analysis-page-search-img',function(){
    commomAjaxDataCall();
});

//analysis page table filter
$(document).on('change','#analysis-page-filter-count',function(){
    let val = parseInt($("#analysis-page-filter-count option:selected").val());
    let range = parseInt($('#analysis_range_select option:selected').val());
    let year = parseInt($('#analysis_year_select option:selected').val());
    getAnalysispageData(0,val,range,year)
})

// pagination 
$(document).on('click','.pagination_link',function(e){
    e.preventDefault();
    $page = $(this).data('val');
    $val = parseInt($("#analysis-page-filter-count option:selected").val());
    let range = parseInt($('#analysis_range_select option:selected').val());
    let year = '';
    if(range == 4 ){
        let range_text = $('input[name="daterange"]').val();
        let range_start1 = range_text.slice(0,10);
        let range_end1 = range_text.substring(13);
        let range_start = moment(range_start1,'MM-DD-YYYY').subtract(1,'days').format('YYYY-MM-DD');
        let range_end = moment(range_end1,'MM-DD-YYYY').add(1,'days').format('YYYY-MM-DD');
        year = `${range_start}&${range_end}`;
    } else {
        year = parseInt($('#analysis_year_select option:selected').val());
    }

    getAnalysispageData($page,$val,range,year)
})

// filter boxes
$(document).on('change','#analysis_item_select',function(){
    let pageCount = parseInt($("#analysis-page-filter-count option:selected").val());
    let select_by_range = parseInt($('#analysis_range_select option:selected').val());
    let current_year = parseInt($('#analysis_year_select option:selected').val());
    let is_prod = $('#analysis_item_select').val();
    let is_table_tab = $('#tab_input').is(':checked');
    $('#product_line_select option.prod').remove();
    $('#analysis-page-search').val('');
    if(is_prod == '1' && !is_table_tab) {
        $('#analysis_range_select_label').addClass('d-none');
        $('#analysis_year_select_label').removeClass('d-none');
        // $('#product_line_select_label').removeClass('d-none');
        $('#analysis-page-search').attr({placeholder: 'Search By Benchmark Item Number'});
    } else {
        // $('#product_line_select_label').addClass('d-none');
        $('#analysis-page-search').attr('placeholder','Search by Invoice Number');
        $('#analysis_range_select_label').removeClass('d-none');
    }
    getAnalysispageData(0,pageCount,select_by_range,current_year)
})

// Item Code Search Button Click
$(document).on('click','#item_code_search',function(){
    commomAjaxDataCall();
});

// item code search keyup 
$(document).on('keyup','#item_code_input',function(){
    let search_val = $('#item_code_input').val();
    if(search_val == '') {
        commomAjaxDataCall();
    }
});

// function commomAjaxDataCall(icode = ''){
function commomAjaxDataCall(){
    let pageCount = parseInt($("#analysis-page-filter-count option:selected").val());
    let select_by_range = parseInt($('#analysis_range_select option:selected').val());
    let current_year = parseInt($('#analysis_year_select option:selected').val());
    let is_prod = $('#analysis_item_select').val();
    let is_table_tab = $('#tab_input').is(':checked');
    if(is_prod == '1' && !is_table_tab) {
        $('#analysis_range_select_label').addClass('d-none');
        $('#analysis_year_select_label').removeClass('d-none');
    } else {
        $('#analysis_range_select_label').removeClass('d-none');
    }
    // if(icode != '') {
        getAnalysispageData(0,pageCount,select_by_range,current_year)
    // } else {
        // getAnalysispageData(0,pageCount,select_by_range,current_year)
    // }
}

// range select
$(document).on('change','select#analysis_range_select',function(){
    $(this).closest('.down-arrow').css("transform", "rotate(-180deg)");
    if($(this).val() == 0) {
        $('#analysis_year_select_label').removeClass('d-none')
    } else {
        $('#analysis_year_select_label').addClass('d-none')
    }
    let year = parseInt($('#analysis_year_select option:selected').val());
    let pageCount = parseInt($("#analysis-page-filter-count option:selected").val());
    if($(this).val() == 4){
        $('.date-range-field').removeClass('d-none');
    }else{
        getAnalysispageData(0,pageCount,$(this).val(),year)
        $('.date-range-field').addClass('d-none');
    }
});

// year change
$(document).on('change','select#analysis_year_select',function(){
    let year = parseInt($('#analysis_year_select option:selected').val());
    let range = parseInt($('#analysis_range_select option:selected').val());
    let pageCount = parseInt($("#analysis-page-filter-count option:selected").val());
    console.log('___year changed');
    // let analysis_type = $('#analysis_item_select').val();
    // let is_table = $('#tab_input').is(':checked');
    // if(analysis_type == '1' && !is_table) {
        $('#product_line_select option.prod').remove();
    // }
    if(range == 4){
    }else{
        getAnalysispageData(0,pageCount,range,year)
        $('.date-range-field').addClass('d-none');
    }
});

$('input[name="daterange"]').daterangepicker({
    locale: {
        format: 'MM-DD-YYYY'
    },
    
},function(start, end) {
    let start_date = start.format('YYYY-MM-DD');
    let start_year = start.format('YYYY');
    let end_date = end.format('YYYY-MM-DD');
    $val = parseInt($("#analysis-page-filter-count option:selected").val());
    let range = parseInt($('#analysis_range_select option:selected').val());
    let year = `${moment(start_date,'YYYY-MM-DD').subtract(1,'days').format('YYYY-MM-DD')}&${moment(end_date,'YYYY-MM-DD').add(1,'days').format('YYYY-MM-DD')}`;
    $("#analysis_year_select").val(start_year).change();
    getAnalysispageData(0,$val,range,year)
});

function renderAnalysisChart(ct_counts,ct_months,ct_desc){
    let percent = `${ct_months.length * 3}%`;
    const chart_div = document.querySelector("#analysis_page_chart");
    chart_div.innerHTML = '';
    var options = {
        series: [{
                name: 'Total',
                data: ct_counts
            },
        ],
        chart: {
            foreColor: '#9ba7b2',
            type: 'bar',
            height: 650,
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
                columnWidth:percent
            },
        },
        dataLabels: {
             enabled: false
        },
        xaxis: {
            // type:'month',
            type:'category',
            categories: ct_months
            // categories: ct_month_year
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
                formatter: function($ct_counts, series) {
                return '$'+ numberWithCommas($ct_counts);
                }
            }
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
            labels: {
                formatter:function($ct_counts, series) {
                    return '$'+ numberWithCommas($ct_counts);
                }
            }
        },
    };
    analysis_page_chart = new ApexCharts(document.querySelector("#analysis_page_chart"), options);
    analysis_page_chart.render();
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// export functions
$(document).on('click','#export-analysis-chart',function(e){
    e.preventDefault();
    $('#export-analysis-drop-down').toggleClass('d-none');
})

$(document).on('click','.export-analysis-chart-item',function(e){
    e.preventDefault();
    let type = $(e.currentTarget).data('type');
    exportChart(analysis_page_chart,type);
    $('#export-analysis-drop-down').toggleClass('d-none')
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

// export analysis  
// $(document).on('click','#analysis-report-icon',function(e){
//     e.preventDefault();
//     $('#export-analysis-page-drop').toggleClass('d-none');
// })

$(document).on('click','.export-analysis-page-item',function(e){
    e.preventDefault();
    let type = $(e.currentTarget).data('type');
    let year = parseInt($('#analysis_year_select option:selected').val());
    let range = parseInt($('#analysis_range_select option:selected').val());
    if(range == 4){
        let range_text = $('input[name="daterange"]').val();
        let range_start1 = range_text.slice(0,10);
        let range_end1 = range_text.substring(13);
        let range_start = moment(range_start1,'MM-DD-YYYY').subtract(1,'days').format('YYYY-MM-DD');
        let range_end = moment(range_end1,'MM-DD-YYYY').add(1,'days').format('YYYY-MM-DD');
        year = `${range_start}&${range_end}`;  
    } 
    let type1 = 1;
    if(type == 'csv'){
        type1 = 1;
    } 
    if(type == 'pdf'){
        type1 = 2;
    }

    // ajax call
    $.ajax({
        type: 'POST',
        url: '/exportAnalysis',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { 'year' : year,'range':range,'type':type1},
        beforeSend:function(){
           $('.backdrop').removeClass('d-none');
        },
        success: function (res) {  
            Swal.fire({
                position: 'center-center',
                icon: res.icon,
                title: res.title,
                text: res.message,
                showConfirmButton: !res.success,
                timer: res.success ? 4000 : 0,
            })
        },
        complete:function(){
            $('#export-analysis-page-drop').addClass('d-none');
            $('.backdrop').addClass('d-none');
        }
    });
    
});


// export toggle
$(document).on('click','#analysis-page-export',function(e){
    e.preventDefault();
    $('#export-analysis-page-drop').toggleClass('d-none');
})


// item code click in analysis page
$(document).on('click','.itemcode_click',function(){
    let data_item_code = $(this).data('item_code');
    let is_item_code = $(this).data('is_item_code');
    if(parseInt(is_item_code)) {
        $('#is_search_item_code').val('1');
    } else {
        $('#is_search_item_code').val('0');
    }
    let previous_value = $('#item_code_input').val();
    if(previous_value != data_item_code){
        $('#item_code_input').val(data_item_code);
        commomAjaxDataCall()
    }
    console.log('__clicked');
})


// alias item code click in analysis page
// $(document).on('click','.aliasitem_click',function(){
//     let data_item_code = $(this).data('alias_no');
//     let previous_value = $('#item_code_input').val();
//     if(previous_value != data_item_code){
//         $('#item_code_input').val(data_item_code);
//         $('#is_search_item_code').val('1');
//         commomAjaxDataCall()
//     }
// })