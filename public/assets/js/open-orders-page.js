let pagecount = parseInt($("#open-orders-page-filter-count option:selected").val());
$(document).on('change','#open-orders-page-filter-count',function(){
    let val = parseInt($("#open-orders-page-filter-count option:selected").val());
    getOpenOrderAjax(0,val)
})

getOpenOrderAjax(0,pagecount)

$(document).on('click','.pagination_link',function(e){
    e.preventDefault();
    $page = $(this).data('val');
    $val = parseInt($("#open-orders-page-filter-count option:selected").val());
    getOpenOrderAjax($page,$val)
})

// $('#open-orders-page-search').keyup(function(){
//     let search_word = $(this).val();
//     if(search_word != ''){
//         $('#pagination_disp').addClass('d-none');
//     } else {
//         $('#pagination_disp').removeClass('d-none');
//     }
//     console.log(search_word,'___search word');
//     open_order_page_table.search($(this).val()).draw();
// })
$('#open-orders-page-search').keyup(function(){
    let search_word = $(this).val();
    if(search_word == ''){
        commonOpenOrdersAjaxData();
    }
})


$(document).on('click','#open-orders-page-search-img',function(){
    commonOpenOrdersAjaxData();
});


function commonOpenOrdersAjaxData(){
    let val = parseInt($("#open-orders-page-filter-count option:selected").val());
    getOpenOrderAjax(0,val)
}

let open_order_page_table;

function getOpenOrderAjax($page,$count){
    let search_word = $('#open-orders-page-search').val();
    $.ajax({
        type: 'GET',
        url: '/getOpenOrders',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "page" : $page,'count': $count,search_word,'sorting_dir': $('#sorting_dir').val()},
        beforeSend:function(){
            beforeOpenOrderAjax();
        },
        success: function (res) {  
            $('#pagination_disp').html(res.pagination_code);
            $('#open-orders-page-table-div').html(res.table_code);
            if(res.total_records > parseInt(env_maximum)){
                $('#export-open-csv').prop('src','#');
                $('#export-open-pdf').prop('src','#');
            } else {
                $('#export-open-csv').prop('src','/open-export/csv');
                $('#export-open-pdf').prop('src','/open-export/pdf');
            }
            var sort_dir = $('#sorting_dir').val();
            open_order_page_table = $('#open-orders-page-table').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength:$count,
                paging: true,
                ordering: true,
                info: false,
                order: [[4, sort_dir]],
                autoWidth: false,
                columnDefs: [
                    { targets: [1], width:"16%" },
                    { targets: [7], width:"7%" },
                    { targets: [8], orderable: false},
                ],
                // responsive: true
            });

             open_order_page_table.on('order.dt', function() {
                var orderInfo = open_order_page_table.order();  // Returns array of [columnIndex, direction]
                console.log('Sorting changed:', orderInfo);

                // Example: check first column's sorting
                if (orderInfo.length && orderInfo[0][0] === 4) {  // 0 = first column
                    $('#sorting_dir').val(orderInfo[0][1]);  
                    commonOpenOrdersAjaxData();                
                }
            });
        },
        complete:function(){
            afterOpenOrderAjax();
        }
    });
}

function beforeOpenOrderAjax(){
    $('.table_loader').removeClass('d-none');
    $('#open-orders-page-table-div').addClass('d-none');
    $('#pagination_disp').addClass('d-none');
}

function afterOpenOrderAjax(){
    $('.table_loader').addClass('d-none');
    $('#open-orders-page-table-div').removeClass('d-none');
    $('#pagination_disp').removeClass('d-none');
}


$(document).on('click','#open-order-page-export',function(e){
    e.preventDefault();
    $('#export-open-orders-page-drop').toggleClass('d-none');
})

$(document).on('click','.export-open-orders-page-item',function(e){
    e.preventDefault();
    let src = $(e.currentTarget).prop('src');
    let type = $(e.currentTarget).data('type');
    if(src == '#'){
        downloadExportAjax(type)
    } else {
        window.location = src;
    }
})

function downloadExportAjax(type){
    let type1 = 2;
    if(type == 'csv'){
        type1 = 1;
    }
    $.ajax({
        type: 'POST',
        url: '/exportOpenOrders',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "type" : type1},
        success: function (res) {  
            console.log(res,'__export response');
            Swal.fire({
                position: 'center-center',
                icon: 'success',
                title: 'Request Sent',
                text: res.message,
                showConfirmButton: true,
                // timer: 2000,
            })
        }
    });  
}