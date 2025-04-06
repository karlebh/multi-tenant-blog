<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Default Title')</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>
        <!-- Navigation -->
        <nav>
            <!-- Your navigation content -->
        </nav>

        <!-- Main Content -->
        <div class="container">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer>
            <!-- Your footer content -->
        </footer>

        <script src="{{ asset('js/app.js') }}"></script>
    </body>

</html>
