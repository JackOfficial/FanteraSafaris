<x-layout 
    title="Safari Tips & Articles | Fantera Safaris Blog"
    metaDescription="Expert advice, packing guides, and wildlife stories from the heart of East Africa. Stay updated with Fantera Safaris."
>
    <div class="hero-wrap js-fullheight" style="background-image: url('{{ asset('front/images/bg_4.jpg') }}');">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-center" data-scrollax-parent="true">
                <div class="col-md-9 ftco-animate text-center" data-scrollax="properties: { translateY: '70%' }">
                    <p class="breadcrumbs" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">
                        <span class="mr-2"><a href="/">Home</a></span> <span>Blog</span>
                    </p>
                    <h1 class="mb-3 bread" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">Tips &amp; Articles</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="ftco-section bg-light">
        <div class="container">
            <div class="row d-flex">
                @php
                // Mock data for the blog posts
                $posts = [
                    ['img' => 'image_1.jpg', 'tag' => 'Tips, Travel', 'title' => 'The Ultimate Guide to Gorilla Trekking in Bwindi', 'date' => 'Feb 12, 2026'],
                    ['img' => 'image_2.jpg', 'tag' => 'Culture', 'title' => 'Meeting the Maasai: A Cultural Journey in Kenya', 'date' => 'Feb 05, 2026'],
                    ['img' => 'image_3.jpg', 'tag' => 'Wildlife', 'title' => 'What to Pack for Your First African Safari', 'date' => 'Jan 28, 2026'],
                    ['img' => 'image_4.jpg', 'tag' => 'Travel', 'title' => 'Top 5 Luxury Lodges in the Serengeti', 'date' => 'Jan 20, 2026'],
                    ['img' => 'image_5.jpg', 'tag' => 'Tips', 'date' => 'Jan 15, 2026', 'title' => 'Photography Tips for Capturing the Big Five'],
                    ['img' => 'image_6.jpg', 'tag' => 'Culture', 'date' => 'Jan 08, 2026', 'title' => 'Understanding the Great Migration Cycle'],
                    ['img' => 'image_7.jpg', 'tag' => 'Travel', 'date' => 'Jan 02, 2026', 'title' => 'Zanzibar: The Perfect Post-Safari Relaxation'],
                    ['img' => 'image_8.jpg', 'tag' => 'Wildlife', 'date' => 'Dec 20, 2025', 'title' => 'Conservation Efforts: Protecting the Rhinos']
                ];
                @endphp

                @foreach($posts as $post)
                <div class="col-md-3 d-flex ftco-animate">
                    <div class="blog-entry align-self-stretch">
                        <a href="/blog-details" class="block-20" style="background-image: url('{{ asset('front/images/' . $post['img']) }}');">
                        </a>
                        <div class="text p-4 d-block">
                            <span class="tag text-warning" style="font-size: 12px; font-weight: 700; text-uppercase: uppercase;">{{ $post['tag'] }}</span>
                            <h3 class="heading mt-3" style="font-size: 18px;"><a href="#">{{ $post['title'] }}</a></h3>
                            <div class="meta mb-3">
                                <div><a href="#">{{ $post['date'] }}</a></div>
                                <div><a href="#">Admin</a></div>
                                <div><a href="#" class="meta-chat"><span class="icon-chat"></span> 3</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row mt-5">
                <div class="col text-center">
                    <div class="block-27">
                        <ul>
                            <li><a href="#">&lt;</a></li>
                            <li class="active"><span>1</span></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">&gt;</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>