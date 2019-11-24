<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="Sard" content="sard">
        <meta name="robots" content="index">
        <meta name="csrf-token" value="{{ csrf_token() }}" />
        <title>Laravel</title>
        <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('templates/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('templates/css/style.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    </head>
    <body>
    <div id="app"></div>

    <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    </body>


</html>
