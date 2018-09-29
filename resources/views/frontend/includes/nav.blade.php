<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark mb-3">
    <a href="{{ route('frontend.index') }}" class="navbar-brand">{{ app_name() }}</a>

    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('labels.general.toggle_navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>@yield('breadcrumbs')
    <div class="collapse navbar-collapse justify-content-end mr-4" id="navbarSupportedContent">
        <ul class="navbar-nav">

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuProject" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">Projects <span class="badge badge-pill badge-secondary">{{$total_projects}}</span></a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuProject">
                    <a href="{{ route('frontend.project.index_all') }}" class="dropdown-item">All</a>
                    @auth
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('frontend.project.index') }}" class="dropdown-item">Relevant (matching your tags)</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('frontend.project.index_free') }}" class="dropdown-item">Relevant and <strong>available</strong></a>
                    <a href="{{ route('frontend.project.index_taken') }}" class="dropdown-item">Relevant and <strong>taken</strong></a>
                    <a href="{{ route('frontend.project.index_orphan') }}" class="dropdown-item">Relevant and <strong>without supervisor</strong></a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('frontend.project.search') }}" class="dropdown-item">Search Projects</a>
                    @can('write projects')
                      <div class="dropdown-divider"></div>
                      <a href="{{ route('frontend.project.create') }}" class="dropdown-item">New Project</a>
                    @endcan
                   @endauth
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuLecturer" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">Lecturers <span class="badge badge-pill badge-secondary">{{$total_lecturers}}</span></a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLecturer">
                    <a href="{{ route('frontend.person.show_all_lecturers') }}" class="dropdown-item">All</a>
                    @auth
                    <a href="{{ route('frontend.person.show_lecturers') }}" class="dropdown-item">Relevant (matching your tags)</a>
                    @endauth
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuStudent" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">Students <span class="badge badge-pill badge-secondary">{{$total_students}}</span></a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuStudent">
                    <a href="{{ route('frontend.person.show_all_students') }}" class="dropdown-item">All</a>
                    @auth
                    <a href="{{ route('frontend.person.show_students') }}" class="dropdown-item">Relevant (matching your tags)</a>
                    <a href="{{ route('frontend.person.show_free_students') }}" class="dropdown-item">Relevant and <strong>without</strong> project</a>
                    <a href="{{ route('frontend.person.show_busy_students') }}" class="dropdown-item">Relevant and <strong>with</strong> project</a>
                   @endauth
                </div>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuDeliver" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">Deliverables</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuDeliver">
                    <a href="{{route('frontend.deliverable.all_requests')}}" class="dropdown-item">Deliverable requests</a>
                   @hasanyrole('lecturer|student')
                    <a href="{{route('frontend.deliverable.my')}}" class="dropdown-item">My deliverables</a>
                   @endhasanyrole
                </div>
            </li>

            @auth
                <li class="nav-item"><a href="{{route('frontend.user.dashboard')}}" class="nav-link {{ active_class(Active::checkRoute('frontend.user.dashboard')) }}">{{ __('navs.frontend.dashboard') }}</a></li>
            @endauth

            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuHelp" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">Help</a>
                <div class="dropdown-menu">
                    <a href="{{route('frontend.help', 'about')}}" class="dropdown-item">About</a>
                    <a href="{{route('frontend.help', 'quickstart')}}" class="dropdown-item">Existing users</a>
                    <a href="{{route('frontend.matters')}}" class="dropdown-item">Categories</a>
                </div>
            </li>

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
</nav>
