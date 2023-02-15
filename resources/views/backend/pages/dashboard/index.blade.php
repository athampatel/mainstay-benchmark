
@extends('backend.layouts.master')

@section('title')
Dashboard Page - Admin Panel
@endsection
@php
     $usr = Auth::guard('admin')->user();
 @endphp
 
@section('admin-content')

<div class="home-content">
    <span class="page_title">Dashboard</span>
    <div class="overview-boxes widget_container_cards col-12">
        <!-- page title area start -->
        <div class="page-title-area">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <div class="breadcrumbs-area clearfix">
                        <h4 class="page-title pull-left">Dashboard</h4>
                        <ul class="breadcrumbs pull-left">
                            <li><a href="index.html">Home</a></li>
                            <li><span>Dashboard</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- page title area end -->

<div class="main-content-inner dark-cards">
  <div class="row">
    <div class="col-lg-12">
        <div class="row">
            @if ($usr->can('admin.create') || $usr->can('admin.view') ||  $usr->can('admin.edit') ||  $usr->can('admin.delete'))
                <div class="col-12 col-md-6 col-lg-4 mt-1 mt-md-5 mb-1">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.admins.index') }}">
                                <div class="p-4 d-flex justify-content-between align-items-center">
                                    <div class="seofct-icon"><span class="icon-item"><i class="fa fa-user"></i></span> Admin/Staff Users</div>
                                    <h2>{{ $total_admins }}</h2>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6 col-lg-4 mt-1 mt-md-5 mb-1">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.admins.manager') }}">
                            <div class="p-4 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon">
                                    <span class="icon-item"><img src="assets/images/svg/region_manager_info_icon.svg"></span> Region Managers</div>
                                <h2>{{ $sales_persons }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

               

            @endif
           


            <div class="col-12 col-md-6 col-lg-4 mt-1 mt-md-5 mb-1">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.users.index') }}">
                                <div class="p-4 d-flex justify-content-between align-items-center">
                                    <div class="seofct-icon"><span class="icon-item"><svg xmlns="http://www.w3.org/2000/svg" width="45.742" height="46.948" viewBox="0 0 45.742 46.948"><g class="customer" transform="translate(-2944.398 2203)"><path class="Path_1047" data-name="Path 1047" d="M180.966,274.262c.318-.088.643-.163.974-.219a9.649,9.649,0,0,1,1.043-.094,20.61,20.61,0,0,0-12.342-9.437,10.892,10.892,0,0,1-1.511.606h-.006a11.862,11.862,0,0,1-7.332,0,10.7,10.7,0,0,1-1.518-.606A21.1,21.1,0,0,0,144.929,285v.937h30.81a7.774,7.774,0,0,1-.387-.937,8,8,0,0,1-.262-.937,8.232,8.232,0,0,1-.187-1.768,8.369,8.369,0,0,1,6.065-8.032Z" transform="translate(2799.469 -2445.766)" fill="#9fcc47"/><path class="Path_1048" data-name="Path 1048" d="M241.381,91.523a10.987,10.987,0,1,0-3.61-.606,10.928,10.928,0,0,0,3.61.606Z" transform="translate(2723.546 -2272.525)" fill="#9fcc47"/><path class="Path_1049" data-name="Path 1049" d="M429.71,357.481c-.075-.006-.144-.006-.219-.006a6.854,6.854,0,0,0-.8.044,8.242,8.242,0,0,0-.981.169,7.424,7.424,0,0,0-5.427,8.968c.019.081.044.162.062.244q.065.244.15.468a7.417,7.417,0,1,0,7.214-9.886Zm.931,11.592h-1.6v-1.6h1.6Zm.063-6.4-.406,4.241h-.912l-.412-4.241V360.71h1.73Z" transform="translate(2553.235 -2528.361)" fill="#9fcc47"/></g></svg>
                                    </span>Total Customer</div>
                                    <h2>{{ $total_customers }}</h2>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            <div class="col-12 col-md-6 col-lg-4 mt-1 mt-md-5 mb-1">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.users.index') }}?type=vmi">
                                <div class="p-4 d-flex justify-content-between align-items-center">
                                    <div class="seofct-icon"><span class="icon-item"><svg xmlns="http://www.w3.org/2000/svg" width="45.742" height="46.948" viewBox="0 0 45.742 46.948"><g class="customer" transform="translate(-2944.398 2203)"><path class="Path_1047" data-name="Path 1047" d="M180.966,274.262c.318-.088.643-.163.974-.219a9.649,9.649,0,0,1,1.043-.094,20.61,20.61,0,0,0-12.342-9.437,10.892,10.892,0,0,1-1.511.606h-.006a11.862,11.862,0,0,1-7.332,0,10.7,10.7,0,0,1-1.518-.606A21.1,21.1,0,0,0,144.929,285v.937h30.81a7.774,7.774,0,0,1-.387-.937,8,8,0,0,1-.262-.937,8.232,8.232,0,0,1-.187-1.768,8.369,8.369,0,0,1,6.065-8.032Z" transform="translate(2799.469 -2445.766)" fill="#9fcc47"/><path class="Path_1048" data-name="Path 1048" d="M241.381,91.523a10.987,10.987,0,1,0-3.61-.606,10.928,10.928,0,0,0,3.61.606Z" transform="translate(2723.546 -2272.525)" fill="#9fcc47"/><path class="Path_1049" data-name="Path 1049" d="M429.71,357.481c-.075-.006-.144-.006-.219-.006a6.854,6.854,0,0,0-.8.044,8.242,8.242,0,0,0-.981.169,7.424,7.424,0,0,0-5.427,8.968c.019.081.044.162.062.244q.065.244.15.468a7.417,7.417,0,1,0,7.214-9.886Zm.931,11.592h-1.6v-1.6h1.6Zm.063-6.4-.406,4.241h-.912l-.412-4.241V360.71h1.73Z" transform="translate(2553.235 -2528.361)" fill="#9fcc47"/></g></svg>
                                    </span>VMI Customers</div>
                                    <h2>{{ $vmi_customers }}</h2>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-md-6 col-lg-4 mt-1 mt-md-5 mb-1">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.users.index') }}?type=new">
                                <div class="p-4 d-flex justify-content-between align-items-center">
                                    <div class="seofct-icon"><span class="icon-item"><svg xmlns="http://www.w3.org/2000/svg" width="45.742" height="46.948" viewBox="0 0 45.742 46.948"><g class="customer" transform="translate(-2944.398 2203)"><path class="Path_1047" data-name="Path 1047" d="M180.966,274.262c.318-.088.643-.163.974-.219a9.649,9.649,0,0,1,1.043-.094,20.61,20.61,0,0,0-12.342-9.437,10.892,10.892,0,0,1-1.511.606h-.006a11.862,11.862,0,0,1-7.332,0,10.7,10.7,0,0,1-1.518-.606A21.1,21.1,0,0,0,144.929,285v.937h30.81a7.774,7.774,0,0,1-.387-.937,8,8,0,0,1-.262-.937,8.232,8.232,0,0,1-.187-1.768,8.369,8.369,0,0,1,6.065-8.032Z" transform="translate(2799.469 -2445.766)" fill="#9fcc47"/><path class="Path_1048" data-name="Path 1048" d="M241.381,91.523a10.987,10.987,0,1,0-3.61-.606,10.928,10.928,0,0,0,3.61.606Z" transform="translate(2723.546 -2272.525)" fill="#9fcc47"/><path class="Path_1049" data-name="Path 1049" d="M429.71,357.481c-.075-.006-.144-.006-.219-.006a6.854,6.854,0,0,0-.8.044,8.242,8.242,0,0,0-.981.169,7.424,7.424,0,0,0-5.427,8.968c.019.081.044.162.062.244q.065.244.15.468a7.417,7.417,0,1,0,7.214-9.886Zm.931,11.592h-1.6v-1.6h1.6Zm.063-6.4-.406,4.241h-.912l-.412-4.241V360.71h1.73Z" transform="translate(2553.235 -2528.361)" fill="#9fcc47"/></g></svg>
                                    </span>New Sign up Customer</div>
                                    <h2>{{ $new_customers }}</h2>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 mt-1 mt-md-5 mb-1">
                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('admin.users.change-order') }}">
                                <div class="p-4 d-flex justify-content-between align-items-center">
                                    <div class="seofct-icon"><span class="icon-item"><img src="assets/images/svg/open-orders.svg"></span> Change Order Request</div>
                                    <h2>{{ $total_customers }}</h2>
                                </div>
                            </a>
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