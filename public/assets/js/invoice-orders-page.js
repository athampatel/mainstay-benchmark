let pagecount = parseInt($("#invoice-orders-page-filter-count option:selected").val());

$(document).on('change','#invoice-orders-page-filter-count',function(){
    let val = parseInt($("#invoice-orders-page-filter-count option:selected").val());
    // let filter_type = parseInt($('#invoice_filter_select').val());
    let selected_year = $('#invoice_year_select').val();
    let started_date = selected_year +'-01-01';
    let ended_date = selected_year + '-12-31';
    // if(filter_type == 1) {
    //     let daterange = $('input[name="daterange"]').val();
    //     let dates = daterange.split(' - ');
    //     started_date = moment(dates[0],'MM-DD-YYYY').format('YYYY-MM-DD');
    //     ended_date = moment(dates[1],'MM-DD-YYYY').format('YYYY-MM-DD');
    // }

    getInvoiceOrderAjax(0,val,started_date,ended_date)
})

let selected_year = $('#invoice_year_select').val();
let date_range = $('input[name="daterange"]').val();
let started_date = selected_year +'-01-01';
let ended_date = selected_year + '-12-31';

getInvoiceOrderAjax(0,pagecount,started_date,ended_date)

$(document).on('click','.pagination_link',function(e){
    e.preventDefault();
    $page = $(this).data('val');
    $val = parseInt($("#invoice-orders-page-filter-count option:selected").val());
    let selected_year = $('#invoice_year_select').val();
    let started_date = selected_year +'-01-01';
    let ended_date = selected_year + '-12-31';
    // let filter_type = parseInt($('#invoice_filter_select').val());
    // if(filter_type == 1) {
    //     let daterange = $('input[name="daterange"]').val();
    //     let dates = daterange.split(' - ');
    //     started_date = moment(dates[0],'MM-DD-YYYY').format('YYYY-MM-DD');
    //     ended_date = moment(dates[1],'MM-DD-YYYY').format('YYYY-MM-DD');
    // }
    getInvoiceOrderAjax($page,$val,started_date,ended_date)
})
// $('#invoice-orders-page-search').keyup(function(){
//     let search_word = $(this).val();
//     if(search_word != ''){
//         $('#pagination_disp').addClass('d-none');
//     } else {
//         $('#pagination_disp').removeClass('d-none');
//     }
//     open_order_page_table.search($(this).val()).draw();
// })
$('#invoice-orders-page-search').keyup(function(){
    let search_word = $(this).val();
    if(search_word == ''){
        invoiceCommonAjaxData();
    }
})


$(document).on('click','#invoice-orders-page-search-img',function(){
    invoiceCommonAjaxData();
});


function invoiceCommonAjaxData(){
    let val = parseInt($("#invoice-orders-page-filter-count option:selected").val());
    let selected_year = $('#invoice_year_select').val();
    let started_date = selected_year +'-01-01';
    let ended_date = selected_year + '-12-31';
    getInvoiceOrderAjax(0,val,started_date,ended_date)
}

let open_order_page_table;

function getInvoiceOrderAjax($page,$count,start_date,end_date){
    let search_word = $('#invoice-orders-page-search').val();
    $.ajax({
        type: 'GET',
        url: '/getInvoiceOrders',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "page" : $page,'count': $count,'start_date':start_date,'end_date':end_date,search_word,'sorting_dir': $('#sorting_dir').val()},
        beforeSend:function(){
            beforeChangeOrderAjax();
        },
        success: function (res) {  
            $('#pagination_disp').html(res.pagination_code);
            $('#invoice-orders-page-table-div').html(res.table_code);
            
            if(res.total_records > parseInt(env_maximum)){
                $('#invoice-csv-export').prop('src','#');
                $('#invoice-pdf-export').prop('src','#');
            } else {
                $('#invoice-csv-export').prop('src','/invoice-export/csv');
                $('#invoice-pdf-export').prop('src','/invoice-export/pdf');
            }
        
            var sort_dir = $('#sorting_dir').val();
            open_order_page_table = $('#invoice-orders-page-table').DataTable({
                searching: true,
                lengthChange: true,
                pageLength:$count,
                paging: true,
                ordering: true,
                info: false,
                order: [],
                columnDefs: [
                    { targets: [6], orderable: false},
                ],
            });

            open_order_page_table.on('order.dt', function() {
                var orderInfo = open_order_page_table.order();  // Returns array of [columnIndex, direction]
                console.log('Sorting changed:', orderInfo);

                // Example: check first column's sorting
                if (orderInfo.length && orderInfo[0][0] === 3) {  // 0 = first column
                    //$('#sorting_dir').val(orderInfo[0][1]);
                   var tab_sort =  $('#sorting_dir').val();
                   if(tab_sort == 'asc'){
                        $('#sorting_dir').val('desc');
                   }else{
                        $('#sorting_dir').val('asc');
                   }
                   invoiceCommonAjaxData();
                }
            });
        },
        complete:function(){
            afterChangeOrderAjax();
        }
    });

    
}

// export details options
$(document).on('click','#invoice-orders-report-page-icon',function(e){
    e.preventDefault();
    console.log('clicked');
    $('#export-invoice-orders-drop').toggleClass('d-none');
})

