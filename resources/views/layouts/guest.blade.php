<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Share\Plus</title>

    <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @livewireStyles
</head>
<body class="bg-light">
    <div class="container">
        <main>{{$slot}}</main>
    </div>
    @livewireScripts
    <script src="{{asset('bootstrap/bootstrap.min.js')}}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
      </script>
      
</body>
</html>