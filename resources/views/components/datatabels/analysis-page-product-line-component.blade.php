<div>
    <table id="analysis-page-table" class="table bench-datatable border-0" style="width:100%">
        <thead>
            <tr>
                <th class="border-0">Item Code</th>
                <th class="border-0">Product Line</th>
                <th class="border-0">Month</th>
                <th class="border-0">Quantity Sold</th>
            </tr>
        </thead>
        <tbody>
        @foreach($analysisdata as $record)
            <tr>
                <td class="font-12">
                    <div class="py-6">
                        #{{$record['itemcode']}}
                    </div>
                </td>
                <td class="text-center pointer_events_none">{{$record['productline']}}</td>
                <td class="pointer_events_none">{{$record['fiscalcalperiod']}}</td>
                <td class="location pointer_events_none">{{$record['quantitysold']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>