@php
    // http://localhost/thuongmaidientu/public/user/catalogue/index
    // segment: user/catalogue/index
    // segment(1): user
    $segment = request()->segment(1);
@endphp
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                        <a href="{{ route('dashboard.index') }}"><img alt="image" class="img-circle"
                                src="{{ Auth::user()->image }}" width="60px" height="60px" /></a>
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong
                                    class="font-bold">{{ Auth::user()->name }}</strong>
                            </span> <span class="text-muted text-xs block">{{ Auth::user()->user_catalogues->name }} <b
                                    class="caret"></b></span>
                        </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="contacts.html">Contacts</a></li>
                        <li><a href="mailbox.html">Mailbox</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('auth.logout') }}">{{ __('navigation.logout') }}</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    TC+
                </div>
            </li>
            {{-- __('sidebar.module') => resources/lang/{locale}/sidebar.php (locale trong file app.php) --}}
            @foreach (__('sidebar.module') as $key => $val)
                <li
                    class="{{ isset($val['class']) ? $val['class'] : '' }} {{ in_array($segment, $val['name']) ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}"
                        class="{{ in_array($segment, $val['name']) ? 'text-white' : '' }}">
                        <i class="{{ $val['icon'] }}"></i>
                        <span class="nav-label">{{ $val['title'] }}</span>
                        @if (isset($val['subModule']) && count($val['subModule']))
                            <span class="fa arrow"></span>
                        @endif
                    </a>
                    @if (isset($val['subModule']))
                        <ul class="nav nav-second-level">
                            @foreach ($val['subModule'] as $module)
                                <li class="{{ request()->routeIs($module['route']) ? 'active' : '' }}">
                                    <a href="{{ route($module['route']) }}"
                                        class="{{ request()->routeIs($module['route']) ? 'text-white' : '' }}">
                                        {{ $module['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</nav>
