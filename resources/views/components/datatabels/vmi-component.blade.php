<div>
    <table id="vmi-page-table" class="table bench-datatable border-0" style="width:100%;">
        <thead>
            <tr>
                <th class="border-0">Customer Item #</th>
                <th class="border-0">Benchmark Item #</th>
                <th class="border-0">Item Description</th>
                <th class="border-0" style="display:none">Vendor Name</th>
                {{-- <th class="border-0">Oty on Hand</th> --}}
                <th class="border-0" >Quantity on Hand</th>
                <th class="border-0" style="display:none">Quantity Purchased (Year)</th>                                                
            </tr>

        </thead>
        <tbody>
            @foreach ($vmiProducts as $product)    
                <tr>
                    <td class=""><a href="javascript:void(0)" class="font-12 pointer_events_none sorting_1" style="text-decoration:none">#{{$product['aliasitemno']}}</a></td>
                    <td><a href="javascript:void(0)" class="customer-name text-decoration-none">{{$product['itemcode']}}</a></td>
                    <td>@if(isset($product['itemcodedesc'])){{$product['itemcodedesc']}}@endif</td>
                    <td style="display:none">@if(isset($product['vendorname'])){{$product['vendorname']}}@endif</td>
                    <td class="qty_hand" data-val={{$product['quantityonhand']}}>{{$product['quantityonhand']}}</td>
                    <td style="display:none">@if(isset($product['quantitypurchased'])) {{$product['quantitypurchased']}} @endif</td> 
                </tr>
            @endforeach
        </tbody>
    </table>
</div>