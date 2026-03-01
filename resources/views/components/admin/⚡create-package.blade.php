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
            'safari_category_ids' => 'required|array|min:1',
            'description' => 'required|min:20',
            'featured_image' => 'required|image|max:2048',
            'gallery_images.*' => 'image|max:2048',
            'itinerary.*.title' => 'required|string',
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
            // Assuming a many-to-many relationship for categories now
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
        return (finalPrice * people).toLocaleString();
    },
    calculatePerPerson(people) {
        if(!this.basePrice) return 0;
        let totalDiscountPercentage = (people - 1) * this.discount;
        let pricePerPerson = this.basePrice * (1 - (totalDiscountPercentage / 100));
        return Math.max(pricePerPerson, this.basePrice * 0.5).toLocaleString();
    }
}">
    @section('title', 'Create Safari Package')

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .sticky-top-card { position: sticky; top: 20px; z-index: 10; }
            .featured-upload-box { width: 100%; height: 250px; border: 2px dashed #cbd5e0; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; background: #f8fafc; cursor: pointer; transition: all 0.3s ease; }
            .featured-upload-box.dragging { border-color: #e83e8c; background: #fff5f8; }
            .price-preview-box { background: #fdf2f7; border: 1px solid #f9a8d4; border-radius: 12px; }
            .itinerary-header { cursor: pointer; background: #fff; transition: 0.2s; }
            .itinerary-header:hover { background: #fdf2f7; }
            .btn-pink { background-color: #e83e8c; color: white; transition: 0.3s; }
            .btn-pink:hover { background-color: #be185d; color: white; transform: translateY(-1px); }
            .btn-outline-pink { border-color: #e83e8c; color: #e83e8c; }
            .btn-outline-pink:hover { background-color: #e83e8c; color: white; }
            .text-pink { color: #e83e8c !important; }
            .bg-pink { background-color: #e83e8c !important; }
            .card-pink { border-top: 4px solid #e83e8c; }
            [x-cloak] { display: none !important; }
            .object-fit-cover { object-fit: cover; }
            
            .itinerary-scroll-container { max-height: 500px; overflow-y: auto; padding-right: 5px; }
            .itinerary-scroll-container::-webkit-scrollbar { width: 6px; }
            .itinerary-scroll-container::-webkit-scrollbar-thumb { background: #e83e8c; border-radius: 10px; }

            .multiselect-badge { background: #e83e8c; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px; display: inline-flex; align-items: center; margin: 2px; }
            .multiselect-dropdown { position: absolute; z-index: 1000; background: white; border: 1px solid #ddd; width: 100%; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 0 0 8px 8px; }
            .multiselect-option { padding: 8px 12px; cursor: pointer; transition: 0.2s; }
            .multiselect-option:hover { background: #fdf2f7; color: #e83e8c; }
        </style>
    @endpush

    <section class="content">
        <div class="container-fluid pt-4">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Column: Core Data --}}
                    <div class="col-md-5">
                        <div class="card card-pink shadow-sm mb-4 sticky-top-card">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Package Core Details</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. 7-Day Luxury Serengeti">
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">BASE PRICE ($)</label>
                                        <input type="number" wire:model.live="price" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">DISC. PER ADD. PERSON (%)</label>
                                        <input type="number" wire:model.live="discount_per_person" class="form-control">
                                    </div>
                                </div>

                                {{-- Price Preview Box --}}
                                <div class="price-preview-box p-3 mb-4" x-show="basePrice > 0" x-transition>
                                    <h6 class="small font-weight-bold text-pink mb-2">DYNAMIC PRICING PREVIEW</h6>
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                        <span class="small">1 Person:</span>
                                        <span class="small font-weight-bold" x-text="'$' + calculate(1)"></span>
                                    </div>
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                        <span class="small">2 People:</span>
                                        <span class="small font-weight-bold" x-text="'$' + calculate(2) + ' ($' + calculatePerPerson(2) + ' ea)'"></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="small">4 People:</span>
                                        <span class="small font-weight-bold text-success" x-text="'$' + calculate(4) + ' ($' + calculatePerPerson(4) + ' ea)'"></span>
                                    </div>
                                </div>

                                {{-- Multi-Select Destinations --}}
                                <div class="form-group mb-3" x-data="{
                                    open: false,
                                    search: '',
                                    options: @js(\App\Models\Destination::orderBy('name')->get()->map(fn($d) => ['id' => $d->id, 'name' => $d->name])),
                                    selected: @entangle('destination_ids'),
                                    get filteredOptions() { return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()) && !this.selected.includes(i.id)); },
                                    get selectedNames() { return this.options.filter(i => this.selected.includes(i.id)); },
                                    toggle(id) {
                                        if (this.selected.includes(id)) { this.selected = this.selected.filter(i => i !== id); } 
                                        else { this.selected.push(id); }
                                    }
                                }" @click.away="open = false">
                                    <label class="small font-weight-bold">DESTINATIONS</label>
                                    <div class="form-control h-auto d-flex flex-wrap align-items-center p-1" @click="open = true" style="cursor: text; min-height: 45px;">
                                        <template x-for="item in selectedNames" :key="item.id">
                                            <span class="multiselect-badge">
                                                <span x-text="item.name"></span>
                                                <i class="fas fa-times ml-2" @click.stop="toggle(item.id)" style="cursor:pointer"></i>
                                            </span>
                                        </template>
                                        <input type="text" x-model="search" class="border-0 flex-grow-1 m-1" placeholder="Search destinations..." @focus="open = true" style="outline:none;">
                                    </div>
                                    <div x-show="open" class="multiselect-dropdown" x-cloak>
                                        <template x-for="option in filteredOptions" :key="option.id">
                                            <div class="multiselect-option" @click="toggle(option.id); search = '';">
                                                <span x-text="option.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Multi-Select Categories --}}
                                <div class="form-group mb-3" x-data="{
                                    open: false,
                                    search: '',
                                    options: @js(\App\Models\SafariCategory::orderBy('name')->get()->map(fn($c) => ['id' => $c->id, 'name' => $c->name])),
                                    selected: @entangle('safari_category_ids'),
                                    get filteredOptions() { return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()) && !this.selected.includes(i.id)); },
                                    get selectedNames() { return this.options.filter(i => this.selected.includes(i.id)); },
                                    toggle(id) {
                                        if (this.selected.includes(id)) { this.selected = this.selected.filter(i => i !== id); } 
                                        else { this.selected.push(id); }
                                    }
                                }" @click.away="open = false">
                                    <label class="small font-weight-bold">CATEGORIES</label>
                                    <div class="form-control h-auto d-flex flex-wrap align-items-center p-1" @click="open = true" style="cursor: text; min-height: 45px;">
                                        <template x-for="item in selectedNames" :key="item.id">
                                            <span class="multiselect-badge" style="background: #6366f1;">
                                                <span x-text="item.name"></span>
                                                <i class="fas fa-times ml-2" @click.stop="toggle(item.id)" style="cursor:pointer"></i>
                                            </span>
                                        </template>
                                        <input type="text" x-model="search" class="border-0 flex-grow-1 m-1" placeholder="Add categories..." @focus="open = true" style="outline:none;">
                                    </div>
                                    <div x-show="open" class="multiselect-dropdown" x-cloak>
                                        <template x-for="option in filteredOptions" :key="option.id">
                                            <div class="multiselect-option" @click="toggle(option.id); search = '';">
                                                <span x-text="option.name"></span>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="small font-weight-bold">VISIBILITY STATUS</label>
                                    <select wire:model="status" class="form-control">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Media and Itinerary --}}
                    <div class="col-md-7">
                        {{-- Media Uploads --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white font-weight-bold small">MEDIA ASSETS</div>
                            <div class="card-body">
                                <label class="small font-weight-bold">COVER IMAGE</label>
                                <div x-data="{ isOver: false, preview: null }">
                                    <input type="file" id="cover_input" wire:model="featured_image" class="d-none" @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }">
                                    <div class="featured-upload-box mb-3" :class="isOver ? 'dragging' : ''" @dragover.prevent="isOver = true" @dragleave.prevent="isOver = false" @drop.prevent="isOver = false; @this.upload('featured_image', $event.dataTransfer.files[0])" onclick="document.getElementById('cover_input').click()">
                                        <template x-if="preview"><img :src="preview" class="w-100 h-100 object-fit-cover"></template>
                                        <template x-if="!preview"><div class="text-center text-muted"><i class="fas fa-cloud-upload-alt fa-3x mb-2 text-pink"></i><p>Upload Featured Image</p></div></template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Itinerary Journey --}}
                        <div class="itinerary-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="font-weight-bold mb-0">Itinerary Journey</h5>
                                <button type="button" wire:click="addDay" class="btn btn-sm btn-pink rounded-pill px-3">+ Add Day</button>
                            </div>
                            <div class="itinerary-scroll-container">
                                @foreach($itinerary as $index => $day)
                                    <div class="card mb-2 border-0 shadow-sm">
                                        <div class="itinerary-header p-3 d-flex align-items-center" @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                            <div class="bg-pink text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">{{ $day['day_number'] }}</div>
                                            <div class="flex-grow-1 font-weight-bold small">{{ $day['title'] ?: 'Click to edit day details' }}</div>
                                            <i class="fas fa-chevron-down text-muted transition-all" :style="activeDay === {{ $index }} ? 'transform:rotate(180deg)' : ''"></i>
                                        </div>
                                        <div class="card-body bg-light border-top" x-show="activeDay === {{ $index }}" x-cloak x-transition>
                                            <input type="text" wire:model.defer="itinerary.{{ $index }}.title" class="form-control mb-2" placeholder="Title for the day...">
                                            <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control mb-2" rows="3" placeholder="Describe the activities..."></textarea>
                                            <div class="row">
                                                <div class="col-6"><input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm" placeholder="Accommodation"></div>
                                                <div class="col-6"><input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control form-control-sm" placeholder="Meals"></div>
                                            </div>
                                            <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-link btn-sm text-danger mt-2 p-0">Delete Day</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Overview --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white font-weight-bold small">PACKAGE OVERVIEW</div>
                            <div class="card-body">
                                <div wire:ignore x-data="{ value: @entangle('description') }" @trix-change="value = $event.target.value">
                                    <trix-editor class="trix-content border-0 bg-light rounded"></trix-editor>
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