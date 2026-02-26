<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Fantera Safaris' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet"> 

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="{{ asset('front/images/FanteraSafaris_logo.png') }}" rel="icon">

    @include('partials.styles') 
    @livewireStyles
    
    <style>
        /* Specific styling for auth pages to center the forms */
        .auth-wrapper {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa; /* Light safari sand/grey */
        }
        .auth-card {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            background: white;
        }
    </style>
</head>

<body>
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>

    <div class="auth-wrapper py-5">
        <div class="container d-flex justify-content-center">
            <div class="auth-card">
                <div class="text-center mb-4">
                    <a href="/">
                        <img src="{{ asset('front/images/FanteraSafaris_logo.png') }}" alt="Logo" style="height: 60px;">
                    </a>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>

    <div class="container-fluid copyright py-4">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-4 text-center text-md-start mb-md-0">
                    <span class="text-body">
                        <i class="fas fa-copyright text-light me-2"></i>Fantera Safaris, {{ date('Y') }}
                    </span>
                </div>
                <div class="col-md-4 text-center">
                    <div class="d-flex align-items-center justify-content-center">
                        <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn-hover-color btn-square text-white me-0"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-md-4 text-center text-md-end text-body">
                    Designed By <a class="border-bottom" href="#">Tonny Jack</a>
                </div>
            </div>
        </div>
    </div>

    @include('partials.scripts')
    @livewireScripts
</body>
</html>