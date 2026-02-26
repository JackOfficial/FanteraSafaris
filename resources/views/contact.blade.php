<x-layout 
    title="Contact Us"
    metaDescription="Get in touch with Fantera Safaris. Plan your next adventure in Uganda, Kenya, or Tanzania with our expert team."
>
    <x-slot name="styles">
        <style>
            /* Smooth Hero adjustment */
            .hero-wrap.hero-bread { height: 450px !important; }
            
            /* Info Cards */
            .contact-info-card {
                padding: 30px;
                background: #fff;
                border-radius: 20px;
                transition: 0.3s;
                height: 100%;
                border: 1px solid #f0f0f0;
            }
            .contact-info-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            }
            .icon-box {
                width: 60px;
                height: 60px;
                background: rgba(255, 193, 7, 0.1);
                color: #ffc107;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                margin-bottom: 20px;
            }

            /* Form Modernization */
            .contact-form-wrapper {
                background: #fff;
                border-radius: 30px;
                padding: 40px;
            }
            .form-control {
                height: 55px !important;
                background: #f9f9f9 !important;
                border: 1px solid #f0f0f0 !important;
                border-radius: 12px;
                padding-left: 20px;
            }
            .form-control:focus {
                border-color: #ffc107 !important;
                background: #fff !important;
            }
            textarea.form-control { height: auto !important; }

            #map { 
                border-radius: 30px; 
                overflow: hidden; 
                box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
            }
        </style>
    </x-slot>

    <div class="hero-wrap" style="background-image: url('{{ asset('front/images/bg_2.jpg') }}'); height: 50vh; background-size: cover; background-position: center; position: relative;">
        <div class="overlay" style="position: absolute; inset: 0; background: rgba(0,0,0,0.45);"></div>
        <div class="container h-100">
            <div class="row no-gutters h-100 align-items-center justify-content-center text-center">
                <div class="col-md-9" style="z-index: 2;">
                    <p class="breadcrumbs mb-2">
                        <span class="mr-2"><a href="/" class="text-white-50">Home</a></span> 
                        <span class="text-white">Contact</span>
                    </p>
                    <h1 class="display-4 font-weight-bold text-white" style="font-family: 'Playfair Display', serif;">Get In Touch</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section bg-light py-5">
        <div class="container">
            <div class="row mb-5 justify-content-center" style="margin-top: -100px;">
                <div class="col-md-3 mb-4">
                    <div class="contact-info-card text-center shadow-sm">
                        <div class="icon-box mx-auto"><i class="fas fa-map-marker-alt"></i></div>
                        <h6 class="font-weight-bold">Our Office</h6>
                        <p class="text-muted small">Kampala, Uganda<br>Plot 45, Safari Plaza</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="contact-info-card text-center shadow-sm">
                        <div class="icon-box mx-auto"><i class="fas fa-phone"></i></div>
                        <h6 class="font-weight-bold">Phone Number</h6>
                        <p class="text-muted small"><a href="tel:256000000000" class="text-muted">+256 700 000 000</a></p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="contact-info-card text-center shadow-sm">
                        <div class="icon-box mx-auto"><i class="fas fa-paper-plane"></i></div>
                        <h6 class="font-weight-bold">Email Address</h6>
                        <p class="text-muted small"><a href="mailto:info@fanterasafaris.com" class="text-muted">info@fanterasafaris.com</a></p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="contact-info-card text-center shadow-sm">
                        <div class="icon-box mx-auto"><i class="fas fa-clock"></i></div>
                        <h6 class="font-weight-bold">Working Hours</h6>
                        <p class="text-muted small">Mon - Sat: 9am - 6pm<br>Sun: Online Support</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row no-gutters contact-form-wrapper shadow-lg">
                        <div class="col-md-6 p-4 p-md-5">
                            <h3 class="mb-4 font-weight-bold">Message Us</h3>
                            <form action="#">
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control" placeholder="Your Full Name">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="email" class="form-control" placeholder="Email Address">
                                </div>
                                <div class="form-group mb-3">
                                    <input type="text" class="form-control" placeholder="Subject">
                                </div>
                                <div class="form-group mb-4">
                                    <textarea name="" id="" cols="30" rows="4" class="form-control" placeholder="How can we help you plan your safari?"></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-warning btn-block py-3 font-weight-bold shadow-sm" style="border-radius: 12px;">SEND MESSAGE</button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-6 d-flex align-items-stretch">
                            <div id="map" class="w-100" style="min-height: 450px; background: #eee; position: relative;">
                                <div class="h-100 d-flex align-items-center justify-content-center flex-column text-muted">
                                    <i class="fas fa-map-marked-alt fa-3x mb-3 text-warning"></i>
                                    <p>Interactive Map Loading...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-slot:scripts>
        <script>
            // Note: Replace with actual Google Maps or Leaflet integration later
         
        </script>
    </x-slot:scripts>
</x-layout>