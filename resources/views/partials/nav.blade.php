<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
  <div class="container">
    <a class="navbar-brand font-weight-bold d-flex align-items-center" href="/">
    <img src="{{ asset('front/images/FanteraSafaris_logo.png') }}" 
         alt="Fantera Safaris - Luxury East Africa Tours and Uganda Safaris" 
         class="img-fluid safari-logo" 
         style="max-height: 50px; width: auto;">
</a>
    
    <div class="collapse navbar-collapse" id="ftco-nav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item {{ request()->is('/') ? 'active' : '' }}"><a href="/" class="nav-link">Home</a></li>
         <li class="nav-item {{ request()->is('/about') ? 'active' : '' }}"><a href="/about" class="nav-link">About Us</a></li>
        <li class="nav-item {{ request()->is('/tour') ? 'active' : '' }}"><a href="/tour" class="nav-link">Destinations</a></li>
        <li class="nav-item {{ request()->is('/packages') ? 'active' : '' }}"><a href="/packages" class="nav-link">Safari Packages</a></li>
        <li class="nav-item {{ request()->is('/story') ? 'active' : '' }}"><a href="/about" class="nav-link">Our Story</a></li>
        <li class="nav-item {{ request()->is('/contact') ? 'active' : '' }}"><a href="/contact" class="nav-link">Contact</a></li>
        
        <li class="nav-item cta ml-md-2">
            <a href="https://wa.me/256708239010" class="nav-link bg-success border-0 text-white">
                <span class="icon-whatsapp mr-1"></span> Chat with an Expert
            </a>
        </li>
      </ul>
    </div>
  </div>
</nav>