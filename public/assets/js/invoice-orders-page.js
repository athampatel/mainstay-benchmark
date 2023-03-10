console.log('__invoice orders page.js')
if ($('#dataTable').length) {
    $('#dataTable').DataTable({
        responsive: true
    });
}
let pagecount = parseInt($("#invoice-orders-page-filter-count option:selected").val());
//open orders table filter
$(document).on('change','#invoice-orders-page-filter-count',function(){
    let val = parseInt($("#invoice-orders-page-filter-count option:selected").val());
    // let current_page = $('#current_page').val();    
    getInvoiceOrderAjax(0,val)
    // open_order_page_table.page.len(val).draw();
})
getInvoiceOrderAjax(0,pagecount)
$(document).on('click','.pagination_link',function(e){
    e.preventDefault();
    $page = $(this).data('val');
    $val = parseInt($("#invoice-orders-page-filter-count option:selected").val());
    getInvoiceOrderAjax($page,$val)
})
$('#invoice-orders-page-search').keyup(function(){
    open_order_page_table.search($(this).val()).draw();
})

let open_order_page_table;

function getInvoiceOrderAjax($page,$count){
    
    if(typeof(recent_orders) != 'undefined')
        return false;
    
    return false;
    $.ajax({
        type: 'GET',
        url: '/getInvoiceOrders',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "page" : $page,'count': $count},
        beforeSend:function(){
            $('.page-table-loader-div').removeClass('d-none');
            $('.open-orders .card.box').addClass('active');
        },
        success: function (res) {  
            $('#pagination_disp').html(res.pagination_code);
            console.log(res,'___response');
            $('#invoice-orders-page-table-div').html(res.table_code);
            /* data table generate */
            open_order_page_table = $('#invoice-orders-page-table').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength:pagecount,
                paging: true,
                ordering: false,
                info: false,
                // responsive: true
            });
        },
        complete:function(){
            // $('.backdrop').addClass('d-none');
            $('.open-orders .card.box').removeClass('active');
            $('.page-table-loader-div').addClass('d-none');
        }
    });
}