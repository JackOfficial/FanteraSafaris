<x-layout>
    <x-slot name="styles">
        <style>
            /* 1. The Base Class: Sets the transition speed */
            .transition-hover {
                transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); /* Smooth "luxury" ease */
                will-change: transform;
            }

            /* 2. The Hover State: Lift and Shadow */
            .transition-hover:hover {
                transform: translateY(-12px);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
            }

            /* 3. Optional: Make images inside the card zoom slightly */
            .transition-hover:hover .img {
                transform: scale(1.05);
                transition: transform 0.6s ease;
            }
            
            .destination .img {
                overflow: hidden;
                transition: transform 0.6s ease;
            }
        </style>
    </x-slot>
    
    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/Bwindi.jpg') }}');" data-stellar-background-ratio="0.5">
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
                <button type="submit" class="btn btn-warning px-5" style="border-radius: 12px; height: 60px; font-weight: 700; min-width: 200px;">
                    Find Adventure
                </button>  
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
            <div class="media block-6 services d-block text-center shadow-sm p-4 bg-white transition" style="border-radius: 20px; border-bottom: 5px solid transparent; transition: 0.3s;">
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
                     style="background-image: url({{ asset('front/images/about.jpg') }}); border-radius: 30px; height: 500px;">
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

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-md-7 text-center heading-section ftco-animate">
                    <span class="subheading" style="color: #ffc107; font-weight: 700; letter-spacing: 2px;">POPULAR TOURS</span>
                    <h2 class="mb-4" style="font-family: 'Playfair Display', serif;">Featured <strong>Safari Packages</strong></h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 ftco-animate mb-4">
                    <div class="destination shadow-sm transition-hover" style="border-radius: 20px; overflow: hidden; background: #fff;">
                        <a href="#" class="img d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/safari-1.jpg') }}); height: 280px; position: relative;">
                            <span class="badge badge-warning p-2 px-3" style="position: absolute; top: 20px; left: 20px; border-radius: 20px;">Top Rated</span>
                        </a>
                        <div class="text p-4">
                            <div class="d-flex mb-2">
                                <span class="price font-weight-bold text-warning" style="font-size: 1.4rem;">$1,200</span>
                                <span class="ml-auto text-muted"><i class="icon-calendar mr-1"></i> 5 Days</span>
                            </div>
                            <h3 class="h5 font-weight-bold mb-3"><a href="#" class="text-dark">Queen Elizabeth & Bwindi Forest</a></h3>
                            <p class="text-muted small mb-3"><i class="icon-map-o mr-2"></i> Uganda, East Africa</p>
                            <a href="#" class="btn btn-warning btn-block py-2" style="border-radius: 10px; font-weight: 700;">Explore Package</a>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </section>

    <section class="ftco-section ftco-counter img shadow-lg" id="section-counter" style="background-image: url({{ asset('front/images/bg_1.jpg') }}); background-attachment: fixed; margin: 40px 0;">
        <div class="overlay" style="background: rgba(0,0,0,0.6);"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row">
                      @php
                        $stats = [
                            ['num' => '4500', 'label' => 'Happy Clients'],
                            ['num' => '120', 'label' => 'Safari Routes'],
                            ['num' => '15', 'label' => 'Partner Lodges'],
                            ['num' => '20', 'label' => 'Expert Guides']
                        ];
                      @endphp
                      @foreach($stats as $stat)
                      <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                        <div class="block-18 text-center">
                          <div class="text">
                            <strong class="number" data-number="{{ $stat['num'] }}" style="color: #ffc107; font-size: 3rem;">0</strong>
                            <span class="text-white d-block uppercase tracking-widest" style="font-size: 0.8rem;">{{ $stat['label'] }}</span>
                          </div>
                        </div>
                      </div>
                      @endforeach
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
                    <a href="/contact" class="btn btn-warning py-4 px-5 shadow-lg" style="border-radius: 50px; font-weight: 800; text-transform: uppercase;">Inquire Now</a>
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
                    <div class="form-group d-flex shadow-sm" style="border-radius: 50px; overflow: hidden; border: 1px solid #eee;">
                      <input type="email" class="form-control px-4" placeholder="Enter your email" style="border: none; height: 60px;">
                      <button type="submit" class="btn btn-dark px-5" style="border-radius: 0; font-weight: 700;">Join</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </section>

</x-layout>