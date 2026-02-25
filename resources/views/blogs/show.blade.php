@extends('layouts.app')

@section('content')
<section class="post-hero" style="padding: 140px 0 60px; background: linear-gradient(to bottom, var(--deep), #0a1118);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center">
                <div class="mb-4">
                    <span class="badge rounded-pill px-3 py-2 text-uppercase" style="background: var(--accent); color: #000; letter-spacing: 1px; font-size: 0.75rem; font-weight: 800;">
                        {{ $post->category->name }}
                    </span>
                    <span class="text-white-50 ms-3 small fw-bold">
                        <i class="far fa-calendar-alt me-1 text-accent"></i> {{ $post->published_at->format('M d, Y') }}
                    </span>
                </div>

                <h1 class="display-3 fw-bold text-white mb-4" style="line-height: 1.1; letter-spacing: -1px;">
                    {{ $post->title }}
                </h1>

                <div class="d-flex align-items-center justify-content-center mb-5">
                    <div class="rounded-circle bg-accent d-flex align-items-center justify-content-center text-dark fw-bold me-3" style="width: 45px; height: 45px; box-shadow: 0 0 15px rgba(0, 224, 255, 0.3);">
                        {{ substr($post->author->name ?? 'F', 0, 1) }}
                    </div>
                    <div class="text-start">
                        <p class="mb-0 text-white fw-bold">By {{ $post->author->name }}</p>
                        <p class="mb-0 text-accent small fw-bold" style="font-size: 0.7rem; text-uppercase; letter-spacing: 1px;">Expert Contributor</p>
                    </div>
                </div>

                <div class="rounded-4 overflow-hidden shadow-lg border" style="border: 1px solid rgba(255,255,255,0.1) !important;">
                    <img src="{{ $post->featured_image ? asset('storage/'.$post->featured_image) : asset('images/Bold Moves.jpeg') }}" 
                         alt="{{ $post->title }}" class="img-fluid w-100" style="max-height: 550px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="post-body pb-5" style="background: #0a1118;">
    <div class="container">
        <div class="row justify-content-center">
            
            {{-- 1. SOCIAL SHARE SIDEBAR (Desktop) --}}
            <div class="col-lg-1 d-none d-lg-block">
                <div class="sticky-top" style="top: 150px; z-index: 10;">
                    <div class="d-flex flex-column align-items-center gap-3">
                        <small class="text-white-50 text-uppercase fw-bold mb-2" style="writing-mode: vertical-rl; font-size: 0.65rem; letter-spacing: 2px;">Share</small>
                        
                        {{-- Twitter/X --}}
<a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($post->title) }}" 
   target="_blank" class="share-btn twitter">
    <i class="fab fa-twitter"></i>
</a>

{{-- LinkedIn --}}
<a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}" 
   target="_blank" class="share-btn linkedin">
    <i class="fab fa-linkedin-in"></i>
</a>

{{-- WhatsApp --}}
<a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" 
   target="_blank" class="share-btn whatsapp">
    <i class="fab fa-whatsapp"></i>
</a>
                    </div>
                </div>
            </div>

            {{-- 2. MAIN ARTICLE CONTENT --}}
            <div class="col-lg-8">
                <div class="article-content mb-5">
                    {!! $post->content !!}
                </div>

              {{-- MOBILE SHARE BUTTONS (Visible only on mobile) --}}
<div class="d-lg-none py-4 border-top border-secondary">
    <p class="text-white fw-bold small mb-3">SHARE THIS INSIGHT:</p>
    <div class="d-flex flex-wrap gap-2">
        {{-- Twitter --}}
        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($post->title) }}" 
           target="_blank" 
           class="btn btn-dark btn-sm rounded-pill px-3 border-secondary hover-accent-btn">
            <i class="fab fa-twitter me-1"></i> Twitter
        </a>

        {{-- LinkedIn --}}
        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ url()->current() }}" 
           target="_blank" 
           class="btn btn-dark btn-sm rounded-pill px-3 border-secondary hover-accent-btn">
            <i class="fab fa-linkedin-in me-1"></i> LinkedIn
        </a>

        {{-- WhatsApp --}}
        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" 
           target="_blank" 
           class="btn btn-dark btn-sm rounded-pill px-3 border-secondary hover-accent-btn">
            <i class="fab fa-whatsapp me-1"></i> WhatsApp
        </a>
    </div>
</div>

                {{-- AUTHOR BIO --}}
                <div class="mt-5 p-5 rounded-4 border author-card">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                             <div class="rounded-circle bg-accent d-inline-flex align-items-center justify-content-center text-dark fw-bold" style="width: 70px; height: 70px; font-size: 1.5rem;">
                                {{ substr($post->author->name ?? 'F', 0, 1) }}
                            </div>
                        </div>
                        <div class="col-md-10">
                            <h5 class="text-white fw-bold mb-1">{{ $post->author->name }}</h5>
                            <p class="text-white-50 small mb-0">Senior Market Strategist at FameOceans.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-1"></div>
        </div>
    </div>
</section>

<style>
    /* Premium Share Buttons Styling */
    .share-btn {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .share-btn:hover {
        transform: translateY(-5px);
        background: var(--accent);
        color: #000;
        border-color: var(--accent);
        box-shadow: 0 5px 15px rgba(0, 224, 255, 0.4);
    }

    .author-card {
        background: linear-gradient(145deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
        border-color: rgba(255,255,255,0.08) !important;
    }

    :root {
        --accent: #00e0ff; /* Ensure this is your bright cyan */
    }

    /* THE READABILITY ENGINE */
    .article-content {
        color: rgba(255, 255, 255, 0.88); /* Much brighter than text-white-50 */
        font-size: 1.2rem;
        line-height: 1.85;
        font-weight: 400;
        font-family: 'Inter', 'Segoe UI', sans-serif; /* Use a clean sans-serif */
    }

    .article-content h2, .article-content h3 {
        color: #ffffff !important;
        margin-top: 3rem;
        margin-bottom: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .article-content p {
        margin-bottom: 1.8rem;
    }

    .article-content blockquote {
        border-left: 5px solid var(--accent);
        padding: 2.5rem;
        margin: 3rem 0;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 0 1.5rem 1.5rem 0;
        font-style: italic;
        color: #fff;
        font-size: 1.4rem;
    }

    .text-accent { color: var(--accent) !important; }

    /* Fix for lists */
    .article-content ul, .article-content ol {
        margin-bottom: 2rem;
        padding-left: 1.5rem;
    }
    .article-content li {
        margin-bottom: 0.8rem;
    }
</style>
@endsection