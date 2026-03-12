<x-layout 
    :title="'Premier East African Safari Destinations | Fantera Safaris'"
    :metaDescription="'Discover the best destinations in Uganda, Kenya, and Tanzania. From the misty mountains of Bwindi to the vast Serengeti plains.'"
>
   <x-slot name="styles">
        <style>
            :root { --safari-gold: #ffc107; --safari-dark: #1a1a1a; }
            .hero-wrap { position: relative; overflow: hidden; }
            .hero-wrap .overlay { background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%); }
            .sidebar-wrap { border: 1px solid rgba(0,0,0,0.05); background: #ffffff !important; border-radius: 20px !important; position: sticky; top: 100px; }
            .tour-card { border: none !important; border-radius: 24px !important; background: #fff; transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); overflow: hidden; }
            .tour-card:hover { transform: translateY(-12px); box-shadow: 0 30px 60px rgba(0,0,0,0.12) !important; }
            .tour-card .img { position: relative; overflow: hidden; border-radius: 20px; margin: 10px; height: 240px !important; }
            .img-bg { transition: 0.8s ease; background-size: cover; background-position: center; width: 100%; height: 100%; }
            .tour-card:hover .img-bg { transform: scale(1.1); }
            .badge-luxury { position: absolute; top: 15px; left: 15px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(5px); color: var(--safari-dark); padding: 6px 15px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; z-index: 2; }
            input[type='range'] { accent-color: var(--safari-gold); cursor: pointer; }
        </style>
   </x-slot>

    {{-- Dynamic Hero Section --}}
    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/zanzibar_beach.jpg') }}'); height: 60vh;">
        <div class="overlay"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-10">
                    <span class="subheading text-warning font-weight-bold" style="letter-spacing: 3px; font-size: 13px;">CHOOSE YOUR ADVENTURE</span>
                    <h1 class="display-3 font-weight-bold text-white mb-3" style="font-family: 'Playfair Display', serif;">Explore East Africa</h1>
                    <p class="lead text-white-50 mb-4" style="max-width: 600px; margin: 0 auto;">
                        Bespoke itineraries from the misty Gorilla peaks to the golden Serengeti plains.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section bg-light" id="tours-section">
        <div class="container">
            <div class="row">
                {{-- Dynamic Sidebar Filter --}}
                <div class="col-lg-3 sidebar">
                    <div class="sidebar-wrap p-4 shadow-sm">
                        <h3 class="heading mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700;">Refine Search</h3>
                        <form action="{{ route('safaris.index') }}" method="GET">
                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-muted uppercase">Destination</label>
                                <select name="destination" class="form-control border-0 bg-light" style="border-radius: 12px; height: 50px;">
                                    <option value="">All Regions</option>
                                    @foreach($destinations as $dest)
                                        <option value="{{ $dest->slug }}" {{ request('destination') == $dest->slug ? 'selected' : '' }}>
                                            {{ $dest->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-muted uppercase">Max Budget: <span class="text-warning">$<span id="rangeValue">{{ request('price', 5000) }}</span></span></label>
                                <input name="price" value="{{ request('price', 5000) }}" min="500" max="10000" step="100" type="range" class="w-100 mb-2" oninput="document.getElementById('rangeValue').innerText = this.value">
                            </div>

                            <button type="submit" class="btn btn-dark py-3 w-100 shadow-sm" style="border-radius: 15px; font-weight: 700;">Apply Filters</button>
                            @if(request()->anyFilled(['destination', 'price']))
                                <a href="{{ route('safaris.index') }}" class="btn btn-link btn-sm w-100 mt-2 text-muted">Clear Filters</a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        {{-- Dynamic Tour Loop --}}
                        @forelse($packages as $package)
                        <div class="col-md-6 col-lg-4">
                            <div class="tour-card shadow-sm mb-4">
                                <div class="img">
                                    {{-- Tag logic: Show the first category name or a custom 'Featured' tag --}}
                                    <div class="badge-luxury">{{ $package->categories->first()->name ?? 'Safari' }}</div>
                                    <div class="img-bg" style="background-image: url('{{ $package->photo ? asset('storage/' . $package->photo->path) : asset('front/images/safari_default.jpg') }}');"></div>
                                </div>
                                
                                <div class="text p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <p class="text-warning mb-1" style="font-size: 11px; font-weight: 800; letter-spacing: 1px;">
                                                <i class="fas fa-map-marker-alt mr-1"></i> 
                                                {{ strtoupper($package->destinations->pluck('name')->implode(', ')) }}
                                            </p>
                                            <h3 style="font-size: 1.15rem; font-weight: 700; line-height: 1.3;">
                                                <a href="{{ route('safaris.show', $package->slug) }}" class="text-dark">{{ $package->name }}</a>
                                            </h3>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mt-3 pt-3" style="border-top: 1px solid #f0f0f0;">
                                        <div class="price">
                                            <span class="text-muted small">From</span>
                                            <span class="d-block font-weight-bold text-dark" style="font-size: 1.2rem;">
                                                ${{ number_format($package->price) }}
                                            </span>
                                        </div>
                                        <div class="ml-auto">
                                            <a href="{{ route('safaris.show', $package->slug) }}" class="btn btn-warning btn-sm px-3 shadow-sm" style="border-radius: 10px; font-weight: 700;">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <h4 class="text-muted">No safaris found matching your criteria.</h4>
                            <a href="{{ route('safaris.index') }}" class="btn btn-warning mt-3">View All Safaris</a>
                        </div>
                        @endforelse
                    </div>

                    {{-- Dynamic Pagination --}}
                    <div class="row mt-5">
                        <div class="col text-center d-flex justify-content-center">
                            {{ $packages->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>