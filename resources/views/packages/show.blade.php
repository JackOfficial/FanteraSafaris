<x-layout 
    title="{{ $package->name }} | Fantera Safaris"
    meta-description="{{ Str::limit($package->summary, 160) }}"
    meta-keywords="{{ $package->categories->pluck('name')->implode(', ') }}, Safari East Africa"
>

<style>
    /* Styling for Luxury Safari Details */
    .safari-meta { font-size: 13px; text-transform: uppercase; letter-spacing: 2px; color: #e83e8c; font-weight: 700; }
    
    .itinerary-day { 
        border-left: 2px solid #e1e1e1; 
        padding-left: 30px; 
        position: relative; 
        padding-bottom: 20px;
    }
    .itinerary-day::before {
        content: '';
        position: absolute;
        left: -9px;
        top: 0;
        width: 16px;
        height: 16px;
        background: #e83e8c;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e83e8c;
    }
    
    .booking-card { 
        position: sticky; 
        top: 110px; 
        border-radius: 15px; 
        border: none; 
        box-shadow: 0 15px 45px rgba(0,0,0,0.08); 
    }
    
    .gallery-img { 
        border-radius: 12px; 
        height: 180px; 
        width: 100%;
        object-fit: cover; 
        cursor: pointer; 
        transition: transform 0.4s ease; 
    }
    .gallery-img:hover { transform: scale(1.05); }

    .service-icon-box {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        flex: 1;
        margin: 5px;
    }
</style>

{{-- 1. Hero Header --}}
<div class="hero-wrap js-fullheight" style="background-image: url('{{ $package->photo ? asset('storage/' . $package->photo->path) : asset('front/images/safari_default.jpg') }}'); height: 550px;">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center">
            <div class="col-md-9 text-center mt-5">
                <p class="safari-meta mb-2">Explore the heart of East Africa</p>
                <h1 class="mb-3 bread text-white" style="font-weight: 800; font-size: 55px; text-shadow: 2px 2px 10px rgba(0,0,0,0.3);">{{ $package->name }}</h1>
                <div class="d-flex justify-content-center align-items-center text-white">
                    <span class="mr-3"><i class="icon-calendar mr-2"></i> {{ $package->duration_days }} Days</span>
                    <span><i class="icon-map-o mr-2"></i> {{ $package->destinations->pluck('name')->implode(', ') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="ftco-section">
    <div class="container">
        <div class="row">
            {{-- 2. Main Content --}}
            <div class="col-lg-8">
                {{-- Quick Info Bar --}}
                <div class="d-flex flex-wrap mb-5">
                    <div class="service-icon-box shadow-sm">
                        <i class="icon-clock-o text-primary d-block mb-2" style="font-size: 24px;"></i>
                        <span class="small font-weight-bold text-dark">Duration</span>
                        <p class="mb-0 text-muted small">{{ $package->duration_days }} Days</p>
                    </div>
                    <div class="service-icon-box shadow-sm">
                        <i class="icon-signal text-primary d-block mb-2" style="font-size: 24px;"></i>
                        <span class="small font-weight-bold text-dark">Difficulty</span>
                        <p class="mb-0 text-muted small">{{ ucfirst($package->difficulty ?? 'Moderate') }}</p>
                    </div>
                    <div class="service-icon-box shadow-sm">
                        <i class="icon-group text-primary d-block mb-2" style="font-size: 24px;"></i>
                        <span class="small font-weight-bold text-dark">Group Size</span>
                        <p class="mb-0 text-muted small">Max 6-8 People</p>
                    </div>
                    <div class="service-icon-box shadow-sm">
                        <i class="icon-star text-warning d-block mb-2" style="font-size: 24px;"></i>
                        <span class="small font-weight-bold text-dark">Reviews</span>
                        <p class="mb-0 text-muted small">{{ number_format($package->reviews_avg_rating ?? 5, 1) }}/5.0</p>
                    </div>
                </div>

                {{-- Overview --}}
                <div class="hotel-single mt-4">
                    <h4 class="mb-4 font-weight-bold border-bottom pb-2">Tour Overview</h4>
                    <p class="lead text-primary font-italic mb-4" style="font-size: 18px;">{{ $package->summary }}</p>
                    <div class="text-secondary leading-relaxed" style="font-size: 16px; line-height: 1.8;">
                        {!! $package->description !!}
                    </div>
                </div>

                {{-- Itinerary Section --}}
                
                <div class="mt-5 pt-4">
                    <h4 class="mb-5 font-weight-bold border-bottom pb-2">Your Detailed Itinerary</h4>
                    <div class="itinerary-container">
                        @foreach($package->itineraries as $item)
                            <div class="itinerary-day">
                                <h5 class="text-dark font-weight-bold mb-3">Day {{ $item->day_number }}: {{ $item->title }}</h5>
                                <p class="text-muted" style="line-height: 1.7;">{{ $item->activities }}</p>
                                
                                <div class="bg-light p-3 rounded d-flex mt-3">
                                    @if($item->accommodation)
                                        <div class="small mr-4"><i class="icon-bed text-primary mr-2"></i> <strong>Stay:</strong> {{ $item->accommodation }}</div>
                                    @endif
                                    @if($item->meals)
                                        <div class="small"><i class="icon-utensils text-primary mr-2"></i> <strong>Meals:</strong> {{ $item->meals }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Gallery --}}
                <div class="mt-5 pt-4">
                    <h4 class="mb-4 font-weight-bold border-bottom pb-2">Gallery</h4>
                    <div class="row">
                        @foreach($package->photos as $p)
                            <div class="col-md-4 col-6 mb-4">
                                <a href="{{ asset('storage/' . $p->path) }}" class="image-popup">
                                    <img src="{{ asset('storage/' . $p->path) }}" class="gallery-img shadow-sm">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="my-5">
                @include('partials._reviews')

            </div>

            {{-- 3. Sidebar Booking Form --}}
            <div class="col-lg-4">
                <div class="card booking-card p-4 bg-white mt-lg-0 mt-5">
                    <div class="price-header text-center mb-4">
                        @if($package->discount_rate > 0)
                            <span class="badge badge-success mb-2">Save {{ number_format($package->discount_rate) }}% Today</span>
                            <div class="text-muted small text-decoration-line-through">${{ number_format($package->price) }}</div>
                        @endif
                        <h2 class="text-primary font-weight-bold mb-0">
                            ${{ number_format($package->price - ($package->price * ($package->discount_rate / 100))) }}
                        </h2>
                        <span class="text-muted small">Per Person (Adult)</span>
                    </div>

                    <form action="/contact" method="POST" class="booking-form">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">
                        
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted uppercase">Full Name</label>
                            <input type="text" name="name" class="form-control bg-light border-0" placeholder="John Doe" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted uppercase">Email Address</label>
                            <input type="email" name="email" class="form-control bg-light border-0" placeholder="example@email.com" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted uppercase">Preferred Date</label>
                            <input type="date" name="travel_date" class="form-control bg-light border-0" required>
                        </div>
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted uppercase">Guests</label>
                            <select name="guests" class="form-control bg-light border-0">
                                @for($i=1; $i<=10; $i++) <option value="{{$i}}">{{$i}} {{ Str::plural('Guest', $i) }}</option> @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block py-3 font-weight-bold shadow-sm">Check Availability</button>
                    </form>
                    
                    <div class="text-center mt-4 border-top pt-3">
                        <p class="small text-muted mb-0"><i class="icon-check-circle text-success mr-1"></i> No immediate payment required</p>
                        <p class="small text-muted"><i class="icon-check-circle text-success mr-1"></i> Expert local advice included</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

</x-layout>