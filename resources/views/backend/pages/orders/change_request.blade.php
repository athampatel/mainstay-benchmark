
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
    .admin-table .table{
        color: #fff;
    }
    .admin-table .table th{
        color: #fff;
    }
    .table td, .table th{
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

<div class="home-content">
    <span class="page_title">Change Order Request</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="alert alert-success d-none text-center" id="user_activate_message"></div>
                    @include('backend.layouts.partials.messages')            
                        @csrf
                        <input type="hidden" name="change_id" id="change_id" value="{{$change_id}}">
                        <div class="alert alert-success text-center d-none" id="order-change-status-display"></div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
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
                                                <div class="text-secondary">@if(isset($order_detail['shiptoaddress3'])) {{$order_detail['shiptoaddress3']}} @endif </div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="user_zipcode">Zipcode</label>
                                                <div class="text-secondary">{{$order_detail['shiptozipcode']}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
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
                                        <div class="mb-3">
                                            <div class="card-body px-0" id="change-order-request-buttons">
                                            @if($change_request['request_status'] == 2)
                                            <div class="alert alert-danger text-secondary font-xs btn-rounded">This Request was Declined</div>
                                            @elseif($change_request['request_status'] == 1)
                                                <div class="alert alert-success text-secondary font-xs btn-rounded">This Request was Approved</div>
                                                <button class="btn btn-success pr-4 pl-4 btn-rounded" id="approve_sync">Sent to Sage</button>
                                            @else
                                                <button class="btn btn-danger btn-rounded pr-4 pl-4" id="cancel_request">Decline Request</button>
                                                <button class="btn btn-success btn-rounded pr-4 pl-4" id="approve_request">Approve Request</button>
                                            @endif
                                            </div>
                                        </div> 
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>

    $(document).on('click','#cancel_request',function(e){
        e.preventDefault();
        $change_id = $('#change_id').val();
        AjaxRequest(`/admin/order/request/${$change_id}/change`,'POST',{ "_token": "{{ csrf_token() }}",'change_id':$change_id,'status':'2'},'changeOrderRequestChange')
    });

    $(document).on('click','#approve_request',function(e){
        e.preventDefault();
        $change_id = $('#change_id').val();
        AjaxRequest(`/admin/order/request/${$change_id}/change`,'POST',{ "_token": "{{ csrf_token() }}",'change_id':$change_id,'status':'1'},'changeOrderRequestChange')
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
            if(res.data.status == 1){
                let action_buttons = `<button class="btn btn-success btn-rounded pr-4 pl-4" id="sync_request">Sent to SAGE</button>`
                $('#change-order-request-buttons').addClass('d-none')
                $('#change-order-request-buttons').html(action_buttons);
                setTimeout(() => {
                    $('#change-order-request-buttons').removeClass('d-none')
                }, 2000);
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Order Requested Approved Successfully',
                    showConfirmButton: false,
                    timer: 2000
                })
                
            } else {
                $('#change-order-request-buttons').addClass('d-none')
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Order Requested Cancelled Successfully',
                    showConfirmButton: false,
                    timer: 2000
                  })
            }
        }
    }

    $(document).on('click','#sync_request',function(e){
        e.preventDefault();
        $change_id = $('#change_id').val();
        AjaxRequest(`/admin/order/request/${$change_id}/sync`,'POST',{ "_token": "{{ csrf_token() }}",'change_id':$change_id},'changeOrderRequestSync')
    })

    function changeOrderRequestSync(res){
        console.log(res,'___change order request sync');
    }
    
    const searchWords = <?php echo json_encode($searchWords); ?>;
</script>
@endsection