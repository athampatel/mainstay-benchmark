let pagecount = parseInt($("#change-order-request-page-filter-count option:selected").val());

//open orders table filter
$(document).on('change','#change-order-request-page-filter-count',function(){
    let val = parseInt($("#change-order-request-page-filter-count option:selected").val());
    getChangeOrderRequestAjax(0,val)
})

getChangeOrderRequestAjax(0,pagecount)

$(document).on('click','.pagination_link',function(e){
    e.preventDefault();
    $page = $(this).data('val');
    $val = parseInt($("#change-order-request-page-filter-count option:selected").val());
    getChangeOrderRequestAjax($page,$val)
})

$('#change-order-request-page-search').keyup(function(){
    change_order_request_page_table.search($(this).val()).draw();
})

let change_order_request_page_table;

function getChangeOrderRequestAjax($page,$count){
    $.ajax({
        type: 'GET',
        url: '/getChangeOrderRequest',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "page" : $page,'count': $count},
        beforeSend:function(){
            beforeChangeOrderRequestAjax();
        },
        success: function (res) {  
            $('#pagination_disp').html(res.pagination_code);
            console.log(res,'___response');
            $('#change-order-request-page-table-div').html(res.table_code);
            change_order_request_page_table = $('#change-order-request-page-table').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength:$count,
                paging: true,
                ordering: true,
                info: false,
                // responsive: true
            });
        },
        complete:function(){
            afterChangeOrderRequestAjax();
        }
    });
}

function beforeChangeOrderRequestAjax(){
    $('.table_loader').removeClass('d-none');
    $('#change-order-request-page-table-div').addClass('d-none');
    $('#pagination_disp').addClass('d-none');
}

function afterChangeOrderRequestAjax(){
    $('.table_loader').addClass('d-none');
    $('#change-order-request-page-table-div').removeClass('d-none');
    $('#pagination_disp').removeClass('d-none');
}