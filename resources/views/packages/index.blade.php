<x-layout 
    title="Safaris & Tour Packages | Fantera Safaris"
    meta-description="Explore our luxury safari and tour packages across East Africa including Uganda, Kenya, Tanzania, Rwanda, and Zanzibar."
    meta-keywords="Luxury Safari Packages, East Africa Tours, Gorilla Trekking, Serengeti Safaris, Maasai Mara"
    og-image="{{ asset('front/images/safari_banner.jpg') }}"
>

<style>
    /* Professional UI Enhancements */
    .destination {
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
        border: 1px solid #eee;
        height: 100%; /* Ensures uniform card heights in a row */
    }
    .destination:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    .destination .img {
        position: relative;
        height: 250px;
        background-size: cover;
        background-position: center;
    }
    .badge-overlay {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 10;
    }
    .price-tag {
        background: #e83e8c;
        color: white;
        padding: 5px 15px;
        border-radius: 50px 0 0 50px;
        font-weight: 700;
        position: absolute;
        bottom: 20px;
        right: 0;
    }
    .old-price {
        text-decoration: line-through;
        font-size: 0.85em;
        opacity: 0.7;
        margin-right: 5px;
    }
    .tag-category {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #e83e8c;
        font-weight: 700;
    }
    .pagination-wrapper .active span {
        background: #e83e8c !important;
        border-color: #e83e8c !important;
        color: #fff !important;
    }
</style>

<div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/Bwindi.jpg') }}'); height: 400px;">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-md-10 hero-content mt-5">
                <h1 class="display-4 font-weight-bold text-white mb-3">Luxury Safaris & Tours</h1>
                <p class="lead text-light mb-4">Handcrafted East African adventures designed for the discerning traveler.</p>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section ftco-degree-bg">
    <div class="container">
        <div class="row">

            <div class="col-lg-3 sidebar ftco-animate">
                <div class="sidebar-box bg-white p-4 rounded shadow-sm mb-4 border">
                    <h3 class="heading-2 mb-4" style="font-size: 18px; font-weight: 700;">Find Your Adventure</h3>
                    <form action="{{ route('safaris.index') }}" method="GET" class="search-property-1">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">Search</label>
                            <div class="form-field">
                                <div class="icon"><span class="icon-search"></span></div>
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="e.g. Gorilla, Serengeti">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">Price Limit ($)</label>
                            <div class="form-field">
                                <input type="number" name="price_range" class="form-control" value="{{ request('price_range') }}" placeholder="Max Price">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block py-3 mt-4">Filter Results</button>
                        @if(request()->anyFilled(['search', 'price_range']))
                            <a href="{{ route('safaris.index') }}" class="btn btn-link btn-block btn-sm text-muted mt-2">Clear Filters</a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    
                    @forelse($packages as $package)
                        @php
                            // Dynamic Discount Calculation
                            $hasDiscount = $package->discount_rate > 0;
                            $finalPrice = $hasDiscount 
                                ? $package->price - ($package->price * ($package->discount_rate / 100)) 
                                : $package->price;
                        @endphp

                        <div class="col-md-6 mb-5 ftco-animate">
                            <div class="destination">
                                <a href="{{ route('safaris.show', $package->slug) }}" class="img d-flex justify-content-center align-items-center" 
                                   style="background-image: url('{{ $package->image_path ? asset('storage/' . $package->image_path) : asset('front/images/placeholder.jpg') }}');">
                                    
                                    <div class="badge-overlay">
                                        @if($package->views > 100)
                                            <span class="badge badge-danger px-3 py-2 rounded-pill shadow-sm">Popular</span>
                                        @elseif($hasDiscount)
                                            <span class="badge badge-success px-3 py-2 rounded-pill shadow-sm">Special Offer</span>
                                        @endif
                                    </div>

                                    <div class="price-tag">
                                        @if($hasDiscount)
                                            <span class="old-price">${{ number_format($package->price) }}</span>
                                        @endif
                                        ${{ number_format($finalPrice) }}
                                    </div>
                                </a>

                                <div class="text p-4">
                                    <div class="tag-category mb-1">
                                        {{ $package->categories->pluck('name')->implode(' • ') }}
                                    </div>
                                    <h3 class="mb-2" style="font-size: 20px; font-weight: 700;">
                                        <a href="{{ route('safaris.show', $package->slug) }}" class="text-dark">{{ $package->name }}</a>
                                    </h3>
                                    
                                    <div class="d-flex mb-3">
                                        <p class="rate mr-3 mb-0">
                                            @php $rating = $package->reviews_avg_rating ?? 5; @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="icon-star {{ $i <= $rating ? 'text-warning' : 'text-light' }}"></i>
                                            @endfor
                                            <span class="small text-muted ml-1">({{ number_format($rating, 1) }})</span>
                                        </p>
                                        <p class="mb-0 small text-muted">
                                            <i class="icon-calendar mr-1"></i> {{ $package->duration_days }} Days
                                        </p>
                                    </div>

                                    <p class="text-muted small">
                                        {{ Str::limit($package->description, 100) }}
                                    </p>
                                    
                                    <hr>
                                    <div class="bottom-area d-flex align-items-center">
                                        <div class="location">
                                            <i class="icon-map-o mr-1"></i> 
                                            <span class="small">{{ $package->destinations->pluck('name')->first() }}</span>
                                        </div>
                                        <div class="ml-auto">
                                            <a href="{{ route('safaris.show', $package->slug) }}" class="btn btn-outline-dark btn-sm px-3" style="border-radius: 20px;">Explore</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <h4 class="text-muted">No packages found matching your criteria.</h4>
                            <a href="{{ route('safaris.index') }}" class="btn btn-primary mt-3">View All Packages</a>
                        </div>
                    @endforelse

                </div>

                <div class="row mt-5 pagination-wrapper">
                    <div class="col text-center">
                        <div class="block-27">
                            {{ $packages->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layout>