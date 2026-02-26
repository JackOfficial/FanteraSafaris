<x-layout>
    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/Bwindi.jpg') }}');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
          <div class="col-md-9 ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
            <h1 class="mb-4" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }" style="font-family: 'Playfair Display', serif; font-weight: 700;">
                Experience the <br><span class="text-warning">African Wilderness</span>
            </h1>
            <p class="lead text-white mb-5" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Luxury Gorilla trekking and bespoke savannah safaris across East Africa.</p>
            
            <div class="block-17 my-4 shadow-lg" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(15px); border-radius: 15px; padding: 20px; border: 1px solid rgba(255,255,255,0.2);">
              <form action="{{ route('safaris.index') }}" method="GET" class="d-block d-flex">
                <div class="fields d-block d-flex w-100">
                  <div class="textfield-search one-third w-100 mr-2">
                    <input type="text" name="query" class="form-control" placeholder="Ex: Gorilla Trekking, Bwindi, Maasai Mara" style="border-radius: 10px; height: 52px;">
                  </div>
                  <div class="select-wrap one-third w-100 mr-2">
                    <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                    <select name="country" class="form-control" style="border-radius: 10px; height: 52px;">
                      <option value="">Select Destination</option>
                      <option value="uganda">Uganda</option>
                      <option value="rwanda">Rwanda</option>
                      <option value="kenya">Kenya</option>
                      <option value="tanzania">Tanzania</option>
                    </select>
                  </div>
                </div>
                <input type="submit" class="search-submit btn btn-warning px-5" value="Find Adventure" style="border-radius: 10px; font-weight: 700;">  
              </form>
            </div>
            <p class="text-white-50">Popular: <a href="#" class="text-warning ml-2">Gorilla Trekking</a>, <a href="#" class="text-warning ml-2">Birding</a>, <a href="#" class="text-warning ml-2">Game Drives</a></p>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section services-section bg-light">
      <div class="container">
        <div class="row d-flex">
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block text-center shadow-sm p-4 bg-white" style="border-radius: 15px; border-bottom: 4px solid #ffc107;">
              <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-guarantee text-warning"></span></div></div>
              <div class="media-body mt-3">
                <h3 class="heading mb-3 font-weight-bold">Fair Pricing</h3>
                <p>Direct local rates without the middleman markup.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block text-center shadow-sm p-4 bg-white" style="border-radius: 15px; border-bottom: 4px solid #ffc107;">
              <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-like text-warning"></span></div></div>
              <div class="media-body mt-3">
                <h3 class="heading mb-3 font-weight-bold">Expert Guides</h3>
                <p>Born and raised here, our guides know every trail and secret.</p>
              </div>
            </div>    
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block text-center shadow-sm p-4 bg-white" style="border-radius: 15px; border-bottom: 4px solid #ffc107;">
              <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-detective text-warning"></span></div></div>
              <div class="media-body mt-3">
                <h3 class="heading mb-3 font-weight-bold">Eco-Conscious</h3>
                <p>We prioritize sustainable tourism and local community support.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block text-center shadow-sm p-4 bg-white" style="border-radius: 15px; border-bottom: 4px solid #ffc107;">
              <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-support text-warning"></span></div></div>
              <div class="media-body mt-3">
                <h3 class="heading mb-3 font-weight-bold">24/7 Support</h3>
                <p>From booking to departure, we are with you every step.</p>
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
            <span class="subheading" style="color: #ffc107; font-weight: 700; letter-spacing: 2px;">DESTINATIONS</span>
            <h2 class="mb-4" style="font-family: 'Playfair Display', serif;"><strong>Featured</strong> Wild Spaces</h2>
          </div>
        </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="destination-slider owl-carousel ftco-animate">
                        <div class="item">
                            <div class="destination shadow-sm" style="border-radius: 15px; overflow: hidden;">
                                <a href="#" class="img d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/destination-1.jpg') }}); height: 350px;">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="icon-search2"></span>
                                    </div>
                                </a>
                                <div class="text p-3 bg-white">
                                    <h3 class="font-weight-bold"><a href="#">Bwindi Forest, Uganda</a></h3>
                                    <span class="listing text-warning">Gorilla Encounters</span>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="destination shadow-sm" style="border-radius: 15px; overflow: hidden;">
                                <a href="#" class="img d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/destination-2.jpg') }}); height: 350px;">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="icon-search2"></span>
                                    </div>
                                </a>
                                <div class="text p-3 bg-white">
                                    <h3 class="font-weight-bold"><a href="#">Maasai Mara, Kenya</a></h3>
                                    <span class="listing text-warning">Great Migration</span>
                                </div>
                            </div>
                        </div>
                        </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url({{ asset('front/images/bg_1.jpg') }}); background-attachment: fixed;">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
            <h2 class="mb-4" style="font-family: 'Playfair Display', serif;">Our Impact in Numbers</h2>
            <span class="subheading">Creating memories and protecting wildlife since 2018</span>
          </div>
        </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row">
                  <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                    <div class="block-18 text-center">
                      <div class="text">
                        <strong class="number" data-number="4500" style="color: #ffc107;">0</strong>
                        <span class="text-white">Happy Clients</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                    <div class="block-18 text-center">
                      <div class="text">
                        <strong class="number" data-number="120" style="color: #ffc107;">0</strong>
                        <span class="text-white">Safari Routes</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                    <div class="block-18 text-center">
                      <div class="text">
                        <strong class="number" data-number="15" style="color: #ffc107;">0</strong>
                        <span class="text-white">Partner Lodges</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                    <div class="block-18 text-center">
                      <div class="text">
                        <strong class="number" data-number="20" style="color: #ffc107;">0</strong>
                        <span class="text-white">Professional Guides</span>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <section class="ftco-section testimony-section">
  <div class="container">
    <div class="row justify-content-center mb-5 pb-3">
      <div class="col-md-7 text-center heading-section ftco-animate">
        <span class="subheading" style="color: #ffc107; font-weight: 700; letter-spacing: 2px;">TESTIMONIALS</span>
        <h2 class="mb-4" style="font-family: 'Playfair Display', serif;">What Our <strong>Explorers</strong> Say</h2>
      </div>
    </div>
    <div class="row ftco-animate">
      <div class="col-md-12">
        <div class="carousel-testimony owl-carousel">
          <div class="item">
            <div class="testimony-wrap p-4 text-center bg-light" style="border-radius: 20px;">
              <div class="user-img mb-5" style="background-image: url({{ asset('front/images/person_1.jpg') }}); border: 4px solid #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <span class="quote d-flex align-items-center justify-content-center">
                  <i class="icon-quote-left text-white"></i>
                </span>
              </div>
              <div class="text">
                <p class="mb-5 italic">"The 10-day Uganda-Rwanda safari was life-changing. Everything from the lodge selection to our guide's knowledge was flawless."</p>
                <p class="name font-weight-bold">Sarah Jenkins</p>
                <span class="position">London, UK</span>
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
        <span class="subheading" style="color: #ffc107; font-weight: 700; letter-spacing: 2px;">JOURNAL</span>
        <h2 class="mb-4" style="font-family: 'Playfair Display', serif;"><strong>Recent</strong> Safari Stories</h2>
      </div>
    </div>
    <div class="row d-flex">
      <div class="col-md-4 d-flex ftco-animate">
        <div class="blog-entry align-self-stretch shadow-sm bg-white" style="border-radius: 15px; overflow: hidden;">
          <a href="#" class="block-20" style="background-image: url('{{ asset('front/images/image_1.jpg') }}');">
          </a>
          <div class="text p-4 d-block">
            <div class="meta mb-3">
              <div><a href="#" class="text-muted">August 12, 2026</a></div>
              <div><a href="#" class="text-muted">Admin</a></div>
            </div>
            <h3 class="heading"><a href="#">Tips for Your First Gorilla Trekking Experience</a></h3>
            <p>The mist-covered mountains of Bwindi hold secrets only the brave discover...</p>
            <p><a href="#" class="btn btn-link text-warning p-0">Read More <span class="ion-ios-arrow-forward"></span></a></p>
          </div>
        </div>
      </div>
      </div>
  </div>
</section>

    @push('scripts')
    <script>
        // Custom search bar interaction or additional home-specific JS
    </script>
    @endpush
</x-layout>