@extends('layouts.app')

@section('title', 'Premier East African Safari Destinations | Fantera Safaris')
@section('meta_description', 'Discover the best destinations in Uganda, Kenya, and Tanzania. From the misty mountains of Bwindi to the vast Serengeti plains.')
@section('meta_keywords', 'Uganda Destinations, Serengeti Tours, Masai Mara Safaris, Gorilla Trekking Locations')
@section('og_image', asset('front/images/zanzibar_beach.jpg'))
@push('styles')
	<style>
.hero-wrap {
  position: relative;
  height: 90vh;
  min-height: 600px;
  background-size: cover;
  background-position: center;
  display: flex;
  align-items: center;
}

.hero-wrap .overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, 0.45); /* darker overlay for readability */
  z-index: 1;
}

.hero-content {
  position: relative;
  z-index: 2;
  animation: fadeInUp 1s ease forwards;
}

.hero-content h1 {
  text-shadow: 2px 2px 12px rgba(0,0,0,0.6);
}

.hero-content p {
  font-size: 1.25rem;
  line-height: 1.6;
}

.hero-content .btn {
  transition: all 0.3s ease;
}

.hero-content .btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.3);
}

/* Fade-in animation */
@keyframes fadeInUp {
  0% { opacity: 0; transform: translateY(30px); }
  100% { opacity: 1; transform: translateY(0); }
}

/* Breadcrumb styles */
.breadcrumb a {
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s;
}

.breadcrumb a:hover {
  color: #ffc107; /* highlight on hover */
}
	</style>
@endpush
@section('content')
    
   <!-- Hero Section -->
<div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/zanzibar_beach.jpg') }}');">
  <div class="overlay"></div>
  <div class="container h-100">
    <div class="row h-100 align-items-center justify-content-center text-center">
      
      <!-- Content Box -->
      <div class="col-md-10 hero-content">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="d-inline-block mb-3">
          <ol class="breadcrumb justify-content-center bg-transparent p-0 m-0">
            <li class="breadcrumb-item"><a href="/" class="text-white">Home</a></li>
            <li class="breadcrumb-item active text-warning" aria-current="page">Tours</li>
          </ol>
        </nav>

        <!-- Main Title -->
        <h1 class="display-4 font-weight-bold text-white mb-3">Explore East Africa</h1>
        
        <!-- Subtitle / Tagline -->
        <p class="lead text-light mb-4" style="max-width: 700px; margin: 0 auto;">
          Discover the most extraordinary safaris from pristine beaches to iconic wildlife sanctuaries.
        </p>
        
        <!-- CTA Button -->
        <a href="#tours-section" class="btn btn-warning btn-lg shadow-lg mt-3">
          Browse Tours <i class="fas fa-arrow-right ml-2"></i>
        </a>
      </div>

    </div>
  </div>
