// setTimeout(function(){
// },200);
getInvoiceDetails()

function getInvoiceDetails(){
    let $order_id = $('#orderid_val').val();
    if($order_id != ""){
        // if(invoice_order_details == ""){
            invoiceOrderDetailsAjax($order_id);        
        // } else {
        //     invoiceDisplayChangeOrderPage(invoice_order_details,$InvoiceItemCode);
        // }
    }
}

function invoiceOrderDetailsAjax($order_id){
    $.ajax({
        type: 'POST',
        url: '/invoice-order-detail',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "order_no" : $order_id},
        beforeSend:function(){
            $('.backdrop').removeClass('d-none');
        },
        success: function (res) {  
            if(res.success){
                displayInvoiceOrderDetail(res)
            } else {
                $('.result-icon').addClass('d-none');
                $('.backdrop').addClass('d-none');
                // $('.order-validation-error').removeClass('d-none');
                $('.order-validation-error-msg').removeClass('d-none');
                $('.order-validation-error-msg').text(res.error)
            }
        },
        complete:function(){
            $('.backdrop').addClass('d-none');
        }
    });
}

function displayInvoiceOrderDetail(res){
    let order_detail = res.order_detail;
    let item_details_html = '';
    let quantity_count = 0;

    console.log(order_detail,'___order_detail');
    // invoice details
    let order_id_disp = `${order_detail.invoiceno} - ${moment(order_detail.invoicedate,'YYYY-MM-DD').format('MM-DD-YYYY')}`;
    $('#disp-order-id').text(order_id_disp);
    $('#customer_po_number').val(order_detail.customerpono ? order_detail.customerpono : 'N/A');
    $('#details_invoice_date').val(moment(order_detail.invoicedate,'YYYY-MM-DD').format('MM-DD-YYYY'));
    $('#ship-to-address1').val(order_detail.shiptoaddress1 ? order_detail.shiptoaddress1 : 'N/A');
    $('#ship-to-address2').val(order_detail.shiptoaddress2 ? order_detail.shiptoaddress2 : 'N/A');
    $('#ship-to-address3').val(order_detail.shiptoaddress3 ? order_detail.shiptoaddress3 : 'N/A');
    $('#ship-to-state option:selected').val(order_detail.shiptostate);
    $('#ship-to-state option:selected').text(order_detail.shiptostate ? order_detail.shiptostate : 'N/A');
    $('#ship-to-city option:selected').val(order_detail.shiptocity).change();
    $('#ship-to-city option:selected').text(order_detail.shiptocity ? order_detail.shiptocity : 'N/A').change();
    $('#ship-to-zipcode').val(order_detail.shiptozipcode ? order_detail.shiptozipcode : 'N/A');
    $('#shipvia').val(order_detail.shipvia ? order_detail.shipvia : 'N/A');
    
    // Order Details
    $('#order-detail-order-no').val(order_detail.salesorderno ? order_detail.salesorderno : 'N/A');
    $('#order-location').val(order_detail.shiptocity ? order_detail.shiptocity : 'N/A');
    let aliasnumber = order_detail.details.length > 0 ? order_detail.details[0].aliasitemno : '';
    $('#AliasItemNumber').val(aliasnumber);
    $('#OrderDate').val(moment(order_detail.orderdate,'YYYY-MM-DD').format('MM-DD-YYYY'));
    // let drop_ship = order_detail.details.length > 0 ? order_detail.details[0].dropship == 'Y' ? 'Shipped' : 'Not Shipped' : 'Not Shipped';
    // let drop_ship = order_detail.details.length > 0 ? order_detail.details[0].dropship == 'Y' ? 'Yes' : 'No' : 'No';
    // $('#DropShip option:selected').val(drop_ship);
    // $('#DropShip option:selected').text(drop_ship);

    let drop_ship_status = order_detail.details.length > 0 ? order_detail.details[0].dropship == 'Y' ? 'Complete' : 'Shipped' : 'Shipped';
    $('#orderStatus option:selected').val(drop_ship_status);
    $('#orderStatus option:selected').text(drop_ship_status);
    // Item Details
    order_detail.details.forEach(item => {
        if(item.quantityshipped > 0){
            quantity_count += item.quantityshipped;    
            itemcode = 'N/A';
            if(item.itemcode != '')
            itemcode = item.itemcode;

            aliasitemno = 'N/A';
            if(item.aliasitemno != '')
            aliasitemno = item.aliasitemno;
            /*<br/>
                Item Code: <a href="javascript:void(0)" class="item-number font-12 pointer_events_none" data-val="${item.itemcode}">${item.itemcode}</a> */
            item_details_html += `<tr class="order_item_row" data-val="${item.itemcode}">
                <td class="text-break text-center">${item.itemcodedesc}</td> 
                <td class="bench-no itemcode text-center">${itemcode}</td>
                <td class="alias-no itemcode text-center">${aliasitemno}</td>
                <td class="order_item_quantity text-center"  data-val="${item.quantityordered}" data-start_val="${item.quantityordered}">
                <input type="number" name="ordered_item_quantity_input" id="" min="${item.quantityordered}" value="${item.quantityordered}" data-val=${item.quantityordered} class="order_item_quantity_input notactive form-input" disabled>
                </td>
                <td class="order_item_quantity text-center"  data-val="${item.quantityshipped}" data-start_val="${item.quantityshipped}">
                <input type="number" name="order_item_quantity_input" id="" min="${item.quantityshipped}" value="${item.quantityshipped}" data-val=${item.quantityshipped} class="order_item_quantity_input notactive form-input" disabled>
                </td>
                <td class="order_item_quantity text-center"  data-val="${item.quantitybackordered}" data-start_val="${item.quantitybackordered}">
                <input type="number" name="open_item_quantity_input" id="" min="${item.quantitybackordered}" value="${item.quantitybackordered}" data-val=${item.quantitybackordered} class="order_item_quantity_input notactive form-input" disabled></td>
                <td class="order_unit_price text-center" data-val="${item.unitprice}">$ ${numberWithCommas(item.unitprice)}</td>
                <td class="order_unit_total_price text-center" data-val="${item.unitprice}">$ ${numberWithCommas( item.quantityshipped * item.unitprice)}</td>
                <td class="text-center">${item.dropship == 'Y' ? 'Yes' : 'No'}</td>
            </tr>`;
        }
    }); 
    $('#quantityShiped').val(quantity_count);            
    $('#disp-items-body').html(item_details_html);


    //$('#orderItems').fixedHeaderTable({ footer: false, cloneHeadToFoot: false, fixedColumn: true });

    // tracking id's listing
    if(typeof order_detail.trackingid != 'undefined'){
        console.log(order_detail.trackingid,'___tracking id ___eeee');
        let tracking_html_content = "";
        order_detail.trackingid.forEach(trackid => {
            if(trackid != ''){
                let tracking_item_content = `<div class="list_item">${trackid}</div>`;
                tracking_html_content += tracking_item_content;
            } 
        });    
        // tracking_id_title
        $('#tracking_container').html(tracking_html_content);
        if(tracking_html_content == '') {
            $('#tracking_id_title').addClass('d-none');
            $('#tracking_container').parent().parent().parent().addClass('d-none')
        } else {
            $('#tracking_id_title').removeClass('d-none');
            $('#tracking_container').parent().parent().parent().removeClass('d-none')
        }
    } else {
        $('#tracking_id_title').addClass('d-none');
        $('#tracking_container').parent().parent().parent().addClass('d-none');
    }
}

function numberWithCommas(x) {
    // return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    console.log(x,'___x');
    return  x.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}