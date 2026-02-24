@section('content')
<style>
    /* 2026 Custom Utilities for BS4 */
    .hero-wrap { height: 75vh; min-height: 600px; position: relative; overflow: hidden; }
    .hero-content { position: relative; z-index: 2; color: #fff; text-align: center; }
    
    /* Soft Elevation instead of Borders */
    .destination-card { 
        border: none; 
        border-radius: 24px; 
        transition: all 0.4s ease-in-out; 
        background: #fff;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .destination-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    
    .img-container { height: 280px; position: relative; overflow: hidden; }
    .img-container img { transition: transform 0.6s; width: 100%; height: 100%; object-fit: cover; }
    .destination-card:hover img { transform: scale(1.1); }

    /* Price Badge */
    .price-badge {
        position: absolute; bottom: 20px; right: 20px;
        background: #1a1a1a; color: #fff; padding: 10px 20px;
        border-radius: 15px; font-weight: 700;
    }

    /* Modern Glassmorphic Sidebar */
    .sidebar-filter {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 30px;
        padding: 30px;
        border: 1px solid rgba(0,0,0,0.05);
        position: sticky; top: 100px;
    }
    
    .btn-safari { 
        background: #1a1a1a; color: #fff; border-radius: 12px; 
        padding: 15px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;
    }
    .btn-safari:hover { background: #00d084; color: #fff; }
</style>

<div class="hero-wrap d-flex align-items-center justify-content-center" style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('front/images/zanzibar_beach.jpg') }}') no-repeat center center/cover;">
    <div class="hero-content">
        <h6 class="text-uppercase tracking-wider mb-3" style="letter-spacing: 4px;">Luxury Safaris</h6>
        <h1 class="display-3 font-weight-bold mb-4">Explore East Africa</h1>
        <p class="lead opacity-75">Curated wildlife experiences for the modern explorer.</p>
    </div>
</div>

<section class="ftco-section bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-5">
                <div class="sidebar-filter shadow-sm">
                    <h5 class="font-weight-bold mb-4">Refine Search</h5>
                    <form action="#">
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted uppercase">Destination</label>
                            <select class="form-control border-0 bg-light rounded-pill px-4">
                                <option>All Regions</option>
                                <option>Uganda</option>
                                <option>Kenya</option>
                            </select>
                        </div>
                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted uppercase">Travel Date</label>
                            <input type="text" class="form-control border-0 bg-light rounded-pill px-4" placeholder="Anytime">
                        </div>
                        <div class="form-group mb-4">
                            <div class="d-flex justify-content-between">
                                <label class="small font-weight-bold text-muted uppercase">Budget</label>
                                <span class="small font-weight-bold text-dark">$10,000</span>
                            </div>
                            <input type="range" class="custom-range accent-dark" min="500" max="10000">
                        </div>
                        <button class="btn btn-safari btn-block mt-4">Update Results</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="row">
                    @foreach($destinations as $dest)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="destination-card">
                            <div class="img-container">
                                <img src="{{ asset('front/images/'.$dest->image) }}" alt="{{ $dest->name }}">
                                <div class="price-badge">${{ number_format($dest->price) }}</div>
                                <div class="position-absolute p-3" style="top:0; left:0;">
                                    <span class="badge badge-light rounded-pill px-3 py-2 text-uppercase" style="font-size: 10px;">{{ $dest->country }}</span>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="mb-2">
                                    <span class="text-warning small"><i class="fa fa-star"></i> 5.0</span>
                                </div>
                                <h5 class="font-weight-bold text-dark mb-3">{{ $dest->name }}</h5>
                                <p class="text-muted small mb-4">{{ Str::limit($dest->description, 60) }}</p>
                                <hr class="border-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small text-muted"><i class="fa fa-clock-o mr-1"></i> 7 Days</span>
                                    <a href="{{ route('tours.show', $dest->slug) }}" class="font-weight-bold text-dark">Details <i class="fa fa-arrow-right ml-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="row mt-5">
                    <div class="col text-center">
                        <div class="pagination-custom">
                            {{ $destinations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection