@extends('layouts.app')
@push('styles')
<style>
      .hero-section {
      height: 100vh;
      background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
                  url('front/images/Bwindi.jpg') center center/cover no-repeat;
    }

    .btn-warning {
      background-color: #d4a373;
      border: none;
    }

    .btn-warning:hover {
      background-color: #c48c5a;
    }

    .service-box,
    .tour-card {
      transition: 0.3s;
    }

    .service-box:hover,
    .tour-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

</style>
@endpush
@section('content')
    
   <!-- HERO SECTION -->
<section class="hero-wrap d-flex align-items-center" style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('front/images/Bwindi.jpg') center center/cover no-repeat; height: 100vh;">

  <div class="container">
    <div class="row align-items-center">
      
      <!-- LEFT CONTENT -->
      <div class="col-lg-7 text-white">
        <h1 class="font-weight-bold mb-4" style="font-size:52px; line-height:1.2;">
          Experience <br>
          <span class="text-warning">The Wild Heart of Africa</span>
        </h1>

        <p class="lead mb-4" style="max-width:600px;">
          Bespoke luxury safaris across Uganda, Kenya, Tanzania & Rwanda.
          From intimate Gorilla encounters to the breathtaking Great Migration.
        </p>

        <a href="#" class="btn btn-warning btn-lg font-weight-bold px-4 py-3">
          Explore Tours
        </a>
      </div>

      <!-- RIGHT FORM -->
      <div class="col-lg-5 mt-5 mt-lg-0">
        <div class="bg-white p-4 rounded shadow">
          <h5 class="font-weight-bold mb-3 text-center">Plan Your Safari</h5>
          <form>
            <div class="form-group">
              <input type="text" class="form-control" placeholder="Full Name">
            </div>

            <div class="form-group">
              <input type="email" class="form-control" placeholder="Email Address">
            </div>

            <div class="form-group">
              <select class="form-control">
                <option>Select Destination</option>
                <option>Uganda</option>
                <option>Rwanda</option>
                <option>Kenya</option>
                <option>Tanzania</option>
              </select>
            </div>

            <button class="btn btn-warning btn-block font-weight-bold">
              Start Planning
            </button>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

   <section class="py-5 bg-light">
  <div class="container">
    <div class="row text-center">

      <div class="col-md-4 mb-4">
        <div class="p-4 bg-white rounded shadow-sm h-100 service-box">
          <div class="mb-3">
            <span class="fa fa-map-marker fa-2x text-warning"></span>
          </div>
          <h5 class="font-weight-bold">Expert Local Guides</h5>
          <p>Professional safari guides with deep regional knowledge.</p>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="p-4 bg-white rounded shadow-sm h-100 service-box">
          <div class="mb-3">
            <span class="fa fa-shield fa-2x text-warning"></span>
          </div>
          <h5 class="font-weight-bold">Safe & Secure</h5>
          <p>Your safety and comfort are our top priorities.</p>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="p-4 bg-white rounded shadow-sm h-100 service-box">
          <div class="mb-3">
            <span class="fa fa-star fa-2x text-warning"></span>
          </div>
          <h5 class="font-weight-bold">Luxury Experience</h5>
          <p>Handcrafted safari experiences tailored to you.</p>
        </div>
      </div>

    </div>
  </div>
