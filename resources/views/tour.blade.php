@extends('layouts.app')

@section('title', 'Premier East African Safari Destinations | Fantera Safaris')
@section('meta_description', 'Discover the best destinations in Uganda, Kenya, and Tanzania. From the misty mountains of Bwindi to the vast Serengeti plains.')
@section('meta_keywords', 'Uganda Destinations, Serengeti Tours, Masai Mara Safaris, Gorilla Trekking Locations')
@section('og_image', asset('front/images/zanzibar_beach.jpg'))
@push('styles')
	<style>
    /* 2026 Modern Safari UI Tweaks */
    :root { --safari-gold: #c5a059; --safari-green: #2d5a27; }
    
    .hero-wrap { 
        height: 60vh; 
        min-height: 450px; 
        border-bottom-left-radius: 50px; 
        border-bottom-right-radius: 50px; 
        overflow: hidden; 
    }

    .sidebar-wrap {
        border-radius: 20px;
        border: 1px solid #eee;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        position: sticky;
        top: 20px;
    }

    .destination {
        border-radius: 20px;
        overflow: hidden;
        transition: all .3s ease;
        border: 1px solid #f0f0f0;
        background: #fff;
    }

    .destination:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .destination .img { height: 250px; position: relative; }
    
    .price-tag {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(5px);
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 700;
        color: var(--safari-green);
    }

    .btn-filter {
        background: var(--safari-green);
        color: white;
        border-radius: 12px;
        font-weight: 600;
        transition: 0.3s;
    }
    
    .btn-filter:hover { background: #1e3d1a; color: #fff; }

    .custom-range::-webkit-slider-thumb { background: var(--safari-green); }
</style>
@endpush
@section('content')


<div class="hero-wrap d-flex align-items-center" style="background-image: url('{{ asset('front/images/zanzibar_beach.jpg') }}'); background-size: cover; background-position: center;">
    <div class="overlay" style="background: rgba(0,0,0,0.3);"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 text-center text-white">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent justify-content-center p-0 mb-2">
                        <li class="breadcrumb-item"><a href="/" class="text-white-50">Home</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Tours</li>
                    </ol>
                </nav>
                <h1 class="display-4 font-weight-bold">Explore East Africa</h1>
                <p class="lead">Handcrafted safari experiences across the Savannah</p>
            </div>
        </div>
    </div>
</div>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <aside class="col-lg-3">
                <div class="sidebar-wrap bg-white p-4 mb-4">
                    <h5 class="font-weight-bold mb-4"><i class="icon-filter_list mr-2"></i>Filter Your Safari</h5>
                    <form action="#">
                        <div class="form-group">
                            <label class="small font-weight-bold">Search</label>
                            <input type="text" class="form-control border-0 bg-light rounded-pill px-4" placeholder="Park or Activity">
                        </div>
                        
                        <div class="form-group">
                            <label class="small font-weight-bold">Region</label>
                            <select class="form-control border-0 bg-light rounded-pill px-4">
                                <option value="">All Regions</option>
                                <option>Uganda (Gorillas)</option>
                                <option>Kenya (Maasai Mara)</option>
                                <option>Tanzania (Serengeti)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Budget (Max)</label>
                            <input type="range" class="custom-range" min="500" max="10000" id="priceRange">
                            <div class="d-flex justify-content-between mt-1">
                                <span class="badge badge-light text-muted">$500</span>
                                <span class="badge badge-success">$10,000</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-filter btn-block py-3 mt-4 shadow-sm">
                            Show Results
                        </button>
                    </form>
                </div>
            </aside>

            <div class="col-lg-9">
                <div class="row">
                    @php
                    // Placeholder for your Laravel 12 collection
                    $tours = [
                        ['name' => 'Bwindi Gorilla Trek', 'price' => '1,800', 'loc' => 'Uganda', 'img' => 'Bwindi.jpg', 'desc' => 'Encounter the mountain gorillas in the wild.'],
                        ['name' => 'Serengeti Migration', 'price' => '1,250', 'loc' => 'Tanzania', 'img' => 'Serengeti.jpg', 'desc' => 'Witness the Great Migration across the plains.'],
                        ['name' => 'Maasai Mara Luxury', 'price' => '980', 'loc' => 'Kenya', 'img' => 'Maasai mara.jpg', 'desc' => 'The ultimate Big Five safari experience.'],
                        // Add more as needed
                    ];
                    @endphp

                    @foreach($tours as $tour)
                    <div class="col-md-6 mb-4">
                        <div class="destination shadow-sm h-100 d-flex flex-column">
                            <a href="#" class="img" style="background-image: url({{ asset('front/images/'.$tour['img']) }}); background-size: cover;">
                                <div class="price-tag">${{ $tour['price'] }}</div>
                            </a>
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small"><i class="icon-map-pin mr-1"></i> {{ $tour['loc'] }}</span>
                                    <div class="text-warning small">
                                        <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i>
                                    </div>
                                </div>
                                <h4 class="font-weight-bold"><a href="#" class="text-dark">{{ $tour['name'] }}</a></h4>
                                <p class="text-muted small flex-grow-1">{{ $tour['desc'] }}</p>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold text-success">Available</span>
                                    <a href="#" class="btn btn-sm btn-outline-dark rounded-pill px-4">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled"><a class="page-link border-0 rounded-circle mr-2" href="#">&laquo;</a></li>
                        <li class="page-item active"><a class="page-link border-0 rounded-circle mr-2 bg-success text-white" href="#">1</a></li>
                        <li class="page-item"><a class="page-link border-0 rounded-circle mr-2" href="#">2</a></li>
                        <li class="page-item"><a class="page-link border-0 rounded-circle" href="#">&raquo;</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>
@endsection