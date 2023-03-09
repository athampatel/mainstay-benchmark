// $(document).on('click','.order-item-detail',function(e){
//     e.preventDefault();
//     let sales_order_no = $(e.currentTarget).data('sales_no');
//     $.ajax({
//         type: 'POST',
//         url: '/invoice-order-detail',
//         dataType: "JSON",
//         headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         data: { "order_no" : sales_order_no,"item_code":"" },
//         success: function (res) {  
//             console.log(res,'___product data');
//         }
//     });
// })

if($(document.body).find('#InvoicePurchaseOrderNumber').length > 0 && $('#InvoicePurchaseOrderNumber').val() != '' ){
    // $('.backdrop').removeClass('d-none');
    setTimeout(function(){
        $('form#InvoicePurchaseOrderNumber').trigger('submit');
    },1000);
}


$('#invoice-order-form').on('submit', function(e) {
    e.preventDefault();
    $('.backdrop').removeClass('d-none');
    let $InvoiceItemCode = $('#InvoiceItemCode').val();
    console.log($InvoiceItemCode,'__$InvoiceItemCode');
    $InvoicePurchaseOrderNumber = $('#InvoicePurchaseOrderNumber').val();
    console.log($InvoicePurchaseOrderNumber,'__$InvoicePurchaseOrderNumber');
    if($InvoicePurchaseOrderNumber != ""){
        if($InvoiceItemCode != ""){
            if(invoice_order_details == ""){
                // ajax request and display
                console.log('__cm 1');
                invoiceOrderDetailsAjax($InvoicePurchaseOrderNumber,$InvoiceItemCode);        
            } else {
                // display
                console.log('__cm 2');
                invoiceDisplayChangeOrderPage(invoice_order_details,$InvoiceItemCode);
            }
        } else {
            // ajax request
            console.log('__cm 3');
            invoiceOrderDetailsAjax($InvoicePurchaseOrderNumber,$InvoiceItemCode);
        }
    } else {
        $('.order-validation-error').removeClass('d-none');
        $('.result-icon').addClass('d-none');
        $('.backdrop').addClass('d-none');
    }
});

function invoiceOrderDetailsAjax($InvoicePurchaseOrderNumber,$InvoiceItemCode){
    console.log('__cm 4');
    $.ajax({
        type: 'POST',
        url: '/invoice-order-detail',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "order_no" : $InvoicePurchaseOrderNumber,"item_code":$InvoiceItemCode },
        beforeSend:function(){
            $('.backdrop').removeClass('d-none');
        },
        success: function (res) {  
            console.log(res,'___cm response');
            return false;
            if(res.success){
                invoice_order_details = res;
                    let item_code_select_options = '';
                    res.data.data.sales_order_history_detail.forEach(item => {
                        $.each(item.product_details,function(ind,values){    
                            if(values.quantityordered > 0)                    
                                item_code_select_options += `<option value="${values.itemcode}">${values.itemcode}</option>`;
                        });
                    })
                    let item_code_selectbox = `
                                            <label for="ItemCode" class="form-label">Choose Item Code</label>
                                                <select class="form-select col-12" id="InvoiceItemCode">                                                   
                                                    ${item_code_select_options}
                                                </select>`;
                    $('#invoice-item-code-selectbox').html(item_code_selectbox);
                    let first_item_code = res.data.data.sales_order_history_detail.length > 0 ? res.data.data.sales_order_history_detail[0].itemcode : '';
                    displayChangeOrderPage(order_details,first_item_code);
                    displayChangeOrderPage(order_details,$ItemCode);
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