</div>

    <section class="ftco-section ftco-degree-bg">
      <div class="container">
        <div class="row">
            <div class="col-lg-3 sidebar ftco-animate">
                <div class="sidebar-wrap bg-light ftco-animate">
                    <h3 class="heading mb-4">Filter Your Safari</h3>
                    <form action="#">
                        <div class="fields">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Search Park or Activity">
                            </div>
                            <div class="form-group">
                                <div class="select-wrap one-third">
                                    <div class="icon"><span class="ion-ios-arrow-down"></span></div>
                                    <select name="" id="" class="form-control">
                                      <option value="">Select Region</option>
                                      <option value="">Uganda (Gorillas)</option>
                                      <option value="">Kenya (Maasai Mara)</option>
                                      <option value="">Tanzania (Serengeti)</option>
                                      <option value="">Rwanda (Volcanoes NP)</option>
                                    </select>
                                </div>
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
                    <div class="col-md-4 ftco-animate">
                        <div class="destination">
                            <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/Bwindi.jpg') }});">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-3">
                                <div class="d-flex">
                                    <div class="one">
                                        <h3><a href="#">Bwindi Gorilla Trek</a></h3>
                                        <p class="rate"><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i></p>
                                    </div>
                                    <div class="two"><span class="price">$1,800</span></div>
                                </div>
                                <p>Encounter the mountain gorillas in Uganda.</p>
                                <hr>
                                <p class="bottom-area d-flex">
                                    <span><i class="icon-map-o"></i> Uganda</span> 
                                    <span class="ml-auto"><a href="#">Details</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 ftco-animate">
                        <div class="destination">
                            <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/Serengeti.jpg') }});">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-3">
                                <div class="d-flex">
                                    <div class="one">
                                        <h3><a href="#">Serengeti Migration</a></h3>
                                        <p class="rate"><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i></p>
                                    </div>
                                    <div class="two"><span class="price">$1,250</span></div>
                                </div>
                                <p>Witness the Great Migration plains.</p>
                                <hr>
                                <p class="bottom-area d-flex">
                                    <span><i class="icon-map-o"></i> Tanzania</span> 
                                    <span class="ml-auto"><a href="#">Details</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 ftco-animate">
                        <div class="destination">
                            <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/Maasai mara.jpg') }});">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-3">
                                <div class="d-flex">
                                    <div class="one">
                                        <h3><a href="#">Maasai Mara Luxury</a></h3>
                                        <p class="rate"><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i></p>
                                    </div>
                                    <div class="two"><span class="price">$980</span></div>
                                </div>
                                <p>The ultimate Big Five experience.</p>
                                <hr>
                                <p class="bottom-area d-flex">
                                    <span><i class="icon-map-o"></i> Kenya</span> 
                                    <span class="ml-auto"><a href="#">Details</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 ftco-animate">
                        <div class="destination">
                            <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/gorilla_trek.jpg') }});">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-3">
                                <div class="d-flex">
                                    <div class="one">
                                        <h3><a href="#">Rwanda Primates</a></h3>
                                        <p class="rate"><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i></p>
                                    </div>
                                    <div class="two"><span class="price">$2,100</span></div>
                                </div>
                                <p>Land of a thousand hills adventure.</p>
                                <hr>
                                <p class="bottom-area d-flex">
                                    <span><i class="icon-map-o"></i> Rwanda</span> 
                                    <span class="ml-auto"><a href="#">Details</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 ftco-animate">
                        <div class="destination">
                            <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/big_five.jpg') }});">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-3">
                                <div class="d-flex">
                                    <div class="one">
                                        <h3><a href="#">Big Five Special</a></h3>
                                        <p class="rate"><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i></p>
                                    </div>
                                    <div class="two"><span class="price">$1,400</span></div>
                                </div>
                                <p>Spot the giants of the Savannah.</p>
                                <hr>
                                <p class="bottom-area d-flex">
                                    <span><i class="icon-map-o"></i> East Africa</span> 
                                    <span class="ml-auto"><a href="#">Details</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 ftco-animate">
                        <div class="destination">
                            <a href="#" class="img img-2 d-flex justify-content-center align-items-center" style="background-image: url({{ asset('front/images/zanzibar_beach.jpg') }});">
                                <div class="icon d-flex justify-content-center align-items-center"><span class="icon-search2"></span></div>
                            </a>
                            <div class="text p-3">
                                <div class="d-flex">
                                    <div class="one">
                                        <h3><a href="#">Zanzibar Escapade</a></h3>
                                        <p class="rate"><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i class="icon-star-o"></i></p>
                                    </div>
                                    <div class="two"><span class="price">$450</span></div>
                                </div>
                                <p>Relax on pristine white beaches.</p>
                                <hr>
                                <p class="bottom-area d-flex">
                                    <span><i class="icon-map-o"></i> Zanzibar</span> 
                                    <span class="ml-auto"><a href="#">Details</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                </div><div class="row mt-5">
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