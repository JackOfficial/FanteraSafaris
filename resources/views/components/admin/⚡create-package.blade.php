<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SafariPackage;
use App\Models\SafariCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

new class extends Component {
    use WithFileUploads;

    public $name, $price, $duration_days, $location, $status = 'draft', $safari_category_id, $description;
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
            'location' => 'required|string',
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
                'location' => $this->location,
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

<div class="container-fluid">
    <form wire:submit="save">
        <div class="row">
            {{-- Form Content --}}
            <div class="col-md-5">
                <div class="card card-outline card-pink shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 font-weight-bold text-pink">General Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold">PACKAGE NAME</label>
                            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. 3-Day Gorilla Trek">
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
                            <label class="small font-weight-bold">LOCATION</label>
                            <input type="text" wire:model="location" class="form-control">
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

                {{-- Gallery --}}
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold">Gallery Images</h5></div>
                    <div class="card-body">
                        <input type="file" wire:model="gallery_images" multiple class="form-control mb-3">
                        <div wire:loading wire:target="gallery_images" class="text-pink small mb-2">Processing...</div>
                        @if($gallery_images)
                            <div class="row px-2">
                                @foreach($gallery_images as $image)
                                    <div class="col-3 p-1">
                                        <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded border">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                {{-- Featured Image --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <label class="small font-weight-bold d-block text-left mb-2">FEATURED IMAGE</label>
                        @if ($featured_image)
                            <img src="{{ $featured_image->temporaryUrl() }}" class="featured-preview mb-2">
                        @endif
                        <input type="file" wire:model="featured_image" class="form-control">
                        @error('featured_image') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Description (Trix) --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <label class="small font-weight-bold text-muted">DESCRIPTION</label>
                        <div wire:ignore x-data="{ value: @entangle('description') }" @trix-change="value = $event.target.value">
                            <trix-editor class="trix-content"></trix-editor>
                        </div>
                    </div>
                </div>

                {{-- Itinerary --}}
                <div class="itinerary-section">
                    <h5 class="font-weight-bold mb-3">Itinerary Details</h5>
                    @foreach($itinerary as $index => $day)
                        <div class="card mb-3 border-pink shadow-sm" wire:key="day-{{ $index }}">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge badge-primary">DAY {{ $day['day_number'] }}</span>
                                    <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                                </div>
                                <input type="text" wire:model="itinerary.{{ $index }}.title" class="form-control mb-2 font-weight-bold" placeholder="Day Title">
                                <textarea wire:model="itinerary.{{ $index }}.activities" class="form-control mb-2" rows="2" placeholder="Activities"></textarea>
                                <div class="row">
                                    <div class="col-6"><input type="text" wire:model="itinerary.{{ $index }}.meals" class="form-control form-control-sm" placeholder="Meals"></div>
                                    <div class="col-6"><input type="text" wire:model="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm" placeholder="Lodge"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addDay" class="btn btn-outline-pink btn-block mb-4 shadow-sm">
                        <i class="fas fa-plus mr-1"></i> ADD DAY
                    </button>
                </div>

                <button type="submit" class="btn btn-pink btn-lg btn-block shadow-lg">
                    <span wire:loading.remove wire:target="save">CREATE PACKAGE</span>
                    <span wire:loading wire:target="save">SAVING...</span>
                </button>
            </div>
        </div>
    </form>
</div>