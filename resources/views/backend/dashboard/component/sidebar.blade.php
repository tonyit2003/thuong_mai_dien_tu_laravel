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
                        <img alt="image" class="img-circle" src="img/profile_small.jpg" />
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">David
                                    Williams</strong>
                            </span> <span class="text-muted text-xs block">Art Director <b class="caret"></b></span>
                        </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="profile.html">Profile</a></li>
                        <li><a href="contacts.html">Contacts</a></li>
                        <li><a href="mailbox.html">Mailbox</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('auth.logout') }}">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>
            @foreach (config('apps.module.module') as $key => $val)
                <li class="{{ $segment == $val['name'] ? 'active' : '' }}">
                    <a href="#"><i class="{{ $val['icon'] }}"></i> <span
                            class="nav-label">{{ $val['title'] }}</span>
                        <span class="fa arrow"></span></a>
                    @if (isset($val['subModule']))
                        <ul class="nav nav-second-level">
                            @foreach ($val['subModule'] as $module)
                                <li><a href="{{ route($module['route']) }}">{{ $module['title'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>

    </div>
</nav>
