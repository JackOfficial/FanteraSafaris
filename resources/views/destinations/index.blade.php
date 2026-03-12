<x-layout 
    :title="'Premier East African Safari Destinations | Fantera Safaris'"
    :metaDescription="'Explore our curated list of destinations across Rwanda, Uganda, Kenya, and Tanzania. Find the perfect backdrop for your next adventure.'"
>
    <x-slot name="styles">
        <style>
            :root { --safari-gold: #ffc107; --safari-dark: #1a1a1a; }
            .hero-wrap { position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; }
            .hero-wrap .overlay { background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.6) 100%); z-index: 1; }
            
            /* Enhanced Destination Card */
            .dest-card { border: none !important; border-radius: 30px !important; background: #fff; transition: all 0.5s ease; overflow: hidden; height: 100%; border: 1px solid #f0f0f0 !important; }
            .dest-card:hover { transform: translateY(-15px); box-shadow: 0 40px 80px rgba(0,0,0,0.1) !important; border-color: transparent !important; }
            
            .dest-img-container { position: relative; height: 320px; overflow: hidden; margin: 12px; border-radius: 22px; }
            .dest-img { transition: 1.2s cubic-bezier(0.165, 0.84, 0.44, 1); background-size: cover; background-position: center; width: 100%; height: 100%; }
            .dest-card:hover .dest-img { transform: scale(1.15); }
            
            /* Badge Styling */
            .tour-count-badge { 
                position: absolute; top: 15px; left: 15px; 
                background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); 
                color: var(--safari-dark); padding: 6px 18px; border-radius: 50px; 
                font-size: 11px; font-weight: 800; z-index: 2; letter-spacing: 1px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            }

            /* Fleet Promotion Section */
.destination-fleet-cta {
    background: linear-gradient(45deg, #1a1a1a, #2d2d2d);
    border-radius: 30px;
    padding: 60px;
    position: relative;
    overflow: hidden;
    color: white;
}
.fleet-floating-img {
    position: absolute;
    right: -50px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.3;
    max-width: 500px;
}
@media (max-width: 991px) {
    .fleet-floating-img { display: none; }
    .destination-fleet-cta { padding: 40px 20px; text-align: center; }
}

            /* Country Pill Filters */
            .filter-pill { 
                padding: 10px 25px; border-radius: 50px; background: #fff; 
                color: var(--safari-dark); border: 1px solid #e0e0e0; 
                transition: 0.3s; font-weight: 600; font-size: 14px; margin: 5px; display: inline-block;
            }
            .filter-pill:hover, .filter-pill.active { background: var(--safari-dark); color: #fff; border-color: var(--safari-dark); text-decoration: none; }
        </style>
    </x-slot>

    {{-- Luxury Hero Section --}}
    <div class="hero-wrap" style="background-image: url('{{ asset('front/images/bg_1.jpg') }}'); height: 55vh; background-size: cover; background-position: center;">
        <div class="overlay" style="position: absolute; inset: 0;"></div>
        <div class="container" style="z-index: 2;">
            <div class="row justify-content-center text-center">
                <div class="col-md-10">
                    <span class="subheading text-warning font-weight-bold" style="letter-spacing: 5px; font-size: 13px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">UNVEIL THE WILD</span>
                    <h1 class="display-3 font-weight-bold text-white mb-3" style="font-family: 'Playfair Display', serif;">Our Destinations</h1>
                    <p class="text-white-50 lead mx-auto" style="max-width: 600px;">Curated landscapes where nature remains untouched and adventures are born.</p>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section bg-light pt-5">
        <div class="container">
            {{-- Modern Country Filter Bar --}}
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <div class="filter-container">
                        <a href="{{ route('destinations.index') }}" class="filter-pill {{ !request('country') ? 'active' : '' }}">All Regions</a>
                        @foreach($countries as $country)
                            <a href="{{ route('destinations.index', ['country' => $country]) }}" 
                               class="filter-pill {{ request('country') == $country ? 'active' : '' }}">
                               {{ $country }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row">
                @forelse($destinations as $dest)
                <div class="col-md-6 col-lg-4 mb-5">
                    <a href="{{ route('destinations.show', $dest->slug) }}" class="text-decoration-none">
                        <div class="dest-card shadow-sm">
                            <div class="dest-img-container">
                                <div class="tour-count-badge">
                                    <i class="fas fa-binoculars mr-1 text-warning"></i> 
                                    {{ $dest->packages_count }} TOURS
                                </div>
                                <div class="dest-img" style="background-image: url('{{ $dest->image ? asset('storage/'.$dest->image) : asset('front/images/dest_default.jpg') }}');"></div>
                            </div>
                            
                            <div class="text p-4 pt-2 text-center">
                                <span class="country-tag" style="font-size: 11px;">{{ $dest->country }}</span>
                                <h3 class="mt-2 mb-3" style="font-family: 'Playfair Display', serif; font-weight: 800; color: var(--safari-dark); letter-spacing: -0.5px;">
                                    {{ $dest->name }}
                                </h3>
                                <p class="text-muted small px-3 line-height-lg" style="line-height: 1.6;">
                                    {{ Str::limit(strip_tags($dest->description), 95) }}
                                </p>
                                <hr style="width: 40px; border-top: 2px solid var(--safari-gold); margin: 20px auto;">
                                <div class="mt-2">
                                    <span class="text-dark font-weight-bold" style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">
                                        Explore Region <i class="fas fa-chevron-right ml-1 small"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <img src="{{ asset('front/images/no-results.png') }}" style="width: 150px; opacity: 0.5;" alt="No results">
                    <h4 class="text-muted mt-4">No destinations found in this region.</h4>
                    <a href="{{ route('destinations.index') }}" class="btn btn-dark mt-3 px-4" style="border-radius: 50px;">View All</a>
                </div>
                @endforelse
            </div>

            {{-- Fleet CTA: The Fantera Difference --}}
<div class="destination-fleet-cta mt-5 mb-4 shadow-lg">
    <div class="row align-items-center">
        <div class="col-lg-7" style="z-index: 2;">
            <span class="text-warning font-weight-bold small text-uppercase" style="letter-spacing: 2px;">The Fantera Difference</span>
            <h2 class="text-white font-weight-bold mt-2 mb-3" style="font-family: 'Playfair Display', serif;">Traverse {{ $destination->name }} in Comfort</h2>
            <p class="text-white-50 mb-4">
                The roads in East Africa can be challenging. That's why we use our own custom-fitted 4x4 Land Cruisers—maintained daily to ensure your safety and provide the perfect platform for photography.
            </p>
            <div class="d-flex flex-wrap mb-4">
                <div class="mr-4 mb-2"><i class="fas fa-wifi text-warning mr-2"></i> <small>On-board Wi-Fi</small></div>
                <div class="mr-4 mb-2"><i class="fas fa-battery-full text-warning mr-2"></i> <small>Charging Ports</small></div>
                <div class="mb-2"><i class="fas fa-ice-cream text-warning mr-2"></i> <small>Cooler Box</small></div>
            </div>
            <a href="{{ route('fleet.index') }}" class="btn btn-warning px-4 py-3 font-weight-bold shadow-sm" style="border-radius: 15px;">
                EXPLORE OUR FLEET <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
    {{-- Floating Background Image --}}
    <img src="{{ asset('front/images/land_cruiser_extended.png') }}" class="fleet-floating-img" alt="Safari Land Cruiser">
</div>

            {{-- Custom Pagination Styling --}}
            <div class="row mt-5">
                <div class="col text-center d-flex justify-content-center">
                    {{ $destinations->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </section>
</x-layout>