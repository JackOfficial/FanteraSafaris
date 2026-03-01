<x-layout>
    <x-slot name="styles">
        <style>
            .transition-hover {
                transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                will-change: transform;
            }
            .transition-hover:hover {
                transform: translateY(-12px);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
            }
            .transition-hover:hover .img, .blog-entry:hover .block-20 {
                transform: scale(1.05);
                transition: transform 0.6s ease;
            }
            .destination .img, .blog-entry .block-20 {
                overflow: hidden;
                transition: transform 0.6s ease;
            }
        </style>
    </x-slot>

    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/zanzibar_beach.jpg') }}');" data-stellar-background-ratio="0.5">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
          <div class="col-md-9 ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
            <h1 class="mb-4" style="font-family: 'Playfair Display', serif; font-weight: 800; font-size: 4rem; line-height: 1.1;">
                Experience the <br><span class="text-warning">African Wilderness</span>
            </h1>
            <p class="lead text-white mb-5" style="font-size: 1.25rem; max-width: 600px;">Luxury Gorilla trekking and bespoke savannah safaris curated by local experts across East Africa.</p>
            
            <div class="block-17 my-4 shadow-2xl" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(20px); border-radius: 20px; padding: 15px; border: 1px solid rgba(255,255,255,0.1);">
              <form action="{{ route('safaris.index') }}" method="GET" class="d-block d-flex align-items-center">
                <div class="fields d-block d-flex w-100">
                  <div class="textfield-search one-third w-100 mr-2">
                    <input type="text" name="query" class="form-control" placeholder="Where do you want to go?" style="border-radius: 12px; height: 60px; border: none; padding-left: 20px;">
                  </div>
                  <div class="select-wrap one-third w-100 mr-2">
                    <select name="country" class="form-control" style="border-radius: 12px; height: 60px; border: none;">
                      <option value="">Select Destination</option>
                      <option value="uganda">Uganda</option>
                      <option value="rwanda">Rwanda</option>
                      <option value="kenya">Kenya</option>
                      <option value="tanzania">Tanzania</option>
                    </select>
                  </div>
                </div>
                <button type="submit" class="btn btn-warning px-5" style="border-radius: 12px; height: 60px; font-weight: 700; min-width: 200px;">Find Adventure</button>  
              </form>
            </div>
            <p class="text-white-50">Popular: <a href="#" class="text-warning font-weight-bold ml-2">Gorilla Trekking</a>, <a href="#" class="text-warning font-weight-bold ml-2">Maasai Mara</a></p>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section services-section bg-light py-5">
      <div class="container">
        <div class="row">
          @php
            $services = [
                ['icon' => 'flaticon-guarantee', 'title' => 'Fair Pricing', 'desc' => 'Direct local rates without the middleman markup.'],
                ['icon' => 'flaticon-like', 'title' => 'Expert Guides', 'desc' => 'Born and raised here, our guides know every secret trail.'],
                ['icon' => 'flaticon-detective', 'title' => 'Eco-Conscious', 'desc' => 'We prioritize sustainable tourism and local communities.'],
                ['icon' => 'flaticon-support', 'title' => '24/7 Support', 'desc' => 'From booking to departure, we are with you every step.']
            ];
          @endphp
          @foreach($services as $s)
          <div class="col-md-3 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services d-block text-center shadow-sm p-4 bg-white transition-hover" style="border-radius: 20px; border-bottom: 5px solid #ffc107;">
              <div class="d-flex justify-content-center mb-3"><div class="icon"><span class="{{ $s['icon'] }} text-warning" style="font-size: 50px;"></span></div></div>
              <div class="media-body">
                <h3 class="heading mb-3 font-weight-bold" style="font-size: 1.2rem;">{{ $s['title'] }}</h3>
                <p class="text-muted small">{{ $s['desc'] }}</p>
              </div>
            </div>      
          </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="ftco-section ftco-no-pt ftc-no-pb mt-5">
        <div class="container">
            <div class="row no-gutters align-items-center">
                <div class="col-md-5 p-md-5 img img-2 d-flex justify-content-center align-items-center shadow-lg" 
                     style="background-image: url({{ asset('front/images/about_us.jpg') }}); border-radius: 30px; height: 500px;">
                </div>
                <div class="col-md-7 wrap-about py-md-5 ftco-animate pl-md-5">
                    <div class="heading-section mb-5">
                        <span class="subheading" style="color: #ffc107; font-weight: 700; letter-spacing: 2px;">ESTABLISHED 2018</span>
                        <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-size: 2.5rem;">Adventure is <strong>Our DNA</strong></h2>
                        <p class="lead text-dark">We don’t just organize trips; we curate life-defining moments based in the heart of East Africa.</p>
                        <p class="text-muted">Whether it’s the heavy breath of a Silverback Gorilla in the Bwindi mist or the golden sunset over the Maasai Mara, we ensure your journey is ethical, immersive, and luxurious.</p>
                        <a href="/about" class="btn btn-outline-warning px-4 py-3 mt-3" style="border-radius: 30px; font-weight: 600;">Learn Our Story</a>
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
                    @forelse($destinations as $destination)
                        <div class="item">
                            <div class="destination shadow-sm transition-hover" style="border-radius: 15px; overflow: hidden;">
                                {{-- Link to a destination details page --}}
                                <a href="#" 
                                   class="img d-flex justify-content-center align-items-center" 
                                   style="background-image: url({{ asset('storage/' . $destination->image) }}); height: 350px;">
                                </a>
                                <div class="text p-3 bg-white">
                                    <h3 class="font-weight-bold">
                                        <a href="#">
                                            {{ $destination->name }}, {{ $destination->country }}
                                        </a>
                                    </h3>
                                    {{-- Use a 'tagline' or 'type' field from your DB --}}
                                    <span class="listing text-warning font-weight-bold">{{ $destination->tagline }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p>No destinations found at the moment. Adventure is coming soon!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

   <section class="ftco-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 text-center heading-section ftco-animate">
                <span class="subheading" style="color: #ffc107; font-weight: 700; letter-spacing: 2px;">POPULAR TOURS</span>
                <h2 class="mb-4" style="font-family: 'Playfair Display', serif;">Featured <strong>Safari Packages</strong></h2>
            </div>
        </div>
        <div class="row">
            @forelse($packages as $package)
                <div class="col-md-4 ftco-animate mb-4">
                    <div class="destination shadow-sm transition-hover" style="border-radius: 20px; overflow: hidden; background: #fff;">
                        {{-- Package Image with dynamic badge --}}
                        <a href="#" 
                           class="img d-flex justify-content-center align-items-center" 
                           style="background-image: url({{ asset('storage/' . $package->image) }}); height: 280px; position: relative;">
                            
                            @if($package->is_top_rated)
                                <span class="badge badge-warning p-2 px-3" style="position: absolute; top: 20px; left: 20px; border-radius: 20px;">Top Rated</span>
                            @endif
                        </a>

                        <div class="text p-4">
                            <div class="d-flex mb-2">
                                {{-- Use number_format for clean pricing --}}
                                <span class="price font-weight-bold text-warning" style="font-size: 1.4rem;">
                                    ${{ number_format($package->price) }}
                                </span>
                                <span class="ml-auto text-muted">
                                    <i class="icon-calendar mr-1"></i> {{ $package->duration }} Days
                                </span>
                            </div>

                            <h3 class="h5 font-weight-bold mb-3">
                                <a href="#" class="text-dark">{{ $package->title }}</a>
                            </h3>

                            {{-- Assuming a relationship exists between Package and Destination --}}
                            <p class="text-muted small mb-3">
                                <i class="icon-map-o mr-2"></i> {{ $package->location_label ?? $package->destination->name }}
                            </p>

                            <a href="#" class="btn btn-warning btn-block py-2" style="border-radius: 10px; font-weight: 700;">
                                Explore Package
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12 text-center">
                    <p class="text-muted">No safari packages available at the moment. Check back soon!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

    <section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url({{ asset('front/images/bg_1.jpg') }}); background-attachment: fixed;">
        <div class="overlay" style="background: rgba(0,0,0,0.6);"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row">
                        @foreach([['4500', 'Happy Clients'], ['120', 'Safari Routes'], ['15', 'Partner Lodges'], ['20', 'Professional Guides']] as $stat)
                        <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                          <div class="block-18 text-center">
                            <strong class="number" data-number="{{ $stat[0] }}" style="color: #ffc107; font-size: 3rem;">0</strong>
                            <span class="text-white d-block uppercase tracking-widest" style="font-size: 0.8rem;">{{ $stat[1] }}</span>
                          </div>
                        </div>
                        @endforeach
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
                <div class="testimony-wrap p-4 text-center bg-light shadow-sm" style="border-radius: 20px;">
                  <div class="user-img mb-4" style="background-image: url({{ asset('front/images/person_1.jpg') }}); border: 4px solid #fff;"></div>
                  <div class="text">
                    <p class="mb-4 font-italic">"The 10-day Uganda-Rwanda safari was life-changing. Everything from the lodge selection to our guide's knowledge was flawless."</p>
                    <p class="name font-weight-bold mb-0">Sarah Jenkins</p>
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
            <div class="blog-entry align-self-stretch shadow-sm bg-white transition-hover" style="border-radius: 15px; overflow: hidden;">
              <a href="#" class="block-20" style="background-image: url('{{ asset('front/images/image_1.jpg') }}'); height: 250px; display: block;"></a>
              <div class="text p-4 d-block">
                <div class="meta mb-3">
                  <div class="text-muted small"><i class="fa fa-calendar mr-2"></i> August 12, 2026</div>
                </div>
                <h3 class="heading h5 font-weight-bold"><a href="#" class="text-dark">Tips for Your First Gorilla Trekking Experience</a></h3>
                <p class="text-muted small">The mist-covered mountains of Bwindi hold secrets only the brave discover...</p>
                <a href="#" class="btn btn-link text-warning p-0 font-weight-bold">Read More <span class="ion-ios-arrow-forward"></span></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-intro" style="background-color: #1a1a1a; padding: 80px 0; border-bottom: 5px solid #ffc107;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 text-center text-md-left">
                    <h2 class="text-white" style="font-family: 'Playfair Display', serif; font-size: 2.2rem;">Ready to start your <strong>unforgettable</strong> journey?</h2>
                    <p class="text-white-50 mb-0">Contact our expert safari planners today and get a free custom itinerary.</p>
                </div>
                <div class="col-md-4 text-center text-md-right mt-4 mt-md-0">
                    <a href="/contact" class="btn btn-warning py-4 px-5 shadow-lg" style="border-radius: 50px; font-weight: 800;">Inquire Now</a>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center heading-section ftco-animate">
                  <h2 class="mb-4" style="font-family: 'Playfair Display', serif;">The <strong>Safari Insider</strong></h2>
                  <p class="text-muted">Join 5,000+ travelers. Get seasonal trekking updates and exclusive last-minute deals.</p>
                  <form action="#" class="subscribe-form mt-4">
                    <div class="form-group d-flex shadow-sm" style="border-radius: 50px; overflow: hidden; border: 1px solid #eee; background: #fff; padding: 5px;">
                      <input type="email" class="form-control px-4" placeholder="Enter your email" style="border: none; height: 55px; box-shadow: none;">
                      <button type="submit" class="btn btn-dark px-5" style="border-radius: 50px; font-weight: 700;">Join</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </section>
</x-layout>