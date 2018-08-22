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

        {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
        @stack('before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        {{ style(mix('css/frontend.css')) }}
    <style>

     body { background-image: linear-gradient(to bottom right, #E3E3E3 0%, #8A9CB5 100%);}

     a:link, a:visited {
     	text-decoration: none;
     	color: #008080
     	}

     a:hover, a:focus, a:active {
     	text-decoration: underline;
     	color: #85144b;
     	}

     .dropdown-menu a:link, .dropdown-menu a:visited {
         color: #222222;
     }

     .dropdown-menu a:hover, .dropdown-menu a:focus, .dropdown-menu a:active {
        background-color: #696969;
     	color: #FAEBD7;
     	}

     .dropdown:hover>.dropdown-menu {
      display: block;
     }

     .dropdown>.dropdown-toggle:active {
      /*Without this, clicking will make it sticky*/
        pointer-events: none;
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
        {!! script(mix('js/frontend.js')) !!}
        @stack('after-scripts')

        @include('includes.partials.ga')
    </body>
</html>
