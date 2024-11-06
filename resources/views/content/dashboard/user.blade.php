@extends('layouts.admin') 
@section('content')
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <p>Hello, User!</p>
@endsection

        @push('scripts')
              <!--morris JavaScript -->
        <script src="{{asset('../assets/node_modules/raphael/raphael-min.js')}}"></script>
        <script src="{{asset('../assets/node_modules/morrisjs/morris.min.js')}}"></script>
        <script src="{{asset('../assets/node_modules/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
        <!-- Popup message jquery -->
        <script src="{{asset('../assets/node_modules/toast-master/js/jquery.toast.js')}}"></script>
        <!-- Chart JS -->
        <script src="{{asset('dist/js/dashboard1.js')}}"></script>
        @endpush