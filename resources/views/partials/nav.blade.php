<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light shadow-sm" id="ftco-navbar">
  <div class="container">
    <a class="navbar-brand font-weight-bold d-flex align-items-center" href="/">
        <img src="{{ asset('front/images/FanteraSafaris_logo.png') }}" alt="Fantera Safaris" class="img-fluid safari-logo" style="max-height: 50px;">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav">
      <span class="oi oi-menu"></span> Menu
    </button>
    
    <div class="collapse navbar-collapse" id="ftco-nav">
      <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item {{ request()->is('/') ? 'active' : '' }}"><a href="/" class="nav-link">Home</a></li>
        <li class="nav-item {{ request()->is('tour*') ? 'active' : '' }}"><a href="/tour" class="nav-link">Destinations</a></li>
        <li class="nav-item {{ request()->is('safaris*') ? 'active' : '' }}"><a href="/safaris" class="nav-link">Safari Packages</a></li>

        @hasanyrole('super-admin|safari-manager')
          <li class="nav-item {{ request()->is('admin/fleet*') ? 'active' : '' }}">
            <a href="{{ route('admin.fleet.index') }}" class="nav-link text-warning">Fleet</a>
          </li>
        @endhasanyrole

        <li class="nav-item {{ request()->is('contact*') ? 'active' : '' }}"><a href="/contact" class="nav-link">Contact</a></li>

        <li class="nav-item d-none d-lg-block mx-2">
            <span class="text-muted" style="opacity: 0.3;">|</span>
        </li>

        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle font-weight-bold text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
              <i class="fas fa-user-circle mr-1"></i> {{ explode(' ', Auth::user()->name)[0] }}
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow animate__animated animate__fadeIn" style="border-radius: 12px;">
              
              @hasanyrole('super-admin|safari-manager')
                <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                  <i class="fas fa-chart-line mr-2 text-primary"></i> Admin Panel
                </a>
              @else
                <a class="dropdown-item py-2" href="/dashboard">
                  <i class="fas fa-th-large mr-2 text-muted"></i> My Bookings
                </a>
              @endhasanyrole

              @role('tour-guide')
                <a class="dropdown-item py-2" href="{{ route('guide.itinerary') }}">
                  <i class="fas fa-map-marked-alt mr-2 text-success"></i> My Itineraries
                </a>
              @endrole

              <a class="dropdown-item py-2" href="/user/profile"><i class="fas fa-cog mr-2 text-muted"></i> Settings</a>
              
              <div class="dropdown-divider"></div>
              
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item py-2 text-danger">
                    <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                </button>
              </form>
            </div>
          </li>
        @else
          <li class="nav-item {{ request()->is('login') ? 'active' : '' }}">
              <a href="/login" class="nav-link">Login</a>
          </li>
          <li class="nav-item ml-lg-2">
              <a href="/register" class="btn btn-outline-primary px-4 py-2" style="border-radius: 50px; font-size: 13px; font-weight: 700;">
                  Join Us
              </a>
          </li>
        @endauth

        <li class="nav-item cta ml-lg-3 mt-3 mt-lg-0">
            <a href="https://wa.me/256751115949" class="nav-link bg-success border-0 text-white shadow-sm d-flex align-items-center justify-content-center" style="border-radius: 50px; padding: 10px 20px !important;">
                <i class="fab fa-whatsapp mr-2" style="font-size: 18px;"></i> 
                <span>Plan Your Trip</span>
            </a>
        </li>
      </ul>
    </div>
  </div>
</nav>