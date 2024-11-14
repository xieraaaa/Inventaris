<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon.png">
	<title>Inventory</title>

	<link
		rel="stylesheet"
		href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.min.css" />
	
	<link
		rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" />

	<link
		rel="stylesheet"
		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

	@stack('styles')

	<link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">

	<!-- Morris CSS -->
	<link href="{{ asset('../assets/node_modules/morrisjs/morris.css') }}" rel="stylesheet">

	<!-- Toaster Popup message CSS -->
	<link href="{{ asset('../assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet">

	<!-- Morris CSS -->
	<link href="{{ asset('../assets/node_modules/morrisjs/morris.css') }}" rel="stylesheet">

	<!-- Dashboard 1 Page CSS -->
	<link href="{{asset('dist/css/pages/dashboard1.css')}}" rel="stylesheet">

	@vite(['resources/js/app.js'])
</head>

<body class="skin-blue fixed-layout">
	<!-- ============================================================== -->
	<!-- Preloader - style you can find in spinners.css -->
	<!-- ============================================================== -->
	<div class="preloader">
		<div class="loader">
			<div class="loader__figure"></div>
			<p class="loader__label">Elite admin</p>
		</div>
	</div>
	<!-- ============================================================== -->
	<!-- Main wrapper - style you can find in pages.scss -->
	<!-- ============================================================== -->
	<div id="main-wrapper">
		<!-- ============================================================== -->
		<!-- Topbar header - style you can find in pages.scss -->
		<!-- ============================================================== -->
		<header class="topbar">
			@include('partials.navbar')
		</header>
		<!-- ============================================================== -->
		<!-- End Topbar header -->
		<!-- ============================================================== -->
		@include ('partials.sidebar')

		<div class="page-wrapper">
			@yield('content')
		</div>
		
		@include('partials.rightbar')

	</div>

	<!-- SweetAlert -->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

	<!-- JQuery -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="{{ asset('../assets/node_modules/jquery/dist/jquery.min.js') }}"></script>

	<script>
		/**
		 * Buat semua panggilan AJAX mengikuti aturan CSRF
		 */
		$(document).ready(function() {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name=\'csrf-token\']').attr('content')
				}
			});
		});
	</script>

	<!-- Bootstrap JS -->
	<script src="{{ asset('../assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

	<!-- Perfect Scrollbar Library -->
	<script src="{{ asset('dist/js/perfect-scrollbar.jquery.min.js') }}"></script>

	<!-- Wave Effects -->
	<script src="{{ asset('dist/js/waves.js') }}"></script>

	<!-- Menu Sidebar -->
	<script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>

	<!-- Custom JavaScript -->
	<script src="{{ asset('dist/js/custom.min.js') }}"></script>   

	<!-- Datatables -->
	<script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>

	@stack('scripts')
</body>

</html>
