<x-layout 
    :title="$destination->name . ' | Fantera Safaris'"
    :metaDescription="Str::limit(strip_tags($destination->description), 160)"
>
    <x-slot name="styles">
        <style>
            :root { --safari-gold: #ffc107; --safari-dark: #1a1a1a; }
            
            /* Immersive Breadcrumbs */
            .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before { content: "•"; color: rgba(255,255,255,0.5); padding: 0 10px; }
            
            /* Glassmorphism Stats Bar */
            .stats-bar {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(10px);
                border-radius: 20px;
                padding: 25px;
                margin-top: -50px; /* Pulls it up into the white space */
                position: relative;
                z-index: 10;
                border: 1px solid rgba(0,0,0,0.05);
            }

            /* Premium Safari Cards */
            .package-card { 
                border-radius: 24px; 
                overflow: hidden; 
                border: none; 
                transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); 
                background: #fff;
            }
            .package-card:hover { transform: translateY(-12px); box-shadow: 0 25px 50px rgba(0,0,0,0.1) !important; }
            .card-img-container { height: 220px; overflow: hidden; position: relative; }
            .card-img-top { transition: 0.8s ease; object-fit: cover; height: 100%; width: 100%; }
            .package-card:hover .card-img-top { transform: scale(1.1); }
            
            /* Sidebar Styling */
            .sidebar-card { border-radius: 25px; border: none; overflow: hidden; background: #fff; }
            .form-control:focus { box-shadow: none; border-color: var(--safari-gold); background: #fff !important; }
            .sticky-top { top: 110px !important; }
        </style>
    </x-slot>

    {{-- Hero Section: Showcasing the Destination --}}
    <div class="hero-wrap" style="background-image: url('{{ $destination->image ? asset('storage/' . $destination->image) : asset('front/images/default_destination.jpg') }}'); height: 65vh; background-size: cover; background-position: center; position: relative;">
        <div class="overlay" style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.7) 100%);"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-10" style="z-index: 2;">
                    <nav aria-label="breadcrumb" class="breadcrumb-modern">
                        <ol class="breadcrumb justify-content-center bg-transparent p-0 m-0 mb-3">
                            <li class="breadcrumb-item"><a href="/" class="text-white-50">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('destinations.index') }}" class="text-white-50">Destinations</a></li>
                            <li class="breadcrumb-item active text-warning font-weight-bold" aria-current="page">{{ $destination->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="display-3 font-weight-bold text-white mb-2" style="font-family: 'Playfair Display', serif;">{{ $destination->name }}</h1>
                    <p class="text-white font-weight-bold" style="letter-spacing: 3px; text-transform: uppercase; font-size: 14px;">
                        <i class="fas fa-map-marker-alt text-warning mr-2"></i> {{ $destination->country }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section bg-light pb-5">
        <div class="container">
            {{-- Quick Stats Bar --}}
            <div class="row justify-content-center mb-5">
                <div class="col-lg-10">
                    <div class="stats-bar shadow-sm d-flex justify-content-around text-center flex-wrap">
                        <div class="px-3">
                            <span class="d-block text-muted small font-weight-bold uppercase">Available Tours</span>
                            <span class="h5 font-weight-bold mb-0 text-dark">{{ $packages->total() }} Packages</span>
                        </div>
                        <div class="px-3 border-left">
                            <span class="d-block text-muted small font-weight-bold uppercase">Region</span>
                            <span class="h5 font-weight-bold mb-0 text-dark">East Africa</span>
                        </div>
                        <div class="px-3 border-left">
                            <span class="d-block text-muted small font-weight-bold uppercase">Category</span>
                            <span class="h5 font-weight-bold mb-0 text-dark">Top Destination</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-8">
                    {{-- About Section --}}
                    <div class="bg-white p-4 p-md-5 mb-5 shadow-sm" style="border-radius: 25px;">
                        <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-weight: 800;">Experience {{ $destination->name }}</h2>
                        <div class="content text-muted mb-0" style="line-height: 1.8; font-size: 17px;">
                            {!! $destination->description !!}
                        </div>
                    </div>

                    {{-- Safari Packages Grid --}}
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <h3 class="font-weight-bold mb-0" style="font-family: 'Playfair Display', serif;">Tours including {{ $destination->name }}</h3>
                        <span class="text-muted small">{{ $packages->total() }} results</span>
                    </div>

                    <div class="row">
                        @forelse($packages as $package)
                            <div class="col-md-6 mb-5">
                                <div class="card package-card shadow-sm h-100">
                                    <div class="card-img-container">
                                        <img src="{{ $package->photo ? asset('storage/' . $package->photo->path) : asset('front/images/safari_default.jpg') }}" class="card-img-top">
                                        <div style="position: absolute; bottom: 15px; left: 15px; background: rgba(0,0,0,0.6); color: #fff; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; backdrop-filter: blur(4px);">
                                            {{ $package->duration_days }} DAYS
                                        </div>
                                    </div>
                                    <div class="card-body p-4">
                                        <h5 class="font-weight-bold mb-2 text-dark">{{ $package->name }}</h5>
                                        <p class="text-muted small mb-3">{{ Str::limit(strip_tags($package->description), 90) }}</p>
                                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                            <div>
                                                <span class="text-muted small d-block mb-n1">From</span>
                                                <span class="font-weight-bold text-dark h5 mb-0">${{ number_format($package->price) }}</span>
                                            </div>
                                            <a href="{{ route('safaris.show', $package->slug) }}" class="btn btn-warning px-3 font-weight-bold" style="border-radius: 12px; font-size: 13px;">Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="p-5 text-center bg-white rounded-lg shadow-sm">
                                    <i class="fas fa-search fa-3x text-light mb-3"></i>
                                    <h5 class="text-muted">No specific packages found for this destination yet.</h5>
                                    <p class="small text-muted">But we can create a custom itinerary for you!</p>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Bootstrap Pagination --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $packages->links() }}
                    </div>
                </div>

                {{-- Lead Generation Sidebar --}}
                <div class="col-lg-4">
                    <div class="sticky-top">
                        <div class="card sidebar-card shadow-lg mb-4">
                            <div class="p-4 text-center" style="background: var(--safari-dark);">
                                <h4 class="text-white mb-1" style="font-family: 'Playfair Display', serif;">Enquire Now</h4>
                                <p class="text-white-50 small mb-0">Get a custom quote for {{ $destination->name }}</p>
                            </div>
                            <div class="card-body p-4 bg-white">
                                <form action="#">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold text-muted">FULL NAME</label>
                                        <input type="text" class="form-control border-0 bg-light p-3" style="border-radius: 12px;" placeholder="John Doe">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold text-muted">EMAIL ADDRESS</label>
                                        <input type="email" class="form-control border-0 bg-light p-3" style="border-radius: 12px;" placeholder="john@example.com">
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="small font-weight-bold text-muted">SELECT TOUR</label>
                                        <select class="form-control border-0 bg-light custom-select" style="border-radius: 12px; height: 50px;">
                                            <option selected disabled>Choose a package...</option>
                                            @foreach($packages as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                            <option>I want a tailor-made trip</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-warning btn-block btn-lg py-3 shadow-sm font-weight-bold" style="border-radius: 15px; letter-spacing: 1px;">SEND INQUIRY</button>
                                </form>
                            </div>
                        </div>

                        {{-- Why Book with Us --}}
                        <div class="bg-white p-4 shadow-sm" style="border-radius: 25px; border: 1px solid #f0f0f0;">
                            <h6 class="font-weight-bold mb-3 text-dark">Why book this region?</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="small mb-3 d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success mt-1 mr-2"></i>
                                    <span>Verified luxury accommodations and lodges.</span>
                                </li>
                                <li class="small mb-3 d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success mt-1 mr-2"></i>
                                    <span>Expert local guides with 10+ years experience.</span>
                                </li>
                                <li class="small d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success mt-1 mr-2"></i>
                                    <span>Flexible cancellation and 24/7 support.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>