@extends('layouts.app')
@push('styles')
<style>
    /* Styling for the title truncation */
    .blog-card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3rem; /* Keeps cards aligned */
    }

    /* Hover Effects */
    .glass-card:hover {
        transform: translateY(-8px);
        background: rgba(255,255,255,0.06) !important;
        border-color: var(--accent) !important;
    }

    .zoom-img {
        transition: transform 0.5s ease;
    }

    .glass-card:hover .zoom-img {
        transform: scale(1.1);
    }

    .hover-accent-btn:hover {
        background-color: var(--accent) !important;
        color: var(--deep) !important;
        border-color: var(--accent) !important;
    }
</style>
@endpush

@section('content')

<div class="container-fluid mt-4 pt-5">
    <div class="row px-xl-5">
        <div class="col-12">
            <nav class="breadcrumb bg-dark bg-opacity-25 mb-30 mt-5 p-3 rounded" style="backdrop-filter: blur(10px);">
                <a class="breadcrumb-item text-accent text-decoration-none" href="/">Home</a>
                <span class="breadcrumb-item active text-white">Blog</span>
            </nav>
        </div>
    </div>
</div>
<div class="container-fluid mb-5">
    <div class="row px-xl-5">

        <div class="col-lg-3 col-md-4">

            <div class="glass-card p-4 mb-30 border-0 shadow-sm" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(10px);">
                <h5 class="section-title position-relative text-uppercase mb-3 text-white">
                    <span class="pr-3">Search</span>
                </h5>
                <form action="{{ route('post.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control bg-transparent text-white border-secondary" placeholder="Search insights..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-accent" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="glass-card p-4 mb-30 border-0 shadow-sm" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(10px);">
                <h5 class="section-title position-relative text-uppercase mb-3 text-white">
                    <span class="pr-3">Categories</span>
                </h5>
                <ul class="list-unstyled mb-0">
                    @foreach($categories as $category)
                    <li class="d-flex justify-content-between align-items-center mb-3">
                        <a class="text-white-50 text-decoration-none hover-accent" href="{{ route('post.category', $category->slug) }}">
                            {{ $category->name }}
                        </a>
                        <span class="badge border border-secondary text-muted font-weight-normal">{{ $category->posts_count ?? '0' }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div class="glass-card p-4 mb-30 border-0 shadow-sm" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(10px);">
                <h5 class="section-title position-relative text-uppercase mb-3 text-white">
                    <span class="pr-3">Recent Posts</span>
                </h5>

                @foreach ($recent_posts as $recent)
                <div class="media mb-3 d-flex">
                    <img src="{{ $recent->featured_image ? asset('storage/'.$recent->featured_image) : asset('images/placeholder.jpg') }}" 
                         class="mr-3 rounded" style="width: 70px; height: 50px; object-fit: cover;">
                    <div class="media-body ms-3">
                        <a class="text-white text-decoration-none" href="{{ route('post.show', $recent->slug) }}">
                            <h6 class="mt-0 text-truncate small">{{ Str::limit($recent->title, 25) }}</h6>
                        </a>
                        <small class="text-muted" style="font-size: 11px;">
                            <i class="fa fa-calendar text-accent me-1"></i> {{ $recent->published_at?->format('d M, Y') }}
                        </small>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
        <div class="col-lg-9 col-md-8">
    <div class="row pb-3">

        @forelse ($posts as $post)
        <div class="col-lg-4 col-md-6 col-sm-6 pb-4">
            <div class="glass-card h-100 p-0 overflow-hidden border-0 group-hover-effect" 
                 style="background: rgba(255,255,255,0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.08)!important; transition: 0.3s;">
                
                {{-- Featured Image & Category Badge --}}
                <div class="position-relative overflow-hidden" style="height: 200px;">
                    <img src="{{ $post->featured_image ? asset('storage/'.$post->featured_image) : asset('images/Bold Moves.jpeg') }}" 
                         alt="{{ $post->title }}" 
                         class="w-100 h-100 object-fit-cover zoom-img">
                    
                    {{-- Category Ribbon/Badge --}}
                    @if($post->category)
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge rounded-pill px-3 py-2" style="background: var(--accent); color: var(--deep); font-size: 0.65rem; font-weight: 700; text-uppercase: uppercase; letter-spacing: 0.5px;">
                            {{ $post->category->name }}
                        </span>
                    </div>
                    @endif
                </div>
                
                {{-- Card Content --}}
                <div class="p-4 text-start">
                    <h5 class="text-white fw-bold mb-2 blog-card-title">{{ $post->title }}</h5>
                    
                    <small class="d-block mb-3" style="color: var(--accent); font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="fas fa-user me-1"></i>By {{ $post->author->name ?? 'Unknown' }} &nbsp;|&nbsp; 
                        <i class="fas fa-calendar-alt me-1"></i>{{ $post->published_at?->format('M d, Y') ?? '—' }}
                    </small>
                    
                    <p class="text-white-50 small mb-4" style="line-height: 1.6;">
                        {{ Str::limit($post->excerpt, 100) ?? Str::limit(strip_tags($post->content), 100) }}
                    </p>
                    
                    <a href="{{ route('post.show', $post->slug) }}" class="btn btn-sm btn-outline-light rounded-pill px-4 hover-accent-btn" style="font-size: 0.75rem;">
                        Read More <i class="fas fa-arrow-right ms-2" style="font-size: 0.7rem;"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">The tide is low. No insights found.</h4>
        </div>
        @endforelse

        {{-- Pagination --}}
        <div class="col-12 mt-5">
            <div class="d-flex justify-content-center custom-pagination">
                {{ $posts->links() }}
            </div>
        </div>

    </div>
</div>
        </div>
</div>
<style>
    .text-accent { color: var(--accent) !important; }
    .btn-outline-accent { color: var(--accent); border-color: var(--accent); }
    .btn-outline-accent:hover { background-color: var(--accent); color: #000; }
    .hover-accent:hover { color: var(--accent) !important; }

    .blog-card-title {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: 0.3s;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        background: rgba(255,255,255,0.06) !important;
        box-shadow: 0 10px 20px rgba(0,0,0,0.3) !important;
    }

    /* Override Laravel Pagination to match theme */
    .pagination .page-link { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: #fff; }
    .pagination .page-item.active .page-link { background: var(--accent); border-color: var(--accent); color: #000; }
</style>

@endsection