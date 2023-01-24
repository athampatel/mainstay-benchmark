$(function(){
    // customer sales history
    if ($("#customer_sales_history").length) {
        AjaxRequestCom('/customersales','GET','','chartDisplay');
    }
    // customer invoice orders
    if ($("#invoice-orders-table-body").length) {
        $('#invoice-orders-table-body').html('<tr><td class="text-center" colspan="8">Loading...</td></tr>');
        AjaxRequestCom('/customer-invoice-orders','GET','','customerInvoiceOrderDisplay');
    }
});

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
})


function customerInvoiceOrderDisplay(res){
    $html = '';
    const final_data = res.data.data.salesorderhistoryheader;
    const user = res.data.user;
    final_data.forEach( da => {
        let display_date = CustomDateFormat1(da.orderdate);
        let total_items = 0;
        let total_price = 0;
        da.salesorderhistorydetail.forEach( item => {
            total_items += item.quantityorderedrevised;
            total_price += item.lastunitprice;
        });
        $html += `
        <tr>
            <td>
                <a href="javascript:void(0)" class="item-number font-12 btn btn-primary btn-rounded order-item-detail" data-sales_no=${da.salesorderno} ># ${da.salesorderno}</a>
            </td>
            <td>
                <a href="javascript:void(0)" class="customer-name">${user.name}</a>
            </td>
            <td>
                <a href="mailto:adamsbaker@mail.com" class="customer-name">${user.email}</a>
            </td>
            <td>
                ${total_items}
            </td>
            <td>
                $${total_price}
            </td>
            <td>
                ${display_date}
            </td>
            <td>
                <span class="svg-icon location-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                </span> 
                ${da.shiptocity}
            </td>
            <td>
               ${da.orderstatus == 'C' ? 'Completed' : 'Not Completed'}
            </td>
        </tr>`;
    })

    $('#invoice-orders-table-body').html($html);
}

