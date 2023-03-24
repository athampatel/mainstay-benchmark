$(document).on('click','.order-item-detail',function(e){
    e.preventDefault();
    let sales_order_no = $(e.currentTarget).data('sales_no');
    $.ajax({
        type: 'POST',
        url: '/order-detail',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "order_no" : sales_order_no,"item_code":"" },
        success: function (res) {  
            console.log(res,'___product data');
        }
    });

    // invoice detail page
    // let invoice_sales_orderno = 
})

//change order form page
/*let order_details = "";
$(document).on('click','#get_order_details',function(e){
    e.preventDefault();
    $('.backdrop').removeClass('d-none');
    $ItemCode = $('#ItemCode').val();
    $PurchaseOrderNumber = $('#PurchaseOrderNumber').val();
    if($PurchaseOrderNumber != ""){
        if($ItemCode != ""){
            if(order_details == ""){
                // ajax request and display
                orderDetailsAjax($PurchaseOrderNumber,$ItemCode);        
            } else {
                // display
                displayChangeOrderPage(order_details,$ItemCode);
            }
        } else {
            // ajax request
            orderDetailsAjax($PurchaseOrderNumber,$ItemCode);
        }
    } else {
        console.log('purchase order number needed');
        $('.backdrop').addClass('d-none');
    }
});*/

if($(document.body).find('#PurchaseOrderNumber').length > 0 && $('#PurchaseOrderNumber').val() != '' ){
    $('.backdrop').removeClass('d-none');
    setTimeout(function(){
        $('form#change-order-form').trigger('submit');
    },10);
}


$('#change-order-form').on('submit', function(e) {
    e.preventDefault();
    $('.backdrop').removeClass('d-none');
    let $ItemCode = $('#ItemCode').val();
    $PurchaseOrderNumber = $('#PurchaseOrderNumber').val();
    if($PurchaseOrderNumber != ""){
        if($ItemCode != ""){
            if(order_details == ""){
                // ajax request and display
                orderDetailsAjax($PurchaseOrderNumber,$ItemCode);        
            } else {
                // display
                displayChangeOrderPage(order_details,$ItemCode);
            }
        } else {
            // ajax request
            orderDetailsAjax($PurchaseOrderNumber,$ItemCode);
        }
    } else {
        $('.order-validation-error').removeClass('d-none');
        $('.result-icon').addClass('d-none');
        $('.backdrop').addClass('d-none');
    }
});

function orderDetailsAjax($PurchaseOrderNumber,$ItemCode){
    $.ajax({
        type: 'POST',
        url: '/order-detail',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "order_no" : $PurchaseOrderNumber,"item_code":$ItemCode },
        beforeSend:function(){
            $('.backdrop').removeClass('d-none');
        },
        success: function (res) {  
            // displayChangeOrderPage(res)
            if(res.success){
                order_details = res;
                //if($ItemCode == ""){
                    let item_code_select_options = '';
                    res.data.data.sales_order_history_detail.forEach(item => {
                        $.each(item.product_details,function(ind,values){    
                            if(values.quantityordered > 0)                    
                                item_code_select_options += `<option value="${values.itemcode}">${values.itemcode}</option>`;
                        });
                    })
                    let item_code_selectbox = `
                                            <label for="ItemCode" class="form-label">Choose Item Code</label>
                                                <select class="form-select col-12" id="ItemCode">                                                   
                                                    ${item_code_select_options}
                                                </select>`;
                    $('#item-code-selectbox').html(item_code_selectbox);
                    let first_item_code = res.data.data.sales_order_history_detail.length > 0 ? res.data.data.sales_order_history_detail[0].itemcode : '';
                    displayChangeOrderPage(order_details,first_item_code);
               // } else {
                    displayChangeOrderPage(order_details,$ItemCode);
                //}
            } else {
                $('.result-icon').addClass('d-none');
                $('.backdrop').addClass('d-none');
                $('.order-validation-error').removeClass('d-none');
                $('.order-validation-error-msg').text(res.error)
            }
        },
        complete:function(){
            $('.backdrop').addClass('d-none');
        }
    });
}


$(document).on('change','#ItemCode',function(e){
    e.preventDefault();
    let $itemcode =  $(this).val();
    displayChangeOrderPage(order_details,$itemcode);
});


