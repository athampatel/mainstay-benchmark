console.log('vmi page.js')
if ($('#dataTable').length) {
    $('#dataTable').DataTable({
        responsive: true
    });
}
let pagecount = parseInt($("#vmi-page-filter-count option:selected").val());

$(document).on('change','#vmi-page-filter-count',function(){
    let val = parseInt($("#vmi-page-filter-count option:selected").val());
    getVmiDataAjax(0,val)
})
getVmiDataAjax(0,pagecount)

$(document).on('click','.pagination_link',function(e){
    e.preventDefault();
    $page = $(this).data('val');
    $val = parseInt($("#vmi-page-filter-count option:selected").val());
    getVmiDataAjax($page,$val)
})
let vmi_page_table;

$('#vmi-page-search').keyup(function(){
    vmi_page_table.search($(this).val()).draw();
})

function getVmiDataAjax($page,$count){
    $.ajax({
        type: 'GET',
        url: '/getVmiData',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "page" : $page,'count': $count},
        beforeSend:function(){
            // $('.page-table-loader-div').removeClass('d-none');
            // $('.open-orders .card.box').addClass('active');
            beforeVmiOrderAjax();
        },
        success: function (res) {  
            console.log(res,'___Response vmi');
            // $('#pagination_disp').html(res.pagination_code);
            $('#pagination_disp').html(res.pagination_code);
            $('#vmi_table_disp').html(res.table_code);
            vmi_page_table = $('#vmi-page-table').DataTable( {
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
            // $('.open-orders .card.box').removeClass('active');
            // $('.page-table-loader-div').addClass('d-none');
            afterVmiOrderAjax();
        }
    });
}

function beforeVmiOrderAjax(){
    $('.table_loader').removeClass('d-none');
    $('#vmi_table_disp').addClass('d-none');
    $('#pagination_disp').addClass('d-none');
}

function afterVmiOrderAjax(){
    $('.table_loader').addClass('d-none');
    $('#vmi_table_disp').removeClass('d-none');
    $('#pagination_disp').removeClass('d-none');
}