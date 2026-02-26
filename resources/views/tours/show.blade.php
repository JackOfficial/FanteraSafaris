<x-layout 
    :title="'Bwindi Gorilla Trek | Fantera Safaris'"
    :metaDescription="'Experience the unforgettable Bwindi Gorilla Trek in Uganda. Luxury safari packages, expert guides, and conservation-focused travel.'"
>
   <x-slot name="styles">
        <style>
            /* 1. Header & Breadcrumbs */
            .hero-wrap.hero-bread { height: 500px !important; }
            .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before { content: "→"; color: rgba(255,255,255,0.5); }

            /* 2. Timeline Design for Itinerary */
            .itinerary-timeline { position: relative; padding-left: 40px; }
            .itinerary-timeline::before {
                content: ''; position: absolute; left: 15px; top: 0; bottom: 0;
                width: 2px; background: #eee; border-radius: 2px;
            }
            .timeline-item { position: relative; margin-bottom: 30px; }
            .timeline-day {
                position: absolute; left: -40px; top: 0;
                width: 32px; height: 32px; background: var(--warning);
                border-radius: 50%; color: #000; font-weight: 800;
                display: flex; align-items: center; justify-content: center;
                font-size: 12px; z-index: 2; border: 4px solid #fff;
            }

            /* 3. Icon Grid Styling */
            .feature-box {
                background: #f8f9fa;
                border-radius: 15px;
                padding: 20px;
                transition: 0.3s;
                height: 100%;
                border: 1px solid transparent;
            }
            .feature-box:hover { border-color: var(--warning); background: #fff; transform: translateY(-5px); }
            .icon-circle {
                width: 50px; height: 50px; background: rgba(255, 193, 7, 0.1);
                border-radius: 12px; display: flex; align-items: center; justify-content: center;
            }

            /* 4. Gallery Hover */
            .gallery-card { position: relative; border-radius: 20px; overflow: hidden; cursor: pointer; }
            .gallery-card img { transition: 0.5s; }
            .gallery-card:hover img { transform: scale(1.1); }
            .gallery-overlay {
                position: absolute; inset: 0; background: rgba(0,0,0,0.3);
                opacity: 0; transition: 0.3s; display: flex; align-items: center; justify-content: center;
            }
            .gallery-card:hover .gallery-overlay { opacity: 1; }

            /* 5. Sidebar Polish */
            .sidebar-card { border-radius: 25px; border: none; overflow: hidden; }
            .sticky-top { top: 100px !important; }
        </style>
 </x-slot>

    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/Bwindi.jpg') }}'); height: 60vh;">
        <div class="overlay"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-10 ftco-animate">
                    <nav aria-label="breadcrumb" class="breadcrumb-modern">
                        <ol class="breadcrumb justify-content-center bg-transparent p-0 m-0 mb-3">
                            <li class="breadcrumb-item"><a href="/" class="text-white-50">Home</a></li>
                            <li class="breadcrumb-item"><a href="/tours" class="text-white-50">Tours</a></li>
                            <li class="breadcrumb-item active text-warning font-weight-bold">Bwindi Trek</li>
                        </ol>
                    </nav>
                    <h1 class="display-3 font-weight-bold text-white mb-3" style="font-family: 'Playfair Display', serif;">Bwindi Gorilla Trek</h1>
                    <div class="d-flex justify-content-center align-items-center text-white-50">
                        <span class="mx-3"><i class="fas fa-clock text-warning mr-2"></i> 3 Days</span>
                        <span class="mx-3"><i class="fas fa-map-marker-alt text-warning mr-2"></i> Uganda</span>
                        <span class="mx-3"><i class="fas fa-star text-warning mr-1"></i> 5.0 (12 Reviews)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 ftco-animate">
                    
                    <div class="bg-white p-4 p-md-5 rounded-lg shadow-sm mb-5" style="border-radius: 25px;">
                        <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700;">Experience the Mist</h2>
                        <p class="lead text-muted mb-4">Tracking mountain gorillas in Bwindi is not just a safari; it is an emotional encounter with our closest cousins in a prehistoric rainforest.</p>
                        
                        <div class="row py-4 border-top border-bottom mb-4">
                            <div class="col-6 col-md-3 text-center border-right">
                                <span class="d-block text-muted small uppercase font-weight-bold">Duration</span>
                                <span class="font-weight-bold text-dark">3 Days</span>
                            </div>
                            <div class="col-6 col-md-3 text-center border-right">
                                <span class="d-block text-muted small uppercase font-weight-bold">Group</span>
                                <span class="font-weight-bold text-dark">Max 8</span>
                            </div>
                            <div class="col-6 col-md-3 text-center border-right">
                                <span class="d-block text-muted small uppercase font-weight-bold">Difficulty</span>
                                <span class="font-weight-bold text-dark">Moderate</span>
                            </div>
                            <div class="col-6 col-md-3 text-center">
                                <span class="d-block text-muted small uppercase font-weight-bold">Ages</span>
                                <span class="font-weight-bold text-dark">15+</span>
                            </div>
                        </div>

                        <p>This expedition takes you deep into the <strong>Bwindi Impenetrable National Park</strong>, a UNESCO World Heritage site. You will trek through thick ferns and ancient trees until you find yourself mere meters away from a silverback and his family.</p>
                    </div>

                    <div class="mb-5">
                        <h3 class="mb-4 font-weight-bold" style="font-family: 'Playfair Display', serif;">Detailed Itinerary</h3>
                        <div class="itinerary-timeline">
                            @foreach($itinerary as $item)
                            <div class="timeline-item">
                                <div class="timeline-day">{{ $item['day'] }}</div>
                                <div class="bg-white p-4 rounded-lg shadow-sm ml-2">
                                    <h5 class="font-weight-bold text-dark">{{ $item['title'] }}</h5>
                                    <p class="text-muted mb-0">{{ $item['content'] }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="mb-4 font-weight-bold">What’s Included</h3>
                        <div class="row">
                            @foreach($features as $f)
                            <div class="col-md-6 mb-3">
                                <div class="feature-box d-flex align-items-center">
                                    <div class="icon-circle mr-3"><i class="fas fa-{{ $f['icon'] }} text-warning"></i></div>
                                    <span class="font-weight-bold text-dark small">{{ $f['text'] }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="mb-4 font-weight-bold">Moments from the Trail</h3>
                        <div class="row">
                            @foreach(['Bwindi_1.jpg', 'Bwindi_2.jpg'] as $img)
                            <div class="col-md-6 mb-4">
                                <div class="gallery-card shadow-sm">
                                    <img src="{{ asset('front/images/'.$img) }}" class="img-fluid" alt="Bwindi">
                                    <div class="gallery-overlay"><i class="fas fa-expand text-white fa-2x"></i></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 sidebar">
                    <div class="sticky-top">
                        <div class="card sidebar-card shadow-lg mb-4">
                            <div class="bg-dark p-4 text-center">
                                <span class="text-white-50 small d-block">Starting from</span>
                                <h2 class="text-warning font-weight-bold mb-0">$1,800</h2>
                            </div>
                            <div class="card-body p-4">
                                <form action="/booking/bwindi" method="GET">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">Travel Date</label>
                                        <input type="date" class="form-control border-0 bg-light" style="border-radius: 10px;">
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="small font-weight-bold">Travelers</label>
                                        <select class="form-control border-0 bg-light" style="border-radius: 10px;">
                                            <option>1 Person</option>
                                            <option>2 Persons</option>
                                            <option>Group (4+)</option>
                                        </select>
                                    </div>
                                    <button class="btn btn-warning btn-block btn-lg py-3 shadow" style="border-radius: 15px; font-weight: 800;">RESERVE NOW</button>
                                </form>
                                <div class="text-center mt-3">
                                    <p class="small text-muted mb-0"><i class="fas fa-shield-alt mr-1"></i> Secure Payment & Local Support</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-lg shadow-sm mb-4" style="border-radius: 20px;">
                            <h5 class="font-weight-bold mb-3 small uppercase">Why book with us?</h5>
                            <ul class="list-unstyled small">
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> 100% Tailor-made itineraries</li>
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Verified eco-luxury lodges</li>
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Expert local Gorilla guides</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>