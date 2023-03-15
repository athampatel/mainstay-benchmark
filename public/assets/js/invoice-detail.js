setTimeout(function(){
    getInvoiceDetails()
},200);

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
                $('.order-validation-error').removeClass('d-none');
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

    // invoice details
    let order_id_disp = `# ${order_detail.invoiceno} - ${moment(order_detail.invoicedate,'YYYY-MM-DD').format('MMM DD,YYYY')}`;
    $('#disp-order-id').text(order_id_disp);
    $('#customer_po_number').val(order_detail.customerpono);
    $('#details_invoice_date').val(moment(order_detail.invoicedate,'YYYY-MM-DD').format('MMM DD,YYYY'));
    $('#ship-to-address1').val(order_detail.shiptoaddress1);
    $('#ship-to-address2').val(order_detail.shiptoaddress2);
    $('#ship-to-address3').val(order_detail.shiptoaddress3);
    $('#ship-to-state option:selected').val(order_detail.shiptostate);
    $('#ship-to-state option:selected').text(order_detail.shiptostate);
    $('#ship-to-city option:selected').val(order_detail.shiptocity).change();
    $('#ship-to-city option:selected').text(order_detail.shiptocity).change();
    $('#ship-to-zipcode').val(order_detail.shiptozipcode);
    $('#shipvia').val(order_detail.shipvia);
    
    // Order Details
    $('#order-detail-order-no').val(order_detail.salesorderno);
    $('#order-location').val(order_detail.shiptocity);
    let aliasnumber = order_detail.details.length > 0 ? order_detail.details[0].aliasitemno : '';
    $('#AliasItemNumber').val(aliasnumber);
    $('#OrderDate').val(moment(order_detail.orderdate,'YYYY-MM-DD').format('MMM DD,YYYY'));
    let drop_ship = order_detail.details.length > 0 ? order_detail.details[0].dropship == 'Y' ? 'Shipped' : 'Not Shipped' : 'Not Shipped';
    $('#DropShip option:selected').val(drop_ship);
    $('#DropShip option:selected').text(drop_ship);
    
    // Item Details
    order_detail.details.forEach(item => {
        if(item.quantityshipped > 0){
            quantity_count += item.quantityshipped;
            item_details_html += `<tr class="order_item_row" data-val="${item.itemcode}">
                <td>${item.itemcodedesc}<br/>
                Item Code: <a href="javascript:void(0)" class="item-number font-12" data-val="${item.itemcode}">${item.itemcode}</a></td> 
                <td class="order_item_quantity"  data-val="${item.quantityshipped}" data-start_val="${item.quantityshipped}">
                <input type="number" name="order_item_quantity_input" id="" min="${item.quantityshipped}" value="${item.quantityshipped}" data-val=${item.quantityshipped} class="order_item_quantity_input notactive form-input" disabled></td>
                <td class="order_unit_price" data-val="${item.unitprice}">$ ${numberWithCommas(item.unitprice)}</td>
                <td class="order_unit_total_price" data-val="${item.unitprice}">$ ${numberWithCommas(item.quantityshipped * item.unitprice)}</td>
            </tr>`;
        }
    }); 
    $('#quantityShiped').val(quantity_count);            
    $('#disp-items-body').html(item_details_html);
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}