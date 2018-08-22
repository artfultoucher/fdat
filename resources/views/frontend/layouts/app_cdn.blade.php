<!DOCTYPE html>
@langrtl
    <html lang="{{ app()->getLocale() }}" dir="rtl">
@else
    <html lang="{{ app()->getLocale() }}">
@endlangrtl
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', app_name())</title>
        <meta name="description" content="@yield('meta_description', 'Departmental Adminstration Tools')">
        <meta name="author" content="@yield('meta_author', 'Frank Boehme')">
        @yield('meta')

        @stack('before-styles')

        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>

     body { background-image: linear-gradient(to bottom right, #E3E3E3 0%, #8A9CB5 100%);}

     a:link, a:visited {
     	//font-weight: bold;
     	text-decoration: none;
     	color: #3D9970;
     	}

     a:hover, a:focus, a:active {
     	text-decoration: underline;
     	color: #85144b;
     	}

    </style>

    @stack('after-styles')

    </head>
    <body>
        <div id="app">
            @include('includes.partials.logged-in-as')
            @include('frontend.includes.nav')
            <div class="container">
                @include('includes.partials.messages')
                @yield('content')
            </div><!-- container -->
        </div><!-- #app -->
        <hr>
        <address class="text-center">&copy; Frank Boehme &bull; University College Cork &bull; Department of Computer Science</address>

        <!-- Scripts -->
        @stack('before-scripts')
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"> </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
        @stack('after-scripts')

        @include('includes.partials.ga')
    </body>
</html>
