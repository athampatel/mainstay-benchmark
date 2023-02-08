console.log('__open orders page.js')
if ($('#dataTable').length) {
    $('#dataTable').DataTable({
        responsive: true
    });
}
let pagecount = parseInt($("#open-orders-page-filter-count option:selected").val());
const open_order_page_table = $('#open-orders-page-table').DataTable( {
    searching: true,
    lengthChange: true,
    pageLength:pagecount,
    paging: true,
    ordering: false,
    info: false,
});

// open orders table search
$('#open-orders-page-search').keyup(function(){
    open_order_page_table.search($(this).val()).draw() ;
})

//open orders table filter
$(document).on('change','#open-orders-page-filter-count',function(){
    let val = parseInt($("#open-orders-page-filter-count option:selected").val());
    open_order_page_table.page.len(val).draw();
})