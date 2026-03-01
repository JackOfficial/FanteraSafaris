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

    public $name, $price, $duration_days = 1, $destination_id, $status = 'draft', $safari_category_id, $description;
    public $featured_image; 
    public $gallery_images = []; 
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
        $this->duration_days = count($this->itinerary);
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
        $this->duration_days = count($this->itinerary);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:safari_packages,name',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'destination_id' => 'required|exists:destinations,id',
            'safari_category_id' => 'required|exists:safari_categories,id',
            'description' => 'required',
            'featured_image' => 'required|image|max:2048',
            'gallery_images.*' => 'image|max:2048',
        ]);

        DB::transaction(function () {
            $package = SafariPackage::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'price' => $this->price,
                'duration_days' => count($this->itinerary), // Sync with itinerary
                'destination_id' => $this->destination_id,
                'status' => $this->status,
                'safari_category_id' => $this->safari_category_id,
                'description' => $this->description,
            ]);

            $featuredPath = $this->featured_image->store('safaris/featured', 'public');
            $package->photos()->create(['path' => $featuredPath, 'type' => 'featured']);

            foreach ($this->gallery_images as $image) {
                $galleryPath = $image->store('safaris/gallery', 'public');
                $package->photos()->create(['path' => $galleryPath, 'type' => 'gallery']);
            }

            foreach ($this->itinerary as $index => $day) {
                $package->itineraries()->create([
                    'day_number'    => $day['day_number'] ?? ($index + 1),
                    'title'         => $day['title'] ?? '',
                    'activities'    => $day['activities'] ?? '',
                    'meals'         => $day['meals'] ?? '',
                    'accommodation' => $day['accommodation'] ?? '',
                ]);
            }
        });

        session()->flash('success', 'Safari Package created successfully!');
        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data="{ activeDay: 0 }">
    @section('title', 'Create Safari Package')

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .featured-upload-box { width: 100%; height: 220px; border: 2px dashed #ddd; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative; background: #fdfdfd; cursor: pointer; transition: 0.3s; }
            .featured-upload-box:hover { border-color: #e83e8c; background: #fff5f8; }
            .featured-upload-box img { width: 100%; height: 100%; object-fit: cover; }
            .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
            .gallery-item { position: relative; height: 100px; }
            .gallery-item img { width: 100%; height: 100%; object-fit: cover; border-radius: 6px; }
            .trix-content { min-height: 250px !important; background: white; }
            .border-pink { border-left: 4px solid #e83e8c !important; }
            .text-pink { color: #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .btn-pink:hover { background-color: #d81b60; color: white; }
            .btn-outline-pink { border: 1px solid #e83e8c; color: #e83e8c; background: transparent; }
            .btn-outline-pink:hover { background: #e83e8c; color: white; }
            .itinerary-header { cursor: pointer; background: #f8f9fa; transition: 0.2s; border-bottom: 1px solid #eee; }
            .itinerary-header:hover { background: #fff0f5; }
            .itinerary-scroll-container::-webkit-scrollbar { width: 6px; }
            .itinerary-scroll-container::-webkit-scrollbar-thumb { background: #e83e8c; border-radius: 10px; }
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6"><h1 class="font-weight-bold">Create New Safari</h1></div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.packages.index') }}" class="btn btn-default btn-sm shadow-sm">Cancel</a>
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
                                        <input type="number" wire:model="duration_days" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small text-muted">DESTINATION</label>
                                    <select wire:model="destination_id" class="form-control">
                                        <option value="">Select Destination</option>
                                        @foreach(\App\Models\Destination::orderBy('name')->get() as $dest)
                                            <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-muted">CATEGORY</label>
                                        <select wire:model="safari_category_id" class="form-control">
                                            <option value="">Select Category</option>
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
                                <input type="file" wire:model="gallery_images" multiple class="form-control-file border p-1 rounded mb-3">
                                @if($gallery_images)
                                    <div class="gallery-grid">
                                        @foreach($gallery_images as $image)
                                            <div class="gallery-item shadow-sm">
                                                <img src="{{ $image->temporaryUrl() }}">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-md-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body text-center p-2">
                                <div x-data="{ preview: null }">
                                    <input type="file" wire:model="featured_image" class="d-none" x-ref="photo"
                                        @change="const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);">
                                    <div class="featured-upload-box border" @click="$refs.photo.click()">
                                        <template x-if="preview">
                                            <img :src="preview">
                                        </template>
                                        <template x-if="!preview">
                                            <div class="text-center text-muted">
                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                <p class="mb-0">Click to upload cover photo</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="itinerary-builder">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="font-weight-bold mb-0">Itinerary Builder</h5>
                                <span class="badge badge-pink px-3 shadow-sm">{{ count($itinerary) }} Days</span>
                            </div>

                            <div class="itinerary-scroll-container mb-3" 
                                 style="max-height: 480px; overflow-y: auto; padding: 10px; background: #fdfdfd; border: 1px solid #eee; border-radius: 8px;">
                                
                                @foreach($itinerary as $index => $day)
                                    <div class="card mb-2 border-pink shadow-sm" wire:key="create-itinerary-{{ $index }}">
                                        <div class="card-header itinerary-header p-3 d-flex align-items-center justify-content-between" 
                                             @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                            
                                            <div class="d-flex align-items-center" style="flex: 1;">
                                                <span class="badge badge-pink mr-3" style="min-width: 60px;">DAY {{ $day['day_number'] }}</span>
                                                <span class="small font-weight-bold text-dark text-uppercase" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px;">
                                                    {{ $itinerary[$index]['title'] ?: 'Enter day title...' }}
                                                </span>
                                            </div>

                                            <div class="pl-2">
                                                <i class="fas fa-chevron-down text-muted" 
                                                   :class="activeDay === {{ $index }} ? 'fa-rotate-180 text-pink' : ''" 
                                                   style="transition: 0.3s;"></i>
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
                                                    <input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-6">
                                                    <label class="small font-weight-bold text-muted">ACCOMMODATION</label>
                                                    <input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mt-3 pt-2 border-top">
                                                <button type="button" wire:click="duplicateDay({{ $index }})" 
                                                        @click="activeDay = {{ $index + 1 }}"
                                                        class="btn btn-xs btn-outline-info mr-2 shadow-sm">
                                                    <i class="fas fa-copy mr-1"></i> Duplicate
                                                </button>
                                                <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-xs btn-outline-danger shadow-sm">
                                                    <i class="fas fa-trash-alt mr-1"></i> Remove
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
                                <div wire:ignore x-data="{ value: @entangle('description') }" 
                                     @trix-change="value = $event.target.value">
                                    <trix-editor class="trix-content"></trix-editor>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block shadow-lg py-3 font-weight-bold mb-5">
                            <span wire:loading.remove wire:target="save">CREATE PACKAGE</span>
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