
@extends('backend.layouts.master')

@section('title')
User Create - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
    /* admin table design */
    .admin-table .table{
        color: #fff;
    }
    .admin-table .table thead{
        background-color: #7B7C7F;
    }
    .admin-table .table>:not(caption)>*>*{
        padding: 0.5rem 0.5rem;
        background-color: transparent;
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px transparent;
    }
</style>
@endsection


@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Order Request</h4>
                <ul class="breadcrumbs pull-left">
                    {{-- <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li> --}}
                    {{-- <li><a href="{{ route('admin.users.index') }}">All Customers</a></li> --}}
                    {{-- <li><span>Create Customer</span></li> --}}
                </ul> 
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="alert alert-success d-none text-center" id="user_activate_message"></div>
            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="header-title">Change Order Information</h4>       
                </div>
            </div> 
           
            @include('backend.layouts.partials.messages')            
            {{-- <form action="{{ route('admin.users.store') }}" method="POST">                         --}}
                @csrf
                <input type="hidden" name="change_id" id="change_id" value="{{$change_id}}">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="text-secondary">Order Information</h6><br>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_no">Order No</label>
                                <div class="text-secondary">{{$order_detail['salesorderno']}}</div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_email">Customer No</label>
                                <div class="text-secondary">{{$order_detail['customerno']}}</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_name">Order Date</label>
                                <div class="text-secondary">{{$order_detail['orderdate']}}</div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="ardivision_no">AR Division No</label>
                                <div class="text-secondary">{{$order_detail['ardivisionno']}}</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_name">Order Status</label>
                                <div class="text-secondary">{{$order_detail['orderstatus']}}</div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="ardivision_no">Customer Phone Number</label>
                                <div class="text-secondary">{{$order_detail['customerpono']}}</div>
                            </div>
                        </div>

                        <h6 class="text-secondary">Ship Details</h6><br>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_1">Address Line 1</label>
                                <div class="text-secondary">{{$order_detail['shiptoaddress1']}}</div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_2">State</label>
                                <div class="text-secondary">{{$order_detail['shiptostate']}}</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="address_line_3">Address Line 2</label>
                                <div class="text-secondary">{{$order_detail['shiptoaddress2']}}</div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_city">City</label>
                                <div class="text-secondary">{{$order_detail['shiptocity']}}</div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_state">Address Line 3</label>
                                <div class="text-secondary">{{$order_detail['shiptoaddress3']}}</div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="user_zipcode">Zipcode</label>
                                <div class="text-secondary">{{$order_detail['shiptozipcode']}}</div>
                            </div>
                        </div>

                        <h6 class="text-secondary">Order Changes</h6><br>
                        
                        <div class="form-row">
                            <div class="form-group col-md-12 col-sm-12">
                                <div class="admin-table">
                                    <table class="table">
                                         <thead>
                                             <th>Item Code</th>
                                             <th>Existing Quantity</th>
                                             <th>New Quantity</th>
                                             <th>Unit Price</th>
                                         </thead>
                                         <tbody>
                                             @foreach ($changed_items as $item)    
                                                 <tr>
                                                     <td>{{$item->item_code}}</td>
                                                     <td>{{$item->existing_quantity}}</td>
                                                     <td>{{$item->modified_quantity}}</td>
                                                     <td>${{$item->order_item_price}}</td>
                                                 </tr>
                                             @endforeach
                                         </tbody>
                                    </table>
                                </div> 
                            </div>
                        </div>
                        </div>
                      </div>
                     <div class="card mb-3">
                        <div class="card-body">
                            <button class="btn btn-danger pr-4 pl-4" id="cancel_request">Decline Request</button>
                            <button class="btn btn-success pr-4 pl-4" id="approve_request">Approve Request</button>
                        </div>
                    </div>        
            {{-- </form>  --}}
                
        </div>
        <!-- data table end -->
        
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).on('click','#cancel_request',function(){
        $change_id = $('#change_id').val();
        console.log($change_id,'__decline');
        AjaxRequest(`/admin/order/request/${$change_id}/change`,'POST',{ "_token": "{{ csrf_token() }}",'change_id':$change_id,'status':'1'},'changeOrderRequestChange')
    });

    $(document).on('click','#approve_request',function(){
        $change_id = $('#change_id').val();
        console.log($change_id,'__approve');
        AjaxRequest(`/admin/order/request/${$change_id}/change`,'POST',{ "_token": "{{ csrf_token() }}",'change_id':$change_id,'status':'2'},'changeOrderRequestChange')
    });

    
    function AjaxRequest($url,$method,$data,$callback){
        $.ajax({
            type: $method,
            url: $url,
            dataType: "JSON",
            data: $data,
            success: function (res) {  
                window[$callback](res);
            }
        });
    }

    function changeOrderRequestChange(res){
        if(res.success){
            console.log(res,'___change order request changes response');
            // $('#user_activate_message').text(res.message).removeClass('d-none');
            // setTimeout(() => {
            //     $('#user_activate_message').addClass('d-none');
            //     window.location.href = "{{ url('/admin/customers/')}}";
            // }, 2000);
        }
    }
</script>
@endsection