@extends('layouts.app')

@section('content')
<style>
    /* 1. Sophisticated Color Palette & Typography */
    :root { 
        --safari-dark: #1e241e; 
        --safari-gold: #b08d57; 
        --accent-sage: #6b705c;
        --light-tan: #fdfaf5;
    }
    body { background-color: var(--light-tan); color: var(--safari-dark); font-family: 'Playfair Display', serif; }
    .text-muted { font-family: 'Inter', sans-serif; }

    /* 2. Seamless Hero Section */
    .hero-wrap { 
        height: 90vh !important; 
        border-radius: 0 0 80px 0; /* Elegant asymmetrical curve */
    }
    .overlay { background: linear-gradient(to right, rgba(0,0,0,0.6) 30%, transparent 100%); }

    /* 3. The "Floating" Search UX */
    .search-container {
        margin-top: -60px;
        position: relative;
        z-index: 10;
    }
    .modern-search {
        background: #fff;
        padding: 25px 40px;
        border-radius: 100px; /* Pill shape is more modern */
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.05);
    }
    .modern-search input, .modern-search select {
        border: none !important;
        font-size: 0.95rem;
        font-weight: 500;
    }

    /* 4. Service Icons (Minimalist) */
    .services-section { padding-top: 100px; background: transparent; }
    .block-6 .icon { 
        width: 70px; height: 70px; 
        background: #fff; 
        border-radius: 15px; 
        color: var(--safari-gold); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.03);
    }

    /* 5. Destination & Package Cards (Floating Style) */
    .destination { 
        border: none; 
        background: transparent; 
        transition: transform 0.4s ease;
    }
    .destination:hover { transform: translateY(-10px); }
    .destination .img { 
        border-radius: 30px; 
        margin-bottom: 20px;
        overflow: hidden;
    }
    .destination .price {
        font-family: 'Inter', sans-serif;
        background: var(--safari-dark);
        color: white;
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.8rem;
    }

    /* 6. Typography hierarchy */
    h2 { font-size: 3rem; font-weight: 700; color: var(--safari-dark); }
    .subheading { color: var(--safari-gold); letter-spacing: 3px; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; }
</style>

<div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/bg_1.jpg') }}');">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center">
            <div class="col-md-8 ftco-animate">
                <span class="subheading text-white mb-3">Est. 2014</span>
                <h1 class="mb-4 text-white display-2">The Wild Heart <br><em>of Africa</em></h1>
                <p class="mb-5 text-white-50 w-75">Bespoke luxury safaris curated by local experts. Experience Uganda, Kenya, and Tanzania in their purest form.</p>
            </div>
        </div>
    </div>
</div>

<div class="container search-container">
    <div class="row justify-content-center">
        <div class="col-md-10 modern-search">
            <form action="#" class="row align-items-center">
                <div class="col-md-4 border-right">
                    <label class="small font-weight-bold text-muted mb-1 ml-3">LOCATION</label>
                    <select class="form-control"><option>Where to?</option></select>
                </div>
                <div class="col-md-4 border-right">
                    <label class="small font-weight-bold text-muted mb-1 ml-3">EXPERIENCE</label>
                    <input type="text" class="form-control" placeholder="e.g. Gorilla Trekking">
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <button class="btn btn-primary btn-block py-3 rounded-pill ml-md-3">Check Availability</button>
                </div>
            </form>
        </div>
    </div>
</div>

<section class="ftco-section services-section">
    <div class="container">
        <div class="row">
            @php
                $highlights = [
                    ['title' => 'Local Roots', 'icon' => 'flaticon-guarantee', 'desc' => 'Based in Kampala, our guides are family.'],
                    ['title' => 'Tailor-Made', 'icon' => 'flaticon-like', 'desc' => 'Every itinerary is a blank canvas.'],
                    ['title' => 'Eco-First', 'icon' => 'flaticon-detective', 'desc' => 'Luxury that gives back to the land.'],
                    ['title' => 'Concierge', 'icon' => 'flaticon-support', 'desc' => 'Arrival to departure, we are yours.']
                ];
            @endphp
            @foreach($highlights as $h)
            <div class="col-md-3 text-center ftco-animate">
                <div class="block-6">
                    <div class="icon d-flex align-items-center justify-content-center mx-auto mb-4">
                        <span class="{{ $h['icon'] }}"></span>
                    </div>
                    <h3 class="h5 font-weight-bold">{{ $h['title'] }}</h3>
                    <p class="text-muted small">{{ $h['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center heading-section ftco-animate">
                <span class="subheading">The Collection</span>
                <h2 class="mb-4">Iconic Landscapes</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="destination-slider owl-carousel ftco-animate">
                    @php
                        $locs = [
                            ['img' => 'Bwindi.jpg', 'title' => 'Bwindi Forest', 'sub' => 'Uganda'],
                            ['img' => 'Serengeti.jpg', 'title' => 'Serengeti', 'sub' => 'Tanzania'],
                            ['img' => 'Maasai mara.jpg', 'title' => 'Maasai Mara', 'sub' => 'Kenya']
                        ];
                    @endphp
                    @foreach($locs as $l)
                    <div class="item">
                        <div class="destination">
                            <a href="#" class="img d-block" style="background-image: url('{{ asset('front/images/'.$l['img']) }}'); height: 450px;"></a>
                            <div class="text text-center">
                                <span class="subheading" style="font-size: 0.6rem;">{{ $l['sub'] }}</span>
                                <h3 class="h4 font-weight-bold">{{ $l['title'] }}</h3>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection