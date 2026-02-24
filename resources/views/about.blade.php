@extends('layouts.app')
@push('styles')
  <style>
    .btn-warning {
  background-color: #d4a373;
  border: none;
}

.btn-warning:hover {
  background-color: #c48c5a;
}

.card {
  transition: 0.3s;
}

.card:hover {
  transform: translateY(-5px);
}
/* Bigger, more premium icons */

.icon-box {
  width: 50px;
  height: 50px;
  margin: 0 auto;
  border-radius: 50%;
  background: linear-gradient(135deg, #d4a373, #c48c5a);
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-box i {
  font-size: 25px;
  color: #fff;
}

.commitment-card:hover .icon-box {
  transform: scale(1.08);
  transition: 0.3s ease;
}
  </style>
@endpush
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
          <a href="/contact" class="btn btn-warning font-weight-bold px-4 py-2">
            Plan Your Safari
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

<section class="py-5 bg-light commitment-section">
  <div class="container">

    <!-- Section Heading -->
    <div class="row text-center mb-5">
      <div class="col-md-8 mx-auto">
        <span class="text-warning text-uppercase font-weight-bold" style="letter-spacing:2px;font-size:13px;">
          Our Philosophy
        </span>
        <h2 class="font-weight-bold mt-2">Our Commitment</h2>
        <p class="text-muted">
          Luxury travel rooted in conservation, authenticity, and meaningful impact.
        </p>
      </div>
    </div>

    <!-- Commitment Cards -->
    <div class="row">

      <!-- Card 1 -->
      <div class="col-md-4 mb-4">
        <div class="commitment-card text-center p-5 bg-white rounded shadow-sm h-100">
          
          <div class="icon-box mb-4">
            <i class="fas fa-paw"></i>
          </div>

          <h5 class="font-weight-bold mb-3">Conservation First</h5>
          <p class="text-muted mb-0">
            We actively support wildlife preservation and sustainable tourism 
            initiatives that protect Africa’s fragile ecosystems.
          </p>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-4 mb-4">
        <div class="commitment-card text-center p-5 bg-white rounded shadow-sm h-100">

          <div class="icon-box mb-4">
            <i class="fas fa-hands-helping"></i>
          </div>

          <h5 class="font-weight-bold mb-3">Community Empowerment</h5>
          <p class="text-muted mb-0">
            Our safaris directly contribute to local communities through 
            employment, partnerships, and ethical sourcing.
          </p>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-4 mb-4">
        <div class="commitment-card text-center p-5 bg-white rounded shadow-sm h-100">

          <div class="icon-box mb-4">
            <i class="fas fa-globe-africa"></i>
          </div>

          <h5 class="font-weight-bold mb-3">Authentic Experiences</h5>
          <p class="text-muted mb-0">
            We avoid mass tourism and curate immersive journeys that connect 
            travelers to Africa’s true spirit.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="font-weight-bold">Safari FAQs</h2>
      <p class="text-muted">Everything you need to know before your journey.</p>
    </div>

    <div id="faqAccordion">

      @php
      $faqs = [
        ['id' => 1, 'q' => 'When is the best time for a Safari?', 'a' => 'The dry seasons (June–September & December–February) provide optimal wildlife viewing. For the Great Migration, July–October is peak season.'],
        ['id' => 2, 'q' => 'Do I need a permit for Gorilla Trekking?', 'a' => 'Yes. Permits are limited and should be secured 4–6 months in advance. We handle the full permit process for Uganda and Rwanda.'],
        ['id' => 3, 'q' => 'Is East Africa safe for travelers?', 'a' => 'Yes. You will be accompanied by professional guides from arrival to departure. Guest safety is our top priority.'],
        ['id' => 4, 'q' => 'Can I customize my itinerary?', 'a' => 'Absolutely. Every Fantera safari is tailor-made to match your travel style, pace, and wildlife interests.']
      ];
      @endphp

      @foreach($faqs as $faq)
      <div class="card mb-3 border-0 shadow-sm">
        <div class="card-header bg-white">
          <a class="d-block font-weight-bold text-dark" data-toggle="collapse" href="#faq{{ $faq['id'] }}">
            {{ $faq['q'] }}
          </a>
        </div>
        <div id="faq{{ $faq['id'] }}" class="collapse {{ $loop->first ? 'show' : '' }}" data-parent="#faqAccordion">
          <div class="card-body">
            {{ $faq['a'] }}
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</section>

<section class="py-5 text-center text-white" style="background:#1b4332;">
  <div class="container">
    <h2 class="font-weight-bold mb-3">Ready To Experience Africa Differently?</h2>
    <p class="mb-4">Let our experts craft your tailor-made luxury safari.</p>
    <a href="/contact" class="btn btn-warning btn-lg font-weight-bold px-4 py-3">
      Start Planning Your Safari
    </a>
  </div>
</section>

@endsection