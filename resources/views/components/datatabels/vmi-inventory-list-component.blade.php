<div>
    <table id="vmi-inventory-page-table" class="table bench-datatable border-0">
        <thead>
            <tr>
                <th class="border-0" {{-- style="width:151px" --}}>Customer<br/>Item Number</th>
                <th class="border-0" {{-- style="width:151px" --}}>Benchmark <br/> Item Number</th>
                <th class="border-0" {{-- style="width:151px" --}}>Item <br/> Description</th>
                <th class="border-0" {{-- style="width:151px" --}}>Vendor<br/>Name</th>
                <th class="border-0" {{-- style="width:151px" --}}>Qty <br/>on Hand</th>
                <th class="border-0" {{-- style="width:151px" --}}>Purchased<br/>(Year)</th>
                <th class="border-0" {{-- style="width:151px" --}}>Quantity<br/>Counted</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($vmiProducts as $product)    
            <tr class="vmi_row">
                <td>
                    <a href="javascript:void(0)" class="item-number font-12 btn btn-rounded">#{{$product['aliasitemno']}}</a>
                </td>
                <td>
                    <a href="javascript:void(0)" class="customer-name itemcode">{{$product['itemcode']}}</a>
                </td>
                <td>{{$product['itemcodedesc']}}</td>
                <td>{{$product['vendorname']}}</td>
                <td class="qty_hand" data-val={{$product['quantityonhand']}}>{{$product['quantityonhand']}}</td>
                <td>{{$product['quantitypurchased']}}</td>
                <td>
                    <input type="number" name="" id="" data-itemcode={{$product['itemcode']}} class="quantity_counted form-control1" disabled>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>