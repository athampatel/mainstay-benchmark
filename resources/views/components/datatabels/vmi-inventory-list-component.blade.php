<div>
    <table id="vmi-inventory-page-table" class="table bench-datatable border-0">
        <thead>
            <tr>
                <th class="border-0" {{-- style="width:151px" --}}>Customer<br/>Item Number</th>
                <th class="border-0" {{-- style="width:151px" --}}>Benchmark <br/> Item Number</th>
                <th class="border-0" {{-- style="width:151px" --}}>Item <br/> Description</th>
                <th class="border-0" {{-- style="width:151px" --}} style="display:none">Vendor<br/>Name</th>
                <th class="border-0" {{-- style="width:151px" --}}>Qty <br/>on Hand</th>
                <th class="border-0" {{-- style="width:151px" --}} style="display:none">Purchased<br/>(Year)</th>
                <th class="border-0" {{-- style="width:151px" --}}>Quantity<br/>Counted</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($vmiProducts as $product)    
            <tr class="vmi_row">
                <td>
                    <a href="javascript:void(0)" class="item-number font-12 btn btn-rounded">@if(isset($product['aliasitemno'])){{$product['aliasitemno']}}@endif</a>
                </td>
                <td>
                    <a href="javascript:void(0)" class="customer-name itemcode">{{$product['itemcode']}}</a>
                </td>
                <td>@if(isset($product['itemcodedesc'])){{$product['itemcodedesc']}}@endif</td>
                <td style="display:none">@if(isset($product['vendorname'])){{$product['vendorname']}}@endif</td>
                <td class="qty_hand" data-val={{$product['quantityonhand']}}>{{$product['quantityonhand']}}</td>
                <td style="display:none">@if(isset($product['quantitypurchased'])) {{$product['quantitypurchased']}} @endif</td> 
                <td>
                    <input type="number" name="" id="" data-itemcode={{$product['itemcode']}} class="quantity_counted form-control1" disabled>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>