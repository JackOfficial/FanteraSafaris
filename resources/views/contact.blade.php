<x-layout 
    title="Contact Us"
    metaDescription="Get in touch with Fantera Safaris. Plan your next adventure in Uganda, Kenya, or Tanzania with our expert team."
>
    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/bg_2.jpg') }}');">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
                <div class="col-md-9 ftco-animate text-center" data-scrollax="properties: { translateY: '70%' }">
                    <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
                        <span class="mr-2"><a href="/">Home</a></span> <span>Contact</span>
                    </p>
                    <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Contact Us</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section contact-section ftco-degree-bg">
        <div class="container">
            <div class="row d-flex mb-5 contact-info">
                <div class="col-md-12 mb-4">
                    <h2 class="h4 font-weight-bold">Contact Information</h2>
                </div>
                <div class="w-100"></div>
                <div class="col-md-3">
                    <p><span>Address:</span> Kampala, Uganda</p>
                </div>
                <div class="col-md-3">
                    <p><span>Phone:</span> <a href="tel://256000000000">+ 256 ...</a></p>
                </div>
                <div class="col-md-3">
                    <p><span>Email:</span> <a href="mailto:info@fanterasafaris.com">info@fanterasafaris.com</a></p>
                </div>
                <div class="col-md-3">
                    <p><span>Website</span> <a href="#">fanterasafaris.com</a></p>
                </div>
            </div>

            <div class="row block-9">
                <div class="col-md-6 pr-md-5">
                    <form action="#">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Your Name">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Your Email">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Subject">
                        </div>
                        <div class="form-group">
                            <textarea name="message" id="message" cols="30" rows="7" class="form-control" placeholder="Message"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
                        </div>
                    </form>
                </div>

                <div class="col-md-6" id="map" style="min-height: 400px; background: #eee;">
                    </div>
            </div>
        </div>
    </section>

    <x-slot:scripts>
        <script>
            // Add Google Maps or Leaflet initialization here
        </script>
    </x-slot:scripts>
</x-layout>