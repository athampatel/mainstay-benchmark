@extends('layouts.dashboard')

@section('title')
{{config('constants.page_title.customers.change_order')}} - Benchmark
@endsection

@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    @if($is_change_order)
    <h1 class="page_title px-5 pt-3">Open order</h1>
    @else 
    <h1 class="page_title px-5 pt-3">View order</h1>
    @endif
    <div class="overview-boxes widget_container_cards col-12 ">
         <div class="row row-cols-1 col-12 mb-2">
            <div class="col-12">
                <div class="card box">						
                    <div class="card-body col-12 d-flex align-items-center">
                        <form method="post" id="change-order-form" class="order-form col-12 col-md-12 col-lg-8 pt-3 mx-auto d-flex justify-content-between align-items-center flex-wrap" action="">
                            <div class="mb-3 col-12 col-md-4 col-lg-4">    
                                <label for="formFile" class="form-label">Enter Purchase Order Number</label>
                                <input class="form-control  col-12" type="text" placeholder="" value="{{$order_id}}" name="PurchaseOrderNumber" id="PurchaseOrderNumber" required autocomplete="off" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-4 px-md-3 px-0 px-lg-3" id="item-code-selectbox">    
                                <label for="formFile" class="form-label">Enter Item Code</label>
                                <select name="ItemCode" id="ItemCode" class="form-select" {{ $is_change_order ? '': 'disabled'}} disabled>
                                    <option value="0" selected>All</option>                                   
                                </select>
                            </div>
                            
                            <div class="form-button mt-2 col-12 col-md-3 col-lg-4"></div>
                        </form>
                    </div>
                </div>   
             </div>
        </div>       
        <div class="row row-cols-1 col-12">
            <div class="col-12">
                <div class="alert alert-success text-center d-none" id="change-order-request-response-alert">{{config('constants.change_order_request.success')}}</div>
             </div>
        </div>
        <div class="row row-cols-1 col-12 result-data">
            <div class="col-12">
                <div class="card box mb-1 mt-1">						
                    <div class="card-body col-12">
                        <h3 class="title-4 m-0">Order <span id="disp-order-id">{{$order_id}}</span></h3>
                        <input type="hidden" name="salesorderno_val" value="" id="salesorderno_val">
                        <input type="hidden" name="customerno_val" value="" id="customerno_val">
                        <input type="hidden" name="ordereddate_val" value="" id="ordereddate_val">
                    </div>      
                </div>   
             </div>
        </div>
        <div class="row row-cols-1 col-12 result-data">
            <div class="col-12">
                <div class="card box">						
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="/assets/images/svg/invoice.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">Ship To Details</h4>
                        </div>                    
                    </div>
                    @php 
                        $selected_customer = session('selected_customer');
                    @endphp
                    <div class="card-body col-12">
                        <div class="row">
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Confirm To</label>
                                <input class="form-control col-12" type="text" placeholder="Name" value="" name="Name" id="ship-to-confirm-to" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Phone Number</label>
                                <input class="form-control  col-12" type="text" value="{{$selected_customer['phone_no'] ? $selected_customer['phone_no'] : 'N/A'}}" placeholder="Phone Number" name="PhoneNumber" id="ship-to-phonenumber" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                        {{-- </div>
                        <div class="row"> --}}
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Customer P.O. Number</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Customer P.O. Number" name="customer_po_number" id="ship-to-customer_po_number" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        {{-- </div>
                        <div class="row"> --}}
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Company Name </label>
                                <input class="form-control col-12" value="{{$selected_customer['customername']}}" type="text" placeholder="Address Line 1" name="AddressLine1" id="ship-to-address1" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Address Line 1 </label>
                                <input class="form-control col-12" value="" type="text" placeholder="Address Line 1" name="AddressLine1" id="ship-to-address1" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        {{-- </div>
                        <div class="row"> --}}
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Address Line 2</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Address Line 2" name="AddressLine2" id="ship-to-address2" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        {{-- </div>
                        <div class="row"> --}}
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Address Line 3</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Address Line 3" name="AddressLine3" id="ship-to-address3" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>                            
                        {{-- </div>
                        <div class="row"> --}}
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">State</label>
                                <select class="form-control" name="State" id="ship-to-state" {{ $is_change_order ? '': 'disabled'}} disabled>
                                    <option value="" selected></option>
                                </select>
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">City</label>
                                <select class="form-control" name="City" id="ship-to-city" {{ $is_change_order ? '': 'disabled'}} disabled>
                                    <option value="" selected></option>
                                </select>
                            </div>
                        {{-- </div>
                        <div class="row"> --}}
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Zip Code</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Zip Code" name="ZipCode" id="ship-to-zipcode" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Ship Via</label>
                                <input class="form-control  col-12" type="text" value="" placeholder="Ship Via" name="ShipVia" id="shipvia" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                        </div>
                    </div>      
                </div>   
            </div>
            <div class="col-12">
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
                                <label class="form-label">Order</label>
                                <input class="form-control col-12" type="text" value="" placeholder="Order Number" name="OrderNumber" id="order-detail-order-no" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Location</label>
                                <input class="form-control  col-12" type="text" value="" placeholder="Location" name="Location" id="order-location" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Order Date</label>
                                <input class="form-control col-12" type="text" value="" placeholder="OrderDate" name="OrderDate" id="OrderDate" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Total Quantity Ordered</label>
                                <input class="form-control  col-12" type="text" value="" placeholder="Quantity Shipped" name="QuantityShipped" id="quantityShiped" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Promise Date</label>
                                <input class="form-control col-12" type="text" placeholder="Promise Date" value="" name="PromiseDate" id="promiseDate" {{ $is_change_order ? '': 'disabled'}} disabled>
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-4">    
                                <label class="form-label">Status</label>
                                <select class="form-control" name="Status" id="orderStatus" {{ $is_change_order ? '': 'disabled'}} disabled>
                                    <option value="" selected></option>
                                </select>
                            </div>                            
                        </div>
                    </div>  
                    
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="/assets/images/svg/order-details.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">Item Details</h4>
                        </div>                    
                    </div>   

                    <div class="table-responsive orderItems-table col-12 p-3">
                        <table id="orderItems" class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">Description</th>
                                    <th><div class="text-center">Benchmark<br/>Item Number</th>   
                                    <th><div class="text-center">Customer<br/>Number</th>   
                                    <th class="max-100 text-center">Quantity<br/>Ordered</th>
                                    <th class="max-100 text-center">Quantity<br/>Shipped</th>
                                    <th class="max-100 text-center">Quantity<br/>Open</th>
                                    <th><div class="text-center">Unit<br/>Price</th>
                                    <th><div class="text-center">Total Order<br/>Amount</th>
                                    <th class="text-center door-ship">Drop Ship</th>                                    
                                    @if($is_change_order)
                                    <th>&nbsp;</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="disp-items-body">
                            </tbody>                           
                        </table>
                    </div>
                   
                </div>   
                @if($is_change_order)
                <button type="submit" class="btn btn-primary btn-rounded text-capitalize btn-larger fl-right" id="order-save-button">Save Changes</button>
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
    let app_url = '{{ config('app.url') }}';
    let order_details = [];
    let changed_order_items = [];
    const constants = <?php echo json_encode($constants); ?>;
    const searchWords = <?php echo json_encode($searchWords); ?>;
</script>
@endsection