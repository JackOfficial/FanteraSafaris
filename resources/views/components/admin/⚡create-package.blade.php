<?php

use Livewire\Component; // If using Volt, otherwise 'use Livewire\Component' works for standard anonymous components
use Livewire\WithFileUploads;
use App\Models\SafariPackage;
use App\Models\SafariCategory;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

new class extends Component {
    use WithFileUploads;

    // Updated Properties: Replaced $location with $destination_id
    public $name, $price, $duration_days, $destination_id, $status = 'draft', $safari_category_id, $description;
    
    // Media
    public $featured_image; 
    public $gallery_images = []; 

    // Itinerary Builder
    public $itinerary = [];

    public function mount()
    {
        $this->addDay();
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

    public function removeDay($index)
    {
        unset($this->itinerary[$index]);
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
            'duration_days' => 'required|integer',
            'destination_id' => 'required|exists:destinations,id', // Added validation
            'safari_category_id' => 'required|exists:safari_categories,id',
            'description' => 'required',
            'featured_image' => 'required|image|max:2048',
            'gallery_images.*' => 'image|max:2048',
        ]);

        DB::transaction(function () {
            // 1. Create the Main Package
            $package = SafariPackage::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'price' => $this->price,
                'duration_days' => $this->duration_days,
                'destination_id' => $this->destination_id, // Linked to Destinations table
                'status' => $this->status,
                'safari_category_id' => $this->safari_category_id,
                'description' => $this->description,
            ]);

            // 2. Store Featured Image
            $featuredPath = $this->featured_image->store('safaris/featured', 'public');
            $package->photos()->create(['path' => $featuredPath, 'type' => 'featured']);

            // 3. Store Gallery Images
            foreach ($this->gallery_images as $image) {
                $galleryPath = $image->store('safaris/gallery', 'public');
                $package->photos()->create(['path' => $galleryPath, 'type' => 'gallery']);
            }

            // 4. Store Itinerary Days
            foreach ($this->itinerary as $day) {
                $package->itineraries()->create($day);
            }
        });

        session()->flash('success', 'Safari Package created successfully!');
        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .featured-upload-box { width: 100%; height: 250px; border: 2px dashed #ddd; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; background: #fdfdfd; cursor: pointer; transition: 0.3s; }
            .featured-upload-box:hover { border-color: #e83e8c; background: #fff5f8; }
            .featured-upload-box img { width: 100%; height: 100%; object-fit: cover; }
            .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
            .gallery-item { height: 100px; border-radius: 8px; overflow: hidden; border: 1px solid #eee; }
            .gallery-item img { width: 100%; height: 100%; object-fit: cover; }
            .border-pink { border-left: 4px solid #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .btn-pink:hover { background-color: #d81b60; color: white; }
            .text-pink { color: #e83e8c !important; }
            trix-editor { min-height: 250px !important; background: white; border-radius: 8px; }
            .badge-pink { background-color: #e83e8c; color: white; }
        </style>
    @endpush

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6"><h1 class="font-weight-bold">Create New Safari</h1></div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-default btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Column: Basics & Gallery --}}
                    <div class="col-md-5">
                        <div class="card card-outline card-pink shadow-sm">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold text-pink">General Information</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. 3-Day Gorilla Trekking Expedition">
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">PRICE (USD)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                            <input type="number" wire:model="price" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">DURATION (DAYS)</label>
                                        <input type="number" wire:model="duration_days" class="form-control">
                                    </div>
                                </div>

                                {{-- UPDATED: Destination Dropdown --}}
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">PRIMARY DESTINATION</label>
                                    <select wire:model="destination_id" class="form-control @error('destination_id') is-invalid @enderror">
                                        <option value="">Select a Park or City</option>
                                        @foreach(\App\Models\Destination::all()->groupBy('country') as $country => $destinations)
                                            <optgroup label="{{ strtoupper($country) }}">
                                                @foreach($destinations as $dest)
                                                    <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('destination_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label class="small font-weight-bold">CATEGORY</label>
                                        <select wire:model="safari_category_id" class="form-control">
                                            <option value="">Select Category</option>
                                            @foreach(\App\Models\SafariCategory::all() as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold">STATUS</label>
                                        <select wire:model="status" class="form-control">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mt-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Tour Gallery Images</h5></div>
                            <div class="card-body">
                                <div class="custom-file mb-3">
                                    <input type="file" wire:model="gallery_images" multiple class="custom-file-input" id="galleryInput">
                                    <label class="custom-file-label" for="galleryInput">Choose multiple photos...</label>
                                </div>

                                <div wire:loading wire:target="gallery_images" class="text-pink small mb-2"><i class="fas fa-spinner fa-spin mr-1"></i> Preprocessing...</div>

                                @if($gallery_images)
                                    <div class="gallery-grid">
                                        @foreach($gallery_images as $image)
                                            <div class="gallery-item shadow-sm">
                                                <img src="{{ $image->temporaryUrl() }}">
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4 text-muted border rounded" style="background: #fafafa">
                                        <i class="fas fa-images fa-2x opacity-25"></i>
                                        <p class="small mt-2 mb-0">No gallery images selected</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Content & Itinerary --}}
                    <div class="col-md-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <label class="small font-weight-bold text-muted">COVER PHOTO (FEATURED)</label>
                                <div x-data="{ preview: null }" class="featured-upload-box" @click="$refs.featured.click()">
                                    <input type="file" wire:model="featured_image" class="d-none" x-ref="featured"
                                        @change="const file = $refs.featured.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result }; reader.readAsDataURL(file); }">
                                    
                                    <template x-if="preview">
                                        <img :src="preview">
                                    </template>
                                    <template x-if="!preview">
                                        <div class="text-center text-muted">
                                            <i class="fas fa-cloud-upload-alt fa-3x mb-2 text-pink"></i>
                                            <p class="mb-0">Click to upload cover image</p>
                                        </div>
                                    </template>
                                </div>
                                @error('featured_image') <span class="text-danger small mt-1 d-block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <label class="small font-weight-bold text-muted">PACKAGE OVERVIEW</label>
                                <div wire:ignore x-data="{ value: @entangle('description') }" @trix-change="value = $event.target.value">
                                    <trix-editor class="trix-content"></trix-editor>
                                </div>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="itinerary-builder">
                            <h5 class="font-weight-bold mb-3">Detailed Itinerary</h5>
                            @foreach($itinerary as $index => $day)
                                <div class="card mb-3 border-pink shadow-sm" wire:key="day-{{ $index }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge badge-pink py-2 px-3">DAY {{ $day['day_number'] }}</span>
                                            <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </div>
                                        <input type="text" wire:model="itinerary.{{ $index }}.title" class="form-control mb-2 font-weight-bold" placeholder="Day Title (e.g. Arrival in Bwindi)">
                                        <textarea wire:model="itinerary.{{ $index }}.activities" class="form-control mb-2" rows="2" placeholder="Describe activities..."></textarea>
                                        <div class="row">
                                            <div class="col-md-6"><input type="text" wire:model="itinerary.{{ $index }}.meals" class="form-control form-control-sm" placeholder="Meals"></div>
                                            <div class="col-md-6"><input type="text" wire:model="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm" placeholder="Accommodation"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addDay" class="btn btn-outline-pink btn-block mb-4 shadow-sm font-weight-bold">
                                <i class="fas fa-plus mr-2"></i> ADD NEXT DAY
                            </button>
                        </div>

                        <div class="pb-5">
                            <button type="submit" class="btn btn-pink btn-lg btn-block shadow-lg py-3 font-weight-bold">
                                <span wire:loading.remove wire:target="save"><i class="fas fa-check-circle mr-2"></i> PUBLISH SAFARI PACKAGE</span>
                                <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin mr-2"></i> SAVING...</span>
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