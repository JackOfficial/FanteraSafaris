@extends('layouts.app')

@section('content')
<div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/bg_2.jpg') }}');">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
      <div class="col-md-9 text-center ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
        <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }"><span class="mr-2"><a href="/">Home</a></span> <span>Our Story</span></p>
        <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">The Fantera Legacy</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section">
    <div class="container">
        <div class="row d-md-flex">
            <div class="col-md-6 ftco-animate img about-image" style="background-image: url({{ asset('front/images/about_safari.jpg') }});">
            </div>
            <div class="col-md-6 ftco-animate p-md-5">
                <div class="row">
              <div class="col-md-12 nav-link-wrap mb-5">
                <div class="nav ftco-animate nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                  <a class="nav-link active" id="v-pills-whatwedo-tab" data-toggle="pill" href="#v-pills-whatwedo" role="tab" aria-controls="v-pills-whatwedo" aria-selected="true">What We Do</a>
                  <a class="nav-link" id="v-pills-mission-tab" data-toggle="pill" href="#v-pills-mission" role="tab" aria-controls="v-pills-mission" aria-selected="false">Our Mission</a>
                  <a class="nav-link" id="v-pills-goal-tab" data-toggle="pill" href="#v-pills-goal" role="tab" aria-controls="v-pills-goal" aria-selected="false">Our Values</a>
                </div>
              </div>
              <div class="col-md-12 d-flex align-items-center">
                
                <div class="tab-content ftco-animate" id="v-pills-tabContent">

                  <div class="tab-pane fade show active" id="v-pills-whatwedo" role="tabpanel" aria-labelledby="v-pills-whatwedo-tab">
                    <div>
                        <h2 class="mb-4">Curating Extraordinary Encounters</h2>
                        <p>At Fantera Safaris, we specialize in bespoke luxury expeditions across Uganda, Kenya, Tanzania, and Rwanda. We bridge the gap between untamed wilderness and refined comfort.</p>
                        <p>From private gorilla trekking permits to luxury mobile camps in the path of the Great Migration, we handle every detail of your East African odyssey.</p>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="v-pills-mission" role="tabpanel" aria-labelledby="v-pills-mission-tab">
                    <div>
                        <h2 class="mb-4">Preserving the Wild</h2>
                        <p>Our mission is to provide life-changing travel experiences that directly contribute to the conservation of Africa’s wildlife and the empowerment of its local communities.</p>
                        <p>We believe that responsible tourism is the most powerful tool for protecting endangered species like the Mountain Gorilla and the African Lion.</p>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="v-pills-goal" role="tabpanel" aria-labelledby="v-pills-goal-tab">
                    <div>
                        <h2 class="mb-4">Integrity & Authenticity</h2>
                        <p>We don't do "tourist traps." Our goal is to provide authentic cultural immersion and exclusive access to the most pristine wilderness areas in East Africa.</p>
                        <p>We prioritize safety, professional guiding, and a "leave no trace" philosophy in every park we enter.</p>
                    </div>
                  </div>
                </div>
              </div>
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