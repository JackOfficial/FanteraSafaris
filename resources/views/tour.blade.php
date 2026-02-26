<x-layout
    title="Premier East African Safari Destinations"
    metaDescription="Discover the best destinations in Uganda, Kenya, and Tanzania. From the misty mountains of Bwindi to the vast Serengeti plains."
    metaKeywords="Uganda Destinations, Serengeti Tours, Masai Mara Safaris, Gorilla Trekking Locations"
    :ogImage="asset('front/images/zanzibar_beach.jpg')"
>
    <x-slot:styles>
        <style>
            /* Custom styles for the Tours page */
            .hero-wrap.js-fullheight {
                height: 70vh; /* Adjusted for a tours listing page */
                min-height: 500px;
            }
        </style>
    </x-slot:styles>

    <div class="hero-wrap d-flex align-items-center" style="background-image: url('{{ asset('front/images/zanzibar_beach.jpg') }}'); background-size: cover; background-position: center; position: relative; height: 70vh;">
        <div class="overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4);"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-10 hero-content" style="z-index: 2;">
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
                    <div class="sidebar-wrap bg-light p-4 rounded ftco-animate">
                        <h3 class="heading mb-4" style="font-size: 20px; font-weight: 700;">Filter Your Safari</h3>
                        <form action="#">
                            <div class="fields">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Search Park or Activity">
                                </div>
                                <div class="form-group">
                                    <select class="form-control">
                                        <option value="">Select Region</option>
                                        <option value="">Uganda (Gorillas)</option>
                                        <option value="">Kenya (Maasai Mara)</option>
                                        <option value="">Tanzania (Serengeti)</option>
                                        <option value="">Rwanda (Volcanoes NP)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="checkin_date" class="form-control" placeholder="Travel Date">
                                </div>
                                <div class="form-group">
                                    <div class="range-slider">
                                        <small class="d-block mb-2 text-muted">Price Range ($)</small>
                                        <input value="500" min="0" max="10000" step="100" type="range" class="w-100">
                                        <div class="d-flex justify-content-between mt-2">
                                            <span class="badge badge-primary">$500</span>
                                            <span class="badge badge-primary">$10,000</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="Filter Results" class="btn btn-primary py-3 px-5 w-100">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row">
                        @php
                            $tours = [
                                ['title' => 'Bwindi Gorilla Trek', 'price' => '1,800', 'img' => 'Bwindi.jpg', 'loc' => 'Uganda', 'desc' => 'Encounter the mountain gorillas.'],
                                ['title' => 'Serengeti Migration', 'price' => '1,250', 'img' => 'Serengeti.jpg', 'loc' => 'Tanzania', 'desc' => 'Witness the Great Migration.'],
                                ['title' => 'Maasai Mara Luxury', 'price' => '980', 'img' => 'Maasai mara.jpg', 'loc' => 'Kenya', 'desc' => 'The ultimate Big Five experience.'],
                                ['title' => 'Rwanda Primates', 'price' => '2,100', 'img' => 'gorilla_trek.jpg', 'loc' => 'Rwanda', 'desc' => 'Land of a thousand hills.'],
                                ['title' => 'Big Five Special', 'price' => '1,400', 'img' => 'big_five.jpg', 'loc' => 'East Africa', 'desc' => 'Spot the giants of Savannah.'],
                                ['title' => 'Zanzibar Escapade', 'price' => '450', 'img' => 'zanzibar_beach.jpg', 'loc' => 'Zanzibar', 'desc' => 'Relax on pristine beaches.']
                            ];
                        @endphp

                        @foreach($tours as $tour)
                        <div class="col-md-4 ftco-animate">
                            <div class="destination">
                                <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/' . $tour['img']) }});">
                                    <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                                </a>
                                <div class="text p-3">
                                    <div class="d-flex">
                                        <div class="one">
                                            <h3><a href="#">{{ $tour['title'] }}</a></h3>
                                            <p class="rate"><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i><i class="fas fa-star text-warning"></i></p>
                                        </div>
                                        <div class="two"><span class="price">${{ $tour['price'] }}</span></div>
                                    </div>
                                    <p>{{ $tour['desc'] }}</p>
                                    <hr>
                                    <p class="bottom-area d-flex">
                                        <span><i class="fas fa-map-marker-alt"></i> {{ $tour['loc'] }}</span> 
                                        <span class="ml-auto"><a href="#">Details</a></span>
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

    <x-slot:scripts>
        <script>
            console.log("Tours page loaded");
        </script>
    </x-slot:scripts>
</x-layout>