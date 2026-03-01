<?php

use Livewire\Volt\Component; // Using the Volt-specific class
use Livewire\WithFileUploads;
use App\Models\SafariPackage;
use App\Models\Destination;
use App\Models\SafariCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public SafariPackage $package;
    
    public $name, $price, $duration_days, $status, $description;
    public $selected_destinations = []; 
    public $selected_categories = [];
    
    // Note: Discounts are now purely for the Alpine.js preview, 
    // we don't save these to the SafariPackage model anymore.
    public $featured_image; 
    public $gallery_images = []; 
    public $itinerary = [];

    public function mount(SafariPackage $package)
    {
        // Eager load the new many-to-many relationships
        $this->package = $package->load(['itineraries', 'photos', 'destinations', 'categories']);
        
        $this->name = $package->name;
        $this->price = $package->price;
        $this->duration_days = $package->duration_days;
        $this->status = $package->status;
        $this->description = $package->description;

        // Pluck IDs from the pivot tables
        $this->selected_destinations = $this->package->destinations->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->selected_categories = $this->package->categories->pluck('id')->map(fn($id) => (string)$id)->toArray();
        
        $this->itinerary = $this->package->itineraries->sortBy('day_number')->map(fn($day) => [
            'day_number' => $day->day_number,
            'title' => $day->title,
            'activities' => $day->activities,
            'meals' => $day->meals,
            'accommodation' => $day->accommodation
        ])->toArray();
    }

    public function deletePhoto($photoId)
    {
        $photo = $this->package->photos()->find($photoId);
        if ($photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
            $this->package->load('photos'); 
        }
    }

    public function addDay()
    {
        $this->itinerary[] = [
            'day_number' => count($this->itinerary) + 1,
            'title' => '', 
            'activities' => '', 
            'meals' => 'Breakfast, Lunch, Dinner', 
            'accommodation' => ''
        ];
    }

    public function duplicateDay($index)
    {
        $newDay = $this->itinerary[$index];
        array_splice($this->itinerary, $index + 1, 0, [$newDay]);
        $this->reorderDays();
    }

    public function removeDay($index)
    {
        unset($this->itinerary[$index]);
        $this->reorderDays();
    }

    protected function reorderDays()
    {
        $this->itinerary = array_values($this->itinerary);
        foreach ($this->itinerary as $k => $v) { 
            $this->itinerary[$k]['day_number'] = $k + 1; 
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:safari_packages,name,' . $this->package->id,
            'price' => 'required|numeric',
            'selected_destinations' => 'required|array|min:1',
            'selected_categories' => 'required|array|min:1',
            'description' => 'required',
            'gallery_images.*' => 'image|max:2048',
        ]);

        DB::transaction(function () {
            $this->package->update([
                'name' => $this->name, 
                'slug' => Str::slug($this->name),
                'price' => $this->price, 
                'duration_days' => count($this->itinerary),
                'status' => $this->status,
                'description' => $this->description,
                // No discount columns here!
            ]);

            // Sync Many-to-Many Relationships to the pivot tables we built
            $this->package->destinations()->sync($this->selected_destinations);
            $this->package->categories()->sync($this->selected_categories);

            if ($this->featured_image) {
                $old = $this->package->photos()->where('type', 'featured')->first();
                if ($old) { Storage::disk('public')->delete($old->path); $old->delete(); }
                $path = $this->featured_image->store('safaris/featured', 'public');
                $this->package->photos()->create(['path' => $path, 'type' => 'featured']);
            }

            foreach ($this->gallery_images as $image) {
                $path = $image->store('safaris/gallery', 'public');
                $this->package->photos()->create(['path' => $path, 'type' => 'gallery']);
            }

            $this->package->itineraries()->delete();
            foreach ($this->itinerary as $day) { 
                $this->package->itineraries()->create($day); 
            }
        });

        session()->flash('success', 'Safari updated successfully!');
        return redirect()->route('admin.packages.index');
    }
}; ?>

