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
    
    // UI State: Track which day is open
    public $activeDay = 0; 

    public function mount() {
        if (empty($this->itinerary)) {
            $this->addDay();
        }
    }

    public function toggleDay($index) {
        $this->activeDay = ($this->activeDay === $index) ? null : $index;
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
        $this->activeDay = count($this->itinerary) - 1; // Open the new day
    }

    public function duplicateDay($index) {
        $dayToCopy = $this->itinerary[$index];
        array_splice($this->itinerary, $index + 1, 0, [$dayToCopy]);
        $this->reorderDays();
        $this->activeDay = $index + 1; // Open the duplicated day
    }

    public function removeDay($index) {
        array_splice($this->itinerary, $index, 1);
        $this->reorderDays();
        $this->activeDay = null;
    }

    public function clearAllDays() {
        $this->itinerary = [];
        $this->duration_days = 0;
        $this->activeDay = null;
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
            .featured-upload-box { width: 100%; height: 250px; border: 2px dashed #cbd5e0; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; background: #f8fafc; cursor: pointer; }
            .gallery-upload-box { width: 100px; height: 100px; border: 2px dashed #cbd5e0; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; background: #f8fafc; }
            .gallery-item { position: relative; width: 100px; height: 100px; border-radius: 8px; overflow: hidden; }
            .gallery-remove { position: absolute; top: 2px; right: 2px; background: rgba(232, 62, 140, 0.9); color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px; cursor: pointer; z-index: 5; }
            .price-preview-box { background: #fdf2f7; border: 1px solid #f9a8d4; border-radius: 12px; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .btn-pink:hover { background-color: #be185d; color: white; }
            .text-pink { color: #e83e8c !important; }
            .bg-pink { background-color: #e83e8c !important; }
            .card-pink { border-top: 4px solid #e83e8c; }
            [x-cloak] { display: none !important; }
            .object-fit-cover { object-fit: cover; }
            .itinerary-scroll-container { max-height: 600px; overflow-y: auto; padding-right: 5px; }
            .multiselect-badge { background: #e83e8c; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px; display: inline-flex; align-items: center; margin: 2px; }
            .multiselect-dropdown { position: absolute; z-index: 1000; background: white; border: 1px solid #ddd; width: 100%; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 0 0 8px 8px; }
            .multiselect-option { padding: 8px 12px; cursor: pointer; font-size: 14px; }
            .multiselect-option:hover { background: #fdf2f7; color: #e83e8c; }
            .transition-icon { transition: transform 0.3s ease; }
            .border-dashed { border: 2px dashed #cbd5e0 !important; }
            .fa-rotate-180 { transform: rotate(180deg); }
        </style>
    @endpush

    <section class="content">
        <div class="container-fluid pt-4">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-md-5">
                        <div class="card card-pink shadow-sm mb-4 sticky-top-card">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Core Details</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="small font-weight-bold">BASE PRICE ($)</label>
                                        <input type="number" wire:model.live="price" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="small font-weight-bold">DISC. %</label>
                                        <input type="number" wire:model.live="discount_per_person" class="form-control">
                                    </div>
                                </div>

                                {{-- Price Preview --}}
                                <div class="price-preview-box p-3 mb-4" x-show="basePrice > 0" x-transition>
                                    <h6 class="small font-weight-bold text-pink mb-2">PRICING PREVIEW</h6>
                                    <div class="d-flex justify-content-between small border-bottom mb-1">
                                        <span>2 People:</span> <span class="font-weight-bold" x-text="'$' + calculate(2)"></span>
                                    </div>
                                    <div class="d-flex justify-content-between small">
                                        <span>Per Person:</span> <span class="font-weight-bold" x-text="'$' + calculatePerPerson(2)"></span>
                                    </div>
                                </div>

                                {{-- Destinations --}}
                                <div class="form-group mb-3" x-data="{
                                    open: false, search: '',
                                    options: @js(\App\Models\Destination::orderBy('name')->get()->map(fn($d) => ['id' => $d->id, 'name' => $d->name])),
                                    selected: @entangle('destination_ids'),
                                    get filteredOptions() { return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()) && !this.selected.includes(i.id)); },
                                    get selectedNames() { return this.options.filter(i => this.selected.includes(i.id)); },
                                    toggle(id) { this.selected.includes(id) ? this.selected = this.selected.filter(i => i !== id) : this.selected.push(id); }
                                }" @click.away="open = false">
                                    <label class="small font-weight-bold">DESTINATIONS</label>
                                    <div class="form-control h-auto d-flex flex-wrap p-1" @click="open = true">
                                        <template x-for="item in selectedNames" :key="item.id">
                                            <span class="multiselect-badge"><span x-text="item.name"></span><i class="fas fa-times ml-2" @click.stop="toggle(item.id)"></i></span>
                                        </template>
                                        <input type="text" x-model="search" class="border-0 flex-grow-1 m-1" placeholder="Search..." style="outline:none;">
                                    </div>
                                    <div x-show="open" class="multiselect-dropdown" x-cloak>
                                        <template x-for="option in filteredOptions" :key="option.id">
                                            <div class="multiselect-option" @click="toggle(option.id); search = '';" x-text="option.name"></div>
                                        </template>
                                    </div>
                                </div>

                                {{-- Categories --}}
                                <div class="form-group mb-3" x-data="{
                                    open: false, search: '',
                                    options: @js(\App\Models\SafariCategory::orderBy('name')->get()->map(fn($c) => ['id' => $c->id, 'name' => $c->name])),
                                    selected: @entangle('safari_category_ids'),
                                    get filteredOptions() { return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()) && !this.selected.includes(i.id)); },
                                    get selectedNames() { return this.options.filter(i => this.selected.includes(i.id)); },
                                    toggle(id) { this.selected.includes(id) ? this.selected = this.selected.filter(i => i !== id) : this.selected.push(id); }
                                }" @click.away="open = false">
                                    <label class="small font-weight-bold">CATEGORIES</label>
                                    <div class="form-control h-auto d-flex flex-wrap p-1" @click="open = true">
                                        <template x-for="item in selectedNames" :key="item.id">
                                            <span class="multiselect-badge" style="background: #6366f1;"><span x-text="item.name"></span><i class="fas fa-times ml-2" @click.stop="toggle(item.id)"></i></span>
                                        </template>
                                        <input type="text" x-model="search" class="border-0 flex-grow-1 m-1" placeholder="Search..." style="outline:none;">
                                    </div>
                                    <div x-show="open" class="multiselect-dropdown" x-cloak>
                                        <template x-for="option in filteredOptions" :key="option.id">
                                            <div class="multiselect-option" @click="toggle(option.id); search = '';" x-text="option.name"></div>
                                        </template>
                                    </div>
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

                    {{-- Right Column --}}
                    <div class="col-md-7">
                        {{-- Media --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white font-weight-bold small">MEDIA ASSETS</div>
                            <div class="card-body">
                                <label class="small font-weight-bold">COVER IMAGE</label>
                                <div x-data="{ preview: null }">
                                    <input type="file" id="cover_input" wire:model="featured_image" class="d-none" @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }">
                                    <div class="featured-upload-box mb-3 shadow-sm" onclick="document.getElementById('cover_input').click()">
                                        <template x-if="preview"><img :src="preview" class="w-100 h-100 object-fit-cover"></template>
                                        <template x-if="!preview"><div class="text-center text-muted"><i class="fas fa-cloud-upload-alt fa-3x mb-2 text-pink"></i><p>Upload Cover</p></div></template>
                                    </div>
                                </div>

                                <label class="small font-weight-bold">GALLERY</label>
                                <div class="d-flex flex-wrap" style="gap: 10px;">
                                    @foreach($gallery_images as $index => $image)
                                        <div class="gallery-item border">
                                            <img src="{{ $image->temporaryUrl() }}" class="w-100 h-100 object-fit-cover">
                                            <div class="gallery-remove" wire:click="removeGalleryImage({{ $index }})"><i class="fas fa-times"></i></div>
                                        </div>
                                    @endforeach
                                    <label class="gallery-upload-box mb-0 border-dashed" for="gallery_input">
                                        <i class="fas fa-plus text-muted"></i>
                                        <input type="file" id="gallery_input" wire:model.live="gallery_images" multiple class="d-none">
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Itinerary: FIXED COLLAPSE --}}
                        <div class="itinerary-section mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="font-weight-bold mb-0">Itinerary Journey</h5>
                                <div>
                                    <button type="button" wire:click="addDay" class="btn btn-sm btn-pink rounded-pill px-3">
                                        <i class="fas fa-plus mr-1"></i> Add Day
                                    </button>
                                </div>
                            </div>

                            <div class="itinerary-scroll-container">
                                @forelse($itinerary as $index => $day)
                                    <div class="card mb-2 border-0 shadow-sm" wire:key="day-{{ $index }}-{{ count($itinerary) }}">
                                        {{-- Header --}}
                                        <div class="p-3 d-flex align-items-center bg-white" 
                                             wire:click="toggleDay({{ $index }})" 
                                             style="cursor:pointer; border-bottom: 1px solid #f1f5f9;">
                                            <div class="bg-pink text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 11px; font-weight:bold;">{{ $day['day_number'] }}</div>
                                            <div class="flex-grow-1 font-weight-bold small text-dark">{{ $day['title'] ?: 'Untitled Day' }}</div>
                                            <i class="fas fa-chevron-down text-muted transition-icon {{ $activeDay === $index ? 'fa-rotate-180' : '' }}"></i>
                                        </div>
                                        
                                        {{-- Body --}}
                                        @if($activeDay === $index)
                                        <div class="card-body bg-light">
                                            <div class="form-group mb-2">
                                                <label class="small text-muted font-weight-bold">TITLE</label>
                                                <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control">
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="small text-muted font-weight-bold">ACTIVITIES</label>
                                                <textarea wire:model.blur="itinerary.{{ $index }}.activities" class="form-control" rows="3"></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="small text-muted font-weight-bold">LODGE</label>
                                                    <input type="text" wire:model.blur="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-6">
                                                    <label class="small text-muted font-weight-bold">MEALS</label>
                                                    <input type="text" wire:model.blur="itinerary.{{ $index }}.meals" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="mt-3 pt-2 border-top text-right">
                                                <button type="button" wire:click="duplicateDay({{ $index }})" class="btn btn-link btn-sm text-pink">Duplicate</button>
                                                <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-link btn-sm text-danger ml-2">Delete</button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-5 bg-light rounded border-dashed">No days added.</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- Overview --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white font-weight-bold small">OVERVIEW</div>
                            <div class="card-body">
                                <div wire:ignore x-data="{ value: @entangle('description') }" @trix-change="value = $event.target.value">
                                    <trix-editor class="trix-content border-0 bg-light rounded" style="min-height: 200px;"></trix-editor>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block py-3 font-weight-bold shadow-sm rounded-pill">
                            <span wire:loading.remove>CREATE SAFARI PACKAGE</span>
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