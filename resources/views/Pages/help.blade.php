@extends('layouts.dashboard')

@section('content')
<div class="home-content">
    <span class="page_title">Help</span>
    <div class="">
        <div class="col-9">
            <div class="card box">
              <div class="card-header col-12 p-3">
                <div class="col-6 d-flex align-items-center">
                  <div class="box-icon">
                    <img src="assets/images/svg/home_rounded_color.svg" />
                  </div>  
                  <h4 class="mb-0 text-uppercase">Customer Sales History</h4>
                </div>
               </div>
                <div id="cutomersaleshistory" class="col-12 p-2"></div>
            </div>
         </div>	
    </div>
</div>
@endsection