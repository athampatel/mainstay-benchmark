@extends('layouts.dashboard')

@section('title')
{{config('constants.page_title.customers.help')}} - Benchmark
@endsection

@section('content')
<div class="backdrop d-none">
    <div class="loader"></div>
</div>
<div class="home-content">
    {{-- <h1 class="page_title px-5 pt-3">Help</h1> --}}
    <h1 class="page_title px-5 pt-3">Submit Help Request</h1>
    <div class="overview-boxes widget_container_cards col-12">
        <div class="row row-cols-1 col-12 result-data flex-wrap sm-rverse-flex">
            <div class="col-12 col-md-12  co-lg-12">
                <div class="alert alert-success text-center d-none" id="help-message-alert"></div>
                <div class="card box">	
                    <div class=" profile-header card-header col-12 p-3 d-flex align-items-center">
                        <div class="col-12 d-flex align-items-center">
                            <div class="box-icon small-icon rounder-border">
                                @if(Auth::user()->profile_image && File::exists(Auth::user()->profile_image))
                                    <img id="account-detail-profile-img" src="/{{Auth::user()->profile_image}}" class="rounded-circle" style="max-width:100%;height:100%;" />
                                @else
                                    <img id="account-detail-profile-img" src="/assets/images/profile_account_img2.png" class="rounded-circle" style="max-width:100%; height:100%" />
                                @endif
                            </div>  
                        </div>                    
                    </div>    					
                    <div class="card-body col-12">
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label" for="acc_name">Your Name</label>
                                <input class="form-control col-12 box-shadow-none" type="text" value="{{Auth::user()->name}}" placeholder="Name" name="user_name" id="user_name" autocomplete="off">
                            </div>
                            <div class="mb-3 col-6">    
                                <label class="form-label">Your Email Address</label>
                                <input class="form-control col-12 box-shadow-none" type="text" value="{{Auth::user()->email}}" placeholder="Email Address" name="email_address" id="email_address" autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">    
                                <label class="form-label">Your Phone Number</label>
                                <input class="form-control col-12 box-shadow-none" type="text" value="" placeholder="Phone Number" name="phone_no" id="phone_no" autocomplete="off">
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-12">    
                                <label class="form-label">Message</label>
                                <textarea name="message" id="help_textarea" cols="30" rows="15" class="col-12 box-shadow-none" placeholder="Message.." autocomplete="off"></textarea>
                            </div>          
                        </div>
                    </div>                     
                </div>
                <button type="submit" class="btn btn-primary text-capitalize btn-rounded btn-larger float-right" id="help-save-button">Send Request</button>
            </div>
        </div>
    </div>
</div>            
@endsection

@php 
    $version = time(); 
@endphp

@section('scripts')
    <script src="/assets/js/help-page.js?v={{$version}}"></script>
    <script>
        const constants = <?php echo json_encode($constants); ?>;
        const searchWords = <?php echo json_encode($searchWords); ?>;
    </script>
@endsection