<x-layout 
    title="Our Custom Safari Fleet | Fantera Safaris"
    metaDescription="Explore our fleet of custom-fitted 4x4 Land Cruisers designed for safety, comfort, and the ultimate wildlife viewing experience in East Africa."
>
    <x-slot:styles>
        <style>
            .vehicle-card { border-radius: 30px; overflow: hidden; border: none; background: #fff; transition: 0.4s; }
            .vehicle-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important; }
            .feature-tag { background: #f8f9fa; padding: 6px 15px; border-radius: 50px; font-size: 12px; font-weight: 600; color: #555; display: inline-block; margin: 3px; }
            .spec-icon { width: 40px; height: 40px; background: rgba(212, 163, 115, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #d4a373; }
        </style>
    </x-slot:styles>

    {{-- Hero --}}
    <div class="hero-wrap" style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset('front/images/fleet_hero.jpg') }}') center center/cover no-repeat; height: 50vh;">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-8 text-white">
                    <h1 class="display-4 font-weight-bold">Engineered for Adventure</h1>
                    <p class="lead">Our custom-fitted 4x4 fleet is the backbone of every Fantera journey.</p>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6">
                    <h2 class="font-weight-bold">Why Our Fleet Matters</h2>
                    <p class="text-muted">A safari is only as good as the vehicle that takes you there. We don't outsource our transport; we own and maintain every vehicle to ensure zero breakdowns and maximum comfort.</p>
                </div>
                <div class="col-lg-6 d-flex justify-content-lg-end">
                    <div class="d-flex border-left pl-4 mt-3 mt-lg-0">
                        <div class="mr-4">
                            <h3 class="font-weight-bold mb-0">100%</h3>
                            <small class="text-uppercase text-muted">Private 4x4s</small>
                        </div>
                        <div>
                            <h3 class="font-weight-bold mb-0">24/7</h3>
                            <small class="text-uppercase text-muted">Radio Support</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vehicle Grid --}}
            <div class="row">
                {{-- Example: Land Cruiser Extended --}}
                <div class="col-lg-12 mb-5">
                    <div class="card vehicle-card shadow-sm">
                        <div class="row no-gutters">
                            <div class="col-md-7">
                                <img src="{{ asset('front/images/land_cruiser_extended.jpg') }}" class="img-fluid h-100 w-100" style="object-fit: cover;" alt="Extended Safari Land Cruiser">
                            </div>
                            <div class="col-md-5 p-4 p-lg-5">
                                <span class="badge badge-warning mb-2 px-3 py-2 text-white">Most Popular</span>
                                <h3 class="font-weight-bold">Custom Land Cruiser (Extended)</h3>
                                <p class="text-muted">The gold standard for East African Safaris. Modified for maximum visibility and long-range comfort.</p>
                                
                                <div class="row my-4">
                                    <div class="col-6 mb-3 d-flex align-items-center">
                                        <div class="spec-icon mr-3"><i class="fas fa-users"></i></div>
                                        <div><small class="d-block text-muted">Capacity</small><strong>6-7 Guests</strong></div>
                                    </div>
                                    <div class="col-6 mb-3 d-flex align-items-center">
                                        <div class="spec-icon mr-3"><i class="fas fa-camera"></i></div>
                                        <div><small class="d-block text-muted">Roof</small><strong>Pop-up Top</strong></div>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="spec-icon mr-3"><i class="fas fa-bolt"></i></div>
                                        <div><small class="d-block text-muted">Power</small><strong>USB Ports</strong></div>
                                    </div>
                                    <div class="col-6 d-flex align-items-center">
                                        <div class="spec-icon mr-3"><i class="fas fa-snowflake"></i></div>
                                        <div><small class="d-block text-muted">Extras</small><strong>Cooler Box</strong></div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <span class="feature-tag">Winched</span>
                                    <span class="feature-tag">First Aid Kit</span>
                                    <span class="feature-tag">High-Lift Jack</span>
                                    <span class="feature-tag">Unlimited Miles</span>
                                </div>

                                <a href="/contact" class="btn btn-dark btn-block py-3 font-weight-bold" style="border-radius:15px;">Book This Vehicle</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Technical Diagram / Visual Aid --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-md-8">
                    <h2 class="font-weight-bold">Optimized for Photography</h2>
                    <p class="text-muted">Our vehicles are specifically modified to ensure every guest gets a window seat and a clear view of the wildlife.</p>
                </div>
            </div>
            
        </div>
    </section>
</x-layout>