// datatable 
let pagecount = parseInt($("#admin-managers-customers-filter-count option:selected").val());
//open orders table filter
$(document).on('change','#admin-managers-customers-filter-count',function(){
    let val = parseInt($("#admin-managers-customers-filter-count option:selected").val());
    getManagerCustomers(0,val)
})
getManagerCustomers(0,pagecount)
$(document).on('click','.pagination_link',function(e){
    e.preventDefault();
    $page = $(this).data('val');
    $val = parseInt($("#admin-managers-customers-filter-count option:selected").val());
    getManagerCustomers($page,$val)
})
$('#admin_managers_customers_search').keyup(function(){
    backend_manager_customers_table.search($(this).val()).draw();
})

let backend_manager_customers_table;

function getManagerCustomers($page,$count){
    let sales_person_number  = $('#sales_person_number').val();
    $.ajax({
        type: 'GET',
        url: '/admin/manager/customers',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "page" : $page,'count': $count,'sales_person_number':sales_person_number},
        beforeSend:function(){
            // beforeChangeOrderAjax();
        },
        success: function (res) {  
            console.log(res,'__response');
            $('#pagination_disp').html(res.pagination_code);
            $('.backend_manager_customers_display').html(res.table_code);    
            backend_manager_customers_table = $('#backend_manager_customers').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength:$count,
                paging: true,
                ordering: true,
                info: false,
                responsive: true,
                autoWidth: false,
                columns: [
                    { "width": "25%" },
                    { "width": "25%" },
                    { "width": "25%" },
                    { "width": "25%" },
                  ]
            });
        },
        complete:function(){
            // afterChangeOrderAjax();
        }
    });
}