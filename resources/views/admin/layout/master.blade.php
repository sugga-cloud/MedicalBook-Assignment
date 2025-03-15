<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    @include('admin.includes.head')
    @yield('css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    {{--<div class="preloader">--}}
        {{--<div class="lds-ripple">--}}
            {{--<div class="lds-pos"></div>--}}
            {{--<div class="lds-pos"></div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <div id="main-wrapper">
       @include('admin.includes.navigation')
       @include('admin.includes.sidebar')

       @yield('content')

    </div>
    @include('admin.includes.scripts')

        @yield('js')

</body>

</html>