<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SafariPackage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public SafariPackage $package;
    
    public $name, $price, $duration_days, $status, $description;
    public $selected_destinations = []; 
    public $selected_categories = [];
    
    public $featured_image; 
    public $gallery_images = []; 
    public $itinerary = [];

    public function mount(SafariPackage $package)
    {
        $this->package = $package->load(['itineraries', 'photos', 'categories', 'destinations']);
        
        $this->name = $package->name;
        $this->price = $package->price;
        $this->duration_days = $package->duration_days;
        $this->status = $package->status;
        $this->description = $package->description;

        $this->selected_destinations = $this->package->destinations->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $this->selected_categories = $this->package->categories->pluck('id')->map(fn($id) => (string)$id)->toArray();
        
        // We add a 'temp_id' to every row so Livewire can track it reliably in the loop
        $this->itinerary = $this->package->itineraries->sortBy('day_number')->values()->map(fn($day) => [
            'temp_id' => Str::random(8),
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
            'temp_id' => Str::random(8),
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
        $newDay['temp_id'] = Str::random(8); // Give the duplicate its own identity
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
            ]);

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

            // Clean up and save Itineraries
            $this->package->itineraries()->delete();
            foreach ($this->itinerary as $day) { 
                // Remove the helper temp_id before database insertion
                $data = collect($day)->except('temp_id')->toArray();
                $this->package->itineraries()->create($data); 
            }
        });

        session()->flash('success', 'Safari updated successfully!');
        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data="{ 
    expandedId: null,
    price: @entangle('price'),
    discountRate: 0,
    destinations: @entangle('selected_destinations'),
    categories: @entangle('selected_categories'),

    get solo() { return (this.price * (1 - (this.discountRate / 100))).toFixed(2) },
    get couple() { return (this.price * 2 * (1 - (this.discountRate / 100))).toFixed(2) },
    get group() { return (this.price * 4 * (1 - (this.discountRate / 100))).toFixed(2) },

    toggle(id, list) {
        id = id.toString();
        const index = this[list].indexOf(id);
        if (index > -1) { this[list].splice(index, 1); } 
        else { this[list].push(id); }
    }
}">
    @section('title', 'Edit Safari: ' . $package->name)

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .badge-choice { cursor: pointer; transition: 0.2s; border: 1px solid #dee2e6; user-select: none; }
            .badge-choice.active-dest { background-color: #e83e8c; color: white; border-color: #e83e8c; }
            .badge-choice.active-cat { background-color: #343a40; color: white; border-color: #343a40; }
            .featured-preview { width: 100%; height: 220px; object-fit: cover; border-radius: 12px; cursor: pointer; transition: 0.3s; }
            .featured-preview:hover { opacity: 0.9; filter: brightness(0.8); }
            
            .itinerary-timeline { position: relative; padding-left: 20px; border-left: 2px dashed #e9ecef; margin-left: 15px; }
            .itinerary-day-node { position: absolute; left: -31px; width: 20px; height: 20px; background: #fff; border: 4px solid #e83e8c; border-radius: 50%; top: 20px; z-index: 2; }
            .card-itinerary { border: 1px solid #e9ecef; border-radius: 12px !important; transition: all 0.3s ease; overflow: hidden; margin-bottom: 1.5rem; }
            .card-itinerary.active { border-color: #e83e8c; box-shadow: 0 5px 15px rgba(232, 62, 140, 0.1); }
            .itinerary-header { background: #fff !important; cursor: pointer; padding: 1.25rem !important; }
            .itinerary-header:hover { background: #fdf2f7 !important; }
            .meal-tag { background: #f8f9fa; border-radius: 8px; padding: 12px; display: flex; align-items: center; gap: 10px; border: 1px solid #eee; }
            
            .text-pink { color: #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; }
            .btn-pink:hover { background-color: #d1347d; color: white; }
            .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
            .gallery-item { position: relative; height: 100px; }
            .gallery-item img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }
            .delete-overlay { position: absolute; top: -5px; right: -5px; background: #dc3545; color: white; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid white; }
            [x-cloak] { display: none !important; }
            .sticky-action-bar { position: sticky; bottom: 20px; z-index: 100; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border: 1px solid #eee; border-radius: 50px; padding: 10px 20px; }
        </style>
    @endpush

    <section class="content">
        <div class="container-fluid py-4">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-md-5">
                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                            <div class="card-header bg-white py-3"><h5 class="mb-0 font-weight-bold">Package Essentials</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold small text-muted">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control form-control-lg border-0 bg-light rounded-pill px-4">
                                </div>

                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label class="font-weight-bold small text-muted">BASE PRICE (USD)</label>
                                        <input type="number" x-model="price" class="form-control border-0 bg-light rounded-pill px-4">
                                    </div>
                                    <div class="col-6">
                                        <label class="font-weight-bold small text-muted">DISCOUNT %</label>
                                        <input type="number" x-model="discountRate" class="form-control border-0 bg-light rounded-pill px-4">
                                    </div>
                                </div>

                                <div class="p-3 rounded-lg mb-4" style="background: #fff5f8; border: 1px dashed #e83e8c;">
                                    <label class="small font-weight-bold text-pink d-block mb-2">LIVE QUOTE PREVIEW</label>
                                    <div class="d-flex justify-content-between mb-1 small"><span>Solo:</span><span class="font-weight-bold text-dark">$<span x-text="solo"></span></span></div>
                                    <div class="d-flex justify-content-between mb-1 small"><span>Couple:</span><span class="font-weight-bold text-dark">$<span x-text="couple"></span></span></div>
                                    <div class="d-flex justify-content-between"><span class="font-weight-bold text-pink">Group (4+):</span><span class="font-weight-bold text-pink">$<span x-text="group"></span></span></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold small text-muted">DESTINATIONS</label>
                                    <div class="d-flex flex-wrap">
                                        @foreach(\App\Models\Destination::orderBy('name')->get() as $dest)
                                            <div @click="toggle({{ $dest->id }}, 'destinations')" 
                                                 class="badge badge-choice px-3 py-2 m-1 rounded-pill"
                                                 :class="destinations.includes('{{ $dest->id }}') ? 'active-dest' : 'bg-white'">
                                                {{ $dest->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold small text-muted">CATEGORIES</label>
                                    <div class="d-flex flex-wrap">
                                        @foreach(\App\Models\SafariCategory::orderBy('name')->get() as $cat)
                                            <div @click="toggle({{ $cat->id }}, 'categories')" 
                                                 class="badge badge-choice px-3 py-2 m-1 rounded-pill"
                                                 :class="categories.includes('{{ $cat->id }}') ? 'active-cat' : 'bg-white'">
                                                {{ $cat->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
                            <div class="card-header bg-white py-3"><h5 class="mb-0 font-weight-bold">Gallery Images</h5></div>
                            <div class="card-body">
                                <div class="gallery-grid mb-3">
                                    @foreach($package->photos->where('type', 'gallery') as $photo)
                                        <div class="gallery-item" wire:key="photo-{{ $photo->id }}">
                                            <img src="{{ asset('storage/' . $photo->path) }}">
                                            <div class="delete-overlay" wire:click="deletePhoto({{ $photo->id }})"><i class="fas fa-times"></i></div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="custom-file">
                                    <input type="file" wire:model="gallery_images" multiple class="custom-file-input" id="galleryFiles">
                                    <label class="custom-file-label rounded-pill" for="galleryFiles">Choose images...</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Itinerary --}}
                    <div class="col-md-7">
                        {{-- Featured Photo Card --}}
                        <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 15px;">
                            <div class="card-body p-2 position-relative">
                                <div x-data="{ photoPreview: null }">
                                    <input type="file" wire:model="featured_image" class="d-none" x-ref="photo"
                                        @change="const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);">
                                    <img :src="photoPreview ? photoPreview : '{{ $package->photos->firstWhere('type', 'featured') ? asset('storage/' . $package->photos->firstWhere('type', 'featured')->path) : asset('front/images/placeholder.jpg') }}'" 
                                         class="featured-preview" @click="$refs.photo.click()">
                                    <div class="position-absolute" style="bottom: 20px; right: 20px;">
                                        <button type="button" class="btn btn-sm btn-light shadow rounded-pill px-3" @click="$refs.photo.click()">
                                            <i class="fas fa-camera mr-1"></i> Change Cover
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Itinerary Section --}}
                     {{-- Itinerary Section --}}
<div class="mb-4">

    <h4 class="font-weight-bold mb-4">Journey Itinerary</h4>

    {{-- Isolated Alpine Scope --}}
    <div x-data="{ open: null }" class="itinerary-timeline">

        @forelse($itinerary as $index => $day)
            <div class="card card-itinerary shadow-sm"
                 wire:key="itinerary-{{ $day['temp_id'] }}"
                 :class="open === '{{ $day['temp_id'] }}' ? 'active' : ''">

                <div class="itinerary-day-node"></div>

                {{-- HEADER --}}
                <div class="card-header itinerary-header"
                     @click="open = open === '{{ $day['temp_id'] }}' ? null : '{{ $day['temp_id'] }}'">

                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="text-center mr-3" style="min-width:45px;">
                                <span class="text-pink font-weight-bold h4 mb-0">
                                    {{ $day['day_number'] }}
                                </span>
                                <div class="small text-muted font-weight-bold" style="font-size:9px;">
                                    DAY
                                </div>
                            </div>

                            <div>
                                <h6 class="mb-0 font-weight-bold">
                                    {{ $day['title'] ?: 'New Safari Day' }}
                                </h6>
                                <span class="small text-muted">
                                    {{ Str::limit($day['accommodation'] ?: 'TBD', 25) }}
                                </span>
                            </div>
                        </div>

                        <i class="fas fa-chevron-down text-muted"
                           :style="open === '{{ $day['temp_id'] }}'
                                    ? 'transform: rotate(180deg); transition: 0.2s'
                                    : 'transition: 0.2s'">
                        </i>
                    </div>
                </div>

                {{-- BODY --}}
                <div x-show="open === '{{ $day['temp_id'] }}'"
                     x-cloak
                     style="display:none;">

                    <div class="card-body border-top">

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted">
                                DAY TITLE
                            </label>
                            <input type="text"
                                   wire:model.live="itinerary.{{ $index }}.title"
                                   class="form-control border-0 bg-light rounded-pill px-3">
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted">
                                ACTIVITIES
                            </label>
                            <textarea wire:model.live="itinerary.{{ $index }}.activities"
                                      class="form-control border-0 bg-light rounded-lg"
                                      rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <input type="text"
                                       wire:model.live="itinerary.{{ $index }}.meals"
                                       class="form-control form-control-sm bg-light border-0"
                                       placeholder="Meals">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text"
                                       wire:model.live="itinerary.{{ $index }}.accommodation"
                                       class="form-control form-control-sm bg-light border-0"
                                       placeholder="Accommodation">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3 pt-3 border-top">
                            <button type="button"
                                    wire:click="duplicateDay({{ $index }})"
                                    class="btn btn-sm btn-link text-muted">
                                Duplicate
                            </button>

                            <button type="button"
                                    wire:click="removeDay({{ $index }})"
                                    onclick="confirm('Delete this day?') || event.stopImmediatePropagation()"
                                    class="btn btn-sm btn-link text-danger">
                                Delete Day
                            </button>
                        </div>

                    </div>
                </div>

            </div>

        @empty
            <div class="p-5 text-center bg-white border rounded-lg">
                <p class="text-muted mb-0">No days added yet.</p>
            </div>
        @endforelse

    </div>

    {{-- Add Day Button --}}
    <button type="button"
            wire:click="addDay"
            class="btn btn-block btn-outline-pink py-3 rounded-pill font-weight-bold mt-3"
            style="border-style:dashed;">
        + ADD DAY {{ count($itinerary) + 1 }}
    </button>

</div>

                        {{-- Trix Editor --}}
                        <div class="card shadow-sm border-0 mb-5" style="border-radius: 15px;">
                            <div class="card-header bg-white py-3"><h5 class="mb-0 font-weight-bold">Detailed Overview</h5></div>
                            <div class="card-body">
                                <div wire:ignore x-data="{ value: @entangle('description'), isSet: false }" 
                                     x-init="$refs.trix.editor.loadHTML(value); $watch('value', v => { if (!isSet) $refs.trix.editor.loadHTML(v); isSet = false; })" 
                                     @trix-change="isSet = true; value = $event.target.value">
                                    <trix-editor x-ref="trix" class="trix-content border-0 bg-light p-3 rounded"></trix-editor>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Bar --}}
                        <div class="sticky-action-bar d-flex align-items-center justify-content-between shadow-lg">
                            <div class="d-none d-md-block">
                                <span class="text-muted small">Current status: <strong>{{ strtoupper($status) }}</strong></span>
                            </div>
                            <div class="d-flex">
                                <select wire:model="status" class="form-control-sm border-0 bg-light rounded-pill px-3 mr-3" style="width: 120px;">
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                                <button type="submit" class="btn btn-pink rounded-pill px-5 font-weight-bold shadow-sm">
                                    <span wire:loading.remove>SAVE CHANGES</span>
                                    <span wire:loading><i class="fas fa-spinner fa-spin"></i></span>
                                </button>
                            </div>
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