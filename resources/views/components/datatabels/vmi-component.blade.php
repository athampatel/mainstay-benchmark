<div>
    <table id="vmi-page-table" class="table bench-datatable border-0">
        <thead>
            <tr>
                <th class="border-0">Customer Item Number</th>
                <th class="border-0">Benchmark Item Number</th>
                <th class="border-0">Item Description</th>
                <th class="border-0">Vendor Name</th>
                <th class="border-0">Oty on Hand</th>
                <th class="border-0">Quantity purchased(Year)</th>                        
            </tr>
        </thead>
        <tbody>
        {{-- @for($i = 0; $i < 50; $i++)   --}}
        @foreach ($vmiProducts as $product)    
            <tr>
                <td><a href="javascript:void(0)" class="item-number font-12 btn btn-rounded">#8974224</a></td>
                <td><a href="javascript:void(0)" class="customer-name">{{$product['itemcode']}}</a></td>
                <td>{{$product['itemcodedesc']}}</td>
                <td>{{$product['vendorname']}}</td>
                <td>2555</td>
                <td>582155</td>
            </tr>
        @endforeach
        {{-- @endfor --}}
        {{-- <tr>
            <td><a href="javascript:void(0)" class="item-number font-12 btn btn-rounded">#8974224</a></td>
            <td><a href="javascript:void(0)" class="customer-name">BC18765451</a></td>
            <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum fermentum ut augue sit amet molestie.</td>
            <td>MAYTEX CROP</td>
            <td>2555</td>
            <td>582155</td>
        </tr> --}}
        </tbody>
    </table>
</div>