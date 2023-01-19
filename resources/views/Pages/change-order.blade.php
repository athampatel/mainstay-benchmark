@extends('layouts.dashboard')

@section('content')
<div class="home-content">
    <h1 class="page_title px-5 pt-3">Change order</h1>
    <div class="overview-boxes widget_container_cards col-12">
         <div class="row row-cols-1 col-12">
            <div class="col-12">
                <div class="card box">						
                    <div class="card-body col-12 d-flex align-items-center">
                        <form method="post" class="order-form col-8 pt-3 mx-auto d-flex justify-content-between align-items-center" action="">
                            <div class="mb-3 col-4">    
                                <label for="formFile" class="form-label">Enter Purchase Order Number</label>
                                <input class="form-control  col-12" type="text" placeholder="" name="PurchaseOrderNumber" id="PurchaseOrderNumber">
                            </div>
                            <div class="mb-3 col-4">    
                                <label for="formFile" class="form-label">Enter Item Code</label>
                                <input class="form-control  col-12" type="text" placeholder="" name="ItemCode" id="ItemCode">
                            </div>
                            <div class="form-button col-3">    
                             {{-- <button type="submit" class="font-12 btn btn-primary">Get Order Details</button> --}}
                             <button class="font-12 btn btn-primary" id="get_order_details">Get Order Details</button>
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
    </div>
</div>            
@endsection