
@extends('backend.layouts.master')

@section('title')
Export Info - Admin Panel
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
    <span class="page_title">Export Request Information</span>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="main-content-inner">
            <div class="row">
                <div class="col-12 mt-5">
                    <div class="alert alert-success d-none text-center" id="user_activate_message"></div>
                    @include('backend.layouts.partials.messages')            
                        @csrf
                        <input type="hidden" name="change_id" id="change_id" value="">
                        <div class="alert alert-success text-center d-none" id="order-change-status-display"></div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h6 class="text-secondary">Customer Information</h6><br>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="user_no">Customer Number</label>
                                                <div class="text-secondary">{{$user_detail->customerno}}</div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="user_email">Customer Name</label>
                                                <div class="text-secondary">{{$user_detail->customername}}</div>
                                            </div> 
                                        </div>
                
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="user_name">Customer Email</label>
                                                <div class="text-secondary">{{$user_detail->email}}</div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="ardivision_no">AR Division No</label>
                                                <div class="text-secondary">{{$user_detail->ardivisionno}}</div>
                                            </div>
                                        </div>
                                                        
                                        <h6 class="text-secondary">Export Details</h6><br>
                                        @if($customer_export_count->is_analysis == 1)
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="address_line_1">Start Date</label>
                                                <div class="text-secondary">{{ \Carbon\Carbon::parse($customer_export_count->start_date)->format('M d, Y') }}</div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="address_line_2">End Date</label>
                                                <div class="text-secondary">{{ \Carbon\Carbon::parse($customer_export_count->end_date)->format('M d, Y') }}</div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="address_line_3">Year</label>
                                                <div class="text-secondary">{{$customer_export_count->year}}</div>
                                            </div>
                                        </div>
                                        @else 
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="address_line_3">Request body</label>
                                                <div class="text-secondary">{{$customer_export_count->request_body}}</div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="user_state">Resource</label>
                                                <div class="text-secondary">
                                                    {{$customer_export_count->resource}}
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="user_zipcode">Requested Date</label>
                                                <div class="text-secondary">{{\Carbon\Carbon::parse($customer_export_count->created_at)->format('M d, Y')}}</div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="user_state">Type</label>
                                                <div class="text-secondary">
                                                    {{$customer_export_count->type == 1 ? 'CSV' : 'PDF'}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="card-body px-0" id="change-order-request-buttons">
                                            <button class="btn btn-success btn-rounded pr-4 pl-4" id="approve_request">Download</button>
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
    });

    $(document).on('click','#approve_request',function(e){
        e.preventDefault();
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
                $('#order-change-status-display').removeClass('d-none');
                $('#order-change-status-display').addClass('alert-success').removeClass('alert-danger').text('Order Requested Approved Successfully');
                $('#change-order-request-buttons').addClass('d-none')
                $('#change-order-request-buttons').html(action_buttons);
                setTimeout(() => {
                    $('#order-change-status-display').addClass('d-none');
                }, 2000);
                setTimeout(() => {
                    $('#change-order-request-buttons').removeClass('d-none')
                }, 1000);
            } else {
                $('#order-change-status-display').removeClass('d-none');
                $('#order-change-status-display').addClass('alert-danger').removeClass('alert-success').text('Order Requested Cancelled Successfully');
                $('#change-order-request-buttons').addClass('d-none')
                setTimeout(() => {
                    $('#order-change-status-display').addClass('d-none');
                }, 2000);
            }
        }
    }

    $(document).on('click','#sync_request',function(e){
        e.preventDefault();
    })

    function changeOrderRequestSync(res){
        console.log(res,'___change order request sync');
    }
    const searchWords = <?php echo json_encode($searchWords); ?>;
</script>
@endsection