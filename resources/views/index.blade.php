@section('content')
<style>
    /* Modern Safari Branding */
    :root { --safari-gold: #c5a059; --safari-green: #1a4314; --soft-bg: #f8f9fa; }
    
    .hero-wrap { position: relative; border-radius: 0 0 40px 40px; overflow: hidden; }
    
    /* Elegant Search Bar UX */
    .block-17 {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(15px);
        padding: 15px;
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .block-17 form { background: #fff; border-radius: 15px; padding: 10px; overflow: hidden; }
    .block-17 .form-control { border: none !important; font-weight: 500; }
    .block-17 .search-submit { border-radius: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }

    /* Service Icon Refinement */
    .services { 
        padding: 30px; 
        background: #fff; 
        border-radius: 20px; 
        transition: 0.3s; 
        border: 1px solid transparent;
    }
    .services:hover { transform: translateY(-10px); border-color: var(--safari-gold); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
    .services .icon { color: var(--safari-gold); font-size: 50px; margin-bottom: 20px; }

    /* Destination Cards */
    .destination { border-radius: 20px; overflow: hidden; background: #fff; }
    .destination .img { height: 300px; transition: 0.5s; }
    .destination:hover .img { transform: scale(1.08); }
    .destination .text h3 a { color: #222; font-weight: 700; }
    .price-badge { background: var(--safari-green); color: #fff; padding: 4px 12px; border-radius: 50px; font-size: 14px; }

    /* Blog Entry Styling */
    .blog-entry { border-radius: 20px; overflow: hidden; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .blog-entry .tag { color: var(--safari-gold); font-weight: 700; font-size: 12px; text-transform: uppercase; }

    /* Video section curve */
    .img-video { border-radius: 30px; box-shadow: 20px 20px 60px rgba(0,0,0,0.1); }
</style>

<div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/bg_1.jpg') }}');">
    <div class="overlay" style="background: linear-gradient(45deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.2) 100%);"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start">
            <div class="col-md-9 ftco-animate">
                <h1 class="display-3 font-weight-bold text-white mb-4">
                    Experience <span style="color: var(--safari-gold)">The Wild Heart</span> of Africa
                </h1>
                <p class="lead text-white-50 mb-5">Bespoke luxury safaris through Uganda, Kenya, and Tanzania. From Mountain Gorillas to the Great Migration.</p>
                
                <div class="block-17 shadow-lg">
                    <form action="#" method="get" class="row no-gutters align-items-center">
                        <div class="col-md-5 border-right">
                            <div class="textfield-search px-3">
                                <input type="text" class="form-control" placeholder="What are you looking for?">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="select-wrap px-3">
                                <select class="form-control">
                                    <option value="">Choose Destination</option>
                                    <option>Uganda - Pearl of Africa</option>
                                    <option>Kenya - Maasai Mara</option>
                                    <option>Tanzania - Serengeti</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="submit" class="search-submit btn btn-primary btn-block py-3" value="Inquire Now">
                        </div>
                    </form>
                </div>
                
                <div class="browse d-md-flex mt-5 align-items-center">
                    <span class="text-white-50 mr-4 font-italic">Popular Tags:</span>
                    <a href="#" class="badge badge-pill badge-outline-white border px-3 py-2 mr-2 text-white">#GorillaTreks</a>
                    <a href="#" class="badge badge-pill badge-outline-white border px-3 py-2 mr-2 text-white">#GameDrives</a>
                    <a href="#" class="badge badge-pill badge-outline-white border px-3 py-2 text-white">#LuxuryLodges</a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section services-section bg-light">
    <div class="container">
        <div class="row">
            @php 
                $services = [
                    ['icon' => 'flaticon-guarantee', 'h' => 'Expert Local Knowledge', 'p' => 'Based in Kampala, our guides possess intimate knowledge of East Africa.'],
                    ['icon' => 'flaticon-like', 'h' => 'Tailor-Made Journeys', 'p' => 'We curate every itinerary to match your specific desires.'],
                    ['icon' => 'flaticon-detective', 'h' => 'Eco-Conscious Travel', 'p' => 'We prioritize sustainability and community support.'],
                    ['icon' => 'flaticon-support', 'h' => '24/7 Concierge Support', 'p' => 'From Entebbe arrival to departure, we are with you.']
                ];
            @endphp
            @foreach($services as $s)
            <div class="col-md-3 d-flex align-self-stretch ftco-animate">
                <div class="media block-6 services d-block text-center shadow-sm">
                    <div class="icon"><span class="{{ $s['icon'] }}"></span></div>
                    <div class="media-body">
                        <h3 class="heading h5 font-weight-bold">{{ $s['h'] }}</h3>
                        <p class="small text-muted">{{ $s['p'] }}</p>
                    </div>
                </div>      
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="ftco-section ftco-destination">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 heading-section text-center ftco-animate">
                <span class="subheading" style="color: var(--safari-gold)">Unforgettable Places</span>
                <h2 class="mb-4"><strong>Featured</strong> Destinations</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="destination-slider owl-carousel ftco-animate">
                    @foreach(['Bwindi' => 'Home of the Gorillas', 'Serengeti' => 'The Great Migration', 'Maasai mara' => 'Unlimited Game Viewing'] as $name => $tagline)
                    <div class="item">
                        <div class="destination shadow-sm">
                            <a href="#" class="img d-flex justify-content-center align-items-center" style="background-image: url('{{ asset('front/images/'.$name.'.jpg') }}');">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-4">
                                <h3 class="h5 mb-1"><a href="#">{{ $name }}, East Africa</a></h3>
                                <span class="listing text-success small font-weight-bold">{{ $tagline }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section bg-light">
    <div class="container">
        <div class="row justify-content-between align-items-end mb-5">
            <div class="col-md-6 heading-section ftco-animate">
                <span class="subheading">Exclusive Experiences</span>
                <h2 class="mb-0"><strong>Top</strong> Safari Packages</h2>
            </div>
            <div class="col-md-3 text-md-right ftco-animate">
                <a href="#" class="btn btn-primary rounded-pill px-4">View All Packages</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3 ftco-animate mb-4">
                <div class="destination shadow-sm h-100">
                    <a href="#" class="img img-2" style="background-image: url({{ asset('front/images/Serengeti.jpg') }});"></a>
                    <div class="text p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="price-badge">$1,250</span>
                            <div class="text-warning small"><i class="icon-star"></i> 4.9</div>
                        </div>
                        <h3 class="h5 font-weight-bold"><a href="#">Serengeti Migration</a></h3>
                        <p class="small text-muted mb-3">Witness the world's largest land mammal migration.</p>
                        <hr>
                        <div class="bottom-area d-flex justify-content-between align-items-center">
                            <span class="small text-muted"><i class="icon-calendar mr-1"></i> 5 Days</span>
                            <a href="#" class="font-weight-bold text-dark small">Book Now &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
    </div>
</section>

<section class="ftco-section ftco-no-pt ftco-no-pb">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 pr-md-5">
                <div class="img img-video rounded shadow-lg overflow-hidden" style="background-image:url({{ asset('front/images/about_us.jpg') }}); height: 500px;">
                    <div class="overlay d-flex align-items-center justify-content-center">
                        <a href="#" class="icon-video d-flex align-items-center justify-content-center"><span class="icon-play text-white" style="font-size: 40px;"></span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 py-5 pl-md-5">
                <div class="heading-section ftco-animate">
                    <span class="subheading" style="border-left: 3px solid var(--safari-gold); padding-left: 15px;">Since 2014</span>
                    <h2 class="mb-4">The Fantera Safaris Philosophy</h2>
                    <p class="text-muted">We don't just organize trips; we curate soul-stirring encounters with the wild. Born in the heart of Uganda, we believe travel should be immersive, ethical, and uncompromising.</p>
                    <p><a href="#" class="btn btn-dark rounded-pill px-5 py-3 shadow">Discover Our Story</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="ftco-section ftco-counter img my-5" id="section-counter" style="background-image: url({{ asset('front/images/bg_1.jpg') }}); border-radius: 40px;">
    <div class="container py-5">
        <div class="row justify-content-center">
            @foreach(['1500+' => 'Adventurers', '45' => 'Parks', '120' => 'Lodges', '12' => 'Years'] as $num => $label)
            <div class="col-md-3 text-center counter-wrap ftco-animate text-white">
                <h2 class="display-4 font-weight-bold mb-0 text-white">{{ $num }}</h2>
                <span class="text-white-50">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="ftco-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
            <div class="col-md-7 text-center heading-section ftco-animate">
                <span class="subheading">Safari Insights</span>
                <h2><strong>Wild</strong> Stories & Safari Tips</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 d-flex ftco-animate">
                <div class="blog-entry w-100">
                    <a href="#" class="block-20" style="background-image: url({{ asset('front/images/Serengeti.jpg') }});"></a>
                    <div class="text p-4">
                        <span class="tag">Wild Experience</span>
                        <h3 class="h6 font-weight-bold mt-2"><a href="#">The Great Migration: When to See the Herd</a></h3>
                        <div class="meta small text-muted">Feb 24, 2026 • 5 min read</div>
                    </div>
                </div>
            </div>
            </div>
    </div>
</section>

<section class="ftco-section testimony-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-5 pr-lg-5">
                <div class="heading-section ftco-animate">
                    <span class="subheading">Fantera Safaris</span>
                    <h2 class="mb-4"><strong>Why</strong> Book With Us?</h2>
                    <p class="text-muted mb-4">With over a decade of experience, we curate life-changing encounters. Our guides ensure you are in the right place at the right moment.</p>
                    <ul class="list-unstyled mb-5">
                        <li class="mb-2"><i class="icon-check text-success mr-2"></i> Private Luxury Vehicles</li>
                        <li class="mb-2"><i class="icon-check text-success mr-2"></i> Guaranteed Gorilla Permits</li>
                        <li><i class="icon-check text-success mr-2"></i> Local Community Support</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-7">
                <div class="carousel-testimony owl-carousel shadow-lg p-4 bg-white rounded-lg">
                    <div class="item text-center px-4">
                        <div class="user-img mb-4 mx-auto shadow" style="background-image: url({{ asset('front/images/person_1.jpg') }}); width: 80px; height: 80px; border-radius: 50%;"></div>
                        <p class="font-italic text-muted">"Watching the Great Migration from a hot air balloon was surreal. The knowledge of our guide made this the trip of a lifetime!"</p>
                        <h5 class="font-weight-bold mb-0 mt-4">James Henderson</h5>
                        <span class="small text-muted">Canada</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row bg-dark rounded-lg p-5 align-items-center shadow-lg">
            <div class="col-md-8 text-white">
                <h2 class="h3 font-weight-bold">Follow the Adventure</h2>
                <p class="text-white-50 mb-0">Join our community on Instagram for daily glimpses into the wild.</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <a href="#" class="btn btn-white px-5 py-3 rounded-pill font-weight-bold">@FanteraSafaris</a>
            </div>
        </div>
    </div>
</section>

@endsection