function displayChangeOrderPage(res,itemcode){
    let itemcode1 = itemcode;
    if(res.success){
        $_data = res.data.data;       
        $('.result-icon').addClass('d-none');
        $('.result-data').removeClass('d-none');
        // item details
        let item_details_html = '';
        let quantity_count = 0;
        let promise_date = '';
        let dropship = '';
        $_data.sales_order_history_detail.forEach(Sale_item => {


            $('#disp-order-id').text(Sale_item.salesorderno);
        
            // shipment details
            $('#ship-to-name').val(Sale_item.shiptoname);
            $('#ship-to-phonenumber').val();
            $('#ship-to-email').val(res.data.user.email);
            $('#ship-to-address1').val(Sale_item.shiptoaddress1);
            $('#ship-to-address2').val(Sale_item.shiptoaddress2);
            $('#ship-to-address3').val(Sale_item.shiptoaddress3);
            // state html
            // let state_html = `<option value="" selected></option>`
            // $('#ship-to-state').val(Sale_item.shiptostate).prop('selected', true);//.change();
            // $('#ship-to-city').text(Sale_item.shiptocity).prop('selected', true).change();
            $('#ship-to-state option:selected').val(Sale_item.shiptostate);//.change();
            $('#ship-to-state option:selected').text(Sale_item.shiptostate);//.change();
            $('#ship-to-city option:selected').val(Sale_item.shiptocity).change();
            $('#ship-to-city option:selected').text(Sale_item.shiptocity).change();
            $('#ship-to-zipcode').val(Sale_item.shiptozipcode);
            $('#shipvia').val(Sale_item.shipvia);
            // order details
            $('#order-detail-order-no').val(Sale_item.salesorderno);
            $('#order-location').val(Sale_item.shiptocity);
            $('#AliasItemNumber').val();
            $('#OrderDate').val(Sale_item.orderdate);

            $('#ordereddate_val').val(Sale_item.orderdate);
            $('#salesorderno_val').val(Sale_item.salesorderno);
            $('#customerno_val').val(Sale_item.customerno);
                
            
            // order status
            let order_status = Sale_item.orderstatus == 'C' ? 'Completed' : 'Open';
            $('#orderStatus option:selected').val(order_status);
            $('#orderStatus option:selected').text(order_status);
            
            if(Sale_item.orderstatus == 'C'){
                $('#order-save-button').addClass('d-none')
            } else {
                $('#order-save-button').removeClass('d-none')
            }
            console.log(res,'___change order response');
            $.each(Sale_item.product_details,function(index,item){
               
                if(item.quantityordered > 0){
                    quantity_count += item.quantityordered;
                        promise_date = item.promisedate;
                        /* is_change_order */
                        let is_action ='';
                        if(res.data.is_change_order){
                            is_action = `<td class="order_item_actions">    
                                            <a href="#" class="edit_order_item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16.899" height="16.87" viewBox="0 0 16.899 16.87">
                                                    <g class="pen" transform="translate(-181.608 -111.379)">
                                                        <path id="Path_955" data-name="Path 955" d="M197.835,114.471,195.368,112a1.049,1.049,0,0,0-1.437,0l-11.468,11.5a.618.618,0,0,0-.163.325l-.434,3.552a.52.52,0,0,0,.163.461.536.536,0,0,0,.38.163h.054l3.552-.434a.738.738,0,0,0,.325-.163l11.5-11.5a.984.984,0,0,0,.3-.7,1.047,1.047,0,0,0-.3-.732Zm-12.119,12.038-2.684.325.325-2.684,9.76-9.76,2.359,2.359Zm10.519-10.546-2.359-2.332.786-.786,2.359,2.359Z" transform="translate(0 0)" fill="#9fcc47" stroke="#9fcc47" stroke-width="0.5"/>
                                                    </g>
                                                </svg>
                                            </a>
                                            <a href="#" class="d-none order-item-cancel-link" >
                                                <ion-icon name="close-outline" class="order-item-cancel"></ion-icon>
                                            </a>
                                            <a href="#" class="d-none order-item-save-link">
                                                <ion-icon name="save-outline" class="order-item-save"></ion-icon>
                                            </a>
                                        </td>`;
                        }
                        /* is_change_order */
                        item_details_html += `<tr class="order_item_row" data-val="${item.itemcode}">
                            <td>${item.itemcodedesc}<br/>
                            Item Code: <a href="javascript:void(0)" class="item-number font-12" data-val="${item.itemcode}">${item.itemcode}</a></td> 
                            <td class="order_item_quantity"  data-val="${item.quantityordered}" data-start_val="${item.quantityordered}">
                            <input type="number" name="order_item_quantity_input" id="" min="${item.quantityordered}" value="${item.quantityordered}" data-val=${item.quantityordered} class="order_item_quantity_input notactive form-input" disabled></td>
                            <td class="order_unit_price" data-val="${item.unitprice}">$ ${item.unitprice}</td>
                            <td class="order_unit_total_price" data-val="${item.unitprice}">$ ${item.quantityordered * item.unitprice}</td>
                            ${is_action}
                        </tr>`;

                    dropship = item.dropship == 'Y' ? 'Yes' : 'No';
                }
             });            
        })
        $('#disp-items-body').html(item_details_html);
        // get the count of the quantity
        $('#quantityShiped').val(quantity_count);
        // promise date value
        $('#promiseDate').val(promise_date)
        // drop ship
        $('#DropShip option:selected').val(dropship);
        $('#DropShip option:selected').text(dropship);
        $('.backdrop').addClass('d-none');
        $('.result-icon').addClass('d-none');
    } else {
        // error message display
        $('.result-icon').addClass('d-none');
        $('.backdrop').addClass('d-none');
        $('.order-validation-error-msg').text(res.error)
        $('.order-validation-error').removeClass('d-none');
    }  

}
// image upload in Account settings page

