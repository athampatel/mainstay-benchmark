<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--favicon-->
	@include('layouts.favicon')
	<!--plugins-->
	<!--<link href="/assets/css/app.css" rel="stylesheet" /> -->
	<link href="/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->	
	<link href="/assets/css/pace.min.css" rel="stylesheet" />
	<script src="/assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="/assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	{{-- <link href="assets/css/icons.css" rel="stylesheet"> --}}
	<link href="/assets/css/dashboard.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title')</title>
	@yield('styles')
   </head>
<body class="customer-portal">
	<div class="wrapper">
		@include('layouts.menu-bar')
		<section class="home-section position-relative">
			{{-- Nav bar --}}
			@include('layouts.nav-bar')
			{{-- Home content --}}
			@yield('content')
			@include('backend.layouts.partials.search_modal')
			@if (Cookie::get('customer_welcome'))
                <div class="wm_card customer">
                    <div class="wm_icon">
						@if(Auth::user()->profile_image && File::exists(Auth::user()->profile_image))
							<img src="/{{Auth::user()->profile_image}}" height="45" width="45" class="rounded-circle" />
						@else 
							<img src="/assets/images/svg/user_logo.png" height="45" width="45" class="rounded-circle" />
						@endif
                    </div>
                    <div class="wm_msg">
                        <div class="name">Welcome {{Auth::user()->name}}</div>
                    </div>
                </div>
            @endif
			{{-- Footer --}}
			<div id="bottom_notification_disp"></div>  
			@include('layouts.footer-bar')
		</section>
	</div>	
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="/assets/js/jquery.min.js"></script>
	<script src="/assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="/assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
  	<script src="/assets/plugins/apexcharts-bundle/js/apexcharts.js"></script>
  	<!--<script src="/assets/plugins/apexcharts-bundle/js/apex-custom.js"></script> -->
	<script src="/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script src="/assets/js/moment.js"></script>
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js" integrity="sha512-/bOVV1DV1AQXcypckRwsR9ThoCj7FqTV2/0Bm79bL3YSyLkVideFLE3MIZkq1u5t28ke1c0n31WYCOrO01dsUg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="/assets/js/jquery.fixedheadertable.min.js"></script>
	<script src="/assets/js/app.js"></script>
	<script src="/assets/js/menu.js"></script>
	<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
	@yield('scripts')
	<script>
		let is_notify_admin = 0;
	</script>
	<script src="/assets/js/customer-charts.js"></script>
	<script src="/assets/js/common-functions.js"></script>
	<script src="/assets/js/notification.js"></script>
</body>
</html>

