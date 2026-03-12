<div class="pt-5 mt-5 border-top">
    <h3 class="mb-5 font-weight-bold">{{ $package->reviews->count() }} Reviews</h3>
    
    <ul class="comment-list">
        @forelse($package->reviews as $review)
            <li class="comment mb-4 pb-4 border-bottom">
                <div class="vcard bio mr-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user_name) }}&background=random" alt="User Avatar" style="width: 50px; border-radius: 50%;">
                </div>
                <div class="comment-body">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h4 class="font-weight-bold mb-0" style="font-size: 18px;">{{ $review->user_name }}</h4>
                        <div class="star-rating text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="icon-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                    </div>
                    <div class="meta mb-2 text-muted small">{{ $review->created_at->format('M d, Y') }}</div>
                    <p class="text-secondary">{{ $review->comment }}</p>
                </div>
            </li>
        @empty
            <p class="text-muted italic">No reviews yet. Be the first to share your experience!</p>
        @endforelse
    </ul>
    
    <div class="comment-form-wrap pt-5">
        <h3 class="mb-4 font-weight-bold" style="font-size: 24px;">Leave a review</h3>
        <form action="#" class="p-4 bg-light rounded">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="small font-weight-bold">Name *</label>
                        <input type="text" class="form-control border-0" id="name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="rating" class="small font-weight-bold">Rating</label>
                        <select name="rating" id="rating" class="form-control border-0">
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Very Good</option>
                            <option value="3">3 - Good</option>
                            <option value="2">2 - Fair</option>
                            <option value="1">1 - Poor</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="message" class="small font-weight-bold">Message</label>
                <textarea name="" id="message" cols="30" rows="5" class="form-control border-0" placeholder="Share your adventure..."></textarea>
            </div>
            <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary py-3 px-4 font-weight-bold">Post Review</button>
            </div>
        </form>
    </div>
</div>