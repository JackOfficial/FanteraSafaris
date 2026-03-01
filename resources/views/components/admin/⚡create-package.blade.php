<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SafariPackage;
use App\Models\SafariCategory;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

new class extends Component {
    use WithFileUploads;

    // Fields
    public $name, $price, $discount_per_person = 0, $duration_days = 1;
    public $destination_ids = [], $status = 'draft', $safari_category_ids = [], $description; 
    public $featured_image, $gallery_images = [], $itinerary = [];

    public function mount() {
        if (empty($this->itinerary)) { $this->addDay(); }
    }

    public function addDay() {
        $this->itinerary[] = [
            'day_number' => count($this->itinerary) + 1,
            'title' => '', 'activities' => '',
            'meals' => 'Breakfast, Lunch, Dinner', 'accommodation' => ''
        ];
        $this->duration_days = count($this->itinerary);
        // Tell Alpine to open the new day index instantly
        $this->dispatch('day-added', index: count($this->itinerary) - 1);
    }

    public function duplicateDay($index) {
        $dayToCopy = $this->itinerary[$index];
        array_splice($this->itinerary, $index + 1, 0, [$dayToCopy]);
        $this->reorderDays();
        $this->dispatch('day-added', index: $index + 1);
    }

    public function removeDay($index) {
        array_splice($this->itinerary, $index, 1);
        $this->reorderDays();
    }

    protected function reorderDays() {
        $this->itinerary = array_values($this->itinerary);
        foreach ($this->itinerary as $k => $v) {
            $this->itinerary[$k]['day_number'] = $k + 1;
        }
        $this->duration_days = count($this->itinerary);
    }

    public function removeGalleryImage($index) {
        array_splice($this->gallery_images, $index, 1);
    }

    public function save() {
        $this->validate([
            'name' => 'required|string|max:255|unique:safari_packages,name',
            'price' => 'required|numeric|min:0',
            'discount_per_person' => 'required|numeric|min:0|max:100',
            'destination_ids' => 'required|array|min:1',
            'safari_category_ids' => 'required|array|min:1',
            'description' => 'required|min:20',
            'featured_image' => 'required|image|max:2048',
        ]);

        DB::transaction(function () {
            $package = SafariPackage::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'price' => $this->price,
                'discount_per_person' => $this->discount_per_person,
                'duration_days' => count($this->itinerary),
                'status' => $this->status,
                'description' => $this->description,
            ]);

            $package->destinations()->sync($this->destination_ids);
            $package->categories()->sync($this->safari_category_ids);

            $package->photos()->create([
                'path' => $this->featured_image->store('safaris/featured', 'public'),
                'type' => 'featured'
            ]);

            foreach ($this->gallery_images as $image) {
                $package->photos()->create([
                    'path' => $image->store('safaris/gallery', 'public'),
                    'type' => 'gallery'
                ]);
            }

            foreach ($this->itinerary as $day) {
                $package->itineraries()->create($day);
            }
        });

        return redirect()->route('admin.packages.index')->with('success', 'Safari Package created!');
    }
}; ?>

