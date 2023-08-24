<div>
    <table id="open-orders-page-table" class="table bench-datatable border-0 w-100">
        <thead>
            <tr>
                <th class="border-0">Order</th>
                <th class="border-0">Customer P.O. Number</th>
                <th class="border-0">Confirm To</th>
                <th class="border-0">Total Item(s)</th>
                <th class="border-0">Price per Unit</th>
                <th class="border-0">Date</th>
                <th class="border-0">Location</th>
                <th class="border-0">Status</th>
                <th class="border-0">Action</th>
            </tr>
        </thead>
        <tbody id="open-orders-page-table-body">
            @foreach($saleorders as $saleorder)
            <tr>
                {{-- {{dd($saleorder)}} --}}
                {{-- <td><a href="/change-order/{{$saleorder['salesorderno']}}" class="item-number font-12 btn btn-rounded">{{$saleorder['salesorderno']}}</a></td> --}}
                <td class="font-12 pointer_events_none">{{$saleorder['salesorderno']}}</td>
                @php
                    $selected_customer = session('selected_customer');
                @endphp
                {{-- <td class="customer-name pointer_events_none">{{Auth::user()->name}}</td>  --}}
                <td class="customer-name pointer_events_none">{{$saleorder['customerpono'] ? $saleorder['customerpono'] : 'N/A'}}</td> 

                <td class="customer-name pointer_events_none">{{$saleorder['confirmto'] ? $saleorder['confirmto'] : 'N/A'}}</td> 
               {{-- <td class="customer-name pointer_events_none">{{$selected_customer['customername']}}</td>--}}
                {{-- <td><a href="mailto:adamsbaker@mail.com" class="customer-email">{{Auth::user()->email}}</a></td> --}}
                {{-- <td><a href="mailto:{{$selected_customer['email']}}" class="customer-email">{{$selected_customer['email']}}</a></td> --}}
                @php
                $total = 0;
                $price = 0;
                $date = DateTime::createFromFormat('Y-m-d',$saleorder['orderdate']);
                if(isset($saleorder['details'])) {
                    foreach ($saleorder['details'] as $item){
                        $total += $item['quantityordered'];
                        $price += $item['quantityordered'] * $item['unitprice'];
                    }
                }
                @endphp
                <td class="pointer_events_none">{{$total}}</td>
                <td class="pointer_events_none">${{number_format($price,2,".",",")}}</td>
                <td class="pointer_events_none">{{$date->format('M d, Y')}}</td>
                <td class="location pointer_events_none">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46">
                            <path id="Path_769" data-name="Path 769" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/>
                        </svg>                  
                    </span>
                    {{$saleorder['shiptocity']}}
                </td>
                <td class="status pointer_events_none">Open</td>
                <td class="action">
                    @if($is_change_order)
                    {{-- <a href="/change-order/{{$saleorder['salesorderno']}}" target="_blank">
                        Change
                    </a> --}}
                    <a href="/change-order/{{$saleorder['salesorderno']}}" class="btn btn-primary btn-rounded text-capitalize text-dark open-view-details" target="_blank">
                        view details
                        <span class="open_order_view_icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14.899" height="14.87" viewBox="0 0 16.899 16.87">
                                <g id="pen" transform="translate(-181.608 -111.379)">
                                    <path id="Path_955" data-name="Path 955" d="M197.835,114.471,195.368,112a1.049,1.049,0,0,0-1.437,0l-11.468,11.5a.618.618,0,0,0-.163.325l-.434,3.552a.52.52,0,0,0,.163.461.536.536,0,0,0,.38.163h.054l3.552-.434a.738.738,0,0,0,.325-.163l11.5-11.5a.984.984,0,0,0,.3-.7,1.047,1.047,0,0,0-.3-.732Zm-12.119,12.038-2.684.325.325-2.684,9.76-9.76,2.359,2.359Zm10.519-10.546-2.359-2.332.786-.786,2.359,2.359Z" transform="translate(0 0)" fill="#212529" stroke="#212529" stroke-width="0.5"/>
                                </g>
                            </svg>                         
                        </span>
                    </a>
                    @else
                        <a href="/change-order/{{$saleorder['salesorderno']}}" class="btn btn-primary btn-rounded text-capitalize text-dark open-view-details" target="_blank">
                            view details
                        </a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>