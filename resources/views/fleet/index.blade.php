<x-layout 
    title="Our Custom Safari Fleet | Fantera Safaris"
    metaDescription="Explore our fleet of custom-fitted 4x4 Land Cruisers designed for safety, comfort, and the ultimate wildlife viewing experience."
>
    <x-slot:styles>
        <style>
            .vehicle-card { border-radius: 30px; overflow: hidden; border: none; background: #fff; transition: 0.4s; }
            .vehicle-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important; }
            .feature-tag { background: #f8f9fa; padding: 6px 15px; border-radius: 50px; font-size: 11px; font-weight: 700; color: #555; text-transform: uppercase; margin: 3px; border: 1px solid #eee; }
            .spec-icon { width: 45px; height: 45px; background: rgba(212, 163, 115, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #d4a373; font-size: 1.2rem; }
            .interior-gallery img { border-radius: 20px; transition: 0.3s; cursor: pointer; }
            .interior-gallery img:hover { opacity: 0.8; }
            .check-list i { color: #28a745; margin-right: 10px; }
        </style>
    </x-slot:styles>

    {{-- Hero Section --}}
    <div class="hero-wrap" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('front/images/fleet_hero.jpg') }}') center center/cover no-repeat; height: 60vh;">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-10 text-white">
                    <span class="text-uppercase font-weight-bold mb-2 d-block" style="letter-spacing: 3px; color: #d4a373;">Built for the Wild</span>
                    <h1 class="display-3 font-weight-bold">The Fantera Fleet</h1>
                    <p class="lead" style="max-width: 600px; margin: auto;">Premium 4x4 Land Cruisers modified specifically for photographers and luxury explorers.</p>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <h2 class="font-weight-bold">Uncompromising Standards</h2>
                    <p class="text-muted">In the bush, your vehicle is your sanctuary. We own 100% of our fleet, allowing us to guarantee specialized modifications that standard rental vehicles simply don't offer.</p>
                </div>
                <div class="col-lg-6 d-flex justify-content-lg-end mt-4 mt-lg-0">
                    <div class="bg-white p-4 shadow-sm rounded d-flex align-items-center">
                        <div class="text-center px-4 border-right">
                            <h3 class="font-weight-bold mb-0">Window</h3>
                            <small class="text-muted">Seat Guaranteed</small>
                        </div>
                        <div class="text-center px-4">
                            <h3 class="font-weight-bold mb-0">Daily</h3>
                            <small class="text-muted">Mechanical Check</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vehicle Grid Loop --}}
            <div class="row">
                @php
                    $vehicles = [
                        [
                            'name' => 'Land Cruiser Extended (Luxury)',
                            'tag' => 'Most Popular',
                            'image' => 'Land Cruiser Extended.jpg',
                            'desc' => 'The ultimate safari machine. Extra length provides superior legroom and space for camera gear.',
                            'capacity' => '6-7 Guests',
                            'roof' => 'Full Pop-up',
                            'power' => 'USB + Inverter',
                            'fridge' => 'Electric Cooler',
                            'features' => ['Photography Bean Bags', 'Long-Range Fuel Tanks', 'HF Radio', 'Garmin GPS']
                        ],
                        [
                            'name' => 'Land Cruiser Short-Base',
                            'tag' => 'Perfect for Couples',
                            'image' => 'Land Cruiser Short-Base.jpg',
                            'desc' => 'Nimble and fast. Ideal for honeymooners or private couples wanting a more intimate experience.',
                            'capacity' => '2-3 Guests',
                            'roof' => 'Pop-up Top',
                            'power' => 'USB Ports',
                            'fridge' => 'Ice Box',
                            'features' => ['Nikon Binoculars', 'First Aid Kit', 'Snorkel System', 'Quiet Engine']
                        ]
                    ];
                @endphp

                @foreach($vehicles as $vehicle)
                <div class="col-lg-12 mb-5">
                    <div class="card vehicle-card shadow-sm border-0">
                        <div class="row no-gutters">
                            <div class="col-md-7">
                                <img src="{{ asset('front/images/' . $vehicle['image']) }}" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 400px;" alt="{{ $vehicle['name'] }}">
                            </div>
                            <div class="col-md-5 p-4 p-lg-5">
                                <span class="badge badge-warning mb-2 px-3 py-2 text-white" style="background:#d4a373;">{{ $vehicle['tag'] }}</span>
                                <h3 class="font-weight-bold">{{ $vehicle['name'] }}</h3>
                                <p class="text-muted">{{ $vehicle['desc'] }}</p>
                                
                                <div class="row my-4">
                                    <div class="col-6 mb-3 d-flex align-items-center">
                                        <div class="spec-icon mr-3"><i class="fas fa-users"></i></div>
                                        <div><small class="d-block text-muted">Capacity</small><strong>{{ $vehicle['capacity'] }}</strong></div>
                                    </div>
                                    <div class="col-6 mb-3 d-flex align-items-center">
                                        <div class="spec-icon mr-3"><i class="fas fa-camera"></i></div>
                                        <div><small class="d-block text-muted">Roof</small><strong>{{ $vehicle['roof'] }}</strong></div>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="spec-icon mr-3"><i class="fas fa-plug"></i></div>
                                        <div><small class="d-block text-muted">Power</small><strong>{{ $vehicle['power'] }}</strong></div>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="spec-icon mr-3"><i class="fas fa-snowflake"></i></div>
                                        <div><small class="d-block text-muted">Cooling</small><strong>{{ $vehicle['fridge'] }}</strong></div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    @foreach($vehicle['features'] as $feature)
                                        <span class="feature-tag">{{ $feature }}</span>
                                    @endforeach
                                </div>

                                <a href="https://wa.me/256751115949" class="btn btn-dark btn-block py-3 font-weight-bold shadow" style="border-radius:15px;">Enquire About Availability</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Interior Experience Section --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <h2 class="font-weight-bold">The Interior Experience</h2>
                    <p class="text-muted">We believe the journey should be as comfortable as the destination. Our interiors are refitted with custom upholstery and ergonomic seating.</p>
                    <ul class="list-unstyled check-list">
                        <li><i class="fas fa-check-circle"></i> High-density foam seats with lumbar support</li>
                        <li><i class="fas fa-check-circle"></i> Individual charging points for every passenger</li>
                        <li><i class="fas fa-check-circle"></i> Non-slip flooring for steady photography</li>
                        <li><i class="fas fa-check-circle"></i> On-board Wi-Fi (available in select regions)</li>
                    </ul>
                </div>
                <div class="col-lg-7">
                    <div class="row interior-gallery">
                        <div class="col-6 mb-3">
                            <img src="{{ asset('front/images/interior_1.jpg') }}" class="img-fluid" alt="Dashboard charging">
                        </div>
                        <div class="col-6 mb-3">
                            <img src="{{ asset('front/images/interior_2.jpg') }}" class="img-fluid" alt="Pop-up roof view">
                        </div>
                        <div class="col-12">
                            <img src="{{ asset('front/images/interior_3.jpg') }}" class="img-fluid" alt="Seating arrangement">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Diagram Section --}}
    

    <section class="py-5 text-center bg-dark text-white">
        <div class="container">
            <h2 class="font-weight-bold mb-3">Have a Large Group?</h2>
            <p class="mb-4">We can deploy multiple coordinated vehicles with radio communication for family reunions and film crews.</p>
            <a href="/contact" class="btn btn-warning px-5 py-3 font-weight-bold text-white" style="background:#d4a373; border-radius:50px;">Contact Operations</a>
        </div>
    </section>
</x-layout>