@extends('layouts.app')

@section('content')
<div class="hero-wrap" style="background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)), url('{{ asset('front/images/bg_2.jpg') }}') center center/cover no-repeat; height: 70vh;">
  <div class="container h-100">
    <div class="row h-100 align-items-center justify-content-center text-center">
      <div class="col-md-10 text-white">
        <p class="breadcrumbs mb-3">
          <span class="mr-2"><a href="/" class="text-white">Home</a></span>
          <span>About Us</span>
        </p>
        <h1 class="display-4 font-weight-bold">The Fantera Legacy</h1>
        <p class="lead mt-3" style="max-width:700px;margin:auto;">
          Crafting unforgettable East African safaris with integrity, conservation, and refined luxury.
        </p>
      </div>
    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="row align-items-center">

      <!-- Image -->
      <div class="col-md-6 mb-4 mb-md-0">
        <img src="{{ asset('front/images/about_us.jpg') }}" 
             class="img-fluid rounded shadow" 
             alt="Luxury Safari Experience">
      </div>

      <!-- Content -->
      <div class="col-md-6 pl-md-5">

        <h2 class="font-weight-bold mb-4">Curating Extraordinary Encounters</h2>

        <p>
          At <strong>Fantera Safaris</strong>, we specialize in bespoke luxury expeditions across Uganda, Kenya, Tanzania, and Rwanda — bridging the gap between untamed wilderness and refined comfort.
        </p>

        <p>
          From private gorilla trekking permits to exclusive mobile camps positioned along the Great Migration route, we manage every detail of your East African odyssey with precision and care.
        </p>

        <div class="mt-4">
          <a href="{{ route('contact') }}" class="btn btn-warning font-weight-bold px-4 py-2">
            Plan Your Safari
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<section class="ftco-section bg-light">
    <div class="container">
        <div class="row justify-content-start mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate">
        <span class="subheading">Expert Advice</span>
        <h2 class="mb-4"><strong>Safari</strong> FAQs</h2>
      </div>
    </div>  
        <div class="row">
            <div class="col-md-12 ftco-animate">
                <div id="accordion">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                            <div class="card-header">
                                      <a class="card-link" data-toggle="collapse"  href="#menuone" aria-expanded="true" aria-controls="menuone">When is the best time for a Safari? <span class="collapsed"><i class="icon-plus-circle"></i></span><span class="expanded"><i class="icon-minus-circle"></i></span></a>
                            </div>
                            <div id="menuone" class="collapse show">
                              <div class="card-body">
                                        <p>Generally, the dry seasons (June to September and December to February) offer the best wildlife viewing as animals congregate around water sources. For the Great Migration, July to October is peak timing.</p>
                              </div>
                            </div>
                          </div>

                          <div class="card">
                            <div class="card-header">
                                      <a class="card-link" data-toggle="collapse"  href="#menutwo" aria-expanded="false" aria-controls="menutwo">Do I need a permit for Gorilla Trekking? <span class="collapsed"><i class="icon-plus-circle"></i></span><span class="expanded"><i class="icon-minus-circle"></i></span></a>
                            </div>
                            <div id="menutwo" class="collapse">
                              <div class="card-body">
                                        <p>Yes. Permits are highly regulated and limited. We recommend booking at least 6 months in advance. We handle the entire procurement process for our guests in Uganda and Rwanda.</p>
                              </div>
                            </div>
                          </div>

                          <div class="card">
                            <div class="card-header">
                                      <a class="card-link" data-toggle="collapse"  href="#menu3" aria-expanded="false" aria-controls="menu3"> Is East Africa safe for travelers? <span class="collapsed"><i class="icon-plus-circle"></i></span><span class="expanded"><i class="icon-minus-circle"></i></span></a>
                            </div>
                            <div id="menu3" class="collapse">
                              <div class="card-body">
                                        <p>Absolutely. Tourism is a primary industry here, and guest safety is our top priority. You will be accompanied by professional guides from arrival at the airport until your departure.</p>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                            <div class="card-header">
                                      <a class="card-link" data-toggle="collapse"  href="#menu4" aria-expanded="false" aria-controls="menu4">What should I pack for my trip? <span class="collapsed"><i class="icon-plus-circle"></i></span><span class="expanded"><i class="icon-minus-circle"></i></span></a>
                            </div>
                            <div id="menu4" class="collapse">
                              <div class="card-body">
                                        <p>Lightweight, neutral-colored clothing (khaki, green, tan) is best. Don't forget sturdy walking boots for treks, a warm jacket for early morning drives, and high-SPF sunscreen.</p>
                              </div>
                            </div>
                          </div>

                          <div class="card">
                            <div class="card-header">
                                      <a class="card-link" data-toggle="collapse"  href="#menu5" aria-expanded="false" aria-controls="menu5">Are international flights included? <span class="collapsed"><i class="icon-plus-circle"></i></span><span class="expanded"><i class="icon-minus-circle"></i></span></a>
                            </div>
                            <div id="menu5" class="collapse">
                              <div class="card-body">
                                        <p>Our packages typically cover all ground transport, regional bush flights, luxury accommodation, and park fees. International flights to Entebbe, Nairobi, or Kilimanjaro are usually booked by the guest.</p>
                              </div>
                            </div>
                          </div>

                          <div class="card">
                            <div class="card-header">
                                      <a class="card-link" data-toggle="collapse"  href="#menu6" aria-expanded="false" aria-controls="menu6">Can I customize my itinerary? <span class="collapsed"><i class="icon-plus-circle"></i></span><span class="expanded"><i class="icon-minus-circle"></i></span></a>
                            </div>
                            <div id="menu6" class="collapse">
                              <div class="card-body">
                                        <p>Yes, Fantera Safaris is 100% tailor-made. You can choose your pace, level of luxury, and specific wildlife interests. We build the trip around you.</p>
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection