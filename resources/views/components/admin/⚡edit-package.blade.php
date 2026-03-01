<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SafariPackage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

new class extends Component {
    use WithFileUploads;

    public SafariPackage $package;
    public $name, $price, $duration_days, $description;
    public $itinerary = [];

    public function mount(SafariPackage $package)
    {
        $this->package = $package->load('itineraries');
        $this->name = $package->name;
        $this->price = $package->price;
        $this->duration_days = $package->duration_days;
        $this->description = $package->description;
        
        $this->itinerary = $package->itineraries->sortBy('day_number')->map(fn($day) => [
            'id' => (string) ($day->id ?? Str::random(10)), // Permanent unique ID
            'day_number' => $day->day_number,
            'title' => $day->title,
            'activities' => $day->activities,
            'meals' => $day->meals,
            'accommodation' => $day->accommodation
        ])->toArray();
    }

    /**
     * Re-orders the array based on the drag-and-drop result.
     * $sortedIds is an array of IDs in the new order.
     */
    public function updateOrder($sortedIds)
    {
        $newItinerary = [];
        foreach ($sortedIds as $index => $id) {
            foreach ($this->itinerary as $item) {
                if ($item['id'] == $id) {
                    $item['day_number'] = $index + 1;
                    $newItinerary[] = $item;
                    break;
                }
            }
        }
        $this->itinerary = $newItinerary;
    }

    public function addDay()
    {
        $this->itinerary[] = [
            'id' => Str::random(10),
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
        $newDay['id'] = Str::random(10); // Brand new ID
        
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
        $this->validate(['name' => 'required', 'price' => 'required']);
        
        DB::transaction(function () {
            $this->package->update(['name' => $this->name, 'price' => $this->price]);
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
            .border-pink { border-left: 5px solid #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .drag-handle { cursor: grab; padding: 15px; color: #ced4da; }
            .drag-handle:active { cursor: grabbing; }
            .itinerary-scroll-container { max-height: 600px; overflow-y: auto; padding: 10px; border-radius: 8px; background: #fafafa; }
            .sortable-ghost { opacity: 0.2; background: #e83e8c !important; }
            [x-cloak] { display: none !important; }
        </style>
    @endpush

    <section class="content">
        <div class="container-fluid pt-4">
            <form wire:submit="save">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card p-3 shadow-sm">
                            <h5 class="text-pink font-weight-bold">Safari Details</h5>
                            <label class="small font-weight-bold">NAME</label>
                            <input type="text" wire:model="name" class="form-control mb-3">
                            <label class="small font-weight-bold">PRICE ($)</label>
                            <input type="number" wire:model="price" class="form-control mb-3">
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="itinerary-builder">
                            <h5 class="font-weight-bold mb-3">Itinerary ({{ count($itinerary) }} Days)</h5>

                            {{-- WIRE:IGNORE is key to keeping Drag and Drop stable --}}
                            <div wire:ignore 
                                 id="itinerary-list" 
                                 class="itinerary-scroll-container mb-3" 
                                 x-init="
                                    new Sortable($el, {
                                        animation: 150,
                                        handle: '.drag-handle',
                                        ghostClass: 'sortable-ghost',
                                        onEnd: (evt) => {
                                            let sortedIds = Array.from($el.children).map(el => el.getAttribute('data-id'));
                                            $wire.updateOrder(sortedIds);
                                        }
                                    });
                                 ">
                                
                                @foreach($itinerary as $index => $day)
                                    {{-- Use data-id instead of data-index for the sort logic --}}
                                    <div class="card mb-2 border-pink shadow-sm" 
                                         wire:key="day-{{ $day['id'] }}" 
                                         data-id="{{ $day['id'] }}">
                                        
                                        <div class="card-header p-0 d-flex align-items-center bg-white">
                                            <div class="drag-handle">
                                                <i class="fas fa-grip-vertical"></i>
                                            </div>
                                            
                                            <div class="py-3 flex-grow-1" 
                                                 style="cursor: pointer;" 
                                                 @click="activeDay = (activeDay === '{{ $day['id'] }}' ? null : '{{ $day['id'] }}')">
                                                <span class="badge badge-pink mr-2">DAY {{ $day['day_number'] }}</span>
                                                <span class="small font-weight-bold text-uppercase text-dark">
                                                    {{ $day['title'] ?: 'New Safari Day' }}
                                                </span>
                                            </div>

                                            <div class="pr-3" style="cursor: pointer;" @click="activeDay = (activeDay === '{{ $day['id'] }}' ? null : '{{ $day['id'] }}')">
                                                <i class="fas fa-chevron-down text-muted" :class="activeDay === '{{ $day['id'] }}' ? 'fa-rotate-180 text-pink' : ''"></i>
                                            </div>
                                        </div>

                                        <div class="card-body p-3 bg-white" x-show="activeDay === '{{ $day['id'] }}'" x-cloak>
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold">DAY TITLE</label>
                                                <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control bg-light border-0">
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold">ACTIVITIES</label>
                                                <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control" rows="2"></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-6"><label class="small font-weight-bold">MEALS</label><input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control form-control-sm"></div>
                                                <div class="col-6"><label class="small font-weight-bold">ACCOM.</label><input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm"></div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-end border-top mt-3 pt-2">
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
                            
                            <button type="button" wire:click="addDay" class="btn btn-outline-pink btn-block mb-5 font-weight-bold">
                                <i class="fas fa-plus-circle mr-2"></i> ADD NEXT DAY
                            </button>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block shadow py-3 font-weight-bold">
                            UPDATE PACKAGE
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