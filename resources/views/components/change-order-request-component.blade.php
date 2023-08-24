<table id="change-order-request-page-table" class="table bench-datatable border-0">
    <thead>
        <tr>
            <th class="border-0">Order Number</th>
            <th class="border-0">Order Date</th>
            <th class="border-0">Requested Date</th>
            <th class="border-0">Item Code</th>
            <th class="border-0">Unit Price</th>
            <th class="border-0">Old Quantity</th>
            <th class="border-0">New Quantity</th>
            <th class="border-0">Status</th>
        </tr>
    </thead>
    <tbody id="open-orders-page-table-body">
        @foreach($change_orders as $change_order)
        <tr>
            <td><a href="/change-order/info/{{$change_order['order_no']}}" class="item-number font-12 btn btn-rounded">{{$change_order['order_no']}}</a></td>
            <td>{{ \Carbon\Carbon::parse($change_order['ordered_date'])->format('M d, Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($change_order['created_at'])->format('M d, Y') }}</td>
            <td>{{$change_order['item_code']}}</td>
            <td>${{$change_order['order_item_price']}}</td>
            <td>{{$change_order['existing_quantity']}}</td>
            <td>{{$change_order['modified_quantity']}}</td>
            
            @if($change_order['request_status'] == 0)
                <td class="status">In Progress</td>
            @else 
                <td class="status">Approved</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>