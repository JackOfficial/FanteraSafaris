@extends('layouts.app')

@section('title', 'Safaris & Tour Packages | Fantera Safaris')
@section('meta_description', 'Explore our luxury safari and tour packages across East Africa including Uganda, Kenya, Tanzania, Rwanda, and Zanzibar.')
@section('meta_keywords', 'Luxury Safari Packages, East Africa Tours, Gorilla Trekking, Serengeti Safaris, Maasai Mara')
@section('og_image', asset('front/images/safari_banner.jpg'))

@section('content')

<!-- Hero Banner -->
<div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/Bwindi.jpg') }}');">
    <div class="overlay"></div>
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-md-10 hero-content">
                <p class="breadcrumbs mb-3"><span class="mr-2"><a href="/">Home</a></span> <span>Safaris & Tours</span></p>
                <h1 class="display-4 font-weight-bold text-white mb-3">Luxury Safaris & Tours</h1>
                <p class="lead text-light mb-4">Discover unforgettable adventures in East Africa – from wildlife encounters to pristine beaches.</p>
            </div>
        </div>
    </div>
</div>

<!-- Safari Packages Section -->
<section class="ftco-section ftco-degree-bg">
    <div class="container">
        <div class="row">

            <!-- Sidebar / Filters -->
            <div class="col-lg-3 sidebar ftco-animate">
                <div class="sidebar-box bg-white p-4 rounded shadow-sm mb-4">
                    <h3 class="mb-3">Filter Your Safari</h3>
                    <form action="#" method="GET">
                        <div class="form-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by Park or Activity">
                        </div>
                        <div class="form-group">
                            <select name="region" class="form-control">
                                <option value="">Select Region</option>
                                <option value="uganda">Uganda (Gorillas)</option>
                                <option value="kenya">Kenya (Maasai Mara)</option>
                                <option value="tanzania">Tanzania (Serengeti)</option>
                                <option value="rwanda">Rwanda (Volcanoes NP)</option>
                                <option value="zanzibar">Zanzibar (Beaches)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="date" name="travel_date" class="form-control" placeholder="Travel Date">
                        </div>
                        <div class="form-group">
                            <small class="d-block mb-2 text-muted">Price Range ($)</small>
                            <input type="range" name="price_range" min="0" max="10000" step="100" class="w-100">
                            <div class="d-flex justify-content-between mt-2">
                                <span class="badge badge-primary">$0</span>
                                <span class="badge badge-primary">$10,000</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-warning btn-block">Filter Results</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Packages Listing -->
            <div class="col-lg-9">
                <div class="row">

                    <!-- Package Card Example -->
                    <div class="col-md-6 mb-4 ftco-animate">
                        <div class="destination">
                            <a href="/tours/bwindi" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url('{{ asset('front/images/Bwindi.jpg') }}');">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-3">
                                <div class="d-flex">
                                    <div class="one">
                                        <h3><a href="/tours/bwindi">Bwindi Gorilla Trek</a></h3>
                                        <p class="rate"><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i></p>
                                    </div>
                                    <div class="two"><span class="price">$1,800</span></div>
                                </div>
                                <p>Encounter the majestic Mountain Gorillas in Uganda’s Bwindi Impenetrable Forest.</p>
                                <hr>
                                <p class="bottom-area d-flex">
                                    <span><i class="icon-map-o"></i> Uganda</span>
                                    <span class="ml-auto"><a href="/tours/bwindi">Details</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4 ftco-animate">
                        <div class="destination">
                            <a href="/tours/serengeti" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url('{{ asset('front/images/Serengeti.jpg') }}');">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-3">
                                <div class="d-flex">
                                    <div class="one">
                                        <h3><a href="/tours/serengeti">Serengeti Migration</a></h3>
                                        <p class="rate"><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i></p>
                                    </div>
                                    <div class="two"><span class="price">$1,250</span></div>
                                </div>
                                <p>Witness the breathtaking Great Migration plains of Tanzania’s Serengeti.</p>
                                <hr>
                                <p class="bottom-area d-flex">
                                    <span><i class="icon-map-o"></i> Tanzania</span>
                                    <span class="ml-auto"><a href="/tours/serengeti">Details</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4 ftco-animate">
                        <div class="destination">
                            <a href="/tours/maasai-mara" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url('{{ asset('front/images/Maasai mara.jpg') }}');">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-3">
                                <div class="d-flex">
                                    <div class="one">
                                        <h3><a href="/tours/maasai-mara">Maasai Mara Luxury</a></h3>
                                        <p class="rate"><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i></p>
                                    </div>
                                    <div class="two"><span class="price">$980</span></div>
                                </div>
                                <p>Experience the Big Five up close with a premium Maasai Mara safari package.</p>
                                <hr>
                                <p class="bottom-area d-flex">
                                    <span><i class="icon-map-o"></i> Kenya</span>
                                    <span class="ml-auto"><a href="/tours/maasai-mara">Details</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4 ftco-animate">
                        <div class="destination">
                            <a href="/tours/zanzibar" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url('{{ asset('front/images/zanzibar_beach.jpg') }}');">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-3">
                                <div class="d-flex">
                                    <div class="one">
                                        <h3><a href="/tours/zanzibar">Zanzibar Escapade</a></h3>
                                        <p class="rate"><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star text-warning"></i><i class="icon-star-half-alt text-warning"></i></p>
                                    </div>
                                    <div class="two"><span class="price">$450</span></div>
                                </div>
                                <p>Relax on pristine white beaches with luxury beachfront accommodations.</p>
                                <hr>
                                <p class="bottom-area d-flex">
                                    <span><i class="icon-map-o"></i> Zanzibar</span>
                                    <span class="ml-auto"><a href="/tours/zanzibar">Details</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Pagination -->
                <div class="row mt-5">
                    <div class="col text-center">
                        <div class="block-27">
                            <ul>
                                <li><a href="#">&lt;</a></li>
                                <li class="active"><span>1</span></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">&gt;</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

@endsection