var fileInput = document.getElementById("file-input");
$(document).on('click','#file_input_button',function(){
    fileInput.click();
})

$(document).on('change','#file-input',function(e){
    $image = $('#file-input').prop('files')[0];
    var reader = new FileReader();
    reader.onload = function (e) {
        $('.profile_img_disp').attr('src', e.target.result);
    }
    reader.readAsDataURL($image);
});

$(document).on('click','#profile-edit-save-button',function(e){
    e.preventDefault();
    var formData = new FormData();
    $image = $('#file-input').prop('files')[0];
    let password = $('#Acc_password').val();
    let confirm_password = $('#Acc_confirm_password').val();
    formData.append('photo_1', $image);
    formData.append('password', password);
    formData.append('password_confirmation', confirm_password);
    let acc_name = $('#acc_name').val();
    let acc_phone_no = $('#acc_phone_no').val();
    let acc_address_line_1 = $('#acc_address_line_1').val();
    let acc_address_line_2 = $('#acc_address_line_2').val();
    let acc_state = $('#acc_state').val();
    let acc_city = $('#acc_city').val();
    let acc_zipcode = $('#acc_zipcode').val();

    formData.append('acc_name', acc_name);
    formData.append('acc_phone_no', acc_phone_no);
    formData.append('acc_address_line_1', acc_address_line_1);
    formData.append('acc_address_line_2', acc_address_line_2);
    formData.append('acc_state', acc_state);
    formData.append('acc_city', acc_city);
    formData.append('acc_zipcode', acc_zipcode);
    // form fields work end
    $.ajax({
        type: 'POST',
        url: '/account_edit_upload',
        contentType: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: formData,
        success: function (res) {
            let res1 = JSON.parse(res);
            console.log(res1,'__res1');
            if(res1.success){
                if(res1.data.length > 0){
                    console.log(res1.data[0].path,'___res path');
                    $("#nav-bar-profile-img").prop("src", res1.data[0].path);
                    $("#nav-bar-profile-img").prop("height", 45);
                    $("#nav-bar-profile-img").prop("width", 45);
                    $("#account-detail-profile-img").prop("src", res1.data[0].path);
                }
                $('#nav-bar-profile-name').text(acc_name);
                // $('#result-response-message').html("Account Details Updated Succcessfully").removeClass('alert-danger').addClass('alert-success');
                $('#result-response-message').html(constants.customer_account_page.update_message).removeClass('alert-danger').addClass('alert-success');
                $('.result-response').removeClass('d-none');
                setTimeout(() => {
                    $('.result-response').addClass('d-none');
                }, 2000);
            } else {
                $errors = '';
                if(res1.error.length > 0){
                    res1.error.forEach(err => {
                        $errors += err[0]+'<br>';
                    });
                }
                $('#result-response-message').html($errors).removeClass('alert-success').addClass('alert-danger');
                $('.result-response').removeClass('d-none');
            }
        }
    });
})

$(document).on('click','.edit_order_item',function(e){
    e.preventDefault();
    $(this).closest('.order_item_row').find('.order_item_quantity_input').removeClass('notactive');
    $(this).closest('.order_item_row').find('.order_item_quantity_input').prop('disabled',false);
    $(this).closest('.order_item_row').find('.edit_order_item').addClass('d-none');
    $(this).closest('.order_item_row').find('.order-item-cancel-link').removeClass('d-none');
    $(this).closest('.order_item_row').find('.order-item-save-link').removeClass('d-none');
})

