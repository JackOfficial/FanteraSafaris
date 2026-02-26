@props([
    'title' => null,
    'metaDescription' => null,
    'metaKeywords' => null,
    'ogImage' => null,
    'styles' => null,
    'scripts' => null
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- SEO & Titles --}}
    <title>{{ $title ? $title . ' | Fantera Safaris' : 'Luxury Uganda Safaris & East Africa Tours | Fantera Safaris' }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Experience authentic African wildness with Fantera Safaris.' }}">
    <meta name="keywords" content="{{ $metaKeywords ?? 'Uganda Safaris, Gorilla Trekking, East Africa Tours' }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'Explore the Pearl of Africa' }}">
    <meta property="og:description" content="{{ $metaDescription ?? 'Experience authentic African wildness.' }}">
    <meta property="og:image" content="{{ $ogImage ?? asset('front/images/FanteraSafaris_logo.png') }}">

    {{-- Favicon & Fonts --}}
    <link href="{{ asset('front/images/FanteraSafaris_logo.png') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    {{-- Static & Dynamic Styles --}}
    @include('partials.styles') 
    {{ $styles }} 
</head>
<body>
    @include('partials.nav')

    <main>
        {{ $slot }} 
    </main>
    
    @include('partials.footer')
    
    {{-- Static & Dynamic Scripts --}}
    @include('partials.scripts')
    {{ $scripts }}
</body>
</html>