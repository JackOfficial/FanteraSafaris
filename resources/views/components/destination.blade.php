  @props([
    'image', 
    'title', 
    'listing', 
    'link' => '#'
])

  <div class="destination">
                                <a href="#" class="img d-flex justify-content-center align-items-center" style="background-image: url('{{ asset($image) }}');">
                                    <div class="icon d-flex justify-content-center align-items-center">
                                        <span class="icon-search2"></span>
                                    </div>
                                </a>
                                <div class="text p-3">
                                    <h3><a href="{{ $link }}">{{ $title }}</a></h3>
                                    <span class="listing">{{ $listing }}</span>
                                </div>
</div>