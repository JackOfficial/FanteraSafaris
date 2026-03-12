<x-layout 
    :title="$destination->name . ' | Fantera Safaris'"
    :metaDescription="Str::limit(strip_tags($destination->description), 160)"
>
   <x-slot name="styles">
        <style>
            .hero-wrap.hero-bread { height: 500px !important; }
            .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before { content: "→"; color: rgba(255,255,255,0.5); }

            /* Feature Box Grid */
            .feature-box {
                background: #f8f9fa;
                border-radius: 15px;
                padding: 20px;
                transition: 0.3s;
                height: 100%;
                border: 1px solid transparent;
            }
            .feature-box:hover { border-color: #ffc107; background: #fff; transform: translateY(-5px); }
            .icon-circle {
                width: 50px; height: 50px; background: rgba(255, 193, 7, 0.1);
                border-radius: 12px; display: flex; align-items: center; justify-content: center;
            }

            /* Package Cards */
            .package-card { border-radius: 20px; overflow: hidden; border: none; transition: 0.3s; }
            .package-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
            
            .sidebar-card { border-radius: 25px; border: none; overflow: hidden; }
            .sticky-top { top: 100px !important; }
        </style>
    </x-slot>

    {{-- Dynamic Hero Section using Destination Image --}}
    <div class="hero-wrap" style="background-image: url('{{ $destination->image_path ? asset('storage/' . $destination->image_path) : asset('front/images/default_destination.jpg') }}'); height: 60vh; background-size: cover; background-position: center; position: relative;">
        <div class="overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4);"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-md-10" style="z-index: 2;">
                    <nav aria-label="breadcrumb" class="breadcrumb-modern">
                        <ol class="breadcrumb justify-content-center bg-transparent p-0 m-0 mb-3">
                            <li class="breadcrumb-item"><a href="/" class="text-white-50">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('destinations.index') }}" class="text-white-50">Destinations</a></li>
                            <li class="breadcrumb-item active text-warning font-weight-bold">{{ $destination->name }}</li>
                        </ol>
                    </nav>
                    <h1 class="display-3 font-weight-bold text-white mb-3" style="font-family: 'Playfair Display', serif;">{{ $destination->name }}</h1>
                    <div class="d-flex justify-content-center align-items-center text-white-50">
                        <span class="mx-3"><i class="fas fa-map-marked-alt text-warning mr-2"></i> {{ $packages->total() }} Available Tours</span>
                        <span class="mx-3"><i class="fas fa-globe-africa text-warning mr-2"></i> East Africa</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    {{-- Destination Description --}}
                    <div class="bg-white p-4 p-md-5 mb-5 shadow-sm" style="border-radius: 25px;">
                        <h2 class="mb-4" style="font-family: 'Playfair Display', serif; font-weight: 700;">About {{ $destination->name }}</h2>
                        <div class="lead text-muted mb-4">
                            {!! $destination->description !!}
                        </div>
                    </div>

                    {{-- Dynamic Packages List --}}
                    <h3 class="mb-4 font-weight-bold" style="font-family: 'Playfair Display', serif;">Featured Safaris in {{ $destination->name }}</h3>
                    <div class="row">
                        @forelse($packages as $package)
                            <div class="col-md-6 mb-4">
                                <div class="card package-card shadow-sm h-100">
                                    <img src="{{ $package->photo ? asset('storage/' . $package->photo->path) : asset('front/images/safari_default.jpg') }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-warning small font-weight-bold">{{ $package->duration_days }} Days</span>
                                            <span class="font-weight-bold text-dark">${{ number_format($package->price) }}</span>
                                        </div>
                                        <h5 class="font-weight-bold">{{ $package->name }}</h5>
                                        <p class="text-muted small">{{ Str::limit(strip_tags($package->description), 80) }}</p>
                                        <a href="{{ route('safaris.show', $package->slug) }}" class="btn btn-outline-warning btn-sm btn-block mt-3">View Itinerary</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info border-0 shadow-sm rounded-lg">
                                    We are currently updating our packages for {{ $destination->name }}. Please check back soon!
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $packages->links() }}
                    </div>
                </div>

                {{-- Sidebar: Lead Capture --}}
                <div class="col-lg-4">
                    <div class="sticky-top">
                        <div class="card sidebar-card shadow-lg mb-4">
                            <div class="bg-dark p-4 text-center">
                                <h4 class="text-white mb-0">Plan Your Trip</h4>
                                <span class="text-white-50 small">Expert advice for {{ $destination->name }}</span>
                            </div>
                            <div class="card-body p-4">
                                <form action="#">
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">Full Name</label>
                                        <input type="text" class="form-control border-0 bg-light" placeholder="e.g. John Doe">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="small font-weight-bold">Interested In</label>
                                        <select class="form-control border-0 bg-light">
                                            @foreach($packages as $p)
                                                <option>{{ $p->name }}</option>
                                            @endforeach
                                            <option>Custom Itinerary</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-warning btn-block btn-lg py-3 shadow" style="border-radius: 15px; font-weight: 800;">GET FREE QUOTE</button>
                                </form>
                            </div>
                        </div>

                        <div class="bg-white p-4 shadow-sm" style="border-radius: 20px;">
                            <h5 class="font-weight-bold mb-3 small uppercase">Destination Highlights</h5>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Local Wildlife Expert</li>
                                <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Hand-picked Lodges</li>
                                <li><i class="fas fa-check text-success mr-2"></i> Private 4x4 Transport</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>