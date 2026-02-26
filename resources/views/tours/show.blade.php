<x-layout 
    :title="'Bwindi Gorilla Trek | Fantera Safaris'"
    :metaDescription="'Experience the unforgettable Bwindi Gorilla Trek in Uganda. Luxury safari packages, expert guides, and conservation-focused travel.'"
>
   <x-slot name="styles">
        <style>
            .hero-wrap.hero-bread { height: 500px !important; }
            .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before { content: "→"; color: rgba(255,255,255,0.5); }

            /* Timeline Design */
            .itinerary-timeline { position: relative; padding-left: 40px; }
            .itinerary-timeline::before {
                content: ''; position: absolute; left: 15px; top: 0; bottom: 0;
                width: 2px; background: #eee; border-radius: 2px;
            }
            .timeline-item { position: relative; margin-bottom: 30px; }
            .timeline-day {
                position: absolute; left: -40px; top: 0;
                width: 32px; height: 32px; background: #ffc107;
                border-radius: 50%; color: #000; font-weight: 800;
                display: flex; align-items: center; justify-content: center;
                font-size: 12px; z-index: 2; border: 4px solid #fff;
            }

            /* Icon Grid */
            .feature-box {
                background: #f8f9fa;
                border-radius: 15px;
                padding: 20px;
                transition: 0.3s;
                height: 100%;
                border: 1px solid transparent;
            }
            .feature-box:hover { border-color: #ffc107; background: #fff; transform: translateY(-5px); }
            .icon-circle {
                width: 50px; height: 50px; background: rgba(255, 193, 7, 0.1);
                border-radius: 12px; display: flex; align-items: center; justify-content: center;
            }

            /* Gallery */
            .gallery-card { position: relative; border-radius: 20px; overflow: hidden; cursor: pointer; }
            .gallery-card img { transition: 0.5s; width: 100%; object-fit: cover; height: 250px; }
            .gallery-card:hover img { transform: scale(1.1); }
            .gallery-overlay {
                position: absolute; inset: 0; background: rgba(0,0,0,0.3);
                opacity: 0; transition: 0.3s; display: flex; align-items: center; justify-content: center;
            }
            .gallery-card:hover .gallery-overlay { opacity: 1; }

            .sidebar-card { border-radius: 25px; border: none; overflow: hidden; }
            .sticky-top { top: 100px !important; }
        </style>
    </x-slot>

    <div class="hero-wrap" style="background-image: url('{{ asset('front/images/Bwindi.jpg') }}'); height: 60vh; background-size: cover; background-position: center; position: relative;">
        <div class="overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4);"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-10" style="z-index: 2;">
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

    <section class="ftco-section bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="bg-white p-4 p-md-5 mb-5 shadow-sm" style="border-radius: 25px;">
                        <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700;">Experience the Mist</h2>
                        <p class="lead text-muted mb-4">Tracking mountain gorillas in Bwindi is an emotional encounter with our closest cousins in a prehistoric rainforest.</p>
                        
                        <div class="row py-4 border-top border-bottom mb-4">
                            <div class="col-6 col-md-3 text-center border-right">
                                <span class="d-block text-muted small font-weight-bold uppercase">Duration</span>
                                <span class="font-weight-bold text-dark">3 Days</span>
                            </div>
                            <div class="col-6 col-md-3 text-center border-right">
                                <span class="d-block text-muted small font-weight-bold uppercase">Group</span>
                                <span class="font-weight-bold text-dark">Max 8</span>
                            </div>
                            <div class="col-6 col-md-3 text-center border-right">
                                <span class="d-block text-muted small font-weight-bold uppercase">Difficulty</span>
                                <span class="font-weight-bold text-dark">Moderate</span>
                            </div>
                            <div class="col-6 col-md-3 text-center">
                                <span class="d-block text-muted small font-weight-bold uppercase">Ages</span>
                                <span class="font-weight-bold text-dark">15+</span>
                            </div>
                        </div>
                        <p>Explore UNESCO World Heritage sites and trek through thick ferns until you find yourself mere meters away from a silverback family.</p>
                    </div>

                    <div class="mb-5">
                        <h3 class="mb-4 font-weight-bold" style="font-family: 'Playfair Display', serif;">Detailed Itinerary</h3>
                        <div class="itinerary-timeline">
                            <div class="timeline-item">
                                <div class="timeline-day">01</div>
                                <div class="bg-white p-4 rounded-lg shadow-sm ml-2">
                                    <h5 class="font-weight-bold text-dark">Arrival & Transfer to Bwindi</h5>
                                    <p class="text-muted mb-0">Arrive at Entebbe and begin your journey through the "Little Switzerland of Africa" towards the forest.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-day">02</div>
                                <div class="bg-white p-4 rounded-lg shadow-sm ml-2">
                                    <h5 class="font-weight-bold text-dark">The Gorilla Trekking Experience</h5>
                                    <p class="text-muted mb-0">The highlight of your trip. Spend an hour observing the gorillas in their natural habitat.</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-day">03</div>
                                <div class="bg-white p-4 rounded-lg shadow-sm ml-2">
                                    <h5 class="font-weight-bold text-dark">Scenic Return & Departure</h5>
                                    <p class="text-muted mb-0">Enjoy a final breakfast in the mist before heading back to Entebbe for your flight.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="mb-4 font-weight-bold">What’s Included</h3>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="feature-box d-flex align-items-center">
                                    <div class="icon-circle mr-3"><i class="fas fa-hotel text-warning"></i></div>
                                    <span class="font-weight-bold text-dark small">Luxury Lodge Accommodation</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="feature-box d-flex align-items-center">
                                    <div class="icon-circle mr-3"><i class="fas fa-utensils text-warning"></i></div>
                                    <span class="font-weight-bold text-dark small">All Gourmet Meals</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="feature-box d-flex align-items-center">
                                    <div class="icon-circle mr-3"><i class="fas fa-leaf text-warning"></i></div>
                                    <span class="font-weight-bold text-dark small">Gorilla Trekking Permits</span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="feature-box d-flex align-items-center">
                                    <div class="icon-circle mr-3"><i class="fas fa-car text-warning"></i></div>
                                    <span class="font-weight-bold text-dark small">4x4 Safari Land Cruiser</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="mb-4 font-weight-bold">Moments from the Trail</h3>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="gallery-card shadow-sm">
                                    <img src="{{ asset('front/images/Bwindi_1.jpg') }}" alt="Gorilla">
                                    <div class="gallery-overlay"><i class="fas fa-expand text-white fa-2x"></i></div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="gallery-card shadow-sm">
                                    <img src="{{ asset('front/images/Bwindi_2.jpg') }}" alt="Forest">
                                    <div class="gallery-overlay"><i class="fas fa-expand text-white fa-2x"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sticky-top">
                        <div class="card sidebar-card shadow-lg mb-4">
                            <div class="bg-dark p-4 text-center">
                                <span class="text-white-50 small d-block">Starting from</span>
                                <h2 class="text-warning font-weight-bold mb-0">$1,800</h2>
                            </div>
                            <div class="card-body p-4">
                                <form action="#">
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
                                    <button type="button" class="btn btn-warning btn-block btn-lg py-3 shadow" style="border-radius: 15px; font-weight: 800;">RESERVE NOW</button>
                                </form>
                            </div>
                        </div>

                        <div class="bg-white p-4 shadow-sm" style="border-radius: 20px;">
                            <h5 class="font-weight-bold mb-3 small uppercase">Why book with us?</h5>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> 100% Tailor-made</li>
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Verified eco-lodges</li>
                                <li><i class="fas fa-check text-success mr-2"></i> Expert local guides</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>