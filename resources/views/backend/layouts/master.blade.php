<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @include('layouts.favicon')    
    <title>@yield('title')</title>
	<link rel="icon" href="/assets/favicons/favicon-Web-32.png" type="image/png" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('backend.layouts.partials.styles')
    @yield('styles')
</head>

<body class="admin-portal">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <!--<div id="preloader">
        <div class="loader"></div>
    </div> -->
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container wrapper">
       @include('backend.layouts.partials.sidebar')
        <!-- main content area start -->
        <section class="home-section position-relative main-content">
            @include('backend.layouts.partials.header')
            @yield('admin-content')
            @include('backend.layouts.partials.search_modal')
            {{-- welcome message work start --}}
            {{-- <div class="wm_card">
                <div class="wm_icon">
                    <img src="/assets/images/svg/user_logo.png" id="nav-bar-profile-img_admin" class="rounded-circle"/>
                </div>
                <div class="wm_msg">
                    <div class="name">Hey Admin</div>
                    <div class="msg">Lorem ipsum dolor sit amet.</div>
                </div>
            </div> --}}
            {{-- welcome message work end --}}
            {{-- notification work start --}}
            <div id="bottom_notification_disp"></div>  
			{{-- <x-bottom-notification-component :notifications="$notifications" /> --}}
            {{-- notification work end --}}
            {{-- <input type="hidden" name="admin_user_id" value="{{Auth::guard('admin')->user()->id}}" id="admin_user_id">
            <input type="hidden" name="admin_type" value="1" id="admin_type"> --}}
        </section>
        <!-- main content area end -->
        @include('backend.layouts.partials.footer')
    </div>
    <!-- page container area end -->

    @include('backend.layouts.partials.offsets')
    @include('backend.layouts.partials.scripts')
    @yield('scripts')
</body>

</html>
