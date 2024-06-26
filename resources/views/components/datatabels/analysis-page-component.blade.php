<div>
    <table id="analysis-page-table" class="table bench-datatable border-0" style="width:100%">
        <thead>
            <tr>
                <th class="border-0">Invoice</th>
                <th class="border-0 text-center">Invoice Date</th>
                <th class="border-0">Customer PO Number</th>
                {{-- <th class="border-0">Location</th> --}}
                <th class="border-0">Ship to Location</th>
                {{-- <th class="border-0">Total Number of Items</th> --}}
                <th class="border-0">Total Item(s)</th>
                <th class="border-0">Total Invoiced Amount</th>
                <th class="border-0">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($analysisdata as $record)
        @php 
        $date = DateTime::createFromFormat('Y-m-d',$record['invoicedate']);
        $total_quantity = 0;
        $total_amount = 0;
        foreach($record['details'] as $rec){
            $total_quantity = $total_quantity + intval($rec['quantityshipped']);
            $total_amount = $total_amount + (intval($rec['quantityshipped']) * intval($rec['unitprice']));
        }
        @endphp
            <tr>
                <td class="font-12">{{$record['invoiceno']}}</td>
                <td class="text-center pointer_events_none">{{$date->format('M d, Y')}}</td>
                {{-- {{dd($record,$record['customerpono'])}} --}}
                <td class="pointer_events_none">{{$record['customerpono'] != "" ? $record['customerpono'] : "N/A"}}</td>
                <td class="location pointer_events_none">
                    <span class="svg-icon location-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="8.542" height="11.46" viewBox="0 0 8.542 11.46"><path class="location-svg" d="M260.411,154a4.266,4.266,0,0,0-4.266,4.266c0,2.494,2.336,5.48,3.551,6.872a.952.952,0,0,0,1.428,0c1.217-1.385,3.563-4.37,3.563-6.872A4.266,4.266,0,0,0,260.411,154Zm0,6.7a2.439,2.439,0,1,1,1.724-.714A2.438,2.438,0,0,1,260.411,160.7Z" transform="translate(-256.145 -154)" fill="#9fcc47"/></svg>
                    </span>
                    {{$record['shiptocity']}}
                </td>
                <td class="pointer_events_none">{{$total_quantity}}</td>
                <td class="pointer_events_none">${{number_format($total_amount,2,".",",")}}</td>
                <td class="action">
                    <a href="/invoice-detail/{{$record['salesorderno']}}" class="btn btn-primary btn-rounded text-capitalize text-dark open-view-details" target="_blank">
                        view details
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>