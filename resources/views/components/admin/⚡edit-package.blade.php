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
            'id' => $day->id, // Keep ID for stable keys
            'day_number' => $day->day_number,
            'title' => $day->title,
            'activities' => $day->activities,
            'meals' => $day->meals,
            'accommodation' => $day->accommodation
        ])->toArray();
    }

    public function updateOrder($items)
    {
        $newItinerary = [];
        foreach ($items as $newIndex => $oldIndex) {
            $item = $this->itinerary[$oldIndex];
            $item['day_number'] = $newIndex + 1;
            $newItinerary[] = $item;
        }
        $this->itinerary = $newItinerary;
    }

    public function addDay()
    {
        $this->itinerary[] = [
            'id' => Str::random(8), // Temporary ID for wire:key stability
            'day_number' => count($this->itinerary) + 1,
            'title' => '', 
            'activities' => '', 
            'meals' => 'Breakfast, Lunch, Dinner', 
            'accommodation' => ''
        ];
    }

    public function duplicateDay($index)
    {
        $original = $this->itinerary[$index];
        $newDay = $original;
        $newDay['id'] = Str::random(8); // New unique key
        
        // Insert right after the current day
        array_splice($this->itinerary, $index + 1, 0, [$newDay]);
        
        // Re-index all days
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
        // ... (Same save logic as before)
        $this->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'destination_id' => 'required',
        ]);

        DB::transaction(function () {
            $this->package->update([
                'name' => $this->name, 
                'price' => $this->price, 
                'duration_days' => $this->duration_days,
                'destination_id' => $this->destination_id, 
                'status' => $this->status,
                'description' => $this->description,
            ]);

            $this->package->itineraries()->delete();
            foreach ($this->itinerary as $day) { 
                $this->package->itineraries()->create($day); 
            }
        });

        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data="{ activeDay: null }">
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <style>
            .border-pink { border-left: 4px solid #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; }
            .itinerary-scroll-container { max-height: 500px; overflow-y: auto; background: #fdfdfd; border: 1px solid #eee; border-radius: 8px; padding: 10px; }
            .drag-handle { cursor: grab; padding: 10px; color: #adb5bd; }
            .sortable-ghost { opacity: 0.3; background: #f8d7da !important; border: 2px dashed #e83e8c !important; }
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    <div class="row">
        <div class="col-md-5">
            {{-- Basics and Gallery cards here... --}}
            <div class="card p-3">
                <label>Package Name</label>
                <input type="text" wire:model="name" class="form-control mb-3">
                <div class="row">
                    <div class="col-6"><label>Price</label><input type="number" wire:model="price" class="form-control"></div>
                    <div class="col-6"><label>Days</label><input type="number" wire:model="duration_days" class="form-control"></div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="itinerary-builder">
                <div class="d-flex justify-content-between mb-2">
                    <h5 class="font-weight-bold">Itinerary Builder</h5>
                    <span class="badge badge-pink px-2">{{ count($itinerary) }} Days</span>
                </div>

                <div id="itinerary-list" class="itinerary-scroll-container mb-3" 
                     x-init="
                        setTimeout(() => {
                            new Sortable($el, {
                                animation: 150,
                                handle: '.drag-handle',
                                ghostClass: 'sortable-ghost',
                                onEnd: (evt) => {
                                    let items = Array.from($el.children).map(el => el.getAttribute('data-index'));
                                    $wire.updateOrder(items);
                                }
                            });
                        }, 100);
                     ">
                    
                    @foreach($itinerary as $index => $day)
                        <div class="card mb-2 border-pink shadow-sm" 
                             wire:key="day-{{ $day['id'] ?? $index }}" 
                             data-index="{{ $index }}">
                            
                            <div class="card-header p-0 d-flex align-items-center justify-content-between bg-light">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="drag-handle"><i class="fas fa-grip-vertical"></i></div>
                                    <div class="py-3 flex-grow-1" style="cursor: pointer;" @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                        <span class="badge badge-pink mr-2">DAY {{ $day['day_number'] }}</span>
                                        <span class="small font-weight-bold text-uppercase">{{ $day['title'] ?: 'New Day' }}</span>
                                    </div>
                                </div>
                                <div class="pr-3">
                                    <i class="fas fa-chevron-down text-muted" :class="activeDay === {{ $index }} ? 'fa-rotate-180 text-pink' : ''"></i>
                                </div>
                            </div>

                            <div class="card-body p-3 bg-white" x-show="activeDay === {{ $index }}" x-cloak>
                                <div class="form-group mb-2">
                                    <label class="small font-weight-bold">DAY TITLE</label>
                                    <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control form-control-sm">
                                </div>
                                <div class="form-group mb-2">
                                    <label class="small font-weight-bold">ACTIVITIES</label>
                                    <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-6"><label class="small font-weight-bold">MEALS</label><input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control form-control-sm"></div>
                                    <div class="col-6"><label class="small font-weight-bold">ACCOMMODATION</label><input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm"></div>
                                </div>
                                
                                <div class="d-flex justify-content-end border-top pt-2">
                                    <button type="button" wire:click="duplicateDay({{ $index }})" class="btn btn-xs btn-outline-info mr-2">
                                        <i class="fas fa-copy"></i> Duplicate
                                    </button>
                                    <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-xs btn-outline-danger">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <button type="button" wire:click="addDay" class="btn btn-outline-pink btn-block mb-4 font-weight-bold">
                    <i class="fas fa-plus-circle mr-2"></i> ADD DAY
                </button>
            </div>

            <button type="button" wire:click="save" class="btn btn-pink btn-lg btn-block shadow-lg py-3">
                UPDATE PACKAGE
            </button>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    @endpush
</div>