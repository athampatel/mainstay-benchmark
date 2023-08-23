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

// $('#vmi-page-search').keyup(function(){
//     let search_word = $(this).val();
//     if(search_word != ''){
//         $('#pagination_disp').addClass('d-none');
//     } else {
//         $('#pagination_disp').removeClass('d-none');
//     }
//     vmi_page_table.search($(this).val()).draw();
// })

$('#vmi-page-search').keyup(function(){
    let search_word = $(this).val();
    if(search_word == ''){
        commonVmiAjaxData();
    } 
})

$(document).on('click','#vmi-page-search-img',function(){
    commonVmiAjaxData();
});

function commonVmiAjaxData(){
    let val = parseInt($("#vmi-page-filter-count option:selected").val());
    getVmiDataAjax(0,val)
}

function getVmiDataAjax($page,$count){
    let search_word = $('#vmi-page-search').val();
    $.ajax({
        type: 'GET',
        url: '/getVmiData',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "page" : $page,'count': $count,search_word},
        beforeSend:function(){
            beforeVmiOrderAjax();
        },
        success: function (res) {  
            $('#pagination_disp').html(res.pagination_code);
            $('#vmi_table_disp').html(res.table_code);
            
            if(res.total_records > parseInt(env_maximum)){
                $('#vmi-page-export-csv').prop('src','#');
                $('#vmi-page-export-pdf').prop('src','#');
            } else {
                $('#vmi-page-export-csv').prop('src','/vmi-page-export/csv');
                $('#vmi-page-export-pdf').prop('src','/vmi-page-export/pdf');
            }

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

// export 
$(document).on('click','.vmi-datatable-export',function(e){
    e.preventDefault();
    $('#export-vmi-page-drop').toggleClass('d-none');
})

$(document).on('click','.export-vmi-page-item',function(e){
    e.preventDefault();
    let src = $(e.currentTarget).prop('src');
    let type = $(e.currentTarget).data('type');
    if(src == '#'){
        downloadExportAjax(type)
    } else {
        window.location = src;
    }
})

//export vmi request
function downloadExportAjax(type){
    let type1 = 2;
    if(type == 'csv'){
        type1 = 1;
    }
    $.ajax({
        type: 'POST',
        url: '/exportVmiUser',
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
                showConfirmButton: false,
                timer: 2000,
            })
        }
    });  
}