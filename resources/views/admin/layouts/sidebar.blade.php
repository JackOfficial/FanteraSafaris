<aside class="main-sidebar sidebar-dark-pink elevation-4">
    <a href="{{ route('admin.dashboard') }}" class="brand-link px-3">
        <img src="{{ asset('front/images/FanteraSafaris_logo.png') }}" alt="Fantera Safaris" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Fantera Safaris</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                <img src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/?d=mp&s=200' }}"
                     class="img-circle elevation-2" alt="User Avatar">
            </div>
            <div class="info">
                <a href="#" class="d-block text-light fw-bold">
                    {{ Auth::user()->name ?? 'Admin' }}
                    <small class="d-block text-muted text-uppercase" style="font-size: 0.7rem;">
                        {{ Auth::user()?->getRoleNames()->first() ?? 'Administrator' }}
                    </small>
                </a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase small opacity-50">Safari Management</li>

                <li class="nav-item">
                    <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-check"></i>
                        <p>
                            Bookings
                            <span class="right badge badge-info">New</span>
                        </p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.packages.*', 'admin.safari-categories.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.packages.*', 'admin.safari-categories.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>
                            Safaris
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.packages.index') }}" class="nav-link {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Packages</p>
                            </a>
                        </li>

                        <li class="nav-item">
    <a href="{{ route('admin.destinations.index') }}" class="nav-link {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-map-marked-alt text-warning"></i>
        <p>Destinations</p>
    </a>
</li>

                        <li class="nav-item">
                            <a href="{{ route('admin.safari-categories.index') }}" class="nav-link {{ request()->routeIs('admin.safari-categories.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Safari Categories</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header text-uppercase small opacity-50">Content & Blog</li>

                <li class="nav-item {{ request()->routeIs('admin.posts.*', 'admin.categories.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('admin.posts.*', 'admin.categories.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>
                            Blog Content
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.posts.index') }}" class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Posts</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Blog Categories</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header text-uppercase small opacity-50">Communication</li>

                <li class="nav-item">
                    <a href="{{ route('admin.messages.inbox') }}" class="nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Inquiries</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Reports</p>
                    </a>
                </li>

                <li class="nav-header text-uppercase small opacity-50">System</li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>User Management</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings</p>
                    </a>
                </li>

                @if(Auth::check())
                <li class="nav-item mt-4 border-top border-secondary">
                    <a href="#" class="nav-link text-danger"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>