<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('backend.dashboard.index') }}" class="brand-link">
        {{--   <img src="{{ asset('backend/assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">--}}
        <i style="opacity: .8;font-size: 25px" class="nav-icon fas fa-tachometer-alt"></i>&nbsp;&nbsp;
        <span class="brand-text font-weight-light">İdarə Paneli</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">

                @if( auth()->check())
                    @php
                        $user = auth()->user()->avatar;
                    @endphp
                    @role('developer|superadmin')
                            <img style="width:45px;height:45px;" src="{{ asset($user)}}"
                                   class="img-circle elevation-2" alt="İstifadəçinin şəkli">
                    @endrole

                @endif
            </div>
            <div class="info">
                <a href="javavoid:0" class="d-block">
                    @if( Auth::check())
                      <span>{{ Auth::user()->username }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- SidebarSearch Form -->


    <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item @if( request()->is('my-admin/dashboard') || request()->is('my-admin/city-statistics') || request()->is('my-admin/nomination-statistics') || request()->is('my-admin/district-statistics') || request()->is('my-admin/nomination-district-statistics'))  menu-open @endif">
                    <a href="#"
                       class="nav-link @if( request()->is('my-admin/dashboard') || request()->is('my-admin/city-statistics') || request()->is('my-admin/nomination-statistics') || request()->is('my-admin/district-statistics') || request()->is('my-admin/nomination-district-statistics') ) active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Statistika
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.dashboard.index') }}"
                               class="umumi-yuklenir nav-link @if(request()->is('my-admin/dashboard')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Ümumi
                                </p>
                            </a>
                        </li>
                        @role('developer|superadmin|content manager')
                        <li class="nav-item">
                            <a href="{{ route('backend.dashboard.district.statistics') }}"
                               class="umumi-yuklenir nav-link @if(request()->is('my-admin/district-statistics')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Faktiki yaşadığı rayon
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.dashboard.city.statistics') }}"
                               class="umumi-yuklenir nav-link @if(request()->is('my-admin/city-statistics')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Müraciət etdiyi şəhər
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.dashboard.nomination.statistics') }}"
                               class="umumi-yuklenir nav-link @if(request()->is('my-admin/nomination-statistics')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Nominasiya üzrə say
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.nomination.district.statistics') }}"
                               class="umumi-yuklenir nav-link @if(request()->is('my-admin/nomination-district-statistics')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Rayon-nominasiya
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.nomination.city.statistics') }}"
                               class="umumi-yuklenir nav-link @if(request()->is('my-admin/nomination-city-statistics')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p >
                                    Şəhər-nominasiya
                                </p>
                            </a>
                        </li>
                        @endrole
                    </ul>
                </li>
                <li class="nav-item @if( request()->is('my-admin/personal-users-list') ||  request()->is('my-admin/collective-user-detail/*') || request()->is('my-admin/collective-users-list') || request()->is('my-admin/personal-user-detail/*'))  menu-open @endif">
                    <a href="#"
                       class="nav-link @if( request()->is('my-admin/personal-users-list') ||  request()->is('my-admin/collective-user-detail/*') || request()->is('my-admin/collective-users-list') || request()->is('my-admin/personal-user-detail/*'))  active @endif">
                        <i class='nav-icon fas fa-dice-one'></i>
                        <p>
                            Birinci mərhələ
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('backend.personal.users.list') }}"
                               class="umumi-yuklenir nav-link @if(request()->is('my-admin/personal-users-list') || request()->is('my-admin/personal-user-detail/*')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Fərdi iştirakçılar
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('backend.collective.users.list') }}"
                               class="umumi-yuklenir nav-link @if(request()->is('my-admin/collective-users-list') || request()->is('my-admin/collective-user-detail/*')) active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Kollektivlər
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>

                @role('admin')

                <li class="nav-item">
                    <a href="{{ route('backend.judges') }}"
                       class="umumi-yuklenir nav-link @if(request()->is('my-admin/judges')) active @endif">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Münsifə aid iştirakçılar
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('backend.judges.files') }}"
                       class="umumi-yuklenir nav-link @if(request()->is('my-admin/judges-files')) active @endif">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Münsifin fayllarını yüklə
                        </p>
                    </a>
                </li>

                @endrole
                <li class="nav-item">
                    <a href="{{ route('backend.see.judges.files') }}"
                       class="nav-link @if( request()->is('my-admin/see-judges-files') || request()->is('my-admin/see-judges-files/*')) active @endif">
                        <i class="nav-icon far fa-file-image"></i>
                        <p>
                            Münsiflərin fayllarına bax
                        </p>
                    </a>
                </li>
                @can('view role')
                    <li class="nav-item">
                        <a href="{{ route('backend.roles.index') }}"
                           class="nav-link @if( request()->is('my-admin/roles/*') || request()->is('my-admin/roles')) active @endif">
                            <i class="nav-icon fas fa-lock"></i>
                            <p>
                                Rol və İcazələr
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backend.users.index') }}"
                           class="nav-link @if( request()->is('my-admin/users') || request()->is('my-admin/users/*')) active @endif">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                İdarə Paneli İstifadəçiləri
                            </p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
