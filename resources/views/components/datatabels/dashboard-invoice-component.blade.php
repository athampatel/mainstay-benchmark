<div>
    <table id="dashboard-recent-invoice-order-table" class="table bench-datatable">
        <thead>
            <tr>
                <th class="border-0">ID</th>
                {{-- <th class="border-0">Customer email</th> --}}
                <th class="border-0">Customer P.O. Number</th>
                {{-- <th class="border-0">Contact email</th> --}}
                <th class="border-0">Total items</th>
                <th class="border-0">Price</th>
                <th class="border-0">Date</th>
                <th class="border-0">Location</th>
            </tr>
            </thead>
            <tbody id="invoice-orders-table-body">
                @foreach ($invoices as $invoice)    
                    {{-- {{dd($invoice)}} --}}
                    <tr>
                        @php 
                         $selected_customer = session('selected_customer');
                        @endphp
                        <td><a href="/invoice-detail/{{$invoice['salesorderno']}}" target="_blank" class="item-number font-12 btn btn-primary btn-rounded">#{{$invoice['salesorderno']}}</a></td>
                        {{-- <td><a href="mailto:{{$selected_customer['email']}}" class="customer-email">{{$selected_customer['email']}}</a></td>  --}}
                        {{-- <td><a href="mailto:{{$selected_customer['email']}}" class="customer-email">{{$selected_customer['email']}}</a></td>  --}}
                        <td>{{$invoice['customerpono'] ? $invoice['customerpono'] : 'N/A'}}</td> 
                        @php
                        $price = 0;  
                        $total = 0;
                        $date = DateTime::createFromFormat('Y-m-d',$invoice['invoicedate']);
                        foreach($invoice['details'] as $item){
                            $price += $item['quantityshipped'] *  $item['unitprice'];
                            $total += $item['quantityshipped'];
                        }
                        @endphp
                        <td class="pointer_events_none">{{$total}}</td>
                        <td class="pointer_events_none">${{number_format($price,2,".",",")}}</td>
                        <td class="pointer_events_none">{{$date->format('M d, Y')}}</td>
                        <td class="location">
                            <span class="svg-icon location-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                            </span> 
                            {{$invoice['shiptocity']}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
    </table>    
</div>