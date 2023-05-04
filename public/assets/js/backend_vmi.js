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
    // bottom vmi post count button
    $('#vmi-post-count-bottom').removeClass('d-none')

    // open all the toggle buttons
    let vmi_rows = $('td.sorting_1');
    vmi_rows_array = vmi_rows.toArray();
    vmi_rows_array.forEach(vr => {
        if(!$(vr).parent().hasClass('parent')){
            $(vr).trigger('click')
        }
    })
    $('.quantity_counted').prop('disabled',false);
})
// $(document).on('click','#vmi_inventory_save',function(e){
//     e.preventDefault();
//     $('#vmi_inventory_save').addClass('d-none')
//     $('#vmi_inventory_cancel').addClass('d-none')
//     $('#vmi_inventory_edit').removeClass('d-none')
//     $('.quantity_counted').prop('disabled',true);

//     let company_code  = $('#vmi_company_code').val();
//     let user_detail_id = $('#user_detail_id').val();
//     if(change_items.length = 0){
//         $('#vmi_inventory_message').html(constants.vmi_inventory.no_change).removeClass('alert-success').addClass('alert-danger').removeClass('d-none');
//         return false;
//     }
//     let changes = Object.assign({}, change_items)
//     $.ajax({
//         type: 'POST',
//         url: '/admin/saveAdminVmiData',
//         dataType: "JSON",
//         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         data: { "vmi_changes": JSON.stringify(changes),"user_detail_id" : user_detail_id,'company_code': company_code},
//         beforeSend:function(){
//             $('.backdrop').removeClass('d-none');
//         },
//         success: function (res) {  
//             change_items = [];
//             $('.quantity_counted').val('');
//             if(res.success){
//                 $('#vmi_inventory_message').html(res.message).addClass('alert-success').removeClass('alert-danger').removeClass('d-none');
//             }
//         },
//         complete:function(){
//             $('.backdrop').addClass('d-none');
//         }
//     });

// })
$(document).on('click','#vmi_inventory_save',function(e){
    e.preventDefault();
    vmiPostCountSave();
});

$(document).on('click','#vmi-post-count-bottom',function(e){
    e.preventDefault();
    vmiPostCountSave();
});

function vmiPostCountSave(){
    $('#vmi_inventory_save').addClass('d-none')
    $('#vmi-post-count-bottom').addClass('d-none')
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
            // close all the toggle opens
            let vmi_rows = $('td.sorting_1');
            vmi_rows_array = vmi_rows.toArray();
            vmi_rows_array.forEach(vr => {
                if($(vr).parent().hasClass('parent')){
                    $(vr).trigger('click')
                }
            })
            change_items = [];
            $('.quantity_counted').val('');
        }
    });
}

$(document).on('click','#vmi_inventory_cancel',function(e){
    e.preventDefault();
    $('#vmi_inventory_save').addClass('d-none');
    $('#vmi_inventory_cancel').addClass('d-none');
    $('#vmi_inventory_edit').removeClass('d-none');
    $('#vmi-post-count-bottom').addClass('d-none');
    $('.quantity_counted').prop('disabled',true);
    // close all the opened target buttons
    let vmi_rows = $('td.sorting_1');
    vmi_rows_array = vmi_rows.toArray();
    vmi_rows_array.forEach(vr => {
        if($(vr).parent().hasClass('parent')){
            $(vr).trigger('click')
        }
    })
    change_items = [];
    $('.quantity_counted').val('');

})

// single toggle opening
$(document).on('click','td.sorting_1',function(e){
    let target_row = $(e.currentTarget).parent().next()
    if($('#vmi_inventory_save').hasClass('d-none')){
        target_row.find('.quantity_counted').prop('disabled',true);
    } else {
        target_row.find('.quantity_counted').prop('disabled',false);
    }
})

// open all the toggle button in datatable

// $('#toggleAll').on('click', function() {
    var responsiveToggleButtons = $('.dtr-details button');
    var openToggleButtons = responsiveToggleButtons.filter('.dtr-details-show');

    // check if there are any open toggle buttons
    if (openToggleButtons.length > 0) {
        // close all open toggle buttons
        openToggleButtons.trigger('click');
    } else {
        // open all closed toggle buttons
        responsiveToggleButtons.trigger('click');
    }
// });
// bottom vmi post count button
// $(document).on('click','#vmi_inventory_cancel',function(e){
//     e.preventDefault();
//     $('#vmi_inventory_save').addClass('d-none')
//     $('#vmi_inventory_cancel').addClass('d-none')
//     $('#vmi_inventory_edit').removeClass('d-none')
//     $('.quantity_counted').prop('disabled',true);
//     change_items = [];
//     $('.quantity_counted').val('');

// })

// on change data field
$(document).on('keyup','.quantity_counted',function(e){
    e.preventDefault();
    let itemcode = $(e.currentTarget).data('itemcode');
    let change_value = $(e.currentTarget).val();
    let old_quantity = 0;
    let current_row = $(e.currentTarget).closest('tr');
    if(current_row.hasClass('child')){
        old_quantity = current_row.prev().find('.qty_hand').data('val');
    } else {
        old_quantity = $(e.currentTarget).closest('.vmi_row').find('.qty_hand').data('val');
    }
    console.log(old_quantity,'__old quantity');
    let change = { 'old_qty' : old_quantity,'new_qty' : change_value};
    if(change_value == ''){
        delete change_items[itemcode];
    } else {
        change_items[itemcode] = change;
    }
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
    let count = $('#ignore_counts').val();
    $.ajax({
        type: 'GET',
        url: '/admin/getAdminVmiData',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "page" : $page,'count': $count,'user_detail_id':user_detail_id,'company_code':company_code,'ignores' : count},
        beforeSend:function(){
            // beforeChangeOrderAjax();
        },
        success: function (res) {  
            $('#pagination_disp').html(res.pagination_code);
            $('#vmi_inventory_table_disp').html(res.table_code);
            $('#ignore_counts').val(res.count);
            $('#vmi_inventory_edit').removeClass('d-none');
            $('#vmi_inventory_save').addClass('d-none');
            $('#vmi_inventory_cancel').addClass('d-none');
            vmi_inventory_page_table = $('#vmi-inventory-page-table').DataTable( {
                searching: true,
                lengthChange: true,
                pageLength:$count,
                paging: true,
                ordering: true,
                info: false,
                responsive: true,
                autoWidth: false,
                columns: [
                    { "width": "14%" },
                    { "width": "14%" },
                    { "width": "14%" },
                    { "width": "14%" },
                    { "width": "14%" },
                    { "width": "14%" },
                    { "width": "14%" },
                  ]
            });
        },
        complete:function(){
            // afterChangeOrderAjax();
        }
    });
}