$(document).on('change','.order_item_quantity_input',function(e){
    e.preventDefault();
    let new_val = $(this).val();
    let single_unit_price = $(this).closest('.order_item_row').find('.order_unit_price').data('val');
    $(this).closest('.order_item_row').find('.order_unit_total_price').html(`$ ${ (new_val * single_unit_price).toFixed(2)}`);
})

$(document).on('click','.order-item-cancel',function(e){
    e.preventDefault();
    let old_val = $(this).closest('.order_item_row').find('.order_item_quantity_input').data('val');
    $(this).closest('.order_item_row').find('.order_item_quantity_input').addClass('notactive');
    $(this).closest('.order_item_row').find('.order_item_quantity_input').prop('disabled',true);
    $(this).closest('.order_item_row').find('.order_item_quantity_input').val(old_val);
    $(this).closest('.order_item_row').find('.edit_order_item').removeClass('d-none');
    $(this).closest('.order_item_row').find('.order-item-save-link').addClass('d-none');
    $(this).closest('.order_item_row').find('.order-item-cancel-link').addClass('d-none');
});

$(document).on('click','.order-item-save',function(e){
    e.preventDefault();
    let old_val =  $(this).closest('.order_item_row').find('.order_item_quantity_input').data('val');
    let new_val =  $(this).closest('.order_item_row').find('.order_item_quantity_input').val();
    let item_code = $(this).closest('.order_item_row').find('.item-number').data('val');
    let unit_price = $(this).closest('.order_item_row').find('.order_unit_price').data('val');
    let is_found = true;
    changed_order_items.forEach(item => {
        if(item.itemcode == item_code){
            is_found = false;
            if(old_val != new_val){
                item.new_value = new_val;
            }
        } 
    })
    if(is_found){
        let new_array = {'itemcode':item_code,'old_value':old_val,'new_value':new_val,'unit_price':unit_price}
        if(old_val != new_val){
            changed_order_items.push(new_array);
        }
    }
    $(this).closest('.order_item_row').find('.order_item_quantity_input').addClass('notactive');
    $(this).closest('.order_item_row').find('.order_item_quantity_input').prop('disabled',true);
    $(this).closest('.order_item_row').find('.edit_order_item').removeClass('d-none');
    $(this).closest('.order_item_row').find('.order-item-save-link').addClass('d-none');
    $(this).closest('.order_item_row').find('.order-item-cancel-link').addClass('d-none');
});

$(document).on('click','#order-save-button',function(e){
    e.preventDefault();
    if(changed_order_items.length > 0){
        let data = changed_order_items;
        let customerno = $('#customerno_val').val();
        let salesorderNo = $('#salesorderno_val').val();
        let orderedDate = $('#ordereddate_val').val();
        $.ajax({
            type: "POST",
            url: "/change_order_items_save",
            data: JSON.stringify({'data': data,'customer_no':customerno,'sales_order_no':salesorderNo,'ordered_date': orderedDate}),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            beforeSend:function(){
                $('.backdrop').removeClass('d-none');
            },
            success: function(res){
                change_order_save_response(res)
            },
            complete:function(){
                $('.backdrop').addClass('d-none');
            }
        });
    } else {
        // $('#change-order-request-response-alert').text('No changes in the order');
        $('#change-order-request-response-alert').text(constants.change_order_request.no_changes);
        $('#change-order-request-response-alert').removeClass('d-none').removeClass('alert-success').addClass('alert-danger');
        // setTimeout(() => {
        //     $('#change-order-request-response-alert').addClass('d-none');
        // }, 2000);
    }
})

function change_order_save_response(res){
    changed_order_items = [];
    if(res.success){
        // $('#change-order-request-response-alert').text('Change order request sent successfully');
        $('#change-order-request-response-alert').text(constants.change_order_request.success);
        $('#change-order-request-response-alert').removeClass('d-none').removeClass('alert-danger').addClass('alert-success');
        setTimeout(() => {
            $('#change-order-request-response-alert').addClass('d-none');
            window.location = app_url+"open-orders";
        }, 2000);
        
    } else {
        $('#change-order-request-response-alert').text(res.error);
        $('#change-order-request-response-alert').removeClass('d-none').removeClass('alert-success').addClass('alert-danger');
        $('html, body').animate({
            scrollTop: $("#change-order-request-response-alert").offset().top
        }, 500);
        // setTimeout(() => {
        //     $('#change-order-request-response-alert').addClass('d-none');
        // }, 2000);
    }
}

// nav icon path setting
$(document).on('click','#account_setting_nav',function(e){
    window.location = '/account-settings';
})