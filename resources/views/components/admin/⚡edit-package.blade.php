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

    public $openDay = null;
    
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
        $this->discountRate = $package->discount_rate ?? 0;

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

    // In your Livewire component class
public function updatedGalleryImages()
{
    // This is optional, but it clears the "localPreviews" in Alpine 
    // once Livewire has successfully received the files.
    $this->dispatch('clear-previews');
}

 public function addDay()
{
    $newId = Str::random(8);

    $this->itinerary[] = [
        'temp_id' => $newId,
        'day_number' => count($this->itinerary) + 1,
        'title' => '',
        'activities' => '',
        'meals' => 'Breakfast, Lunch, Dinner',
        'accommodation' => ''
    ];

    // Alpine will auto-expand the new day
    $this->dispatch('day-added', id: $newId);
}

public function duplicateDay($index)
{
    $newDay = $this->itinerary[$index];
    $newDay['temp_id'] = Str::random(8);
    array_splice($this->itinerary, $index + 1, 0, [$newDay]);
    $this->reorderDays();

    // Open the duplicated day in Alpine
    $this->dispatch('day-added', id: $newDay['temp_id']);
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
            'discountRate' => 'required|numeric|between:0,100',
            'description' => 'required',
            'gallery_images.*' => 'image|max:2048',
        ]);

        DB::transaction(function () {
            $this->package->update([
                'name' => $this->name, 
                'slug' => Str::slug($this->name),
                'price' => $this->price, 
                'discount_rate' => $this->discountRate,
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
            /* Smooth transitions for everything */
            * { transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s; }

            .badge-choice { cursor: pointer; border: 1.5px solid #eee; background: #fff; color: #666; font-weight: 500; }
            .badge-choice:hover { border-color: #e83e8c; color: #e83e8c; }
            .badge-choice.active-dest { background-color: #e83e8c; color: white; border-color: #e83e8c; box-shadow: 0 4px 10px rgba(232, 62, 140, 0.2); }
            .badge-choice.active-cat { background-color: #343a40; color: white; border-color: #343a40; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
            
            .featured-preview { width: 100%; height: 260px; object-fit: cover; border-radius: 15px; cursor: pointer; }
            .image-container:hover .featured-preview { filter: brightness(0.8); }

            .itinerary-timeline { position: relative; padding-left: 25px; border-left: 2px solid #f1f1f1; margin-left: 15px; }
            .itinerary-day-node { position: absolute; left: -36px; width: 20px; height: 20px; background: #fff; border: 4px solid #e83e8c; border-radius: 50%; top: 25px; z-index: 2; transition: transform 0.3s; }
            .card-itinerary:hover .itinerary-day-node { transform: scale(1.2); }
            
            .card-itinerary { border: 1px solid #f0f0f0; border-radius: 16px !important; margin-bottom: 1.2rem; background: #fff; }
            .card-itinerary.active { border-color: #e83e8c; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
            
            .form-control:focus { box-shadow: 0 0 0 3px rgba(232, 62, 140, 0.1); border-color: #e83e8c !important; background: #fff !important; }
            
            .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px; }
            .gallery-item { position: relative; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; border: 1px solid #eee; }
            .delete-overlay { position: absolute; top: 5px; right: 5px; background: rgba(220, 53, 69, 0.9); color: white; border-radius: 8px; width: 26px; height: 26px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px); cursor: pointer; opacity: 0; transition: 0.3s; }
            .gallery-item:hover .delete-overlay { opacity: 1; }

            .sticky-action-bar { position: sticky; bottom: 25px; z-index: 1000; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border: 1px solid rgba(0,0,0,0.05); border-radius: 20px; padding: 12px 25px; }
            
            .text-pink { color: #e83e8c !important; }
            .btn-pink { background: linear-gradient(135deg, #e83e8c 0%, #d1347d 100%); color: white; border: none; }
            .btn-pink:hover { transform: translateY(-1px); box-shadow: 0 5px 15px rgba(232, 62, 140, 0.3); color: white; }
            
            [x-cloak] { display: none !important; }
            .trix-content { min-height: 250px !important; border-radius: 12px !important; }
        </style>
    @endpush

    <section class="content">
        <div class="container-fluid py-4">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-md-5">
                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px;">
                            <div class="card-header bg-transparent border-0 pt-4 px-4"><h5 class="mb-0 font-weight-bold">Package Essentials</h5></div>
                            <div class="card-body px-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold small text-muted tracking-widest">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" placeholder="e.g. 7-Day Serengeti Classic" class="form-control form-control-lg border-0 bg-light rounded-pill px-4">
                                </div>

                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label class="font-weight-bold small text-muted">BASE PRICE ($)</label>
                                        <input type="number" x-model="price" class="form-control border-0 bg-light rounded-pill px-4">
                                    </div>
                                    <div class="col-6">
                                        <label class="font-weight-bold small text-muted">DISCOUNT %</label>
                                        <input type="number" x-model="discountRate" class="form-control border-0 bg-light rounded-pill px-4">
                                    </div>
                                </div>

                                <div class="p-4 rounded-xl mb-4" style="background: linear-gradient(135deg, #fff5f8 0%, #fff 100%); border: 1px solid #fce4ec;">
                                    <label class="small font-weight-bold text-pink d-block mb-3"><i class="fas fa-calculator mr-2"></i> LIVE QUOTE PREVIEW</label>
                                    <div class="d-flex justify-content-between mb-2 small text-muted"><span>Individual (Solo)</span><span class="font-weight-bold text-dark">$<span x-text="solo"></span></span></div>
                                    <div class="d-flex justify-content-between mb-2 small text-muted"><span>Couple (Per Person)</span><span class="font-weight-bold text-dark">$<span x-text="couple"></span></span></div>
                                    <hr class="my-2" style="border-top: 1px dashed #f8bbd0;">
                                    <div class="d-flex justify-content-between"><span class="font-weight-bold text-pink">Group (4+ People)</span><span class="font-weight-bold text-pink" style="font-size: 1.1rem;">$<span x-text="group"></span></span></div>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold small text-muted">DESTINATIONS</label>
                                    <div class="d-flex flex-wrap mt-2">
                                        @foreach(\App\Models\Destination::orderBy('name')->get() as $dest)
                                            <div @click="toggle({{ $dest->id }}, 'destinations')" 
                                                 class="badge badge-choice px-3 py-2 m-1 rounded-pill shadow-sm"
                                                 :class="destinations.includes('{{ $dest->id }}') ? 'active-dest' : ''">
                                                {{ $dest->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold small text-muted">CATEGORIES</label>
                                    <div class="d-flex flex-wrap mt-2">
                                        @foreach(\App\Models\SafariCategory::orderBy('name')->get() as $cat)
                                            <div @click="toggle({{ $cat->id }}, 'categories')" 
                                                 class="badge badge-choice px-3 py-2 m-1 rounded-pill shadow-sm"
                                                 :class="categories.includes('{{ $cat->id }}') ? 'active-cat' : ''">
                                                {{ $cat->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                      <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px;">
    <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 font-weight-bold">Gallery Images</h5>
        <span class="badge badge-light px-3 rounded-pill text-muted">
            {{ $package->photos->where('type', 'gallery')->count() + count($gallery_images) }} Images
        </span>
    </div>
    <div class="card-body">
        <div class="gallery-grid mb-4">
            {{-- Existing Photos --}}
            @foreach($package->photos->where('type', 'gallery') as $photo)
                <div class="gallery-item shadow-sm" wire:key="existing-photo-{{ $photo->id }}">
                    <img src="{{ asset('storage/' . $photo->path) }}">
                    <div class="delete-overlay" wire:click="deletePhoto({{ $photo->id }})">
                        <i class="fas fa-trash-alt fa-xs"></i>
                    </div>
                </div>
            @endforeach

            {{-- New Uploads Previews (Livewire Temporary) --}}
            @if ($gallery_images)
                @foreach($gallery_images as $index => $image)
                    <div class="gallery-item" wire:key="upload-preview-{{ $index }}" style="border: 2px solid #e83e8c;">
                        {{-- temporaryUrl() works for images out of the box --}}
                        <img src="{{ $image->temporaryUrl() }}">
                        <div class="position-absolute" style="top:5px; left:5px;">
                            <span class="badge badge-pink">New</span>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Loading State Spinner --}}
            <div wire:loading wire:target="gallery_images" class="gallery-item" style="border: 2px dashed #ccc;">
                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                    <div class="spinner-border spinner-border-sm text-pink"></div>
                </div>
            </div>
        </div>

        <div class="custom-file">
            {{-- 
                Removed the complex handleFiles Alpine logic. 
                Livewire handles the upload, and the @if($gallery_images) 
                block above shows the previews once they arrive.
            --}}
            <input type="file" wire:model="gallery_images" multiple class="custom-file-input" id="galleryFiles">
            <label class="custom-file-label rounded-pill border-0 bg-light" for="galleryFiles">
                {{ count($gallery_images) > 0 ? count($gallery_images) . ' new images selected' : 'Add gallery images...' }}
            </label>
            @error('gallery_images.*') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
    </div>
</div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-md-7">
                        <div class="card shadow-sm border-0 mb-4 overflow-hidden" style="border-radius: 20px;">
                            <div class="card-body p-2 image-container">
                                <div x-data="{ photoPreview: null }">
                                    <input type="file" wire:model="featured_image" class="d-none" x-ref="photo"
                                        @change="const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);">
                                    <img :src="photoPreview ? photoPreview : '{{ $package->photos->firstWhere('type', 'featured') ? asset('storage/' . $package->photos->firstWhere('type', 'featured')->path) : asset('front/images/placeholder.jpg') }}'" 
                                         class="featured-preview shadow-sm" @click="$refs.photo.click()">
                                    <div class="position-absolute" style="bottom: 25px; right: 25px;">
                                        <button type="button" class="btn btn-light shadow-lg rounded-pill px-4 py-2 font-weight-bold" @click="$refs.photo.click()">
                                            <i class="fas fa-image mr-2 text-pink"></i> Edit Cover Photo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                       {{-- Itinerary Section --}}
<div class="mb-4">
    <h4 class="font-weight-bold mb-4">Journey Itinerary</h4>

    <div x-data="{ 
            open: null,
            toggleDay(id) { this.open = (this.open === id ? null : id); } 
         }"
         @day-added.window="open = $event.detail.id"
         class="itinerary-timeline">

        @forelse($itinerary as $index => $day)
            <div class="card card-itinerary shadow-sm mb-3"
                 wire:key="itinerary-item-{{ $day['temp_id'] }}"
                 :class="open === '{{ $day['temp_id'] }}' ? 'active shadow-md' : ''">

                <div class="itinerary-day-node"></div>

                {{-- HEADER --}}
                <div class="card-header itinerary-header border-0 cursor-pointer"
                     @click="toggleDay('{{ $day['temp_id'] }}')">

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="text-center mr-3" style="min-width:45px;">
                                <span class="text-pink font-weight-bold h4 mb-0">{{ $day['day_number'] }}</span>
                                <div class="small text-muted" style="font-size:9px;">DAY</div>
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold">{{ $day['title'] ?: 'New Safari Day' }}</h6>
                                <span class="small text-muted">{{ Str::limit($day['accommodation'] ?: 'TBD', 30) }}</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-muted transition-all"
                           :style="open === '{{ $day['temp_id'] }}' ? 'transform: rotate(180deg)' : ''"></i>
                    </div>
                </div>

                {{-- BODY --}}
                <div x-show="open === '{{ $day['temp_id'] }}'" x-collapse x-cloak>
                    <div class="card-body border-top bg-white">
                        <div class="form-group mb-3">
                            <label class="small text-muted">DAY TITLE</label>
                            <input type="text"
                                   wire:model.blur="itinerary.{{ $index }}.title"
                                   class="form-control border-0 bg-light rounded-pill px-3">
                        </div>

                        <div class="form-group mb-4">
                            <label class="small text-muted">ACTIVITIES</label>
                            <textarea wire:model.blur="itinerary.{{ $index }}.activities"
                                      class="form-control border-0 bg-light rounded-lg" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="small text-muted">Meals</label>
                                <input type="text" wire:model.blur="itinerary.{{ $index }}.meals"
                                       class="form-control form-control-sm bg-light border-0">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="small text-muted">Accommodation</label>
                                <input type="text" wire:model.blur="itinerary.{{ $index }}.accommodation"
                                       class="form-control form-control-sm bg-light border-0">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3 pt-3 border-top">
                            <button type="button" wire:click="duplicateDay({{ $index }})"
                                    class="btn btn-sm btn-link text-muted">Duplicate</button>
                            <button type="button" wire:click="removeDay({{ $index }})"
                                    class="btn btn-sm btn-link text-danger">Delete Day</button>
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

    <button type="button"
            wire:click="addDay"
            class="btn btn-block btn-outline-pink py-3 rounded-pill font-weight-bold mt-3 shadow-sm"
            style="border-style:dashed; background: #fff;">
        <i class="fas fa-plus mr-2"></i> ADD DAY {{ count($itinerary) + 1 }}
    </button>
</div>

                        <div class="card shadow-sm border-0 mb-5" style="border-radius: 20px;">
                            <div class="card-header bg-transparent border-0 pt-4 px-4"><h5 class="mb-0 font-weight-bold">Full Package Overview</h5></div>
                            <div class="card-body px-4 pb-4">
                                <div wire:ignore x-data="{ value: @entangle('description'), isSet: false }" 
                                     x-init="$refs.trix.editor.loadHTML(value); $watch('value', v => { if (!isSet) $refs.trix.editor.loadHTML(v); isSet = false; })" 
                                     @trix-change="isSet = true; value = $event.target.value">
                                    <trix-editor x-ref="trix" class="trix-content border-0 bg-light p-3 rounded-xl"></trix-editor>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Save Bar --}}
                        <div class="sticky-action-bar d-flex align-items-center justify-content-between shadow-lg">
                            <div class="d-none d-md-flex align-items-center">
                                <div class="mr-3 p-2 bg-light rounded-circle"><i class="fas fa-save text-pink"></i></div>
                                <div>
                                    <p class="mb-0 small font-weight-bold text-dark">Package Status</p>
                                    <span class="text-muted small">{{ strtoupper($status) }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <select wire:model="status" class="form-control-sm border-0 bg-light rounded-pill px-3 mr-4" style="height: 40px; width: 130px; font-weight: 600;">
                                    <option value="draft">📁 Draft</option>
                                    <option value="published">🚀 Published</option>
                                </select>
                                <button type="submit" class="btn btn-pink rounded-pill px-5 font-weight-bold py-2 shadow">
                                    <span wire:loading.remove wire:target="save">UPDATE SAFARI</span>
                                    <span wire:loading wire:target="save"><i class="fas fa-circle-notch fa-spin mr-2"></i> SAVING...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>