{{-- UI with Alpine.js handling the "Fake" Discounts --}}
<div x-data="{ 
    activeDay: 0,
    basePrice: @entangle('price'),
    discountType: 'none',
    get previewPrice() {
        let p = parseFloat(this.basePrice) || 0;
        if(this.discountType === 'solo') return (p * 0.90).toFixed(2);
        if(this.discountType === 'couple') return (p * 0.85).toFixed(2);
        if(this.discountType === 'group') return (p * 0.75).toFixed(2);
        return p.toFixed(2);
    }
}">
    @section('title', 'Edit Safari: ' . $package->name)

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .featured-preview { width: 100%; height: 220px; object-fit: cover; border-radius: 8px; cursor: pointer; }
            .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
            .gallery-item { position: relative; height: 100px; }
            .gallery-item img { width: 100%; height: 100%; object-fit: cover; border-radius: 6px; }
            .delete-overlay { position: absolute; top: -5px; right: -5px; background: #dc3545; color: white; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid white; }
            .trix-content { min-height: 250px !important; background: white; }
            .border-pink { border-left: 4px solid #e83e8c !important; }
            .text-pink { color: #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .itinerary-header { cursor: pointer; background: #f8f9fa; transition: 0.2s; border-bottom: 1px solid #eee; }
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    <section class="content">
        <div class="container-fluid py-4">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-md-5">
                        <div class="card card-outline card-pink shadow-sm mb-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold text-pink">Package Basics</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small text-muted">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="font-weight-bold small text-muted">PRICE (USD)</label>
                                        <input type="number" wire:model="price" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="font-weight-bold small text-muted">STATUS</label>
                                        <select wire:model="status" class="form-control">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Alpine.js Discount Preview Section --}}
                                <div class="bg-light p-3 rounded mb-3 border">
                                    <label class="small font-weight-bold text-muted d-block mb-2">LIVE DISCOUNT PREVIEW</label>
                                    <div class="btn-group btn-group-toggle w-100 mb-2">
                                        <button type="button" class="btn btn-xs btn-outline-secondary" :class="discountType === 'none' && 'active'" @click="discountType = 'none'">Regular</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary" :class="discountType === 'solo' && 'active'" @click="discountType = 'solo'">Solo (-10%)</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary" :class="discountType === 'couple' && 'active'" @click="discountType = 'couple'">Couple (-15%)</button>
                                    </div>
                                    <h4 class="mb-0 text-pink font-weight-bold">$<span x-text="previewPrice"></span></h4>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small text-muted">DESTINATIONS</label>
                                    <select wire:model="selected_destinations" class="form-control" multiple style="height: 120px">
                                        @foreach(\App\Models\Destination::orderBy('name')->get() as $dest)
                                            <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold small text-muted">CATEGORIES</label>
                                    <select wire:model="selected_categories" class="form-control" multiple style="height: 100px">
                                        @foreach(\App\Models\SafariCategory::all() as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Gallery Section... --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Gallery Images</h5></div>
                            <div class="card-body">
                                <div class="gallery-grid mb-3">
                                    @foreach($package->photos->where('type', 'gallery') as $photo)
                                        <div class="gallery-item" wire:key="photo-{{ $photo->id }}">
                                            <img src="{{ asset('storage/' . $photo->path) }}">
                                            <div class="delete-overlay" wire:click="deletePhoto({{ $photo->id }})"><i class="fas fa-times"></i></div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="file" wire:model="gallery_images" multiple class="form-control-file border p-1 rounded">
                            </div>
                        </div>
                    </div>

                    {{-- Right Column (Featured Image, Itinerary, Trix)... --}}
                    <div class="col-md-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body text-center p-2">
                                <div x-data="{ photoPreview: null }">
                                    <input type="file" wire:model="featured_image" class="d-none" x-ref="photo"
                                        @change="const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);">
                                    <img :src="photoPreview ? photoPreview : '{{ $package->photos->firstWhere('type', 'featured') ? asset('storage/' . $package->photos->firstWhere('type', 'featured')->path) : asset('front/images/placeholder.jpg') }}'" 
                                         class="featured-preview border" @click="$refs.photo.click()">
                                </div>
                            </div>
                        </div>

                        {{-- Itinerary Loader... --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="font-weight-bold mb-0">Itinerary Builder</h5>
                                <span class="badge badge-pink px-3">{{ count($itinerary) }} Days</span>
                            </div>
                            <div class="itinerary-scroll-container mb-3" style="max-height: 480px; overflow-y: auto; padding: 10px; background: #fdfdfd; border: 1px solid #eee; border-radius: 8px;">
                                @foreach($itinerary as $index => $day)
                                    <div class="card mb-2 border-pink shadow-sm" wire:key="itinerary-{{ $index }}">
                                        <div class="card-header itinerary-header p-3 d-flex align-items-center justify-content-between" 
                                             @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                            <div class="d-flex align-items-center">
                                                <span class="badge badge-pink mr-3">DAY {{ $day['day_number'] }}</span>
                                                <span class="small font-weight-bold text-dark">{{ $day['title'] ?: 'Untitled Day' }}</span>
                                            </div>
                                            <i class="fas fa-chevron-down text-muted" :class="activeDay === {{ $index }} ? 'fa-rotate-180' : ''"></i>
                                        </div>
                                        <div class="card-body p-3 bg-white" x-show="activeDay === {{ $index }}" x-cloak>
                                            <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control mb-2" placeholder="Title">
                                            <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control mb-2" rows="3" placeholder="Activities"></textarea>
                                            <div class="row">
                                                <div class="col-6"><input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control form-control-sm" placeholder="Meals"></div>
                                                <div class="col-6"><input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm" placeholder="Hotel"></div>
                                            </div>
                                            <div class="d-flex justify-content-end mt-2">
                                                <button type="button" wire:click="duplicateDay({{ $index }})" class="btn btn-xs btn-outline-info mr-2">Duplicate</button>
                                                <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-xs btn-outline-danger">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" wire:click="addDay" class="btn btn-outline-pink btn-block mb-4 shadow-sm font-weight-bold">ADD DAY</button>
                        </div>

                        {{-- Trix and Submit... --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <div wire:ignore x-data="{ value: @entangle('description'), isSet: false }" 
                                     x-init="$refs.trix.editor.loadHTML(value); $watch('value', v => { if (!isSet) $refs.trix.editor.loadHTML(v); isSet = false; })" 
                                     @trix-change="isSet = true; value = $event.target.value">
                                    <trix-editor x-ref="trix" class="trix-content"></trix-editor>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block shadow-lg py-3 font-weight-bold mb-5">
                            <span wire:loading.remove>UPDATE PACKAGE</span>
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