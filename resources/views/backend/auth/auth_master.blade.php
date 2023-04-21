<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('auth_title', 'Authentication - Admin Panel') - {{ config('app.name', 'Laravel') }}  </title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    @include('backend.layouts.partials.styles')
    @yield('styles')
</head>

<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>

    @yield('auth-content')

    @include('backend.layouts.partials.scripts')
    @yield('scripts')
</body>

</html>