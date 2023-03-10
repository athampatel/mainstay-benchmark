@extends('layouts.dashboard')

@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    @php 
    $order_date = DateTime::createFromFormat('Y-m-d',$details['orderdate']);
    $invoice_date = DateTime::createFromFormat('Y-m-d',$details['invoicedate']);
    @endphp
    @if($is_change_order)
    <h1 class="page_title px-5 pt-3">Change order</h1>
    @else 
    <h1 class="page_title px-5 pt-3">Invoice Order Detail</h1>
    @endif
    <div class="overview-boxes widget_container_cards col-12 ">
         {{-- <div class="row row-cols-1 col-12 mb-2">
            <div class="col-12">
                <div class="card box">						
                    <div class="card-body col-12 d-flex align-items-center">
                        <form method="post" id="invoice-order-form" class="order-form col-12 col-md-12 col-lg-8 pt-3 mx-auto d-flex justify-content-between align-items-center flex-wrap" action="">
                            <div class="mb-3 col-12 col-md-4 col-lg-4">    
                                <label for="formFile" class="form-label">Enter Purchase Order Number</label>
                                <input class="form-control  col-12" type="text" placeholder="" value="{{$order_id}}" name="InvoicePurchaseOrderNumber" id="InvoicePurchaseOrderNumber" required autocomplete="off" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-4 px-md-3 px-0 px-lg-3" id="invoice-item-code-selectbox">    
                                <label for="formFile" class="form-label">Enter Item Code</label>
                                <select name="InvoiceItemCode" id="InvoiceItemCode" class="form-select" {{ $is_change_order ? '': 'disabled'}}>
                                    <option value="0" selected>All</option>                                   
                                </select>
                            </div>
                            
                            <div class="form-button mt-2 col-12 col-md-3 col-lg-4">
                            @if($is_change_order)    
                             <button type="submit" class="font-12 btn btn-primary btn-rounded text-capitalize" id="invoice_get_order_details">Get Order Details</button>
                            @endif
                            </div>
                        </form>
                    </div>
                </div>   
             </div>
        </div>        --}}
        <div class="row row-cols-1 col-12">
            <div class="col-12">
                <div class="alert alert-success text-center d-none" id="change-order-request-response-alert">{{config('constants.change_order_request.success')}}</div>
             </div>
        </div>
        <div class="row row-cols-1 col-12 result-data">
            <div class="col-12">
                <div class="card box mb-1 mt-1">						
                    <div class="card-body col-12">
                        <h3 class="title-4 m-0">Invoice Number <span id="disp-order-id">#{{$details['invoiceno']}} - {{$invoice_date->format('M d, Y')}}</span></h3>
                        <input type="hidden" name="salesorderno_val" value="" id="salesorderno_val">
                        <input type="hidden" name="customerno_val" value="" id="customerno_val">
                        <input type="hidden" name="ordereddate_val" value="" id="ordereddate_val">
                    </div>      
                </div>   
             </div>
        </div>
        <div class="row row-cols-1 col-12 result-data">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card box">						
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="/assets/images/svg/invoice.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">Invoice Details</h4>
                        </div>                    
                    </div>
                    <div class="card-body col-12">
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label">Name</label>
                                <input class="form-control col-12" type="text" placeholder="Name" value="{{Auth::user()->name}}" name="Name" id="ship-to-name" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">Phone Number</label>
                                <input class="form-control  col-12" type="text" placeholder="Phone Number" name="PhoneNumber" id="ship-to-phonenumber" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label">Customer Po Number</label>
                                <input class="form-control col-12" type="text" value="{{$details['customerpono']}}" placeholder="Zip Code" name="ZipCode" id="ship-to-zipcode" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">Invoice Date</label>
                                <input class="form-control  col-12" type="text" value="{{$invoice_date->format('M d, Y')}}" placeholder="Ship Via" name="ShipVia" id="shipvia" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Email Address</label>
                                <input class="form-control col-12" type="text" value="{{Auth::user()->email}}" placeholder="Email Address" name="EmailAddress" id="ship-to-email" {{ $is_change_order ? '': 'disabled'}}>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Address Line 1 </label>
                                <input class="form-control col-12" value="{{$details['shiptoaddress1']}}" type="text" placeholder="Address Line 1" name="AddressLine1" id="ship-to-address1" {{ $is_change_order ? '': 'disabled'}}>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Address Line 2</label>
                                <input class="form-control col-12" type="text" value="{{$details['shiptoaddress2']}}" placeholder="Address Line 2" name="AddressLine2" id="ship-to-address2" {{ $is_change_order ? '': 'disabled'}}>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Address Line 3</label>
                                <input class="form-control col-12" type="text" value="{{$details['shiptoaddress3']}}" placeholder="Address Line 3" name="AddressLine3" id="ship-to-address3" {{ $is_change_order ? '': 'disabled'}}>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label">State</label>
                                <select class="form-control" name="State" id="ship-to-state" {{ $is_change_order ? '': 'disabled'}}>
                                    <option value="{{$details['shiptostate']}}" selected>{{$details['shiptostate']}}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">City</label>
                                <select class="form-control" name="City" id="ship-to-city" {{ $is_change_order ? '': 'disabled'}}>
                                    <option value="{{$details['shiptocity']}}" selected>{{$details['shiptocity']}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label">Zip Code</label>
                                <input class="form-control col-12" type="text" value="{{$details['shiptozipcode']}}" placeholder="Zip Code" name="ZipCode" id="ship-to-zipcode" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">Ship Via</label>
                                <input class="form-control  col-12" type="text" value="{{$details['shipvia']}}" placeholder="Ship Via" name="ShipVia" id="shipvia" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                        </div>
                    </div>      
                </div>   
            </div>
            <div class="col-12 col-md-6 col-lg-8">
                <div class="card box mb-3">	
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="/assets/images/svg/order-details.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">Order Details</h4>
                        </div>                    
                    </div>    					
                    <div class="card-body col-12">
                        <div class="row flex-wrap">
                            <div class="mb-3 col-12 col-md-12 col-lg-4">    
                                <label class="form-label">Order Number</label>
                                <input class="form-control col-12" type="text" value="{{$details['invoiceno']}}" placeholder="Order Number" name="OrderNumber" id="order-detail-order-no" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                            <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Location</label>
                                <input class="form-control  col-12" type="text" value="{{$details['shiptocity']}}" placeholder="Location" name="Location" id="order-location" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                            <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Alias Item Number</label>
                                <input class="form-control  col-12" type="text" value="{{$details['items'][0]['aliasitemno']}}" placeholder="Alias Item Number" name="AliasItemNumber" id="AliasItemNumber" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12 col-md-12 col-lg-4">    
                                <label class="form-label">Order Date</label>
                                <input class="form-control col-12" type="text" value="{{$order_date->format('M d, Y')}}" placeholder="OrderDate" name="OrderDate" id="OrderDate" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                            <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Drop Ship</label>
                                <select class="form-control" name="DropShip" id="DropShip" {{ $is_change_order ? '': 'disabled'}}>
                                    <option value="" selected>{{$details['items'][0]['dropship'] == 1 ? 'Shipped' : 'Not Shipped'}}</option>
                                </select>
                            </div>
                            <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Quantity Shipped</label>
                                @php 
                                $total_quantity_shipped = 0;
                                foreach($details['items'] as $item){
                                    $total_quantity_shipped = $total_quantity_shipped + $item['quantityshipped'];
                                }
                                @endphp
                                <input class="form-control  col-12" type="text" value="{{$total_quantity_shipped}}" placeholder="Quantity Shipped" name="QuantityShipped" id="quantityShiped" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Promise Date</label>
                                <input class="form-control col-12" type="text" placeholder="Promise Date" value="{{$details['invoicedate']}}" name="PromiseDate" id="promiseDate" {{ $is_change_order ? '': 'disabled'}}>
                            </div>
                            <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Status</label>
                                <select class="form-control" name="Status" id="orderStatus" {{ $is_change_order ? '': 'disabled'}}>
                                    <option value="{{$details['invoiceno']}}" selected>Success</option>
                                </select>
                            </div>                            
                        </div>
                    </div>  
                    
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="/assets/images/svg/order-details.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">item Details</h4>
                        </div>                    
                    </div>   

                    <div class="table-responsive col-12 p-3">
                        <table id="orderItems" class="table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>                                    
                                    <th>Total Order Amount</th>
                                    @if($is_change_order)
                                    <th>&nbsp;</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="disp-items-body">
                               @foreach($details['items'] as $items)
                                    <tr class="order_item_row" data-val="${item.itemcode}">
                                        <td>{{$items['itemcodedesc']}}<br/>
                                        Item Code: <a href="javascript:void(0)" class="item-number font-12" data-val="${item.itemcode}">{{$items['itemcode']}}</a></td> 
                                        <td class="order_item_quantity"  data-val="${item.quantityordered}" data-start_val="${item.quantityordered}">
                                            {{$items['quantityshipped']}}
                                        </td>
                                        {{-- <td class="order_unit_price" data-val="${item.unitprice}">${{$items['unitprice']}}</td> --}}
                                        <td class="order_unit_price" data-val="${item.unitprice}">${{number_format($items['unitprice'],2,".",",")}}</td>
                                        @php 
                                        $total_price = $items['unitprice'] * $items['quantityshipped'];
                                        @endphp
                                        <td class="order_unit_total_price" data-val="${item.unitprice}">$ {{number_format($total_price,2,".",",")}}</td>
                                        @if($is_change_order)
                                        <td class="order_item_actions">    
                                            <a href="#" class="edit_order_item">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16.899" height="16.87" viewBox="0 0 16.899 16.87">
                                                    <g class="pen" transform="translate(-181.608 -111.379)">
                                                        <path id="Path_955" data-name="Path 955" d="M197.835,114.471,195.368,112a1.049,1.049,0,0,0-1.437,0l-11.468,11.5a.618.618,0,0,0-.163.325l-.434,3.552a.52.52,0,0,0,.163.461.536.536,0,0,0,.38.163h.054l3.552-.434a.738.738,0,0,0,.325-.163l11.5-11.5a.984.984,0,0,0,.3-.7,1.047,1.047,0,0,0-.3-.732Zm-12.119,12.038-2.684.325.325-2.684,9.76-9.76,2.359,2.359Zm10.519-10.546-2.359-2.332.786-.786,2.359,2.359Z" transform="translate(0 0)" fill="#9fcc47" stroke="#9fcc47" stroke-width="0.5"/>
                                                    </g>
                                                </svg>
                                            </a>
                                            <a href="#" class="d-none order-item-cancel-link" >
                                                <ion-icon name="close-outline" class="order-item-cancel"></ion-icon>
                                            </a>
                                            <a href="#" class="d-none order-item-save-link">
                                                <ion-icon name="save-outline" class="order-item-save"></ion-icon>
                                            </a>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>                           
                        </table>
                    </div>
                   
                </div>   
                @if($is_change_order)
                <button type="submit" class="btn btn-primary btn-rounded text-capitalize" id="order-save-button">Save Changes</button>
                @endif
            </div>
        </div>
    </div>
</div>            
@endsection


<style>
</style>

@section('scripts')
<script>
    let app_url = '{{ env('APP_URL') }}';
    let invoice_order_details = [];
    let changed_order_items = [];
    const constants = <?php echo json_encode($constants); ?>;
</script>
<script src="/assets/js/invoice-detail.js"></script>
@endsection