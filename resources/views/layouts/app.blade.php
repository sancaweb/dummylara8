<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle . ' || ' . config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Google Font: Source Sans Pro add to mix -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('v1/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('v1/plugins/daterangepicker/daterangepicker.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('v1/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('v1/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('v1/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('v1/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('v1/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">


    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{ asset('v1/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">

    <!-- lightbox -->
    <link rel="stylesheet" href="{{ asset('v1/plugins/lightbox/css/lightbox.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('v1/css/adminlte.min.css') }}">



</head>

<body class="hold-transition sidebar-mini sidebar-collapse">

    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->

        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 3.1.0
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.


                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();" class="btn btn-primary"
                        type="button">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:
                    none;">
        @csrf
    </form>

    <!-- REQUIRED SCRIPTS -->

    <script src="{{ asset('js/app.js') }}"></script>

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <!-- <script src="assets/plugins/jquery/jquery.min.js"></script> -->
    <!-- Bootstrap 4 -->
    <!-- <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->

    <script src="{{ asset('v1/plugins/moment/moment.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('v1/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('v1/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('v1/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>



    <!-- Select2 -->
    <script src="{{ asset('v1/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{ asset('v1/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('v1/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('v1/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- lightbox -->
    <script src="{{ asset('v1/plugins/lightbox/js/lightbox.min.js') }}"></script>


    <script>
        let base_url = "{{ route('root') }}";
    </script>

    <!-- AdminLTE App -->
    <script src="{{ asset('v1/js/adminlte.min.js') }}"></script>

    <!-- pages js -->
    @if ($page == 'user')
        <script src="{{ asset('js/pages/user.js') }}"></script>
    @endif
    @if ($page == 'userTrash')
        <script src="{{ asset('js/pages/userTrash.js') }}"></script>
    @endif

    @if ($page == 'activity')
        <script src="{{ asset('js/pages/activity.js') }}"></script>
    @endif

    @if ($page == 'rolePermission')
        <script src="{{ asset('js/pages/rolePermission.js') }}"></script>
    @endif

    @if ($page == 'assignPermission')
        <script src="{{ asset('js/pages/assignPermission.js') }}"></script>
    @endif
    @if ($page == 'profile')
        <script src="{{ asset('js/pages/profile.js') }}"></script>
    @endif
    @if ($page == 'post')
        <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
        <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

        <script src="{{ asset('js/pages/post.js?v=1.2') }}"></script>
    @endif

    @if ($page == 'editPost')
        <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
        <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

        <script src="{{ asset('js/pages/editPost.js?v=1.1') }}"></script>
    @endif

    @if ($page == 'catTags')
        <script src="{{ asset('js/pages/category.js?v=1.1') }}"></script>
        <script src="{{ asset('js/pages/tag.js?v=1.1') }}"></script>
    @endif

    @if ($page == 'page')
        <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
        <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

        <script src="{{ asset('js/pages/page.js?v=1.2') }}"></script>
    @endif

    @if ($page == 'editPage')
        <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
        <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

        <script src="{{ asset('js/pages/editPage.js?v=1.2') }}"></script>
    @endif

    @if ($page == 'dashboard')
        <script src="{{ asset('v1/plugins/jquery-mousewheel/jquery.mousewheel.js') }}"></script>
        <script src="{{ asset('v1/plugins/raphael/raphael.min.js') }}"></script>
        <script src="{{ asset('v1/plugins/jquery-mapael/jquery.mapael.min.js') }}"></script>
        <script src="{{ asset('v1/plugins/jquery-mapael/maps/usa_states.min.js') }}"></script>
        <!-- ChartJS -->
        <script src="{{ asset('v1/plugins/chart.js/Chart.min.js') }}"></script>

        <script src="{{ asset('js/pages/dashboard.js') }}"></script>
    @endif

</body>


</html>
