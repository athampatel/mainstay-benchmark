// // open_order_page_table.page.len(val).draw();
let start_filter_count =  parseInt($("#admin-customer-filter-count option:selected").val());
let backend_customers;
backend_customers = $('#backend_customers').DataTable( {
    searching: true,
    lengthChange: true,
    pageLength:start_filter_count,
    paging: true,
    ordering: false,
    info: false,
});

$(document).on('change','#admin-customer-filter-count',function(){
    let val = parseInt($("#admin-customer-filter-count option:selected").val());
    // backend_customers.page.len(val).draw();
    let search = $('#admin_customer_search').val();
    $('<input>').attr({
        type: 'hidden',
        id: 'limit',
        name: 'limit',
        value: val
    }).appendTo('#customer_from');
    $('<input>').attr({
        type: 'hidden',
        id: 'search',
        name: 'search',
        value: search
    }).appendTo('#customer_from');
    $('#customer_from').submit();
})
$('#admin-customer-search-img').click(function(){
    let search = $('#admin_customer_search').val();
    let val = parseInt($("#admin-customer-filter-count option:selected").val());
    $('<input>').attr({
        type: 'hidden',
        id: 'limit',
        name: 'limit',
        value: val
    }).appendTo('#customer_from');
    $('<input>').attr({
        type: 'hidden',
        id: 'search',
        name: 'search',
        value: search
    }).appendTo('#customer_from');
    $('#customer_from').submit();
})

$('#admin_customer_search').keyup(function(){
    let search = $(this).val();
    if(search == ''){
        let val = parseInt($("#admin-customer-filter-count option:selected").val());
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#customer_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#customer_from');
        $('#customer_from').submit();
    }
})

// getAllCustomers();

// function getAllCustomers($search,$page,$count){
//     $.ajax({
//         type: 'GET',
//         url: '/admin/getAllCustomers',
//         dataType: "JSON",
//         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         data: { "page" : $page,'count': $count,'search':$search},
//         beforeSend:function(){
//             // $('.page-table-loader-div').removeClass('d-none');
//             // $('.open-orders .card.box').addClass('active');
//         },
//         success: function (res) {  
//             $('.pagination_disp').html(res.pagination_code);
//             console.log(res,'___response');
//             $('.table_disp').html(res.table_code);
//             /* data table generate */
//             backend_customers = $('#backend_customers').DataTable( {
//                 searching: true,
//                 lengthChange: true,
//                 pageLength:5,
//                 paging: true,
//                 ordering: false,
//                 info: false,
//             });
//         },
//         complete:function(){
//             // $('.open-orders .card.box').removeClass('active');
//             // $('.page-table-loader-div').addClass('d-none');
//         }
//     });
// }