<x-layout 
    :title="'Premier East African Safari Destinations | Fantera Safaris'"
    :metaDescription="'Discover the best destinations in Uganda, Kenya, and Tanzania. From the misty mountains of Bwindi to the vast Serengeti plains.'"
>
   <x-slot name="styles">
        <style>
            /* 1. Global Elevation Styles */
            :root { --safari-gold: #ffc107; --safari-dark: #1a1a1a; }
            
            .hero-wrap { position: relative; overflow: hidden; }
            .hero-wrap .overlay { background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%); }

            /* 2. Modern Sidebar (Glassmorphism) */
            .sidebar-wrap {
                border: 1px solid rgba(0,0,0,0.05);
                background: #ffffff !important;
                border-radius: 20px !important;
                position: sticky;
                top: 100px;
            }

            /* 3. The Interactive Tour Card */
            .tour-card {
                border: none !important;
                border-radius: 24px !important;
                background: #fff;
                transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                overflow: hidden;
            }
            .tour-card:hover {
                transform: translateY(-12px);
                box-shadow: 0 30px 60px rgba(0,0,0,0.12) !important;
            }
            .tour-card .img {
                position: relative;
                overflow: hidden;
                border-radius: 20px;
                margin: 10px;
                height: 240px !important;
            }
            .tour-card:hover .img-bg { transform: scale(1.1); transition: 0.8s ease; }
            .img-bg { transition: 0.8s ease; background-size: cover; background-position: center; width: 100%; height: 100%; }

            /* 4. Luxury Badges */
            .badge-luxury {
                position: absolute; top: 15px; left: 15px;
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(5px);
                color: var(--safari-dark);
                padding: 6px 15px;
                border-radius: 50px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
                z-index: 2;
            }

            /* 5. Range Slider Styling */
            input[type='range'] { accent-color: var(--safari-gold); cursor: pointer; }
        </style>
   </x-slot>

    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/zanzibar_beach.jpg') }}'); height: 60vh;">
        <div class="overlay"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-10 ftco-animate">
                    <span class="subheading text-warning font-weight-bold tracking-widest" style="letter-spacing: 3px; font-size: 13px;">CHOOSE YOUR ADVENTURE</span>
                    <h1 class="display-3 font-weight-bold text-white mb-3" style="font-family: 'Playfair Display', serif;">Explore East Africa</h1>
                    <p class="lead text-white-50 mb-4" style="max-width: 600px; margin: 0 auto; line-height: 1.8;">
                        Bespoke itineraries from the misty Gorilla peaks to the golden Serengeti plains.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section bg-light" id="tours-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 sidebar ftco-animate">
                    <div class="sidebar-wrap p-4 shadow-sm">
                        <h3 class="heading mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700;">Refine Search</h3>
                        <form action="#">
                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-muted uppercase">Destination</label>
                                <select class="form-control border-0 bg-light" style="border-radius: 12px; height: 50px;">
                                    <option value="">All Regions</option>
                                    <option value="uganda">Uganda (Gorillas)</option>
                                    <option value="kenya">Kenya (Maasai Mara)</option>
                                    <option value="tanzania">Tanzania (Serengeti)</option>
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-muted uppercase">Budget Range</label>
                                <input value="5000" min="500" max="10000" step="100" type="range" class="w-100 mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="small font-weight-bold">$500</span>
                                    <span class="small font-weight-bold text-warning">$10,000</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark py-3 w-100 shadow-sm" style="border-radius: 15px; font-weight: 700;">Apply Filters</button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        @php
                        $tours = [
                            ['title' => 'Bwindi Gorilla Trek', 'location' => 'Bwindi, Uganda', 'price' => '1,800', 'img' => 'Bwindi.jpg', 'tag' => 'Bucket List'],
                            ['title' => 'Serengeti Migration', 'location' => 'Serengeti, Tanzania', 'price' => '1,250', 'img' => 'Serengeti.jpg', 'tag' => 'Seasonal'],
                            ['title' => 'Maasai Mara Luxury', 'location' => 'Mara, Kenya', 'price' => '980', 'img' => 'Maasai mara.jpg', 'tag' => 'Best Value'],
                            ['title' => 'Rwanda Primates', 'location' => 'Volcanoes NP, Rwanda', 'price' => '2,100', 'img' => 'gorilla_trek.jpg', 'tag' => 'Luxury'],
                            ['title' => 'Big Five Special', 'location' => 'Murchison Falls, UG', 'price' => '1,400', 'img' => 'big_five.jpg', 'tag' => 'Popular'],
                            ['title' => 'Zanzibar Escapade', 'location' => 'Nungwi, Zanzibar', 'price' => '450', 'img' => 'zanzibar_beach.jpg', 'tag' => 'Relaxation']
                        ];
                        @endphp

                        @foreach($tours as $tour)
                        <div class="col-md-6 col-lg-4 ftco-animate">
                            <div class="tour-card shadow-sm mb-4">
                                <div class="img">
                                    <div class="badge-luxury">{{ $tour['tag'] }}</div>
                                    <div class="img-bg" style="background-image: url('{{ asset('front/images/' . $tour['img']) }}');"></div>
                                </div>
                                
                                <div class="text p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <p class="text-warning mb-1" style="font-size: 11px; font-weight: 800; letter-spacing: 1px;">
                                                <i class="fas fa-map-marker-alt mr-1"></i> {{ strtoupper($tour['location']) }}
                                            </p>
                                            <h3 style="font-size: 1.15rem; font-weight: 700; line-height: 1.3;">
                                                <a href="/tour-details" class="text-dark">{{ $tour['title'] }}</a>
                                            </h3>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mt-3 pt-3" style="border-top: 1px solid #f0f0f0;">
                                        <div class="price">
                                            <span class="text-muted small">From</span>
                                            <span class="d-block font-weight-bold text-dark" style="font-size: 1.2rem;">${{ $tour['price'] }}</span>
                                        </div>
                                        <div class="ml-auto">
                                            <a href="/tour-details" class="btn btn-warning btn-sm px-3 shadow-sm" style="border-radius: 10px; font-weight: 700;">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="row mt-5">
                        <div class="col text-center">
                            <div class="block-27">
                                <ul class="d-flex justify-content-center list-unstyled">
                                    <li><a href="#" class="mx-1 shadow-sm rounded-circle">1</a></li>
                                    <li class="active"><a href="#" class="mx-1 shadow-sm rounded-circle">2</a></li>
                                    <li><a href="#" class="mx-1 shadow-sm rounded-circle">3</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>