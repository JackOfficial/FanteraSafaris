@extends('layouts.app')

@section('title', 'Bwindi Gorilla Trek | Fantera Safaris')
@section('meta_description', 'Experience the unforgettable Bwindi Gorilla Trek in Uganda. Luxury safari packages, expert guides, and conservation-focused travel.')
@section('meta_keywords', 'Bwindi Safari, Gorilla Trekking Uganda, Luxury Safari Packages')
@section('og_image', asset('front/images/Bwindi.jpg'))

@section('content')

<!-- Hero Banner -->
<div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/Bwindi.jpg') }}');">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-md-10 hero-content">
                <p class="breadcrumbs mb-3"><span class="mr-2"><a href="/">Home</a></span> <span><a href="/tours">Tours</a></span> <span>Bwindi Gorilla Trek</span></p>
                <h1 class="display-4 font-weight-bold text-white mb-3">Bwindi Gorilla Trek</h1>
                <p class="lead text-light mb-4">Embark on a once-in-a-lifetime adventure to encounter the endangered Mountain Gorillas in their natural habitat.</p>
            </div>
        </div>
    </div>
</div>

<!-- Tour Details Section -->
<section class="ftco-section bg-light">
    <div class="container">

        <div class="row">

            <!-- Main Content -->
            <div class="col-lg-8 ftco-animate">

                <!-- Overview -->
                <div class="tour-overview mb-5">
                    <h2 class="mb-4">Tour Overview</h2>
                    <p>Experience the majestic Bwindi Impenetrable Forest in Uganda, home to over half of the world's remaining Mountain Gorillas. This guided trek combines adventure, luxury, and responsible tourism.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-map-marker-alt text-warning mr-2"></i>Location: Bwindi, Uganda</li>
                        <li><i class="fas fa-calendar-alt text-warning mr-2"></i>Duration: 3 Days / 2 Nights</li>
                        <li><i class="fas fa-user-friends text-warning mr-2"></i>Group Size: 4-10 Travelers</li>
                        <li><i class="fas fa-dollar-sign text-warning mr-2"></i>Price: $1,800 per person</li>
                    </ul>
                </div>

                <!-- Itinerary -->
                <div class="tour-itinerary mb-5">
                    <h2 class="mb-4">Itinerary</h2>
                    <div class="accordion" id="itineraryAccordion">

                        <div class="card">
                            <div class="card-header" id="dayOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link text-left w-100" type="button" data-toggle="collapse" data-target="#collapseDayOne" aria-expanded="true" aria-controls="collapseDayOne">
                                        Day 1: Arrival & Orientation
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseDayOne" class="collapse show" aria-labelledby="dayOne" data-parent="#itineraryAccordion">
                                <div class="card-body">
                                    Arrive at Entebbe Airport, transfer to Bwindi. Evening briefing with your guide and overnight stay in luxury lodge.
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="dayTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link text-left w-100 collapsed" type="button" data-toggle="collapse" data-target="#collapseDayTwo" aria-expanded="false" aria-controls="collapseDayTwo">
                                        Day 2: Gorilla Trekking Adventure
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseDayTwo" class="collapse" aria-labelledby="dayTwo" data-parent="#itineraryAccordion">
                                <div class="card-body">
                                    Early morning trek guided by experts to track gorilla families. Witness these gentle giants in their natural habitat. Return for lunch and optional nature walk in the afternoon.
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="dayThree">
                                <h2 class="mb-0">
                                    <button class="btn btn-link text-left w-100 collapsed" type="button" data-toggle="collapse" data-target="#collapseDayThree" aria-expanded="false" aria-controls="collapseDayThree">
                                        Day 3: Departure
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseDayThree" class="collapse" aria-labelledby="dayThree" data-parent="#itineraryAccordion">
                                <div class="card-body">
                                    After breakfast, check-out and transfer back to Entebbe. Optional city tour or departure.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Gallery -->
                <div class="tour-gallery mb-5">
                    <h2 class="mb-4">Gallery</h2>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <a href="{{ asset('front/images/Bwindi_1.jpg') }}" class="img-popup d-block" style="background-image: url({{ asset('front/images/Bwindi_1.jpg') }}); height: 250px; background-size: cover; border-radius: 8px;"></a>
                        </div>
                        <div class="col-md-6 mb-4">
                            <a href="{{ asset('front/images/Bwindi_2.jpg') }}" class="img-popup d-block" style="background-image: url({{ asset('front/images/Bwindi_2.jpg') }}); height: 250px; background-size: cover; border-radius: 8px;"></a>
                        </div>
                    </div>
                </div>

                <!-- Features -->
                <div class="tour-features mb-5">
                    <h2 class="mb-4">Tour Features</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <i class="fas fa-star text-warning mr-2"></i> Luxury accommodation
                        </div>
                        <div class="col-md-6 mb-3">
                            <i class="fas fa-utensils text-warning mr-2"></i> Gourmet meals included
                        </div>
                        <div class="col-md-6 mb-3">
                            <i class="fas fa-hiking text-warning mr-2"></i> Guided treks in Bwindi Forest
                        </div>
                        <div class="col-md-6 mb-3">
                            <i class="fas fa-leaf text-warning mr-2"></i> Conservation-focused activities
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="tour-reviews mb-5">
                    <h2 class="mb-4">Traveler Reviews</h2>
                    <div class="media mb-4">
                        <img src="{{ asset('front/images/user1.jpg') }}" class="mr-3 rounded-circle" width="60" alt="Traveler">
                        <div class="media-body">
                            <h5 class="mt-0">Sarah M.</h5>
                            <p>"An unforgettable experience! The gorilla trek was magical and the lodge was absolutely perfect."</p>
                        </div>
                    </div>
                    <div class="media mb-4">
                        <img src="{{ asset('front/images/user2.jpg') }}" class="mr-3 rounded-circle" width="60" alt="Traveler">
                        <div class="media-body">
                            <h5 class="mt-0">James K.</h5>
                            <p>"Professional guides, amazing scenery, and a strong focus on conservation. Highly recommended!"</p>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="text-center mb-5">
                    <a href="/booking/bwindi" class="btn btn-warning btn-lg px-5">Book This Safari <i class="fas fa-arrow-right ml-2"></i></a>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 sidebar ftco-animate">

                <div class="sidebar-box bg-white p-4 rounded shadow-sm mb-4">
                    <h3 class="mb-3">Quick Info</h3>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-clock text-warning mr-2"></i> Duration: 3 Days / 2 Nights</li>
                        <li><i class="fas fa-users text-warning mr-2"></i> Group Size: 4-10</li>
                        <li><i class="fas fa-dollar-sign text-warning mr-2"></i> Price: $1,800</li>
                        <li><i class="fas fa-map-marker-alt text-warning mr-2"></i> Location: Bwindi, Uganda</li>
                    </ul>
                    <a href="/booking/bwindi" class="btn btn-warning btn-block mt-3">Book Now</a>
                </div>

                <div class="sidebar-box bg-white p-4 rounded shadow-sm mb-4">
                    <h3 class="mb-3">Related Tours</h3>
                    <ul class="list-unstyled">
                        <li><a href="/tours/serengeti">Serengeti Migration</a></li>
                        <li><a href="/tours/maasai-mara">Maasai Mara Luxury</a></li>
                        <li><a href="/tours/zanzibar">Zanzibar Escapade</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection