<x-layout 
    :title="'Bwindi Gorilla Trek | Fantera Safaris'"
    :metaDescription="'Experience the unforgettable Bwindi Gorilla Trek in Uganda. Luxury safari packages, expert guides, and conservation-focused travel.'"
>
    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/Bwindi.jpg') }}');">
        <div class="overlay"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-10 hero-content ftco-animate">
                    <p class="breadcrumbs mb-3">
                        <span class="mr-2"><a href="/" class="text-white">Home</a></span> 
                        <span class="mr-2"><a href="/tours" class="text-white">Tours</a></span> 
                        <span class="text-warning">Bwindi Gorilla Trek</span>
                    </p>
                    <h1 class="display-4 font-weight-bold text-white mb-3">Bwindi Gorilla Trek</h1>
                    <p class="lead text-light mb-4" style="max-width: 800px; margin: 0 auto;">
                        Embark on a once-in-a-lifetime adventure to encounter the endangered Mountain Gorillas in their natural habitat.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 ftco-animate">
                    
                    <div class="tour-overview bg-white p-4 p-md-5 rounded shadow-sm mb-5">
                        <h2 class="mb-4" style="font-weight: 700; color: #333;">Tour Overview</h2>
                        <p class="mb-4">Experience the majestic Bwindi Impenetrable Forest in Uganda, home to over half of the world's remaining Mountain Gorillas. This guided trek combines adventure, luxury, and responsible tourism.</p>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-map-marker-alt text-warning mr-2"></i><strong>Location:</strong> Bwindi, Uganda</li>
                                    <li class="mb-2"><i class="fas fa-calendar-alt text-warning mr-2"></i><strong>Duration:</strong> 3 Days / 2 Nights</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2"><i class="fas fa-user-friends text-warning mr-2"></i><strong>Group Size:</strong> 4-10 Travelers</li>
                                    <li class="mb-2"><i class="fas fa-dollar-sign text-warning mr-2"></i><strong>Price:</strong> $1,800 per person</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="tour-itinerary mb-5">
                        <h2 class="mb-4" style="font-weight: 700;">Itinerary</h2>
                        <div class="accordion custom-accordion" id="itineraryAccordion">
                            @php
                                $itinerary = [
                                    ['day' => '1', 'title' => 'Arrival & Orientation', 'content' => 'Arrive at Entebbe Airport, transfer to Bwindi. Evening briefing with your guide and overnight stay in luxury lodge.'],
                                    ['day' => '2', 'title' => 'Gorilla Trekking Adventure', 'content' => 'Early morning trek guided by experts to track gorilla families. Witness these gentle giants in their natural habitat.'],
                                    ['day' => '3', 'title' => 'Departure', 'content' => 'After breakfast, check-out and transfer back to Entebbe. Optional city tour or departure.']
                                ];
                            @endphp

                            @foreach($itinerary as $index => $item)
                            <div class="card border-0 mb-3 shadow-sm">
                                <div class="card-header bg-white border-0" id="heading{{ $index }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left text-dark font-weight-bold py-3 {{ $index != 0 ? 'collapsed' : '' }}" type="button" data-toggle="collapse" data-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                                            <span class="text-warning mr-3">Day {{ $item['day'] }}:</span> {{ $item['title'] }}
                                            <i class="fas fa-chevron-down float-right mt-1"></i>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapse{{ $index }}" class="collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-parent="#itineraryAccordion">
                                    <div class="card-body pt-0 pb-4">
                                        {{ $item['content'] }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tour-gallery mb-5">
                        <h2 class="mb-4" style="font-weight: 700;">Gallery</h2>
                        <div class="row">
                            @foreach(['Bwindi_1.jpg', 'Bwindi_2.jpg'] as $img)
                            <div class="col-md-6 mb-4">
                                <div class="img-popup d-block rounded shadow-sm" style="background-image: url('{{ asset('front/images/'.$img) }}'); height: 250px; background-size: cover;">
                                    <div class="overlay-gallery d-flex align-items-center justify-content-center">
                                        <i class="fas fa-search-plus text-white fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tour-features bg-white p-4 p-md-5 rounded shadow-sm mb-5">
                        <h2 class="mb-4" style="font-weight: 700;">Tour Features</h2>
                        <div class="row">
                            @php
                                $features = [
                                    ['icon' => 'star', 'text' => 'Luxury accommodation'],
                                    ['icon' => 'utensils', 'text' => 'Gourmet meals included'],
                                    ['icon' => 'hiking', 'text' => 'Guided treks in Bwindi Forest'],
                                    ['icon' => 'leaf', 'text' => 'Conservation-focused activities']
                                ];
                            @endphp
                            @foreach($features as $feature)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-circle bg-light-warning mr-3">
                                            <i class="fas fa-{{ $feature['icon'] }} text-warning"></i>
                                        </div>
                                        <span>{{ $feature['text'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tour-reviews mb-5">
                        <h2 class="mb-4" style="font-weight: 700;">Traveler Reviews</h2>
                        @foreach([['Sarah M.', 'user1.jpg', 'Unforgettable!'], ['James K.', 'user2.jpg', 'Professional guides!']] as $review)
                        <div class="media mb-4 p-3 bg-white rounded shadow-sm">
                            <img src="{{ asset('front/images/'.$review[1]) }}" class="mr-3 rounded-circle shadow-sm" width="60" height="60" alt="Traveler">
                            <div class="media-body">
                                <h5 class="mt-0 font-weight-bold">{{ $review[0] }}</h5>
                                <div class="text-warning mb-2"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                                <p class="mb-0 italic">"{{ $review[2] }}"</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4 sidebar">
                    <div class="sticky-top" style="top: 100px; z-index: 1;">
                        <div class="sidebar-box bg-white p-4 rounded shadow-sm mb-4 border-top border-warning" style="border-width: 4px !important;">
                            <h3 class="mb-3 font-weight-bold">Quick Info</h3>
                            <ul class="list-unstyled mb-4">
                                <li class="py-2 border-bottom"><i class="fas fa-clock text-warning mr-2"></i> 3 Days / 2 Nights</li>
                                <li class="py-2 border-bottom"><i class="fas fa-users text-warning mr-2"></i> Group Size: 4-10</li>
                                <li class="py-2 border-bottom"><i class="fas fa-dollar-sign text-warning mr-2"></i> From $1,800</li>
                                <li class="py-2"><i class="fas fa-map-marker-alt text-warning mr-2"></i> Bwindi, Uganda</li>
                            </ul>
                            <a href="/booking/bwindi" class="btn btn-warning btn-block btn-lg shadow">Book This Safari</a>
                        </div>

                        <div class="sidebar-box bg-white p-4 rounded shadow-sm mb-4">
                            <h3 class="mb-3 font-weight-bold">Related Tours</h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><a href="/tours/serengeti" class="text-dark"><i class="fas fa-chevron-right mr-2 text-warning small"></i> Serengeti Migration</a></li>
                                <li class="mb-2"><a href="/tours/maasai-mara" class="text-dark"><i class="fas fa-chevron-right mr-2 text-warning small"></i> Maasai Mara Luxury</a></li>
                                <li><a href="/tours/zanzibar" class="text-dark"><i class="fas fa-chevron-right mr-2 text-warning small"></i> Zanzibar Escapade</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>