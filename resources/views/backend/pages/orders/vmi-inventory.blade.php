
@extends('backend.layouts.master')
@section('title')
VMI Inventory - Admin Panel
@endsection

@section('admin-content')

<div class="home-content">    
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title float-left mb-0">Inventory Item</h4>                   
                            <div class="clearfix"></div>                    
                                @include('backend.layouts.partials.messages')
                        </div>
                    </div>
                </div>
                <!-- data table end -->
            </div>

            <div class="row">
                <!-- data table start -->
                <div class="col-12 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" id="change-item-form" class="order-form col-8 pt-3 mx-auto d-flex justify-content-between align-items-center" action="">
                                <div class="form-row col-12 d-flex justify-content-between align-items-center">
                                    <div class="mb-3 col-9 px-3">    
                                        <label for="formFile" class="form-label">Enter Inventory Item Code</label>
                                        <input class="form-control  col-12" type="text" placeholder="" value="" name="PurchaseOrderNumber" id="PurchaseOrderNumber" required autocomplete="off">
                                    </div>
                                    
                                    <div class="form-button col-3 mt-2">                            
                                        <button type="submit" class="font-12 btn btn-primary match-btn" id="get_item_details">Get Item Details</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3 item-details d-none">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" id="update-item-form" class="order-form col-8 pt-3 mx-auto d-flex justify-content-center flex-wrap align-items-center" action="">
                                <div class="form-row col-12 d-flex justify-content-center align-items-center mb-2">
                                    <label for="formFile" class="form-label col-12 col-md-4">Item Code Description</label>
                                    <div class="col-12 col-md-8">
                                        <input class="form-control col-12 disabled" type="text" placeholder="" value="" name="ItemCodeDesc" id="ItemCodeDesc" required autocomplete="off">
                                    </div>                            
                                </div>
                                <div class="form-row col-12 d-flex justify-content-center align-items-center mb-2">
                                    <label for="formFile" class="form-label col-12 col-md-4">Product Line</label>
                                    <div class="col-12 col-md-8">
                                        <input class="form-control col-12 disabled" type="text" placeholder="" value="" name="ItemCodeDesc" id="ItemCodeDesc" required autocomplete="off">
                                    </div>                            
                                </div>

                                <div class="form-row col-12 d-flex justify-content-center align-items-center mb-2">
                                    <label for="formFile" class="form-label col-12 col-md-4">Primary Vendor No</label>
                                    <div class="col-12 col-md-8">
                                        <input class="form-control col-12 disabled" type="text" placeholder="" value="" name="PrimaryVendorNo" id="PrimaryVendorNo" required autocomplete="off">
                                    </div>                            
                                </div>

                                <div class="form-row col-12 d-flex justify-content-center align-items-center mb-2">
                                    <label for="formFile" class="form-label col-12 col-md-4">Primary AP Division No</label>
                                    <div class="col-12 col-md-8">
                                        <input class="form-control col-12 disabled" type="text" placeholder="" value="" name="PrimaryAPDivisionNo" id="PrimaryAPDivisionNo" required autocomplete="off">
                                    </div>                            
                                </div>
                                <div class="form-row col-12 d-flex justify-content-center align-items-center">
                                    <div class="form-button col-12 mt-2">                            
                                        <button type="submit" class="font-12 btn btn-primary" id="update_items">Update Inventory</button>
                                    </div>
                                </div>    
                            </form>
                        </div>
                    </div>
                </div>
                <!-- data table end -->
            </div>
        </div>
    </div>
</div>
@endsection

