// All customers 
if($('#backend_customers').length){

    let start_filter_count =  parseInt($("#admin-customer-filter-count option:selected").val());
    let backend_customers;
    backend_customers = $('#backend_customers').DataTable( {
        searching: true,
        lengthChange: true,
        pageLength:start_filter_count,
        paging: true,
        ordering: true,
        info: false,
        responsive: true
    });
    
    $(document).on('change','#admin-customer-filter-count',function(){
        let val = parseInt($("#admin-customer-filter-count option:selected").val());
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
}

// All roles
if($('#backend_roles').length){

    let roles_filter_count =  parseInt($("#admin-roles-filter-count option:selected").val());
    let backend_roles;
    backend_roles = $('#backend_roles').DataTable( {
        searching: true,
        lengthChange: true,
        pageLength:roles_filter_count,
        paging: true,
        ordering: true,
        info: false,
        responsive: true
    });
    
    $(document).on('change','#admin-roles-filter-count',function(){
        let val = parseInt($("#admin-roles-filter-count option:selected").val());
        let search = $('#admin_roles_search').val();
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#roles_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#roles_from');
        $('#roles_from').submit();
    })
    $('#admin-roles-search-img').click(function(){
        let search = $('#admin_roles_search').val();
        let val = parseInt($("#admin-roles-filter-count option:selected").val());
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#roles_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#roles_from');
        $('#roles_from').submit();
    })
    
    $('#admin_roles_search').keyup(function(){
        let search = $(this).val();
        if(search == ''){
            let val = parseInt($("#admin-roles-filter-count option:selected").val());
            $('<input>').attr({
                type: 'hidden',
                id: 'limit',
                name: 'limit',
                value: val
            }).appendTo('#roles_from');
            $('<input>').attr({
                type: 'hidden',
                id: 'search',
                name: 'search',
                value: search
            }).appendTo('#roles_from');
            $('#roles_from').submit();
        }
    })
}


// all Admins
if($('#backend_admins').length){

    let admins_filter_count =  parseInt($("#admin-admins-filter-count option:selected").val());
    let backend_admins;
    backend_admins = $('#backend_admins').DataTable( {
        searching: true,
        lengthChange: true,
        pageLength:admins_filter_count,
        paging: true,
        ordering: true,
        info: false,
        responsive: true
    });
    
    $(document).on('change','#admin-admins-filter-count',function(){
        let val = parseInt($("#admin-admins-filter-count option:selected").val());
        let search = $('#admin_admins_search').val();
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#admins_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#admins_from');
        $('#admins_from').submit();
    })
    $('#admin-admins-search-img').click(function(){
        let search = $('#admin_admins_search').val();
        let val = parseInt($("#admin-admins-filter-count option:selected").val());
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#admins_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#admins_from');
        $('#admins_from').submit();
    })
    
    $('#admin_admins_search').keyup(function(){
        let search = $(this).val();
        if(search == ''){
            let val = parseInt($("#admin-admins-filter-count option:selected").val());
            $('<input>').attr({
                type: 'hidden',
                id: 'limit',
                name: 'limit',
                value: val
            }).appendTo('#admins_from');
            $('<input>').attr({
                type: 'hidden',
                id: 'search',
                name: 'search',
                value: search
            }).appendTo('#admins_from');
            $('#admins_from').submit();
        }
    })
}

// All managers
if($('#backend_managers').length){

    let managers_filter_count =  parseInt($("#admin-managers-filter-count option:selected").val());
    let backend_managers;
    backend_managers = $('#backend_managers').DataTable( {
        searching: true,
        lengthChange: true,
        pageLength:managers_filter_count,
        paging: true,
        ordering: true,
        info: false,
        responsive: true
    });
    
    $(document).on('change','#admin-managers-filter-count',function(){
        let val = parseInt($("#admin-managers-filter-count option:selected").val());
        let search = $('#admin_managers_search').val();
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#managers_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#managers_from');
        $('#managers_from').submit();
    })
    $('#admin-managers-search-img').click(function(){
        let search = $('#admin_managers_search').val();
        let val = parseInt($("#admin-managers-filter-count option:selected").val());
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#managers_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#managers_from');
        $('#managers_from').submit();
    })
    
    $('#admin_managers_search').keyup(function(){
        let search = $(this).val();
        if(search == ''){
            let val = parseInt($("#admin-managers-filter-count option:selected").val());
            $('<input>').attr({
                type: 'hidden',
                id: 'limit',
                name: 'limit',
                value: val
            }).appendTo('#managers_from');
            $('<input>').attr({
                type: 'hidden',
                id: 'search',
                name: 'search',
                value: search
            }).appendTo('#managers_from');
            $('#managers_from').submit();
        }
    })
}

// All Change Order Requests
if($('#backend_change_order_requests').length){

    let change_order_filter_count =  parseInt($("#admin-change-order-filter-count option:selected").val());
    let backend_change_order;
    backend_change_order = $('#backend_change_order_requests').DataTable( {
        searching: true,
        lengthChange: true,
        pageLength:change_order_filter_count,
        paging: true,
        ordering: true,
        info: false,
        responsive: true
    });
    
    $(document).on('change','#admin-change-order-filter-count',function(){
        let val = parseInt($("#admin-change-order-filter-count option:selected").val());
        let search = $('#admin_change_order_search').val();
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#change_order_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#change_order_from');
        $('#change_order_from').submit();
    })
    $('#admin-change-order-search-img').click(function(){
        let search = $('#admin_change_order_search').val();
        let val = parseInt($("#admin-change-order-filter-count option:selected").val());
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#change_order_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#change_order_from');
        $('#change_order_from').submit();
    })
    
    $('#admin_change_order_search').keyup(function(){
        let search = $(this).val();
        if(search == ''){
            let val = parseInt($("#admin-change-order-filter-count option:selected").val());
            $('<input>').attr({
                type: 'hidden',
                id: 'limit',
                name: 'limit',
                value: val
            }).appendTo('#change_order_from');
            $('<input>').attr({
                type: 'hidden',
                id: 'search',
                name: 'search',
                value: search
            }).appendTo('#change_order_from');
            $('#change_order_from').submit();
        }
    })
}

if($('#backend_signup_request').length){
    // All Signup Requests
    let signup_request_filter_count =  parseInt($("#admin-signup-filter-count option:selected").val());
    let backend_signup_request;
    backend_signup_request = $('#backend_signup_request').DataTable( {
        searching: true,
        lengthChange: true,
        pageLength:signup_request_filter_count,
        paging: true,
        ordering: true,
        info: false,
        responsive: true
    });
    
    $(document).on('change','#admin-signup-filter-count',function(){
        let val = parseInt($("#admin-signup-filter-count option:selected").val());
        let search = $('#admin_signup_search').val();
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#admin_signup_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#admin_signup_from');
        $('#admin_signup_from').submit();
    })
    $('#admin-signup-search-img').click(function(){
        let search = $('#admin_signup_search').val();
        let val = parseInt($("#admin-signup-filter-count option:selected").val());
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#admin_signup_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#admin_signup_from');
        $('#admin_signup_from').submit();
    })
    
    $('#admin_signup_search').keyup(function(){
        let search = $(this).val();
        if(search == ''){
            let val = parseInt($("#admin-signup-filter-count option:selected").val());
            $('<input>').attr({
                type: 'hidden',
                id: 'limit',
                name: 'limit',
                value: val
            }).appendTo('#admin_signup_from');
            $('<input>').attr({
                type: 'hidden',
                id: 'search',
                name: 'search',
                value: search
            }).appendTo('#admin_signup_from');
            $('#admin_signup_from').submit();
        }
    })
}

// export tables

// customer table
$(document).on('click','#admin-customer-report-icon',function(e){
    e.preventDefault();
    $('#export-admin-customers-drop').toggleClass('d-none');
})

$(document).on('click','.export-admin-customer-item',function(){
    $('#export-admin-customers-drop').addClass('d-none');
})

// roles table
$(document).on('click','#admin-roles-report-icon',function(e){
    e.preventDefault();
    $('#export-admin-roles-drop').toggleClass('d-none');
})

$(document).on('click','.export-admin-roles-item',function(){
    $('#export-admin-roles-drop').addClass('d-none');
})

// admins table
$(document).on('click','#admin-admins-report-icon',function(e){
    e.preventDefault();
    $('#export-admin-admins-drop').toggleClass('d-none');
})

$(document).on('click','.export-admin-admins-item',function(){
    $('#export-admin-admins-drop').addClass('d-none');
})

// orders table
$(document).on('click','#admin-customer-orders-icon',function(e){
    e.preventDefault();
    $('#export-admin-orders-drop').toggleClass('d-none');
})

$(document).on('click','.export-admin-orders-item',function(){
    $('#export-admin-orders-drop').addClass('d-none');
})

// managers table
$(document).on('click','#admin-managers-report-icon',function(e){
    e.preventDefault();
    $('#export-admin-managers-drop').toggleClass('d-none');
})

$(document).on('click','.export-admin-managers-item',function(){
    $('#export-admin-managers-drop').addClass('d-none');
})

// signup request page
$(document).on('click','#admin-signup-report-icon',function(e){
    e.preventDefault();
    $('#export-signup-admins-drop').toggleClass('d-none');
})

$(document).on('click','.export-signup-admins-item',function(){
    $('#export-signup-admins-drop').addClass('d-none');
})

if($('#get_inventory_item_details').length){
    $(document).on('click','#get_inventory_item_details',function(e){
        e.preventDefault();
        let company_code  = $('#company_code').val();
        let item_code = $('#inventory_item_code').val();
        $.ajax({
            type: 'POST',
            url: '/admin/getAdminVmiItem',
            dataType: "JSON",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { "ccode" : company_code,'icode': item_code},
            beforeSend:function(){
                $('.backdrop').removeClass('d-none');
            },
            success: function (res) {  
                console.log(res,'___get inventory item details');
                if(res.success){
                    if(res.data.length > 0){
                        displayInventoryItemData(res.data[0])
                    } else {
                        // no item found
                    }
                }
            },
            complete:function(){
                $('.backdrop').addClass('d-none');
            }
        });
    })
    
}
function displayInventoryItemData(item_details){
    $('#ItemCodeDetail').val(item_details.itemcode);
    $('#ItemCodeDesc').val(item_details.itemcodedesc);
    $('#itemProductLine').val(item_details.productline);
    $('#PrimaryVendorNo').val(item_details.vendorno);
    $('#PrimaryAPDivisionNo').val(item_details.apdivisionno);
    $('#qtyInHand').val(item_details.quantityonhand);
    $('#quantityPurchased').val(item_details.quantitypurchased);
    $('.inventory_item-details').removeClass('d-none');
}

$(document).on('click','#update_inventory_items',function(e){
    e.preventDefault();
    let company_code = $('#company_code').val();
    let item_code = $('#ItemCodeDetail').val();
    let user_id = $('#user_id').val();
    let quantity_in_hand = $('#qtyInHand').val();
    let new_quantity_in_hand = $('#newQuantityInHand').val();
    $.ajax({
        type: 'POST',
        url: '/admin/update_inventory_item',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "company_code" : company_code,'item_code': item_code,'user_id' : user_id ,'old_quantity':quantity_in_hand,'new_quantity':new_quantity_in_hand},
        beforeSend:function(){
            $('.backdrop').removeClass('d-none');
        },
        success: function (res) {  
            if(res.success){
                $('#inventory_update_status').addClass('alert-success').removeClass('alert-danger').html(res.message);
            } else {
                $('#inventory_update_status').removeClass('alert-success').addClass('alert-danger').html(res.error);
            }
        },
        complete:function(){
            $('.backdrop').addClass('d-none');
        }
    });
})

// profile upload
$(document).on('click','#admin-nav-profile-detail',function(e){
    e.preventDefault();
    window.location = '/admin/profile';
})

var admin_file_input = document.getElementById("file-input-admin");
$(document).on('click','#file_input_button_admin',function(){
    admin_file_input.click();
})

$(document).on('change','#file-input-admin',function(e){
    $image = $('#file-input-admin').prop('files')[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        $('.profile_img_disp_admin').attr('src', e.target.result);
    }
    reader.readAsDataURL($image);
});


// 
$(document).on('click','#admin-profile-edit-save-button',function(e){
    e.preventDefault();
    var formData = new FormData();
    $image = $('#file-input-admin').prop('files')[0];
    formData.append('photo_1', $image);
    $.ajax({
        type: 'POST',
        url: '/admin/profile-save',
        contentType: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: formData,
        beforeSend:function(){
            $('.backdrop').removeClass('d-none');
        },
        success: function (res) {
            let res1 = JSON.parse(res);
            if(res1.success){
                $("#nav-bar-profile-img_admin").prop("src", res1.data.path);
                $("#nav-bar-profile-img_admin").prop("height", 45);
                $("#nav-bar-profile-img_admin").prop("width", 45);
                $('#display_profile_upload_msg').removeClass('d-none');
                $('#display_profile_upload_msg').addClass('alert-success').removeClass('alert-danger').html(res1.data.message);
                setTimeout(() => {
                    $('#display_profile_upload_msg').addClass('d-none');
                }, 2000);
            } else {
                // update fail
            }
        },
        complete:function(){
            $('.backdrop').addClass('d-none');
        }
    });
})

// common print function
$(document).on('click','.datatable-print.admin a',function(e){
    e.preventDefault();
    window.print();
})


// admin customer request toggle
$(document).on('change','.customer_mult_check',function(e){
    e.preventDefault();
    let value = $(e.currentTarget).val();
    let accord_name = `#create_user_body_${value}`;
    let accord_header_name = `#customer_header_${value}`;
    if($(e.currentTarget).is(':checked')){
        $(accord_name).removeClass('d-none');
        $(accord_header_name).css("background-color", "#9FCC47");
        $(accord_header_name+' .customer_header_icon').css("transform", "rotate(180deg)");
    } else {
        $(accord_name).addClass('d-none');
        $(accord_header_name).css("background-color", "#7B7C7F");
        $(accord_header_name+' .customer_header_icon').css("transform", "rotate(0deg)");
    }
})