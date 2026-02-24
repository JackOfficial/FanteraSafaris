<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', 'Luxury Uganda Safaris & East Africa Tours | Fantera Safaris')</title>
    <meta name="description" content="@yield('meta_description', 'Experience authentic African wildness with Fantera Safaris. Specialized Gorilla trekking in Uganda, Rwanda primates, and Serengeti migrations.')">
    <meta name="keywords" content="Uganda Safaris, Gorilla Trekking, East Africa Tours, Kampala Tour Operator, Luxury Safaris">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="Explore the Pearl of Africa with Fantera Safaris">
    <meta property="og:image" content="{{ asset('front/images/FanteraSafaris.png') }}">
    <link href="{{ asset('front/images/FanteraSafaris.png') }}" rel="icon">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700" rel="stylesheet"> @include('partials.styles') </head>
<body>
    @include('partials.nav')

    @yield('content')

    @include('partials.footer')
    @include('partials.scripts')
</body>
</html>