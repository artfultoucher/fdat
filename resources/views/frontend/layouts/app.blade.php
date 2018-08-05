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

        @stack('after-styles')
        <style>
      body {background-image: linear-gradient(to bottom right, #E3E3E3 0%, #8A9CB5 100%);}
    .navbar-custom {
    background-color: #224455;
}

/* change the brand and text color
.navbar-custom .navbar-brand,
.navbar-custom .navbar-text {
    color: rgba(255,255,255,.5);
}
*/
/* change the link color
.navbar-custom .nav-link {
    color: rgba(255,255,255,.5);
}

*/
/* change the color of active or hovered links
.navbar-custom .nav-link.active,
.navbar-custom .nav-link:hover {
    color: #ffffff;
}

*/
</style>
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
