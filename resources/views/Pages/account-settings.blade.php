@extends('layouts.dashboard')

@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    <h1 class="page_title px-5 pt-3">Account Settings</h1>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="row row-cols-1 col-12 result-data">
            <div class="col-12">
                <div class="card box">						
                    <div class="card-body col-12">
                        <h3 class="title-4 m-0 text-primary"><span>{{Auth::user()->name}}</span></h3>
                    </div>      
                </div>   
             </div>
        </div>
        <div class="row row-cols-1 col-12 result-data">
            <div class="col-3">
                <div class="card box">						
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="assets/images/svg/invoice.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">Edit Customer Details</h4>
                        </div>                    
                    </div>
                    <div class="card-body col-12">
                        <div class="">
                            {{-- <input type="file" name="" id="profile_image_edit">
                            <button id="photo_image_upload">Save Photo</button> --}}
                            <img src="/assets/images/profile_account_img2.png" alt="profile Image" height="182" width="182">
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Name</label>
                                <input class="form-control col-12" type="text" placeholder="Name" name="AddressLine1" id="ship-to-address1">
                            </div>                            
                        </div>
                        <div class="title-5 text-white">Change Password</div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Password</label>
                                <input class="form-control col-12" type="text" placeholder="Password" name="AddressLine2" id="ship-to-address2">
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Confirm Password</label>
                                <input class="form-control col-12" type="text" placeholder="Confirm Password" name="AddressLine3" id="ship-to-address3">
                            </div>                            
                        </div>
                    </div>      
                </div>  
                <button type="submit" class="btn btn-primary" id="order-save-button">Update Changes</button> 
            </div>
            <div class="col-9">
                {{-- <div class="card box">	
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="assets/images/svg/order-details.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">Order Details</h4>
                        </div>                    
                    </div>    					
                    <div class="card-body col-12">
                        <div class="row">
                            <div class="mb-3 col-4">    
                                    <label class="form-label">Order Number</label>
                                    <input class="form-control col-12" type="text" placeholder="Order Number" name="OrderNumber" id="order-detail-order-no">
                            </div>
                            <div class="mb-3 col-4">    
                                <label class="form-label">Location</label>
                                <input class="form-control  col-12" type="text" placeholder="Location" name="Location" id="order-location">
                            </div>
                            <div class="mb-3 col-4">    
                                <label class="form-label">Alias Item Number</label>
                                <input class="form-control  col-12" type="text" placeholder="Alias Item Number" name="AliasItemNumber" id="AliasItemNumber">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-4">    
                                    <label class="form-label">Order Date</label>
                                    <input class="form-control col-12" type="text" placeholder="OrderDate" name="OrderDate" id="OrderDate">
                            </div>
                            <div class="mb-3 col-4">    
                                <label class="form-label">Drop Ship</label>
                                <select class="form-control" name="DropShip" id="DropShip">
                                    <option value="" selected>Drop Ship</option>
                                </select>
                            </div>
                            <div class="mb-3 col-4">    
                                <label class="form-label">Quantity Shipped</label>
                                <input class="form-control  col-12" type="text" placeholder="Quantity Shipped" name="QuantityShipped" id="quantityShiped">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-4">    
                                    <label class="form-label">Promise Date</label>
                                    <input class="form-control col-12" type="text" placeholder="Promise Date" name="PromiseDate" id="promiseDate">
                            </div>
                            <div class="mb-3 col-4">    
                                <label class="form-label">Status</label>
                                <select class="form-control" name="Status" id="orderStatus">
                                    <option value="" selected>New Order</option>
                                </select>
                            </div>                            
                        </div>
                    </div>  
                    
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="assets/images/svg/order-details.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">item Details</h4>
                        </div>                    
                    </div>   

                    <div class="table-responsive col-12 p-2">
                        <table id="orderItems" class="table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>                                    
                                    <th>Price</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="disp-items-body">
                            </tbody>                           
                        </table>
                    </div>
                   
                </div>    --}}
                {{-- <button type="submit" class="btn btn-primary" id="order-save-button">Save Changes</button> --}}
            </div>
        </div>
    </div>
</div>            
@endsection


<style>
.backdrop{
    height: 100%;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    z-index: 2;
    background-color: rgba(255, 255, 255, .5);
}

.loader {
    position: absolute;
    border-radius: 50%;
    border: #A8CB5C 5px solid;
    border-left-color: transparent;
    width: 36px;
    height: 36px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>