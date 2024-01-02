<!doctype html>
<html class="no-js login-blade" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('auth_title', 'Authentication - Admin Panel') - {{ config('app.name', 'Laravel') }}  </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    @include('backend.layouts.partials.styles')
    @yield('styles')
</head>

<body class="auth-login">
    <div id="preloader">
        <div class="loader"></div>
    </div>

    @yield('auth-content')
	<div class="row">
		<div class="col-12">
			<div class="footer-content">
				<div class="footer-text">
					© 2023 Benchmark Products All Rights Reserved
				</div>
			</div>
		</div>
	</div>
    @include('backend.layouts.partials.scripts')
    @yield('scripts')
</body>

</html>