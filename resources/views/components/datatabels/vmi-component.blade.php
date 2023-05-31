<div>
    <table id="vmi-page-table" class="table bench-datatable border-0">
        <thead>
            <tr>
                <th class="border-0">Customer Item #</th>
                <th class="border-0">Benchmark Item #</th>
                <th class="border-0">Item Description</th>
                <th class="border-0">Vendor Name</th>
                {{-- <th class="border-0">Oty on Hand</th> --}}
                <th class="border-0">Quantity on Hand</th>
                <th class="border-0">Quantity Purchased (Year)</th>                                                
            </tr>
        </thead>
        <tbody>
            @foreach ($vmiProducts as $product)    
                <tr>
                    <td class=""><a href="javascript:void(0)" class="font-12 pointer_events_none sorting_1" style="text-decoration:none">#{{$product['aliasitemno']}}</a></td>
                    <td><a href="javascript:void(0)" class="customer-name text-decoration-none">{{$product['itemcode']}}</a></td>
                    <td>{{$product['itemcodedesc']}}</td>
                    <td>{{$product['vendorname']}}</td>
                    <td>{{$product['quantityonhand']}}</td>
                    <td>{{$product['quantitypurchased']}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>