</section>
    
    <section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="font-weight-bold">Featured Safari Packages</h2>
      <p class="text-muted">Explore our most popular adventures</p>
    </div>

    <div class="row">

      <div class="col-md-4 mb-4">
        <div class="card border-0 shadow tour-card h-100">
          <img src="front/images/Bwindi.jpg" class="card-img-top" style="height:250px; object-fit:cover;">
          <div class="card-body">
            <h5 class="font-weight-bold">3 Days Gorilla Trekking</h5>
            <p>Bwindi Impenetrable Forest</p>
          </div>
          <div class="card-footer bg-white border-0">
            <a href="#" class="btn btn-warning btn-block font-weight-bold">View Details</a>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card border-0 shadow tour-card h-100">
          <img src="front/images/Serengeti.jpg" class="card-img-top" style="height:250px; object-fit:cover;">
          <div class="card-body">
            <h5 class="font-weight-bold">5 Days Serengeti Safari</h5>
            <p>Tanzania</p>
          </div>
          <div class="card-footer bg-white border-0">
            <a href="#" class="btn btn-warning btn-block font-weight-bold">View Details</a>
          </div>
        </div>
      </div>

      <div class="col-md-4 mb-4">
        <div class="card border-0 shadow tour-card h-100">
          <img src="front/images/Masai.jpg" class="card-img-top" style="height:250px; object-fit:cover;">
          <div class="card-body">
            <h5 class="font-weight-bold">4 Days Maasai Mara</h5>
            <p>Kenya</p>
          </div>
          <div class="card-footer bg-white border-0">
            <a href="#" class="btn btn-warning btn-block font-weight-bold">View Details</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

    <section class="ftco-section bg-light">
  <div class="container">
    <div class="row justify-content-start mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate">
        <span class="subheading">Exclusive Experiences</span>
        <h2 class="mb-4"><strong>Top</strong> Safari Packages</h2>
      </div>
    </div>
  </div>
  
  <div class="container-fluid">
    <div class="row">
      
      <div class="col-sm col-md-6 col-lg ftco-animate">
        <div class="destination">
          <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/Serengeti.jpg') }});">
            <div class="icon d-flex justify-content-center align-items-center">
              <span class="icon-search2"></span>
            </div>
          </a>
          <div class="text p-3">
            <div class="d-flex">
              <div class="one">
                <h3><a href="#">Serengeti Migration</a></h3>
                <p class="rate">
                  <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i>
                  <span>25 Rating</span>
                </p>
              </div>
              <div class="two">
                <span class="price">$1,250</span>
              </div>
            </div>
            <p>Witness the world's largest land mammal migration across the endless plains.</p>
            <p class="days"><span>5 days 4 nights</span></p>
            <hr>
            <p class="bottom-area d-flex">
              <span><i class="icon-map-o"></i> Tanzania</span>
              <span class="ml-auto"><a href="#">Book Now</a></span>
            </p>
          </div>
        </div>
      </div>

      <div class="col-sm col-md-6 col-lg ftco-animate">
        <div class="destination">
          <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/Maasai mara.jpg') }});">
            <div class="icon d-flex justify-content-center align-items-center">
              <span class="icon-search2"></span>
            </div>
          </a>
          <div class="text p-3">
            <div class="d-flex">
              <div class="one">
                <h3><a href="#">Maasai Mara Luxury</a></h3>
                <p class="rate">
                  <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i>
                  <span>18 Rating</span>
                </p>
              </div>
              <div class="two">
                <span class="price">$980</span>
              </div>
            </div>
            <p>Experience the heart of the Big Five territory with expert Maasai guides.</p>
            <p class="days"><span>4 days 3 nights</span></p>
            <hr>
            <p class="bottom-area d-flex">
              <span><i class="icon-map-o"></i> Kenya</span>
              <span class="ml-auto"><a href="#">Book Now</a></span>
            </p>
          </div>
        </div>
      </div>

      <div class="col-sm col-md-6 col-lg ftco-animate">
        <div class="destination">
          <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/gorilla_trek.jpg') }});">
            <div class="icon d-flex justify-content-center align-items-center">
              <span class="icon-search2"></span>
            </div>
          </a>
          <div class="text p-3">
            <div class="d-flex">
              <div class="one">
                <h3><a href="#">Gorilla Trekking</a></h3>
                <p class="rate">
                  <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i>
                  <span>30 Rating</span>
                </p>
              </div>
              <div class="two">
                <span class="price">$1,800</span>
              </div>
            </div>
            <p>An intimate encounter with the mountain gorillas in the misty volcanic hills.</p>
            <p class="days"><span>3 days 2 nights</span></p>
            <hr>
            <p class="bottom-area d-flex">
              <span><i class="icon-map-o"></i> Rwanda/Uganda</span>
              <span class="ml-auto"><a href="#">Book Now</a></span>
            </p>
          </div>
        </div>
      </div>

      <div class="col-sm col-md-6 col-lg ftco-animate">
        <div class="destination">
          <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/zanzibar_beach.jpg') }});">
            <div class="icon d-flex justify-content-center align-items-center">
              <span class="icon-search2"></span>
            </div>
          </a>
          <div class="text p-3">
            <div class="d-flex">
              <div class="one">
                <h3><a href="#">Zanzibar Escapade</a></h3>
                <p class="rate">
                  <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star-o"></i>
                  <span>12 Rating</span>
                </p>
              </div>
              <div class="two">
                <span class="price">$450</span>
              </div>
            </div>
            <p>The perfect post-safari relaxation on the white sands of the Spice Island.</p>
            <p class="days"><span>3 days 3 nights</span></p>
            <hr>
            <p class="bottom-area d-flex">
              <span><i class="icon-map-o"></i> Zanzibar</span>
              <span class="ml-auto"><a href="#">Book Now</a></span>
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

    <section class="ftco-section ftco-no-pt ftco-no-pb">
        <div class="container">
            <div class="row">
                <div class="col-md-6 d-flex align-items-stretch">
                    <div class="img img-video d-flex align-self-stretch align-items-center justify-content-center" style="background-image:url({{ asset('front/images/about_us.jpg') }});">
                    </div>
                </div>
                <div class="col-md-6 py-md-5 mt-md-5">
                    <div class="heading-section mb-5 ftco-animate">
                        <span class="subheading">Since 2014</span>
                        <h2 class="mb-4">The Fantera Safaris Philosophy</h2>
                        <p>We don't just organize trips; we curate soul-stirring encounters with the wild. Born in the heart of Uganda, Fantera Safaris was founded on the belief that travel should be immersive, ethical, and uncompromising in luxury.</p>
                        <p>Whether it’s the silent gaze of a Mountain Gorilla or the thundering hooves across the Serengeti, we ensure your journey is seamless, private, and profoundly personal.</p>
                        <p><a href="#" class="btn btn-primary px-4 py-3">Learn More About Us</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url({{ asset('front/images/bg_1.jpg') }});">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
            <h2 class="mb-4">Our Legacy in the Wild</h2>
            <span class="subheading">Delivering Excellence Across East Africa</span>
          </div>
        </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row">
                  <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                    <div class="block-18 text-center">
                      <div class="text">
                        <strong class="number" data-number="1500">0</strong>
                        <span>Happy Adventurers</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                    <div class="block-18 text-center">
                      <div class="text">
                        <strong class="number" data-number="45">0</strong>
                        <span>National Parks</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                    <div class="block-18 text-center">
                      <div class="text">
                        <strong class="number" data-number="120">0</strong>
                        <span>Luxury Lodges</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                    <div class="block-18 text-center">
                      <div class="text">
                        <strong class="number" data-number="12">0</strong>
                        <span>Years Experience</span>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        </div>
    </section>

