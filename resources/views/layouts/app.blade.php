<!DOCTYPE html>
<html>
<head>
    <title>Kargo</title>
</head>
<body>

    <header>
        <nav>
            <a href="{{ route('home') }}">Home</a> |
            <a href="{{ route('about') }}">About</a>
        </nav>
        <hr>
        <h2>Kargo Logistics System</h2>
        <hr>
    </header>

    <main>
        @yield('content')
    </main>

</body>
</html>