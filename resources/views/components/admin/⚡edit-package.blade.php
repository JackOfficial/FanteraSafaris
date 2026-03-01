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
    
    public $name, $price, $duration_days, $destination_id, $status, $safari_category_id, $description;
    public $featured_image; 
    public $gallery_images = []; 
    public $itinerary = [];

    public function mount(SafariPackage $package)
    {
        $this->package = $package->load(['itineraries', 'photos']);
        
        $this->name = $package->name;
        $this->price = $package->price;
        $this->duration_days = $package->duration_days;
        $this->destination_id = $package->destination_id;
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
            'name' => 'required|string|max:255|unique:safari_packages,name,' . $this->package->id,
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'destination_id' => 'required|exists:destinations,id',
            'description' => 'required',
            'gallery_images.*' => 'image|max:2048',
        ]);

        DB::transaction(function () {
            $this->package->update([
                'name' => $this->name, 
                'slug' => Str::slug($this->name),
                'price' => $this->price, 
                'duration_days' => $this->duration_days,
                'destination_id' => $this->destination_id, 
                'status' => $this->status,
                'safari_category_id' => $this->safari_category_id, 
                'description' => $this->description,
            ]);

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

<div x-data="{ activeDay: 0 }">
    @section('title', 'Edit Safari: ' . $package->name)

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .featured-preview { width: 100%; height: 220px; object-fit: cover; border-radius: 8px; cursor: pointer; }
            .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
            .gallery-item { position: relative; height: 100px; }
            .gallery-item img { width: 100%; height: 100%; object-fit: cover; border-radius: 6px; }
            .delete-overlay { position: absolute; top: -5px; right: -5px; background: #dc3545; color: white; border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.2); border: 2px solid white; }
            .trix-content { min-height: 250px !important; background: white; }
            .border-pink { border-left: 4px solid #e83e8c !important; }
            .text-pink { color: #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .btn-pink:hover { background-color: #d81b60; color: white; }
            .itinerary-header { cursor: pointer; background: #f8f9fa; transition: 0.2s; border-bottom: 1px solid #eee; }
            .itinerary-header:hover { background: #fff0f5; }
            [x-cloak] { display: none !important; }

            /* Custom Slim Pink Scrollbar */
            .itinerary-scroll-container::-webkit-scrollbar { width: 6px; }
            .itinerary-scroll-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
            .itinerary-scroll-container::-webkit-scrollbar-thumb { background: #e83e8c; border-radius: 10px; }
            .itinerary-scroll-container::-webkit-scrollbar-thumb:hover { background: #d81b60; }
        </style>
    @endpush

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6"><h1 class="font-weight-bold">Edit Safari Package</h1></div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-default btn-sm mr-2 shadow-sm">Cancel</a>
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
                                    <label class="font-weight-bold small text-muted">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="font-weight-bold small text-muted">PRICE (USD)</label>
                                        <input type="number" wire:model="price" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="font-weight-bold small text-muted">DAYS</label>
                                        <input type="number" wire:model="duration_days" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small text-muted">DESTINATION</label>
                                    <select wire:model="destination_id" class="form-control">
                                        @foreach(\App\Models\Destination::orderBy('name')->get() as $dest)
                                            <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-muted">CATEGORY</label>
                                        <select wire:model="safari_category_id" class="form-control">
                                            @foreach(\App\Models\SafariCategory::all() as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-muted">STATUS</label>
                                        <select wire:model="status" class="form-control">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

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

                    {{-- Right Column --}}
                    <div class="col-md-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body text-center p-2">
                                <div x-data="{ photoPreview: null }">
                                    <input type="file" wire:model="featured_image" class="d-none" x-ref="photo"
                                        @change="const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);">
                                    <img :src="photoPreview ? photoPreview : '{{ $package->photos->firstWhere('type', 'featured') ? asset('storage/' . $package->photos->firstWhere('type', 'featured')->path) : asset('front/images/placeholder.jpg') }}'" 
                                         class="featured-preview border" @click="$refs.photo.click()">
                                    <p class="small text-muted mt-2 mb-0">Click image to change cover photo</p>
                                </div>
                            </div>
                        </div>

                        {{-- SCROLLABLE ITINERARY WITH GUARANTEED END-ALIGNED ICONS --}}
<div class="itinerary-builder">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5 class="font-weight-bold mb-0">Itinerary Builder</h5>
        <span class="badge badge-pink px-3 shadow-sm">{{ count($itinerary) }} Days</span>
    </div>

    <div class="itinerary-scroll-container mb-3" 
         style="max-height: 480px; overflow-y: auto; padding: 10px; background: #fdfdfd; border: 1px solid #eee; border-radius: 8px;">
        
        @foreach($itinerary as $index => $day)
            <div class="card mb-2 border-pink shadow-sm" wire:key="edit-itinerary-{{ $index }}">
                
                {{-- HEADER: Use w-100 and d-flex to force the chevron to the far right --}}
                <div class="card-header itinerary-header p-3 d-flex align-items-center justify-content-between" 
                     style="width: 100%;"
                     @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                    
                    {{-- LEFT SIDE: Badge and Title --}}
                    <div class="d-flex align-items-center" style="flex: 1;">
                        <span class="badge badge-pink mr-3" style="min-width: 60px;">DAY {{ $day['day_number'] }}</span>
                        <span class="small font-weight-bold text-dark text-uppercase" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">
                            {{ $itinerary[$index]['title'] ?: 'Enter day title...' }}
                        </span>
                    </div>

                    {{-- RIGHT SIDE: The Icon (Guaranteed to be at the end) --}}
                    <div class="pl-2">
                        <i class="fas fa-chevron-down text-muted" 
                           :class="activeDay === {{ $index }} ? 'fa-rotate-180 text-pink' : ''" 
                           style="transition: 0.3s; pointer-events: none;"></i>
                    </div>
                </div>

                <div class="card-body p-3 bg-white" x-show="activeDay === {{ $index }}" x-cloak>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">DAY TITLE</label>
                        <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control font-weight-bold border-0 bg-light" placeholder="e.g. Arrival at Entebbe">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted">ACTIVITIES</label>
                        <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="small font-weight-bold text-muted">MEALS</label>
                            <input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control form-control-sm shadow-none">
                        </div>
                        <div class="col-6">
                            <label class="small font-weight-bold text-muted">ACCOMMODATION</label>
                            <input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm shadow-none">
                        </div>
                    </div>
                    <div class="text-right mt-3 pt-2 border-top">
                        <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-xs btn-outline-danger">
                            <i class="fas fa-trash-alt mr-1"></i> Remove Day
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <button type="button" wire:click="addDay" @click="activeDay = {{ count($itinerary) }}" 
            class="btn btn-outline-pink btn-block mb-4 shadow-sm font-weight-bold py-2">
        <i class="fas fa-plus-circle mr-2"></i> ADD NEXT DAY
    </button>
</div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">General Overview</h5></div>
                            <div class="card-body">
                                <div wire:ignore x-data="{ value: @entangle('description'), isSet: false }" 
                                     x-init="$refs.trix.editor.loadHTML(value); $watch('value', v => { if (!isSet) $refs.trix.editor.loadHTML(v); isSet = false; })" 
                                     @trix-change="isSet = true; value = $event.target.value">
                                    <trix-editor x-ref="trix" class="trix-content"></trix-editor>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block shadow-lg py-3 font-weight-bold mb-5">
                            <span wire:loading.remove wire:target="save">UPDATE PACKAGE</span>
                            <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin mr-2"></i> SAVING...</span>
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