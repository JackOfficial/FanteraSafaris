@extends('layouts.app')

@section('title', 'Premier East African Safari Destinations | Fantera Safaris')
@section('meta_description', 'Discover the best destinations in Uganda, Kenya, and Tanzania. From the misty mountains of Bwindi to the vast Serengeti plains.')
@section('meta_keywords', 'Uganda Destinations, Serengeti Tours, Masai Mara Safaris, Gorilla Trekking Locations')
@section('og_image', asset('front/images/zanzibar_beach.jpg'))

@section('content')
<style>
    /* 2026 Aesthetic Overrides for Bootstrap 4 */
    :root {
        --safari-green: #008155;
        --safari-dark: #1a1a1a;
        --soft-shadow: 0 20px 40px rgba(0,0,0,0.06);
        --radius: 24px;
    }

    .hero-section {
        height: 70vh;
        min-height: 500px;
        background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.4)), url('{{ asset('front/images/zanzibar_beach.jpg') }}');
        background-size: cover;
        background-position: center;
        border-bottom-left-radius: 60px; /* 2026 Organic edge trend */
        display: flex;
        align-items: center;
        color: white;
    }

    .glass-sidebar {
        background: white;
        border: 1px solid #eee;
        border-radius: var(--radius);
        padding: 30px;
        box-shadow: var(--soft-shadow);
    }

    .dest-card {
        border: none;
        border-radius: var(--radius);
        background: white;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: var(--soft-shadow);
    }

    .dest-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.12);
    }

    .dest-img-container {
        height: 250px;
        position: relative;
    }

    .dest-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .price-tag {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: var(--safari-dark);
        color: white;
        padding: 8px 18px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .badge-country {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(5px);
        color: var(--safari-dark);
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-control-safari {
        border-radius: 12px;
        background: #f8f9fa;
        border: 1px solid #eee;
        padding: 12px 15px;
        height: auto;
    }

    .btn-safari {
        background: var(--safari-green);
        color: white;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
    }
</style>

<header class="hero-section">
    <div class="container text-center">
        <h1 class="display-3 font-weight-bold tracking-tight">Explore East Africa</h1>
        <p class="lead font-weight-light opacity-75">Boutique Safari Experiences & Hidden Gems</p>
    </div>
</header>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="glass-sidebar sticky-top" style="top: 20px;">
                    <h5 class="font-weight-bold mb-4">Find Your Journey</h5>
                    <form>
                        <div class="form-group">
                            <label class="small text-muted font-weight-bold">Search</label>
                            <input type="text" class="form-control form-control-safari" placeholder="e.g. Bwindi">
                        </div>
                        <div class="form-group">
                            <label class="small text-muted font-weight-bold">Region</label>
                            <select class="form-control form-control-safari">
                                <option>All Destinations</option>
                                <option>Uganda</option>
                                <option>Kenya</option>
                                <option>Tanzania</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="small text-muted font-weight-bold">Price Range</label>
                            <input type="range" class="custom-range" min="500" max="10000">
                            <div class="d-flex justify-content-between small font-weight-bold">
                                <span>$500</span>
                                <span>$10,000</span>
                            </div>
                        </div>
                        <button class="btn btn-safari btn-block shadow-sm mt-3">Filter Results</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="dest-card">
                            <div class="dest-img-container">
                                <span class="badge-country">Uganda</span>
                                <img src="{{ asset('front/images/Bwindi.jpg') }}" alt="Bwindi">
                                <div class="price-tag">$1,800</div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-weight-bold mb-2">Bwindi Gorilla Trek</h4>
                                <p class="text-muted small">Deep dive into the misty forests for a life-changing encounter.</p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <span class="small font-weight-bold"><i class="fa fa-map-marker mr-1 text-success"></i> Bwindi NP</span>
                                    <a href="#" class="btn btn-link text-dark font-weight-bold p-0">View Details <i class="fa fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="dest-card">
                            <div class="dest-img-container">
                                <span class="badge-country">Tanzania</span>
                                <img src="{{ asset('front/images/Serengeti.jpg') }}" alt="Serengeti">
                                <div class="price-tag">$1,250</div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-weight-bold mb-2">Serengeti Migration</h4>
                                <p class="text-muted small">The greatest wildlife spectacle on earth across the endless plains.</p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <span class="small font-weight-bold"><i class="fa fa-map-marker mr-1 text-success"></i> Serengeti NP</span>
                                    <a href="#" class="btn btn-link text-dark font-weight-bold p-0">View Details <i class="fa fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled"><a class="page-link border-0 rounded-circle mr-2" href="#">&lt;</a></li>
                        <li class="page-item active"><a class="page-link border-0 rounded-circle mr-2 bg-dark" href="#">1</a></li>
                        <li class="page-item"><a class="page-link border-0 rounded-circle mr-2" href="#">2</a></li>
                        <li class="page-item"><a class="page-link border-0 rounded-circle" href="#">&gt;</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>
@endsection