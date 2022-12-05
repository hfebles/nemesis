<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('NAME_COMPANY') }} | @yield('title-section')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <link href="{{ url('/css/app.css') }}" rel="stylesheet">    
    <link href="{{ url('/themes/css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="{{ url('/fontawesome/css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('/css/toastr.min.css') }}">
    
    <script  src="{{ url('/themes/vendor/jquery/jquery.min.js') }}" type="text/javascript"></script>
    <script  src="{{ url('/themes/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('/js/toastr.min.js') }}"></script>





</head>
<body>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        @include('layouts.partials.side')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                    @include('layouts.partials.navbar')
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- top side -->
                    @include('layouts.partials.topside')
                    <!-- end top side -->
                    <!-- Content Row -->
                    <div class="row">
                        <!-- Content Column -->
                        <div class="col-lg-12 mb-4">
                            @yield('content')
                        </div>                     
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Sidebar -->
    <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" data-bs-backdrop="false" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                <div class="offcanvas-header">
                    <h4 class="offcanvas-title" id="offcanvasExampleLabel">@yield('side-title')</h4>
                    
                    <button data-bs-dismiss="offcanvas" aria-label="Close" class="btn btn-danger align-self-start ml-1">

                        <i class="fas fa-times-circle"></i>

    
                    </button>
                </div>
                <div class="offcanvas-body">
                    @yield('side-body')
                </div>
            </div>
            <!-- end sidebar -->



    @yield('js')



    
    

    <!-- Core plugin JavaScript-->

    <script  src="{{ url('/themes/vendor/jquery-easing/jquery.easing.min.js') }}" type="text/javascript"></script>

    <!-- Custom scripts for all pages-->
    <script  src="{{ url('/themes/js/sb-admin-2.min.js') }}" type="text/javascript"></script>

    <!-- Page level plugins -->

    <script  src="{{ url('/themes/vendor/chart.js/Chart.min.js') }}" type="text/javascript"></script>
    <script  src="{{ url('/js/app.js') }}" type="text/javascript"></script>
    <script  src="{{ url('/fontawesome/js/all.js') }}" type="text/javascript"></script>

    

    <!-- Page level custom scripts 
    <script src="/themes/js/demo/chart-area-demo.js"></script>
    <script src="/themes/js/demo/chart-pie-demo.js"></script>-->
    
<script>
    @if( Session::has('message') )
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true,
        "positionClass": "toast-bottom-right",
    }
            toastr.success("{{ session('message') }}");
    @endif
  
    @if(Session::has('error'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true,
        "positionClass": "toast-bottom-right",
    }
            toastr.error("{{ session('error') }}");
    @endif
  
    @if(Session::has('info'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true,
        "positionClass": "toast-bottom-right",
    }
            toastr.info("{{ session('info') }}");
    @endif
  
    @if(Session::has('warning'))
    toastr.options =
    {
        "closeButton" : true,
        "progressBar" : true,
        "positionClass": "toast-bottom-right",
    }
            toastr.warning("{{ session('warning') }}");
    @endif
  </script>
</body>
</html>
