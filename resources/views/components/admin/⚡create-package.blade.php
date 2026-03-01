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

    public $name, $price, $discount_per_person = 0, $duration_days = 1;
    public $destination_ids = [], $status = 'draft', $safari_category_ids = [];
    public $description;
    public $featured_image, $gallery_images = [];
    public $itinerary = [];

    // ✅ Preloaded (no Blade queries)
    public $destinations = [];
    public $categories = [];

    public function mount()
    {
        $this->destinations = Destination::orderBy('name')->get(['id','name']);
        $this->categories   = SafariCategory::orderBy('name')->get(['id','name']);

        if (empty($this->itinerary)) {
            $this->addDay();
        }
    }

    public function addDay()
    {
        $this->itinerary[] = [
            'uuid' => (string) Str::uuid(),
            'day_number' => count($this->itinerary) + 1,
            'title' => '',
            'activities' => '',
            'meals' => 'Breakfast, Lunch, Dinner',
            'accommodation' => ''
        ];

        $this->duration_days = count($this->itinerary);
    }

    public function duplicateDay($index)
    {
        $day = $this->itinerary[$index];
        $day['uuid'] = (string) Str::uuid();

        array_splice($this->itinerary, $index + 1, 0, [$day]);
        $this->reorderDays();
    }

    public function removeDay($index)
    {
        array_splice($this->itinerary, $index, 1);
        $this->reorderDays();
    }

    public function clearAllDays()
    {
        $this->itinerary = [];
        $this->duration_days = 0;
    }

    public function removeGalleryImage($index)
    {
        array_splice($this->gallery_images, $index, 1);
    }

    protected function reorderDays()
    {
        $this->itinerary = array_values($this->itinerary);

        foreach ($this->itinerary as $k => $v) {
            $this->itinerary[$k]['day_number'] = $k + 1;
        }

        $this->duration_days = count($this->itinerary);
    }

    public function save()
    {
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
};
?>

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
            .gallery-upload-box { width: 100px; height: 100px; border: 2px dashed #cbd5e0; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; background: #f8fafc; }
            .gallery-item { position: relative; width: 100px; height: 100px; border-radius: 8px; overflow: hidden; }
            .gallery-remove { position: absolute; top: 2px; right: 2px; background: rgba(232, 62, 140, 0.9); color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 10px; cursor: pointer; z-index: 5; }
            .price-preview-box { background: #fdf2f7; border: 1px solid #f9a8d4; border-radius: 12px; }
            .btn-pink { background-color: #e83e8c; color: white; transition: 0.3s; border: none; }
            .btn-pink:hover { background-color: #be185d; color: white; transform: translateY(-1px); }
            .text-pink { color: #e83e8c !important; }
            .bg-pink { background-color: #e83e8c !important; }
            .card-pink { border-top: 4px solid #e83e8c; }
            [x-cloak] { display: none !important; }
            .object-fit-cover { object-fit: cover; }
            .itinerary-scroll-container { max-height: 550px; overflow-y: auto; padding-right: 8px; }
            .multiselect-badge { background: #e83e8c; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px; display: inline-flex; align-items: center; margin: 2px; }
            .multiselect-dropdown { position: absolute; z-index: 1000; background: white; border: 1px solid #ddd; width: 100%; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-radius: 0 0 8px 8px; }
            .multiselect-option { padding: 8px 12px; cursor: pointer; transition: 0.2s; font-size: 14px; }
            .multiselect-option:hover { background: #fdf2f7; color: #e83e8c; }
            .transition-icon { transition: transform 0.3s ease; }
            .border-dashed { border: 2px dashed #cbd5e0 !important; }
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
                                        <label class="small font-weight-bold">BASE PRICE</label>
                                        <div class="input-group shadow-sm">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text bg-white text-muted border-right-0">$</span>
                                            </div>
                                            <input type="number" wire:model.live="price" class="form-control border-left-0" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">DISC. / PERSON</label>
                                        <div class="input-group shadow-sm">
                                            <input type="number" wire:model.live="discount_per_person" class="form-control border-right-0" placeholder="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-white text-muted border-left-0">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Price Preview Box --}}
                                <div class="price-preview-box p-3 mb-4" x-show="basePrice > 0" x-transition>
                                    <h6 class="small font-weight-bold text-pink mb-2"><i class="fas fa-calculator mr-1"></i> DYNAMIC PRICING PREVIEW</h6>
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1 small">
                                        <span>1 Person:</span> <span class="font-weight-bold" x-text="'$' + calculate(1)"></span>
                                    </div>
                                    <div class="d-flex justify-content-between border-bottom pb-1 mb-1 small">
                                        <span>2 People:</span> <span class="font-weight-bold" x-text="'$' + calculate(2) + ' ($' + calculatePerPerson(2) + ' ea)'"></span>
                                    </div>
                                    <div class="d-flex justify-content-between small">
                                        <span>4 People:</span> <span class="font-weight-bold text-success" x-text="'$' + calculate(4) + ' ($' + calculatePerPerson(4) + ' ea)'"></span>
                                    </div>
                                </div>

                                {{-- Multi-Select Destinations --}}
                                <div class="form-group mb-3" x-data="{
                                    open: false, search: '',
                                    options: @js(\App\Models\Destination::orderBy('name')->get()->map(fn($d) => ['id' => $d->id, 'name' => $d->name])),
                                    selected: @entangle('destination_ids'),
                                    get filteredOptions() { return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()) && !this.selected.includes(i.id)); },
                                    get selectedNames() { return this.options.filter(i => this.selected.includes(i.id)); },
                                    toggle(id) { this.selected.includes(id) ? this.selected = this.selected.filter(i => i !== id) : this.selected.push(id); }
                                }" @click.away="open = false">
                                    <label class="small font-weight-bold">DESTINATIONS</label>
                                    <div class="form-control h-auto d-flex flex-wrap align-items-center p-1" @click="open = true" style="cursor: text; min-height: 45px;">
                                        <template x-for="item in selectedNames" :key="item.id">
                                            <span class="multiselect-badge"><span x-text="item.name"></span><i class="fas fa-times ml-2" @click.stop="toggle(item.id)" style="cursor:pointer"></i></span>
                                        </template>
                                        <input type="text" x-model="search" class="border-0 flex-grow-1 m-1" placeholder="Search destinations..." style="outline:none;">
                                    </div>
                                    <div x-show="open" class="multiselect-dropdown" x-cloak>
                                        <template x-for="option in filteredOptions" :key="option.id">
                                            <div class="multiselect-option" @click="toggle(option.id); search = '';" x-text="option.name"></div>
                                        </template>
                                    </div>
                                    @error('destination_ids') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                {{-- Multi-Select Categories --}}
                                <div class="form-group mb-3" x-data="{
                                    open: false, search: '',
                                    options: @js(\App\Models\SafariCategory::orderBy('name')->get()->map(fn($c) => ['id' => $c->id, 'name' => $c->name])),
                                    selected: @entangle('safari_category_ids'),
                                    get filteredOptions() { return this.options.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()) && !this.selected.includes(i.id)); },
                                    get selectedNames() { return this.options.filter(i => this.selected.includes(i.id)); },
                                    toggle(id) { this.selected.includes(id) ? this.selected = this.selected.filter(i => i !== id) : this.selected.push(id); }
                                }" @click.away="open = false">
                                    <label class="small font-weight-bold">CATEGORIES</label>
                                    <div class="form-control h-auto d-flex flex-wrap align-items-center p-1" @click="open = true" style="cursor: text; min-height: 45px;">
                                        <template x-for="item in selectedNames" :key="item.id">
                                            <span class="multiselect-badge" style="background: #6366f1;"><span x-text="item.name"></span><i class="fas fa-times ml-2" @click.stop="toggle(item.id)" style="cursor:pointer"></i></span>
                                        </template>
                                        <input type="text" x-model="search" class="border-0 flex-grow-1 m-1" placeholder="Search categories..." style="outline:none;">
                                    </div>
                                    <div x-show="open" class="multiselect-dropdown" x-cloak>
                                        <template x-for="option in filteredOptions" :key="option.id">
                                            <div class="multiselect-option" @click="toggle(option.id); search = '';" x-text="option.name"></div>
                                        </template>
                                    </div>
                                    @error('safari_category_ids') <small class="text-danger">{{ $message }}</small> @enderror
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
                                <div x-data="{ preview: null }">
                                    <input type="file" id="cover_input" wire:model="featured_image" class="d-none" @change="const file = $event.target.files[0]; if(file){ const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL(file); }">
                                    <div class="featured-upload-box mb-3 shadow-sm" onclick="document.getElementById('cover_input').click()">
                                        <template x-if="preview"><img :src="preview" class="w-100 h-100 object-fit-cover"></template>
                                        <template x-if="!preview"><div class="text-center text-muted"><i class="fas fa-cloud-upload-alt fa-3x mb-2 text-pink"></i><p>Upload Featured Image</p></div></template>
                                    </div>
                                </div>

                                <label class="small font-weight-bold">GALLERY IMAGES</label>
                                <div class="d-flex flex-wrap" style="gap: 10px;">
                                    @foreach($gallery_images as $index => $image)
                                        <div class="gallery-item shadow-sm border">
                                            <img src="{{ $image->temporaryUrl() }}" class="w-100 h-100 object-fit-cover">
                                            <div class="gallery-remove" wire:click="removeGalleryImage({{ $index }})"><i class="fas fa-times"></i></div>
                                        </div>
                                    @endforeach
                                    <label class="gallery-upload-box mb-0 shadow-sm" for="gallery_input">
                                        <i class="fas fa-plus text-muted"></i>
                                        <input type="file" id="gallery_input" wire:model.live="gallery_images" multiple class="d-none">
                                    </label>
                                </div>
                                <div wire:loading wire:target="gallery_images" class="small text-pink mt-2"><i class="fas fa-spinner fa-spin mr-1"></i> Uploading gallery...</div>
                            </div>
                        </div>

                        {{-- Itinerary Journey --}}
<div class="itinerary-section mb-4" x-data="{ activeDay: null }">

    <div class="d-flex justify-content-between mb-3">
        <h5 class="font-weight-bold mb-0">Itinerary Journey</h5>

        <div>
            @if(count($itinerary) > 0)
                <button type="button"
                        wire:click="clearAllDays"
                        onclick="confirm('Delete entire itinerary?') || event.stopImmediatePropagation()"
                        class="btn btn-sm btn-outline-danger mr-2">
                    Clear All
                </button>
            @endif

            <button type="button" wire:click="addDay" class="btn btn-sm btn-pink">
                Add Day
            </button>
        </div>
    </div>

    @forelse($itinerary as $index => $day)
        <div class="card mb-2 shadow-sm border-0"
             wire:key="day-{{ $day['uuid'] }}">

            <div class="p-3 d-flex align-items-center"
                 @click="activeDay = activeDay === {{ $index }} ? null : {{ $index }}"
                 style="cursor:pointer;">

                <div class="bg-pink text-white rounded-circle mr-3 d-flex align-items-center justify-content-center"
                     style="width:28px;height:28px;font-size:11px;">
                    {{ $day['day_number'] }}
                </div>

                <div class="flex-grow-1 font-weight-bold small">
                    {{ $day['title'] ?: 'Day '.$day['day_number'].' Untitled' }}
                </div>

                <i class="fas fa-chevron-down"
                   :style="activeDay === {{ $index }} ? 'transform:rotate(180deg)' : ''"></i>
            </div>

            <div class="card-body bg-light border-top"
                 x-show="activeDay === {{ $index }}"
                 x-cloak>

                <input type="text"
                       wire:model.blur="itinerary.{{ $index }}.title"
                       class="form-control mb-2"
                       placeholder="Day title">

                <textarea wire:model.blur="itinerary.{{ $index }}.activities"
                          class="form-control mb-2"
                          rows="3"></textarea>

                <div class="row">
                    <div class="col">
                        <input type="text"
                               wire:model.blur="itinerary.{{ $index }}.accommodation"
                               class="form-control form-control-sm"
                               placeholder="Accommodation">
                    </div>
                    <div class="col">
                        <input type="text"
                               wire:model.blur="itinerary.{{ $index }}.meals"
                               class="form-control form-control-sm">
                    </div>
                </div>

                <div class="mt-3">
                    <button type="button"
                            wire:click="duplicateDay({{ $index }})"
                            class="btn btn-link text-pink p-0 mr-3">
                        Duplicate
                    </button>

                    <button type="button"
                            wire:click="removeDay({{ $index }})"
                            onclick="confirm('Delete this day?') || event.stopImmediatePropagation()"
                            class="btn btn-link text-danger p-0">
                        Delete
                    </button>
                </div>

            </div>
        </div>

    @empty
        <div class="text-center p-5 border border-dashed">
            No itinerary added yet.
        </div>
    @endforelse
</div>

                        {{-- Overview --}}
                        <div class="card shadow-sm mb-4">
    <div class="card-header bg-white font-weight-bold small">
        PACKAGE OVERVIEW
    </div>
    <div class="card-body">

        <div wire:ignore>
            <input id="description"
                   type="hidden"
                   wire:model="description">

            <trix-editor input="description"
                         class="trix-content border-0 bg-light rounded shadow-inner"
                         style="min-height:200px;">
            </trix-editor>
        </div>

        @error('description')
            <small class="text-danger">{{ $message }}</small>
        @enderror

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