// All customers
const wm_card = document.querySelector('.wm_card');
if(wm_card){
    setTimeout(() => {
        wm_card.classList.add('d-none');
    }, 5000);
}

// if($('#backend_customers').length){

    let start_filter_count =  parseInt($("#admin-customer-filter-count option:selected").val());
    let backend_customers;
    // backend_customers = $('#backend_customers').DataTable( {
    //     searching: true,
    //     lengthChange: true,
    //     pageLength:start_filter_count,
    //     paging: true,
    //     ordering: false,
    //     info: false,
    //     responsive: true,
    //     autoWidth: false,
    //     columns: [
    //         { "width": "12%" },
    //         { "width": "12%" },
    //         { "width": "12%" },
    //         { "width": "12%" },
    //         { "width": "12%" },
    //         { "width": "12%" },
    //         { "width": "12%" },
    //         { "width": "12%",}
    //     ]
    // });
    // backend_customers = $('#backend_customers').DataTable( {
    //     responsive: true,  
    // });

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

        console.log('__Count changed');
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
    
    $('#admin_customer_search').keyup(function(e){
        let is_pressed = false;
        if(e.key == 'Enter' && e.keyCode == 13) {
            is_pressed = true;
        }
        let search = $(this).val();
        if(search == '' || is_pressed){
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
// }

// customer sorting
function customers_submit(order,order_type){
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
        id: 'srorder',
        name: 'srorder',
        value: order
    }).appendTo('#customer_from');

    $('<input>').attr({
        type: 'hidden',
        id: 'search',
        name: 'search',
        value: search
    }).appendTo('#customer_from');
    
    $('<input>').attr({
        type: 'hidden',
        id: 'ortype',
        name: 'ortype',
        value: order_type
    }).appendTo('#customer_from');

    $('#customer_from').submit();
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
        ordering: false,
        info: false,
        responsive: true,
        autoWidth: false,
        "fnInitComplete": function (oSettings, json) {           
            renderDataTableView();
        },
        columns: [
            { "width": "7%" },
            { "width": "25%" },
            { "width": "42%" },
            { "width": "25%" },
        ]
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
    
    $('#admin_roles_search').keyup(function(e){
        let is_pressed = false;
        if(e.key == 'Enter' && e.keyCode == 13) {
            is_pressed = true;
        }
        let search = $(this).val();
        if(search == '' || is_pressed){
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

// roles sorting
function roles_submit(order,order_type){
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
    
    $('<input>').attr({
        type: 'hidden',
        id: 'ortype',
        name: 'ortype',
        value: order_type
    }).appendTo('#roles_from');

    $('<input>').attr({
        type: 'hidden',
        id: 'srorder',
        name: 'srorder',
        value: order
    }).appendTo('#roles_from');

    $('#roles_from').submit();
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
        ordering: false,
        info: false,
        responsive: true,
        autoWidth: false,
        "fnInitComplete": function (oSettings, json) {           
            renderDataTableView();
        },
        columns: [
            { "width": "7%" },
            { "width": "14%" },
            { "width": "14%" },
            { "width": "14%" },
            { "width": "14%" },
            { "width": "21%" },
            { "width": "14%" }
        ]
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
    
    $('#admin_admins_search').keyup(function(e){
        let is_pressed = false;
        if(e.key == 'Enter' && e.keyCode == 13) {
            is_pressed = true;
        } 
        let search = $(this).val();
        if(search == ''|| is_pressed){
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

// admins sorting
function admins_submit(order,order_type){
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
    
    $('<input>').attr({
        type: 'hidden',
        id: 'srorder',
        name: 'srorder',
        value: order
    }).appendTo('#admins_from');
    
    $('<input>').attr({
        type: 'hidden',
        id: 'ortype',
        name: 'ortype',
        value: order_type
    }).appendTo('#admins_from');

    $('#admins_from').submit(); 
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
        ordering: false,
        info: false,
        responsive: true,
        autoWidth: false,
        columns: [
            { "width": "20%" },
            { "width": "20%" },
            { "width": "20%" },
            { "width": "20%" },
            { "width": "20%" },
        ]
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
    
    $('#admin_managers_search').keyup(function(e){
        let is_pressed = false;
        if(e.key == 'Enter' && e.keyCode == 13) {
            is_pressed = true;
        }
        let search = $(this).val();
        if(search == '' || is_pressed){
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

//managers sorting

function managers_submit(order,order_type){
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
   
    $('<input>').attr({
        type: 'hidden',
        id: 'ortype',
        name: 'ortype',
        value: order_type
    }).appendTo('#managers_from');

    $('<input>').attr({
        type: 'hidden',
        id: 'srorder',
        name: 'srorder',
        value: order
    }).appendTo('#managers_from');

    $('#managers_from').submit();
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
        ordering: false,
        info: false,
        responsive: true,
        autoWidth: false,
        "fnInitComplete": function (oSettings, json) {           
            renderDataTableView();
        },
        columns: [
            { "width": "16%" },
            { "width": "16%" },
            { "width": "16%" },
            { "width": "16%" },
            { "width": "16%" },
            { "width": "16%" },
        ]
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
    
    $('#admin_change_order_search').keyup(function(e){
        let is_pressed = false;
        if(e.key == 'Enter' && e.keyCode == 13) {
            is_pressed = true;
        }
        let search = $(this).val();
        if(search == ''|| is_pressed){
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

// change orders sort
function change_order_sort(order,order_type){
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
    
    $('<input>').attr({
        type: 'hidden',
        id: 'ortype',
        name: 'ortype',
        value: order_type
    }).appendTo('#change_order_from');

    $('<input>').attr({
        type: 'hidden',
        id: 'srorder',
        name: 'srorder',
        value: order
    }).appendTo('#change_order_from');
    
    $('#change_order_from').submit();
}
// admin exports
if($('#backend_export_requests').length){
    // All Signup Requests
    let admin_export_filter_count =  parseInt($("#admin-exports-filter-count option:selected").val());
    let backend_admin_exports;
    backend_admin_exports = $('#backend_export_requests').DataTable( {
        searching: true,
        lengthChange: true,
        pageLength:admin_export_filter_count,
        paging: true,
        ordering: false,
        info: false,
        responsive: true,
        autoWidth: false,
        "fnInitComplete": function (oSettings, json) {           
            renderDataTableView();
        },
        columns: [
            { "width": "20%" },
            { "width": "20%" },
            { "width": "20%" },
            { "width": "20%" },
            { "width": "20%" },
        ]
    });
    
    $(document).on('change','#admin-exports-filter-count',function(){
        let val = parseInt($("#admin-exports-filter-count option:selected").val());
        let search = $('#admin_exports_search').val();
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#admins_exports_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#admins_exports_from');
        $('#admins_exports_from').submit();
    })
    $('#admin-exports-search-img').click(function(){
        let search = $('#admin_exports_search').val();
        let val = parseInt($("#admin-exports-filter-count option:selected").val());
        $('<input>').attr({
            type: 'hidden',
            id: 'limit',
            name: 'limit',
            value: val
        }).appendTo('#admins_exports_from');
        $('<input>').attr({
            type: 'hidden',
            id: 'search',
            name: 'search',
            value: search
        }).appendTo('#admins_exports_from');
        $('#admins_exports_from').submit();
    })
    
    $('#admin_exports_search').keyup(function(e){
        let is_pressed = false;
        if(e.key == 'Enter' && e.keyCode == 13) {
            is_pressed = true;
        }
        let search = $(this).val();
        if(search == ''|| is_pressed){
            let val = parseInt($("#admin-exports-filter-count option:selected").val());
            $('<input>').attr({
                type: 'hidden',
                id: 'limit',
                name: 'limit',
                value: val
            }).appendTo('#admins_exports_from');
            $('<input>').attr({
                type: 'hidden',
                id: 'search',
                name: 'search',
                value: search
            }).appendTo('#admins_exports_from');
            $('#admins_exports_from').submit();
        }
    })
}

function exports_sorting(order,order_type){
    let val = parseInt($("#admin-exports-filter-count option:selected").val());
    let search = $('#admin_exports_search').val();
    $('<input>').attr({
        type: 'hidden',
        id: 'limit',
        name: 'limit',
        value: val
    }).appendTo('#admins_exports_from');
    $('<input>').attr({
        type: 'hidden',
        id: 'search',
        name: 'search',
        value: search
    }).appendTo('#admins_exports_from');
    $('<input>').attr({
        type: 'hidden',
        id: 'ortype',
        name: 'ortype',
        value: order_type
    }).appendTo('#admins_exports_from');

    $('<input>').attr({
        type: 'hidden',
        id: 'srorder',
        name: 'srorder',
        value: order
    }).appendTo('#admins_exports_from');
    $('#admins_exports_from').submit();
}

// sign up requests
if($('#backend_signup_request').length){
    // All Signup Requests
    let signup_request_filter_count =  parseInt($("#admin-signup-filter-count option:selected").val());
    let backend_signup_request;
    backend_signup_request = $('#backend_signup_request').DataTable( {
        searching: true,
        lengthChange: true,
        pageLength:signup_request_filter_count,
        paging: true,
        ordering: false,
        info: false,
        responsive: true,
        autoWidth: false,
        "fnInitComplete": function (oSettings, json) {           
            renderDataTableView();
        },
        columns: [
            { "width": "16%" },
            { "width": "16%" },
            { "width": "16%" },
            { "width": "16%" },
            { "width": "16%" },
            { "width": "16%" },
        ]
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
    
    $('#admin_signup_search').keyup(function(e){
        let is_pressed = false;
        if(e.key == 'Enter' && e.keyCode == 13) {
            is_pressed = true;
        }
        let search = $(this).val();
        if(search == '' || is_pressed){
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

// sign up sorting
function signup_sorting(order,order_type){
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
    
    $('<input>').attr({
        type: 'hidden',
        id: 'ortype',
        name: 'ortype',
        value: order_type
    }).appendTo('#admin_signup_from');

    $('<input>').attr({
        type: 'hidden',
        id: 'srorder',
        name: 'srorder',
        value: order
    }).appendTo('#admin_signup_from');

    $('#admin_signup_from').submit();
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
// exports page
$(document).on('click','#admin-customer-exports-icon',function(e){
    e.preventDefault();
    $('#export-admin-exports-drop').toggleClass('d-none');
})

$(document).on('click','.export-admin-exports-item',function(){
    $('#export-admin-exports-drop').addClass('d-none');
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
    // var reader = new FileReader();

    // reader.onload = function (e) {
    //     $('.profile_img_disp_admin').attr('src', e.target.result);
    // }
    // reader.readAsDataURL($image);
    if ($image) {
        $('.image-upload').css('background-image','url('+URL.createObjectURL($image)+')');
        $('.profile_img_disp_admin').attr('src',URL.createObjectURL($image)).css('opacity','0');
    }
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
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Profile Updated successfully',
                    showConfirmButton: false,
                    timer: 2000
                  })

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
    //alert("PRINT USERS");
    window.print();
    /*
    var divContents = document.getElementById("backend_customers").innerHTML;
    var a = window.open('', '', 'height=500, width=500');
    a.document.write('<html>');
    a.document.write('<body>');
    a.document.write(divContents);
    a.document.write('</body></html>');
    a.document.close();
    a.print(); */
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

// admin nav bar
$(document).on('click','#account_setting_nav',function(e){
    e.preventDefault();
    window.location = '/admin/profile';
})


$(document).on('click','#admin_nav_change',function(e){
    e.preventDefault();
    window.location = '/admin/customers/change-orders';
})

// backend sortings
$(document).on('click','th span',function(e){
    e.preventDefault();
    $('th span').css({'opacity':0.3})
    $(e.currentTarget).css({'opacity':1})
    let order = $(e.currentTarget).data('col');
    let order_type = $(e.currentTarget).data('ordertype');
    let table = $(e.currentTarget).data('table');
    console.log(table,'__table');
    console.log(order,'__order');
    console.log(order_type,'__order_type');
    if(table == 'customers') {
        customers_submit(order,order_type)
    }

    if(table == 'roles') {
        roles_submit(order,order_type)
    }

    if(table == 'admins') {
        admins_submit(order,order_type)
    }

    if(table == 'managers') {
        managers_submit(order,order_type)
    }

    if(table == 'signups') {
        signup_sorting(order,order_type)
    }

    if(table == 'change_orders'){
        change_order_sort(order,order_type)
    }

    if(table == 'export_request') {
        exports_sorting(order,order_type)
    }
})


function renderDataTableView(){    
    $('table.table-opacity').removeClass('table-opacity');
    $('.chart-loader-div').addClass('d-none');    
}