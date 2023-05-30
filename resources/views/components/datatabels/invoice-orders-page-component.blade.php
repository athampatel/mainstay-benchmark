<div>
    <table id="invoice-orders-page-table" class="table bench-datatable border-0">
        <thead>
            <tr>
                <th class="border-0">Invoice #</th>
                <th class="border-0">Customer Name</th>
                <th class="border-0">Customer Email</th>
                {{-- <th class="border-0">Company Name</th>
                <th class="border-0">Company Email</th> --}}
                <th class="border-0">Customer P.O. Number</th>
                <th class="border-0">Total Item(s)</th>
                {{-- <th class="border-0">Price</th> --}}
                <th class="border-0">Invoice Total</th>
                <th class="border-0">Date</th>
                <th class="border-0">Status</th>
                <th class="border-0">Action</th>
            </tr>
        </thead>
        <tbody id="invoice-orders-page-table-body">
            @foreach($invoices as $invoice)
            <tr>
                {{-- <td><a href="/invoice-detail/{{$invoice['salesorderno']}}" target="_blank" class="item-number font-12 btn btn-rounded">#{{$invoice['invoiceno']}}</a></td>
                <td><a href="javascript:void(0)" class="customer-name">{{Auth::user()->name}}</a></td>
                <td><a href="mailto:adamsbaker@mail.com" class="customer-email">{{Auth::user()->email}}</a></td>
                <td><a href="mailto:adamsbaker@mail.com" class="customer-email">{{$invoice['customerpono'] != "" ? $invoice['customerpono'] : 'N/A' }}</a></td> --}}
                @php
                    $selected_customer = session('selected_customer');
                @endphp
                <td class="font-12 pointer_events_none">#{{$invoice['invoiceno']}}</td>
                <td class="customer-name pointer_events_none">{{$selected_customer['customername']}}</td>
                <td><a href="mailto:{{$selected_customer['email']}}" class="customer-email">{{$selected_customer['email']}}</a></td>
                <td class="pointer_events_none">{{$invoice['customerpono'] != "" ? $invoice['customerpono'] : 'N/A' }}</td>
                @php
                $total = 0;
                $price = 0;
                $date = DateTime::createFromFormat('Y-m-d',$invoice['invoicedate']);
                foreach ($invoice['details'] as $item){
                    $total += $item['quantityshipped'];
                    $price += $item['quantityshipped'] * $item['unitprice'];
                }
                @endphp
                <td class="pointer_events_none">{{$total}}</td>
                <td class="pointer_events_none">${{number_format($price,2,".",",")}}</td>
                {{-- <td>{{ \Carbon\Carbon::parse($invoice['invoicedate'])->format('M d, Y') }}</td> --}}
                <td class="pointer_events_none">{{$date->format('M d, Y')}}</td>
                <td class="status pointer_events_none">Shipped</td>
                <td class="action">
                    <a href="/invoice-detail/{{$invoice['salesorderno']}}" class="btn btn-primary btn-rounded text-capitalize text-dark open-view-details" target="_blank">
                        view details
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>