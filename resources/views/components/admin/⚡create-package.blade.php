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
    public $destination_ids = [], $status = 'draft', $safari_category_id, $description; 
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

    public function removeGalleryImage($index) {
        array_splice($this->gallery_images, $index, 1);
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
            'destination_ids' => 'required|array|min:1',
            'destination_ids.*' => 'exists:destinations,id',
            'safari_category_id' => 'required|exists:safari_categories,id',
            'description' => 'required|min:20',
            'featured_image' => 'required|image|max:2048',
            'gallery_images.*' => 'image|max:2048',
            'itinerary.*.title' => 'required|string',
        ], [
            'itinerary.*.title.required' => 'Each day needs a title.',
            'featured_image.required' => 'A cover photo is essential for sales.',
            'destination_ids.required' => 'Please select at least one destination.'
        ]);

        DB::transaction(function () {
            $package = SafariPackage::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'price' => $this->price,
                'discount_per_person' => $this->discount_per_person,
                'duration_days' => count($this->itinerary),
                'status' => $this->status,
                'safari_category_id' => $this->safari_category_id,
                'description' => $this->description,
            ]);

            $package->destinations()->sync($this->destination_ids);

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
        let totalDiscountPercentage = (people - 1) * this.discount;
        let pricePerPerson = this.basePrice * (1 - (totalDiscountPercentage / 100));
        let finalPrice = Math.max(pricePerPerson, this.basePrice * 0.5);
        return (finalPrice * people).toFixed(2);
    },
    calculatePerPerson(people) {
        if(!this.basePrice) return 0;
        let totalDiscountPercentage = (people - 1) * this.discount;
        let pricePerPerson = this.basePrice * (1 - (totalDiscountPercentage / 100));
        return Math.max(pricePerPerson, this.basePrice * 0.5).toFixed(2);
    }
}">
    @section('title', 'Create Safari Package')

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .sticky-top-card { position: sticky; top: 20px; z-index: 10; }
            .featured-upload-box { width: 100%; height: 250px; border: 2px dashed #cbd5e0; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; background: #f8fafc; cursor: pointer; transition: all 0.3s ease; }
            .featured-upload-box.dragging { border-color: #e83e8c; background: #fff5f8; }
            .price-preview-box { background: #fff5f8; border: 1px solid #fed7e2; border-radius: 8px; padding: 10px; }
            .itinerary-header { cursor: pointer; background: #fff; transition: 0.2s; }
            .itinerary-header:hover { background: #fdf2f7; }
            .btn-pink { background-color: #e83e8c; color: white; transition: 0.3s; }
            .btn-pink:hover { background-color: #be185d; color: white; transform: translateY(-1px); }
            .btn-outline-pink { border-color: #e83e8c; color: #e83e8c; }
            .btn-outline-pink:hover { background-color: #e83e8c; color: #white; }
            .text-pink { color: #e83e8c !important; }
            .bg-pink { background-color: #e83e8c !important; }
            .card-pink { border-top: 4px solid #e83e8c; }
            [x-cloak] { display: none !important; }
            .object-fit-cover { object-fit: cover; }
            .transition-all { transition: all 0.3s ease; }
            
            /* Custom Scrollbar for Itinerary */
            .itinerary-scroll-container {
                max-height: 500px;
                overflow-y: auto;
                padding-right: 5px;
            }
            .itinerary-scroll-container::-webkit-scrollbar { width: 6px; }
            .itinerary-scroll-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
            .itinerary-scroll-container::-webkit-scrollbar-thumb { background: #e83e8c; border-radius: 10px; }
            .itinerary-scroll-container::-webkit-scrollbar-thumb:hover { background: #be185d; }

            /* Modern Multi-select Styles */
            .multiselect-badge { background: #e83e8c; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px; display: inline-flex; align-items: center; margin: 2px; }
            .multiselect-dropdown { position: absolute; z-index: 1000; background: white; border: 1px solid #ddd; width: 100%; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 0 0 8px 8px; }
            .multiselect-option { padding: 8px 12px; cursor: pointer; transition: 0.2s; }
            .multiselect-option:hover { background: #fdf2f7; color: #e83e8c; }
            
            .skeleton {
                background: #eee;
                background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
                border-radius: 8px;
                background-size: 200% 100%;
                animation: 1.5s shine linear infinite;
            }
            @keyframes shine { to { background-position-x: -200%; } }
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
                    {{-- Left Column --}}
                    <div class="col-md-5">
                        <div class="card card-pink shadow-sm mb-4 sticky-top-card">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Package Core Details</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. 7-Day Luxury">
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">BASE PRICE ($)</label>
                                        <input type="number" wire:model.live="price" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">DISCOUNT (%)</label>
                                        <input type="number" wire:model.live="discount_per_person" class="form-control">
                                    </div>
                                </div>

                                {{-- Multi-Select Destinations --}}
                                <div class="form-group mb-3" x-data="{
                                    open: false,
                                    search: '',
                                    options: @js(\App\Models\Destination::orderBy('name')->get()->map(fn($d) => ['id' => $d->id, 'name' => $d->name])),
                                    selected: @entangle('destination_ids'),
                                    get filteredOptions() {
                                        return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()) && !this.selected.includes(i.id));
                                    },
                                    get selectedNames() {
                                        return this.options.filter(i => this.selected.includes(i.id));
                                    },
                                    toggle(id) {
                                        if (this.selected.includes(id)) {
                                            this.selected = this.selected.filter(i => i !== id);
                                        } else {
                                            this.selected.push(id);
                                        }
                                    }
                                }" @click.away="open = false">
                                    <label class="small font-weight-bold">DESTINATIONS</label>
                                    <div class="form-control h-auto d-flex flex-wrap align-items-center p-1 @error('destination_ids') is-invalid @enderror" @click="open = true" style="cursor: text; min-height: 45px;">
                                        <template x-for="item in selectedNames" :key="item.id">
                                            <span class="multiselect-badge">
                                                <span x-text="item.name"></span>
                                                <i class="fas fa-times ml-2" @click.stop="toggle(item.id)" style="cursor:pointer"></i>
                                            </span>
                                        </template>
                                        <input type="text" x-model="search" class="border-0 flex-grow-1 m-1" placeholder="Search..." @focus="open = true" style="outline:none; min-width: 100px;">
                                    </div>
                                    <div x-show="open" class="multiselect-dropdown" x-cloak>
                                        <template x-for="option in filteredOptions" :key="option.id">
                                            <div class="multiselect-option" @click="toggle(option.id); search = '';">
                                                <span x-text="option.name"></span>
                                            </div>
                                        </template>
                                        <div x-show="filteredOptions.length === 0" class="p-2 text-muted small text-center">No more destinations found.</div>
                                    </div>
                                    @error('destination_ids') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label class="small font-weight-bold">CATEGORY</label>
                                        <select wire:model="safari_category_id" class="form-control">
                                            <option value="">Select...</option>
                                            @foreach(\App\Models\SafariCategory::all() as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold">VISIBILITY</label>
                                        <select wire:model="status" class="form-control">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-md-7">
                        {{-- Cover Image --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white font-weight-bold small text-uppercase">Cover Photo</div>
                            <div class="card-body p-3">
                                <div x-data="{ isOver: false, preview: null }">
                                    <input type="file" id="cover_input" wire:model="featured_image" class="d-none" @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }">
                                    <div class="featured-upload-box" :class="isOver ? 'dragging' : ''" @dragover.prevent="isOver = true" @dragleave.prevent="isOver = false" @drop.prevent="isOver = false; @this.upload('featured_image', $event.dataTransfer.files[0])" onclick="document.getElementById('cover_input').click()">
                                        <div wire:loading wire:target="featured_image"><div class="spinner-border text-pink"></div></div>
                                        <div wire:loading.remove wire:target="featured_image">
                                            <template x-if="preview"><img :src="preview" class="w-100 h-100 object-fit-cover"></template>
                                            <template x-if="!preview"><div class="text-center text-muted"><i class="fas fa-image fa-3x mb-2 text-pink"></i><p class="mb-0 font-weight-bold">Drag cover photo here</p></div></template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Gallery --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 font-weight-bold small text-uppercase">Gallery Images</h6>
                                <small class="text-muted">{{ count($gallery_images) }}/10</small>
                            </div>
                            <div class="card-body bg-light-50" x-data="{ isOver: false }">
                                <div class="upload-zone p-4 mb-3 text-center border rounded-lg" :class="isOver ? 'dragging' : ''" style="border: 2px dashed #cbd5e0; background: #fff; cursor: pointer;" @dragover.prevent="isOver = true" @dragleave.prevent="isOver = false" @drop.prevent="isOver = false; @this.uploadMultiple('gallery_images', $event.dataTransfer.files)" onclick="document.getElementById('gallery_input').click()">
                                    <i class="fas fa-images text-pink fa-2x mb-2"></i>
                                    <p class="mb-0 small font-weight-bold">Drop images here</p>
                                    <input type="file" id="gallery_input" wire:model="gallery_images" multiple class="d-none">
                                </div>
                                <div class="row row-cols-4 g-2">
                                    @foreach($gallery_images as $index => $image)
                                        <div class="col mb-2"><div class="position-relative rounded overflow-hidden" style="height: 80px;"><img src="{{ $image->temporaryUrl() }}" class="w-100 h-100 object-fit-cover"><button type="button" wire:click="removeGalleryImage({{ $index }})" class="btn btn-danger btn-xs position-absolute" style="top:2px; right:2px;"><i class="fas fa-times"></i></button></div></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Itinerary with Scrollbar --}}
                        <div class="itinerary-section mb-4">
                            <h5 class="font-weight-bold mb-3">Itinerary Journey</h5>
                            <div class="itinerary-scroll-container">
                                @foreach($itinerary as $index => $day)
                                    <div class="card mb-2 border-0 shadow-sm overflow-hidden">
                                        <div class="itinerary-header p-3 d-flex align-items-center" @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                            <div class="bg-pink text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">{{ $day['day_number'] }}</div>
                                            <div class="flex-grow-1 font-weight-bold small text-uppercase">{{ $day['title'] ?: 'New Day' }}</div>
                                            <i class="fas fa-chevron-down text-muted" :style="activeDay === {{ $index }} ? 'transform:rotate(180deg)' : ''"></i>
                                        </div>
                                        <div class="card-body bg-light border-top" x-show="activeDay === {{ $index }}" x-cloak>
                                            <input type="text" wire:model.defer="itinerary.{{ $index }}.title" class="form-control mb-2" placeholder="Title">
                                            <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control mb-2" rows="2" placeholder="Activities"></textarea>
                                            <div class="row">
                                                <div class="col-6"><input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control small" placeholder="Lodge"></div>
                                                <div class="col-6"><input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control small" placeholder="Meals"></div>
                                            </div>
                                            <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-link btn-sm text-danger px-0 mt-2">Delete Day</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" wire:click="addDay" class="btn btn-outline-pink btn-block mt-3 font-weight-bold">
                                <i class="fas fa-plus-circle mr-2"></i>Add Another Day
                            </button>
                        </div>

                        {{-- Description --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white font-weight-bold small">Overview</div>
                            <div class="card-body">
                                <div wire:ignore x-data="{ value: @entangle('description') }" @trix-change="value = $event.target.value">
                                    <trix-editor class="trix-content border-0 bg-light rounded shadow-inner"></trix-editor>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block py-3 font-weight-bold shadow-sm">
                            <span wire:loading.remove>CREATE PACKAGE</span>
                            <span wire:loading><i class="fas fa-spinner fa-spin mr-2"></i> SAVING...</span>
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