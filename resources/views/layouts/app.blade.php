<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('dist/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('dist/plugins/select2/css/select2.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <!-- Livewire Styles -->
        <livewire:styles />
        @stack('css')


        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body class="layout-fixed layout-navbar-fixed">
        <div class="wrapper">
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60" width="60">
            </div>
            @include('layouts.shared.navbar')

            @include('layouts.shared.sidebar')
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                @if(isset($breadcrumb))
                    {{ $breadcrumb }}
                @endif

                <!-- Main content -->
                <section class="content">
                    {{ $slot }}
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            @include('layouts.shared.footer')

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>

        <script src="{{ asset('dist/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('dist/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
        <!-- Select2 -->
        <script src="{{ asset('dist/plugins/select2/js/select2.full.min.js') }}"></script>
        <!-- Livewire Script -->
        <livewire:scripts />
        @stack('js')
    </body>
</html>
