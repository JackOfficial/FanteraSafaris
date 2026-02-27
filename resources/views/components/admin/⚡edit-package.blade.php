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
    public $itinerary = [];

    public function mount(SafariPackage $package)
    {
        // Load relationships and initialize categories
        $this->package = $package->load(['itineraries', 'photos']);
        $this->categories = SafariCategory::all();
        
        // Map model attributes to component properties
        $this->name = $package->name;
        $this->price = $package->price;
        $this->duration_days = $package->duration_days;
        $this->location = $package->location;
        $this->status = $package->status;
        $this->safari_category_id = $package->safari_category_id;
        $this->description = $package->description;
        
        // Initialize Itinerary with proper sort order
        $this->itinerary = $package->itineraries->sortBy('day_number')->map(fn($day) => [
            'day_number' => $day->day_number,
            'title' => $day->title,
            'activities' => $day->activities,
            'meals' => $day->meals,
            'accommodation' => $day->accommodation
        ])->toArray();
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
        // Reset keys and re-calculate day numbers to keep sequence intact
        $this->itinerary = array_values($this->itinerary);
        foreach ($this->itinerary as $key => $day) {
            $this->itinerary[$key]['day_number'] = $key + 1;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:safari_packages,name,' . $this->package->id,
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'location' => 'required|string',
            'status' => 'required|in:draft,published',
            'safari_category_id' => 'required|exists:safari_categories,id',
            'description' => 'required',
            'itinerary.*.title' => 'required|string',
            'itinerary.*.activities' => 'required|string',
        ]);

        DB::transaction(function () {
            // 1. Update Core Package
            $this->package->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'price' => $this->price,
                'duration_days' => $this->duration_days,
                'location' => $this->location,
                'status' => $this->status,
                'safari_category_id' => $this->safari_category_id,
                'description' => $this->description,
            ]);

            // 2. Handle Featured Image
            if ($this->featured_image) {
                $oldPhoto = $this->package->photos()->where('type', 'featured')->first();
                if ($oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto->path);
                    $oldPhoto->delete();
                }
                $path = $this->featured_image->store('safaris/featured', 'public');
                $this->package->photos()->create(['path' => $path, 'type' => 'featured']);
            }

            // 3. Sync Itinerary (Delete & Re-insert)
            $this->package->itineraries()->delete();
            foreach ($this->itinerary as $day) {
                $this->package->itineraries()->create($day);
            }
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
            .trix-content { min-height: 250px !important; }
            .border-pink { border-left: 4px solid #e83e8c !important; }
            .text-pink { color: #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; transition: 0.2s; }
            .btn-pink:hover { background-color: #d81b60; color: white; }
            .btn-outline-pink { border-color: #e83e8c; color: #e83e8c; }
            .btn-outline-pink:hover { background-color: #e83e8c; color: white; }
        </style>
    @endpush

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="font-weight-bold">Edit Safari Package</h1>
                </div>
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
                    {{-- Left Column: Basics --}}
                    <div class="col-md-5">
                        <div class="card card-outline card-pink shadow-sm mb-4">
                            <div class="card-header bg-white">
                                <h5 class="mb-0 font-weight-bold text-pink">Package Basics</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small">PACKAGE NAME</label>
                                    <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror">
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold small">PRICE (USD)</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"><span class="input-group-text">$</span></div>
                                            <input type="number" wire:model="price" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="font-weight-bold small">DURATION (DAYS)</label>
                                        <input type="number" wire:model="duration_days" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="font-weight-bold small">LOCATION</label>
                                    <input type="text" wire:model="location" class="form-control">
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">CATEGORY</label>
                                        <select wire:model="safari_category_id" class="form-control">
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold">STATUS</label>
                                        <select wire:model="status" class="form-control">
                                            <option value="draft">Draft</option>
                                            <option value="published">Published</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Image Preview Section --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Cover Image</h5></div>
                            <div class="card-body text-center">
                                <div x-data="{ photoPreview: null }">
                                    <input type="file" wire:model="featured_image" class="d-none" x-ref="photo"
                                        @change="const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL($refs.photo.files[0]);">
                                    
                                    <div class="mt-2" x-show="! photoPreview">
                                        @php $featured = $package->photos->firstWhere('type', 'featured'); @endphp
                                        <img src="{{ $featured ? asset('storage/' . $featured->path) : asset('front/images/placeholder.jpg') }}" class="featured-preview border shadow-sm img-thumbnail">
                                    </div>
                                    <div class="mt-2" x-cloak x-show="photoPreview">
                                        <img :src="photoPreview" class="featured-preview border shadow-sm img-thumbnail">
                                    </div>

                                    <button type="button" class="btn btn-outline-pink btn-sm mt-3 px-3 rounded-pill" @click.prevent="$refs.photo.click()">
                                        <i class="fas fa-camera mr-1"></i> Change Cover Photo
                                    </button>
                                </div>
                                @error('featured_image') <span class="text-danger d-block mt-2 small">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Content & Itinerary --}}
                    <div class="col-md-7">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">General Overview</h5></div>
                            <div class="card-body">
                                <div wire:ignore x-data="{ 
                                    value: @entangle('description'),
                                    isSetByEditor: false
                                }" x-init="
                                    $refs.trix.editor.loadHTML(value);
                                    $watch('value', v => { if (!isSetByEditor) $refs.trix.editor.loadHTML(v); isSetByEditor = false; });
                                " @trix-change="isSetByEditor = true; value = $event.target.value">
                                    <trix-editor x-ref="trix" class="bg-white border rounded trix-content"></trix-editor>
                                </div>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="itinerary-section">
                            <h5 class="font-weight-bold mb-3">Itinerary Builder</h5>
                            @foreach($itinerary as $index => $day)
                                <div class="card mb-3 border-pink shadow-sm" wire:key="day-{{ $index }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="badge badge-pink px-3">DAY {{ $day['day_number'] }}</span>
                                            <button type="button" wire:confirm="Remove this day?" wire:click="removeDay({{ $index }})" class="btn btn-sm btn-danger rounded-circle">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="form-group mb-2">
                                            <input type="text" wire:model="itinerary.{{ $index }}.title" class="form-control font-weight-bold" placeholder="Day Title">
                                        </div>
                                        <div class="form-group">
                                            <textarea wire:model="itinerary.{{ $index }}.activities" class="form-control" rows="3" placeholder="Activities..."></textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6"><input type="text" wire:model="itinerary.{{ $index }}.meals" class="form-control form-control-sm" placeholder="Meals"></div>
                                            <div class="col-md-6"><input type="text" wire:model="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm" placeholder="Accommodation"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addDay" class="btn btn-outline-pink btn-block mb-4 shadow-sm font-weight-bold">
                                <i class="fas fa-plus mr-2"></i> ADD ANOTHER DAY
                            </button>
                        </div>

                        <div class="mt-4 pb-5">
                            <button type="submit" class="btn btn-pink btn-lg btn-block shadow py-3 font-weight-bold">
                                <span wire:loading.remove><i class="fas fa-save mr-2"></i> SAVE ALL CHANGES</span>
                                <span wire:loading><i class="fas fa-spinner fa-spin mr-2"></i> SAVING...</span>
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