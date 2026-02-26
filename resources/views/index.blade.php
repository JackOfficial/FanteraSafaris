<x-layout
    title="Luxury Uganda Safaris & East Africa Tours"
    metaDescription="Experience authentic African wildness with Fantera Safaris. Specialized Gorilla trekking in Uganda, Rwanda primates, and Serengeti migrations."
    :ogImage="asset('front/images/Bwindi.jpg')"
>
    <section class="hero-wrap d-flex align-items-center" style="background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('{{ asset('front/images/Bwindi.jpg') }}') center center/cover no-repeat; height: 100vh;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 text-white">
                    <h1 class="font-weight-bold mb-4" style="font-size:52px; line-height:1.2;">
                        Experience <br>
                        <span class="text-warning">The Wild Heart of Africa</span>
                    </h1>
                    <p class="lead mb-4" style="max-width:600px;">
                        Bespoke luxury safaris across Uganda, Kenya, Tanzania & Rwanda.
                        From intimate Gorilla encounters to the breathtaking Great Migration.
                    </p>
                    <a href="#" class="btn btn-warning btn-lg font-weight-bold px-4 py-3">
                        Explore Tours
                    </a>
                </div>

                <div class="col-lg-5 mt-5 mt-lg-0">
                    <div class="bg-white p-4 rounded shadow">
                        <h5 class="font-weight-bold mb-3 text-center text-dark">Plan Your Safari</h5>
                        <form action="#">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Full Name">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Email Address">
                            </div>
                            <div class="form-group">
                                <select class="form-control">
                                    <option>Select Destination</option>
                                    <option>Uganda</option>
                                    <option>Rwanda</option>
                                    <option>Kenya</option>
                                    <option>Tanzania</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-warning btn-block font-weight-bold">
                                Start Planning
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section services-section bg-light">
        <div class="container">
            <div class="row d-flex">
                <div class="col-md-3 d-flex align-self-stretch ftco-animate">
                    <div class="media block-6 services d-block text-center">
                        <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-guarantee"></span></div></div>
                        <div class="media-body p-2 mt-2">
                            <h3 class="heading mb-3">Expert Local Knowledge</h3>
                            <p>Based in Kampala, our guides possess intimate knowledge of East Africa's hidden gems.</p>
                        </div>
                    </div>      
                </div>
                <div class="col-md-3 d-flex align-self-stretch ftco-animate">
                    <div class="media block-6 services d-block text-center">
                        <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-like"></span></div></div>
                        <div class="media-body p-2 mt-2">
                            <h3 class="heading mb-3">Tailor-Made Journeys</h3>
                            <p>No two travelers are the same. We curate every itinerary to match your specific desires.</p>
                        </div>
                    </div>    
                </div>
                <div class="col-md-3 d-flex align-self-stretch ftco-animate">
                    <div class="media block-6 services d-block text-center">
                        <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-detective"></span></div></div>
                        <div class="media-body p-2 mt-2">
                            <h3 class="heading mb-3">Eco-Conscious Travel</h3>
                            <p>We prioritize sustainability and community support in every park we visit.</p>
                        </div>
                    </div>      
                </div>
                <div class="col-md-3 d-flex align-self-stretch ftco-animate">
                    <div class="media block-6 services d-block text-center">
                        <div class="d-flex justify-content-center"><div class="icon"><span class="flaticon-support"></span></div></div>
                        <div class="media-body p-2 mt-2">
                            <h3 class="heading mb-3">24/7 Support</h3>
                            <p>From arrival at Entebbe to your final departure, we are with you every step.</p>
                        </div>
                    </div>      
                </div>
            </div>
        </div>
    </section>
    
    <section class="ftco-section ftco-destination">
        <div class="container">
            <div class="row justify-content-start mb-5 pb-3">
                <div class="col-md-7 heading-section ftco-animate">
                    <span class="subheading">Unforgettable Places</span>
                    <h2 class="mb-4"><strong>Featured</strong> Destinations</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="destination-slider owl-carousel ftco-animate">
                        <div class="item">
                            <x-destination
                                image="front/images/Bwindi.jpg"
                                title="Bwindi Impenetrable, Uganda"
                                listing="Home of the Gorillas"
                            ></x-destination>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url({{ asset('front/images/bg_1.jpg') }});">
        <div class="container">
            <div class="row justify-content-center mb-5 pb-3">
                <div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
                    <h2 class="mb-4">Our Legacy in the Wild</h2>
                    <span class="subheading">Delivering Excellence Across East Africa</span>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
                            <div class="block-18 text-center">
                                <div class="text">
                                    <strong class="number" data-number="1500">0</strong>
                                    <span>Happy Adventurers</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-slot:scripts>
        <script>
            $(document).ready(function(){
                // Your Owl Carousel or animation triggers here
            });
        </script>
    </x-slot:scripts>
</x-layout>