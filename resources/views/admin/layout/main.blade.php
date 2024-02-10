<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Data Entry Admin</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="{{ asset('admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/themify-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/selectFX/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    @yield('style')
</head>

<body>
    <div>
        <!-- Left Panel -->
        @include('admin.layout.side_bar')
        <!-- Left Panel -->

        <!-- Right Panel -->
        <div id="right-panel" class="right-panel">
            <!-- Header-->
            @include('admin.layout.header')
            <!-- Header-->

            @yield('content')

        </div>
    </div>

    <!-- Right Panel -->
    @yield('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script src="{{ asset('admin/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>


    <script src="{{ asset('admin/vendors/chart.js/dist/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('admin/assets/js/widgets.js') }}"></script>
    <script src="{{ asset('admin/vendors/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
    <script src="{{ asset('admin/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script>
        (function($) {
            "use strict";

            jQuery('#vmap').vectorMap({
                map: 'world_en',
                backgroundColor: null,
                color: '#ffffff',
                hoverOpacity: 0.7,
                selectedColor: '#1de9b6',
                enableZoom: true,
                showTooltip: true,
                values: sample_data,
                scaleColors: ['#1de9b6', '#03a9f5'],
                normalizeFunction: 'polynomial'
            });
        })(jQuery);
    </script>
    <script>
        let table = new DataTable('#myTable', {
            sorting: false
        });
    </script>

</body>

</html>
