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
    <div class="page-container wrapper">
       @include('backend.layouts.partials.sidebar')
        <section class="home-section position-relative main-content">
            @include('backend.layouts.partials.header')
            @yield('admin-content')
            @include('backend.layouts.partials.search_modal')
            @if (Cookie::get('admin_welcome'))
                <div class="wm_card">
                    <div class="wm_icon">
                        @if(Auth::guard('admin')->user()->profile_path)
                            <img src="/{{Auth::guard('admin')->user()->profile_path}}" class="rounded-circle height-45 width-45"/>
                        @else
                            <img src="/assets/images/svg/user_logo.png" class="rounded-circle height-45 width-45"/>
                        @endif  
                    </div>
                    <div class="wm_msg">
                        <div class="name">Welcome {{Auth::guard('admin')->user()->name}}</div>
                        {{-- <div class="msg">Welcome to the portal !!!</div> --}}
                    </div>
                </div>
            @endif

            <div id="bottom_notification_disp"></div>
        </section>
        @include('backend.layouts.partials.footer')
    </div>
    @include('backend.layouts.partials.offsets')
    @include('backend.layouts.partials.scripts')
    @yield('scripts')
</body>
</html>
