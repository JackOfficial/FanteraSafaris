<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero {
            background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)),
                        url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee') center/cover;
            color: #fff;
            padding: 120px 0;
        }
        .hero h1 {
            font-weight: 700;
        }
        .section-padding {
            padding: 80px 0;
        }
        .icon-box {
            font-size: 40px;
            color: #007bff;
        }
        .stats {
            background: #f8f9fa;
        }
        .cta {
            background: #007bff;
            color: #fff;
        }
        footer {
            background: #212529;
            color: #aaa;
        }
        footer a {
            color: #aaa;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand font-weight-bold" href="#">YourBrand</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero text-center">
    <div class="container">
        <h1 class="display-4">Building Impact Through Innovation</h1>
        <p class="lead mt-3">We create meaningful digital experiences that transform lives and businesses.</p>
        <a href="#" class="btn btn-primary btn-lg mt-4">Get Started</a>
        <a href="#" class="btn btn-outline-light btn-lg mt-4 ml-2">Learn More</a>
    </div>
</section>

<!-- ABOUT -->
<section class="section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <h2 class="font-weight-bold">Who We Are</h2>
                <p class="mt-3">
                    We are a purpose-driven organization focused on delivering sustainable solutions
                    through technology, creativity, and community empowerment.
                </p>
                <p>
                    Our mission is to innovate, inspire, and create real-world impact.
                </p>
            </div>
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d"
                     class="img-fluid rounded" alt="About Image">
            </div>
        </div>
    </div>
</section>

<!-- SERVICES -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="font-weight-bold">What We Do</h2>
            <p class="text-muted">Our core areas of expertise</p>
        </div>
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="icon-box mb-3">💡</div>
                <h5 class="font-weight-bold">Innovation</h5>
                <p>Creative and practical solutions designed for real impact.</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="icon-box mb-3">🌍</div>
                <h5 class="font-weight-bold">Community</h5>
                <p>Empowering people through education and engagement.</p>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="icon-box mb-3">🚀</div>
                <h5 class="font-weight-bold">Growth</h5>
                <p>Helping organizations and individuals reach their full potential.</p>
            </div>
        </div>
    </div>
</section>

<!-- STATS -->
<section class="stats section-padding text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4">
                <h2 class="font-weight-bold">250+</h2>
                <p>Projects Completed</p>
            </div>
            <div class="col-md-3 mb-4">
                <h2 class="font-weight-bold">120+</h2>
                <p>Happy Clients</p>
            </div>
            <div class="col-md-3 mb-4">
                <h2 class="font-weight-bold">15</h2>
                <p>Countries Reached</p>
            </div>
            <div class="col-md-3 mb-4">
                <h2 class="font-weight-bold">10+</h2>
                <p>Years Experience</p>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="section-padding">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="font-weight-bold">What People Say</h2>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p>"Professional, innovative, and truly impactful."</p>
                        <h6 class="font-weight-bold mt-3">— Client A</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p>"They transformed our vision into reality."</p>
                        <h6 class="font-weight-bold mt-3">— Client B</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p>"Highly recommended for impactful projects."</p>
                        <h6 class="font-weight-bold mt-3">— Client C</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta section-padding text-center">
    <div class="container">
        <h2 class="font-weight-bold">Ready to Make an Impact?</h2>
        <p class="mt-3">Let’s work together to build something meaningful.</p>
        <a href="#" class="btn btn-light btn-lg mt-3">Contact Us</a>
    </div>
</section>

<!-- FOOTER -->
<footer class="py-4">
    <div class="container text-center">
        <p class="mb-1">&copy; 2026 YourBrand. All rights reserved.</p>
        <small>
            <a href="#">Privacy Policy</a> · <a href="#">Terms of Service</a>
        </small>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
