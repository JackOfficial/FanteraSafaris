@extends('layouts.app')

@section('content')
    
    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/bg_1.jpg') }}');">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
          <div class="col-md-9 ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
            <h1 class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
                <strong>Experience <br></strong> The Wild Heart of Africa
            </h1>
            <p data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Bespoke luxury safaris through Uganda, Kenya, and Tanzania. From Mountain Gorillas to the Great Migration.</p>
            
            <div class="block-17 my-4">
              <form action="#" method="get" class="d-block d-flex">
                <div class="fields d-block d-flex">
                  <div class="textfield-search one-third">
                    <input type="text" class="form-control" placeholder="Ex: Gorilla Trekking, Serengeti">
                  </div>
                  <div class="select-wrap one-third">
                    <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                    <select name="" id="" class="form-control">
                      <option value="">Choose Destination</option>
                      <option value="">Uganda - Pearl of Africa</option>
                      <option value="">Kenya - Maasai Mara</option>
                      <option value="">Tanzania - Serengeti</option>
                      <option value="">Rwanda - Land of 1000 Hills</option>
                    </select>
                  </div>
                </div>
                <input type="submit" class="search-submit btn btn-primary" value="Inquire Now">  
              </form>
            </div>
            <p>Or browse our signature experiences</p>
            <p class="browse d-md-flex">
                <span class="d-flex justify-content-md-center align-items-md-center"><a href="#"><i class="flaticon-mountain"></i> Gorilla Treks</a></span>
                <span class="d-flex justify-content-md-center align-items-md-center"><a href="#"><i class="flaticon-sun"></i> Savannah Game Drives</a></span> 
                <span class="d-flex justify-content-md-center align-items-md-center"><a href="#"><i class="flaticon-hotel"></i> Luxury Lodges</a></span> 
            </p>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section services-section bg-light">
      <div class="container">
        <div class="row d-flex">
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block text-center">
              <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-guarantee"></span></div></div>
              <div class="media-body p-2 mt-2">
                <h3 class="heading mb-3">Expert Local Knowledge</h3>
                <p>Based in Kampala, our guides possess intimate knowledge of East Africa's hidden gems.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block text-center">
              <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-like"></span></div></div>
              <div class="media-body p-2 mt-2">
                <h3 class="heading mb-3">Tailor-Made Journeys</h3>
                <p>No two travelers are the same. We curate every itinerary to match your specific desires.</p>
              </div>
            </div>    
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block text-center">
              <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-detective"></span></div></div>
              <div class="media-body p-2 mt-2">
                <h3 class="heading mb-3">Eco-Conscious Travel</h3>
                <p>We prioritize sustainability and community support in every park we visit.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block text-center">
              <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-support"></span></div></div>
              <div class="media-body p-2 mt-2">
                <h3 class="heading mb-3">24/7 Concierge Support</h3>
                <p>From arrival at Entebbe to your final departure, we are with you every step.</p>
              </div>
            </div>      
          </div>
        </div>
      </div>
    </section>
    
    <section class="ftco-section ftco-destination">
        <div class="container">
            <div class="row justify-content-start mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate">
            <span class="subheading">Unforgettable Places</span>
            <h2 class="mb-4"><strong>Featured</strong> Destinations</h2>
          </div>
        </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="destination-slider owl-carousel ftco-animate">
                        <div class="item">
                            <div class="destination">
                                <a href="#" class="img d-flex justify-content-center align-items-center" style="background-image: url('{{ asset('front/images/Bwindi.jpg') }}');">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="icon-search2"></span>
                                    </div>
                                </a>
                                <div class="text p-3">
                                    <h3><a href="#">Bwindi Impenetrable, Uganda</a></h3>
                                    <span class="listing">Home of the Gorillas</span>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="destination">
                                <a href="#" class="img d-flex justify-content-center align-items-center" style="background-image: url('{{ asset('front/images/Serengeti.jpg') }}');">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="icon-search2"></span>
                                    </div>
                                </a>
                                <div class="text p-3">
                                    <h3><a href="#">Serengeti National Park</a></h3>
                                    <span class="listing">The Great Migration</span>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="destination">
                                <a href="#" class="img d-flex justify-content-center align-items-center" style="background-image: url('{{ asset('front/images/Maasai mara.jpg') }}');">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="icon-search2"></span>
                                    </div>
                                </a>
                                <div class="text p-3">
                                    <h3><a href="#">Maasai Mara, Kenya</a></h3>
                                    <span class="listing">Unlimited Game Viewing</span>
                                </div>
                            </div>
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
                    <div class="img img-video d-flex align-self-stretch align-items-center justify-content-center" style="background-image:url({{ asset('front/images/about_safari.png') }});">
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

    <section class="ftco-section testimony-section bg-light">
      <div class="container">
        <div class="row justify-content-start mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate">
            <span class="subheading">Guest Reviews</span>
            <h2 class="mb-4"><strong>Shared</strong> Experiences</h2>
          </div>
        </div>
        <div class="row ftco-animate">
          <div class="col-md-12">
            <div class="carousel-testimony owl-carousel">
              <div class="item">
                <div class="testimony-wrap p-4 pb-5">
                  <div class="text">
                    <p class="mb-5">"The attention to detail was incredible. Our guide in Bwindi knew every bird and tree. Fantera made our honeymoon magical."</p>
                    <div class="d-flex align-items-center">
                        <div class="user-img" style="background-image: url({{ asset('front/images/person_1.jpg') }})"></div>
                        <div class="ml-3">
                            <p class="name">James Willson</p>
                            <span class="position">London, UK</span>
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