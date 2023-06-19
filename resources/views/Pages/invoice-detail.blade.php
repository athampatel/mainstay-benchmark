@extends('layouts.dashboard')

@section('title')
{{config('constants.page_title.customers.invoice_detail')}} - Benchmark
@endsection

@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    @if($is_change_order)
    <h1 class="page_title px-5 pt-3">Change order</h1>
    @else 
    <h1 class="page_title px-5 pt-3">Invoice Order Detail</h1>
    @endif
    <div class="overview-boxes widget_container_cards col-12 ">
        <div class="row row-cols-1 col-12">
            <div class="col-12">
                <div class="alert alert-success text-center d-none" id="change-order-request-response-alert">{{config('constants.change_order_request.success')}}</div>
             </div>
            <div class="col-12">
                <div class="alert alert-danger text-center d-none order-validation-error-msg"></div>
             </div>
        </div>
        <div class="row row-cols-1 col-12 result-data">
            <div class="col-12">
                <div class="card box mb-1 mt-1">						
                    <div class="card-body col-12">
                        <h3 class="title-4 m-0">Invoice <span id="disp-order-id">#</span></h3>
                        <input type="hidden" name="orderid_val" value="{{$order_id}}" id="orderid_val">
                        <input type="hidden" name="InvoicePurchaseOrderNumber" value="" id="InvoicePurchaseOrderNumber">
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
                    @php
                    $selected_customer = session('selected_customer');
                    @endphp
                    <div class="card-body col-12">
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Company Name</label>
                                {{-- {{dd($selected_customer)}} --}}
                                <input class="form-control col-12" type="text" value="{{$selected_customer['customername'] ? $selected_customer['customername'] : 'N/A'}}" placeholder="Company Name" name="EmailAddress" id="company-to-name" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        </div>
                        {{-- <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Email Address</label>
                                <input class="form-control col-12" type="text" value="{{Auth::user()->email}}" placeholder="Email Address" name="EmailAddress" id="ship-to-email" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        </div> --}}
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Billing Email Address</label>
                                <input class="form-control col-12" type="text" value="{{$selected_customer['email'] ? $selected_customer['email'] : 'N/A'}}" placeholder="Email Address" name="Billing_email" id="ship-to-billing-email" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        </div>
                        <div class="row">
                            {{-- <div class="mb-3 col-6">    
                                <label class="form-label">Conact Name</label>
                                <input class="form-control col-12" type="text" placeholder="Name" value="{{Auth::user()->name}}" name="Name" id="ship-to-name" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div> --}}
                            <div class="mb-3 col-12">    
                                <label class="form-label">Phone Number</label>
                                <input class="form-control  col-12" type="text" placeholder="Phone Number" value="{{$selected_customer['phone_no'] ? $selected_customer['phone_no'] : 'N/A'}}" name="PhoneNumber" id="ship-to-phonenumber" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label">Customer P.O. Number</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Customer Po Number" name="customer_po_number" id="customer_po_number" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">Invoice Date</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Invoice Date" name="invoice_date" id="details_invoice_date" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Address Line 1 </label>
                                <input class="form-control col-12" value="" type="text" placeholder="Address Line 1" name="AddressLine1" id="ship-to-address1" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Address Line 2</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Address Line 2" name="AddressLine2" id="ship-to-address2" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Address Line 3</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Address Line 3" name="AddressLine3" id="ship-to-address3" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label">State</label>
                                <select class="form-control" name="State" id="ship-to-state" {{ $is_change_order ? '': 'disabled'}} disabled>
                                    <option value="" selected></option>
                                </select>
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">City</label>
                                <select class="form-control" name="City" id="ship-to-city" {{ $is_change_order ? '': 'disabled'}} disabled>
                                    <option value="" selected></option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label">Zip Code</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Zip Code" name="zipcode" id="ship-to-zipcode" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">Ship Via</label>
                                <input class="form-control  col-12" type="text" value="" placeholder="Ship Via" name="ShipVia" id="shipvia" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                        </div>
                    </div>      
                </div>   
            </div>
            {{-- Order Details --}}
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
                                <label class="form-label">Order #</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Order Number" name="OrderNumber" id="order-detail-order-no" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Location</label>
                                <input class="form-control  col-12" type="text" value="" placeholder="Location" name="Location" id="order-location" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            {{-- <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Customer Item Number</label>
                                <input class="form-control  col-12" type="text" value="" placeholder="Alias Item Number" name="AliasItemNumber" id="AliasItemNumber" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div> --}}
                            <div class="mb-3 col-12 col-md-12 col-lg-4">    
                                <label class="form-label">Order Date</label>
                                <input class="form-control col-12" type="text" value="" placeholder="OrderDate" name="OrderDate" id="OrderDate" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Total Quantity Shipped</label>
                                @php 
                                $total_quantity_shipped = 0;
                                @endphp
                                <input class="form-control  col-12" type="text" value="{{$total_quantity_shipped}}" placeholder="Quantity Shipped" name="QuantityShipped" id="quantityShiped" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Status</label>
                                <select class="form-control" name="Status" id="orderStatus" {{ $is_change_order ? '': 'disabled'}} disabled>
                                    <option value="" selected></option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="row"> --}}
                            {{-- <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Promise Date</label>
                                <input class="form-control col-12" type="text" placeholder="Promise Date" value="" name="PromiseDate" id="promiseDate" {{ $is_change_order ? '': 'disabled'}}>
                            </div> --}}
                            {{-- <div class="mb-3 col-6 col-md-6 col-lg-4">    
                                <label class="form-label">Status</label>
                                <select class="form-control" name="Status" id="orderStatus" {{ $is_change_order ? '': 'disabled'}} disabled>
                                    <option value="" selected></option>
                                </select>
                            </div>                             --}}
                        {{-- </div> --}}
                    </div>  
                    
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="/assets/images/svg/order-details.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">Item Details</h4>
                        </div>                    
                    </div>   

                    <div class="table-responsive col-12 p-3">
                        <table id="orderItems" class="table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Customer Item Number</th>                                    
                                    <th class="max-100">Quantity<br/>Ordered</th>
                                    <th class="max-100">Quantity<br/>Shipped</th>
                                    <th class="max-100">Quantity<br/>Open</th>
                                    <th>Unit Price</th>                                    
                                    <th>Total Order<br/>Amount</th>
                                    <th>Drop Ship</th>
                                </tr>
                            </thead>
                            <tbody id="disp-items-body"></tbody>                           
                        </table>
                    </div>

                    <div class="card-header col-12 p-3 d-flex align-items-center d-none" id="tracking_id_title">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="/assets/images/svg/order-details.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">Tracking Reference Ids</h4>
                        </div>                    
                    </div>
                    <div class="card-body col-12">
                        <div class="row flex-wrap">
                            <div class="my-1 col-12">    
                                <div id="tracking_container">
                                    {{-- <div class="list_item">${trackid}</div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
    </div>
</div>            
@endsection


<style>
</style>

@section('scripts')
<script>
    let app_url = '{{ config("app.url") }}';
    let invoice_order_details = "";
    const constants = <?php echo json_encode($constants); ?>;
    const searchWords = <?php echo json_encode($searchWords); ?>;
</script>
<script src="/assets/js/invoice-detail.js"></script>
@endsection