function chartDisplay($res){
    $counts = [];
    $categories = [];
    $customer_data = $res.data.data.customersaleshistory;
    for(let i = 1; i <= 12 ; i++ ){
        let is_add = true;
        $customer_data.forEach(da => {
            if(da.fiscalperiod == i){
                is_add = false;
                $counts.push(da.dollarssold)
            }
        })
        if(is_add){
            $counts.push(0);
        }
    }
    
    var options = {
        series: [{
                name: 'sales',
                data: $counts
            },
        ],
        chart: {
            foreColor: '#9ba7b2',
            type: 'line',
            height: 360,
            zoom:{
                enabled:false
            },
            toolbar : {
                show:true
            },
            dropShadow:{
                enabled:true,
                top:3,
                left:14,
                blur:4,
                opacity:0.10,
            }
        },
        stroke: {
            width: 5,
            curve:'straight'
        },

        xaxis: {
            type:'month',
            categories: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            title:{
                text:'Year '+$res.data.year,
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                gradientToColors: ['#A4CD3C'],
                shadeIntensity: 1,
                type: 'horizontal',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        markers: {
        	size: 4,
        	colors: ["#A4CD3C"],
        	strokeColors: "#fff",
        	strokeWidth: 2,
        	hover: {
        		size: 7,
        	}
        },
        colors: ["#A4CD3C"],
        
        yaxis: {
            title: {
                text: ''
            }
        },
    };

    var chart = new ApexCharts(document.querySelector("#customer_sales_history"), options);
    chart.render();
}


// change order form page
// let order_details = "";
// $(document).on('click','#get_order_details',function(e){
//     e.preventDefault();
//     $('.backdrop').removeClass('d-none');
//     $ItemCode = $('#ItemCode').val();
//     $PurchaseOrderNumber = $('#PurchaseOrderNumber').val();
//     if($PurchaseOrderNumber != ""){
//         if($ItemCode != ""){
//             if(order_details == ""){
//                 // ajax request and display
//                 orderDetailsAjax($PurchaseOrderNumber,$ItemCode);        
//             } else {
//                 // display
//                 displayChangeOrderPage(order_details,$ItemCode);
//             }
//         } else {
//             // ajax request
//             orderDetailsAjax($PurchaseOrderNumber,$ItemCode);
//         }
//     } else {
//         console.log('purchase order number needed');
//         $('.backdrop').addClass('d-none');
//     }
// });

let order_details = "";
$('#change-order-form').on('submit', function(e) {
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
                if($ItemCode == ""){
                    let item_code_select_options = '';
                    res.data.data.sales_order_history_detail.forEach(item => {
                        item_code_select_options += `<option value="${item.itemcode}">${item.itemcode}</option>`;
                    })
                    let item_code_selectbox = `
                                            <label for="ItemCode" class="form-label">Choose Item Code</label>
                                                <select class="form-select col-12" id="ItemCode">
                                                    ${item_code_select_options}
                                                </select>`;
                    $('#item-code-selectbox').html(item_code_selectbox);
                    let first_item_code = res.data.data.sales_order_history_detail.length > 0 ? res.data.data.sales_order_history_detail[0].itemcode : '';
                    displayChangeOrderPage(order_details,first_item_code);
                } else {
                    displayChangeOrderPage(order_details,$ItemCode);
                }
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


function displayChangeOrderPage(res,$itemCode){
    if(res.success){
        $_data = res.data.data;
        $('.result-icon').addClass('d-none');
        $('.result-data').removeClass('d-none');
        $('#disp-order-id').text($_data.salesorderno);
        
        // shipment details
        $('#ship-to-name').val($_data.shiptoname);
        $('#ship-to-phonenumber').val();
        $('#ship-to-email').val(res.data.user.email);
        $('#ship-to-address1').val($_data.shiptoaddress1);
        $('#ship-to-address2').val($_data.shiptoaddress2);
        $('#ship-to-address3').val($_data.shiptoaddress3);
        // state html
        // let state_html = `<option value="" selected></option>`
        // $('#ship-to-state').val($_data.shiptostate).prop('selected', true);//.change();
        // $('#ship-to-city').text($_data.shiptocity).prop('selected', true).change();
        $('#ship-to-state option:selected').val($_data.shiptostate);//.change();
        $('#ship-to-state option:selected').text($_data.shiptostate);//.change();
        $('#ship-to-city option:selected').val($_data.shiptocity).change();
        $('#ship-to-city option:selected').text($_data.shiptocity).change();
        $('#ship-to-zipcode').val($_data.shiptozipcode);
        $('#shipvia').val($_data.shipvia);

        // order details
        $('#order-detail-order-no').val($_data.salesorderno);
        $('#order-location').val($_data.shiptocity);
        $('#AliasItemNumber').val();
        $('#OrderDate').val($_data.orderdate);
        // order status
        let order_status = $_data.orderstatus == 'C' ? 'Completed' : 'Not Completed';
        $('#orderStatus option:selected').val(order_status);
        $('#orderStatus option:selected').text(order_status);
        
        if($_data.orderstatus == 'C'){
            $('#order-save-button').addClass('d-none')
        } else {
            $('#order-save-button').removeClass('d-none')
        }
        // item details
        let item_details_html = '';
        let quantity_count = 0;
        let promise_date = '';
        let dropship = '';
        $_data.sales_order_history_detail.forEach(item => {
            if(item.itemcode == $itemCode){
                quantity_count +=item.quantityorderedrevised;
                promise_date = item.promisedate;
                item_details_html += `<tr>
                    <td>${item.product_details.length > 0 ? item.product_details[0].itemcodedesc : ''}<br/>
                    Item Code: <a href="javascript:void(0)" class="item-number font-12">${item.itemcode}</a></td>                                    
                    <td>${item.quantityorderedrevised}</td>
                    <td>$ ${item.lastunitprice}</td>
                    <td>$ ${item.quantityorderedrevised * item.lastunitprice}</td>
                    <td>
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16.899" height="16.87" viewBox="0 0 16.899 16.87">
                                <g class="pen" transform="translate(-181.608 -111.379)">
                                    <path id="Path_955" data-name="Path 955" d="M197.835,114.471,195.368,112a1.049,1.049,0,0,0-1.437,0l-11.468,11.5a.618.618,0,0,0-.163.325l-.434,3.552a.52.52,0,0,0,.163.461.536.536,0,0,0,.38.163h.054l3.552-.434a.738.738,0,0,0,.325-.163l11.5-11.5a.984.984,0,0,0,.3-.7,1.047,1.047,0,0,0-.3-.732Zm-12.119,12.038-2.684.325.325-2.684,9.76-9.76,2.359,2.359Zm10.519-10.546-2.359-2.332.786-.786,2.359,2.359Z" transform="translate(0 0)" fill="#9fcc47" stroke="#9fcc47" stroke-width="0.5"/>
                                </g>
                            </svg>
                        </a>
                    </td>
                </tr>`;

                dropship = item.dropship == 'Y' ? 'Yes' : 'No';
            }
        })

        $('#disp-items-body').html(item_details_html);
        // get the count of the quantity
        $('#quantityShiped').val(quantity_count);
        // promise date value
        $('#promiseDate').val(promise_date)
        // drop ship
        $('#DropShip option:selected').val(dropship);
        $('#DropShip option:selected').text(dropship);

    } else {
        // error message display
        $('.result-icon').addClass('d-none');
        $('.backdrop').addClass('d-none');
        $('.order-validation-error-msg').text(res.error)
        $('.order-validation-error').removeClass('d-none');
    }  

}

$(document).on('click','#photo_image_upload',function(e){
    e.preventDefault();
    var formData = new FormData();
    let _token = $('meta[name="csrf-token"]').attr('content');
    console.log('__clicked');
    $image = $('#profile_image_edit').prop('files')[0];
    formData.append('photo_1', $image);
    $.ajax({
        type: 'POST',
        url: '/photo-upload',
        contentType: 'multipart/form-data',
        cache: false,
        contentType: false,
        processData: false,
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: formData,
        success: function (res) {  
            console.log(res,'___photo upload response');
        }
    });
})