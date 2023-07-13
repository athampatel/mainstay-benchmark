let pagecount = parseInt($("#invoice-orders-page-filter-count option:selected").val());

$(document).on('change','#invoice-orders-page-filter-count',function(){
    let val = parseInt($("#invoice-orders-page-filter-count option:selected").val());
    let selected_year = $('#invoice_year_select').val();
    let started_date = selected_year +'-01-01';
    let ended_date = selected_year + '-12-31';
    getInvoiceOrderAjax(0,val,started_date,ended_date)
})
let selected_year = $('#invoice_year_select').val();
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
    getInvoiceOrderAjax($page,$val,started_date,ended_date)
})
$('#invoice-orders-page-search').keyup(function(){
    let search_word = $(this).val();
    if(search_word != ''){
        $('#pagination_disp').addClass('d-none');
    } else {
        $('#pagination_disp').removeClass('d-none');
    }
    open_order_page_table.search($(this).val()).draw();
})

let open_order_page_table;

function getInvoiceOrderAjax($page,$count,start_date,end_date){
    $.ajax({
        type: 'GET',
        url: '/getInvoiceOrders',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "page" : $page,'count': $count,'start_date':start_date,'end_date':end_date},
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
        
            open_order_page_table = $('#invoice-orders-page-table').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength:$count,
                paging: true,
                ordering: true,
                info: false,
                order: []
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

// $(document).on('click','#invoice-order-export',function(e){
//     e.preventDefault();
//     window.location = '/analysis/2022'
// })

$(document).on('click','#invoice-order-export',function(e){
    e.preventDefault();
    $('#export-invoice-page-drop').toggleClass('d-none');
})

$(document).on('click','.export-invoice-page-item',function(e){
    e.preventDefault();
    let year = $('#invoice_year_select').val();
    $.ajax({
        type: 'POST',
        url: '/invoice-export/pdf',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { year },
        success: function (res) {
            Swal.fire({
                position: 'center-center',
                icon: res.icon,
                title: res.title,
                text: res.message,
                showConfirmButton: !res.success,
                timer: res.success ? 2000 : 0,
            })
        }
    });
})

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