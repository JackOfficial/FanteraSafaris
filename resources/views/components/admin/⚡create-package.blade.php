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

    public $name, $price, $duration_days, $destination_id, $status = 'draft', $safari_category_id, $description;
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
                'duration_days' => $this->duration_days,
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

            foreach ($this->itinerary as $day) {
                $package->itineraries()->create($day);
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
            
            .itinerary-header { cursor: pointer; background: #f8f9fa; transition: 0.2s; border-bottom: 1px solid #eee; }
            .itinerary-header:hover { background: #fff0f5; }
            [x-cloak] { display: none !important; }
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
                    {{-- Left Column --}}
                    <div class="col-md-5">
                        <div class="card card-outline card-pink shadow-sm">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold text-pink">General Information</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">PRICE (USD)</label>
                                        <input type="number" wire:model="price" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">DURATION (DAYS)</label>
                                        <input type="number" wire:model="duration_days" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold">DESTINATION / LOCATION</label>
                                    <select wire:model="destination_id" class="form-control">
                                        <option value="">Select Destination</option>
                                        @foreach(\App\Models\Destination::orderBy('name')->get() as $dest)
                                            <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                        @endforeach
                                    </select>
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
                                <input type="file" wire:model="gallery_images" multiple class="form-control mb-3">
                                @if($gallery_images)
                                    <div class="gallery-grid">
                                        @foreach($gallery_images as $image)
                                            <div class="gallery-item shadow-sm"><img src="{{ $image->temporaryUrl() }}"></div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-md-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <label class="small font-weight-bold text-muted">COVER PHOTO (FEATURED)</label>
                                <div x-data="{ preview: null }" class="featured-upload-box" @click="$refs.featured.click()">
                                    <input type="file" wire:model="featured_image" class="d-none" x-ref="featured" @change="const file = $refs.featured.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { preview = e.target.result }; reader.readAsDataURL(file); }">
                                    <template x-if="preview"><img :src="preview"></template>
                                    <template x-if="!preview">
                                        <div class="text-center text-muted"><i class="fas fa-cloud-upload-alt fa-3x mb-2"></i><p>Upload Cover</p></div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <label class="small font-weight-bold text-muted">PACKAGE OVERVIEW</label>
                                <div wire:ignore x-data="{ value: @entangle('description') }" @trix-change="value = $event.target.value">
                                    <trix-editor class="trix-content"></trix-editor>
                                </div>
                            </div>
                        </div>

                        {{-- RE-FIXED ITINERARY BUILDER --}}
                        <div class="itinerary-builder">
                            <h5 class="font-weight-bold mb-3">Detailed Itinerary</h5>
                            
                            @foreach($itinerary as $index => $day)
                                <div class="card mb-2 border-pink shadow-sm" wire:key="itinerary-day-{{ $index }}">
                                    {{-- Use a standard button/div for toggle to avoid event bubbling issues --}}
                                    <div class="card-header itinerary-header p-2 d-flex justify-content-between align-items-center" 
                                         @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-pink mr-2">DAY {{ $day['day_number'] }}</span>
                                            <span class="small font-weight-bold text-dark text-uppercase">
                                                {{ $itinerary[$index]['title'] ?: 'Click to edit day details' }}
                                            </span>
                                        </div>
                                        <i class="fas fa-chevron-down text-muted" :class="activeDay === {{ $index }} ? 'fa-rotate-180' : ''" style="transition: 0.3s"></i>
                                    </div>

                                    <div class="card-body p-3" x-show="activeDay === {{ $index }}" x-cloak>
                                        <div class="form-group">
                                            <label class="small font-weight-bold">DAY TITLE</label>
                                            <input type="text" wire:model.defer="itinerary.{{ $index }}.title" class="form-control mb-2 font-weight-bold" placeholder="e.g. Arrival and Transfer to Bwindi">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="small font-weight-bold">ACTIVITIES</label>
                                            <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control mb-2" rows="3" placeholder="What will they do today?"></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="small font-weight-bold">MEALS</label>
                                                <input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small font-weight-bold">LODGING</label>
                                                <input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm">
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        <div class="text-right">
                                            <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt mr-1"></i> Remove Day
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <button type="button" wire:click="addDay" @click="activeDay = {{ count($itinerary) }}" class="btn btn-outline-pink btn-block mb-4 shadow-sm font-weight-bold">
                                <i class="fas fa-plus-circle mr-2"></i> ADD NEXT DAY
                            </button>
                        </div>

                        <div class="pb-5">
                            <button type="submit" class="btn btn-pink btn-lg btn-block shadow-lg py-3 font-weight-bold">
                                <span wire:loading.remove wire:target="save"><i class="fas fa-check-circle mr-2"></i> PUBLISH SAFARI PACKAGE</span>
                                <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin mr-2"></i> SAVING PACKAGE...</span>
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