@extends('layouts.dashboard')

@section('title')
{{config('constants.page_title.customers.change_order')}} - Benchmark
@endsection

@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    <h1 class="page_title px-5 pt-3">Change Order Info</h1>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="row row-cols-1 col-12 result-data flex-wrap sm-rverse-flex">
            <div class="col-12 col-md-12  co-lg-12">
                <div class="alert alert-success text-center d-none" id="help-message-alert"></div>
                <div class="card box">	
                    <div class="card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                        </div>                    
                    </div>    					
                    <div class="card-body col-12">
                        <input type="hidden" id="order_no" value="{{$order_request->order_no}}">
                        <input type="hidden" id="request_id" value="{{$order_request->id}}">
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label" for="acc_name">Order</label>
                                <input class="form-control col-12 box-shadow-none" type="text" value="{{$order_request->order_no}}" placeholder="Name" name="user_name" id="user_name" autocomplete="off" disabled>
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">Order Date</label>
                                <input class="form-control col-12 box-shadow-none" type="text" value="{{ \Carbon\Carbon::parse($order_request->ordered_date)->format('M d, Y') }}" placeholder="Email Address" name="email_address" id="email_address" autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label">Requested Date</label>
                                <input class="form-control col-12 box-shadow-none" type="text" value="{{ \Carbon\Carbon::parse($order_request->created_at)->format('M d, Y') }}" placeholder="Phone Number" name="phone_no" id="phone_no" autocomplete="off">
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">Requested Status</label>
                                @php
                                $status = $order_request->request_status == 0 ? 'In Progress' : ( $order_request->request_status == 1 ? 'Approved' : 'Declined') ;
                                @endphp
                                <input class="form-control col-12 box-shadow-none" type="text" value="{{$status}}" placeholder="Phone Number" name="phone_no" id="phone_no" autocomplete="off">
                            </div>
                        </div>
                        <label class="form-label">Item Details</label>
                        <div class="row">
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
                                                @foreach ($order_information as $item)    
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
            </div>
            @if($order_request->request_status == 0)
            <button type="submit" class="btn mt-3 btn-danger bm-btn-danger text-capitalize btn-rounded" id="cancel-request-button">Cancel Request</button>
            @endif
        </div>
    </div>
</div>            
@endsection

@section('scripts')
    <script>
        const constants = <?php echo json_encode($constants); ?>;
        const searchWords = <?php echo json_encode($searchWords); ?>;

        $(document).on('click','#cancel-request-button',function(e){
            e.preventDefault();
            let order_no = $('#order_no').val();
            let request_id = $('#request_id').val();
            $.ajax({
                type: 'POST',
                url: '/cancelChangeRequest',
                dataType: "JSON",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: { "order_no" : order_no,'request_id': request_id},
                beforeSend:function(){
                },
                success: function (res) {  
                    if(res.success){
                       $('#help-message-alert').addClass('alert-success').removeClass('alert-danger').removeClass('d-none').text(res.message); 
                    } else {
                        $('#help-message-alert').removeClass('alert-success').addClass('alert-danger').removeClass('d-none').text(res.message);
                    }

                    setTimeout(() => {
                        $('#help-message-alert').addClass('d-none');
                        window.location = '/requests/change_orders';
                    }, 2000);
                },
                complete:function(){
                }
            });
        })
    </script>
@endsection