<div x-data="{ 
    basePrice: @entangle('price'), 
    discount: @entangle('discount_per_person'),
    activeDay: 0,
    calculate(people) {
        if(!this.basePrice) return 0;
        let totalDiscountPercentage = (people - 1) * this.discount;
        let pricePerPerson = this.basePrice * (1 - (totalDiscountPercentage / 100));
        let finalPrice = Math.max(pricePerPerson, this.basePrice * 0.5);
        return (finalPrice * people).toLocaleString();
    }
}" @day-added.window="activeDay = $event.detail.index">

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .sticky-top-card { position: sticky; top: 20px; z-index: 10; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .btn-pink:hover { background-color: #be185d; color: white; }
            .text-pink { color: #e83e8c !important; }
            .price-preview-box { background: #fdf2f7; border: 1px solid #f9a8d4; border-radius: 12px; }
            .featured-upload-box { width: 100%; height: 200px; border: 2px dashed #cbd5e0; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #f8fafc; cursor: pointer; }
            .gallery-item { position: relative; width: 80px; height: 80px; border-radius: 8px; overflow: hidden; }
            .gallery-remove { position: absolute; top: 2px; right: 2px; background: #e83e8c; color: white; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-size: 9px; cursor: pointer; }
            [x-cloak] { display: none !important; }
            .rotate-180 { transform: rotate(180deg); transition: 0.3s; }
        </style>
    @endpush

    <section class="content pt-4">
        <div class="container-fluid">
            <form wire:submit="save">
                <div class="row">
                    {{-- LEFT COLUMN: Pricing & Meta --}}
                    <div class="col-md-5">
                        <div class="card shadow-sm mb-4 sticky-top-card">
                            <div class="card-body">
                                <h6 class="font-weight-bold text-uppercase small text-muted mb-3">Core Details</h6>
                                
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="small font-weight-bold">PRICE ($)</label>
                                        <input type="number" wire:model.live="price" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="small font-weight-bold">DISC % / PERSON</label>
                                        <input type="number" wire:model.live="discount_per_person" class="form-control">
                                    </div>
                                </div>

                                {{-- Extended Price Preview (1-6 People) --}}
                                <div class="price-preview-box p-3 mb-4" x-show="basePrice > 0" x-transition>
                                    <h6 class="small font-weight-bold text-pink mb-2">QUICK PRICE ESTIMATOR</h6>
                                    <table class="table table-sm table-borderless mb-0" style="font-size: 12px;">
                                        <template x-for="n in [1, 2, 3, 4, 6]">
                                            <tr class="border-bottom-0">
                                                <td class="py-1" x-text="n + (n==1?' Person':' People')"></td>
                                                <td class="py-1 font-weight-bold text-right" x-text="'$' + calculate(n)"></td>
                                            </tr>
                                        </template>
                                    </table>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">DESTINATIONS</label>
                                    <select wire:model="destination_ids" class="form-control" multiple style="height: 120px;">
                                        @foreach(\App\Models\Destination::all() as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="small font-weight-bold">STATUS</label>
                                    <select wire:model="status" class="form-control">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN: Media & Itinerary --}}
                    <div class="col-md-7">
                        {{-- Media Section --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <label class="small font-weight-bold">FEATURED IMAGE</label>
                                <div x-data="{ preview: null }" class="mb-3">
                                    <input type="file" id="feat_img" wire:model="featured_image" class="d-none" @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }">
                                    <div class="featured-upload-box" onclick="document.getElementById('feat_img').click()">
                                        <template x-if="preview"><img :src="preview" class="w-100 h-100" style="object-fit: cover;"></template>
                                        <template x-if="!preview"><span class="text-muted small">Click to upload cover</span></template>
                                    </div>
                                </div>

                                <label class="small font-weight-bold">GALLERY IMAGES</label>
                                <div class="d-flex flex-wrap" style="gap: 8px;">
                                    @foreach($gallery_images as $idx => $img)
                                        <div class="gallery-item border">
                                            <img src="{{ $img->temporaryUrl() }}" class="w-100 h-100" style="object-fit: cover;">
                                            <div class="gallery-remove" wire:click="removeGalleryImage({{ $idx }})">×</div>
                                        </div>
                                    @endforeach
                                    <label class="gallery-item border d-flex align-items-center justify-content-center bg-light" style="cursor:pointer;">
                                        <i class="fas fa-plus text-muted"></i>
                                        <input type="file" wire:model.live="gallery_images" multiple class="d-none">
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Itinerary Section --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 font-weight-bold">Itinerary Journey</h5>
                            <button type="button" wire:click="addDay" class="btn btn-sm btn-pink rounded-pill px-3">Add Day</button>
                        </div>

                        <div class="itinerary-wrapper">
                            @foreach($itinerary as $index => $day)
                                <div class="card mb-2 border-0 shadow-sm" wire:key="day-{{ $index }}">
                                    <div class="p-3 bg-white d-flex align-items-center" 
                                         @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})" 
                                         style="cursor:pointer; border-radius: 8px;">
                                        <div class="bg-pink text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">{{ $day['day_number'] }}</div>
                                        <div class="flex-grow-1 font-weight-bold small text-dark">{{ $day['title'] ?: 'New Safari Day' }}</div>
                                        <i class="fas fa-chevron-down text-muted" :class="activeDay === {{ $index }} ? 'rotate-180' : ''"></i>
                                    </div>

                                    <div x-show="activeDay === {{ $index }}" x-collapse x-cloak>
                                        <div class="card-body bg-light border-top">
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-muted">TITLE</label>
                                                <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control">
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-muted">ACTIVITIES</label>
                                                <textarea wire:model.blur="itinerary.{{ $index }}.activities" class="form-control" rows="3"></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="small font-weight-bold text-muted">LODGE</label>
                                                    <input type="text" wire:model.blur="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-6">
                                                    <label class="small font-weight-bold text-muted">MEALS</label>
                                                    <input type="text" wire:model.blur="itinerary.{{ $index }}.meals" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="text-right mt-3">
                                                <button type="button" wire:click="duplicateDay({{ $index }})" class="btn btn-link btn-sm text-muted">Duplicate</button>
                                                <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-link btn-sm text-danger">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Trix Editor --}}
                        <div class="card shadow-sm border-0 mt-4">
                            <div class="card-header bg-white small font-weight-bold">PACKAGE OVERVIEW</div>
                            <div class="card-body p-0" wire:ignore>
                                <div x-data="{ value: @entangle('description') }" @trix-change="value = $event.target.value">
                                    <trix-editor class="trix-content border-0"></trix-editor>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block mt-4 shadow-lg rounded-pill py-3 font-weight-bold">
                            SAVE SAFARI PACKAGE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
        <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @endpush
</div>