<section class="ftco-section bg-light">
  <div class="container">
    <div class="row justify-content-start mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate">
        <span class="subheading">Safari Insights</span>
        <h2><strong>Wild</strong> Stories &amp; Safari Tips</h2>
      </div>
    </div>

    <div class="row d-flex">
      
      <div class="col-md-3 d-flex ftco-animate">
        <div class="blog-entry align-self-stretch">
          <a href="blog-single.html" class="block-20" style="background-image: url({{ asset('front/images/Serengeti.jpg') }});"></a>
          <div class="text p-4 d-block">
            <span class="tag">Wild Experience</span>
            <h3 class="heading mt-3"><a href="#">The Great Migration: When and Where to See the Herd</a></h3>
            <div class="meta mb-3">
              <div><a href="#">Feb 24, 2026</a></div>
              <div><a href="#">Safari Guide</a></div>
              <div><a href="#" class="meta-chat"><span class="icon-chat"></span> 42</a></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 d-flex ftco-animate">
        <div class="blog-entry align-self-stretch">
          <a href="blog-single.html" class="block-20" style="background-image: url('{{ asset('front/images/gorilla_trek.jpg') }}');"></a>
          <div class="text p-4">
            <span class="tag">Adventure</span>
            <h3 class="heading mt-3"><a href="#">Chasing Shadows: A Guide to Gorilla Trekking in Rwanda</a></h3>
            <div class="meta mb-3">
              <div><a href="#">Feb 22, 2026</a></div>
              <div><a href="#">Admin</a></div>
              <div><a href="#" class="meta-chat"><span class="icon-chat"></span> 18</a></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 d-flex ftco-animate">
        <div class="blog-entry align-self-stretch">
          <a href="blog-single.html" class="block-20" style="background-image: url('{{ asset('front/images/big_five.jpg') }}');"></a>
          <div class="text p-4">
            <span class="tag">Photography</span>
            <h3 class="heading mt-3"><a href="#">Capturing the Big Five: Best Camera Settings for Safari</a></h3>
            <div class="meta mb-3">
              <div><a href="#">Feb 19, 2026</a></div>
              <div><a href="#">Admin</a></div>
              <div><a href="#" class="meta-chat"><span class="icon-chat"></span> 9</a></div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 d-flex ftco-animate">
        <div class="blog-entry align-self-stretch">
          <a href="blog-single.html" class="block-20" style="background-image: url('{{ asset('front/images/zanzibar_beach.jpg') }}');"></a>
          <div class="text p-4">
            <span class="tag">Coastal</span>
            <h3 class="heading mt-3"><a href="#">From Bush to Beach: Relaxing in Zanzibar After Your Safari</a></h3>
            <div class="meta mb-3">
              <div><a href="#">Feb 14, 2026</a></div>
              <div><a href="#">Travel Expert</a></div>
              <div><a href="#" class="meta-chat"><span class="icon-chat"></span> 31</a></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>



 <section class="ftco-section testimony-section bg-light">
  <div class="container">
    <div class="row justify-content-start">
      
      <div class="col-md-5 heading-section ftco-animate">
        <span class="subheading">FanteraSafaris</span>
        <h2 class="mb-4 pb-3"><strong>Why</strong> Book With Us?</h2>
        <p>With over a decade of experience across the East African Savannah, we don't just organize trips; we curate life-changing encounters. From the vast plains of the Serengeti to the mist-covered peaks of Rwanda, our local guides ensure you are in the right place at the right moment.</p>
        <p>We prioritize sustainable tourism and authentic cultural immersion, giving you a deeper connection to the wild heart of Africa.</p>
        <p><a href="#" class="btn btn-primary btn-outline-primary mt-4 px-4 py-3">View Our Packages</a></p>
      </div>

      <div class="col-md-1"></div>

      <div class="col-md-6 heading-section ftco-animate">
        <span class="subheading">Guest Reviews</span>
        <h2 class="mb-4 pb-3"><strong>What</strong> Our Explorers Say</h2>
        <div class="row ftco-animate">
          <div class="col-md-12">
            <div class="carousel-testimony owl-carousel">
              
              <div class="item">
                <div class="testimony-wrap d-flex">
                  <div class="user-img mb-5" style="background-image: url({{ asset('front/images/person_1.jpg') }})">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text ml-md-4">
                    <p class="mb-5">"Watching the Great Migration from a hot air balloon was surreal. The attention to detail and the knowledge of our guide made this the trip of a lifetime!"</p>
                    <p class="name">James Henderson</p>
                    <span class="position">Guest from Canada</span>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="testimony-wrap d-flex">
                  <div class="user-img mb-5" style="background-image: url({{ asset('front/images/person_2.jpg') }})">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text ml-md-4">
                    <p class="mb-5">"The gorilla trekking in Uganda was breath-taking. Everything was perfectly organized, from the permits to the stunning eco-lodges we stayed in."</p>
                    <p class="name">Elena Rossi</p>
                    <span class="position">Guest from Italy</span>
                  </div>
                </div>
              </div>

              <div class="item">
                <div class="testimony-wrap d-flex">
                  <div class="user-img mb-5" style="background-image: url({{ asset('front/images/person_3.jpg') }})">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text ml-md-4">
                    <p class="mb-5">"Expertly handled. We saw the Big Five within the first two days! If you're looking for an authentic East African adventure, this is it."</p>
                    <p class="name">Sarah Jenkins</p>
                    <span class="position">Guest from USA</span>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      </div>
  </div>
</section>

    <section class="ftco-section-parallax">
      <div class="parallax-img d-flex align-items-center" style="background-image: url({{ asset('front/images/bg_2.jpg') }}); padding: 100px 0;">
        <div class="container">
          <div class="row d-flex justify-content-center">
            <div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
              <h2>Follow the Adventure</h2>
              <p>Join our community on Instagram for daily glimpses into the wild.</p>
              <p><a href="#" class="btn btn-white btn-outline-white px-4 py-3">@FanteraSafaris</a></p>
            </div>
          </div>
        </div>
      </div>
    </section>

@endsection