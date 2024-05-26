<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Share\Plus</title>
    <link rel="stylesheet" href="{{asset('custom/main.css')}}">
    <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="{{asset('fontawesome/css/all.css')}}">
    @livewireStyles
</head>
<body style="background-color: #f1efeff6">
    @livewire('partials.navbar')
    <div class="pb-5"></div>
    <div class="pb-4"></div>
    <main>{{$slot}}</main>

    @livewireScripts
    <script src="{{asset('bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset('fontawesome/js/all.js')}}"></script>
</body>
</html>