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
        
        <li class="nav-item {{ request()->is('destinations*') ? 'active' : '' }}">
            <a href="{{ route('destinations.index') }}" class="nav-link">Destinations</a>
        </li>

        <li class="nav-item {{ request()->is('safaris*') ? 'active' : '' }}">
            <a href="/safaris" class="nav-link">Safaris</a>
        </li>

        {{-- Combined 'Experiences' into 'Travel Guide' for a cleaner look if desired, 
             otherwise kept as a single clean link --}}
        <li class="nav-item {{ request()->is('travel-guide*') ? 'active' : '' }}">
            <a href="/travel-guide" class="nav-link">Guide</a>
        </li>

        <li class="nav-item {{ request()->is('contact*') ? 'active' : '' }}">
            <a href="/contact" class="nav-link">Contact</a>
        </li>

        {{-- Fleet: Only visible to staff, keeping it distinct --}}
        @hasanyrole('super-admin|safari-manager')
          <li class="nav-item">
            <a href="{{ route('admin.fleet.index') }}" class="nav-link text-warning px-lg-3"><i class="fas fa-car-side mr-1"></i> Fleet</a>
          </li>
        @endhasanyrole

        {{-- Divider --}}
        <li class="nav-item d-none d-lg-block mx-1">
            <span class="text-muted" style="opacity: 0.2;">|</span>
        </li>

        {{-- Auth & CTA --}}
        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle font-weight-bold text-primary" href="#" id="userDropdown" data-toggle="dropdown">
              {{ explode(' ', Auth::user()->name)[0] }}
            </a>
            <div class="dropdown-menu dropdown-menu-right border-0 shadow" style="border-radius: 12px; font-size: 14px;">
              <a class="dropdown-item py-2" href="{{ Auth::user()->hasAnyRole('super-admin|safari-manager') ? route('admin.dashboard') : '/dashboard' }}">
                <i class="fas fa-th-large mr-2"></i> Dashboard
              </a>
              <div class="dropdown-divider"></div>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item py-2 text-danger"><i class="fas fa-sign-out-alt mr-2"></i> Logout</button>
              </form>
            </div>
          </li>
        @else
          <li class="nav-item {{ request()->is('login') ? 'active' : '' }}">
              <a href="/login" class="nav-link">Login</a>
          </li>
        @endauth

        <li class="nav-item cta ml-lg-2">
            <a href="https://wa.me/256751115949" class="nav-link bg-success text-white shadow-sm" style="border-radius: 50px; padding: 8px 18px !important; font-size: 12px; font-weight: 700;">
                <i class="fab fa-whatsapp mr-1"></i> Plan Trip
            </a>
        </li>
      </ul>
    </div>
  </div>
</nav>