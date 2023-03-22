// if($('#get_inventory_item_details').length){    
// }

// getUserVmiData();

// function getUserVmiData(){
//     let company_code  = $('#vmi_company_code').val();
//     let user_detail_id = $('#user_detail_id').val();
//     $.ajax({
//         type: 'GET',
//         url: '/admin/getAdminVmiData',
//         dataType: "JSON",
//         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         data: { "user_detail_id" : user_detail_id,'company_code': company_code},
//         beforeSend:function(){
//             $('.backdrop').removeClass('d-none');
//         },
//         success: function (res) {  
//             console.log(res,'___get inventory item details');
//             // return false;
//             if(res.success){
//                 $('#vmi_inventory_table_disp').html(res.table_code);
//                 $('#pagination_disp').html(res.pagination_code);
//             }
//         },
//         complete:function(){
//             $('.backdrop').addClass('d-none');
//         }
//     });
// }

$(document).on('click','#vmi_inventory_edit',function(e){
    e.preventDefault();
    $('#vmi_inventory_save').removeClass('d-none')
    $('#vmi_inventory_cancel').removeClass('d-none')
    $('#vmi_inventory_edit').addClass('d-none')
    $('.quantity_counted').prop('disabled',false);
})
$(document).on('click','#vmi_inventory_save',function(e){
    e.preventDefault();
    $('#vmi_inventory_save').addClass('d-none')
    $('#vmi_inventory_cancel').addClass('d-none')
    $('#vmi_inventory_edit').removeClass('d-none')
    $('.quantity_counted').prop('disabled',true);

    let company_code  = $('#vmi_company_code').val();
    let user_detail_id = $('#user_detail_id').val();
    if(change_items.length = 0){
        $('#vmi_inventory_message').html(constants.vmi_inventory.no_change).removeClass('alert-success').addClass('alert-danger').removeClass('d-none');
        return false;
    }
    let changes = Object.assign({}, change_items)
    $.ajax({
        type: 'POST',
        url: '/admin/saveAdminVmiData',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "vmi_changes": JSON.stringify(changes),"user_detail_id" : user_detail_id,'company_code': company_code},
        beforeSend:function(){
            $('.backdrop').removeClass('d-none');
        },
        success: function (res) {  
            change_items = [];
            $('.quantity_counted').val('');
            if(res.success){
                $('#vmi_inventory_message').html(res.message).addClass('alert-success').removeClass('alert-danger').removeClass('d-none');
            }
        },
        complete:function(){
            $('.backdrop').addClass('d-none');
        }
    });

})
$(document).on('click','#vmi_inventory_cancel',function(e){
    e.preventDefault();
    $('#vmi_inventory_save').addClass('d-none')
    $('#vmi_inventory_cancel').addClass('d-none')
    $('#vmi_inventory_edit').removeClass('d-none')
    $('.quantity_counted').prop('disabled',true);
    change_items = [];
    $('.quantity_counted').val('');

})

// on change data field
$(document).on('change','.quantity_counted',function(e){
    e.preventDefault();
    let itemcode = $(e.currentTarget).data('itemcode');
    let change_value = $(e.currentTarget).val();
    let old_quantity = $(e.currentTarget).closest('.vmi_row').find('.qty_hand').data('val');
    let change = { 'old_qty' : old_quantity,'new_qty' : change_value};
    change_items[itemcode] = change;
    console.log(change_items,'__new change items');
})


// datatable 

let pagecount = parseInt($("#admin-vmi-filter-count option:selected").val());
//open orders table filter
$(document).on('change','#admin-vmi-filter-count',function(){
    let val = parseInt($("#admin-vmi-filter-count option:selected").val());
    getVmiInventoryAjax(0,val)
})
getVmiInventoryAjax(0,pagecount)
$(document).on('click','.pagination_link',function(e){
    e.preventDefault();
    $page = $(this).data('val');
    $val = parseInt($("#admin-vmi-filter-count option:selected").val());
    getVmiInventoryAjax($page,$val)
})
$('#vmi-inventory-search').keyup(function(){
    vmi_inventory_page_table.search($(this).val()).draw();
})

let vmi_inventory_page_table;

function getVmiInventoryAjax($page,$count){
    let company_code  = $('#vmi_company_code').val();
    let user_detail_id = $('#user_detail_id').val();
    $.ajax({
        type: 'GET',
        url: '/admin/getAdminVmiData',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "page" : $page,'count': $count,'user_detail_id':user_detail_id,'company_code':company_code},
        beforeSend:function(){
            // beforeChangeOrderAjax();
        },
        success: function (res) {  
            $('#pagination_disp').html(res.pagination_code);
            $('#vmi_inventory_table_disp').html(res.table_code);
            vmi_inventory_page_table = $('#vmi-inventory-page-table').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength:$count,
                paging: true,
                ordering: true,
                info: false,
                responsive: true
            });
        },
        complete:function(){
            // afterChangeOrderAjax();
        }
    });
}