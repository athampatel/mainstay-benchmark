console.log('vmi page.js')
if ($('#dataTable').length) {
    $('#dataTable').DataTable({
        responsive: true
    });
}
let pagecount = parseInt($("#vmi-page-filter-count option:selected").val());
const vmi_page_table = $('#vmi-page-table').DataTable( {
    searching: true,
    lengthChange: true,
    pageLength:pagecount,
    paging: true,
    ordering: false,
    info: false,
});

// open orders table search
$('#vmi-page-search').keyup(function(){
    vmi_page_table.search($(this).val()).draw() ;
})

//open orders table filter
$(document).on('change','#vmi-page-filter-count',function(){
    let val = parseInt($("#vmi-page-filter-count option:selected").val());
    vmi_page_table.page.len(val).draw();
})