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

    public function duplicateDay($index)
    {
        $newDay = $this->itinerary[$index];
        array_splice($this->itinerary, $index + 1, 0, [$newDay]);
        
        foreach ($this->itinerary as $k => $v) { 
            $this->itinerary[$k]['day_number'] = $k + 1; 
        }
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
            .trix-editor-container { min-height: 250px; background: white; border: 1px solid #ced4da; border-radius: 4px; padding: 10px; }
            .border-pink { border-left: 4px solid #e83e8c !important; }
            .text-pink { color: #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .btn-pink:hover { background-color: #d81b60; color: white; }
            .itinerary-header { cursor: pointer; background: #f8f9fa; transition: 0.2s; border-bottom: 1px solid #eee; }
            .itinerary-header:hover { background: #fff0f5; }
            [x-cloak] { display: none !important; }

            .itinerary-scroll-container::-webkit-scrollbar { width: 6px; }
            .itinerary-scroll-container::-webkit-scrollbar-thumb { background: #e83e8c; border-radius: 10px; }
        </style>
    @endpush

    <section class="content">
        <div class="container-fluid pt-4">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Column: Settings & Description --}}
                    <div class="col-md-5">
                        <div class="card card-outline card-pink shadow-sm mb-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold text-pink">Package Info</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small text-muted text-uppercase">Package Name</label>
                                    <input type="text" wire:model="name" class="form-control">
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-6"><label class="small font-weight-bold">PRICE (USD)</label><input type="number" wire:model="price" class="form-control"></div>
                                    <div class="col-6"><label class="small font-weight-bold">DAYS</label><input type="number" wire:model="duration_days" class="form-control"></div>
                                </div>

                                {{-- DESCRIPTION SECTION --}}
                                <div class="form-group mb-3" wire:ignore>
                                    <label class="small font-weight-bold text-muted text-uppercase">Description</label>
                                    <input id="description" type="hidden" name="description" value="{{ $description }}">
                                    <trix-editor input="description" 
                                        class="trix-editor-container"
                                        x-on:trix-change="$wire.set('description', $event.target.value)">
                                    </trix-editor>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">DESTINATION</label>
                                    <select wire:model="destination_id" class="form-control">
                                        @foreach(\App\Models\Destination::orderBy('name')->get() as $dest)
                                            <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label class="small font-weight-bold">CATEGORY</label>
                                        <select wire:model="safari_category_id" class="form-control">
                                            @foreach(\App\Models\SafariCategory::all() as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
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

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Gallery</h5></div>
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

                    {{-- Right Column: Itinerary --}}
                    <div class="col-md-7">
                        {{-- Cover Image --}}
                        <div class="card shadow-sm mb-4 p-2">
                            <div x-data="{ photoPreview: null }">
                                <input type="file" wire:model="featured_image" class="d-none" x-ref="photo"
                                    @change="const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);">
                                <img :src="photoPreview ? photoPreview : '{{ $package->photos->firstWhere('type', 'featured') ? asset('storage/' . $package->photos->firstWhere('type', 'featured')->path) : asset('front/images/placeholder.jpg') }}'" 
                                     class="featured-preview border" @click="$refs.photo.click()">
                            </div>
                        </div>

                        {{-- Itinerary List --}}
                        <div class="itinerary-builder">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="font-weight-bold mb-0">Itinerary Builder</h5>
                                <span class="badge badge-pink px-3 shadow-sm">{{ count($itinerary) }} Days</span>
                            </div>

                            <div class="itinerary-scroll-container mb-3" style="max-height: 500px; overflow-y: auto;">
                                @foreach($itinerary as $index => $day)
                                    <div class="card mb-2 border-pink shadow-sm" wire:key="day-{{ $index }}">
                                        {{-- HEADER WITH END-ALIGNED BUTTON --}}
                                        <div class="card-header itinerary-header p-3 d-flex align-items-center justify-content-between" 
                                             @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                            
                                            <div class="d-flex align-items-center overflow-hidden">
                                                <span class="badge badge-pink mr-3">DAY {{ $day['day_number'] }}</span>
                                                <span class="small font-weight-bold text-dark text-uppercase text-truncate" style="max-width: 320px;">
                                                    {{ $day['title'] ?: 'Enter day title...' }}
                                                </span>
                                            </div>

                                            <div class="pl-2">
                                                <i class="fas fa-chevron-down text-muted" 
                                                   :class="activeDay === {{ $index }} ? 'fa-rotate-180 text-pink' : ''" 
                                                   style="transition: 0.3s"></i>
                                            </div>
                                        </div>

                                        <div class="card-body p-3 bg-white" x-show="activeDay === {{ $index }}" x-cloak>
                                            <div class="form-group mb-3">
                                                <label class="small font-weight-bold text-muted">DAY TITLE</label>
                                                <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control font-weight-bold bg-light border-0">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="small font-weight-bold text-muted">ACTIVITIES</label>
                                                <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control" rows="3"></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-6"><label class="small font-weight-bold text-muted">MEALS</label><input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control form-control-sm"></div>
                                                <div class="col-6"><label class="small font-weight-bold text-muted">ACCOM.</label><input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm"></div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-end mt-3 pt-2 border-top">
                                                <button type="button" wire:click="duplicateDay({{ $index }})" 
                                                        @click="activeDay = {{ $index + 1 }}"
                                                        class="btn btn-xs btn-outline-info mr-2">
                                                    <i class="fas fa-copy mr-1"></i> Duplicate
                                                </button>
                                                <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-xs btn-outline-danger">
                                                    <i class="fas fa-trash-alt mr-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="button" wire:click="addDay" @click="activeDay = {{ count($itinerary) }}" 
                                    class="btn btn-outline-pink btn-block mb-4 shadow-sm font-weight-bold">
                                <i class="fas fa-plus-circle mr-2"></i> ADD NEXT DAY
                            </button>
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