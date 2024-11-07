<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/reset.css')}}">
    <link rel="stylesheet" href="{{ asset('css/common.css')}}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
        <img class="header__logo" src="/storage/images/logo.svg" alt="Website Logo">
        @yield('header_item')
        </header>
        <div class="content">
        @yield('content')
        </div>
    </div>
</body>

</html>
