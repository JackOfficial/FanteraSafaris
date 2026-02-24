<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>@yield('title', 'Luxury Uganda Safaris & East Africa Tours | Fantera Safaris')</title>
    <meta name="description" content="@yield('meta_description', 'Experience authentic African wildness with Fantera Safaris. Specialized Gorilla trekking in Uganda, Rwanda primates, and Serengeti migrations.')">
    
    <meta name="keywords" content="@yield('meta_keywords', 'Uganda Safaris, Gorilla Trekking, East Africa Tours, Kampala Tour Operator, Luxury Safaris')">
    
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="@yield('title', 'Explore the Pearl of Africa with Fantera Safaris')">
    <meta property="og:description" content="@yield('meta_description', 'Experience authentic African wildness with Fantera Safaris.')">
    <meta property="og:image" content="@yield('og_image', asset('front/images/FanteraSafaris_logo.png'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <link href="{{ asset('front/images/FanteraSafaris_logo.png') }}" rel="icon">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700" rel="stylesheet"> 
    <!-- Font Awesome 5 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    @include('partials.styles') 
    @stack('styles')
</head>
<body>
    @include('partials.nav')

    @yield('content')

    @include('partials.footer')
    
    @include('partials.scripts')
    @stack('scripts')
</body>
</html>