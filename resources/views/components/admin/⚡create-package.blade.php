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

    // Form Fields
    public $name, $price, $discount_per_person = 0, $duration_days = 1;
    public $destination_ids = [], $status = 'draft', $safari_category_ids = [], $description; 
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
        // Dispatch to Alpine to auto-open the new day
        $this->dispatch('day-added', index: count($this->itinerary) - 1);
    }

    public function removeAllDays() {
        $this->itinerary = [];
        $this->addDay();
    }

    public function duplicateDay($index) {
        $dayToCopy = $this->itinerary[$index];
        array_splice($this->itinerary, $index + 1, 0, [$dayToCopy]);
        $this->reorderDays();
        $this->dispatch('day-added', index: $index + 1);
    }

    public function removeDay($index) {
        if (count($this->itinerary) > 1) {
            array_splice($this->itinerary, $index, 1);
            $this->reorderDays();
        }
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'destination_ids' => 'required|array|min:1',
            'description' => 'required|min:10',
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

        session()->flash('success', 'Safari Package created!');
        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data="{ 
    basePrice: @entangle('price'), 
    discount: @entangle('discount_per_person'),
    openedDays: {0: true},
    toggleDay(index) {
        this.openedDays[index] = !this.openedDays[index];
    },
    calculate(people) {
        if(!this.basePrice) return 0;
        let totalDiscountPercentage = (people - 1) * this.discount;
        let pricePerPerson = this.basePrice * (1 - (totalDiscountPercentage / 100));
        return (Math.max(pricePerPerson, this.basePrice * 0.5) * people).toLocaleString();
    }
}" @day-added.window="openedDays[$event.detail.index] = true">

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .sticky-top-card { position: sticky; top: 20px; z-index: 10; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .btn-pink:hover { background-color: #be185d; color: white; }
            .text-pink { color: #e83e8c !important; }
            .bg-pink { background-color: #e83e8c !important; }
            [x-cloak] { display: none !important; }
            .rotate-180 { transform: rotate(180deg); transition: 0.3s; }
            .featured-upload-box { width: 100%; height: 250px; border: 2px dashed #cbd5e0; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #f8fafc; cursor: pointer; overflow: hidden; }
            .gallery-item { position: relative; width: 100px; height: 100px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd; }
            .gallery-remove { position: absolute; top: 2px; right: 2px; background: #e83e8c; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 12px; }
        </style>
    @endpush

    <section class="content pt-4">
        <div class="container-fluid">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Column: Core Pricing & Meta --}}
                    <div class="col-md-5">
                        <div class="card shadow-sm sticky-top-card">
                            <div class="card-header bg-white font-weight-bold">Basic Information</div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control">
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <label class="small font-weight-bold">BASE PRICE ($)</label>
                                        <input type="number" wire:model.live="price" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold">DISCOUNT % (Per Person)</label>
                                        <input type="number" wire:model.live="discount_per_person" class="form-control">
                                    </div>
                                </div>

                                <div class="p-3 mb-4 rounded" style="background: #fff5f7; border: 1px solid #f9a8d4;" x-show="basePrice > 0">
                                    <h6 class="small font-weight-bold text-pink">PRICING PREVIEW</h6>
                                    <template x-for="p in [1, 2, 3, 4, 6]">
                                        <div class="d-flex justify-content-between small py-1 border-bottom">
                                            <span x-text="p + (p==1?' Person':' People')"></span>
                                            <span class="font-weight-bold" x-text="'$' + calculate(p)"></span>
                                        </div>
                                    </template>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">DESTINATIONS</label>
                                    <select wire:model="destination_ids" class="form-control" multiple style="height: 120px;">
                                        @foreach(\App\Models\Destination::orderBy('name')->get() as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold">STATUS</label>
                                    <select wire:model="status" class="form-control">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Media & Itinerary --}}
                    <div class="col-md-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <label class="small font-weight-bold">FEATURED IMAGE</label>
                                <div x-data="{ preview: null }" class="mb-3">
                                    <input type="file" id="featured_image" wire:model="featured_image" class="d-none" @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }">
                                    <div class="featured-upload-box" onclick="document.getElementById('featured_image').click()">
                                        <template x-if="preview"><img :src="preview" class="w-100 h-100" style="object-fit:cover"></template>
                                        <template x-if="!preview"><div class="text-center text-muted"><i class="fas fa-image fa-2x"></i><p>Click to upload</p></div></template>
                                    </div>
                                </div>

                                <label class="small font-weight-bold">GALLERY</label>
                                <div class="d-flex flex-wrap gap-2" style="gap:10px;">
                                    @foreach($gallery_images as $idx => $img)
                                        <div class="gallery-item">
                                            <img src="{{ $img->temporaryUrl() }}" class="w-100 h-100" style="object-fit:cover">
                                            <span class="gallery-remove" wire:click="removeGalleryImage({{ $idx }})">×</span>
                                        </div>
                                    @endforeach
                                    <label class="gallery-item bg-light d-flex align-items-center justify-content-center" style="cursor:pointer">
                                        <i class="fas fa-plus"></i><input type="file" wire:model.live="gallery_images" multiple class="d-none">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="font-weight-bold mb-0">Itinerary Journey</h5>
                            <div>
                                <button type="button" wire:click="removeAllDays" class="btn btn-sm btn-outline-danger px-3 mr-2 rounded-pill" onclick="confirm('Delete all days?') || event.stopImmediatePropagation()">Delete All</button>
                                <button type="button" wire:click="addDay" class="btn btn-sm btn-pink px-3 rounded-pill">Add Day</button>
                            </div>
                        </div>

                        <div class="itinerary-area">
                            @foreach($itinerary as $index => $day)
                                <div class="card mb-2 border-0 shadow-sm" wire:key="day-v1-{{ $index }}">
                                    <div class="p-3 d-flex align-items-center bg-white rounded" 
                                         style="cursor:pointer;" 
                                         @click="toggleDay({{ $index }})">
                                        <div class="bg-pink text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width:26px; height:26px; font-size:12px; font-weight:bold;">{{ $day['day_number'] }}</div>
                                        <div class="flex-grow-1 font-weight-bold small text-dark">{{ $day['title'] ?: 'Untitled Day' }}</div>
                                        <i class="fas fa-chevron-down text-muted" :class="openedDays[{{ $index }}] ? 'rotate-180' : ''"></i>
                                    </div>
                                    
                                    <div x-show="openedDays[{{ $index }}]" x-collapse x-cloak>
                                        <div class="card-body bg-light border-top">
                                            <div class="form-group mb-2">
                                                <label class="small text-muted font-weight-bold">DAY TITLE</label>
                                                <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="small text-muted font-weight-bold">ACTIVITIES</label>
                                                <textarea wire:model.blur="itinerary.{{ $index }}.activities" class="form-control" rows="3"></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-6"><label class="small text-muted font-weight-bold">ACCOMMODATION</label><input type="text" wire:model.blur="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm"></div>
                                                <div class="col-6"><label class="small text-muted font-weight-bold">MEALS</label><input type="text" wire:model.blur="itinerary.{{ $index }}.meals" class="form-control form-control-sm"></div>
                                            </div>
                                            <div class="text-right mt-3 border-top pt-2">
                                                <button type="button" wire:click="duplicateDay({{ $index }})" class="btn btn-link btn-sm text-pink mr-2">Duplicate</button>
                                                <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-link btn-sm text-danger">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="card shadow-sm mt-4 overflow-hidden">
                            <div class="card-header bg-white font-weight-bold small">PACKAGE OVERVIEW</div>
                            <div class="card-body p-0" wire:ignore>
                                <div x-data="{ value: @entangle('description') }" @trix-change="value = $event.target.value">
                                    <trix-editor class="border-0" style="min-height: 250px;"></trix-editor>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block mt-4 py-3 rounded-pill font-weight-bold shadow">
                            CREATE SAFARI PACKAGE
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