<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <a href="{{ route('frontend.index') }}" class="navbar-brand">{{ app_name() }}</a>

    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('labels.general.toggle_navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav">

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuUser" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">Projects</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                    <a href="{{ route('frontend.project.index_all') }}" class="dropdown-item">..all</a>
                    @auth
                    <a href="{{ route('frontend.project.index') }}" class="dropdown-item">..relevant</a>
                    <a href="{{ route('frontend.project.index_free') }}" class="dropdown-item">..relevant and unassigned</a>
                      @if ($logged_in_user->hasPermissionTo('write projects'))
                      <div class="dropdown-divider"></div>
                      <a href="{{ route('frontend.project.create') }}" class="dropdown-item">New Project</a>
                      @endif
                   @endauth
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuUser" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">Lecturers</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                    <a href="{{ route('frontend.person.show_all_lecturers') }}" class="dropdown-item">All lecturers</a>
                    @auth
                    <a href="{{ route('frontend.person.show_lecturers') }}" class="dropdown-item">Relevant lecturers</a>
                   @endauth
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuUser" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">Students</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                    <a href="{{ route('frontend.person.show_all_students') }}" class="dropdown-item">All students</a>
                    @auth
                    <a href="{{ route('frontend.person.show_students') }}" class="dropdown-item">Relevant students</a>
                   @endauth
                </div>
            </li>

            @auth
                <li class="nav-item"><a href="{{route('frontend.user.dashboard')}}" class="nav-link {{ active_class(Active::checkRoute('frontend.user.dashboard')) }}">{{ __('navs.frontend.dashboard') }}</a></li>
            @endauth

            @guest
                <li class="nav-item"><a href="{{route('frontend.auth.login')}}" class="nav-link {{ active_class(Active::checkRoute('frontend.auth.login')) }}">{{ __('navs.frontend.login') }}</a></li>

                @if (config('access.registration'))
                    <li class="nav-item"><a href="{{route('frontend.auth.register')}}" class="nav-link {{ active_class(Active::checkRoute('frontend.auth.register')) }}">{{ __('navs.frontend.register') }}</a></li>
                @endif
            @else
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuUser" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">{{ $logged_in_user->name }}</a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuUser">
                        @can('view backend')
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">{{ __('navs.frontend.user.administration') }}</a>
                        @endcan

                        <a href="{{ route('frontend.user.account') }}" class="dropdown-item {{ active_class(Active::checkRoute('frontend.user.account')) }}">{{ __('navs.frontend.user.account') }}</a>
                        <a href="{{ route('frontend.auth.logout') }}" class="dropdown-item">{{ __('navs.general.logout') }}</a>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
</nav>@yield('breadcrumbs')
