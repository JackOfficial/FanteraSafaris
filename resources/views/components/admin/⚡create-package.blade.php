<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SafariPackage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public $name, $price = 0, $duration_days, $status = 'draft', $description = '';
    public $selected_destinations = []; 
    public $selected_categories = [];
    
    public $featured_image; 
    public $gallery_images = []; 
    public $itinerary = [];
    public $discountRate = 0;

    public function mount()
    {
        // Initialize with one empty day by default
        $this->addDay();
    }

    public function updatedGalleryImages()
    {
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
        $this->dispatch('day-added', id: $newId);
    }

    public function duplicateDay($index)
    {
        $newDay = $this->itinerary[$index];
        $newDay['temp_id'] = Str::random(8);
        array_splice($this->itinerary, $index + 1, 0, [$newDay]);
        $this->reorderDays();
        $this->dispatch('day-added', id: $newDay['temp_id']);
    }

    public function removeDay($index)
    {
        unset($this->itinerary[$index]);
        $this->reorderDays();
    }

  protected function reorderDays()
{
    // array_values strictly resets the 0, 1, 2 indices
    $this->itinerary = array_values($this->itinerary);
    foreach ($this->itinerary as $k => $v) {
        $this->itinerary[$k]['day_number'] = $k + 1;
    }
}

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:safari_packages,name',
            'price' => 'required|numeric',
            'selected_destinations' => 'required|array|min:1',
            'selected_categories' => 'required|array|min:1',
            'discountRate' => 'required|numeric|between:0,100',
            'description' => 'required',
            'featured_image' => 'required|image|max:2048', // Required for new creations
            'gallery_images.*' => 'image|max:2048',
        ]);

        DB::transaction(function () {
            $package = SafariPackage::create([
                'name' => $this->name, 
                'slug' => Str::slug($this->name),
                'price' => $this->price, 
                'discount_rate' => $this->discountRate,
                'duration_days' => count($this->itinerary),
                'status' => $this->status,
                'description' => $this->description,
            ]);

            $package->destinations()->sync($this->selected_destinations);
            $package->categories()->sync($this->selected_categories);

            // Save Featured Image
            $path = $this->featured_image->store('safaris/featured', 'public');
            $package->photos()->create(['path' => $path, 'type' => 'featured']);

            // Save Gallery
            foreach ($this->gallery_images as $image) {
                $path = $image->store('safaris/gallery', 'public');
                $package->photos()->create(['path' => $path, 'type' => 'gallery']);
            }

            // Save Itineraries
            foreach ($this->itinerary as $day) { 
                $data = collect($day)->except('temp_id')->toArray();
                $package->itineraries()->create($data); 
            }
        });

        session()->flash('success', 'Safari created successfully!');
        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data="{
    expandedId: null,
    price: @entangle('price'),
    discountRate: @entangle('discountRate'),
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
    @section('title', 'Create New Safari')

    {{-- Reusing your existing styles --}}
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            * { transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s; }
            .badge-choice { cursor: pointer; border: 1.5px solid #eee; background: #fff; color: #666; font-weight: 500; }
            .badge-choice:hover { border-color: #e83e8c; color: #e83e8c; }
            .badge-choice.active-dest { background-color: #e83e8c; color: white; border-color: #e83e8c; box-shadow: 0 4px 10px rgba(232, 62, 140, 0.2); }
            .badge-choice.active-cat { background-color: #343a40; color: white; border-color: #343a40; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
            .featured-preview { width: 100%; height: 260px; object-fit: cover; border-radius: 15px; cursor: pointer; border: 2px dashed #ddd; }
            .itinerary-timeline { position: relative; padding-left: 25px; border-left: 2px solid #f1f1f1; margin-left: 15px; }
            .itinerary-day-node { position: absolute; left: -36px; width: 20px; height: 20px; background: #fff; border: 4px solid #e83e8c; border-radius: 50%; top: 25px; z-index: 2; }
            .card-itinerary { border: 1px solid #f0f0f0; border-radius: 16px !important; margin-bottom: 1.2rem; background: #fff; }
            .card-itinerary.active { border-color: #e83e8c; }
            .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px; }
            .gallery-item { position: relative; aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; border: 1px solid #eee; }
            .sticky-action-bar { position: sticky; bottom: 25px; z-index: 1000; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); border: 1px solid rgba(0,0,0,0.05); border-radius: 20px; padding: 12px 25px; }
            .text-pink { color: #e83e8c !important; }
            .btn-pink { background: linear-gradient(135deg, #e83e8c 0%, #d1347d 100%); color: white; border: none; }
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
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
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

                                {{-- Live Quote Preview --}}
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
                                    @error('selected_destinations') <span class="text-danger small">{{ $message }}</span> @enderror
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
                                    @error('selected_categories') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 20px;">
                            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 font-weight-bold">Gallery Images</h5>
                            </div>
                            <div class="card-body">
                                <div class="gallery-grid mb-4">
                                    @if ($gallery_images)
                                        @foreach($gallery_images as $index => $image)
                                            <div class="gallery-item" wire:key="upload-preview-{{ $index }}" style="border: 2px solid #e83e8c;">
                                                <img src="{{ $image->temporaryUrl() }}" class="w-100 h-100 object-fit-cover">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                <div class="custom-file">
                                    <input type="file" wire:model="gallery_images" multiple class="custom-file-input" id="galleryFiles">
                                    <label class="custom-file-label rounded-pill border-0 bg-light" for="galleryFiles">
                                        {{ count($gallery_images) > 0 ? count($gallery_images) . ' images selected' : 'Select gallery images...' }}
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
                                    
                                    <div @click="$refs.photo.click()" class="featured-preview d-flex align-items-center justify-content-center bg-light shadow-sm overflow-hidden">
                                        <template x-if="photoPreview">
                                            <img :src="photoPreview" class="w-100 h-100 object-fit-cover">
                                        </template>
                                        <template x-if="!photoPreview">
                                            <div class="text-center text-muted">
                                                <i class="fas fa-cloud-upload-alt fa-3x mb-2 text-pink"></i>
                                                <p class="mb-0 font-weight-bold">Click to upload cover photo</p>
                                            </div>
                                        </template>
                                    </div>
                                    @error('featured_image') <div class="p-2 text-danger small">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                       {{-- Itinerary Section --}}
<div class="mb-4">
    <h4 class="font-weight-bold mb-4">Journey Itinerary</h4>
    
    {{-- We scope the open state to this container and use a more robust toggle --}}
    <div x-data="{ 
            openId: null, 
            init() {
                // Automatically open the first day on load
                if(this.openId === null && @js(count($itinerary)) > 0) {
                    this.openId = @js($itinerary[0]['temp_id'] ?? null);
                }
            }
         }"
         @day-added.window="openId = $event.detail.id"
         class="itinerary-timeline">

        @foreach($itinerary as $index => $day)
            {{-- CRITICAL: wire:key must be on the outermost element of the loop --}}
            <div class="card card-itinerary shadow-sm mb-3" 
                 wire:key="itinerary-day-{{ $day['temp_id'] }}" 
                 :class="openId === '{{ $day['temp_id'] }}' ? 'active shadow-md' : ''">
                
                <div class="itinerary-day-node"></div>
                
                {{-- Header Toggle --}}
                <div class="card-header itinerary-header border-0 cursor-pointer p-3" 
                     @click="openId = (openId === '{{ $day['temp_id'] }}' ? null : '{{ $day['temp_id'] }}')">
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="text-center mr-3" style="min-width:45px;">
                                <span class="text-pink font-weight-bold h4 mb-0">{{ $day['day_number'] }}</span>
                                <div class="small text-muted" style="font-size:9px;">DAY</div>
                            </div>
                            <div>
                                <h6 class="mb-0 font-weight-bold">
                                    {{ $day['title'] ?: 'New Safari Day' }}
                                </h6>
                            </div>
                        </div>
                        <i class="fas fa-chevron-down text-muted transition-all" 
                           :style="openId === '{{ $day['temp_id'] }}' ? 'transform: rotate(180deg)' : ''"></i>
                    </div>
                </div>

                {{-- Content --}}
                <div x-show="openId === '{{ $day['temp_id'] }}'" x-collapse x-cloak>
                    <div class="card-body border-top bg-white">
                        <div class="form-group mb-3">
                            <label class="small text-muted">DAY TITLE</label>
                            <input type="text" 
                                   wire:model.defer="itinerary.{{ $index }}.title" 
                                   class="form-control border-0 bg-light rounded-pill px-3">
                        </div>
                        
                        <div class="form-group mb-4">
                            <label class="small text-muted">ACTIVITIES</label>
                            <textarea wire:model.defer="itinerary.{{ $index }}.activities" 
                                      class="form-control border-0 bg-light rounded-lg" 
                                      rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="small text-muted">Meals</label>
                                <input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control form-control-sm bg-light border-0">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="small text-muted">Accommodation</label>
                                <input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm bg-light border-0">
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end mt-3 pt-3 border-top">
                            <button type="button" 
                                    wire:click="duplicateDay({{ $index }})" 
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm btn-link text-muted mr-2">
                                <i class="fas fa-copy mr-1"></i> Duplicate
                            </button>
                            <button type="button" 
                                    wire:click="removeDay({{ $index }})" 
                                    wire:confirm="Are you sure you want to delete this day?"
                                    class="btn btn-sm btn-link text-danger">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <button type="button" 
            wire:click="addDay" 
            wire:loading.attr="disabled"
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
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Floating Save Bar --}}
                        <div class="sticky-action-bar d-flex align-items-center justify-content-between shadow-lg">
                            <div class="d-none d-md-flex align-items-center">
                                <div class="mr-3 p-2 bg-light rounded-circle"><i class="fas fa-plus text-pink"></i></div>
                                <div>
                                    <p class="mb-0 small font-weight-bold text-dark">New Package</p>
                                    <span class="text-muted small">Ready to publish?</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <select wire:model="status" class="form-control-sm border-0 bg-light rounded-pill px-3 mr-4" style="height: 40px; width: 130px; font-weight: 600;">
                                    <option value="draft">📁 Draft</option>
                                    <option value="published">🚀 Published</option>
                                </select>
                                <button type="submit" class="btn btn-pink rounded-pill px-5 font-weight-bold py-2 shadow">
                                    <span wire:loading.remove wire:target="save">CREATE SAFARI</span>
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