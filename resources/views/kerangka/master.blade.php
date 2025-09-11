<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    @include('include.style')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <div id="app">

        @include('include.sidebar')

        @include('include.navbar')

        <div id="main">

            @yield('content')

            @include('include.footer')
        </div>
    </div>

    @include('include.scripct')

</body>

</html>
