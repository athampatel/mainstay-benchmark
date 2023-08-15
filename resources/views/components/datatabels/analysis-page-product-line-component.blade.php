<div>
    <table id="analysis-page-table" class="table bench-datatable border-0" style="width:100%">
        <thead>
            <tr>
                <th class="border-0">Item Code</th>
                <th class="border-0">Item Code Desc</th>
                <th class="border-0">Product Line</th>
                <th class="border-0">Alias Item No</th>
                <th class="border-0">Quantity Sold</th>
                <th class="border-0">Total</th>
                <th class="border-0">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($analysisdata as $key => $record)
            {{-- {{dd($record)}} --}}
            @if($key != 'meta')
                <tr>
                    <td class="font-12">
                        <div class="py-6">
                            #{{$record['itemcode']}}
                        </div>
                    </td>
                    <td class="text-center pointer_events_none">{{$record['itemcodedesc']}}</td>
                    <td class="text-center pointer_events_none">{{$record['productline']}}</td>
                    <td class="pointer_events_none">{{$record['aliasitemno']}}</td>
                    <td class="pointer_events_none">{{$record['quantitysold']}}</td>
                    {{-- <td class="pointer_events_none">${{$record['dollarssold']}}</td> --}}
                    <td class="pointer_events_none">${{number_format($record['dollarssold'],2,".",",")}}</td>
                    <td class="action">
                        <a href="/analysis-detail/{{$record['itemcode']}}" class="btn btn-primary btn-rounded text-capitalize text-dark open-view-details" target="_blank">
                            view details
                        </a>
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>