$(document).on('click','.export-invoice-orders-item',function(e){
    e.preventDefault();
    let type = $(e.currentTarget).data('type');
    console.log(type,'___type');
    $('#export-invoice-orders-drop').addClass('d-none');
})

function beforeChangeOrderAjax(){
    $('.table_loader').removeClass('d-none');
    $('#invoice-orders-page-table-div').addClass('d-none');
    $('#pagination_disp').addClass('d-none');
}

function afterChangeOrderAjax(){
    $('.table_loader').addClass('d-none');
    $('#invoice-orders-page-table-div').removeClass('d-none');
    $('#pagination_disp').removeClass('d-none');
}

$(document).on('click','#invoice-order-export',function(e){
    e.preventDefault();
    let year = $('#invoice_year_select').val();
    window.location = `/analysis/${year}`;
})

$(document).on('click','#invoice-order-export',function(e){
    e.preventDefault();
    $('#export-invoice-page-drop').toggleClass('d-none');
})

// $(document).on('click','.export-invoice-page-item',function(e){
//     e.preventDefault();
//     // let filter_type = parseInt($('#invoice_filter_select').val());
//     let year = $('#invoice_year_select').val();
//     let started_date = year+'0101';
//     let ended_date = year + '1231';
//     // if(filter_type == 1) {
//     //     let daterange = $('input[name="daterange"]').val();
//     //     let dates = daterange.split(' - ');
//     //     started_date = moment(dates[0],'MM-DD-YYYY').format('YYYYMMDD');
//     //     ended_date = moment(dates[1],'MM-DD-YYYY').format('YYYYMMDD');
//     // }

//     $.ajax({
//         type: 'POST',
//         url: '/invoice-export/pdf',
//         dataType: "JSON",
//         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         data: {started_date,ended_date},
//         success: function (res) {
//             Swal.fire({
//                 position: 'center-center',
//                 icon: res.icon,
//                 title: res.title,
//                 text: res.message,
//                 showConfirmButton: !res.success,
//                 timer: res.success ? 2000 : 0,
//             })
//         }
//     });
// })

// function downloadExportAjax(type){
//     let type1 = 2;
//     if(type == 'csv'){
//         type1 = 1;
//     }
//     $.ajax({
//         type: 'POST',
//         url: '/exportInvoiceOrders',
//         dataType: "JSON",
//         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         data: { "type" : type1},
//         success: function (res) {  
//             Swal.fire({
//                 position: 'center-center',
//                 icon: 'success',
//                 title: 'Request Sent',
//                 text: res.message,
//                 showConfirmButton: false,
//                 timer: 2000,
//             })
//         }
//     });  
// }

$(document).on('change','#invoice_year_select',function(e) {
    e.preventDefault();
    let year = $(e.currentTarget).val();
    let val = parseInt($("#invoice-orders-page-filter-count option:selected").val());
    let started_date = year +'-01-01';
    let ended_date = year + '-12-31';
    getInvoiceOrderAjax(0,val,started_date,ended_date)
})


// export request for invoice pdf

// $(document).on('click','#invoice-pdf-export',function(e){
//     e.preventDefault();
//     $.ajax({
//         type: 'POST',
//         url: '/invoice-export/pdf',
//         dataType: "JSON",
//         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         data: { "type" : type1},
//         success: function (res) {  
//             Swal.fire({
//                 position: 'center-center',
//                 icon: 'success',
//                 title: 'Request Sent',
//                 text: res.message,
//                 showConfirmButton: false,
//                 timer: 2000,
//             })
//         }
//     });
// })


// // date range work start
// $('input[name="daterange"]').daterangepicker({
//     locale: {
//         format: 'MM-DD-YYYY'
//     },
    
// },function(start, end) {
//     let start_date = start.format('YYYY-MM-DD');
//     let end_date = end.format('YYYY-MM-DD');
//     console.log(start_date,'__start date',end_date,'__end date');
//     let val = parseInt($("#invoice-orders-page-filter-count option:selected").val());
//     getInvoiceOrderAjax(0,val,start_date,end_date)
//     return false;
// });


// $(document).on('change','#invoice_filter_select',function(){
//     let filter_type = $(this).val();
//     let val = parseInt($("#invoice-orders-page-filter-count option:selected").val());
//     let year = $('#invoice_year_select').val();
//     let started_date = year +'-01-01';
//     let ended_date = year + '-12-31';
//     if(parseInt(filter_type) == 0) {
//         $('#invoice_year_select_label').removeClass('d-none');
//         $('#invoice_date_range').addClass('d-none');
//     } else {
//         $('#invoice_year_select_label').addClass('d-none');
//         $('#invoice_date_range').removeClass('d-none');
//         let daterange = $('input[name="daterange"]').val();
//         let dates = daterange.split(' - ');
//         started_date = moment(dates[0],'MM-DD-YYYY').format('YYYY-MM-DD');
//         ended_date = moment(dates[1],'MM-DD-YYYY').format('YYYY-MM-DD');
//     }
//     getInvoiceOrderAjax(0,val,started_date,ended_date)
// })