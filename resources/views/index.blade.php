@extends('layouts.app')

@section('content')

<!-- HERO SECTION -->
<div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/bg_1.jpg') }}');">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start">
      <div class="col-md-9 ftco-animate">
        <h1 class="mb-4">
          <strong>Explore <br></strong> Your Amazing City
        </h1>
        <p>Find great places to stay, eat, shop, or visit from local experts</p>

        <div class="block-17 my-4">
          <form action="#" method="GET" class="d-block d-flex">
            <div class="fields d-block d-flex">
              <div class="textfield-search one-third">
                <input type="text" class="form-control" placeholder="Ex: food, hotel, service">
              </div>
              <div class="select-wrap one-third">
                <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                <select class="form-control">
                  <option>Where</option>
                  <option>San Francisco</option>
                  <option>Berlin</option>
                  <option>London</option>
                  <option>Paris</option>
                </select>
              </div>
            </div>
            <input type="submit" class="search-submit btn btn-primary" value="Search">
          </form>
        </div>

        <p>Or browse the highlights</p>
        <p class="browse d-md-flex">
          <span><a href="#"><i class="flaticon-fork"></i> Restaurant</a></span>
          <span><a href="#"><i class="flaticon-hotel"></i> Hotel</a></span>
          <span><a href="#"><i class="flaticon-meeting-point"></i> Places</a></span>
          <span><a href="#"><i class="flaticon-shopping-bag"></i> Shopping</a></span>
        </p>
      </div>
    </div>
  </div>
</div>

<!-- SERVICES -->
<section class="ftco-section services-section bg-light">
  <div class="container">
    <div class="row">
      @php
        $services = [
          ['icon'=>'flaticon-guarantee','title'=>'Best Price Guarantee'],
          ['icon'=>'flaticon-like','title'=>'Travellers Love Us'],
          ['icon'=>'flaticon-detective','title'=>'Best Travel Agent'],
          ['icon'=>'flaticon-support','title'=>'Dedicated Support'],
        ];
      @endphp

      @foreach($services as $service)
      <div class="col-md-3 d-flex ftco-animate">
        <div class="media block-6 services text-center">
          <div class="icon"><span class="{{ $service['icon'] }}"></span></div>
          <div class="media-body p-2 mt-2">
            <h3 class="heading mb-3">{{ $service['title'] }}</h3>
            <p>Professional and trusted travel services worldwide.</p>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- FEATURED DESTINATIONS -->
<section class="ftco-section ftco-destination">
  <div class="container">
    <div class="row mb-5">
      <div class="col-md-7 heading-section ftco-animate">
        <span class="subheading">Featured</span>
        <h2><strong>Featured</strong> Destinations</h2>
      </div>
    </div>

    <div class="destination-slider owl-carousel ftco-animate">
      @for($i=1;$i<=6;$i++)
      <div class="item">
        <div class="destination">
          <a href="#" class="img d-flex justify-content-center align-items-center"
             style="background-image: url('{{ asset('front/images/destination-'.$i.'.jpg') }}');">
            <div class="icon"><span class="icon-search2"></span></div>
          </a>
          <div class="text p-3">
            <h3><a href="#">Destination {{ $i }}</a></h3>
            <span class="listing">{{ rand(3,20) }} Listings</span>
          </div>
        </div>
      </div>
      @endfor
    </div>
  </div>
</section>

<!-- COUNTER -->
<section class="ftco-counter img" style="background-image: url('{{ asset('front/images/bg_1.jpg') }}');">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-3 counter-wrap ftco-animate">
        <strong class="number" data-number="100000">0</strong>
        <span>Happy Customers</span>
      </div>
      <div class="col-md-3 counter-wrap ftco-animate">
        <strong class="number" data-number="40000">0</strong>
        <span>Destinations</span>
      </div>
      <div class="col-md-3 counter-wrap ftco-animate">
        <strong class="number" data-number="87000">0</strong>
        <span>Hotels</span>
      </div>
      <div class="col-md-3 counter-wrap ftco-animate">
        <strong class="number" data-number="56000">0</strong>
        <span>Restaurants</span>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="ftco-section testimony-section bg-light">
  <div class="container">
    <div class="row">
      <div class="col-md-6 heading-section ftco-animate">
        <span class="subheading">Why Choose Us</span>
        <h2><strong>Trusted</strong> by Travelers</h2>
        <p>We connect travelers with unforgettable experiences worldwide.</p>
      </div>

      <div class="col-md-6 ftco-animate">
        <div class="carousel-testimony owl-carousel">
          @for($i=1;$i<=3;$i++)
          <div class="item">
            <div class="testimony-wrap d-flex">
              <div class="user-img"
                   style="background-image: url('{{ asset('front/images/person_'.$i.'.jpg') }}');"></div>
              <div class="text ml-4">
                <p>Excellent service and unforgettable trips!</p>
                <p class="name">Guest {{ $i }}</p>
                <span class="position">Traveler</span>
              </div>
            </div>
          </div>
          @endfor
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BLOG -->
<section class="ftco-section bg-light">
  <div class="container">
    <div class="row mb-5">
      <div class="col-md-7 heading-section ftco-animate">
        <span class="subheading">Blog</span>
        <h2><strong>Tips</strong> & Articles</h2>
      </div>
    </div>

    <div class="row">
      @for($i=1;$i<=4;$i++)
      <div class="col-md-3 ftco-animate">
        <div class="blog-entry">
          <a href="#" class="block-20"
             style="background-image: url('{{ asset('front/images/image_'.$i.'.jpg') }}');"></a>
          <div class="text p-4">
            <span class="tag">Travel</span>
            <h3><a href="#">Travel Blog Post {{ $i }}</a></h3>
            <div class="meta">
              <span>Admin</span> · <span>2024</span>
            </div>
          </div>
        </div>
      </div>
      @endfor
    </div>
  </div>
</section>

@endsection
