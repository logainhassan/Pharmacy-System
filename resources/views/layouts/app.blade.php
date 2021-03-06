<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('includes.head')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div id="app">
        <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                @include('includes.navbar')
            </nav>
        <!-- /.navbar -->

        @include('includes.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
      

        <!-- /.datatable script -->

        <!-- Main Footer -->
        <footer class="main-footer">  
            @include('includes.footer')
        </footer>
        
    </div>
    <!-- REQUIRED SCRIPTS -->

    @include('includes.script')
</body>
</html>
    @yield('ajaxscript')