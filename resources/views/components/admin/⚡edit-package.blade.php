<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SafariPackage;
use App\Models\SafariCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public SafariPackage $package;
    public $categories;
    
    // Form Properties
    public $name, $price, $duration_days, $location, $status, $safari_category_id, $description;
    public $featured_image; 
    public $gallery_images = []; // For new uploads
    public $itinerary = [];

    public function mount(SafariPackage $package)
    {
        $this->package = $package->load(['itineraries', 'photos']);
        $this->categories = SafariCategory::all();
        
        $this->name = $package->name;
        $this->price = $package->price;
        $this->duration_days = $package->duration_days;
        $this->location = $package->location;
        $this->status = $package->status;
        $this->safari_category_id = $package->safari_category_id;
        $this->description = $package->description;
        
        $this->itinerary = $package->itineraries->sortBy('day_number')->map(fn($day) => [
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
            $this->package->load('photos'); // Refresh
        }
    }

    public function addDay()
    {
        $this->itinerary[] = [
            'day_number' => count($this->itinerary) + 1,
            'title' => '', 'activities' => '', 'meals' => 'Breakfast, Lunch, Dinner', 'accommodation' => ''
        ];
    }

    public function removeDay($index)
    {
        unset($this->itinerary[$index]);
        $this->itinerary = array_values($this->itinerary);
        foreach ($this->itinerary as $k => $v) { $this->itinerary[$k]['day_number'] = $k + 1; }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:safari_packages,name,' . $this->package->id,
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'location' => 'required|string',
            'description' => 'required',
            'gallery_images.*' => 'image|max:2048', // 2MB Max per image
        ]);

        DB::transaction(function () {
            $this->package->update([
                'name' => $this->name, 'slug' => Str::slug($this->name),
                'price' => $this->price, 'duration_days' => $this->duration_days,
                'location' => $this->location, 'status' => $this->status,
                'safari_category_id' => $this->safari_category_id, 'description' => $this->description,
            ]);

            // Handle Featured Image
            if ($this->featured_image) {
                $old = $this->package->photos()->where('type', 'featured')->first();
                if ($old) { Storage::disk('public')->delete($old->path); $old->delete(); }
                $path = $this->featured_image->store('safaris/featured', 'public');
                $this->package->photos()->create(['path' => $path, 'type' => 'featured']);
            }

            // Handle Gallery Uploads
            foreach ($this->gallery_images as $image) {
                $path = $image->store('safaris/gallery', 'public');
                $this->package->photos()->create(['path' => $path, 'type' => 'gallery']);
            }

            // Sync Itinerary
            $this->package->itineraries()->delete();
            foreach ($this->itinerary as $day) { $this->package->itineraries()->create($day); }
        });

        session()->flash('success', 'Safari updated successfully!');
        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data>
    @section('title', 'Edit Safari: ' . $package->name)

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            trix-toolbar .trix-button-group--file-tools { display: none !important; }
            .featured-preview { width: 100%; height: 220px; object-fit: cover; border-radius: 8px; }
            .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
            .gallery-item { position: relative; height: 100px; }
            .gallery-item img { width: 100%; height: 100%; object-fit: cover; border-radius: 6px; }
            .delete-overlay { position: absolute; top: 5px; right: 5px; background: rgba(255,0,0,0.7); color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 10px; }
            .trix-content { min-height: 250px !important; }
            .border-pink { border-left: 4px solid #e83e8c !important; }
            .text-pink { color: #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .btn-pink:hover { background-color: #d81b60; color: white; }
        </style>
    @endpush

    {{-- Content Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="font-weight-bold">Edit Safari Package</h1></div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-default btn-sm mr-2">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-md-5">
                        <div class="card card-outline card-pink shadow-sm mb-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold text-pink">Package Basics</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="font-weight-bold small">PRICE (USD)</label>
                                        <input type="number" wire:model="price" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="font-weight-bold small">DAYS</label>
                                        <input type="number" wire:model="duration_days" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small">LOCATION</label>
                                    <input type="text" wire:model="location" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="small font-weight-bold">CATEGORY</label>
                                        <select wire:model="safari_category_id" class="form-control">
                                            @foreach($categories as $category)
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

                        {{-- Gallery Management --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Safari Gallery</h5></div>
                            <div class="card-body">
                                <label class="small font-weight-bold text-muted">EXISTING PHOTOS</label>
                                <div class="gallery-grid mb-3">
                                    @foreach($package->photos->where('type', 'gallery') as $photo)
                                        <div class="gallery-item">
                                            <img src="{{ asset('storage/' . $photo->path) }}">
                                            <div class="delete-overlay" wire:click="deletePhoto({{ $photo->id }})"><i class="fas fa-times"></i></div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted">UPLOAD NEW PHOTOS</label>
                                    <div class="custom-file">
                                        <input type="file" wire:model="gallery_images" multiple class="custom-file-input" id="galleryUpload">
                                        <label class="custom-file-label" for="galleryUpload">Choose files...</label>
                                    </div>
                                    <div wire:loading wire:target="gallery_images" class="small text-pink mt-1">Uploading previews...</div>
                                </div>
                                
                                @if ($gallery_images)
                                    <div class="gallery-grid mt-2">
                                        @foreach($gallery_images as $temp)
                                            <div class="gallery-item shadow-sm">
                                                <img src="{{ $temp->temporaryUrl() }}">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-md-7">
                        {{-- Featured Image --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-body text-center">
                                <div x-data="{ photoPreview: null }">
                                    <input type="file" wire:model="featured_image" class="d-none" x-ref="photo"
                                        @change="const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);">
                                    <img :src="photoPreview ? photoPreview : '{{ $package->photos->firstWhere('type', 'featured') ? asset('storage/' . $package->photos->firstWhere('type', 'featured')->path) : asset('front/images/placeholder.jpg') }}'" class="featured-preview border shadow-sm img-thumbnail">
                                    <button type="button" class="btn btn-outline-pink btn-sm mt-3 px-4 rounded-pill" @click.prevent="$refs.photo.click()">
                                        <i class="fas fa-camera mr-1"></i> Change Cover
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Trix Description --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">General Overview</h5></div>
                            <div class="card-body">
                                <div wire:ignore x-data="{ value: @entangle('description'), isSet: false }" 
                                     x-init="$refs.trix.editor.loadHTML(value); $watch('value', v => { if (!isSet) $refs.trix.editor.loadHTML(v); isSet = false; })" 
                                     @trix-change="isSet = true; value = $event.target.value">
                                    <trix-editor x-ref="trix" class="bg-white border rounded trix-content"></trix-editor>
                                </div>
                            </div>
                        </div>

                        {{-- Itinerary --}}
                        <div class="itinerary-section">
                            <h5 class="font-weight-bold mb-3">Itinerary Builder</h5>
                            @foreach($itinerary as $index => $day)
                                <div class="card mb-3 border-pink shadow-sm" wire:key="day-{{ $index }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge badge-pink">DAY {{ $day['day_number'] }}</span>
                                            <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                                        </div>
                                        <input type="text" wire:model="itinerary.{{ $index }}.title" class="form-control mb-2" placeholder="Title">
                                        <textarea wire:model="itinerary.{{ $index }}.activities" class="form-control mb-2" rows="2" placeholder="Activities..."></textarea>
                                        <div class="row">
                                            <div class="col-6"><input type="text" wire:model="itinerary.{{ $index }}.meals" class="form-control form-control-sm" placeholder="Meals"></div>
                                            <div class="col-6"><input type="text" wire:model="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm" placeholder="Lodging"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addDay" class="btn btn-outline-pink btn-block mb-4">+ ADD DAY</button>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block shadow-lg py-3 font-weight-bold">
                            <span wire:loading.remove>UPDATE SAFARI PACKAGE</span>
                            <span wire:loading>SAVING CHANGES...</span>
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