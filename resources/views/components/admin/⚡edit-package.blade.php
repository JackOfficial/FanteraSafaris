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
            'id' => $day->id ?? Str::random(8),
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
        // Reset activeDay in Alpine via dispatch if needed, or let it stay null
        $this->dispatch('order-updated');
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
        $original = $this->itinerary[$index];
        $newDay = $original;
        $newDay['id'] = Str::random(8);
        
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
            'destination_id' => 'required',
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

            $this->package->itineraries()->delete();
            foreach ($this->itinerary as $day) { 
                $this->package->itineraries()->create($day); 
            }
        });

        return redirect()->route('admin.packages.index');
    }
}; ?>

<div x-data="{ activeDay: null }" @order-updated.window="activeDay = null">
    @section('title', 'Edit Safari: ' . $package->name)

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
        <style>
            .border-pink { border-left: 4px solid #e83e8c !important; }
            .text-pink { color: #e83e8c !important; }
            .btn-pink { background-color: #e83e8c; color: white; border: none; }
            .btn-pink:hover { background-color: #d81b60; color: white; }
            .itinerary-header { background: #f8f9fa; border-bottom: 1px solid #eee; }
            .drag-handle { cursor: grab; padding: 15px; color: #ccc; display: flex; align-items: center; }
            .drag-handle:active { cursor: grabbing; }
            .itinerary-scroll-container::-webkit-scrollbar { width: 6px; }
            .itinerary-scroll-container::-webkit-scrollbar-thumb { background: #e83e8c; border-radius: 10px; }
            [x-cloak] { display: none !important; }
            .sortable-ghost { opacity: 0.4; background-color: #ffdce5 !important; border: 2px dashed #e83e8c !important; }
        </style>
    @endpush

    <section class="content">
        <div class="container-fluid pt-3">
            <form wire:submit="save">
                <div class="row">
                    {{-- Left Col: Basics --}}
                    <div class="col-md-5">
                        <div class="card card-outline card-pink shadow-sm">
                            <div class="card-header bg-white"><h5 class="mb-0 font-weight-bold text-pink">Package Info</h5></div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label class="small font-weight-bold text-muted">NAME</label>
                                    <input type="text" wire:model="name" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-6"><label class="small font-weight-bold">PRICE</label><input type="number" wire:model="price" class="form-control"></div>
                                    <div class="col-6"><label class="small font-weight-bold">DAYS</label><input type="number" wire:model="duration_days" class="form-control"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Col: Itinerary --}}
                    <div class="col-md-7">
                        <div class="itinerary-builder">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="font-weight-bold mb-0">Itinerary Builder</h5>
                                <span class="badge badge-pink px-3 shadow-sm">{{ count($itinerary) }} Days</span>
                            </div>

                            <div id="itinerary-list" class="itinerary-scroll-container mb-3" 
                                 x-init="
                                    setTimeout(() => {
                                        new Sortable($el, {
                                            animation: 150,
                                            handle: '.drag-handle',
                                            ghostClass: 'sortable-ghost',
                                            filter: 'input, textarea, button, .ignore-drag',
                                            preventOnFilter: false, {{-- Allow clicks to pass through filtered elements --}}
                                            onEnd: (evt) => {
                                                let items = Array.from($el.children).map(el => el.getAttribute('data-index'));
                                                $wire.updateOrder(items);
                                            }
                                        });
                                    }, 200);
                                 "
                                 style="max-height: 500px; overflow-y: auto; padding: 10px; background: #fdfdfd; border: 1px solid #eee; border-radius: 8px;">
                                
                                @foreach($itinerary as $index => $day)
                                    <div class="card mb-2 border-pink shadow-sm" wire:key="day-{{ $day['id'] }}" data-index="{{ $index }}">
                                        <div class="card-header p-0 d-flex align-items-center justify-content-between bg-light">
                                            
                                            <div class="d-flex align-items-center flex-grow-1">
                                                {{-- Handle --}}
                                                <div class="drag-handle">
                                                    <i class="fas fa-grip-vertical"></i>
                                                </div>
                                                
                                                {{-- Toggle Text --}}
                                                <div class="py-3 flex-grow-1 ignore-drag" style="cursor: pointer;" 
                                                     @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                                    <span class="badge badge-pink mr-2">DAY {{ $day['day_number'] }}</span>
                                                    <span class="small font-weight-bold text-uppercase text-dark">
                                                        {{ $day['title'] ?: 'New Safari Day' }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Toggle Icon --}}
                                            <div class="pr-3 ignore-drag" style="cursor: pointer;" 
                                                 @click="activeDay = (activeDay === {{ $index }} ? null : {{ $index }})">
                                                <i class="fas fa-chevron-down text-muted" 
                                                   :class="activeDay === {{ $index }} ? 'fa-rotate-180 text-pink' : ''" 
                                                   style="transition: 0.3s"></i>
                                            </div>
                                        </div>

                                        <div class="card-body p-3 bg-white" x-show="activeDay === {{ $index }}" x-cloak>
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-muted">DAY TITLE</label>
                                                <input type="text" wire:model.blur="itinerary.{{ $index }}.title" class="form-control form-control-sm border-0 bg-light">
                                            </div>
                                            <div class="form-group mb-2">
                                                <label class="small font-weight-bold text-muted">ACTIVITIES</label>
                                                <textarea wire:model.defer="itinerary.{{ $index }}.activities" class="form-control" rows="2"></textarea>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-6"><label class="small font-weight-bold text-muted">MEALS</label><input type="text" wire:model.defer="itinerary.{{ $index }}.meals" class="form-control form-control-sm"></div>
                                                <div class="col-6"><label class="small font-weight-bold text-muted">ACCOM.</label><input type="text" wire:model.defer="itinerary.{{ $index }}.accommodation" class="form-control form-control-sm"></div>
                                            </div>
                                            <div class="d-flex justify-content-end border-top pt-2">
                                                <button type="button" wire:click="duplicateDay({{ $index }})" 
                                                        @click="activeDay = {{ $index + 1 }}" 
                                                        class="btn btn-xs btn-outline-info mr-2">
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
                            
                            <button type="button" wire:click="addDay" @click="activeDay = {{ count($itinerary) }}" 
                                    class="btn btn-outline-pink btn-block mb-4 shadow-sm font-weight-bold">
                                <i class="fas fa-plus-circle mr-2"></i> ADD NEXT DAY
                            </button>
                        </div>

                        <button type="submit" class="btn btn-pink btn-lg btn-block shadow-lg py-3 font-weight-bold mb-5">
                            UPDATE PACKAGE
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
        <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @endpush
</div>