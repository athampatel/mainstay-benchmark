@extends('layouts.dashboard')

@section('content')
<div class="home-content">
    <h1 class="page_title px-5 pt-3">Change order</h1>
    <div class="overview-boxes widget_container_cards col-12">
         <div class="row row-cols-1 col-12">
            <div class="col-12">
                <div class="card box">						
                    <div class="card-body col-12 d-flex align-items-center">
                        <form method="post" id="change-order-form" class="order-form col-8 pt-3 mx-auto d-flex justify-content-between align-items-center" action="">
                            <div class="mb-3 col-4">    
                                <label for="formFile" class="form-label">Enter Purchase Order Number</label>
                                <input class="form-control  col-12" type="text" placeholder="" name="PurchaseOrderNumber">
                            </div>
                            <div class="mb-3 col-4">    
                                <label for="formFile" class="form-label">Enter Item Code</label>
                                <input class="form-control  col-12" type="text" placeholder="" name="ItemCode">
                            </div>
                            <div class="form-button col-3 position-relative">                                    
                                <button type="submit" class="font-12 btn btn-primary">Get Order Details</button>
                            </div>
                        </form>
                    </div>
                </div>   
             </div>
        </div>
        <div class="row row-cols-1 col-12">
            <div class="col-12">
                <div class="card box">						
                    <div class="card-body col-12 d-flex align-items-center min-height">
                           <div class="empty-record h-100 col-12 text-center">
                            <img src="assets/images/svg/change-order.svg" class="h-50" />
                           </div> 
                    </div>
                </div>   
             </div>
        </div>
        <div class="row row-cols-1 col-12 result-data d-none">
            <div class="col-12">
                <div class="card box">						
                    <div class="card-body col-12">
                        <h3 class="title-4 m-0">Order <span>#0022668811</span></h3>
                    </div>      
                </div>   
             </div>
        </div>
        <div class="row row-cols-1 col-12 result-data d-none">
            <div class="col-4">
                <div class="card box">						
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                <img src="assets/images/svg/invoice.svg" />
                            </div>  
                            <h4 class="mb-0 title-5">Ship To Details</h4>
                        </div>                    
                    </div>
                    <div class="card-body col-12">
                        <div class="row">
                            <div class="mb-3 col-6">    
                                    <label class="form-label">Name</label>
                                    <input class="form-control col-12" type="text" placeholder="Name" name="Name">
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">Phone Number</label>
                                <input class="form-control  col-12" type="text" placeholder="Phone Number" name="PhoneNumber">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                    <label class="form-label">Email Address</label>
                                    <input class="form-control col-12" type="text" placeholder="Email Address" name="EmailAddress">
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                    <label class="form-label">Address Line 1 </label>
                                    <input class="form-control col-12" type="text" placeholder="Address Line 1" name="AddressLine1 ">
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                    <label class="form-label">Address Line 2</label>
                                    <input class="form-control col-12" type="text" placeholder="Address Line 2" name="AddressLine1">
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label">State</label>
                                <select class="form-control" name="State">
                                    <option value="">State</option>
                                </select>
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">City</label>
                                <select class="form-control" name="City">
                                    <option value="">City</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">    
                                    <label class="form-label">Zip Code</label>
                                    <input class="form-control col-12" type="text" placeholder="Zip Code" name="ZipCode">
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">Ship Via</label>
                                <input class="form-control  col-12" type="text" placeholder="Ship Via" name="ShipVia">
                            </div>
                        </div>

                    </div>      
                </div>   
            </div>
            <div class="col-8">
                <div class="card box">	
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
                                    <input class="form-control col-12" type="text" placeholder="Order Number" name="OrderNumber">
                            </div>
                            <div class="mb-3 col-4">    
                                <label class="form-label">Location</label>
                                <input class="form-control  col-12" type="text" placeholder="Location" name="Location">
                            </div>
                            <div class="mb-3 col-4">    
                                <label class="form-label">Alias Item Number</label>
                                <input class="form-control  col-12" type="text" placeholder="Alias Item Number" name="AliasItemNumber">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-4">    
                                    <label class="form-label">Order Date</label>
                                    <input class="form-control col-12" type="text" placeholder="OrderDate" name="OrderDate">
                            </div>
                            <div class="mb-3 col-4">    
                                <label class="form-label">Drop Ship</label>
                                <select class="form-control" name="DropShip">
                                    <option value="">Drop Ship</option>
                                </select>
                            </div>
                            <div class="mb-3 col-4">    
                                <label class="form-label">Quantity Shipped</label>
                                <input class="form-control  col-12" type="text" placeholder="Quantity Shipped" name="QuantityShipped">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-4">    
                                    <label class="form-label">Promise Date</label>
                                    <input class="form-control col-12" type="text" placeholder="Promise Date" name="PromiseDate">
                            </div>
                            <div class="mb-3 col-4">    
                                <label class="form-label">Status</label>
                                <select class="form-control" name="Status">
                                    <option value="">New Order</option>
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
                            <tbody>
                                <tr>
                                    <td>Format Parts/protections <br/>
                                    Item Code: <a href="javascript:void(0)" class="item-number font-12">W-99-25IR</a></td>                                    
                                    <td>300</td>
                                    <td>$ 5.50</td>
                                    <td>$ 1,650.00</td>
                                    <td>
                                        <a href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.899" height="16.87" viewBox="0 0 16.899 16.87">
                                                <g class="pen" transform="translate(-181.608 -111.379)">
                                                    <path id="Path_955" data-name="Path 955" d="M197.835,114.471,195.368,112a1.049,1.049,0,0,0-1.437,0l-11.468,11.5a.618.618,0,0,0-.163.325l-.434,3.552a.52.52,0,0,0,.163.461.536.536,0,0,0,.38.163h.054l3.552-.434a.738.738,0,0,0,.325-.163l11.5-11.5a.984.984,0,0,0,.3-.7,1.047,1.047,0,0,0-.3-.732Zm-12.119,12.038-2.684.325.325-2.684,9.76-9.76,2.359,2.359Zm10.519-10.546-2.359-2.332.786-.786,2.359,2.359Z" transform="translate(0 0)" fill="#9fcc47" stroke="#9fcc47" stroke-width="0.5"/>
                                                </g>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Format Parts/protections <br/>
                                    Item Code: <a href="javascript:void(0)" class="item-number font-12">W-99-25IR</a></td>                                    
                                    <td>300</td>
                                    <td>$ 5.50</td>
                                    <td>$ 1,650.00</td>
                                    <td>
                                        <a href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.899" height="16.87" viewBox="0 0 16.899 16.87">
                                                <g class="pen" transform="translate(-181.608 -111.379)">
                                                    <path id="Path_955" data-name="Path 955" d="M197.835,114.471,195.368,112a1.049,1.049,0,0,0-1.437,0l-11.468,11.5a.618.618,0,0,0-.163.325l-.434,3.552a.52.52,0,0,0,.163.461.536.536,0,0,0,.38.163h.054l3.552-.434a.738.738,0,0,0,.325-.163l11.5-11.5a.984.984,0,0,0,.3-.7,1.047,1.047,0,0,0-.3-.732Zm-12.119,12.038-2.684.325.325-2.684,9.76-9.76,2.359,2.359Zm10.519-10.546-2.359-2.332.786-.786,2.359,2.359Z" transform="translate(0 0)" fill="#9fcc47" stroke="#9fcc47" stroke-width="0.5"/>
                                                </g>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>                                		
                            </tbody>                           
                        </table>
                    </div>
                   
                </div>   
                <button type="submit" class="btn btn-primary">Save Changes</button>
             </div>
        </div>
    </div>
</div>            
@endsection