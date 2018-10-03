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

        {{ style(mix('css/frontend.css')) }}
        {{ style('css/fdat.css') }}

    @stack('after-styles')

    </head>
    <body>
        <div id="app">
            @include('includes.partials.logged-in-as')
            @include('frontend.includes.nav')
            <div class="container">
                @include('includes.partials.messages')
                @if (Auth::check() && Auth::user()->subscr_mask == 0)
                    <div class="alert alert-warning" role="alert">
                        You have <strong>not subscribed</strong> to any matter tag. All views which are filtered against your subscribed tags <strong>will be blank</strong>.<br>
                        Subscribe to tags from <a href="{{route('frontend.user.account')}}">My Account</a>->Account Details.
                    </div>
                @endif
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
