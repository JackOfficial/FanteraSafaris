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
    public $destination_id, $status = 'draft', $safari_category_id, $description;
    public $featured_image, $gallery_images = [], $itinerary = [];

    public function mount() {
        if (empty($this->itinerary)) {
            $this->addDay();
        }
    }

    public function addDay() {
        $this->itinerary[] = [
            'day_number' => count($this->itinerary) + 1,
            'title' => '',
            'activities' => '',
            'meals' => 'Breakfast, Lunch, Dinner',
            'accommodation' => ''
        ];
        $this->duration_days = count($this->itinerary);
    }

    public function removeDay($index) {
        unset($this->itinerary[$index]);
        $this->reorderDays();
    }

    protected function reorderDays() {
        $this->itinerary = array_values($this->itinerary);
        foreach ($this->itinerary as $k => $v) {
            $this->itinerary[$k]['day_number'] = $k + 1;
        }
        $this->duration_days = count($this->itinerary);
    }

    public function save() {
        $this->validate([
            'name' => 'required|string|max:255|unique:safari_packages,name',
            'price' => 'required|numeric|min:0',
            'discount_per_person' => 'required|numeric|min:0|max:100',
            'destination_id' => 'required|exists:destinations,id',
            'safari_category_id' => 'required|exists:safari_categories,id',
            'description' => 'required|min:20',
            'featured_image' => 'required|image|max:2048',
            'gallery_images.*' => 'image|max:2048',
            'itinerary.*.title' => 'required|string',
        ], [
            'itinerary.*.title.required' => 'Each day needs a title.',
            'featured_image.required' => 'A cover photo is essential for sales.'
        ]);

        DB::transaction(function () {
            $package = SafariPackage::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'price' => $this->price,
                'discount_per_person' => $this->discount_per_person,
                'duration_days' => count($this->itinerary),
                'destination_id' => $this->destination_id,
                'status' => $this->status,
                'safari_category_id' => $this->safari_category_id,
                'description' => $this->description,
            ]);

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

        session()->flash('success', 'Safari Package created successfully!');
        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data="{ 
    activeDay: 0, 
    basePrice: @entangle('price'), 
    discount: @entangle('discount_per_person'),
    calculate(people) {
        if(!this.basePrice) return 0;
        let totalDiscount = (people - 1) * this.discount;
        let finalPrice = this.basePrice * (1 - (totalDiscount / 100));
        return Math.max(finalPrice, this.basePrice * 0.5).toFixed(2);
    }
}">
    @section('title', 'Create Safari Package')

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .sticky-top-card { position: sticky; top: 20px; z-index: 10; }
            .featured-upload-box { width: 100%; height: 250px; border: 2px dashed #cbd5e0; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; background: #f8fafc; cursor: pointer; transition: all 0.3s ease; }
            .featured-upload-box:hover { border-color: #e83e8c; background: #fff5f8; transform: translateY(-2px); }
            .price-preview-box { background: #fff5f8; border: 1px solid #fed7e2; border-radius: 8px; padding: 10px; }
            .itinerary-header { cursor: pointer; background: #fff; transition: 0.2s; }
            .itinerary-header:hover { background: #fdf2f7; }
            .btn-pink { background-color: #e83e8c; color: white; transition: 0.3s; }
            .btn-pink:hover { background-color: #be185d; color: white; transform: translateY(-1px); }
            .text-pink { color: #e83e8c !important; }
            .card-pink { border-top: 4px solid #e83e8c; }
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="font-weight-bold">Create New Safari Package</h1>
                    <p class="text-muted">Fill in the details to publish a new adventure.</p>
                </div>
                <a href="{{ route('admin.packages.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Column: Basics & Settings --}}
                    <div class="col-md-5">
                        <div class="card card-pink shadow-sm mb-4 sticky-top-card">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Package Core Details</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. 7-Day Luxury Gorilla Trekking">
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">BASE PRICE (Per Person)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text bg-white">$</span></div>
                                            <input type="number" wire:model.live="price" class="form-control @error('price') is-invalid @enderror">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">GROUP DISCOUNT (%)</label>
                                        <div class="input-group">
                                            <input type="number" wire:model.live="discount_per_person" class="form-control">
                                            <div class="input-group-append"><span class="input-group-text bg-white">%</span></div>
                                        </div>
                                        <small class="text-muted">Discount per additional person</small>
                                    </div>
                                </div>

                                {{-- Price Preview Tooltip --}}
                                <div class="price-preview-box mb-3" x-show="basePrice > 0">
                                    <label class="small font-weight-bold text-pink"><i class="fas fa-calculator mr-1"></i> Live Pricing Preview:</label>
                                    <div class="d-flex justify-content-between small border-bottom pb-1">
                                        <span>Solo Traveler:</span> <strong>$<span x-text="basePrice"></span></strong>
                                    </div>
                                    <div class="d-flex justify-content-between small pt-1">
                                        <span>Group of 4 (Price/Person):</span> <strong>$<span x-text="calculate(4)"></span></strong>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">DESTINATION</label>
                                    <select wire:model="destination_id" class="form-control @error('destination_id') is-invalid @enderror">
                                        <option value="">Choose...</option>
                                        @foreach(\App\Models\Destination::orderBy('name')->get() as $dest)
                                            <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label class="small font-weight-bold">CATEGORY</label>
                                        <select wire:model="safari_category_id" class="form-control @error('safari_category_id') is-invalid @enderror">
                                            <option value="">Select...</option>
                                            @foreach(\App\Models\SafariCategory::all() as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold">VISIBILITY</label>
                                        <select wire:model="status" class="form-control">
                                            <option value="draft">Draft (Hidden)</option>
                                            <option value="published">Published (Live)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Media & Itinerary --}}
                    <div class="col-md-7">
                        {{-- Cover Image --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-body p-2">
                                <div x-data="{ preview: null }">
                                    <input type="file" wire:model="featured_image" class="d-none" x-ref="photo"
                                           @change="const file = $refs.photo.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }">
                                    <div class="featured-upload-box @error('featured_image') border-danger @enderror" @click="$refs.photo.click()">
                                        <template x-if="preview">
                                            <img :src="preview" style="width: 100%; height: 100%; object-fit: cover;">
                                        </template>
                                        <template x-if="!preview">
                                            <div class="text-center text-muted">
                                                <i class="fas fa-cloud-upload-alt fa-3x mb-2 text-pink"></i>
                                                <h6 class="font-weight-bold">Upload Featured Cover Image</h6>
                                                <p class="small">Recommended: 1200x800px (Max 2MB)</p>
                                            </div>
                                        </template>
                                    </div>
                                    @error('featured_image') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Itinerary Builder --}}
                        <div class="itinerary-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="font-weight-bold mb-0">Itinerary Journey</h5>
                                <span class="badge badge-dark rounded-pill px-3">{{ count($itinerary) }} Days</span>
                            </div>

                            <div class="itinerary-wrapper">
                                @foreach($itinerary as $index => $day)
                                    <div class="card mb-3 shadow-sm border-0 overflow-hidden" wire:key="day-{{ $index }}">
                                        <div class="itinerary-header p-3 d-flex align-items-center" 
                                             @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                            <div class="bg-pink text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; flex-shrink: 0;">
                                                {{ $day['day_number'] }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 font-weight-bold {{ !$itinerary[$index]['title'] ? 'text-muted italic' : '' }}">
                                                    {{ $itinerary[$index]['title'] ?: 'Day '.$day['day_number'].': Title Pending...' }}
                                                </h6>
                                            </div>
                                            <i class="fas fa-chevron-down text-muted transition-all" :class="activeDay === {{ $index }} ? 'rotate-180' : ''"></i>
                                        </div>

                                        <div class="card-body bg-light border-top" x-show="activeDay === {{ $index }}" x-cloak x-transition>
                                            <div class="form-group mb-3">
                                                <label class="small font-weight-bold">DAY TITLE</label>
                                                <input type="text" wire:model.defer="itinerary.{{ $index }}.title" class="form-control border-0 shadow-sm" placeholder="e.g. Flight to Serengeti">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="small font-weight-bold">ACTIVITIES</label>
                                                <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control border-0 shadow-sm" rows="3" placeholder="Describe what happens today..."></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="small font-weight-bold">ACCOMMODATION</label>
                                                    <input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control border-0 shadow-sm">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="small font-weight-bold">MEALS INCLUDED</label>
                                                    <input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control border-0 shadow-sm">
                                                </div>
                                            </div>
                                            <div class="text-right mt-2">
                                                <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-link text-danger btn-sm text-decoration-none">
                                                    <i class="fas fa-times mr-1"></i> Delete Day
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" wire:click="addDay" class="btn btn-outline-pink btn-block bg-white shadow-sm mb-4 py-2 font-weight-bold">
                                <i class="fas fa-plus-circle mr-2"></i> ADD ANOTHER DAY
                            </button>
                        </div>

                        {{-- Description --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white font-weight-bold text-uppercase small">General Overview & Inclusions</div>
                            <div class="card-body">
                                <div wire:ignore x-data="{ value: @entangle('description') }" @trix-change="value = $event.target.value">
                                    <trix-editor class="trix-content border-0 bg-light rounded shadow-inner" placeholder="Detailed package description, what's included and what's not..."></trix-editor>
                                </div>
                                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="card shadow-sm border-0 mb-5">
                            <button type="submit" class="btn btn-pink btn-lg btn-block py-3 font-weight-bold">
                                <span wire:loading.remove>CREATE & PUBLISH PACKAGE</span>
                                <span wire:loading><i class="fas fa-sync fa-spin mr-2"></i> PROCESSING...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
        <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @endpush
</div>