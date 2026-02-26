<x-layout 
    :title="'Premier East African Safari Destinations | Fantera Safaris'"
    :metaDescription="'Discover the best destinations in Uganda, Kenya, and Tanzania. From the misty mountains of Bwindi to the vast Serengeti plains.'"
>
    @push('styles')
        <style>
            .hero-content { z-index: 2; }
            .destination img { transition: transform 0.3s ease; }
            .destination:hover img { transform: scale(1.05); }
        </style>
    @endpush

    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/zanzibar_beach.jpg') }}');">
        <div class="overlay"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-10 hero-content ftco-animate">
                    <nav aria-label="breadcrumb" class="d-inline-block mb-3">
                        <ol class="breadcrumb justify-content-center bg-transparent p-0 m-0">
                            <li class="breadcrumb-item"><a href="/" class="text-white">Home</a></li>
                            <li class="breadcrumb-item active text-warning" aria-current="page">Tours</li>
                        </ol>
                    </nav>
                    <h1 class="display-4 font-weight-bold text-white mb-3">Explore East Africa</h1>
                    <p class="lead text-light mb-4" style="max-width: 700px; margin: 0 auto;">
                        Discover the most extraordinary safaris from pristine beaches to iconic wildlife sanctuaries.
                    </p>
                    <a href="#tours-section" class="btn btn-warning btn-lg shadow-lg mt-3">
                        Browse Tours <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section ftco-degree-bg" id="tours-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 sidebar ftco-animate">
                    <div class="sidebar-wrap bg-light p-4 rounded shadow-sm ftco-animate">
                        <h3 class="heading mb-4" style="font-size: 20px; font-weight: 700;">Filter Your Safari</h3>
                        <form action="#">
                            <div class="fields">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Search Park or Activity">
                                </div>
                                <div class="form-group">
                                    <div class="select-wrap one-third">
                                        <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                        <select class="form-control">
                                            <option value="">Select Region</option>
                                            <option value="uganda">Uganda (Gorillas)</option>
                                            <option value="kenya">Kenya (Maasai Mara)</option>
                                            <option value="tanzania">Tanzania (Serengeti)</option>
                                            <option value="rwanda">Rwanda (Volcanoes NP)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="checkin_date" class="form-control" placeholder="Travel Date">
                                </div>
                                <div class="form-group">
                                    <div class="range-slider">
                                        <small class="d-block mb-2 text-muted">Max Price ($)</small>
                                        <input value="5000" min="500" max="10000" step="100" type="range" class="w-100" id="priceRange">
                                        <div class="d-flex justify-content-between mt-2">
                                            <span class="badge badge-primary">$500</span>
                                            <span class="badge badge-primary">$10,000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary py-3 px-5 w-100">Filter Results</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        @php
                        $tours = [
                            ['title' => 'Bwindi Gorilla Trek', 'location' => 'Uganda', 'price' => '1,800', 'img' => 'Bwindi.jpg', 'desc' => 'Encounter the mountain gorillas.'],
                            ['title' => 'Serengeti Migration', 'location' => 'Tanzania', 'price' => '1,250', 'img' => 'Serengeti.jpg', 'desc' => 'Witness the Great Migration plains.'],
                            ['title' => 'Maasai Mara Luxury', 'location' => 'Kenya', 'price' => '980', 'img' => 'Maasai mara.jpg', 'desc' => 'The ultimate Big Five experience.'],
                            ['title' => 'Rwanda Primates', 'location' => 'Rwanda', 'price' => '2,100', 'img' => 'gorilla_trek.jpg', 'desc' => 'Land of a thousand hills.'],
                            ['title' => 'Big Five Special', 'location' => 'East Africa', 'price' => '1,400', 'img' => 'big_five.jpg', 'desc' => 'Spot the giants of the Savannah.'],
                            ['title' => 'Zanzibar Escapade', 'location' => 'Zanzibar', 'price' => '450', 'img' => 'zanzibar_beach.jpg', 'desc' => 'Relax on pristine white beaches.']
                        ];
                        @endphp

                        @foreach($tours as $tour)
                        <div class="col-md-4 ftco-animate">
                            <div class="destination shadow-sm border rounded overflow-hidden mb-4">
                                <a href="/tour-details" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url('{{ asset('front/images/' . $tour['img']) }}'); height: 200px; background-size: cover;">
                                    <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                                </a>
                                <div class="text p-3">
                                    <div class="d-flex">
                                        <div class="one">
                                            <h3 style="font-size: 18px;"><a href="/tour-details">{{ $tour['title'] }}</a></h3>
                                            <p class="rate text-warning">
                                                <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                            </p>
                                        </div>
                                        <div class="two text-right">
                                            <span class="price text-primary font-weight-bold" style="font-size: 18px;">${{ $tour['price'] }}</span>
                                        </div>
                                    </div>
                                    <p class="text-muted" style="font-size: 14px;">{{ $tour['desc'] }}</p>
                                    <hr>
                                    <p class="bottom-area d-flex mb-0">
                                        <span><i class="icon-map-o mr-1"></i> {{ $tour['location'] }}</span> 
                                        <span class="ml-auto"><a href="/tour-details" class="btn btn-sm btn-outline-primary">Details</a></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

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
</x-layout>