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
            'id' => (string) ($day->id ?? Str::random(8)), // CRITICAL: Unique ID for wire:key
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
            if (isset($this->itinerary[$oldIndex])) {
                $item = $this->itinerary[$oldIndex];
                $item['day_number'] = $newIndex + 1;
                $newItinerary[] = $item;
            }
        }
        $this->itinerary = $newItinerary;
    }

    public function addDay()
    {
        $this->itinerary[] = [
            'id' => Str::random(8),
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
        $newDay['id'] = Str::random(8); // Must have a NEW unique ID
        
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
        $this->validate(['name' => 'required', 'price' => 'required|numeric']);
        DB::transaction(function () {
            $this->package->update([
                'name' => $this->name, 'price' => $this->price, 
                'duration_days' => $this->duration_days, 'description' => $this->description
            ]);
            $this->package->itineraries()->delete();
            foreach ($this->itinerary as $day) { 
                $this->package->itineraries()->create(collect($day)->except('id')->toArray()); 
            }
        });
        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data="{ activeDay: null }">
    @push('styles')
        <style>
            .border-pink { border-left: 4px solid #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .drag-handle { cursor: grab; padding: 15px; color: #ccc; }
            .itinerary-scroll-container { max-height: 550px; overflow-y: auto; padding: 5px; }
            .sortable-ghost { opacity: 0.3; background: #fff0f5 !important; border: 1px dashed #e83e8c !important; }
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    <section class="content">
        <div class="container-fluid pt-3">
            <form wire:submit="save">
                <div class="row">
                    {{-- ITINERARY COLUMN --}}
                    <div class="col-md-12">
                        <div class="itinerary-builder">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="font-weight-bold">Itinerary Builder</h4>
                                <span class="badge badge-pink px-3 py-2 shadow-sm">{{ count($itinerary) }} DAYS</span>
                            </div>

                            <div id="itinerary-list" class="itinerary-scroll-container mb-3" 
                                 x-init="
                                    new Sortable($el, {
                                        animation: 150,
                                        handle: '.drag-handle',
                                        ghostClass: 'sortable-ghost',
                                        filter: '.ignore-drag', {{-- Elements that should NOT trigger drag --}}
                                        preventOnFilter: false,
                                        onEnd: (evt) => {
                                            let items = Array.from($el.children).map(el => el.getAttribute('data-index'));
                                            $wire.updateOrder(items);
                                        }
                                    });
                                 ">
                                
                                @foreach($itinerary as $index => $day)
                                    <div class="card mb-2 border-pink shadow-sm" wire:key="day-v2-{{ $day['id'] }}" data-index="{{ $index }}">
                                        
                                        {{-- HEADER --}}
                                        <div class="card-header p-0 d-flex align-items-center justify-content-between bg-light">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <div class="drag-handle">
                                                    <i class="fas fa-grip-vertical"></i>
                                                </div>
                                                
                                                <div class="py-3 flex-grow-1 ignore-drag" style="cursor: pointer;" 
                                                     @click.stop="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                                    <span class="badge badge-pink mr-2">DAY {{ $day['day_number'] }}</span>
                                                    <span class="small font-weight-bold text-uppercase text-dark">
                                                        {{ $day['title'] ?: 'Enter Title...' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="pr-3 ignore-drag" style="cursor: pointer;" 
                                                 @click.stop="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                                <i class="fas fa-chevron-down text-muted" 
                                                   :class="activeDay === {{ $index }} ? 'fa-rotate-180 text-pink' : ''" 
                                                   style="transition: 0.3s"></i>
                                            </div>
                                        </div>

                                        {{-- BODY --}}
                                        <div class="card-body p-3 bg-white" x-show="activeDay === {{ $index }}" x-cloak>
                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <label class="small font-weight-bold">DAY TITLE</label>
                                                    <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control border-0 bg-light font-weight-bold">
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <label class="small font-weight-bold">ACTIVITIES</label>
                                                    <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control" rows="3"></textarea>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="small font-weight-bold">MEALS</label>
                                                    <input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="small font-weight-bold">ACCOMMODATION</label>
                                                    <input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-end border-top pt-2">
                                                <button type="button" wire:click="duplicateDay({{ $index }})" 
                                                        @click="activeDay = {{ $index + 1 }}" 
                                                        class="btn btn-sm btn-outline-info mr-2">
                                                    <i class="fas fa-copy mr-1"></i> Duplicate
                                                </button>
                                                <button type="button" wire:click="removeDay({{ $index }})" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash-alt mr-1"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="button" wire:click="addDay" @click="activeDay = {{ count($itinerary) }}" 
                                    class="btn btn-outline-pink btn-block mb-5 font-weight-bold py-2">
                                <i class="fas fa-plus-circle mr-2"></i> ADD NEXT DAY
                            </button>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block shadow-lg py-3 font-weight-bold mb-5">
                            UPDATE SAFARI PACKAGE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endpush
</div>