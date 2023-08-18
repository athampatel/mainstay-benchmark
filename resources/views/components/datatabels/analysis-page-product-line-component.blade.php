<div>
    <table id="analysis-page-table" class="table bench-datatable border-0" style="width:100%">
        <thead>
            <tr>
                <th class="border-0">Benchamrk Item Number</th>
                <th class="border-0">Description</th>
                <th class="border-0">Product Line</th>
                <th class="border-0">Customer Item Number</th>
                @if($isItemSearch)
                <th class="border-0">Month</th>
                @endif
                <th class="border-0">Quantity Sold</th>
                <th class="border-0">Total Amount</th>
            </tr>
        </thead>
        <tbody>
        @foreach($analysisdata as $key => $record)
            @if($key != 'meta')
                <tr>
                    <td class="font-12">
                        <div class="py-6" data-item_code={{$record['itemcode']}} data-is_item_code = '0'>
                        {{-- <div class="py-6 {{!$isItemSearch ?'itemcode_click' : ''}}" data-item_code={{$record['itemcode']}} data-is_item_code = '0'> --}}
                            #{{$record['itemcode']}}
                        </div>
                    </td>
                    <td class="text-center pointer_events_none">{{$record['itemcodedesc']}}</td>
                    <td class="text-center pointer_events_none">{{$record['productline']}}</td>
                    <td class="">
                        <div class="py-6" data-item_code={{$record['aliasitemno']}} data-is_item_code = '1'>
                        {{-- <div class="py-6 {{!$isItemSearch ?'itemcode_click' : ''}}" data-item_code={{$record['aliasitemno']}} data-is_item_code = '1'> --}}
                            {{$record['aliasitemno']}}
                        </div>
                    </td>
                    @if($isItemSearch)
                    <td class="pointer_events_none">{{Carbon\Carbon::createFromDate(null, intval($record['fiscalcalperiod']), null)->shortMonthName}}</td>
                    @endif
                    <td class="pointer_events_none">{{$record['quantitysold']}}</td>
                    <td class="pointer_events_none">${{number_format($record['dollarssold'],2,".",",")}}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>