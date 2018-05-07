<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Tigpezeghe Rodrige K. @ tigrodrige@gmail.com">
        <metan name="description" content="GSoC2018: Building a report generator for LibreHealthEHR">
        <title>Report Generator - LibreEHR</title>

        <!-- Custom app.css in public/assets/css -->
        <link rel="stylesheet" href="{{URL::asset('assets/css/master.css')}}">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="{{URL::asset('assets/css/resource/jquery-ui.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('assets/css/resource/bootstrap.min.css')}}">

        <!-- jQuery and jQueryUI libraries -->
        <script src="{{URL::asset('assets/js/resource/jquery-3.3.1.min.js')}}"></script>
        <script src="{{URL::asset('assets/js/resource/jquery-ui.js')}}"></script>

        <!-- Popper JS -->
        <script src="{{URL::asset('assets/js/resource/popper.min.js')}}"></script>

        <!-- Latest compiled JavaScript -->
        <script src="{{URL::asset('assets/js/resource/bootstrap.min.js')}}"></script>

    </head>
    <body>
        @yield('content')
        <script src="{{URL::asset('assets/js/master.js')}}"></script>
    </body>
</html>
