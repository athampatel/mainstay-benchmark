<div>
    <table id="invoice-orders-page-table" class="table bench-datatable border-0">
        <thead>
            <tr>
                <th class="border-0">ID</th>
                <th class="border-0">Customer name</th>
                <th class="border-0">Customer email</th>
                <th class="border-0">Customer Po Number</th>
                <th class="border-0">Total items</th>
                <th class="border-0">Price</th>
                <th class="border-0">Date</th>
                <th class="border-0">Status</th>
                <th class="border-0">Action</th>
            </tr>
        </thead>
        <tbody id="invoice-orders-page-table-body">
            @foreach($invoices as $invoice)
            <tr>
                <td><a href="javascript:void(0)" class="item-number font-12 btn btn-rounded">#{{$invoice['invoiceno']}}</a></td>
                <td><a href="javascript:void(0)" class="customer-name">{{Auth::user()->name}}</a></td>
                <td><a href="mailto:adamsbaker@mail.com" class="customer-email">{{Auth::user()->email}}</a></td>
                <td><a href="mailto:adamsbaker@mail.com" class="customer-email">{{$invoice['customerpono']}}</a></td>
                @php
                $total = 0;
                $price = 0;
                foreach ($invoice['details'] as $item){
                    $total += $item['quantityshipped'];
                    $price += $item['quantityshipped'] * $item['unitprice'];
                }
                @endphp
                <td>{{$total}}</td>
                {{-- <td>${{$price}}</td> --}}
                <td>${{number_format($price,2,".",",")}}</td>
                <td>{{date('m-d-Y',strtotime($invoice['invoicedate']))}}</td>
                <td class="status">Shipped</td>
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