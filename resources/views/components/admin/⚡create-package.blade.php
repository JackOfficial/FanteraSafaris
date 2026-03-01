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

    public $name, $price, $discount_per_person = 0, $duration_days = 1;
    public $destination_ids = [], $status = 'draft', $safari_category_ids = [], $description; 
    public $featured_image, $gallery_images = [], $itinerary = [];

    public function mount() {
        if (empty($this->itinerary)) {
            $this->addDay();
        }
    }

    public function addDay() {
        $id = uniqid();
        $this->itinerary[] = [
            'id' => $id,
            'day_number' => count($this->itinerary) + 1,
            'title' => '',
            'activities' => '',
            'meals' => 'Breakfast, Lunch, Dinner',
            'accommodation' => ''
        ];
        $this->duration_days = count($this->itinerary);
        $this->dispatch('day-added', id: $id);
    }

    public function duplicateDay($index) {
        $newId = uniqid();
        $dayToCopy = $this->itinerary[$index];
        $dayToCopy['id'] = $newId;
        
        array_splice($this->itinerary, $index + 1, 0, [$dayToCopy]);
        $this->reorderDays();
        
        $this->dispatch('day-added', id: $newId);
    }

    public function removeDay($index) {
        array_splice($this->itinerary, $index, 1);
        $this->reorderDays();
    }

    public function clearAllDays() {
        $this->itinerary = [];
        $this->duration_days = 0;
    }

    protected function reorderDays() {
        $this->itinerary = array_values($this->itinerary);
        foreach ($this->itinerary as $k => $v) {
            $this->itinerary[$k]['day_number'] = $k + 1;
        }
        $this->duration_days = count($this->itinerary);
    }

    public function removeGalleryImage($index) {
        array_splice($this->gallery_images, $index, 1);
    }

    public function save() {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'destination_ids' => 'required|array|min:1',
            'itinerary.*.title' => 'required',
        ]);

        DB::transaction(function () {
            $package = SafariPackage::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'price' => $this->price,
                'discount_per_person' => $this->discount_per_person,
                'duration_days' => count($this->itinerary),
                'status' => $this->status,
                'description' => $this->description,
            ]);

            $package->destinations()->sync($this->destination_ids);
            $package->categories()->sync($this->safari_category_ids);

            if ($this->featured_image) {
                $package->photos()->create([
                    'path' => $this->featured_image->store('safaris/featured', 'public'),
                    'type' => 'featured'
                ]);
            }

            foreach ($this->itinerary as $day) {
                // Remove the temp 'id' before saving to DB if it's not a DB column
                $data = $day;
                unset($data['id']);
                $package->itineraries()->create($data);
            }
        });

        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data="{ 
    activeId: '{{ $itinerary[0]['id'] ?? '' }}',
    basePrice: @entangle('price'), 
    discount: @entangle('discount_per_person'),
    calculate(people) {
        if(!this.basePrice) return 0;
        let disc = (people - 1) * this.discount;
        let price = this.basePrice * (1 - (disc / 100));
        return (Math.max(price, this.basePrice * 0.5) * people).toLocaleString();
    }
}" 
@day-added.window="activeId = $event.detail.id">

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <style>
        .btn-pink { background: #e83e8c; color: white; border: none; transition: 0.2s; }
        .btn-pink:hover { background: #be185d; color: white; }
        .text-pink { color: #e83e8c; }
        .card-pink { border-top: 4px solid #e83e8c; }
        .itinerary-card { transition: transform 0.2s; border-left: 3px solid transparent; }
        .itinerary-card.active { border-left-color: #e83e8c; transform: translateX(5px); }
        .itinerary-scroll { max-height: 650px; overflow-y: auto; scroll-behavior: smooth; padding-right: 5px; }
        .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
        [x-cloak] { display: none !important; }
        .trix-content { border: 1px solid #ced4da !important; border-radius: 0.25rem; min-height: 150px; }
    </style>
    @endpush

    <div class="container-fluid pt-4 pb-5">
        <form wire:submit="save">
            <div class="row">
                {{-- Left: Settings --}}
                <div class="col-md-4">
                    <div class="card shadow-sm card-pink sticky-top" style="top: 20px;">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3">General Information</h6>
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold">PACKAGE NAME</label>
                                <input type="text" wire:model="name" class="form-control form-control-lg">
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-muted">BASE PRICE ($)</label>
                                    <input type="number" wire:model.live="price" class="form-control">
                                </div>
                                <div class="col-6">
                                    <label class="small font-weight-bold text-muted">DISC / PER PERSON</label>
                                    <input type="number" wire:model.live="discount_per_person" class="form-control">
                                </div>
                            </div>

                            <div class="p-3 rounded mb-3" style="background: #fff5f8; border: 1px dashed #f9a8d4;" x-show="basePrice > 0">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>2 People Total:</span> <strong x-text="'$' + calculate(2)"></strong>
                                </div>
                                <div class="d-flex justify-content-between small text-success">
                                    <span>4 People Total:</span> <strong x-text="'$' + calculate(4)"></strong>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="small font-weight-bold">DESTINATIONS</label>
                                <select wire:model="destination_ids" class="form-control" multiple style="height: 120px;">
                                    @foreach(\App\Models\Destination::all() as $dest)
                                        <option value="{{ $dest->id }}">{{ $dest->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-pink btn-block py-3 font-weight-bold rounded-pill shadow">
                                <i class="fas fa-save mr-2"></i> SAVE PACKAGE
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Right: Content --}}
                <div class="col-md-8">
                    {{-- Media Section --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold">Visual Assets</span>
                            <span class="badge badge-pill badge-light text-muted small">Images are required</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="small font-weight-bold text-muted d-block">FEATURED IMAGE</label>
                                    <div class="position-relative bg-light rounded overflow-hidden shadow-sm" style="height: 200px; border: 2px dashed #ddd;">
                                        @if($featured_image)
                                            <img src="{{ $featured_image->temporaryUrl() }}" class="w-100 h-100 object-fit-cover">
                                        @endif
                                        <label class="btn btn-sm btn-white position-absolute shadow-sm" style="bottom: 10px; right: 10px;">
                                            Change <input type="file" wire:model="featured_image" class="d-none">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="small font-weight-bold text-muted">GALLERY</label>
                                    <div class="gallery-grid">
                                        @foreach($gallery_images as $idx => $img)
                                            <div class="position-relative" style="height: 100px;">
                                                <img src="{{ $img->temporaryUrl() }}" class="w-100 h-100 rounded border object-fit-cover">
                                                <button type="button" wire:click="removeGalleryImage({{ $idx }})" class="btn btn-xs btn-danger position-absolute" style="top:-5px; right:-5px; border-radius: 50%; width: 20px; height: 20px; padding:0;">×</button>
                                            </div>
                                        @endforeach
                                        <label class="d-flex align-items-center justify-content-center bg-light rounded border-dashed" style="height: 100px; cursor:pointer; border: 2px dashed #ddd;">
                                            <i class="fas fa-plus text-muted"></i>
                                            <input type="file" wire:model.live="gallery_images" multiple class="d-none">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Itinerary Section --}}
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h6 class="font-weight-bold mb-0"><i class="fas fa-route text-pink mr-2"></i> Itinerary Journey</h6>
                            <div class="btn-group">
                                <button type="button" wire:click="addDay" class="btn btn-sm btn-pink rounded-pill px-3">
                                    <i class="fas fa-plus mr-1"></i> Add Day
                                </button>
                            </div>
                        </div>
                        <div class="card-body bg-light p-2">
                            <div class="itinerary-scroll">
                                @forelse($itinerary as $index => $day)
                                    <div class="card mb-2 itinerary-card shadow-sm border-0" 
                                         :class="activeId === '{{ $day['id'] }}' ? 'active' : ''"
                                         wire:key="itinerary-item-{{ $day['id'] }}">
                                        
                                        <div class="p-3 d-flex align-items-center" 
                                             @click="activeId = (activeId === '{{ $day['id'] }}' ? null : '{{ $day['id'] }}')" 
                                             style="cursor: pointer;">
                                            <div class="rounded-circle bg-pink text-white mr-3 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; flex-shrink:0;">
                                                {{ $day['day_number'] }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <span class="font-weight-bold small">{{ $day['title'] ?: 'Draft Day Title' }}</span>
                                            </div>
                                            <i class="fas fa-chevron-down text-muted transition-icon" :style="activeId === '{{ $day['id'] }}' ? 'transform: rotate(180deg)' : ''"></i>
                                        </div>

                                        <div class="card-body pt-0" x-show="activeId === '{{ $day['id'] }}'" x-collapse x-cloak>
                                            <hr class="mt-0">
                                            <div class="form-group mb-3">
                                                <label class="small font-weight-bold">DAY HEADING</label>
                                                <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control bg-light" placeholder="e.g. Arrival in Arusha & Briefing">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="small font-weight-bold">ACTIVITIES</label>
                                                <textarea wire:model.blur="itinerary.{{ $index }}.activities" class="form-control bg-light" rows="3"></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="small font-weight-bold">ACCOMMODATION</label>
                                                    <input type="text" wire:model.blur="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm bg-light">
                                                </div>
                                                <div class="col-6">
                                                    <label class="small font-weight-bold">MEALS</label>
                                                    <input type="text" wire:model.blur="itinerary.{{ $index }}.meals" class="form-control form-control-sm bg-light">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mt-3">
                                                <button type="button" wire:click="duplicateDay({{ $index }})" class="btn btn-link text-pink btn-sm mr-3">Duplicate</button>
                                                <button type="button" onclick="confirm('Delete this day?') || event.stopImmediatePropagation()" wire:click="removeDay({{ $index }})" class="btn btn-link text-danger btn-sm p-0">Delete Day</button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <p class="text-muted">No days added yet. Start by adding Day 1.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-white font-weight-bold">Full Package Description</div>
                        <div class="card-body">
                            <div wire:ignore x-data="{ content: @entangle('description') }" x-init="$refs.editor.value = content" @trix-change="content = $event.target.value">
                                <trix-editor x-ref="editor" class="trix-content"></trix-editor>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @endpush
</div>