<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	@include('layouts.favicon')
	<!--plugins-->
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
	<link href="/assets/css/app.css" rel="stylesheet">
	<link href="/assets/css/icons.css" rel="stylesheet">
	<title>{{config('app.name')}}</title>
</head>

<body class="user-forms">
	<!--wrapper-->
	<div class="wrapper">	
		@yield("wrapper")
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="/assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="/assets/js/jquery.min.js"></script>
	<script src="/assets/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="/assets/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="/assets/js/app.js"></script>
</body>
</html>